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
$students = array(); 

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
                <table class="table">
             
                    <tr style="text-decoration: underline; font-weight: bold;">
                       
                        
                        
                         
                        <td >
                        <a href="Usage.php?SetID='.$_GET["SetID"].'&Sort=name">
							Student Name                  
                        </a>
						                     
                       
                        </td>
                        <td align="center">
							<a href="Usage.php?SetID='.$_GET["SetID"].'&Sort=attempt">Number of Attempt(s)</a>
                         </th>
							
						<td align="center">
						 <a  href="Usage.php?SetID='.$_GET["SetID"].'&Sort=hScore">Best Score</a>
                        
						
					
							</td>
                       
                        
                    </tr>
               
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

	
			
$name = $row["person_name_family"].', '.$row["person_name_given"];		
			
array_push($students, array("name"=>$name,"attempt"=>$tAttempts,"hScore"=>$Max));
		
$sortArray = array(); 

foreach($students as $person){ 
    foreach($person as $key=>$value){ 
        if(!isset($sortArray[$key])){ 
            $sortArray[$key] = array(); 
        } 
        $sortArray[$key][] = $value; 
    } 
} 

	
if(isset($_GET["Sort"])){
	
	
	if($_GET["Sort"] == "name"){
	
		if($_SESSION["N1"]==1){array_multisort($sortArray[$_GET["Sort"]],SORT_ASC,$students);$_SESSION["N1"]++;}
		else { array_multisort($sortArray[$_GET["Sort"]],SORT_DESC,$students); $_SESSION["N1"]--;}
	}
	else if($_GET["Sort"] == "attempt"){
	
		if($_SESSION["N2"]==1){array_multisort($sortArray[$_GET["Sort"]],SORT_ASC,$students);$_SESSION["N2"]++;}
		else { array_multisort($sortArray[$_GET["Sort"]],SORT_DESC,$students); $_SESSION["N2"]--;}
	}
	else if($_GET["Sort"] == "hScore"){
	
		if($_SESSION["N3"]==1){array_multisort($sortArray[$_GET["Sort"]],SORT_ASC,$students);$_SESSION["N3"]++;}
		else { array_multisort($sortArray[$_GET["Sort"]],SORT_DESC,$students); $_SESSION["N3"]--;}
	}
		
}
	

	$total = sizeof($students);
for($i=0; $i<$total; $i++){

		
			
		echo('                      
                  
				 <tr>
                        
                        <td>'.$students[$i]["name"].'</td>
                        <td align="center">'.$students[$i]["attempt"].'</td>
                        <td align="center">'.$students[$i]["hScore"].'</td>
                    </tr>');
	
}
	
					
          
          
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
