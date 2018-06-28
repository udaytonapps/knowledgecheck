<?php

echo('
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Knowledge Check</a>
            </div>
        </div>
    </nav>
');


 if (isset($newSetID["SetID"])) {$visibleSets = $KC_DAO->getOneKCStudent($newSetID["SetID"]); }
 else{$visibleSets = $KC_DAO->getAll_VisibleKC($CONTEXT->id);}




if (count($visibleSets) == 0) {
    echo('<p><em>There are currently no available flashknowledge check for this course.</em></p>');
} else {	
	
	
    echo('<div class="row">');

    foreach ( $visibleSets as $set ) {
        $questions = $KC_DAO->getQuestions($set["SetID"]);
	$totalPoints = 0;
	foreach($questions as $question) {
		$totalPoints = $totalPoints + $question["Point"];
	}
		$exist = $KC_DAO->userDataExists($set["SetID"], $USER->id); 
		
		
		
		
		
		$studentData = $KC_DAO->getUserData($set["SetID"], $USER->id);		
		$hScore = "";
		$tAttempts = $studentData["Attempt"];
		

		if($tAttempts){			
				$Arr_Score = array();
				for ($i = 1; $i <=  $tAttempts ; $i++) {
					$Score2=0;
					$tPoints=0;
					$Questions = $KC_DAO->getQuestions($set["SetID"]);			
					foreach ( $Questions as $row2 ) {
						$QID = $row2["QID"];
						$tPoints = $tPoints + $row2["Point"];
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
		$Questions2 = $KC_DAO->getQuestions($set["SetID"]);
		foreach ( $Questions2 as $row2 ) {

			$QID = $row2["QID"];
			$reviewData = $KC_DAO->Review($QID, $USER->id, $tAttempts);
			if ($row2["Answer"]== $reviewData["Answer"]){
					$Score1 = $Score1 + $row2["Point"];			
			}
		}
        echo('
            <div class="col-sm-6">
                <div class="row" style="background-color:#eee;border:1px solid #ccc;border-left:4px solid #3c763d;">
		    <div class="col-sm-8" style="background-color:#fff;padding-left:0;padding-right:0;">
                        <h3 style="padding-left:15px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;">'.$set["KCName"].'</h3>
                        <div class="row" style="border-top:1px solid #ccc;">
                            <div class="col-xs-5 " style="border-right:1px solid #ccc;padding-top:.5em;">
                                <p><strong>'.count($questions).'</strong> Questions<br /><strong>'.$totalPoints.'</strong> Total Points</p>
                            </div>
                                <div class="col-xs-7 " style="padding-top:.5em;"><p>');
                                                        if($tAttempts){
                                                                echo('Total Attempts: <strong>'.$tAttempts.'</strong><br />
                                                                Highest Score: <strong>'.$hScore.'/'.$tPoints.'</strong>');
                                                        }
                                                        else{
                                                                echo ('Not attempted.<br />
                                                                Highest Score: N/A');
                                                        }
                        echo ('
				</p></div>
                    </div>
		</div>
		    <div class="col-sm-4 text-center" style="margin-top:1.5em;">
                                <a  style="font-size:1.7em;margin-bottom:.5em;" href="Take.php?SetID='.$set["SetID"].'" class="btn btn-success');if(count($questions) == 0){echo(' disabled');}echo('">
                                    <span class="fa fa-check-square-o"></span>
                                   Start 
                                </a><br />
<a style="font-size: 1.2em;" href="Review.php?SetID='.$set["SetID"].'"');if($exist != 1){echo(' class="disabled" style="color:gray;"');}echo('>
                                  Feedback 
                                </a>

		    </div>
                </div>
            </div>
        ');
    }

    echo('</div>');
}
