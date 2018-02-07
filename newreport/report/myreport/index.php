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
$all_quizes_report=all_quizes_report();

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
$PAGE->set_context($context);
$PAGE->set_title("$coursename: $strlivelogs ($strupdatesevery)");
$PAGE->set_heading("$coursename: $strlivelogs ($strupdatesevery)");

$output = $PAGE->get_renderer('report_loglive');
echo $output->header();
// echo $output->reader_selector($renderable);
// echo $output->toggle_liveupdate_button($renderable);
?>

<style>
#livelogs-pause-button{  display: none;        }
</style>

  <div role="main" id="yui_3_17_2_1_1515148855696_610"><span id="maincontent"></span>
    <aside id="block-region-content" class="block-region" data-blockregion="content" data-droptarget="1"><a class="skip skip-block" id="fsb-1" href="#sb-1">Skip Course overview</a>
      <div id="inst18" class="block_myoverview  block" role="complementary" data-block="myoverview" data-instanceid="18" aria-labelledby="instance-18-header" data-dockable="1">
        <div class="header">
          <div class="title" id="yui_3_17_2_1_1515148855696_424">
            <div class="block_action">   </div>
            <h2 id="instance-18-header">Reports</h2></div>
        </div>
        <div class="content" id="yui_3_17_2_1_1515148855696_609">
          <div id="block-myoverview-5a4f563412b3d5a4f563412b851" class="block-myoverview" data-region="myoverview">
            <ul id="block-myoverview-view-choices-5a4f563412b3d5a4f563412b851" class="nav nav-tabs" role="tablist">
              <li class="nav-item active">
                <a class="nav-link" href="#myoverview_courses_view" role="tab" data-toggle="tab" data-tabname="courses">
                Types
            </a>
              </li>
            </ul>

            <!-- <div class="tab-content" id="yui_3_17_2_1_1515148855696_608">
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
                    </div>
                  </div>

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
              </div> -->
              <div role="tabpanel" class="tab-pane fade in active" id="myoverview_courses_view">
                <div id="courses-view-5a4f563412b3d5a4f563412b851" data-region="courses-view">
                  <div class="row text-center">
                    <div class="btn-group m-b-1" role="group" data-toggle="btns">
                      <a class="btn btn-default active" href="#myoverview_user_reports" data-toggle="tab">
                              User reports
                            </a>
                      <a class="btn btn-default" href="#myoverview_courses_reports" data-toggle="tab">
                                Course reports
                            </a>
                      <a class="btn btn-default" href="#myoverview_test_reports" data-toggle="tab">
                                Test Reports
                      </a>
                      <a class="btn btn-default" href="#myoverview_group_reports" data-toggle="tab">
                                Group Reports
                      </a>


                    </div>
                  </div>

                  <div class="tab-content" id="yui_3_17_2_1_1515148855696_607">

                    <div class="tab-pane active fade in" id="myoverview_user_reports">
                      <div id="courses-view-in-progress" data-status="1">
                        <div id="pc-for-in-progress" data-region="paging-content">
                          <div data-region="paging-content-item" data-page="1" class=" " id="yui_3_17_2_1_1515148855696_606">
                            <table cellspacing="0" class="flexible   generalbox" aria-live="polite">
                              <thead>
                                <tr style="background: #d9b72f;color: #000;">
                                  <th class="header c0" scope="col" style="padding: 10px;">USER
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c1" scope="col" style="padding: 10px;">USER TYPE
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c2" scope="col" style="padding: 10px;">LAST LOGIN
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c3" scope="col" style="padding: 10px;">ASSIGNED COURSES
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c4" scope="col" style="padding: 10px;">COMPLETED COURSES
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c5" scope="col" style="padding: 10px;">OPERATIONS
                                    <div class="commands"></div>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>

                              <?php foreach ($all_users_report as $user_report) {
                                $lastlogin = $user_report->lastlogin;
                                if($lastlogin == 0){
                                  $access = "Never Access";
                                }else{
                                  $date = new DateTime("@$lastlogin");
                                  $access = $date->format('d/m/Y H:i:s') . "\n";
                                }
                                //print_r($user);
                            ?>
                                <tr class="" id="">
                                 <td class="cell c0"  style="padding: 10px;"><a href="userreports2.php?modp=userreports2&uid=<?php echo $user_report->id;?>"><?php echo $user_report->username; ?></a></td>
                                  <td class="cell c1"  style="padding: 10px;"><?php echo $user_report->roletype; ?></td>
                                  <td class="cell c2"  style="padding: 10px;"><?php echo $access; ?></td>
                                  <td class="cell c3"  style="padding: 10px;" ><?php echo $user_report->assigned_courses; ?></td>
                                  <td class="cell c4" style="padding: 10px;" ><?php echo $user_report->completed_courses; ?></td>
                                  <td class="cell c5"  style="padding: 10px;" >-</td>
                                </tr>

                              <?php   } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                       </div>
                    </div>


                  <div class="tab-pane fade" id="myoverview_courses_reports">
                      <div id="courses-view-in-progress" data-status="1">
                        <div id="pc-for-in-progress" data-region="paging-content">
                          <div data-region="paging-content-item" data-page="1" class=" " id="yui_3_17_2_1_1515148855696_606">
                            <table cellspacing="0" class="flexible   generalbox" aria-live="polite">
                              <thead>
                                <tr style="background: #d9b72f;color: #000;">
                                  <th class="header c0" scope="col" style="padding: 10px;">COURSE
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c1" scope="col" style="padding: 10px;">COURSE CATEGORY
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c2" scope="col" style="padding: 10px;">USERS ASSIGNED
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c3" scope="col" style="padding: 10px;">USERS COMPLETED
                                    <div class="commands"></div>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>

                              <?php

                              foreach ($all_courses_report as $course_report) {

                            ?>
                                <tr class="" id="">
                                 <td class="cell c0" style="padding: 10px;"><a href="course_report.php?modp=course_report&courseid=<?php echo $course_report->courseid;?>"><?php echo $course_report->course_name; ?></a></td>
                                  <td class="cell c1" style="padding: 10px;"><?php echo $course_report->category_name; ?></td>
                                  <td class="cell c2"  style="padding: 10px;"><?php echo $course_report->assigned_user_count; ?></td>
                                  <td class="cell c3"  style="padding: 10px;"><?php echo $course_report->completed_user_count; ?></td>
                               </tr>     <!--id="report_loglive_r0_c0"  -->

                              <?php
                             }
                               ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                       </div>
                    </div>



                    <div class="tab-pane fade" id="myoverview_test_reports">
                      <div id="courses-view-in-progress" data-status="1">
                        <div id="pc-for-in-progress" data-region="paging-content">
                          <div data-region="paging-content-item" data-page="1" class=" " id="yui_3_17_2_1_1515148855696_606">
                            <table cellspacing="0" class="flexible   generalbox" aria-live="polite">
                              <thead>
                                <tr style="background: #d9b72f;color: #000;">
                                  <th class="header c0" scope="col" style="padding: 10px;">QUIZ
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c1" scope="col" style="padding: 10px;">ATTEMPTED USERS
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c2" scope="col" style="padding: 10px;">PASSED USERS
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c5" scope="col" style="padding: 10px;">TOTAL MARKS
                                    <div class="commands"></div>
                                  </th>
                                  <th class="header c6" scope="col" style="padding: 10px;">PASSING MARKS
                                    <div class="commands"></div>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>

                              <?php

                              foreach ($all_quizes_report as $quiz_report) {
                                   $passed_users=passed_users($quiz_report->quizid);

                            ?>
                                <tr class="" id="">
                                 <td class="cell c0" style="padding: 10px;"><a href="quiz_report.php?modp=quiz_report&quizid=<?php echo $quiz_report->quizid;?>"><?php echo $quiz_report->quiz_name; ?></a></td>
                                  <td class="cell c1" style="padding: 10px;"><?php echo $quiz_report->user_count; ?></td>
                                  <td class="cell c2"  style="padding: 10px;"><?php print_r($passed_users->passed_users_count); ?></td>
                                  <td class="cell c5"  style="padding: 10px;"><?php echo $quiz_report->total_marks; ?></td>
                                  <td class="cell c6"  style="padding: 10px;"><?php echo $quiz_report->mingrade; ?></td>
                               </tr>     <!--id="report_loglive_r0_c0"  -->

                              <?php
                             }
                               ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
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












<?php
// Trigger a logs viewed event.
// $event = \report_loglive\event\report_viewed::create(array('context' => $context));
// $event->trigger();

echo $output->footer();
