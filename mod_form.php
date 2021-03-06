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
 * Forms for updating/adding attendance
 *
 * @package    mod_attendance
 * @copyright  2011 Artem Andreev <andreev.artem@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * class for displaying add/update form.
 *
 * @copyright  2011 Artem Andreev <andreev.artem@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_attendance_mod_form extends moodleform_mod {

    /**
     * Called to define this moodle form
     *
     * @return void
     */
    public function definition() {
        global $DB;
        
        $mform    =& $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setDefault('name', get_string('modulename', 'attendance'));

        $mform->addElement('modgrade', 'grade', get_string('grade'));
        $mform->setDefault('grade', 100);
        
        $mform->addElement('text', 'keyword', get_string('keyword', 'attendance'), array('size'=>'16'));
        
        //$mform->addElement('text', 'ips', get_string('ips_title', 'attendance'), array('size'=>'64'));
        //$mform->setType('ips', PARAM_TEXT);
        //$mform->addRule('ips', null, 'required', null, 'client');
        
        $options = array(0=> "No need");
        
        if ($data = $DB->get_records("attendance_ips", array(), "id")){ // 
          foreach($data as $k => $v){
            $options[$v->ip] = $v->location;
          }
        }
        
        $mform->addElement('select', 'ips', get_string('ips_title', 'attendance'), $options);
        //$mform->setDefault('ips', $config->numbering);

        $this->standard_coursemodule_elements(true);
        $this->add_action_buttons();
    }
}
