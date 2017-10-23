<?php
require_once "../config.php";
require_once "dao/KC_DAO.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$linkId = $LINK->id;

if ( $USER->instructor ) {

    include("menu.php");

    $allSets = $KC_DAO->getAll_KC($CONTEXT->id);

    $previousLink = $KC_DAO->getLinkedSet($linkId);

    echo('<form action="actions/LinkToSet_Submit.php" method="post">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Send Students to a Knowledge Check</h3>
            </div>

            
            
            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label for="CardSet">Link to Knowledge Check</label>
                    <select class="form-control" id="linkToSet" name="linkToSet">');

                        foreach($allSets as $set) {

                            if(isset($previousLink["SetID"]) && $previousLink["SetID"] == $set["SetID"]) {
                                echo('<option value="'.$set["SetID"].'" selected>'.$set["KCName"].'</option>');
                            } else {
                                echo('<option value="'.$set["SetID"].'">'.$set["KCName"].'</option>');
                            }

                        }

                echo('</select>
                </div>
                <input class="btn btn-primary" type="submit" value="Link to Knowledge Check" /> 
                <a href="actions/UnlinkFromSet_Submit.php" class="btn btn-danger">Unlink</a>
            </div>
        </div>

        </form>');
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();