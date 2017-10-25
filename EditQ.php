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

    $question = $KC_DAO->getQuestionById($_GET["QID"]);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li><a href="Qlist.php?SetID=' .$SetID.'">'.$set["KCName"].'</a></li>
            <li>Edit Question</li>
        </ul>
    ');

    ?>

   
    <form method="post" action="actions/EditQ_Submit.php">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Editing Question #<?php echo($question["QNum"]); ?></h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
               
                <div class="form-group">
                    <label class="control-label" for="Question">Question</label>
                    <textarea class="form-control" name="Question" id="Question" rows="3" autofocus required><?php echo($question["Question"]); ?></textarea>
                </div>

               

                <div class="form-group">
                   <label class="control-label" for="Question" style="padding-bottom: 10px;">Answer</label><br>
                   
                   <?php 
					if($QType == "True/False"){
					?>
                   
                   
                    <input type="radio" value="True" name="Answer" <?php if($question["Answer"] == "True"){?>checked <?php } ?>> 
                   True.<br>
  					<input type="radio" value="False" name="Answer" <?php if($question["Answer"] == "False"){?>checked <?php } ?>> 
                   False.<br>

                    <?php
					}else{
						?>
                    
                   <input type="radio" value="A" name="Answer" <?php if($question["Answer"] == "A"){?>checked <?php } ?>> 
                   A. <input class="form-control answer" name="A" id="A" value="<?php echo($question["A"]); ?>"><br>

                   <input type="radio" value="B" name="Answer" <?php if($question["Answer"] == "B"){?>checked <?php } ?>> 
                   B. <input class="form-control answer" name="B" id="B" value="<?php echo($question["B"]); ?>"><br>

                   <input type="radio" value="C" name="Answer" <?php if($question["Answer"] == "C"){?>checked <?php } ?>>
                   C. <input class="form-control answer" name="C" id="C" value="<?php echo($question["C"]); ?>"><br>

                   <input type="radio" value="D" name="Answer" <?php if($question["Answer"] == "D"){?>checked <?php } ?>> 
                   D. <input class="form-control answer" name="D" id="D" value="<?php echo($question["D"]); ?>"><br>

                   <?php
					}
						?>
                   
                   
					<div class="ML"><input type="checkbox" value="1" name="RA" <?php if($question["RA"]){?>checked <?php } ?>>  Randomize Answers</div>
                    
                </div>
                
                
                
                
                
                
                
                
                
                <div class="form-group row">    
				   
						<label for="ex1">Point Value</label>
						<input class="form-control" id="ex1" type="text" name="Point" value="<?php echo($question["Point"]); ?>" style="width:50px;text-align: center;">
				  
				</div>
                
                
                
                
                
                 <div class="form-group">
                    <label class="control-label" for="FR">Correct Feedback</label>
                    <textarea class="form-control" name="FR" id="FR" rows="2" autofocus ><?php echo($question["FR"]); ?></textarea>
                </div>
                
                  <div class="form-group">
                    <label class="control-label" for="FR">Incorrect Feedback</label>
                    <textarea class="form-control" name="FW" id="FW" rows="2" autofocus ><?php echo($question["FW"]); ?></textarea>
                </div>
                
                

                <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                <input type="hidden" name="QID" value="<?php echo $_GET["QID"];?>"/>
                 <input type="hidden" name="QType" value="<?php echo $_GET["QType"];?>"/>

                <input type="submit" value="Update Question" class="btn btn-primary">
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