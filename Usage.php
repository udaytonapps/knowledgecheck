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
	$_SESSION["SetID"] = $SetID;

    $StudentList = $KC_DAO->getStudentList($CONTEXT->id);
    $set = $KC_DAO->getKC($SetID);
		
    $Total = count($StudentList);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All knowledge check</a></li>
            <li>' .$set["KCName"].'</li>
        </ul>
        
        <div>

       
        
        <h2> '.$set["KCName"].' KC Usage</h2>
		<a href="Export.php" target="_blank" style="float:right; margin-top:-20px;">Export Usage</a>
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no questions in this knowledge check.</em></p>');
    } else {
      
        $QNum = 1;
		echo ('<br><div class="panel " >');
		
		
		
		
		
		
		echo('          
          <div class="panel-body" style="padding-top:2px;border:1px lightblue solid; margin-bottom:-20px; height:25px;background-color:lightblue; font-weight:bold;">
			
			
			<div class="col-sm-2 noPadding" >Student Name</div>
			<div class="col-sm-2 noPadding" >
             Attempts               
        </div>
									
            <div class="col-sm-2 " >
			Best Score
			
			</div>
										
                    
                </div></div>
           

        ');
		
		
		
		echo ('<div class="panel " >');
		
        foreach ( $StudentList as $row ) {

		
	
			
		
		echo('                      
                   
          <div class="panel-body" style="border:1px lightgray solid; ">
			
			
			<div class="col-sm-2 noPadding" >'.$row["LastName"].', '.$row["FirstName"].'</div>
			<div class="col-sm-2 noPadding" >');
            
			
			
		$studentData = $KC_DAO->getUserData($SetID, $row["UserID"]);
//$dateTime = new DateTime($studentData["Modified"]);
//$Last = $dateTime->format("m-d-y")." &nbsp;".$dateTime->format("g:i A");

$tAttempts = $studentData["Attempt"];	
		
echo $tAttempts;
			
echo ('</div>
		<div class="col-sm-4 " >');
	
			
$Arr_Score = array();	
			
	
for ($i = 1; $i <=  $tAttempts ; $i++) {
  
		
	$Score=0;		
	$Questions = $KC_DAO->getQuestions($_GET["SetID"]);
	foreach ( $Questions as $row2 ) {

		$QID = $row2["QID"];
		$reviewData = $KC_DAO->Review($QID, $row["UserID"], $i);
		if ($row2["Answer"]== $reviewData["Answer"]){
		 $Score = $Score + $row2["Point"];				
			
		}
		
	}
	
	array_push($Arr_Score,$Score)."<hr>";	
}

	//print_r($Arr_Score);	
		echo max($Arr_Score); 	
echo ('</div>
										
                    
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
