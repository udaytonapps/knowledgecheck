<?php
require_once "../../config.php";
require_once("../dao/KC_DAO.php");

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID = $_SESSION["SetID"];
date_default_timezone_set('America/New_York');
$Date2 = date("Y-m-d H:i:s");


$KC_DAO = new KC_DAO($PDOX, $p);

$Questions = $KC_DAO->getQuestions($SetID);
$studentData = $KC_DAO->getUserData($SetID, $USER->id);

if ($studentData["Attempt"] == ""){$Attempt=1;}
else {$Attempt =  $studentData["Attempt"] +1;}


echo $Attempt;

 foreach ( $Questions as $row ) {

$Temp = 'Answer'.$row["QNum"];
$Answer = $_POST[$Temp];	 
$QID = $row["QID"];	

$KC_DAO->addUserData($SetID, $QID, $USER->id, $Answer,$Attempt, $Date2);
 
}


header( 'Location: '.addSession('../Review.php') ) ;
