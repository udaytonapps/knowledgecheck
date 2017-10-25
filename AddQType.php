<?php
require_once "../config.php";
require_once "dao/KC_DAO.php";
require_once "util/KC_Utils.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $SetID = $_GET["SetID"];

    $Questions = $KC_DAO->getQuestions($SetID);

    

    $set = $KC_DAO->getKC($SetID);

    $Total = count($Questions);
    $Next = $Total + 1;
	$_SESSION["Next"] = $Next;

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li><a href="Qlist.php?SetID=' .$SetID.'">'.$set["KCName"].'</a></li>
            <li>Add New Question</li>
        </ul>
    ');

    ?>

    
        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Adding Question #<?php echo($Next); ?></h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
              
                    <label class="control-label" for="QType">What type of question would you like to add?</label> <br><br>
					<a  href="AddQ.php?SetID=<?php echo $SetID; ?>&QType=Multiple" class="btn btn-success" style="width:300px;" >Multiple Choice</a><br>

                    <a  href="AddQ.php?SetID=<?php echo $SetID; ?>&QType=True/False" class="btn btn-success" style="width:300px;margin-top:5px;">True / False </a><br><br>


                <a href="Qlist.php?SetID=<?php echo $_GET["SetID"];?>" class="btn btn-danger">Cancel</a>

            </div>
        </div>
   
<?php
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();