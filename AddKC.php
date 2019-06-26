<?php
require_once "../config.php";
require_once('dao/KC_DAO.php');

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
    $linkId = $LINK->id;

    $oldSetID = $KC_DAO->getSetIDForLink($linkId);

    if (isset($oldSetID["SetID"])) {
        echo ('<p>Knowledge check is already created</p>');
    }
    ?>

    <form action="actions/AddKC_Submit.php" method="post">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Create New Knowledge Check</h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label class="control-label" for="KCName">Knowledge Check Title</label>
                    <input id="KCName" name="KCName" class="form-control" required/>
                </div>

                <input name="CourseName" id="CourseName" type="hidden" value="<?php echo($_SESSION["CourseName"]); ?>"/>

                <input class="btn btn-primary" type="submit" value="Add Knowledge Check" />
            </div>
        </div>

    </form>

    <?php

} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();