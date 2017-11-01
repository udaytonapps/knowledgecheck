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


 if (isset($newSetID["SetID"])) {$visibleSets = $KC_DAO->getOneKC($newSetID["SetID"]); }
 else{$visibleSets = $KC_DAO->getAll_VisibleKC($CONTEXT->id);}




if (count($visibleSets) == 0) {
    echo('<p><em>There are currently no available flashknowledge check for this course.</em></p>');
} else {	
	
	
    echo('<div class="row">');

    foreach ( $visibleSets as $set ) {
        $questions = $KC_DAO->getQuestions($set["SetID"]);
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
            <div class="col-6 col-sm-3">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <span class="label label-success pull-right">'.count($questions).' Questions</span>
                        <h3>'.$set["KCName"].'</h3>
                    </div>
                    <div class="panel-body" >
                        <div class="row">
                            <div class="col-xs-6 text-center" style="border-right:1px lightgray solid;">
                                <a href="Take.php?SetID='.$set["SetID"].'"');if(count($questions) == 0){echo(' class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-check-square-o"></span>
                                    <br /><small>Take</small>
                                </a><br>

								
                            </div>
							
							 <div class="col-xs-6 text-center" >
                                <a href="Review.php?SetID='.$set["SetID"].'"');if($exist != 1){echo(' class="disabled" style="color:gray;"');}echo('>
                                    <span class="fa fa-2x fa-flag"></span>
                                    <br /><small>Review</small>
                                </a>
                            </div>
							
							<div class="row" >
							 <div class="col-xs-6 text-center" style="padding-top:10px;">');
							if($tAttempts){
								echo('Total # of attempts: '.$tAttempts.'<br>
								Highest Score: '.$hScore.'/'.$tPoints);
							}
							else{
								echo ('No attempts: '.$tAttempts.'<br>
								Highest Score: N/A');
							}
		
							
								
								echo ('</div></div>
							
							
                        </div>
                    </div>
                </div>
            </div>
        ');
    }

    echo('</div>');
}
