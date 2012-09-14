<?php
header("Content-Type: application/json; charset=UTF-8");
/*
 * TODO: Utilizar en el bloque de la columna lateral
 */
require_once("../../config.php");
require_once('rgrade_lib.php');

$courseid = optional_param('courseid', '', PARAM_INT);
$schoolid = required_param('schoolid');

if(!$courseid || ! $course = get_record('course', 'id', $courseid)) {
	rgrade_json_error('Course not valid');
}

$groups = groups_get_all_groups($schoolid, $courseid);

$data = array('groups' => $groups);
echo json_encode($data);
