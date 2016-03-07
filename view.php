<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints attendance info for particular user
 *
 * @package    mod_attendance
 * @copyright  2011 Artem Andreev <andreev.artem@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');

$pageparams = new att_view_page_params();

$id                     = required_param('id', PARAM_INT);
$pageparams->studentid  = optional_param('studentid', null, PARAM_INT);
$pageparams->mode       = optional_param('mode', att_view_page_params::MODE_THIS_COURSE, PARAM_INT);
$pageparams->view       = optional_param('view', null, PARAM_INT);
$pageparams->curdate    = optional_param('curdate', null, PARAM_INT);
$session                = optional_param('session', null, PARAM_INT);
$word                   = optional_param('word', null, PARAM_TEXT);

$cm             = get_coursemodule_from_id('attendance', $id, 0, false, MUST_EXIST);
$course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$attendance     = $DB->get_record('attendance', array('id' => $cm->instance), '*', MUST_EXIST);

require_login($course, true, $cm);

$pageparams->init($cm);
$att = new attendance($attendance, $cm, $course, $PAGE->context, $pageparams);

/*
* Submit new session
*/
//---E.Rasvet code start-----//
if (!empty($word)){
    $add = new stdClass;
    $add->offcampus = 0;
  
    $configIPrange = $attendance->ips;
    if (!empty($configIPrange)) {
      $userIP = attendance_ip_detect();
      if (!attendance_checkip($configIPrange, $userIP)) 
        $add->offcampus = 1;
    }
    
    if ($sess = $att->get_session_byid($session)) {
      $status = att_get_statuse($attendance->id, 'P');
      
      if (!empty($sess->late) && (($sess->sessdate + ($sess->late*60)) < time()))
        $status = att_get_statuse($attendance->id, 'L');
    }
    
    $add->sessid = $session;
    $add->status = $status->id;
    
    if (!$att->check_session_keyword($session, $word)) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('attendanceforthecourse', 'attendance').' :: ' .$course->fullname);
        echo $OUTPUT->confirm("Incorrect WORD", '/mod/attendance/view.php?id='.$cm->id, '/mod/attendance/view.php?id='.$cm->id);
        echo $OUTPUT->footer();
        die();
    }
    
    $success = $att->take_from_student($add);
}
//---E.Rasvet code end-----//


// Not specified studentid for displaying attendance?
// Redirect to appropriate page if can.
if (!$pageparams->studentid) {
    if ($att->perm->can_manage() || $att->perm->can_take() || $att->perm->can_change()) {
        redirect($att->url_manage());
    } else if ($att->perm->can_view_reports()) {
        redirect($att->url_report());
    }
}

$att->perm->require_view_capability();

$PAGE->set_url($att->url_view());
$PAGE->set_title($course->shortname. ": ".$att->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_cacheable(true);
$PAGE->navbar->add(get_string('attendancereport', 'attendance'));

$output = $PAGE->get_renderer('mod_attendance');

if (isset($pageparams->studentid) && $USER->id != $pageparams->studentid) {
    // Only users with proper permissions should be able to see any user's individual report.
    require_capability('mod/attendance:viewreports', $PAGE->context);
    $userid = $pageparams->studentid;
} else {
    // A valid request to see another users report has not been sent, show the user's own.
    $userid = $USER->id;
}

$userdata = new attendance_user_data($att, $userid);
$userdata->attendanceID = $attendance->id;
$userdata->pageID = $id;

echo $output->header();

echo $output->render($userdata);

echo $output->footer();
