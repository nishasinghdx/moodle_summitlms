<?php
require_once(dirname(__FILE__) . '/../../config.php');
global $DB;
global $USER;
defined('MOODLE_INTERNAL') || die;

function all_users_report(){
  global $DB;
  $all_users_report=$DB->get_records_sql("SELECT u.id, count(DISTINCT ue.enrolid) as assigned_courses, count(DISTINCT cmc.coursemoduleid) as completed_courses, u.username, u.lastlogin, r.shortname as roletype
  FROM {user} u
   LEFT OUTER JOIN {user_enrolments} ue ON u.id = ue.userid
   LEFT OUTER JOIN {course_modules_completion} cmc ON ue.userid=cmc.userid
   JOIN {role_assignments} ra ON u.id=ra.userid
  JOIN {role} r ON ra.roleid=r.id
   WHERE (r.shortname='student')
  GROUP BY u.id
  ");
  return $all_users_report;
}

function single_user_report($id){
  global $DB;
  $single_user_report=$DB->get_record_sql("SELECT u.id, count(DISTINCT ue.enrolid) as assigned_courses, count(DISTINCT cmc.coursemoduleid) as completed_courses, u.username, u.email, u.lastlogin, r.shortname as roletype
  FROM {user} u
   LEFT OUTER JOIN {user_enrolments} ue ON u.id = ue.userid
   LEFT OUTER JOIN {course_modules_completion} cmc ON ue.userid=cmc.userid
   JOIN {role_assignments} ra ON u.id=ra.userid
   JOIN {role} r ON ra.roleid=r.id
   WHERE (u.id=$id)
  ");
  return $single_user_report;
}

function single_user_certificate_report($id){
  global $DB;
  $single_user_certificate_report=$DB->get_record_sql("SELECT count(DISTINCT id) as certificate_count
  FROM {certificate_issues}
  WHERE (userid=$id)
  ");
  return $single_user_certificate_report;
}

function single_user_badge_report($id){
  global $DB;
  $single_user_badge_report=$DB->get_record_sql("SELECT count(DISTINCT id) as badge_count
  FROM {badge_issued}
  WHERE (userid=$id)
  ");
  return $single_user_badge_report;
}

function user_login_info($id){
  global $DB;
  $user_login_info=$DB->get_records_sql("SELECT count(id) as login_count
  FROM {logstore_standard_log}
  WHERE (userid=$id) AND (action='loggedin')
  ");
  return $user_login_info;
}

function user_login_last_week($id){
  global $DB;
  $time=strtotime("-1 week");
  $user_login_last_week=$DB->get_record_sql("SELECT count(id) as login_week_count
  FROM {logstore_standard_log}
  WHERE (userid=$id) AND (action='loggedin') AND (timecreated>=$time)
  ");
  return $user_login_last_week;
}

function user_login_last_month($id){
  global $DB;
  $time=strtotime("-4 week");
  $user_login_last_month=$DB->get_record_sql("SELECT count(id) as login_month_count
  FROM {logstore_standard_log}
  WHERE (userid=$id) AND (action='loggedin') AND (timecreated>=$time)
  ");
  return $user_login_last_month;
}
function login_week_times($id){
  global $DB;
  $time=strtotime("-1 week");
  $login_week_times=$DB->get_records_sql("SELECT timecreated
  FROM {logstore_standard_log}
  WHERE (userid=$id) AND (action='loggedin') AND (timecreated>=$time)
  ");
  return $login_week_times;
}

// function course_report(){
// echo "course report function called";
// global $DB;
// $course_report=$DB->get_records_sql("SELECT id as course_id, fullname as course_name
//   FROM {course}
// ");
// echo "<pre>";
// print_r($course_report);
// echo "<pre>";
// }
// course_report();
//
// function users_enrolled_in_course(){
//   echo "test function called";
//   global $DB;
// $users_enrolled_in_course=$DB->get_records_sql("SELECT ue.userid, u.username
//   FROM {course} c
//   JOIN {enrol} e ON e.courseid=c.id
//   JOIN {user_enrolments} ue ON ue.enrolid=e.id
//   JOIN {user} u ON ue.userid = u.id
//   WHERE c.id='9'
// ");
//   echo "<pre>";
//   print_r($users_enrolled_in_course);
//   echo "</pre>";
// }
// users_enrolled_in_course();
//
// function users_completed_the_course(){
//   echo "users_completed_the_course function called";
//   global $DB;
// $users_completed_the_course=$DB->get_records_sql("SELECT cmc.userid
// FROM {course_modules} cm
// JOIN {course_modules_completion} cmc ON cm.id=cmc.coursemoduleid
// JOIN {user} u ON cmc.userid=u.id
// WHERE cm.course='10'
// ");
//   echo "<pre>";
//   print_r($users_completed_the_course);
//   echo "</pre>";
// }
// users_completed_the_course();
 ?>
