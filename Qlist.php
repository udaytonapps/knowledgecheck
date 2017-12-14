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
	$_SESSION["SetID"] =  $_GET["SetID"];
    $questions = $KC_DAO->getQuestions($SetID);
	
	$tPoints=0;
	foreach ( $questions as $row ) {
		$tPoints = $tPoints + $row["Point"];
	}
	
	

    $set = $KC_DAO->getKC($SetID);		
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
		
		
		if(isset($_GET["QType"])){
	echo('<form method="post" action="actions/AddQ_Submit.php">
	<div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" style="width:30px;>');
			
			echo('</div>	
			<div class="col-sm-1 noPadding text-center" style="width:60px;">');
			
			if($_GET["QType"] =="Multiple"){	$Msg="Multiple Choice";}
			else{$Msg="True/False";}
			echo '<h3>1</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div class="qHead" style="margin-top:0px;">  <span style=" margin-left:40px; ">Point(s) - '.$Msg.'</span>
			<input class="form-control" id="ex1" type="text" style="width:60px; name="Point" style="width:35px; height:25px; text-align:center; margin-top:-20px;padding:0;" autofocus>
			</div>');
			
        	
		  echo '<textarea class="form-control" name="Question" id="Question" rows="2" autofocus required></textarea><br>

	      
		  
		  <div id="flip2">
            <p class="btn btn-default">Add Feedback</p>
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
				
				
								
				echo('	<div style="margin-top:30px;">	
				 <input type="radio" value="True" name="Answer" > True<br>

  					<input type="radio" value="False" name="Answer"> False</div>
					');
			}
			
	
			echo ('
			
		
				
			
			   <input type="hidden" name="SetID" value="'.$_GET["SetID"].'"/>               
                 <input type="hidden" name="QType" value="'.$_GET["QType"].'"/>
                 <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>

			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;">
			<a class="btn btn-danger pull-right" href="Qlist.php?SetID='.$SetID.'"><span class="fa fa-ban"></span></a>
            <input type="submit" class="btn btn-primary pull-right" value="Save">
			</div>
							
                    
                </div>
				</form>
           

        ');
		
	
		}else{
			 echo('<p><em>There are currently no questions in this knowledge check.</em></p>');
		}
		
		
       
    } else {
     //   
        $QNum = 1;
		echo ('<div class="panel panel-default " style="border:0px; ">');
        foreach ( $questions as $row ) {

		
		echo('                      
                   
          <div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" style="width:30px;">');
			
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
			<div class="col-sm-1 noPadding text-center" style="width:60px;">');
			$colorA=""; $colorB=""; $colorC=""; $colorD="";
			$iconA=""; $iconB=""; $iconC=""; $iconD="";
			
			
				
			if($row["Point"] == 1){$Msg2="Point";}
			else{$Msg2 = "Points";}
			
			if($row["QType"] =="Multiple"){	$Msg=$row["Point"]." ".$Msg2." - Multiple Choice";}
			else{$Msg=$row["Point"]." ".$Msg2." - True/False";}
			echo '<h3>'.$QNum.'</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div class="qHead">'.$Msg.'</div>');
			
				
			
			
        	
		  echo $row["Question"].'</div>	<div class="col-sm-4 " >';
			
			
			if($row["QType"] =="Multiple"){				
				
				if ($row["Answer"] =="A"){$colorA="style='color:green; font-weight:bold;'"; $iconA="<span class='fa fa-check-circle-o fa-lg'></span>";}
				else if ($row["Answer"] =="B"){$colorB="style='color:green; font-weight:bold;'";$iconB="<span class='fa fa-check-circle-o fa-lg'></span>";}
				else if ($row["Answer"] =="C"){$colorC="style='color:green; font-weight:bold;'";$iconC="<span class='fa fa-check-circle-o fa-lg'></span>";}
				else if ($row["Answer"] =="D"){$colorD="style='color:green; font-weight:bold;'";$iconD="<span class='fa fa-check-circle-o fa-lg'></span>";}
				
				echo('<div '.$colorA.' >A. '.$row["A"].' '.$iconA.'</div>');
				echo('<div '.$colorB.' >B. '.$row["B"].' '.$iconB.'</div>');
				
				if($row["C"] !=""){echo('<div '.$colorC.' >C. '.$row["C"].' '.$iconC.'</div>');}
				if($row["D"] !=""){echo('<div '.$colorD.' >D. '.$row["D"].' '.$iconD.'</div>');}
			}
			else {
				
				if ($row["Answer"] =="True"){$colorA="style='color:green; font-weight:bold;'"; $iconA="<span class='fa fa-check-circle-o fa-lg'></span>";}
				else if ($row["Answer"] =="False"){$colorB="style='color:green; font-weight:bold;'"; $iconB="<span class='fa fa-check-circle-o fa-lg'></span>";}
				
			
								
				echo('	<div '.$colorA.' >True '.$iconA.'</div>
						<div '.$colorB.' >False '.$iconB.'</div>						
					');
			}
			
	
			echo ('
			
			
			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;"><a class="btn btn-danger pull-right" href="actions/DeleteQ.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'" onclick="return ConfirmDelete();"><span class="fa fa-trash-o"></span></a>
            <a class="btn btn-primary pull-right" href="Qlist_Edit.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'&QType='.$row["QType"].'"><span class="fa fa-pencil"></span></a></div>
							
                    
                </div>
           

        ');
           
            $QNum++;
        }
		
// add new --------------------------------------------------------------------------------------------------------------------------------------
echo (' 	<form method="post" action="actions/AddQ_Submit.php">');		
	
if(isset($_GET["QType"])){
	echo('

	<div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" style="width:30px;">');
			
			echo('</div>	
			<div class="col-sm-1 noPadding text-center" style="width:60px;">');
			
			
			if($_GET["QType"] =="Multiple"){	$Msg="Multiple Choice";}
			else{$Msg="True/False";}
			echo '<h3>'.$QNum.'</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div class="qHead" style="margin-top:0px;">  <span style=" margin-left:40px; ">Point(s) - '.$Msg.'</span>
			<input class="form-control" id="ex1" type="text" name="Point" style="width:35px; height:25px; text-align:center; margin-top:-20px;padding:0;" autofocus>
			</div>');
        	
		  echo '<textarea class="form-control" name="Question" id="Question" rows="2" autofocus required></textarea><br>

	      
		  
		  <div id="flip2">
            <p class="btn btn-default">Add Feedback</p>
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
			 <div style="padding:5px;"><input type="radio" value="A" name="Answer" > A. <input class="form-control answer" name="A" id="A" value=""></div>
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
				
				
								
				echo('<div style="margin-top:30px;">	
				 <input type="radio" value="True" name="Answer" > True<br>

  					<input type="radio" value="False" name="Answer"> False</div>
					');
			}
			
	
			echo ('
			
		
				
			
			   <input type="hidden" name="SetID" value="'.$_GET["SetID"].'"/>               
                 <input type="hidden" name="QType" value="'.$_GET["QType"].'"/>
                 <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>

			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;">
			<a class="btn btn-danger pull-right" href="Qlist.php?SetID='.$row["SetID"].'"><span class="fa fa-ban"></span></a>
            <input type="submit" class="btn btn-primary pull-right" value="Save">
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
