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

$visibleSets = $KC_DAO->getAll_VisibleKC($CONTEXT->id);


if (count($visibleSets) == 0) {
    echo('<p><em>There are currently no available flashknowledge check for this course.</em></p>');
} else {

	
	
	
    echo('<div class="row">');

    foreach ( $visibleSets as $set ) {
        $questions = $KC_DAO->getQuestions($set["SetID"]);
		$exist = $KC_DAO->userDataExists($set["SetID"], $USER->id);
            $questionsPile = '';
       
        echo('
            <div class="col-sm-3">
                <div class="panel panel-default'.$questionsPile.'">
                    <div class="panel-heading">
                        <span class="label label-success pull-right student-card-count">'.count($questions).' Questions</span>
                        <h3>'.$set["KCName"].'</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6 text-center" style="border-right:1px lightgray solid;">
                                <a href="Take.php?SetID='.$set["SetID"].'"');if(count($questions) == 0){echo(' class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-check-square-o"></span>
                                    <br /><small>Take</small>
                                </a>
                            </div>
							
							 <div class="col-xs-6 text-center" >
                                <a href="Review.php?SetID='.$set["SetID"].'"');if($exist != 1){echo(' class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-flag"></span>
                                    <br /><small>Review</small>
                                </a>
                            </div>
							
							
                        </div>
                    </div>
                </div>
            </div>
        ');
    }

    echo('</div>');
}
