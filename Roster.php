<?php
require_once "../config.php";
require_once "dao/KC_DAO.php";
require_once "util/KC_Utils.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    include("menu.php");

    $SetID = $_GET["SetID"];

    $set = $KC_DAO->getKC($SetID);

    echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All knowledge check</a></li>
                <li>' .$set["KCName"].'</li>
            </ul>
        ');

    $Questions = $KC_DAO->getQuestions($SetID);
    $totalQuestions = count($Questions);

    $hasRosters = LTIX::populateRoster(false);
	
	

    if ($hasRosters) {

        echo('<div class="row">
                  <div class="col-sm-12">
                      <h3><a href="actions/ExportActivity.php?SetID='.$SetID.'" class="btn btn-link pull-right">Export Usage to Excel</a><span class="fa fa-bar-chart"></span> '.$set["KCName"].' Usage</h3>
                  </div>
              </div>');

        echo('<div class="row"><div class="col-sm-4"><h4>Student Name</h4></div><div class="col-md-6"><h4>Progress</h4></div></div>');

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('KC_Utils', 'compareStudentsLastName'));

        foreach($rosterData as $student) {
            // Only want students
            if ($student["role"] == 'Learner') {
                echo('<div class="row">
                    <div class="col-sm-4">'.$student["person_name_family"].', '.$student["person_name_given"].'</div>');

                $numberCompleted = $KC_DAO->getNumberOfSeenCards($student["user_id"], $SetID);

                $percentComplete = $numberCompleted / $totalQuestions * 100;

                if($percentComplete < 25) {
                    $progressClass = 'danger';
                } else if ($percentComplete < 75) {
                    $progressClass = 'warning';
                } else {
                    $progressClass = 'success';
                }

                echo('<div class="col-sm-6">
                    <div class="progress">
                        <div class="progress-bar progress-bar-'.$progressClass.'" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentComplete.'%">
                            '.$numberCompleted.' / '.$totalQuestions.' Cards Viewed
                        </div>
                    </div>
                  </div>
                </div>
            ');
            }
        }

    }
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();