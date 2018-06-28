<?php
require_once "../../config.php";
require_once "../dao/KC_DAO.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$SetID=$_POST["SetID"];
$Active = $_POST["Active"];
$Random = $_POST["Random"];
$Page = $_SESSION["Page"];
$KCName = str_replace("'", "&#39;", $_POST["KCName"]);

if ( $USER->instructor ) {

    $KC_DAO->updateKC($SetID, $KCName, $Active, $Random);
}
if($Page === "index"){
    header( 'Location: '.addSession('../index.php') ) ;
}else {
    header( 'Location: '.addSession('../ManageKCs.php') ) ;
}