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
 * Strings for component 'attendance', language 'en'
 *
 * @package   mod_attendance
 * @copyright  2011 Artem Andreev <andreev.artem@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['attendance:addinstance'] = 'Add a new attendance activity';
$string['Aacronym'] = 'A';
$string['Afull'] = '欠席';
$string['Eacronym'] = 'E';
$string['Efull'] = '公欠';
$string['Lacronym'] = 'L';
$string['Lfull'] = '遅刻';
$string['Pacronym'] = 'P';
$string['Pfull'] = '出席';
$string['acronym'] = 'Acronym';
$string['add'] = '追加';
$string['addmultiplesessions'] = '複数セッションを作成する';
$string['addsession'] = 'セッションを作成する';
$string['allcourses'] = '全ての科目';
$string['all'] = '全て';
$string['allpast'] = '過去の全て';
$string['attendancedata'] = 'Attendance data';
$string['attendanceforthecourse'] = 'Attendance for the course';
$string['attendancegrade'] = 'Attendance grade';
$string['attendancenotstarted'] = 'Attendance has not started yet for this course';
$string['attendancepercent'] = 'Attendance percent';
$string['attendancereport'] = 'Attendance report';
$string['attendancesuccess'] = 'Attendance has been successfully taken';
$string['attendanceupdated'] = 'Attendance successfully updated';
$string['attendance:canbelisted'] = 'Appears in the roster';
$string['attendance:changepreferences'] = 'Changing Preferences';
$string['attendance:changeattendances'] = 'Changing Attendances';
$string['attendance:export'] = 'Export Reports';
$string['attendance:manageattendances'] = 'Manage Attendances';
$string['attendance:takeattendances'] = 'Taking Attendances';
$string['attendance:view'] = 'Viewing Attendances';
$string['attendance:viewreports'] = 'Viewing Reports';
$string['attforblockdirstillexists'] = 'old mod/attforblock directory still exists - you must delete this directory on your server before running this upgrade.';
$string['attrecords'] = 'Attendances records';
$string['calclose'] = 'Close';
$string['calmonths'] = 'January,February,March,April,May,June,July,August,September,October,November,December';
$string['calshow'] = 'Choose date';
$string['caltoday'] = 'Today';
$string['calweekdays'] = 'Su,Mo,Tu,We,Th,Fr,Sa';
$string['cannottakeforgroup'] = 'You can\'t take attendance for group "{$a}"';
$string['changeattendance'] = 'Change attendance';
$string['changeduration'] = 'Change duration';
$string['changesession'] = 'Change session';
$string['column'] = 'column';
$string['columns'] = 'columns';
$string['commonsession'] = '共通';
$string['commonsessions'] = '共通';
$string['countofselected'] = 'Count of selected';
$string['copyfrom'] = 'Copy attendance data from';
$string['createmultiplesessions'] = 'Create multiple sessions';
$string['createmultiplesessions_help'] = 'This function allows you to create multiple sessions in one simple step.

  * <strong>Session Start Date</strong>: Select the start date of your course (the first day of class)
  * <strong>Session End Date</strong>: Select the last day of class (the last day you want to take attendance).
  * <strong>Session Days</strong>: Select the days of the week when your class will meet (for example, Monday/Wednesday/Friday).
  * <strong>Frequency</strong>: This allows for a frequency setting. If your class will meet every week, select 1; if it will meet every other week, select 2; every 3rd week, select 3, etc.
';
$string['createonesession'] = 'Create one session for the course';
$string['days'] = '当日';
$string['defaults'] = 'Defaults';
$string['defaultdisplaymode'] = 'Default display mode';
$string['delete'] = 'Delete';
$string['deletelogs'] = 'Delete attendance data';
$string['deleteselected'] = 'Delete selected';
$string['deletesession'] = 'Delete session';
$string['deletesessions'] = 'Delete all sessions';
$string['deletingsession'] = 'Deleting session for the course';
$string['deletingstatus'] = 'Deleting status for the course';
$string['description'] = 'タイプ';
$string['display'] = 'Display';
$string['displaymode'] = 'Display mode';
$string['downloadexcel'] = 'Download in Excel format';
$string['downloadooo'] = 'Download in OpenOffice format';
$string['downloadtext'] = 'Download in text format';
$string['donotusepaging'] = 'Do not use paging';
$string['duration'] = 'Duration';
$string['editsession'] = 'Edit Session';
$string['endtime'] = 'Session end time';
$string['endofperiod'] = 'End of period';
$string['enrolmentend'] = 'User enrolment ends {$a}';
$string['enrolmentstart'] = 'User enrolment starts {$a}';
$string['enrolmentsuspended'] = 'Enrolment suspended';
$string['errorgroupsnotselected'] = 'Select one or more groups';
$string['errorinaddingsession'] = 'Error in adding session';
$string['erroringeneratingsessions'] = 'Error in generating sessions ';
$string['errorlatetime'] = 'The class duration must be longer than the late time';
$string['gradebookexplanation'] = 'Grade in gradebook';
$string['gradebookexplanation_help'] = 'The Attendance module displays your current attendance grade based on the number of points you have earned to date and the number of points that could have been earned to date; it does not include class periods in the future. In the gradebook, your attendance grade is based on your current attendance percentage and the number of points that can be earned over the entire duration of the course, including future class periods. As such, your attendance grades displayed in the Attendance module and in the gradebook may not be the same number of points but they are the same percentage.

For example, if you have earned 8 of 10 points to date (80% attendance) and attendance for the entire course is worth 50 points, the Attendance module will display 8/10 and the gradebook will display 40/50. You have not yet earned 40 points but 40 is the equivalent point value to your current attendance percentage of 80%. The point value you have earned in the Attendance module can never decrease, as it is based only on attendance to date; however, the attendance point value shown in the gradebook may increase or decrease depending on your future attendance, as it is based on attendance for the entire course.';
$string['gridcolumns'] = 'Grid columns';
$string['groupsession'] = 'Group';
$string['hiddensessions'] = 'Hidden sessions';
$string['hiddensessions_help'] = 'Sessions are hidden if they are scheduled before the course start date.

You can use this feature to hide older sessions instead of deleting them. Only visible sessions will appear in the Gradebook.';
$string['identifyby'] = 'Identify student by';
$string['includeall'] = 'Select all sessions';
$string['includenottaken'] = 'Include not taken sessions';
$string['includeremarks'] = 'Include remarks';
$string['indetail'] = 'In detail...';
$string['invalidsessionenddate'] = 'The session end date can not be earlier than the session start date';
$string['jumpto'] = 'Jump to';
$string['modulename'] = 'Attendance';
$string['modulename_help'] = 'The attendance activity module enables a teacher to take attendance during class and students to view their own attendance record.

The teacher can create multiple sessions and can mark the attendance status as "Present", "Absent", "Late", or "Excused" or modify the statuses to suit their needs.

Reports are available for the entire class or individual students.';
$string['modulenameplural'] = 'Attendances';
$string['months'] = '当月';
$string['moreattendance'] = 'Attendance has been successfully taken for this page';
$string['myvariables'] = 'My Variables';
$string['newdate'] = 'New date';
$string['newduration'] = 'New duration';
$string['noattforuser'] = 'No attendance records exist for the user';
$string['nodescription'] = '定期授業';
$string['noguest'] = 'Guest can\'t see attendance';
$string['nogroups'] = 'You can\'t add group sessions. No groups exists in course.';
$string['noofdaysabsent'] = 'No of days absent';
$string['noofdaysexcused'] = 'No of days excused';
$string['noofdayslate'] = 'No of days late';
$string['noofdayspresent'] = 'No of days present';
$string['nosessiondayselected'] = 'No Session day selected';
$string['nosessionexists'] = 'No Session exists for this course';
$string['nosessionsselected'] = 'No sessions selected';
$string['notfound'] = 'Attendance activity not found in this course!';
$string['noupgradefromthisversion'] = 'The Attendance module cannot upgrade from the version of attforblock you have installed. - please delete attforblock or upgrade it to the latest version before isntalling the new attendance module';
$string['olddate'] = 'Old date';
$string['period'] = 'Frequency';
$string['pluginname'] = 'Attendance';
$string['pluginadministration'] = 'Attendance administration';
$string['remark'] = 'Remark for: {a}';
$string['remarks'] = 'Remarks';
$string['report'] = 'レポート';
$string['required'] = 'Required*';
$string['resetdescription'] = 'Remember that deleting attendance data will erase information from database. You can just hide older sessions having changed start date of course!';
$string['resetstatuses'] = 'Reset statuses to default';
$string['restoredefaults'] = 'Restore defaults';
$string['resultsperpage'] = 'Results per page';
$string['resultsperpage_desc'] = 'Number of students displayed on a page';
$string['save'] = 'Save attendance';
$string['session'] = 'Session';
$string['session_help'] = 'Session';
$string['sessionadded'] = 'Session successfully added';
$string['sessionalreadyexists'] = 'Session already exists for this date';
$string['sessiondate'] = 'Session Date';
$string['sessiondays'] = 'Session Days';
$string['sessiondeleted'] = 'Session successfully deleted';
$string['sessionenddate'] = 'Session end date';
$string['sessionexist'] = 'Session not added (already exists)!';
$string['sessions'] = 'Sessions';
$string['sessionscompleted'] = 'Sessions completed';
$string['sessionsids'] = 'IDs of sessions: ';
$string['sessionsgenerated'] = 'Sessions successfully generated';
$string['sessionsnotfound'] = 'There is no sessions in the selected timespan';
$string['sessionstartdate'] = 'Session start date';
$string['sessiontype'] = 'Session type';
$string['sessiontype_help'] = 'There are two types of sessions: common and groups. Ability to add different types depends on activity group mode.

* In group mode "No groups" you can add only common sessions.
* In group mode "Visible groups" you can add common and group sessions.
* In group mode "Separate groups" you can add only group sessions.
';
$string['sessiontypeshort'] = 'セッション 種';
$string['sessionupdated'] = 'Session successfully updated';
$string['setallstatusesto'] = 'Set status for all users to «{$a}»';
$string['settings'] = '設定';
$string['showdefaults'] = 'Show defaults';
$string['showduration'] = 'Show duration';
$string['sortedgrid'] = 'Sorted grid';
$string['sortedlist'] = 'Sorted list';
$string['startofperiod'] = 'Start of period';
$string['status'] = 'Status';
$string['statuses'] = 'Statuses';
$string['statusdeleted'] = 'Status deleted';
$string['strftimedm'] = '%d.%m';
$string['strftimedmy'] = '%d.%m.%Y';
$string['strftimedmyhm'] = '%d.%m.%Y %H.%M'; // Line added to allow multiple sessions in the same day.
$string['strftimedmyw'] = '%d.%m.%y&nbsp;(%a)';
$string['strftimehm'] = '%H:%M'; // Line added to allow display of time.
$string['strftimeshortdate'] = '%d.%m.%Y';
$string['studentid'] = 'Student ID';
$string['submit'] = '確認';
$string['takeattendance'] = 'Take attendance';
$string['thiscourse'] = 'この科目';
$string['tablerenamefailed'] = 'Rename of old attforblock table to attendance failed';
$string['update'] = 'Update';
$string['variable'] = 'variable';
$string['variablesupdated'] = 'Variables successfully updated';
$string['versionforprinting'] = 'version for printing';
$string['viewmode'] = 'View mode';
$string['week'] = 'week(s)';
$string['weeks'] = '今週';
$string['youcantdo'] = 'You can\'t do anything';
// New strings.
$string['studentscanmark'] = 'Allow students to record own attendance';
$string['studentscanmark_help'] = 'If checked students will be able to change their own attendance status for the session.';
$string['set_by_student'] = 'Self-recorded';
$string['attendance_already_submitted'] = 'You may not self register attendance that has already been set.';
$string['lowgrade'] = 'Low grade';
$string['submitattendance'] = 'Submit attendance';
$string['attendancenotset'] = 'You must set your attendance';
$string['sessionkeyword'] = 'Session keyword';
$string['setattendance'] = '確認';
$string['ips_title'] = 'IPs range';
$string['min'] = 'min';
$string['ips_descr'] = 'https://www.mediawiki.org/wiki/Help:Range_blocks';
$string['attendance_fileuploadbox'] = 'Ip list upload';
$string['usekeyword'] = 'キーワード入力機能を利用する';
$string['keyword'] = 'キーワード';
$string['minuteslate'] = '遅刻までの時間帯';
$string['keyw_help'] = '初期のキーワード．各授業の前に新規のキーワードに変更可能です．';
$string['late_help'] = '"授業開始後の出席／遅刻判断の切り替え時刻です．１〜６０分を設定してください．”ー”は遅刻設定なし';
$string['offcampus'] = '教室外で出席が登録した．（IP addressによる）';
