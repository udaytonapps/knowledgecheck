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

    $KC_DAO->deleteLink($linkId);
}

header( 'Location: '.addSession('../index.php') ) ;