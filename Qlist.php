<?php
require_once "../config.php";
require_once('dao/KC_DAO.php');
require_once('util/KC_Utils.php');

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

    $questions = $KC_DAO->getQuestions($SetID);
	
	$tPoints=0;
	foreach ( $questions as $row ) {
		$tPoints = $tPoints + $row["Point"];
	}
	
	

    $set = $KC_DAO->getKC($SetID);
	
	$_SESSION["SetID"] = $set["SetID"];
	
    $Total = count($questions);
	
    $Next = $Total + 1;
	$_SESSION["Next"] = $Next;
	

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li>' .$set["KCName"].'</li>
        </ul>
        
        <div>

         <div id="flip">
            <p class="btn btn-success">Add New Question</p>
        </div>
		<div id="panel">
                   <a  href="Qlist.php?SetID='.$_SESSION["SetID"].'&QType=Multiple" class="btn btn-info" >Multiple Choice</a> 
                    <a  href="Qlist.php?SetID='.$_SESSION["SetID"].'&QType=True/False" class="btn btn-info" ">True / False </a>

            </div>

        <h2>Questions in "'.$set["KCName"].'" <span style="float:right;padding:10px;font-size:12px; color:white; background-color:gray;">'.$Total.' Questions / '.$tPoints.' Points</span></h2>
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no questions in this knowledge check.</em></p>');
    } else {
     //   
        $QNum = 1;
		echo ('<div class="panel panel-default " style="border:0px; ">');
        foreach ( $questions as $row ) {

		
		echo('                      
                   
          <div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" >');
			
            if($QNum != 1) {
                echo('
                            <a href="actions/Move.php?QID=' . $row["QID"] . '&QNum=' . $row["QNum"] . '&SetID=' . $_GET["SetID"] . '&Flag=1">
                                <span class="fa fa-chevron-circle-up fa-2x"></span>
                            </a>
                ');
            }
            if($QNum != $Total) {
                echo('
                            <a href="actions/Move.php?QID=' . $row["QID"] . '&QNum=' . $row["QNum"] . '&SetID=' . $_GET["SetID"] . '&Flag=0">
                                <span class="fa fa-chevron-circle-down fa-2x"></span>
                            </a>
                ');
            }

			echo('</div>	
			<div class="col-sm-1 noPadding" style="width:30px;">');
			$colorA=""; $colorB=""; $colorC=""; $colorD="";
			if($row["Point"] == 1){$Msg2="Point";}
			else{$Msg2 = "Points";}
			
			if($row["QType"] =="Multiple"){	$Msg="Multiple Choice - ".$row["Point"]." ".$Msg2;}
			else{$Msg="True/False - ".$row["Point"]." ".$Msg2;}
			echo '<h3>'.$QNum.'</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div style="color:lightgray;font-style:italic;margin-bottom:10px;">'.$Msg.'</div>');
        	
		  echo $row["Question"].'</div>	<div class="col-sm-4 " >';
			
			
			if($row["QType"] =="Multiple"){				
				
				if ($row["Answer"] =="A"){$colorA="style='color:red; font-weight:bold;'";}
				else if ($row["Answer"] =="B"){$colorB="style='color:red; font-weight:bold;'";}
				else if ($row["Answer"] =="C"){$colorC="style='color:red; font-weight:bold;'";}
				else if ($row["Answer"] =="D"){$colorD="style='color:red; font-weight:bold;'";}
				
				echo('<div '.$colorA.' >A. '.$row["A"].'</div>');
				echo('<div '.$colorB.' >B. '.$row["B"].'</div>');
				
				if($row["C"] !=""){echo('<div '.$colorC.' >C. '.$row["C"].'</div>');}
				if($row["D"] !=""){echo('<div '.$colorD.' >D. '.$row["D"].'</div>');}
			}
			else {
				
				if ($row["Answer"] =="True"){$colorA="style='color:red; font-weight:bold;'";}
				else if ($row["Answer"] =="False"){$colorB="style='color:red; font-weight:bold;'";}
				
			
								
				echo('	<div '.$colorA.' >True</div>
						<div '.$colorB.' >False</div>						
					');
			}
			
	
			echo ('
			
			
			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;"><a class="btn btn-danger pull-right" href="actions/deleteQ.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'" onclick="return ConfirmdeleteQuestion();"><span class="fa fa-trash-o"></span></a>
            <a class="btn btn-primary pull-right" href="EditQ.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'&QType='.$row["QType"].'"><span class="fa fa-pencil"></span></a></div>
							
                    
                </div>
           

        ');
           
            $QNum++;
        }
		
// add new --------------------------------------------------------
echo (' 	<form method="post" action="actions/AddQ_Submit.php">');		
	
if(isset($_GET["QType"])){
	echo('

	<div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" >');
			
			echo('</div>	
			<div class="col-sm-1 noPadding" style="width:30px;">');
			
			
			if($_GET["QType"] =="Multiple"){	$Msg="Multiple Choice - ";}
			else{$Msg="True/False - ";}
			echo '<h3>'.$QNum.'</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div style="color:lightgray;font-style:italic;margin-bottom:10px; width:195px;">'.$Msg.' <span style="float:right">  Point(s)</span><input class="form-control" id="ex1" type="text" name="Point" style="width:30px; height:25px; text-align:center; margin-top:-25px;margin-left:110px;padding:0px;">
			</div>');
        	
		  echo '<textarea class="form-control" name="Question" id="Question" rows="2" autofocus required></textarea><br>

	      
		  
		  <div id="flip2">
            <p class="btn btn-info">Add Feedback</p>
        </div>
		<div id="panel2">
                  	  <br>

                    <label class="control-label" for="FR">Correct Feedback</label>
                    <textarea class="form-control" name="FR" id="FR" rows="2" autofocus ></textarea><br>

              
                
                    <label class="control-label" for="FR">Incorrect Feedback</label>
                    <textarea class="form-control" name="FW" id="FW" rows="2" autofocus ></textarea>
               
				

            </div>
		  
		  
		  












</div>



		  <div class="col-sm-4 " >';
			
			
			if($_GET["QType"] =="Multiple"){				
				
			echo('
			 <div style="padding:5px;"><input type="radio" value="A" name="Answer" >A. <input class="form-control answer" name="A" id="A" value=""></div>
<div style="padding:5px;">
                   <input type="radio" value="B" name="Answer"> 
                   B. <input class="form-control answer" name="B" id="B" value=""></div>
<div style="padding:5px;">
                   <input type="radio" value="C" name="Answer">
                   C. <input class="form-control answer" name="C" id="C" value=""></div>
<div style="padding:5px;">
                   <input type="radio" value="D" name="Answer" > 
                   D. <input class="form-control answer" name="D" id="D" value=""></div>

                  
                  <div class="ML"><input type="checkbox" value="1" name="RA">  Randomize Answers</div>  
			
			
			');	
				
				
				
				
				
			}
			else {
				
				
								
				echo('	
				 <input type="radio" value="True" name="Answer" > True<br>

  					<input type="radio" value="False" name="Answer"> False
					');
			}
			
	
			echo ('
			
		
				
			
			   <input type="hidden" name="SetID" value="'.$_GET["SetID"].'"/>               
                 <input type="hidden" name="QType" value="'.$_GET["QType"].'"/>
                 <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>

			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;">
			<a class="btn btn-danger pull-right" href="Qlist.php?SetID='.$row["SetID"].'"><span class="fa fa-trash-o"></span></a>
            <input type="submit" class="btn btn-primary pull-right" value="submit">
			</div>
							
                    
                </div>
           

        ');
		
	
		}
			//---------------------------
		
		echo ('</form>');
		
		
    }
    echo('</div>');
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
