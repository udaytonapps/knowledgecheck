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

    $rows = $KC_DAO->getQuestions($SetID);
	
	$tPoints=0;
	foreach ( $rows as $row ) {
		$tPoints = $tPoints + $row["Point"];
	}
	
	

    $set = $KC_DAO->getKC($SetID);
	
	$_SESSION["SetID"] = $set["SetID"];
	
    $Total = count($rows);
	
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


     //   
        $QNum = 1;
		echo ('<div class="panel panel-default " style="border:0px; ">');
        foreach ( $rows as $row ) {

			
			
					
// edit --------------------------------------------------------------------------------------------------------------------------------------
	
	
if($_GET["QID"] == $row["QID"]){
	echo (' 	<form method="post" action="actions/EditQ_Submit.php">');	
	echo('

	<div class="panel-body" style="border:1px lightgray solid; margin-bottom:3px;">
			
			<div class="col-sm-1 noPadding" >');
			
			echo('</div>	
			<div class="col-sm-1 noPadding" style="width:30px;">');
			
			
	
			if($_GET["QType"] =="Multiple"){	$Msg="Multiple Choice";}
			else{$Msg="True/False";}
			echo '<h3>'.$QNum.'</h3></div><div class="col-sm-5 noPadding" >';
			echo ('<div class="qHead" style="margin-top:0px;">  <span style=" margin-left:40px; ">Point(s) - '.$Msg.'</span>
			<input class="form-control" id="ex1" type="text" value="'.$row["Point"].'" name="Point" style="width:35px; height:25px; text-align:center; margin-top:-20px;padding:0;" autofocus>
			
			
			
			
			</div>');
        	
		  echo '<textarea class="form-control" name="Question" id="Question" rows="2" autofocus required>'.$row["Question"].'</textarea><br>

	      
		  
		  <div id="flip2">
            <p class="btn btn-default">Edit Feedback</p>
        </div>
		<div id="panel2">
                  	  <br>

                    <label class="control-label" for="FR">Correct Feedback</label>
                    <textarea class="form-control" name="FR" id="FR" rows="2" autofocus >'.$row["FR"].'</textarea><br>

              
                
                    <label class="control-label" for="FR">Incorrect Feedback</label>
                    <textarea class="form-control" name="FW" id="FW" rows="2" autofocus >'.$row["FW"].'</textarea>
               
				

            </div>
</div>



		  <div class="col-sm-4 " >';
			
			
			if($_GET["QType"] =="Multiple"){				
				
			?>
			
			
                   
                   
			 <div style="padding:5px;">
			 	<input type="radio" value="A" name="Answer" <?php if($row["Answer"] == "A"){?>checked <?php } ?>> 
                   A. <input class="form-control answer" name="A" id="A" value="<?php echo($row["A"]); ?>">			 	
			 </div>
<div style="padding:5px;">
                    <input type="radio" value="B" name="Answer" <?php if($row["Answer"] == "B"){?>checked <?php } ?>> 
                   B. <input class="form-control answer" name="B" id="B" value="<?php echo($row["B"]); ?>">
                   
                   </div>
<div style="padding:5px;">
                  <input type="radio" value="C" name="Answer" <?php if($row["Answer"] == "C"){?>checked <?php } ?>>
                   C. <input class="form-control answer" name="C" id="C" value="<?php echo($row["C"]); ?>">
                  </div>
<div style="padding:5px;">
                   <input type="radio" value="D" name="Answer" <?php if($row["Answer"] == "D"){?>checked <?php } ?>> 
                   D. <input class="form-control answer" name="D" id="D" value="<?php echo($row["D"]); ?>"></div>

                  
                  <div class="ML"><input type="checkbox" value="1" name="RA" <?php if($row["RA"]){?>checked <?php } ?>>  Randomize Answers</div>
			
			<?php	
				
				
				
			}
			else {
				
			?>
						
						
                    <input type="radio" value="True" name="Answer" <?php if($row["Answer"] == "True"){?>checked <?php } ?>> 
                   True.<br>
  					<input type="radio" value="False" name="Answer" <?php if($row["Answer"] == "False"){?>checked <?php } ?>> 
                   False.
						
						
			<?php	
								
				
			}
			
	
			echo ('
			
		
				
			
			   <input type="hidden" name="QID" value="'.$_GET["QID"].'"/>               
                 <input type="hidden" name="QType" value="'.$_GET["QType"].'"/>
                 <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>

			
			
			</div>			
			<div class="col-sm-1 noPadding" style="float:right; width:120px;">
			<a class="btn btn-danger pull-right" href="Qlist.php?SetID='.$row["SetID"].'"><span class="fa fa-ban"></span></a>
            <input type="submit" class="btn btn-primary pull-right" value="Save">
			</div>
							
                    
                </div>
           

        ');
		
		echo ('</form>');
}else{
	
			//---------------------------
		
		
			
		
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
			
			if($row["QType"] =="Multiple"){	$Msg=$row["Point"]." ".$Msg2." - Multiple Choice";}
			else{$Msg=$row["Point"]." ".$Msg2." - True/False";}
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
			<div class="col-sm-1 noPadding" style="float:right; width:120px;"><a class="btn btn-danger pull-right" href="actions/DeleteQ.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'" onclick="return ConfirmDelete();"><span class="fa fa-trash-o"></span></a>
            <a class="btn btn-primary pull-right" href="Qlist_Edit.php?QID='.$row["QID"].'&SetID='.$row["SetID"].'&QType='.$row["QType"].'"><span class="fa fa-pencil"></span></a></div>
							
                    
                </div>
           

        ');
           
            $QNum++;
        }
		}
		
    }
    echo('</div>');


$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
