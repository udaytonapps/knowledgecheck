<?php
require_once "../../config.php";
require_once('../dao/KC_DAO.php');

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$KCName = str_replace("'", "&#39;", $_POST["KCName"]);

if ( $USER->instructor ) {
    $linkId = $LINK->id;

    $oldSetID = $KC_DAO->getSetIDForLink($linkId);

    if (isset($oldSetID["SetID"])) {
       // $newSetId = $KC_DAO->createKC($USER->id, $CONTEXT->id, $KCName);
    }else{
        $newSetId = $KC_DAO->createKC($USER->id, $CONTEXT->id, $KCName);
        $KC_DAO->saveOrUpdateLink($newSetId, $linkId);
    }

    header( 'Location: '.addSession('../index.php') ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}