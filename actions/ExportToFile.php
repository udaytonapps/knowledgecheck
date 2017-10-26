<?php
require_once "../../config.php";
require_once "../util/PHPExcel.php";
require_once "../dao/KC_DAO.php";
require_once "../util/KC_Utils.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

if ( $USER->instructor ) {

    $SetID = $_SESSION["SetID"];
    $set = $KC_DAO->getKC($SetID);
    $questions = $KC_DAO->getQuestions($SetID);


    $Total = count($questions);

    $exportFile = new PHPExcel();

    
    $exportFile->setActiveSheetIndex(0)->setCellValue('A1', 'Student Name');
	$exportFile->setActiveSheetIndex(0)->setCellValue('B1', 'Attempts');
	$exportFile->setActiveSheetIndex(0)->setCellValue('C1', 'Best Score');
	$exportFile->setActiveSheetIndex(0)->setCellValue('D1', 'Score (Date)');

	

    $hasRosters = LTIX::populateRoster(false);

 if ($hasRosters) {

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('KC_Utils', 'compareStudentsLastName'));

        $columnIterator = $exportFile->getActiveSheet()->getColumnIterator();
        $columnIterator->next();


      $rowCounter = 2;
   foreach($rosterData as $student) {

                // Only want students
       if ($student["role"] == 'Learner') {
		   $exportFile->getActiveSheet()->setCellValue('A'.$rowCounter, $student["person_name_family"].', '.$student["person_name_given"]);

			$UserID = $KC_DAO->findUserID($student["user_id"]);	
			$studentData = $KC_DAO->getUserData($SetID, $UserID);
			$tAttempts = $studentData["Attempt"];		
			$exportFile->getActiveSheet()->setCellValue('B'.$rowCounter, $tAttempts);
					
	
					
			$Arr_Score = array();
		    $Arr_date = array();
		    $Arr_Attempt = array();
			$Max = "";			
	
		for ($i = 1; $i <=  $tAttempts ; $i++) {


			$Score=0;		
			$Questions = $KC_DAO->getQuestions($SetID);
			foreach ( $Questions as $row2 ) {

				$QID = $row2["QID"];
				$reviewData = $KC_DAO->Review($QID,  $UserID, $i);
				$dateTime = new DateTime($studentData["Modified"]);
				$Date1 =  $dateTime->format("m-d-y")." &nbsp;".$dateTime->format("g:i A");
				if ($row2["Answer"]== $reviewData["Answer"]){
				 $Score = $Score + $row2["Point"];				

				}

			}

			array_push($Arr_Attempt, $i);
			array_push($Arr_Score,$Score);	
			array_push($Arr_date,$Date1);	
		}

			if($tAttempts) {$Max =  max($Arr_Score);}
  			$exportFile->getActiveSheet()->setCellValue('C'.$rowCounter, $Max);
		   
		   $Detail = "";
		   
		   for ($i = 0; $i <  count($Arr_Attempt) ; $i++) {
		
			   $Detail = $Arr_Score[$i]." (".$Arr_date[$i].")";
			   $exportFile->getActiveSheet()->setCellValue('D'.$rowCounter, $Detail);	   
			   $rowCounter++;
		
			}
		   
		   
		   //$exportFile->getActiveSheet()->setCellValue('D'.$rowCounter, $Detail);	   
			//$rowCounter++;
      }
    }
            $columnIterator->next();

        $exportFile->getActiveSheet()->setTitle('Knowledge Check');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$set["KCName"].'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($exportFile, 'Excel5');
        $objWriter->save('php://output');
    }
}

exit;