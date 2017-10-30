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
	$_SESSION["SetID"] = $SetID;

   // $StudentList = $KC_DAO->getStudentList($CONTEXT->id);
    $set = $KC_DAO->getKC($SetID);
		
 
	 $hasRosters = LTIX::populateRoster(false);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Knowledge Checks</a></li>
            <li>' .$set["KCName"].'</li>
        </ul>
        
        <div>

       
        
        <h3><span class="fa fa-bar-chart"></span>  '.$set["KCName"].' Usage</h3>
		<div class="row" style="max-width:600px;">
		<a href="actions/ExportToFile.php" target="_blank" style="float:right; margin-top:-20px;">Export Usage</a></div>
    ');
     
      
		echo ('<br><div class="panel " >');
		
		
		 if ($hasRosters) {
			 
		$rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('KC_Utils', 'compareStudentsLastName'));	
		
		echo('          
         <div class="row" style="max-width:600px;">
       <div class="panel panel-default filterable">                 
          
            
            <table class="table">
                <thead>
                    <tr class="filters">
                       
                        
                        <th><input type="text" class="form-control" placeholder="Student Name" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Attempt" disabled></th>
						<th><input type="text" class="form-control" placeholder="Best Score" disabled></th>
                        <th>
                        <button class="btn btn-default btn-xs btn-filter pull-right filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                        
                        </th>
                    </tr>
                </thead>
                <tbody>
           

        ');
		
		
      foreach($rosterData as $row) {
$Max="";
		if ($row["role"] == 'Learner') {
	
			$UserID = $KC_DAO->findUserID($row["user_id"]);	
			
		$studentData = $KC_DAO->getUserData($SetID, $UserID);
		$tAttempts = $studentData["Attempt"];
		if($tAttempts){	
						$Arr_Score = array();

						for ($i = 1; $i <=  $tAttempts ; $i++) {

							$Score=0;		
							$Questions = $KC_DAO->getQuestions($_GET["SetID"]);
							foreach ( $Questions as $row2 ) {

								$QID = $row2["QID"];

								$reviewData = $KC_DAO->Review($QID, $UserID, $i);
								if ($row2["Answer"]== $reviewData["Answer"]){
								 $Score = $Score + $row2["Point"];				

								}

							}

							array_push($Arr_Score,$Score);	
						}

						$Max = max($Arr_Score); 
}

		
		echo('                      
                  
				 <tr>
                        
                        <td>'.$row["person_name_family"].', '.$row["person_name_given"].'</td>
                        <td>'.$tAttempts.'</td>
                        <td>'.$Max.'</td><td></td>
                    </tr>');
					
          
          
        }
	  }
	echo('</tbody>
	</table>
        </div>
    </div>');
    }
   
echo ('</div>');
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
