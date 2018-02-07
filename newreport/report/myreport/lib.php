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

function user_login_dates_week($uid){
  global $DB;
  $time=strtotime("-1 week");
  $user_login_dates_week=$DB->get_records_sql("SELECT timecreated as user_login_dates_week
  FROM {logstore_standard_log}
  WHERE (userid=$uid) AND (action='loggedin') AND (timecreated>=$time)
  ");
  foreach ($user_login_dates_week as $value) {
    $user_login_dates_week_arr[]=date('m/d/Y', $value->user_login_dates_week);
  }
  $user_login_dates_week_unique_arr=array_unique($user_login_dates_week_arr);
  return $user_login_dates_week_unique_arr;
}

function user_login_dates_month($uid){
  global $DB;
  $time=strtotime("-9 week");
  $user_login_dates_month=$DB->get_records_sql("SELECT timecreated as user_login_dates_month
  FROM {logstore_standard_log}
  WHERE (userid=$uid) AND (action='loggedin') AND (timecreated>=$time)
  ");
  foreach ($user_login_dates_month as $value) {
    $user_login_dates_month_arr[]=date('m/d/Y', $value->user_login_dates_month);
  }
  $user_login_dates_month_unique_arr=array_unique($user_login_dates_month_arr);
  return $user_login_dates_month_unique_arr;
}


//this function fetches all the courses from the database
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


//this function fetches all the users for a particular course
function users_enrolled_in_course($courseid){
  global $DB;
  $users_enrolled_in_course=$DB->get_record_sql("SELECT ue.userid, u.username, count(DISTINCT ue.userid) as users_assigned_count, c.fullname as coursename
  FROM {course} c
  JOIN {enrol} e ON e.courseid=c.id
  JOIN {user_enrolments} ue ON ue.enrolid=e.id
  JOIN {user} u ON ue.userid = u.id
  WHERE c.id=$courseid
");
return $users_enrolled_in_course;
}



//this function fetches all users who completed particular course
function users_completed_the_course($courseid){
  global $DB;
$users_completed_the_course=$DB->get_record_sql("SELECT cmc.userid, u.username, count(DISTINCT cmc.userid) as users_completed_count
FROM {course_modules} cm
JOIN {course_modules_completion} cmc ON cm.id=cmc.coursemoduleid
JOIN {user} u ON cmc.userid=u.id
WHERE cm.course=$courseid
");
return $users_completed_the_course;
}



//this function fetches course list and number of users assigned to them
// function test(){
//  echo "test function called";
//  global $DB;
//  $test=$DB->get_records_sql("SELECT c.id as courseid,c.fullname as coursename, count(DISTINCT ue.userid) as assigned_user_count
//    FROM {course} c
//    LEFT OUTER JOIN {enrol} e ON e.courseid=c.id
//    LEFT OUTER JOIN {user_enrolments} ue ON ue.enrolid=e.id
//    LEFT OUTER JOIN {user} u ON ue.userid = u.id
//    GROUP BY c.id
//    ");
//    echo "<pre>";
//    print_r($test);
//    echo "</pre>";
// }
// test();



//this function fetches all courses and number of users who are assigned or have completed the course
function all_courses_report(){
 global $DB;
 $all_courses_report=$DB->get_records_sql("SELECT c.id as courseid,c.fullname as course_name, count(DISTINCT ue.userid) as assigned_user_count, count(DISTINCT cmc.userid) as completed_user_count, cc.name as category_name
   FROM {course_modules_completion} cmc
   RIGHT JOIN {course_modules} cm ON cmc.coursemoduleid=cm.id
   RIGHT OUTER JOIN {course} c ON cm.course=c.id
   LEFT OUTER JOIN {course_categories} cc ON c.category=cc.id
   LEFT OUTER JOIN {enrol} e ON c.id=e.courseid
   LEFT OUTER JOIN {user_enrolments} ue ON e.id=ue.enrolid
   LEFT OUTER JOIN {user} u ON ue.userid = u.id
   GROUP BY c.id
   ");
   return $all_courses_report;
}

function count_login_on_date($date,$uid){
  global $DB;
  //$date='2018-01-15';
  $t1=strtotime($date."".'00:00:00');
  $t2=strtotime($date."".'24:00:00');
  $count_login_on_date=$DB->get_record_sql("SELECT count(DISTINCT id) as count_login
    FROM {logstore_standard_log}
    WHERE (userid=$uid) AND (action='loggedin') AND (timecreated  BETWEEN $t1 AND $t2)
  ");
  return $count_login_on_date;
}
function count_users_completed_course_on_date($date,$courseid){
  global $DB;
  // $date='2017-12-19';
  $t1=strtotime($date."".'00:00:00');
  $t2=strtotime($date."".'24:00:00');
  $count_users_completed_course_on_date=$DB->get_record_sql("SELECT count(DISTINCT cmc.id) as count_user
  FROM {course_modules_completion} cmc
  LEFT OUTER JOIN {course_modules} cm ON cmc.coursemoduleid=cm.id
  WHERE (timemodified  BETWEEN $t1 AND $t2) AND (completionstate=1) AND (cm.course=$courseid)
  ");
  return $count_users_completed_course_on_date;
}

function course_completion_dates_month(){
global $DB;
$course_completion_dates_month=$DB->get_records_sql("SELECT timemodified as course_completion_dates_month
  FROM {course_modules_completion}
");

foreach ($course_completion_dates_month as $value) {
  $course_completion_dates_month_arr[]=date('m/d/Y', $value->course_completion_dates_month);
}
$course_completion_dates_month_unique_arr=array_unique($course_completion_dates_month_arr);
return $course_completion_dates_month_unique_arr;
}

function count_courses_completed_by_user_on_date($date,$uid){
  global $DB;
  // $date='2017-12-19';
  $t1=strtotime($date."".'00:00:00');
  $t2=strtotime($date."".'24:00:00');
  $count_courses_completed_by_user_on_date=$DB->get_record_sql("SELECT count(DISTINCT id) as count_course
  FROM {course_modules_completion}
  WHERE (timemodified  BETWEEN $t1 AND $t2) AND (completionstate=1) AND (userid=$uid)
  ");
  return $count_courses_completed_by_user_on_date;
}

function all_quizes_report(){
  global $DB;
  $all_quizes_report=$DB->get_records_sql("SELECT q.id as quizid,q.name as quiz_name, q.grade as total_marks, count(DISTINCT qa.userid) as user_count, qf.mingrade
    FROM {quiz_attempts} qa
    RIGHT JOIN {quiz} q ON qa.quiz=q.id
    JOIN {quiz_feedback} qf ON q.id=qf.quizid
    GROUP BY q.name
  ");
  return $all_quizes_report;
}

// function passed_users(){
//   echo "passed_users function called";
//   global $DB;
//   $passed_users=$DB->get_records_sql("SELECT qa.quiz as quiz_id, qf.id as feedback_id, count(DISTINCT qa.userid) as passed_users_count, qf.feedbacktext
//     FROM {quiz_attempts} qa
//     LEFT OUTER JOIN {quiz_feedback} qf ON qf.quizid=qa.quiz
//     WHERE (qf.feedbacktext='<p>pass</p>') AND (qa.sumgrades>=qf.mingrade)
//     GROUP BY qa.quiz
//   ");
//   echo "<pre>";
//   print_r($passed_users);
//   echo "</pre>";
// }
// passed_users();

function passed_users($quizid){
    global $DB;
    $passed_users=$DB->get_record_sql("SELECT qa.quiz as quiz_id, qf.id as feedback_id, count(DISTINCT qa.userid) as passed_users_count, qf.feedbacktext
    FROM {quiz_attempts} qa
    LEFT OUTER JOIN {quiz_feedback} qf ON qf.quizid=qa.quiz
    WHERE (qf.feedbacktext='<p>pass</p>') AND (qa.sumgrades>=qf.mingrade) AND (qa.quiz=$quizid)
  ");
  return $passed_users;
}

function single_quiz_report_table($quizid){
  global $DB;
  $single_quiz_report_table=$DB->get_records_sql("SELECT  qa.userid as userid,u.username as username,qa.timestart, qa.timefinish,qa.sumgrades, qf.feedbacktext, q.grade
    FROM {quiz_attempts} qa
    JOIN {quiz} q ON qa.quiz=q.id
    JOIN {user} u ON u.id=qa.userid
    JOIN {quiz_feedback} qf ON qf.quizid=qa.quiz
    WHERE (q.id=$quizid) AND (qa.sumgrades<qf.maxgrade) AND(qa.sumgrades>=qf.mingrade)
  ");
return $single_quiz_report_table;
}

function single_quiz_attempted_users($quizid){
  global $DB;
  $single_quiz_attempted_users=$DB->get_record_sql("SELECT count(DISTINCT qa.userid) as user_count, q.name as quiz_name
    FROM {quiz_attempts} qa
    RIGHT JOIN {quiz} q ON qa.quiz=q.id
    JOIN {quiz_feedback} qf ON q.id=qf.quizid
    WHERE q.id=$quizid
  ");
return $single_quiz_attempted_users;
}

function count_login_between_two_dates($date1,$date2,$uid){
  global $DB;
  $date1=$date1;
  $date2=$date2;
  $t1=strtotime($date1."".'00:00:00');
  $t2=strtotime($date2."".'00:00:00');
  $count_login_between_two_dates=$DB->get_record_sql("SELECT count(id) as count_login
    FROM {logstore_standard_log}
    WHERE (userid=$uid) AND (action='loggedin') AND (timecreated  BETWEEN $t1 AND $t2)
  ");
  return $count_login_between_two_dates;
}


 ?>
