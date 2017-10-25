<?php
require_once "../../config.php";
require_once "../dao/KC_DAO.php";
require_once "../util/KC_Utils.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$SetID=$_GET["SetID"];
$QID=$_GET["QID"];

if ( $USER->instructor ) {

    $KC_DAO->deleteQuestion($QID);

    $remainingCards = $KC_DAO->getQuestions($SetID);

    usort($remainingCards, array('KC_Utils', 'compareQNum'));

    $QNum = 0;
    foreach ( $remainingCards as $question ) {
        $QNum++;
        $KC_DAO->updateQNumber($question["QID"], $QNum);
    }

    header( 'Location: '.addSession('../Qlist.php?SetID='.$SetID) ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
