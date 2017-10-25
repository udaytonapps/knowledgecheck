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

if ( $USER->instructor ) {

    $KC_DAO->deleteAllQuestion($SetID);   
    $KC_DAO->deleteKC($SetID);

}

header( 'Location: '.addSession('../index.php') ) ;
