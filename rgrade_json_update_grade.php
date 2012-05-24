<?php
header("Content-Type: application/json; charset=UTF-8");

require_once("../../config.php");
require_once('rgrade_lib.php');

if(!isloggedin()) {
	rgrade_json_error('User not logged in');
}

// 1. Validar curso
$courseid = optional_param('courseid', '', PARAM_INT);
if(!$courseid || ! $course = get_record('course', 'id', $courseid)) {
	rgrade_json_error('Course not valid');
}

// 2. Required grade
$id = optional_param('id', 0, PARAM_INT);
if(!$id) {
	rgrade_json_error('Grade id required');
}

$grade = get_record('rcontent_grades', 'id', $id);
if(!$grade){
	rgrade_json_error('Grade not valid');
}

//Somehow required for has_capability to work correctly.
require_login($courseid, false);

// 3. Capabilities
$context = get_context_instance(CONTEXT_MODULE, $grade->rcontentid);
if(!has_capability('mod/rcontent:updatescore', $context)){
	rgrade_json_error('No capabilities');
}

$txtgrade = optional_param('grade', '', PARAM_TEXT);
$comments  = optional_param('comments', '', PARAM_CLEANHTML);

$updated = rgrade_update_grade($grade, $txtgrade, $comments);
if(!$updated){
	rgrade_json_error(rgrade_get_string('error_saving_grade', $grade));
}

echo json_encode($updated);