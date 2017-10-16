<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();

$p = $CFG->dbprefix;

// Start of the output
$OUTPUT->header();

?>
    <!-- Our main css file that overrides default Tsugi styling -->
    <link rel="stylesheet" type="text/css" href="styles/main.css">
<?php

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    include("menu.php");
    include("instructor-home.php");

} else { // student

    include("student-home.php");
}

$OUTPUT->footerStart();
?>
    <!-- Our main javascript file for tool functions -->
    <script src="scripts/main.js" type="text/javascript"></script>
<?php
$OUTPUT->footerEnd();