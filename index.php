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
$LastName = $USER->lastname;
$FirstName = $USER->firstname;
//echo "Site ID: ".$CONTEXT->id;
$_SESSION["SetID"]=0;
$_SESSION["N1"]=1;$_SESSION["N2"]=1;$_SESSION["N3"]=1;




if ( $USER->instructor ) {

    include("menu.php");
    include("instructor-home.php");

}else{ // student

	$a = $KC_DAO->checkStudent($CONTEXT->id, $USER->id);
	if($a["UserID"] == ""){	$b = $KC_DAO->addStudent($USER->id, $CONTEXT->id, $LastName, $FirstName);}
	  $linkId = $LINK->id;		
     $newSetID = $KC_DAO->getSetIDForLink($linkId);
		
	include("student-home.php");
   	
	
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();