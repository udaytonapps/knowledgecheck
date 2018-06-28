<?php
require_once "../../config.php";
require_once "../dao/KC_DAO.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

if ( $USER->instructor ) {

    $linkId = $LINK->id;

    $oSetId = $_GET["SetID"];

    $otherKC = $KC_DAO->getKC($oSetId);

    $KCName = $otherKC["KCName"];

    $newSetId = $KC_DAO->createKC($USER->id, $CONTEXT->id, $KCName);

    $otherKC = $KC_DAO->getQuestions($oSetId);
    $QNum = 1;
	foreach ($otherKC as $row) {		
		$KC_DAO->createQuestion($newSetId,$QNum,$row["Question"],$row["Answer"],$row["QType"],$row["A"],$row["B"],$row["C"],$row["D"], $row["Point"],$row["FR"],$row["FW"]); 
		++$QNum;
    }
    $KC_DAO->saveOrUpdateLink($oSetId, $linkId);
}

header( 'Location: '.addSession('../index.php') ) ;