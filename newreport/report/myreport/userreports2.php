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
$user_login_dates_month_unique_arr=user_login_dates_month($uid);
$user_login_dates_week_unique_arr=user_login_dates_week($uid);
  ?>
<script type="text/javascript">
window.onload = function() {
show_bar_chart_month();
show_pie_chart_month();
};
function show_form(){
  document.getElementById("form").style.display="block";
}
</script>


  <div role="main" id="yui_3_17_2_1_1515148855696_610"><span id="maincontent"></span>
    <aside id="block-region-content" class="block-region" data-blockregion="content" data-droptarget="1"><a class="skip skip-block" id="fsb-1" href="#sb-1">Skip Course overview</a>
      <div id="inst18" class="block_myoverview  block" role="complementary" data-block="myoverview" data-instanceid="18" aria-labelledby="instance-18-header" data-dockable="1">
        <div class="header">
          <div class="title" id="yui_3_17_2_1_1515148855696_424">
            <div class="block_action">   </div><!--div block_action ends here-->
            <h2 id="instance-18-header">Reports</h2>
          </div>   <!--div title ends here-->
        </div><!--div header ends here-->
        <div class="content" id="yui_3_17_2_1_1515148855696_609">
          <div id="block-myoverview-5a4f563412b3d5a4f563412b851" class="block-myoverview" data-region="myoverview">
            <ul id="block-myoverview-view-choices-5a4f563412b3d5a4f563412b851" class="nav nav-tabs" role="tablist">
              <li class="nav-item active">
                <a class="nav-link" href="#myoverview_courses_view" role="tab" data-toggle="tab" data-tabname="courses">
                Types
            </a>
              </li>
            </ul>
            <div class="tab-content" id="yui_3_17_2_1_1515148855696_608">
              <div role="tabpanel" class="tab-pane fade " id="myoverview_timeline_view">
                <div id="timeline-view-5a4f563412b3d5a4f563412b851" data-region="timeline-view">
                  <div class="row text-center">
                    <div class="btn-group m-b-1" role="group" data-toggle="btns">
                      <a class="btn btn-default active" href="#myoverview_timeline_dates" data-toggle="tab">
                            Sort by dates
                        </a>
                      <a class="btn btn-default" href="#myoverview_timeline_courses" data-toggle="tab">
                            Sort by courses
                        </a>
                    </div>   <!--div btn-group m-b-1 ends here-->
                  </div>  <!--div row text-center ends here-->
</div></div></div>
                  <div class="tab-content">
                    <div class="tab-pane fade" id="myoverview_timeline_courses">
                      <div id="sort-by-courses-view-5a4f563412b3d5a4f563412b851">
                        <ul class="list-group unstyled hidden" data-region="course-block">
                          <li class="list-group-item well well-small">
                            <div data-region="course-events-container" id="course-events-container-9" data-course-id="9">
                              <div class="row-fluid">
                                <div class="span3">
                                  <div class="course-info-container" id="course-info-container-9">
                                    <div class="visible-desktop">
                                      <div class="progress-chart-container m-b-1">
                                        <div class="no-progress">
                                          <img class="icon " alt="" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/i/course">
                                        </div>
                                      </div>
                                      <h4><a href="http://www.summitlms.co.za/course/view.php?id=9" class="">IHS Sales</a></h4>
                                    </div>
                                    <div class="hidden-desktop">
                                      <div class="media">
                                        <div class="pull-left">
                                          <div class="media-object">
                                            <div class="progress-chart-container m-b-1">
                                              <div class="no-progress">
                                                <img class="icon " alt="" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/i/course">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="media-body">
                                          <h4 class="media-heading">
                                <a href="http://www.summitlms.co.za/course/view.php?id=9" class="">IHS Sales</a>
                            </h4>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="muted">
                                      Welcome to your introductory sales course. This is a brief overview of our sales divisions within IHS.
                                    </p>
                                  </div>
                                </div>
                                <div class="span9">
                                  <div data-region="event-list-container" data-limit="10" data-course-id="9" data-last-id="" data-midnight="1515103200" id="event-list-container-9">

                                    <div data-region="event-list-content">
                                      <div data-region="event-list-group-container" data-start-day="-14" data-end-day="0" class="hidden">

                                        <h5 class="text-danger" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Recently overdue</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="0" data-end-day="1" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Today</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="1" data-end-day="7" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 7 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="7" data-end-day="30" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 30 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="30" data-end-day="" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Future</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>

                                      <div class="text-xs-center text-center m-b-1">
                                        <button type="button" class="btn btn-secondary" data-action="view-more">
                View more
                <span class="hidden" data-region="loading-icon-container">
                    <span class="loading-icon"><img class="icon " alt="Loading" title="Loading" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/y/loading"></span>
                </span>
            </button>
                                      </div>
                                    </div>
                                    <div class="hidden text-xs-center text-center m-y-3" data-region="empty-message">
                                      <img class="empty-placeholder-image-sm" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/activities" alt="No upcoming activities due" role="presentation">
                                      <p class="text-muted m-t-1">No upcoming activities due</p>
                                      <a href="http://www.summitlms.co.za/course/view.php?id=9" class="btn" aria-label="View course IHS Sales">
            View course
        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item well well-small">
                            <div data-region="course-events-container" id="course-events-container-10" data-course-id="10">
                              <div class="row-fluid">
                                <div class="span3">
                                  <div class="course-info-container" id="course-info-container-10">
                                    <div class="visible-desktop">
                                      <div class="progress-chart-container m-b-1">
                                        <div class="progress-doughnut">
                                          <div class="progress-text has-percent">100%</div>
                                          <div class="progress-indicator">
                                            <svg xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <title aria-hidden="true">100%</title>
                                        <circle class="circle percent-100" r="27.5" cx="35" cy="35"></circle>
                                    </g>
                                </svg>
                                          </div>
                                        </div>
                                      </div>
                                      <h4><a href="http://www.summitlms.co.za/course/view.php?id=10" class="">Marketing course 101</a></h4>
                                    </div>
                                    <div class="hidden-desktop">
                                      <div class="media">
                                        <div class="pull-left">
                                          <div class="media-object">
                                            <div class="progress-chart-container m-b-1">
                                              <div class="progress-doughnut">
                                                <div class="progress-text has-percent">100%</div>
                                                <div class="progress-indicator">
                                                  <svg xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <title aria-hidden="true">100%</title>
                                                    <circle class="circle percent-100" r="27.5" cx="35" cy="35"></circle>
                                                </g>
                                            </svg>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="media-body">
                                          <h4 class="media-heading">
                                <a href="http://www.summitlms.co.za/course/view.php?id=10" class="">Marketing course 101</a>
                            </h4>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="muted">

                                    </p>
                                  </div>
                                </div>
                                <div class="span9">
                                  <div data-region="event-list-container" data-limit="10" data-course-id="10" data-last-id="" data-midnight="1515103200" id="event-list-container-10">

                                    <div data-region="event-list-content">
                                      <div data-region="event-list-group-container" data-start-day="-14" data-end-day="0" class="hidden">

                                        <h5 class="text-danger" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Recently overdue</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="0" data-end-day="1" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Today</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="1" data-end-day="7" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 7 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="7" data-end-day="30" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 30 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="30" data-end-day="" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Future</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>

                                      <div class="text-xs-center text-center m-b-1">
                                        <button type="button" class="btn btn-secondary" data-action="view-more">
                View more
                <span class="hidden" data-region="loading-icon-container">
                    <span class="loading-icon"><img class="icon " alt="Loading" title="Loading" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/y/loading"></span>
                </span>
            </button>
                                      </div>
                                    </div>
                                    <div class="hidden text-xs-center text-center m-y-3" data-region="empty-message">
                                      <img class="empty-placeholder-image-sm" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/activities" alt="No upcoming activities due" role="presentation">
                                      <p class="text-muted m-t-1">No upcoming activities due</p>
                                      <a href="http://www.summitlms.co.za/course/view.php?id=10" class="btn" aria-label="View course Marketing course 101">
            View course
        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item well well-small">
                            <div data-region="course-events-container" id="course-events-container-11" data-course-id="11">
                              <div class="row-fluid">
                                <div class="span3">
                                  <div class="course-info-container" id="course-info-container-11">
                                    <div class="visible-desktop">
                                      <div class="progress-chart-container m-b-1">
                                        <div class="progress-doughnut">
                                          <div class="progress-text has-percent">100%</div>
                                          <div class="progress-indicator">
                                            <svg xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <title aria-hidden="true">100%</title>
                                        <circle class="circle percent-100" r="27.5" cx="35" cy="35"></circle>
                                    </g>
                                </svg>
                                          </div>
                                        </div>
                                      </div>
                                      <h4><a href="http://www.summitlms.co.za/course/view.php?id=11" class="">Marketing quiz</a></h4>
                                    </div>
                                    <div class="hidden-desktop">
                                      <div class="media">
                                        <div class="pull-left">
                                          <div class="media-object">
                                            <div class="progress-chart-container m-b-1">
                                              <div class="progress-doughnut">
                                                <div class="progress-text has-percent">100%</div>
                                                <div class="progress-indicator">
                                                  <svg xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <title aria-hidden="true">100%</title>
                                                    <circle class="circle percent-100" r="27.5" cx="35" cy="35"></circle>
                                                </g>
                                            </svg>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="media-body">
                                          <h4 class="media-heading">
                                <a href="http://www.summitlms.co.za/course/view.php?id=11" class="">Marketing quiz</a>
                            </h4>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="muted">

                                    </p>
                                  </div>
                                </div>
                                <div class="span9">
                                  <div data-region="event-list-container" data-limit="10" data-course-id="11" data-last-id="" data-midnight="1515103200" id="event-list-container-11">

                                    <div data-region="event-list-content">
                                      <div data-region="event-list-group-container" data-start-day="-14" data-end-day="0" class="hidden">

                                        <h5 class="text-danger" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Recently overdue</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="0" data-end-day="1" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Today</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="1" data-end-day="7" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 7 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="7" data-end-day="30" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 30 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="30" data-end-day="" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Future</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>

                                      <div class="text-xs-center text-center m-b-1">
                                        <button type="button" class="btn btn-secondary" data-action="view-more">
                View more
                <span class="hidden" data-region="loading-icon-container">
                    <span class="loading-icon"><img class="icon " alt="Loading" title="Loading" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/y/loading"></span>
                </span>
            </button>
                                      </div>
                                    </div>
                                    <div class="hidden text-xs-center text-center m-y-3" data-region="empty-message">
                                      <img class="empty-placeholder-image-sm" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/activities" alt="No upcoming activities due" role="presentation">
                                      <p class="text-muted m-t-1">No upcoming activities due</p>
                                      <a href="http://www.summitlms.co.za/course/view.php?id=11" class="btn" aria-label="View course Marketing quiz">
            View course
        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item well well-small">
                            <div data-region="course-events-container" id="course-events-container-4" data-course-id="4">
                              <div class="row-fluid">
                                <div class="span3">
                                  <div class="course-info-container" id="course-info-container-4">
                                    <div class="visible-desktop">
                                      <div class="progress-chart-container m-b-1">
                                        <div class="no-progress">
                                          <img class="icon " alt="" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/i/course">
                                        </div>
                                      </div>
                                      <h4><a href="http://www.summitlms.co.za/course/view.php?id=4" class="">Study Skills</a></h4>
                                    </div>
                                    <div class="hidden-desktop">
                                      <div class="media">
                                        <div class="pull-left">
                                          <div class="media-object">
                                            <div class="progress-chart-container m-b-1">
                                              <div class="no-progress">
                                                <img class="icon " alt="" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/i/course">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="media-body">
                                          <h4 class="media-heading">
                                <a href="http://www.summitlms.co.za/course/view.php?id=4" class="">Study Skills</a>
                            </h4>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="muted">
                                      Improving your study skills to accelerate your learning and attainment.
                                    </p>
                                  </div>
                                </div>
                                <div class="span9">
                                  <div data-region="event-list-container" data-limit="10" data-course-id="4" data-last-id="" data-midnight="1515103200" id="event-list-container-4">

                                    <div data-region="event-list-content">
                                      <div data-region="event-list-group-container" data-start-day="-14" data-end-day="0" class="hidden">

                                        <h5 class="text-danger" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Recently overdue</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="0" data-end-day="1" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Today</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="1" data-end-day="7" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 7 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="7" data-end-day="30" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Next 30 days</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>
                                      <div data-region="event-list-group-container" data-start-day="30" data-end-day="" class="hidden">

                                        <h5 class="" id="event-list-title-5a4f563412b3d5a4f563412b851"><strong>Future</strong></h5>
                                        <ul class="unstyled well well-small" data-region="event-list" aria-describedby="event-list-title-5a4f563412b3d5a4f563412b851">
                                        </ul>
                                      </div>

                                      <div class="text-xs-center text-center m-b-1">
                                        <button type="button" class="btn btn-secondary" data-action="view-more">
                View more
                <span class="hidden" data-region="loading-icon-container">
                    <span class="loading-icon"><img class="icon " alt="Loading" title="Loading" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/y/loading"></span>
                </span>
            </button>
                                      </div>
                                    </div>
                                    <div class="hidden text-xs-center text-center m-y-3" data-region="empty-message">
                                      <img class="empty-placeholder-image-sm" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/activities" alt="No upcoming activities due" role="presentation">
                                      <p class="text-muted m-t-1">No upcoming activities due</p>
                                      <a href="http://www.summitlms.co.za/course/view.php?id=4" class="btn" aria-label="View course Study Skills">
            View course
        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>

                        </ul>
                        <div class="text-xs-center text-center m-t-1">
                          <button type="button" class="btn btn-secondary" data-action="more-courses">
                                                More courses
                                                <span class="hidden" data-region="loading-icon-container">
                                                    <span class="loading-icon"><img class="icon " alt="Loading" title="Loading" src="http://www.summitlms.co.za/theme/image.php/lambda/core/1513682059/y/loading"></span>
                                                </span>
                                            </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div role="tabpanel" class="tab-pane fade in active" id="myoverview_courses_view">
                <div id="courses-view-5a4f563412b3d5a4f563412b851" data-region="courses-view">
                  <div class="row text-center" style="text-align: left;    margin-left: 20px;">
                    <div class="btn-group m-b-1" role="group" data-toggle="btns">
                      <a class="btn  active" href="#myoverview_user_reports" data-toggle="tab">
                              Overview
                      </a>
                      <a class="btn " href="#myoverview_courses_reports" data-toggle="tab">
                                Courses
                      </a>
                      <a class="btn " href="#myoverview_test_reports" data-toggle="tab">
                                Badges
                      </a>
                      <a class="btn " href="#myoverview_group_reports" data-toggle="tab">
                                Timeline
                      </a>


                    </div>
                  </div>
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
                              <h3 class="student_name"> <?php echo $single_user_report->username; ?>
                                <span class="ra-last-name"><button type="button" class="btn btn-primary btn-sm" style="background-color:#0092e8;font-size:12px;color:#fff;border:none"><?php echo $single_user_report->roletype; ?></button>&nbsp;&nbsp;</span>
                                <br>
                                <span style="font-size:12px;"><?php echo $single_user_report->email; ?></span></h3>
                            </div>
                          </div>

                        <div class=" row-fluid detail_list" style="margin-top: 20px;font-size: 14px;">
                          <div class="span6"> <b><?php echo $single_user_report->assigned_courses; ?></b> assigned course &nbsp;.&nbsp;   <b><?php echo $single_user_report->completed_courses; ?></b> Completed course &nbsp;.&nbsp; <b> <?php echo $single_user_certificate_report->certificate_count; ?></b> Certification &nbsp;.&nbsp;  <b>25</b> Points  &nbsp;&nbsp; <b><?php echo $single_user_badge_report->badge_count; ?></b> badges  &nbsp;&nbsp;
                          </br><b><?php echo $user_login_last_week->login_week_count; ?></b>logins last week&nbsp;.&nbsp; <b><?php echo $user_login_last_month->login_month_count; ?></b>logins last month&nbsp;.&nbsp; Last Login: <b><?php echo date('m/d/Y', $single_user_report->lastlogin); ?></b>
                    <!-- <button type="button">Export In Excel</button> -->
                           <div>
                        </div>
</br>


                        <div role="tabpanel" class="tab-pane fade in active" id="myoverview_courses_view">
                          <div id="courses-view-5a4f563412b3d5a4f563412b851" data-region="courses-view">
                            <div class="row text-center" style="text-align: left;    margin-left: 20px;">
                              <div class="btn-group m-b-1" role="group" data-toggle="btns">
                                <a class="btn  active" href="#" data-toggle="tab"><font color="black" onclick="show_bar_chart_today();">Today</font></a>
                                <a class="btn active" href="#" data-toggle="tab"><font color="black" onclick="show_bar_chart_yesterday();">Yesterday</font></a>
                                <a class="btn active" href="#" data-toggle="tab"><font color="black" onclick="show_bar_chart_week();">Week</font></a>
                                <a class="btn active" href="#" data-toggle="tab" onclick="show_bar_chart_month();show_pie_chart_month();"><font color="black">Month</font></a>
                                <a class="btn active" href="#" data-toggle="tab"><font color="black" onclick="show_bar_chart_year();">Year</font></a>
                                <a class="btn active" href="#" data-toggle="tab"><font color="black" onclick="show_form();">Period</font></a>
                              </div>
                            </div>
                          </div>
                        </div>

<form class="" style="display:none" id="form">
<label for="fromdate">Start Date:</label>
<input type="date" name="fromdate" value="fromdate" id="fromdate">
<label for="todate">End Date:</label>
<input type="date" name="todate" value="todate" id="todate">
<input type="button" name="click" value="click" onclick="show_bar_chart_period();">
</form>


                        <div class=" row-fluid detail_list" style="margin-top: 30px;font-size: 14px;">
                          <div class="span6"> Logins
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
                            <div style="height:400px;width:400px;">
                            <canvas id="Chart" width="400" height="400"></canvas>
                            </div>
                           <script type="text/javascript">
                           function show_bar_chart_month(){
                           var ctx1 = document.getElementById("Chart").getContext("2d");
                           var data = {
                             labels: [<?php foreach ($user_login_dates_month_unique_arr as $date) {
                             echo "'$date'";
                             echo ",";
                           }?>],
                             datasets: [
                                 {
                                     label: "Logins",
                                     backgroundColor: "blue",
                                     // data: [3,7,4,1,2]
                                     data: [<?php foreach ($user_login_dates_month_unique_arr as $date) {
                                       $date=$date;
                                       $count_login_on_date=count_login_on_date($date,$uid);
                                       echo $count_login_on_date->count_login;
                                       echo ",";
                                     }?>]
                                 },
                                 {
                                     label: "Course Completions",
                                     backgroundColor: "red",
                                     data: [<?php foreach ($user_login_dates_month_unique_arr as $date) {
                                       $date=$date;
                                       $count_courses_completed_by_user_on_date=count_courses_completed_by_user_on_date($date,$uid);
                                       echo $count_courses_completed_by_user_on_date->count_course;
                                       echo ",";
                                     }?>]
                                 },

                             ]
                           };

                           var myBarChart = new Chart(ctx1, {
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


                        function show_bar_chart_week(){
                        var ctx2 = document.getElementById("Chart").getContext("2d");
                        var data = {
                          labels: [<?php foreach ($user_login_dates_week_unique_arr as $date) {
                          echo "'$date'";
                          echo ",";
                        }?>],
                          datasets: [
                              {
                                  label: "Logins",
                                  backgroundColor: "blue",
                                  data: [<?php foreach ($user_login_dates_week_unique_arr as $date) {
                                    $date=$date;
                                    $count_login_on_date=count_login_on_date($date,$uid);
                                    echo $count_login_on_date->count_login;
                                    echo ",";
                                  }?>]

                              },
                              {
                                  label: "Course Completions",
                                  backgroundColor: "red",
                                  data: [<?php foreach ($user_login_dates_week_unique_arr as $date) {
                                    $date=$date;
                                    $count_courses_completed_by_user_on_date=count_courses_completed_by_user_on_date($date,$uid);
                                    echo $count_courses_completed_by_user_on_date->count_course;
                                    echo ",";
                                  }?>]
                              },

                          ]
                        };

                        var myBarChart = new Chart(ctx2, {
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

                     function show_bar_chart_year(){
                     var ctx1 = document.getElementById("Chart").getContext("2d");
                     var data = {
                       labels: [<?php
                       for ($m=1; $m<=12; $m++) {
                           $month = date('F', mktime(0,0,0,$m));
                           echo "'$month'";
                           echo ",";
                           }
                        ?>],
                       datasets: [
                           {
                               label: "Logins",
                               backgroundColor: "blue",
                               data: [<?php
                               $times  = array();
                               for($month = 1; $month <= 12; $month++) {
                                   $first_minute = mktime(0, 0, 0, $month, 1);
                                   $last_minute = mktime(23, 59, 59, $month, date('t', $first_minute));
                                   $times[$month] = array(date('m/d/Y', $first_minute), date('m/d/Y', $last_minute));
                               }
                               foreach ($times as $value) {
                                 $date1= $value[0];
                                 $date2= $value[1];
                                 $count_login_between_two_dates=count_login_between_two_dates($date1,$date2,$uid);
                                 echo $count_login_between_two_dates->count_login;
                                 echo ",";
                               }

                               ?>]

                           },
                           {
                               label: "Course Completions",
                               backgroundColor: "red",
                               data: []
                           },

                       ]
                     };

                     var myBarChart = new Chart(ctx1, {
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

                  function show_bar_chart_today(){
                  var ctx1 = document.getElementById("Chart").getContext("2d");
                  var data = {
                    labels: [<?php
                      echo date("'d-m-Y'",strtotime('today'));
                     ?>],
                    datasets: [
                        {
                            label: "Logins",
                            backgroundColor: "blue",
                            data: [<?php
                                $date=date('d-m-Y',strtotime('today'));
                                $count_login_on_date=count_login_on_date($date,$uid);
                                echo $count_login_on_date->count_login;
                                echo ",";
                             ?>]

                        },
                        {
                            label: "Course Completions",
                            backgroundColor: "red",
                            data: [<?php
                            $date=date('d-m-Y',strtotime('today'));
                            $count_courses_completed_by_user_on_date=count_courses_completed_by_user_on_date($date,$uid);
                            echo $count_courses_completed_by_user_on_date->count_course;
                            echo ",";
                             ?>]
                        },

                    ]
                  };

                  var myBarChart = new Chart(ctx1, {
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

                  function show_bar_chart_yesterday(){
                  var ctx1 = document.getElementById("Chart").getContext("2d");
                  var data = {
                    labels: [<?php
                      echo date("'d-m-Y'",strtotime('yesterday'));
                     ?>],
                    datasets: [
                        {
                            label: "Logins",
                            backgroundColor: "blue",
                            data: [<?php
                                $date=date('d-m-Y',strtotime('yesterday'));
                                $count_login_on_date=count_login_on_date($date,$uid);
                                echo $count_login_on_date->count_login;
                                echo ",";
                             ?>]

                        },
                        {
                            label: "Course Completions",
                            backgroundColor: "red",
                            data: [<?php
                            $date=date('d-m-Y',strtotime('yesterday'));
                            $count_courses_completed_by_user_on_date=count_courses_completed_by_user_on_date($date,$uid);
                            echo $count_courses_completed_by_user_on_date->count_course;
                            echo ",";
                             ?>]
                        },

                    ]
                  };

                  var myBarChart = new Chart(ctx1, {
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

                  function show_bar_chart_period(){
                  var fromdate=document.getElementById("fromdate").value;
                  var todate=document.getElementById("todate").value;
                  var uid=<?php echo $uid; ?>;
                  // alert(fromdate+todate);
                  // alert(uid);
                  $.ajax({
                  url: 'lib.php',
                  data: {fromdate: fromdate,todate: todate,uid: uid},
                  type: 'post',
                  success: function(data) {
                        // alert("success");
                        // alert(JSON.stringify(data));
                        var logincount=JSON.stringify(data);
                        var num=logincount.replace(/"([^"]+(?="))"/g, '$1');
                        // alert(num);
                        var ctx1 = document.getElementById("Chart").getContext("2d");
                        var data = {
                          labels: [fromdate+" to "+todate],
                          datasets: [
                              {
                                  label: "Logins",
                                  backgroundColor: "blue",
                                  data: [num]

                              },
                              // {
                              //     label: "Course Completions",
                              //     backgroundColor: "red",
                              //     data: [8]
                              // },

                          ]
                        };

                        var myBarChart = new Chart(ctx1, {
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


                  },
                  error: function(data) {
                    alert("error");
                  }
                  });


                  }

                           </script>

                          </div>
                          <div class="span6" style="position:absolute;right: 0;">Courses
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
                            <div style="height:400px;width:400px;">
                            <canvas id="myChart" width="400" height="400"></canvas>
                            </div>
                            <script>
                            function show_pie_chart_month(){
                            var ctx = document.getElementById("myChart").getContext('2d');;
                            var myChart = new Chart(ctx, {
                               type: 'pie',
                               data: {

                                   labels: ['completed courses percentage','not completed courses percentage'],
                                   datasets: [{
                                       label: 'user_report',
                                       data: [<?php echo $completed_courses_percentage; ?>,<?php echo $incompleted_courses_percentage; ?>],


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
                                       // borderColor: [
                                       //     'rgba(255,99,132,1)',
                                       //     'rgba(54, 162, 235, 1)',
                                       //     'rgba(255, 206, 86, 1)',
                                       //     'rgba(75, 192, 192, 1)',
                                       //     'rgba(153, 102, 255, 1)',
                                       //     'rgba(255, 159, 64, 1)'
                                       // ],
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


                        </div>
                      </div>
                    </div>
</div>
</div>
</div></div></div>



                    <div class="tab-pane fade" id="myoverview_courses_reports">
                      <div class="text-xs-center text-center m-t-3">
                        <img class="empty-placeholder-image-lg" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/courses" alt="No future courses" role="presentation">
                        <p class="text-muted m-t-1">Courses Reports</p>
                      </div>
                    </div>


                    <div class="tab-pane fade" id="myoverview_test_reports">
                      <div class="text-xs-center text-center m-t-3">
                        <img class="empty-placeholder-image-lg" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/courses" alt="No past courses" role="presentation">
                        <p class="text-muted m-t-1">Test Reports</p>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="myoverview_group_reports">
                      <div class="text-xs-center text-center m-t-3">
                        <img class="empty-placeholder-image-lg" src="http://www.summitlms.co.za/theme/image.php/lambda/block_myoverview/1513682059/courses" alt="No past courses" role="presentation">
                        <p class="text-muted m-t-1">Group reports</p>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><span class="skip-block-to" id="sb-1"></span></aside>
  </div>

<style>
.studen_overview{
background: #f5f5f5;
border-radius: 10px;
padding: 20px;
width: 97%!important;
}
.student_name{
color: #000!important;
}

.student_name span{
  font-size: 12px;
    text-transform: initial;
    font-weight: 300;
}
.student_detail_area{   margin-left: .5%!important;    }
.detail_list b{ font-size: 16px!important; }
</style>








<?php
// Trigger a logs viewed event.
$event = \report_loglive\event\report_viewed::create(array('context' => $context));
$event->trigger();

echo $output->footer();
