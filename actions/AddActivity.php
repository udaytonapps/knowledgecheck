<?php
require_once "../../config.php";
require_once("../dao/KC_DAO.php");

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID = $_SESSION["SetID"];
$QID = $_SESSION["QID"];

$KC_DAO = new KC_DAO($PDOX, $p);

$KC_DAO->updateActivityForUser($SetID, $QID, $USER->id);

exit;