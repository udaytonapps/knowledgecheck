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
$set = $KC_DAO->getKC($SetID);

$Arr_QID = array();

$Questions = $KC_DAO->getQuestions($SetID);
$Total = count($Questions);

foreach ( $Questions as $row ) {
    array_push($Arr_QID, $row["QID"]);
}


if ($set["Random"]){shuffle($Arr_QID);}

if ($shortCut == 0) {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Knowledge Checks</a></li>
                <li>' .$set["KCName"].'</li>
            </ul>
        ');
    }

    ?>
       
       
<div class="row ">           
  <h3><span class="fa fa-check-square-o"></span> <?php echo $set["KCName"];?></h3>
    <form  method="post" action="actions/Take_Submit.php">


<?php

$Temp = 1;
		
      
for($i=0; $i<$Total; $i++){
		
	$each = $KC_DAO->eachQuestion($Arr_QID[$i]);
	
	foreach ( $each as $row ) {
		
		$QNum = $i+1;
		$RA = $row["RA"];
		$mChoice = array ( 
			array("A",$row["A"]),array("B",$row["B"]),array("C",$row["C"]),array("D",$row["D"])
		);
		
		echo('                      
                   
          <div class="panel-body" >
		
			<div class="col noPadding">
                            
        ');
		   if ($row["Point"] == 1){$PTs = " point";}else{$PTs = " points";}
		echo ('<span class="point" style="float:right; padding-left:10px; padding-bottom:10px;">'.$row["Point"].' '.$PTs.'</span>');
		   echo($QNum.'. '.$row["Question"].'<br><div style="margin-left:15px;">');
			
			if($row["QType"] =="Multiple"){	
				
				if($RA){shuffle($mChoice);}		
				for($x=0; $x<4; $x++){
				if($mChoice[$x][1] !=""){
					echo '<div><input type="radio" value="'.$mChoice[$x][0].'" name="Answer'.$row["QNum"].'" > '.$mChoice[$x][1].'</div>';}
				}		
				
			}
			else {			
				echo('	<div > <input type="radio" value="True" name="Answer'.$row["QNum"].'" > True </div>
						<div > <input type="radio" value="False" name="Answer'.$row["QNum"].'" > False </div>						
					');
			}
		   
		
		   
		   echo ('</div></div>
			
            </div>
           

        ');
           
         $Temp++;  
        }
}
  
?><br>

 <input type="hidden" id="SetID" name="SetID" value="<?php echo $_GET["SetID"];?>"/>

                <input class="btn btn-primary" type="submit" value="Submit" />
                <a href="index.php" class="btn btn-danger">Cancel</a>
<?php	 
	 
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();