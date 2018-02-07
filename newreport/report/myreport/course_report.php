
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
 $all_users_report=all_users_report();
 $all_courses_report=all_courses_report();

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
if(isset($_GET['courseid'])){
  $courseid=$_GET['courseid'];
}

$users_enrolled_in_course=users_enrolled_in_course($courseid);

$users_completed_the_course=users_completed_the_course($courseid);

$users_assigned_count_var=$users_enrolled_in_course->users_assigned_count;
$users_completed_count_var= $users_completed_the_course->users_completed_count;
$completed_users_percentage=($users_completed_count_var*100)/$users_assigned_count_var;
$incompleted_users_percentage=(100-$completed_users_percentage);

$course_completion_dates_month_unique_arr=course_completion_dates_month();
 ?>
 <script type="text/javascript">
 window.onload = function() {
 show_bar_chart();
 show_pie_chart();
 };
 </script>

 <a class="btn " href="#myoverview_courses_reports" data-toggle="tab">Course Report</a>>
 <a class="btn " href="#myoverview_courses_reports" data-toggle="tab"><?php echo $users_enrolled_in_course->coursename;?></a>


 <div class=" row-fluid detail_list" style="margin-top: 20px;font-size: 14px;">
   <div class="span6"> <b><?php echo $users_assigned_count_var; ?></b> Assigned Learners &nbsp;.&nbsp;   <b><?php echo $users_completed_count_var; ?></b> Completed Learners &nbsp;&nbsp;
   </div>
 </div>

 <div role="tabpanel" class="tab-pane fade in active" id="myoverview_courses_view">
   <div id="courses-view-5a4f563412b3d5a4f563412b851" data-region="courses-view">
     <div class="row text-center" style="text-align: left;    margin-left: 20px;">
       <div class="btn-group m-b-1" role="group" data-toggle="btns">
         <a class="btn  active" href="#" data-toggle="tab"><font color="black">Today</font></a>
         <a class="btn active" href="#" data-toggle="tab"><font color="black">Yesterday</font></a>
         <a class="btn active" href="#" data-toggle="tab"><font color="black">Week</font></a>
         <a class="btn active" href="#" data-toggle="tab" onclick="show_bar_chart();show_pie_chart();"><font color="black">Month</font></a>
         <a class="btn active" href="#" data-toggle="tab"><font color="black">Year</font></a>
         <a class="btn active" href="#" data-toggle="tab"><font color="black">Period</font></a>
       </div>
     </div>
   </div>
 </div>

 <div class=" row-fluid detail_list" style="margin-top: 30px;font-size: 14px;">
   <div class="span6"> Overall
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
     <div style="height:400px;width:400px;">
     <canvas id="Chart" width="400" height="400"></canvas>
     </div>
    <script type="text/javascript">
  function show_bar_chart(){
    var ctx = document.getElementById("Chart").getContext("2d");
    var data = {
      labels: [<?php foreach ($course_completion_dates_month_unique_arr as $date) {
      echo "'$date'";
      echo ",";
    }?>],
      datasets: [
          // {
          //     label: "Course Assignments (number of learners assigned in this course)",
          //     backgroundColor: "red",
          //     data: []
          //
          // },
          {
              label: "Course Completions (number of learners who completed this course)",
              backgroundColor: "blue",
              data: [<?php foreach ($course_completion_dates_month_unique_arr as $date) {
                $date=$date;
                $count_users_completed_course_on_date=count_users_completed_course_on_date($date,$courseid);
                echo $count_users_completed_course_on_date->count_user;
                echo ",";
              }?>]
          },

      ]
    };

    var myBarChart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
          barValueSpacing: 20,
          scales: {
              yAxes: [{
                  ticks: {
                      min: 0,
                  }
              }]
          }
      }
    });
}
    </script>
   </div>




 <div class="span6">Learners
 </br>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
   <div style="height:400px;width:400px;">
   <canvas id="myChart" width="400" height="400"></canvas>
   </div>
   <script>
   function show_pie_chart(){
   var ctx = document.getElementById("myChart");
   var myChart = new Chart(ctx, {
      type: 'pie',
      data: {

          labels: ['Learners who completed this course','Learners who have not completed this course'],
          datasets: [{
              label: 'user_report',
              data: [<?php echo $completed_users_percentage; ?>,<?php echo $incompleted_users_percentage; ?>],


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
}
   </script>
   </div>
</div>

 <?php
 // Trigger a logs viewed event.
 $event = \report_loglive\event\report_viewed::create(array('context' => $context));
 $event->trigger();

 echo $output->footer();
