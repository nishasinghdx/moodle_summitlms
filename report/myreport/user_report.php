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
 * Displays live view of recent logs
 *
 * This file generates live view of recent logs.
 *
 * @package    report_loglive
 * @copyright  2011 Petr Skoda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once('lib.php');

//$users = $DB->get_records('user');
//$all_users_report=all_users_report();
$id = optional_param('id', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$logreader = optional_param('logreader', '', PARAM_COMPONENT); // Reader which will be used for displaying logs.

if (empty($id)) {
    require_login();
    $context = context_system::instance();
    $coursename = format_string($SITE->fullname, true, array('context' => $context));
} else {
    $course = get_course($id);
    require_login($course);
    $context = context_course::instance($course->id);
    $coursename = format_string($course->fullname, true, array('context' => $context));
}

$params = array();
if ($id != 0) {
    $params['id'] = $id;
}
if ($page != 0) {
    $params['page'] = $page;
}
if ($logreader !== '') {
    $params['logreader'] = $logreader;
}
$url = new moodle_url("/report/loglive/index.php", $params);

$PAGE->set_url($url);
$PAGE->set_pagelayout('report');

$renderable = new report_loglive_renderable($logreader, $id, $url, 0, $page);
$refresh = $renderable->get_refresh_rate();
$logreader = $renderable->selectedlogreader;

// Include and trigger ajax requests.
if ($page == 0 && !empty($logreader)) {
    // Tell Js to fetch new logs only, by passing time().
    $jsparams = array('since' => time() , 'courseid' => $id, 'page' => $page, 'logreader' => $logreader,
            'interval' => $refresh, 'perpage' => $renderable->perpage);
    $PAGE->requires->strings_for_js(array('pause', 'resume'), 'report_loglive');
    $PAGE->requires->yui_module('moodle-report_loglive-fetchlogs', 'Y.M.report_loglive.FetchLogs.init', array($jsparams));
}

$strlivelogs = get_string('livelogs', 'report_loglive');
$strupdatesevery = get_string('updatesevery', 'moodle', $refresh);

if (empty($id)) {
    admin_externalpage_setup('reportloglive', '', null, '', array('pagelayout' => 'report'));
}
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title("$coursename: $strlivelogs ($strupdatesevery)");
$PAGE->set_heading("$coursename: $strlivelogs ($strupdatesevery)");

$output = $PAGE->get_renderer('report_loglive');
echo $output->header();
echo $output->reader_selector($renderable);
echo $output->toggle_liveupdate_button($renderable);
?>

<style>
#livelogs-pause-button{  display: none;        }
</style>







<?php
 if(isset($_GET['uid'])){
   $uid=$_GET['uid'];
 }

$single_user_report=single_user_report($uid);
$single_user_certificate_report=single_user_certificate_report($uid);
$single_user_badge_report=single_user_badge_report($uid);
$assigned_courses_count=$single_user_report->assigned_courses;
$completed_courses_count=$single_user_report->completed_courses;
$completed_courses_percentage=($completed_courses_count*100)/$assigned_courses_count;
$incompleted_courses_percentage=(100-$completed_courses_percentage);
$user_login_info=user_login_info($uid);
$user_login_last_week=user_login_last_week($uid);
$user_login_last_month=user_login_last_month($uid);
$login_week_times=login_week_times($uid);
foreach ($login_week_times as $lwt) {
    $lwt_arr[]=date('m/d/Y',$lwt->timecreated);
}

  ?>

  <!DOCTYPE html>
  <!-- saved from url=(0057)https://v4-alpha.getbootstrap.com/examples/justified-nav/ -->
  <html lang="en"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

      <title>Justified Nav Template for Bootstrap</title>

      <!-- Bootstrap core CSS -->
      <!-- <link href="./report_files/bootstrap.min.css" rel="stylesheet"> -->

      <!-- Custom styles for this template -->
      <link href="./report_files/justified-nav.css" rel="stylesheet">
      <style media="screen">
        body {
          font-size: 18px;
        }
      </style>
    </head>

    <body>

      <div class="container bordr" style="width:95%;">
        	<div class="masthead" >
  	        <nav class="navbar navbar-light  rounded mb-2" style="background-color:#023d5f;">
  	          <span>Home/Reports/User reports/<?php echo $single_user_report->username; ?></span>
  	        </nav>
        	</div>
        	<div class="padding-10">
        	<nav class=" navbar-toggleable-md navbar-light border-b">
  			<div class=" navbar-collapse" id="navbarSupportedContent">
  			    <ul class="navbar-nav mr-auto">
  			      <li class="nav-item active">
  			        <a class="nav-link" href="#">Overview<span class="sr-only">(current)</span></a>
  			      </li>
  			      <li class="nav-item">
  			        <a class="nav-link" href="#">Courses</a>
  			      </li>
  			      <li class="nav-item">
  			        <a class="nav-link" href="#">Badges</a>
  			      </li>
  			      <li class="nav-item">
  			        <a class="nav-link" href="#">Timeline</a>
  			      </li>
  			    </ul>
  			    <div class="btn-group" data-toggle="buttons">
  				  <label class="btn btn-primary ">
  				    <input type="radio" name="options" id="option1" autocomplete="off" checked> Info
  				  </label>
  				  <label class="btn btn-primary active">
  				    <input type="radio" name="options" id="option2" autocomplete="off"> Progress
  				  </label>
  				  <label class="btn btn-primary">
  				    <input type="radio" name="options" id="option3" autocomplete="off"> Infographic
  				  </label>
  				</div>
  			</div>
  		</nav>
  		<div class="col-sm-12 bg-faded p-15 box-s">
  			<div class="row">
  			<section id="profile" class="well col-sm-5">
  				<div class="row">
  	                <div class="col-lg-3 col-sm-4">
  	                    <img src="https://demos.telerik.com/kendo-ui/content/integration/bootstrap/avatar.jpg" class="ra-avatar img-responsive">
  	                </div>

  	                <div class="col-lg-7 col-sm-8 row">
  	                    <span class="ra-first-name"><b><?php echo $single_user_report->username; ?></b>&nbsp;&nbsp;</span>
  	                    <span class="ra-last-name"><button type="button" class="btn btn-primary btn-sm" style="background-color:#0092e8;font-size:12px;color:#fff;border:none"><?php echo $single_user_report->roletype; ?></button>&nbsp;&nbsp;</span>

                       <div class="ra-position"><?php echo $single_user_report->email; ?></div>

  	                </div>

  	            </div>
                     <!--div row ends here-->
  	        </section>
  	        <section class="col-sm-7  text-right"><button type="button" class="btn btn-danger">Export In Excel</button></section>
          </div> <!--div row ends here-->
          </div>    <!--div col-sm-12 bg-faded p-15 box-s ends here-->

        <!-- Jumbotron -->

        </div>

<?php echo $single_user_report->assigned_courses; ?> assigned courses. <?php echo $single_user_report->completed_courses; ?> completed courses. <?php echo $single_user_certificate_report->certificate_count; ?> certifications. <?php echo $single_user_badge_report->badge_count; ?> badges    </br>
<?php echo $user_login_last_week->login_week_count; ?> logins last week. <?php echo $user_login_last_month->login_month_count; ?> logins last month. last login:<?php echo date('m/d/Y', $single_user_report->lastlogin); ?>





  <div class="row">
    <div class="col-sm-6" align="center">
      <u><h4>logins</h4></u>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
     <div style="height:400px;width:400px;">
      <canvas id="Chart" width="400" height="400"></canvas>
      </div>
      <script>
      var ctx = document.getElementById("Chart");
      var Chart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: [<?php echo '"'.implode('","', $lwt_arr).'"' ?>],
              datasets: [{
                  label: 'login_report',
                  data: [1,3,2,0,2,5],


                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255,99,132,1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
      });
      </script>
    </div>

    <div class="col-sm-6" align="center">
      <u><h4>Courses</h4></u>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <div style="height:400px;width:400px;">
   <canvas id="myChart" width="400" height="400"></canvas>
   </div>
   <script>
   var ctx = document.getElementById("myChart");
   var myChart = new Chart(ctx, {
       type: 'pie',
       data: {

           labels: ['completed courses','not completed courses'],
           datasets: [{
               label: 'user_report',
               data: [<?php echo $completed_courses_percentage; ?>,<?php echo $incompleted_courses_percentage; ?>],


               backgroundColor: [
                   'rgba(255, 99, 132, 0.2)',
                   'rgba(54, 162, 235, 0.2)',
                   'rgba(255, 206, 86, 0.2)',
                   'rgba(75, 192, 192, 0.2)',
                   'rgba(153, 102, 255, 0.2)',
                   'rgba(255, 159, 64, 0.2)'
               ],
               borderColor: [
                   'rgba(255,99,132,1)',
                   'rgba(54, 162, 235, 1)',
                   'rgba(255, 206, 86, 1)',
                   'rgba(75, 192, 192, 1)',
                   'rgba(153, 102, 255, 1)',
                   'rgba(255, 159, 64, 1)'
               ],
               borderWidth: 1
           }]
       },
       options: {
           scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero:true
                   }
               }]
           }
       }
   });
   </script>
  </div>
  <!-- end of col sm 6 div -->


      </div> <!-- /container -->


      <!-- Bootstrap core JavaScript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->



  </body></html>
<?php
 echo $output->footer();
 ?>
