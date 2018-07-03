<?php
require_once "../../config.php";
require_once('../dao/KC_DAO.php');

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$QType = $_POST["QType"];
$Point = $_POST["Point"];
$FR = $_POST["FR"];
$FW = $_POST["FW"];
$RA = $_POST["RA"];

$FR = str_replace("'", "&#39;", $_POST["FR"]);
$FW = str_replace("'", "&#39;", $_POST["FW"]);

$SetID=$_POST["SetID"];

$Question = str_replace("'", "&#39;", $_POST["Question"]);
$Answer = str_replace("'", "&#39;", $_POST["Answer"]);

$QNum = $_POST["QNum"];

if ( $USER->instructor ) {

	if ($QType == "Multiple"){
		$A=$_POST["A"];$A = str_replace("'", "&#39;", $_POST["A"]);
		$B=$_POST["B"];$B = str_replace("'", "&#39;", $_POST["B"]);
		$C=$_POST["C"];$C = str_replace("'", "&#39;", $_POST["C"]);
		$D=$_POST["D"];$D = str_replace("'", "&#39;", $_POST["D"]);
		$KC_DAO->createQuestion($SetID, $QNum, $Question, $Answer, $QType,$A, $B, $C, $D, $Point, $FR, $FW, $RA);
	}
	
	else if ($QType == "True/False"){$KC_DAO->createQuestion2($SetID, $QNum, $Question, $Answer, $QType, $Point, $FR, $FW, $RA);}
    if($_SESSION["Page"] !== "index") {
        header('Location: ' . addSession('../Qlist.php?SetID=' . $SetID));
    } else {
        header( 'Location: '.addSession('../index.php') ) ;
    }
	
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}