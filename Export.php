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
$SetID = $_SESSION["SetID"];
$set = $KC_DAO->getKC($SetID);

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=KC_".$set["KCName"].".xls");
header("Pragma: no-cache");
header("Expires: 0");

   

    $StudentList = $KC_DAO->getStudentList($CONTEXT->id);
   
		
    $Total = count($StudentList);

   
    echo('
       
        <div>       
        
        
    ');

   
        $QNum = 1;
		echo ('<table border="1" width="800" cellspacing="10">
		<tr><td colspan=2><h2>'.$set["KCName"].' KC Usage</h2></td></tr>		
		<tr><td>Student Name</td><td>Attempt: Score (Date)</td></tr>
		
		');
		
	
        foreach ( $StudentList as $row ) {

		echo('                      
                
			
			<tr><td>'.$row["LastName"].', '.$row["FirstName"].'</td>
			');
            
			
			
$studentData = $KC_DAO->getUserData($SetID, $row["UserID"]);


$tAttempts = $studentData["Attempt"];	
			
echo ('
		<td>');
	
			
$Arr_Score = array();
$Arr_date = array();
$Arr_Attempt = array();
			
$Date1 ="";	
for ($i = 1; $i <=  $tAttempts ; $i++) {
  
		
	$Score=0;		
	$Questions = $KC_DAO->getQuestions($_SESSION["SetID"]);
	foreach ( $Questions as $row2 ) {

		$QID = $row2["QID"];
		$reviewData = $KC_DAO->Review($QID, $row["UserID"], $i);
		$dateTime = new DateTime($studentData["Modified"]);
$Date1 =  $dateTime->format("m-d-y")." &nbsp;".$dateTime->format("g:i A");
		if ($row2["Answer"]== $reviewData["Answer"]){
		 $Score = $Score + $row2["Point"];				
			
		}
		
	}
	//echo $Date1;
	
	
	
	array_push($Arr_Attempt, $i);
	array_push($Arr_Score,$Score);	
	array_push($Arr_date,$Date1);
}


			
	for ($i = 0; $i <  count($Arr_Attempt) ; $i++) {
		
		echo $Arr_Attempt[$i].": ".$Arr_Score[$i]." (".$Arr_date[$i].")<br>";
		
	}
			
			
			
	
		//echo max($Arr_Score); 	
echo ('</div>
										
                    
</td>
           

        ');
           
           
        }
	echo('</tr>');

    echo('</table>');


$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
