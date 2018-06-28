<?php
require_once('../config.php');
require_once('dao/KC_DAO.php');

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;
$_SESSION["UserID"]= $USER->id;
$_SESSION["Page"]= "manage";
$LastName = $USER->lastname;
$FirstName = $USER->firstname;
//echo "Site ID: ".$CONTEXT->id;
$_SESSION["SetID"]=0;
$_SESSION["N1"]=1;$_SESSION["N2"]=1;$_SESSION["N3"]=1;




if ( $USER->instructor ) {

    include("menu.php");


    $allKC = $KC_DAO->getAll_KC($CONTEXT->id);


    echo('<h2>Knowledge Check');

    $linkId = $LINK->id;

    $newSetID = $KC_DAO->getSetIDForLink($linkId);

    $Hide=0;
    if (isset($newSetID["SetID"])) {
        $newKC = $KC_DAO->getKC($newSetID["SetID"]);
        $Hide=1;
        echo('<br /><small><span class="fa fa-link"></span> '.$newKC["KCName"].' is linked.</small>');
    }

    echo('</h2>');

    echo('<div class="row" id="kc-row">');

    foreach ( $allKC as $KC ) {
        if ($KC["Visible"]) {

            if($KC["Active"] == 0) {
                $flag = 1;
                $panelClass = 'default';
                $pubAction = 'Unpublished';
            } else {
                $flag = 0;
                $panelClass = 'success';
                $pubAction = 'Published';
            }

            $questions = $KC_DAO->getQuestions($KC["SetID"]);
            $totalPoints = 0;
            foreach($questions as $question) {
                $totalPoints = $totalPoints + $question["Point"];
            }
            $exist = $KC_DAO->userDataExists($KC["SetID"], $USER->id);
            $page="ManageKCs.php";
            echo('
                <div class="col-sm-6">
                <div class="row" style="border:1px solid #ccc;border-radius:4px;background-color:#eee;">
                    <div class="col-sm-3 text-center" style="padding:.5em;">
                        <span class="fa fa-check fa-4x text-'.$panelClass.'"></span>
                        <br />
                        <a class="btn btn-'.$panelClass.'" style="margin-top: 1em;" href="actions/Publish.php?SetID='.$KC["SetID"].'&Flag='.$flag.'&Page=' . $page . '">'.$pubAction.'</a>
                    </div>
                    <div class="col-sm-7" style="background-color:#fff;padding:1em;">
                        <h3 style="margin-top:0;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">
                            <a href="Qlist.php?SetID='.$KC["SetID"].'">
                                <span class="fa fa-pencil-square-o"></span>
                                '.$KC["KCName"].'
                            </a>
                        </h3>
                        <span>'.count($questions).' Questions / '.$totalPoints.' Total Points</span>
                        <div class="row" style="margin-top:1em;">
                            <div class="col-xs-6 text-center" style="border-right:1px solid #ccc;">
                            <h4>
                                <a href="Usage.php?SetID='.$KC["SetID"].'" ');if(count($questions) == 0){echo('class="disabled"');}echo('>
                                <span class="fa fa-bar-chart"></span>
                                Usage
                                </a>
                            </h4>
                            </div>
                            <div class="col-xs-6 text-center">
                                <h4>
                                    <a href="Settings.php?SetID='.$KC["SetID"].'">
                                        <span class="fa fa-cog"></span>
                                        Settings
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center" style="padding:.5em;">
                        <div style="font-size: 1.2em;padding-top:.5em;">
                            <a href="Take.php?SetID='.$KC["SetID"].'" ');if(count($questions) == 0){echo('class="disabled"');}echo('>
                                <span class="fa fa-check-square-o"></span><br />
                                <small>Preview</small>
                            </a>
                        </div>
                        <div style="font-size:1.2em;margin-top:.5em;">
                            <a href="Review.php?SetID='.$KC["SetID"].'" ');if($exist != 1){echo('class="disabled"');}echo('>
                                <span class="fa fa-flag"></span><br />
                                <small>Feedback</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ');
        }
    }
}