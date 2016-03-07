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
 * Library of functions and constants for module attendance
 *
 * @package   mod_attendance
 * @copyright  2011 Artem Andreev <andreev.artem@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */




/**
 * Returns the information if the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function attendance_supports($feature) {
    switch($feature) {
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        // Artem Andreev: AFAIK it's not tested.
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        default:
            return null;
    }
}

function att_add_default_statuses($attid) {
    global $DB;

    $statuses = $DB->get_recordset('attendance_statuses', array('attendanceid'=> 0), 'id');
    foreach ($statuses as $st) {
        $rec = $st;
        $rec->attendanceid = $attid;
        $DB->insert_record('attendance_statuses', $rec);
    }
    $statuses->close();
}

function attendance_add_instance($attendance) {
    global $DB;

    $attendance->timemodified = time();

    $attendance->id = $DB->insert_record('attendance', $attendance);

    att_add_default_statuses($attendance->id);

    attendance_grade_item_update($attendance);

    return $attendance->id;
}


function attendance_update_instance($attendance) {
    global $DB;

    $attendance->timemodified = time();
    $attendance->id = $attendance->instance;

    if (! $DB->update_record('attendance', $attendance)) {
        return false;
    }

    attendance_grade_item_update($attendance);

    return true;
}


function attendance_delete_instance($id) {
    global $DB;

    if (! $attendance = $DB->get_record('attendance', array('id'=> $id))) {
        return false;
    }

    if ($sessids = array_keys($DB->get_records('attendance_sessions', array('attendanceid'=> $id), '', 'id'))) {
        $DB->delete_records_list('attendance_log', 'sessionid', $sessids);
        $DB->delete_records('attendance_sessions', array('attendanceid'=> $id));
    }
    $DB->delete_records('attendance_statuses', array('attendanceid'=> $id));

    $DB->delete_records('attendance', array('id'=> $id));

    attendance_grade_item_delete($attendance);

    return true;
}

function attendance_delete_course($course, $feedback=true) {
    global $DB;

    $attids = array_keys($DB->get_records('attendance', array('course'=> $course->id), '', 'id'));
    $sessids = array_keys($DB->get_records_list('attendance_sessions', 'attendanceid', $attids, '', 'id'));
    if ($sessids) {
        $DB->delete_records_list('attendance_log', 'sessionid', $sessids);
    }
    if ($attids) {
        $DB->delete_records_list('attendance_statuses', 'attendanceid', $attids);
        $DB->delete_records_list('attendance_sessions', 'attendanceid', $attids);
    }
    $DB->delete_records('attendance', array('course'=> $course->id));

    return true;
}

/**
 * Called by course/reset.php
 * @param $mform form passed by reference
 */
function attendance_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'attendanceheader', get_string('modulename', 'attendance'));

    $mform->addElement('static', 'description', get_string('description', 'attendance'),
                                get_string('resetdescription', 'attendance'));
    $mform->addElement('checkbox', 'reset_attendance_log', get_string('deletelogs', 'attendance'));

    $mform->addElement('checkbox', 'reset_attendance_sessions', get_string('deletesessions', 'attendance'));
    $mform->disabledIf('reset_attendance_sessions', 'reset_attendance_log', 'notchecked');

    $mform->addElement('checkbox', 'reset_attendance_statuses', get_string('resetstatuses', 'attendance'));
    $mform->setAdvanced('reset_attendance_statuses');
    $mform->disabledIf('reset_attendance_statuses', 'reset_attendance_log', 'notchecked');
}

/**
 * Course reset form defaults.
 */
function attendance_reset_course_form_defaults($course) {
    return array('reset_attendance_log'=>0, 'reset_attendance_statuses'=>0, 'reset_attendance_sessions'=>0);
}

function attendance_reset_userdata($data) {
    global $DB;

    $status = array();

    $attids = array_keys($DB->get_records('attendance', array('course'=> $data->courseid), '', 'id'));

    if (!empty($data->reset_attendance_log)) {
        $sess = $DB->get_records_list('attendance_sessions', 'attendanceid', $attids, '', 'id');
        if (!empty($sess)) {
            list($sql, $params) = $DB->get_in_or_equal(array_keys($sess));
            $DB->delete_records_select('attendance_log', "sessionid $sql", $params);
            list($sql, $params) = $DB->get_in_or_equal($attids);
            $DB->set_field_select('attendance_sessions', 'lasttaken', 0, "attendanceid $sql", $params);

            $status[] = array(
                'component' => get_string('modulenameplural', 'attendance'),
                'item' => get_string('attendancedata', 'attendance'),
                'error' => false
            );
        }
    }

    if (!empty($data->reset_attendance_statuses)) {
        $DB->delete_records_list('attendance_statuses', 'attendanceid', $attids);
        foreach ($attids as $attid) {
            att_add_default_statuses($attid);
        }

        $status[] = array(
            'component' => get_string('modulenameplural', 'attendance'),
            'item' => get_string('sessions', 'attendance'),
            'error' => false
        );
    }

    if (!empty($data->reset_attendance_sessions)) {
        $DB->delete_records_list('attendance_sessions', 'attendanceid', $attids);

        $status[] = array(
            'component' => get_string('modulenameplural', 'attendance'),
            'item' => get_string('statuses', 'attendance'),
            'error' => false
        );
    }

    return $status;
}
/*
 * Return a small object with summary information about what a
 *  user has done with a given particular instance of this module
 *  Used for user activity reports.
 *  $return->time = the time they did it
 *  $return->info = a short text description
 */
function attendance_user_outline($course, $user, $mod, $attendance) {
    global $CFG;
    require_once(dirname(__FILE__).'/locallib.php');
    require_once($CFG->libdir.'/gradelib.php');

    $grades = grade_get_grades($course->id, 'mod', 'attendance', $attendance->id, $user->id);

    $result = new stdClass();
    if (!empty($grades->items[0]->grades)) {
        $grade = reset($grades->items[0]->grades);
        $result->time = $grade->dategraded;
    } else {
        $result->time = 0;
    }
    if (has_capability('mod/attendance:canbelisted', $mod->context, $user->id)) {
        $statuses = att_get_statuses($attendance->id);
        $grade = att_get_user_grade(att_get_user_statuses_stat($attendance->id, $course->startdate,
                                                               $user->id, $mod), $statuses);
        $maxgrade = att_get_user_max_grade(att_get_user_taken_sessions_count($attendance->id, $course->startdate,
                                                                             $user->id, $mod), $statuses);

        $result->info = $grade.' / '.$maxgrade;
    }

    return $result;
}
/*
 * Print a detailed representation of what a  user has done with
 * a given particular instance of this module, for user activity reports.
 *
 */
function attendance_user_complete($course, $user, $mod, $attendance) {
    global $CFG;

    require_once(dirname(__FILE__).'/renderhelpers.php');
    require_once($CFG->libdir.'/gradelib.php');

    if (has_capability('mod/attendance:canbelisted', $mod->context, $user->id)) {
        echo construct_full_user_stat_html_table($attendance, $course, $user, $mod);
    }
}
function attendance_print_recent_activity($course, $isteacher, $timestart) {
    return false;
}

function attendance_cron () {
    global $DB, $CFG;

    require_once($CFG->dirroot.'/mod/attendance/locallib.php');
    
    if ($needtocloseSessions = $DB->get_records_sql("SELECT * FROM {attendance_sessions} WHERE sessdate+duration < :time AND duration > 0 AND finished = 0", array("time" => time()))){
      while(list($k,$sess) = each($needtocloseSessions)){
        $cm             = get_coursemodule_from_instance('attendance', $sess->attendanceid);
        $course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
        $att            = $DB->get_record('attendance', array('id' => $cm->instance), '*', MUST_EXIST);
        
        $pageparams = new att_take_page_params();
        $pageparams->sessionid  = $sess->id;
        $pageparams->grouptype  = $sess->groupid;
        $pageparams->page       = 1;
        $pageparams->perpage    = 1000;
        $pageparams->group = groups_get_activity_group($cm, true);
        $pageparams->init($course->id);
        $pageparams->context = get_context_instance(CONTEXT_MODULE, $cm->id);
        
        $att = new attendance($att, $cm, $course, $pageparams->context, $pageparams);
        
        if ($att->pageparams->grouptype)
            $users = $att->get_users($att->pageparams->grouptype, 0);
        else 
            $users = $att->get_users($att->pageparams->group, 0);
        
        
        $sessionlog = $att->get_session_log($att->pageparams->sessionid);
        
        $status = att_get_statuse($cm->instance, 'A');
 
        $add                    = new stdClass;
        $add->sessionid         = $sess->id;
        $add->grouptype         = $sess->groupid;
        $add->id                = $cm->id;
        $add->noredirect        = true;
            
        foreach($users as $us) {
          if (!isset($sessionlog[$us->id])){
            $add->{"user".$us->id} = $status->id;
            $add->{"remarks".$us->id} = "";
          }
        }
        
        
        $success = $att->take_from_form_data($add);
        
        $add = new stdClass;
        $add->id = $sess->id;
        $add->finished = 1;
        
        $DB->update_record("attendance_sessions", $add);
        
      }
    }

    return true;
}

function attendance_update_grades($attendance, $userid=0, $nullifnone=true) {
    // We need this function to exist so that quick editing of module name is passed to gradebook.
}
/**
 * Create grade item for given attendance
 *
 * @param object $attendance object with extra cmidnumber
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function attendance_grade_item_update($attendance, $grades=null) {
    global $CFG, $DB;

    require_once('locallib.php');

    if (!function_exists('grade_update')) { // Workaround for buggy PHP versions.
        require_once($CFG->libdir.'/gradelib.php');
    }

    if (!isset($attendance->courseid)) {
        $attendance->courseid = $attendance->course;
    }
    if (! $course = $DB->get_record('course', array('id'=> $attendance->course))) {
        error("Course is misconfigured");
    }

    if (!empty($attendance->cmidnumber)) {
        $params = array('itemname'=>$attendance->name, 'idnumber'=>$attendance->cmidnumber);
    } else {
        // MDL-14303.
        $cm = get_coursemodule_from_instance('attendance', $attendance->id);
        $params = array('itemname'=>$attendance->name/*, 'idnumber'=>$attendance->id*/);
    }

    if ($attendance->grade > 0) {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax']  = $attendance->grade;
        $params['grademin']  = 0;
    } else if ($attendance->grade < 0) {
        $params['gradetype'] = GRADE_TYPE_SCALE;
        $params['scaleid']   = -$attendance->grade;

    } else {
        $params['gradetype'] = GRADE_TYPE_NONE;
    }

    if ($grades  === 'reset') {
        $params['reset'] = true;
        $grades = null;
    }

    return grade_update('mod/attendance', $attendance->courseid, 'mod', 'attendance', $attendance->id, 0, $grades, $params);
}

/**
 * Delete grade item for given attendance
 *
 * @param object $attendance object
 * @return object attendance
 */
function attendance_grade_item_delete($attendance) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    if (!isset($attendance->courseid)) {
        $attendance->courseid = $attendance->course;
    }

    return grade_update('mod/attendance', $attendance->courseid, 'mod', 'attendance',
                        $attendance->id, 0, null, array('deleted'=>1));
}

function attendance_get_participants($attendanceid) {
    return false;
}

/**
 * This function returns if a scale is being used by one attendance
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See book, glossary or journal modules
 * as reference.
 *
 * @param int $attendanceid
 * @param int $scaleid
 * @return boolean True if the scale is used by any attendance
 */
function attendance_scale_used ($attendanceid, $scaleid) {
    return false;
}

/**
 * Checks if scale is being used by any instance of attendance
 *
 * This is used to find out if scale used anywhere
 *
 * @param int $scaleid
 * @return bool true if the scale is used by any book
 */
function attendance_scale_used_anywhere($scaleid) {
    return false;
}

/**
 * Serves the attendance sessions descriptions files.
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - justsend the file
 */
function attendance_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    global $CFG, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_login($course, false, $cm);

    if (!$att = $DB->get_record('attendance', array('id' => $cm->instance))) {
        return false;
    }

    // Session area is served by pluginfile.php.
    $fileareas = array('session');
    if (!in_array($filearea, $fileareas)) {
        return false;
    }

    $sessid = (int)array_shift($args);
    if (!$sess = $DB->get_record('attendance_sessions', array('id' => $sessid))) {
        return false;
    }

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/mod_attendance/$filearea/$sessid/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }
    send_stored_file($file, 0, 0, true);
}


function attendance_checkip($network, $ip){
  if (strstr($network, ",")){
    $list = explode(",", $network);
    $inrange = false;
    foreach($list as $l)
      if (attendance_netMatch($l, $ip))
        $inrange = true;
    
    return $inrange;
  } else
    return attendance_netMatch($network, $ip);
}


function attendance_netMatch($network, $ip) {
    $network=trim($network);
    $orig_network = $network;
    $ip = trim($ip);
    if ($ip == $network) {
        return TRUE;
    }
    $network = str_replace(' ', '', $network);
    if (strpos($network, '*') !== FALSE) {
        if (strpos($network, '/') !== FALSE) {
            $asParts = explode('/', $network);
            $network = @ $asParts[0];
        }
        $nCount = substr_count($network, '*');
        $network = str_replace('*', '0', $network);
        if ($nCount == 1) {
            $network .= '/24';
        } else if ($nCount == 2) {
            $network .= '/16';
        } else if ($nCount == 3) {
            $network .= '/8';
        } else if ($nCount > 3) {
            return TRUE; // if *.*.*.*, then all, so matched
        }
    }

    //echo "from original network($orig_network), used network ($network) for ($ip)\n";

    $d = strpos($network, '-');
    if ($d === FALSE) {
        if (strpos($network, '/')) {
            $ip_arr = explode('/', $network);
            if (!preg_match("@\d*\.\d*\.\d*\.\d*@", $ip_arr[0], $matches)){
                $ip_arr[0].=".0";    // Alternate form 194.1.4/24
            }
            $network_long = ip2long($ip_arr[0]);
            $x = ip2long($ip_arr[1]);
            $mask = long2ip($x) == $ip_arr[1] ? $x : (0xffffffff << (32 - $ip_arr[1]));
            $ip_long = ip2long($ip);
            return ($ip_long & $mask) == ($network_long & $mask);
        } else {
            if ($network == $ip)
                return true;
            else
                return false;
        }
    } else {
        $from = trim(ip2long(substr($network, 0, $d)));
        $to = trim(ip2long(substr($network, $d+1)));
        $ip = ip2long($ip);
        return ($ip>=$from and $ip<=$to);
    }
}


function attendance_ip_detect() {
  if (isset($_SERVER)) {
    if(isset($_SERVER['HTTP_CLIENT_IP'])){
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif(isset($_SERVER['REMOTE_ADDR'])){
      $ip = $_SERVER['REMOTE_ADDR'];
    } else {
      $ip = "100.100.100.100";
    }
  } else {
    if (getenv( 'HTTP_CLIENT_IP')) {
      $ip = getenv( 'HTTP_CLIENT_IP' );
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
      $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR')) {
      $ip = getenv('REMOTE_ADDR');
    } else {
      $ip = "100.100.100.100";
    }
  }
  return $ip;
}
