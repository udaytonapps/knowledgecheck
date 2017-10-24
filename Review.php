<?php
require_once "../config.php";
require_once "dao/KC_DAO.php";
require_once "util/KC_Utils.php";

use \Tsugi\Core\LTIX;
use \KC\DAO\KC_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$KC_DAO = new KC_DAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];

$Total=0;

if(isset($_GET["Shortcut"])) {
    $shortCut = $_GET["Shortcut"];
} else {
    $shortCut = 0;
}

$_SESSION["Shortcut"] = $shortCut;

if ( $USER->instructor ) {
    include("menu.php");
} else {
    if ($shortCut == 0) {
        echo('
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">Knowledge Check</a>
                </div>
            </div>
        </nav>
        ');
    }
}

if(isset($_GET["ReviewMode"])){
    $isReviewMode = $_GET["ReviewMode"];
} else {
    $isReviewMode = 0;
}

$SetID = $_GET["SetID"];
$_SESSION["SetID"] = $SetID;
$Questions = $KC_DAO->getQuestions($SetID);
$Total = count($Questions);
$set = $KC_DAO->getKC($SetID);

if ($shortCut == 0) {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All knowledge check</a></li>
                <li>' .$set["KCName"].'</li>
            </ul>
        ');
    }

$studentData = $KC_DAO->getUserData($SetID, $USER->id);
$dateTime = new DateTime($studentData["Modified"]);
$Last = $dateTime->format("m-d-y")." &nbsp;".$dateTime->format("g:i A");
$hScore = "";
$tAttempts = $studentData["Attempt"];

if($tAttempts){			
		$Arr_Score = array();
		for ($i = 1; $i <=  $tAttempts ; $i++) {
			$Score2=0;		
			$Questions = $KC_DAO->getQuestions($_GET["SetID"]);			
			foreach ( $Questions as $row2 ) {
				$QID = $row2["QID"];
				$reviewData2 = $KC_DAO->Review($QID, $USER->id, $i);
				if ($row2["Answer"]== $reviewData2["Answer"]){
				 $Score2 = $Score2 + $row2["Point"];
				}
			}
			array_push($Arr_Score,$Score2);			
		}
	$hScore = max($Arr_Score); 	
}

$Score1=0;
$Questions2 = $KC_DAO->getQuestions($SetID);
foreach ( $Questions2 as $row2 ) {
	
	$QID = $row2["QID"];
	$reviewData = $KC_DAO->Review($QID, $USER->id, $tAttempts);
	if ($row2["Answer"]== $reviewData["Answer"]){
			$Score1 = $Score1 + $row2["Point"];			
	}
}

?>
 
<div class="panel-body" >
	<div class="col-sm-6 noPadding">                  
	  	<h3 class="noPadding"><?php echo $set["KCName"];?> KC Review</h3>
		<h4>Last attempt: <?php echo $Last;?><br>Score: <b><?php echo $Score1;?></b></h4>
	</div>
	
	<div class="col-sm-3 " style="background-color:#12507C;color:white; font-size: 16px; padding:10px; ">                  
	  	<div>Total number of attempts: <?php echo $tAttempts;?><br>
Highest Score: <?php echo $hScore;?></div>
	</div>



</div>
<?php

	
        foreach ( $Questions as $row ) {

			
			
			$colorA=""; $colorB=""; $colorC=""; $colorD=""; 
			$userA="";
			$userB="";
			$userC="";
			$userD="";
			
			$Yes = " <span class='fa fa-check-circle-o fa-lg'></span>";
			$No = " <span class='fa fa-times-circle fa-lg' style='color:red;'></span>";
		
		echo('                      
                   
          <div class="panel-body" >
		
			<div class="col-sm-6 noPadding">
                            
        ');
			
		   echo($row["QNum"].'. '.$row["Question"].'<br><div style="margin-left:15px;">');
			
			$QID = $row["QID"];
			
			$reviewData = $KC_DAO->Review($QID, $USER->id, $tAttempts);	
			
			if ($row["Answer"]== $reviewData["Answer"]){				
				$Feedback = $row["FR"];				
					
			}else{$Feedback = $row["FW"];}
			
			
			if($row["QType"] =="Multiple"){				
				
				if ($row["Answer"] =="A"){$colorA="style='color:green; font-weight:bold;'";										 					
					if($reviewData["Answer"] == "A") {$userA=$Yes;}
					else if($reviewData["Answer"] == "B") {$userB=$No;}
					else if($reviewData["Answer"] == "C") {$userC=$No;}
					else if($reviewData["Answer"] == "D") {$userD=$No;}
				}
				else if ($row["Answer"] =="B"){$colorB="style='color:green; font-weight:bold;'";
						
						if($reviewData["Answer"] == "B") {$userB=$Yes;}
						else if($reviewData["Answer"] == "A") {$userA=$No;}
						else if($reviewData["Answer"] == "C") {$userC=$No;}
						else if($reviewData["Answer"] == "D") {$userD=$No;}	
				}
				else if ($row["Answer"] =="C"){$colorC="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "C") {$userC=$Yes;}
						else if($reviewData["Answer"] == "A") {$userA=$No;}
						else if($reviewData["Answer"] == "B") {$userB=$No;}
						else if($reviewData["Answer"] == "D") {$userD=$No;}	
											   
				}
				else if ($row["Answer"] =="D"){$colorD="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "D") {$userD=$Yes;}
						else if($reviewData["Answer"] == "A") {$userA=$No;}
						else if($reviewData["Answer"] == "B") {$userB=$No;}
						else if($reviewData["Answer"] == "C") {$userC=$No;}						   
				}


								
				echo('	<div '.$colorA.' >A. '.$row["A"].$userA.'</div>
						<div '.$colorB.' >B. '.$row["B"].$userB.'</div>
						<div '.$colorC.' >C. '.$row["C"].$userC.'</div>
						<div '.$colorD.' >D. '.$row["D"].$userD.'</div>
					');
			}
			else {
				
				
			
				
				if ($row["Answer"] =="True"){$colorA="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "True") {$userA=$Yes;}else {$userB=$No;}
				}
				else if ($row["Answer"] =="False"){$colorB="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "False") {$userB=$Yes;}else  {$userB=$No;}
				}
				
				
				
				echo('	<div '.$colorA.' >True'.$userA.'</div>
						<div '.$colorB.' >False'.$userB.'</div>						
					');
			}
			
			
		   
			if ($row["Point"] == 1){$PTs = " point";}else{$PTs = " points";}
		   
		   echo ('</div></div>									
            	<div class="col-sm-1 noPadding" style="text-align:right;  " >'.$row["Point"].$PTs.'</div>
			
			
			            </div>
			 <div class="panel-body" style="background-color:#D9E6FC; margin-left:40px;width:70%;padding:10px;" >
				
					<b>Feedback: </b>'.$Feedback.'
			
		   </div>

        ');
           
            
        }

?><br>

<?php	 
	 
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();