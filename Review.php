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

$SetID = $_GET["SetID"];
$_SESSION["SetID"] = $SetID;
$Questions = $KC_DAO->getQuestions($SetID);
$Total = count($Questions);
$set = $KC_DAO->getKC($SetID);

if ($shortCut == 0) {
    if ( $USER->instructor ) {
        $Page = $_SESSION["Page"];
        echo('
            <ul class="breadcrumb">');
                if($Page === "index"){
                    echo ('<li><a href="index.php">All Knowledge Checks</a></li>');
                }else {
                    echo ('<li><a href="ManageKCs.php">All Knowledge Checks</a></li>');
                }
                echo ('<li>' .$set["KCName"].'</li>
            </ul>
        ');
    } else {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Knowledge Checks</a></li>
                <li>' .$set["KCName"].'</li>
            </ul>
        ');
    }
}

$studentData = $KC_DAO->getUserData($SetID, $USER->id);
$dateTime = new DateTime($studentData["Modified"]);
$Last = $dateTime->format("m-d-y")." ".$dateTime->format("g:i A");
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
	<div class="col noPadding">                  
	  	<h3 class="noPadding"><span class="fa fa-flag"></span> <?php echo $set["KCName"];?> Review</h3>
		<h4 style="margin-left: 29px;font-size: 16px;">Last attempt: <?php echo $Last;?><br>Score: <b><?php echo $Score1;?></b></h4>
		
		
		
		<div class="Review">                  
	  	Total number of attempts: <?php echo $tAttempts;?><br>
Highest Score: <?php echo $hScore;?>
	</div>
		
	</div>
	
	



</div>

 <div class="panel-body" >
<?php

	
        foreach ( $Questions as $row ) {

			
			
		
		echo('                      
                   
         
		
			<div class="col noPadding">
                            
        ');
			  if ($row["Point"] == 1){$PTs = " point";}else{$PTs = " points";}
			
			
			$QID = $row["QID"];
			
			$reviewData = $KC_DAO->Review($QID, $USER->id, $tAttempts);	
			
			if ($row["Answer"]== $reviewData["Answer"]){				
				$Feedback = $row["FR"];
				$yPoint = $row["Point"];
					
			}else{$Feedback = $row["FW"];$yPoint = 0;}
			
			echo ('<span class="point" >'.$yPoint.' / '.$row["Point"].' '.$PTs.'</span>');
		    echo($row["QNum"].'. '.$row["Question"].'<br><div style="margin-left:15px;">');
			
			
			if($row["QType"] =="Multiple"){	
				
				$colorA=""; $colorB=""; $colorC=""; $colorD=""; 
				$userA="";
				$userB="";
				$userC="";
				$userD="";

				$Yes = " <span class='fa fa-check-circle-o fa-lg'></span>";
				$No = " <span class='fa fa-times-circle fa-lg' style='color:red;'></span>";
				
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


								
				echo('	<div '.$colorA.' > '.$row["A"].$userA.'</div>
						<div '.$colorB.' > '.$row["B"].$userB.'</div>
						<div '.$colorC.' > '.$row["C"].$userC.'</div>
						<div '.$colorD.' > '.$row["D"].$userD.'</div>
					');
			}
			else {
				
				$colorA=""; $colorB=""; $icon1 ="";	$icon2 ="";
			
			//$icon1  = " <span class='fa fa-check-circle-o fa-lg'></span>";
			//$icon = " <span class='fa fa-times-circle fa-lg' style='color:red;'></span>";
			
				
				if ($row["Answer"] =="True"){
					
					$colorA="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "True") {$icon1 = " <span class='fa fa-check-circle-o fa-lg'></span>";}else{$icon2 = " <span class='fa fa-times-circle fa-lg' style='color:red;'></span>";}
				}
				else if ($row["Answer"] =="False"){
					
					$colorB="style='color:green; font-weight:bold;'";
						if($reviewData["Answer"] == "False") {$icon2 = " <span class='fa fa-check-circle-o fa-lg'></span>";}else{$icon1 = " <span class='fa fa-times-circle fa-lg' style='color:red;'></span>";}
				}
				
				
				
				echo('	<div '.$colorA.' >True'.$icon1.'</div>
						<div '.$colorB.' >False'.$icon2.'</div>						
					');
			}
			
			
		   
			
		   
		   echo ('</div>');
						

			if($Feedback != ""){		
			
			 	echo ('<div class="feedBack"  >	
				
					<b>Feedback: </b>'.$Feedback.'</div>');
			}
           
     echo "<br>";
        }
	   echo "</div>";  
  
	 
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();