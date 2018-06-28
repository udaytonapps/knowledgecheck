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

$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];
$SetID = $_SESSION["SetID"];
$set = $KC_DAO->getKC($SetID);
$Total=0;

if(isset($_GET["Shortcut"])) {
    $shortCut = $_GET["Shortcut"];
} else {
    $shortCut = 0;
}

$_SESSION["Shortcut"] = $shortCut;

if ( $USER->instructor ) {
    include("menu.php");
} else {
    if ($shortCut == 0) {
        if ( $USER->instructor ) {
            $Page = $_SESSION["Page"];
        echo('
            <ul class="breadcrumb">');
            if($Page === "index"){
                echo ('<li><a href="index.php">All Knowledge Checks</a></li>');
            }else {
                echo ('<li><a href="ManageKCs.php">All Knowledge Checks</a></li>');
            }
        echo ('<li>' .$set["KCName"].'</li>
            </ul>
        ');
        } else {
            echo('
                <ul class="breadcrumb">
                    <li><a href="index.php">All Knowledge Checks</a></li>
                    <li>' . $set["KCName"] . '</li>
                </ul>
            ');
        }
    }
}

if ($shortCut == 0) {
    if ( $USER->instructor ) {
        $Page = $_SESSION["Page"];
        echo('
            <ul class="breadcrumb">');
        if($Page === "index"){
            echo ('<li><a href="index.php">All Knowledge Checks</a></li>');
        }else {
            echo ('<li><a href="ManageKCs.php">All Knowledge Checks</a></li>');
        }
        echo ('<li>' .$set["KCName"].'</li>
            </ul>
        ');
    } else {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Knowledge Checks</a></li>
                <li>' . $set["KCName"] . '</li>
            </ul>
        ');
    }
}

    ?>
       

   <div class="panel-body" style="text-align: center;" >

	   <p >Your submission has been received. You can review your results or return to the main page below.</p><br>




<a  href="Review.php?SetID=<?php echo $SetID;?>"  class='btn btn-success' style='width:220px;'>Review Results</a><br><br>


<a  href="index.php?SetID=<?php echo $SetID;?>" class='btn btn-primary' style='width:220px;'>Return to main page</a>
</div>
<?php	 
	 
$OUTPUT->footerStart();

include("tool-footer.html");


$OUTPUT->footerEnd();