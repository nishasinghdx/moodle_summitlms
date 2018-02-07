
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

  // $renderable = new report_loglive_renderable($logreader, $id, $url, 0, $page);
  // $refresh = $renderable->get_refresh_rate();
  // $logreader = $renderable->selectedlogreader;

  // Include and trigger ajax requests.
  // if ($page == 0 && !empty($logreader)) {
  //     // Tell Js to fetch new logs only, by passing time().
  //     $jsparams = array('since' => time() , 'courseid' => $id, 'page' => $page, 'logreader' => $logreader,
  //             'interval' => $refresh, 'perpage' => $renderable->perpage);
  //     $PAGE->requires->strings_for_js(array('pause', 'resume'), 'report_loglive');
  //     $PAGE->requires->yui_module('moodle-report_loglive-fetchlogs', 'Y.M.report_loglive.FetchLogs.init', array($jsparams));
  // }

  // $strlivelogs = get_string('livelogs', 'report_loglive');
  // $strupdatesevery = get_string('updatesevery', 'moodle', $refresh);

  // if (empty($id)) {
  //     admin_externalpage_setup('reportloglive', '', null, '', array('pagelayout' => 'report'));
  // }
  $PAGE->set_url($url);
  // $PAGE->set_context($context);
  // $PAGE->set_title("$coursename: $strlivelogs ($strupdatesevery)");
  // $PAGE->set_heading("$coursename: $strlivelogs ($strupdatesevery)");

  $output = $PAGE->get_renderer('report_loglive');
  echo $output->header();
  // echo $output->reader_selector($renderable);
  // echo $output->toggle_liveupdate_button($renderable);
  ?>

  <!-- <style>
  #livelogs-pause-button{  display: none;        }
  </style> -->

 <?php
 if(isset($_GET['quizid'])){
   $quizid=$_GET['quizid'];
 }
 $single_quiz_report_table=single_quiz_report_table($quizid);
 $passed_users=passed_users($quizid);
 $single_quiz_attempted_users=single_quiz_attempted_users($quizid);
 $passed_users_percentage=($passed_users->passed_users_count)*100/($single_quiz_attempted_users->user_count);
 $failed_users_percentage=(100-$passed_users_percentage);
  ?>
<h1>Quiz Report</h1>

<div class="tab-content" >
  <div class="tab-pane active fade in" >
    <div  data-status="1">
      <div  data-region="paging-content">
        <div data-region="paging-content-item" data-page="1" class=" " id="yui_3_17_2_1_1515148855696_606">

        <div class="studen_overview row-fluid">
            <div class="span1">
              <img src="https://demos.telerik.com/kendo-ui/content/integration/bootstrap/avatar.jpg" width="80"  style="text-align: right;border-radius: 5px;" class="ra-avatar img-responsive">
            </div>
          <div class="span2 student_detail_area">
            <h3 class="student_name"> <?php echo $single_quiz_attempted_users->quiz_name; ?>
          </div>
        </div>

<div class=" row-fluid detail_list" style="margin-top: 20px;font-size: 14px;">
  <div class="span6"> <b><?php echo $single_quiz_attempted_users->user_count; ?></b> executions &nbsp;.&nbsp;   <b><?php echo $passed_users->passed_users_count; ?></b> passed &nbsp;&nbsp;<b><?php echo "($passed_users_percentage%)"; ?></b>
  </div>
</div>

<div>
<div class="span8">
          <table cellspacing="0" class="flexible   generalbox" aria-live="polite">
            <thead>
              <tr style="background: #d9b72f;color: #000;">
                <th class="header c0" scope="col" style="padding: 10px;">USER
                  <div class="commands"></div>
                </th>
                <th class="header c1" scope="col" style="padding: 10px;">DATE
                  <div class="commands"></div>
                </th>
                <th class="header c2" scope="col" style="padding: 10px;">RESULT
                  <div class="commands"></div>
                </th>
                <th class="header c5" scope="col" style="padding: 10px;">SCORE
                  <div class="commands"></div>
                </th>
                <th class="header c6" scope="col" style="padding: 10px;">TIME
                  <div class="commands"></div>
                </th>
                <th class="header c7" scope="col" style="padding: 10px;">OPERATIONS
                  <div class="commands"></div>
                </th>
              </tr>
            </thead>
            <tbody>

            <?php

            foreach ($single_quiz_report_table as $quiz_report) {
                  $score=(($quiz_report->sumgrades*100)/($quiz_report->grade));
          ?>
              <tr class="" id="">
               <td class="cell c0" style="padding: 10px;"><?php echo $quiz_report->username; ?></td>
                <td class="cell c1" style="padding: 10px;"><?php echo date('m/d/Y H:i:s', $quiz_report->timefinish); ?></td>
                <td class="cell c2"  style="padding: 10px;"><?php echo $quiz_report->feedbacktext; ?></td>
                <td class="cell c3"  style="padding: 10px;"><?php echo $score.'%'; ?></td>
                <td class="cell c4"  style="padding: 10px;"><?php echo date('i:s',$quiz_report->timefinish-$quiz_report->timestart); ?></td>
                <td class="cell c5"  style="padding: 10px;"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-refresh" aria-hidden="true"></i></td>

              </tr>     <!--id="report_loglive_r0_c0"  -->

            <?php
           }
             ?>
            </tbody>
          </table>
</div>


          <div class="span4">Quiz Overall Report
          </br>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
            <div style="height:400px;width:400px;">
            <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <script>
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
               type: 'pie',
               data: {

                   labels: ['passed users percentage','failed users percentage'],
                   datasets: [{
                       label: 'user_report',
                       data: [<?php echo $passed_users_percentage; ?>,<?php echo $failed_users_percentage; ?>],


                       backgroundColor: [
                         'rgb(128,0,0)',
                         'rgb(135,206,250)',
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
                       // yAxes: [{
                       //     ticks: {
                       //         beginAtZero:true
                       //     }
                       // }]
                   }
               }
            });

            </script>
            </div>
          </div>










  <?php
  // Trigger a logs viewed event.
  // $event = \report_loglive\event\report_viewed::create(array('context' => $context));
  // $event->trigger();

  echo $output->footer();
