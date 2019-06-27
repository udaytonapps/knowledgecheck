<?php
require_once "../../config.php";
require_once "../dao/KC_DAO.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$SetID=$_GET["SetID"];

$QID=$_GET["QID"];
$QNum = $_GET["QNum"];

$Flag = $_GET["Flag"];

if ($Flag){
    $NewQNum = $QNum-1;
} else {
    $NewQNum = $QNum+1;
}

if ( $USER->instructor ) {

    $swapCard = $KC_DAO->getQuestionBySetAndNumber($SetID, $NewQNum);

    $KC_DAO->updateQNumber($swapCard["QID"], $QNum);
    $KC_DAO->updateQNumber($QID, $swapCard["QNum"]);

    header( 'Location: '.addSession('../Qlist.php?SetID='.$SetID) ) ;
} else {
    header( 'Location: '.addSession('../index.php') ) ;
}