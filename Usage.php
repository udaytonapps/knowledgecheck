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

   // $StudentList = $KC_DAO->getStudentList($CONTEXT->id);
    $set = $KC_DAO->getKC($SetID);
		
   // $Total = count($StudentList);
	 $hasRosters = LTIX::populateRoster(false);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li>' .$set["KCName"].'</li>
        </ul>
        
        <div>

       
        
        <h2> '.$set["KCName"].' Usage</h2>
		<a href="Export.php" target="_blank" style="float:right; margin-top:-20px;">Export Usage</a>
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no questions in this knowledge check.</em></p>');
    } else {
      
        $QNum = 1;
		echo ('<br><div class="panel " >');
		
		
		 if ($hasRosters) {
			 
			 echo('<div class="row"><div class="col-sm-4"><h4>Student Name</h4></div><div class="col-md-6"><h4>Progress</h4></div></div>');

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('KC_Utils', 'compareStudentsLastName'));
	 
		foreach($rosterData as $student) {
            // Only want students				 
				 
				if ($student["role"] == 'Learner') {
					echo('<div class="row">
						<div class="col-sm-4">'.$student["person_name_family"].', '.$student["person_name_given"].'</div>');

				}
				 
				 
				 
				 
        }
		
		 

		
		
		
		echo('          
          <div>
			
			
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
		
      foreach($rosterData as $row) {

		if ($student["role"] == 'Learner') {
	
			
		
		echo('                      
                   
          <div class="panel-body" style="border:1px lightgray solid; ">
			
			
			<div class="col-sm-2 noPadding" >'.$row["person_name_family"].', '.$row["person_name_given"].'</div>
			<div class="col-sm-2 noPadding" >');
            
			
			
		$studentData = $KC_DAO->getUserData($SetID, $row["UserID"]);


$tAttempts = $studentData["Attempt"];	
		
echo $tAttempts;
			
echo ('</div>
		<div class="col-sm-4 " >');
	
if($tAttempts){			
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

			array_push($Arr_Score,$Score);	
		}

		echo max($Arr_Score); 
}
echo ('</div>
										
                    
</div>
           

        ');
           
            $QNum++;
        }
	  }
    }
    echo('</div>');
 }
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
