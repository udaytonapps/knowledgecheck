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

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $SetID = $_GET["SetID"];

    $questions = $KC_DAO->getQuestions($SetID);

    $set = $KC_DAO->getKC($SetID);
	
	$_SESSION["SetID"] = $set["SetID"];
	
    $Total = count($questions);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li>' .$set["KCName"].'</li>
        </ul>
        
        <div>

        <p>
            <a class="btn btn-success" href="AddQType.php?SetID='.$SetID.'"><span class="fa fa-plus"></span> Add New Question</a>        
        </p>
        
        <h2>Questions in "'.$set["KCName"].'" <span class="badge">'.$Total.' Questions</span></h2>
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
			<div class="col-sm-1 noPadding" style="width:30px;">'.$QNum.'</div>
			<div class="col-sm-5 noPadding" >
                            
        ');
			$colorA=""; $colorB=""; $colorC=""; $colorD=""; 
        	
		   //echo($row["Question"].' ('.$row["Point"].')</div>
		    echo($row["Question"].'</div>
									
            <div class="col-sm-4 " >');
			
			
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
    }
    echo('</div>');
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
