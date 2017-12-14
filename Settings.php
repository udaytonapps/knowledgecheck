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
include("tool-js.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $SetID = $_GET["SetID"];

    $Questions = $KC_DAO->getQuestions($SetID);   

    $set = $KC_DAO->getKC($SetID);

    $Total = count($Questions);

    $KCName = $set["KCName"];
    $Active = $set["Active"];
	$Random = $set["Random"];

    include("menu.php");

    echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Knowledge Checks</a></li>
                <li>'.$KCName.'</li>
            </ul>
        ');

    ?>

    <form  method="post" action="actions/Settings_Submit.php">

        <div class="row" style="max-width:650px;">
            
			<h3> <span class="fa fa-cog"></span> Settings</h3><br>

           
			<div class="panel-body" >
		
			<div class="col noPadding">
                <div class="form-group">
                    <label class="control-label" for="KCName">Knowledge Check Title</label>
                    <input id="KCName" name="KCName" class="form-control" value="<?php echo($KCName); ?>" required/>
                </div>

                <div class="form-group">
                    <div class="radio">
                        <label><input type="radio" name="Active" value="1" <?php if($Active==1){echo('checked="checked"');}?>/>Publish</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="Active" value="0" <?php if($Active==0){echo('checked="checked"');}?>/>Unpublish</label>
                    </div>
                </div>
                
                
               <div class="form-group">
                    <div class="radio">
                        <label><input type="radio" name="Random" value="1" <?php if($Random==1){echo('checked="checked"');}?>/>Randomize question order for students
</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="Random" value="0" <?php if($Random==0){echo('checked="checked"');}?>/>Do Not Randomize
</label>
                    </div>
                </div>
                
                

                <input type="hidden" id="SetID" name="SetID" value="<?php echo $_GET["SetID"];?>"/>

                <input class="btn btn-primary" type="submit" value="Update Knowledge Check" />
                <a href="index.php" class="btn btn-danger" style="margin-right:3px;">Cancel</a>
                <a href="actions/DeleteKC.php?SetID=<?php echo($SetID); ?>" class="btn btn-danger pull-right" onclick="return ConfirmDelete();"><span class="fa fa-trash-o"></span> Delete</a>
            </div>
			</div></div>
      
    </form>

    <?php

} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();