<?php
require_once "../config.php";
require_once "dao/KC_DAO.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);
$QType = $_GET["QType"];
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $SetID = $_GET["SetID"];

    $set = $KC_DAO->getKC($SetID);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li><a href="Qlist.php?SetID=' .$SetID.'">'.$set["KCName"].'</a></li>
            <li>Add New Question</li>
        </ul>
    ');

    ?>


    <form method="post" action="actions/AddQ_Submit.php">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Add Question #<?php echo($_SESSION["Next"]); ?></h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
               
                <div class="form-group">
                    <label class="control-label" for="Question">Question</label>
                    <textarea class="form-control" name="Question" id="Question" rows="3" autofocus required></textarea>
                </div>

               

                <div class="form-group">
                   <label class="control-label mb" for="Question" >Answer</label><br>
                   
                   <?php 
					if($QType == "True/False"){
					?>
                   
                   
                    <input type="radio" value="True" name="Answer" > True<br>
  					<input type="radio" value="False" name="Answer"> False<br>

                    <?php
					}else{
						?>
                  
                   <input type="radio" value="A" name="Answer" > 
                   A. <input class="form-control answer" name="A" id="A" value=""><br>

                   <input type="radio" value="B" name="Answer"> 
                   B. <input class="form-control answer" name="B" id="B" value=""><br>

                   <input type="radio" value="C" name="Answer">
                   C. <input class="form-control answer" name="C" id="C" value=""><br>

                   <input type="radio" value="D" name="Answer" > 
                   D. <input class="form-control answer" name="D" id="D" value=""><br>

                   <?php
					}
						?>
                 
					<div class="ML"><input type="checkbox" value="1" name="RA">  Randomize Answers</div>   
                </div>
				<div class="form-group row">    
				   
						<label for="ex1">Point Value</label>
						<input class="form-control" id="ex1" type="text" name="Point" style="width:50px; text-align:center;">
				  
				</div>
                
                
                 <div class="form-group">
                    <label class="control-label" for="FR">Correct Feedback</label>
                    <textarea class="form-control" name="FR" id="FR" rows="2" autofocus ></textarea>
                </div>
                
                  <div class="form-group">
                    <label class="control-label" for="FR">Incorrect Feedback</label>
                    <textarea class="form-control" name="FW" id="FW" rows="2" autofocus ></textarea>
                </div>
                
                
                

                <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                <input type="hidden" name="QID" value="<?php echo $_GET["QID"];?>"/>
                 <input type="hidden" name="QType" value="<?php echo $_GET["QType"];?>"/>
                 <input type="hidden" name="QNum" value="<?php echo $_SESSION["Next"];?>"/>

                <input type="submit" value="Add Question" class="btn btn-primary">
                <a href="Qlist.php?SetID=<?php echo $_GET["SetID"];?>" class="btn btn-danger">Cancel</a>

            </div>
        </div>
    </form>

    <?php
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();