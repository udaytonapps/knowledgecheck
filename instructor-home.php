<?php

echo('<h2>Knowledge Check');

$linkId = $LINK->id;

$newSetID = $KC_DAO->getSetIDForLink($linkId);

$Hide=0;
if (isset($newSetID["SetID"])) {
    $newKC = $KC_DAO->getKC($newSetID["SetID"]);	
	$Hide=1;
    echo('<br /><small><span class="fa fa-link"></span> '.$newKC["KCName"].' is linked.</small>');
}

echo('</h2>');



$_SESSION["SetID"]=0;


if($Hide){$allKC = $KC_DAO->getOneKC($newSetID["SetID"]);}
else{$allKC = $KC_DAO->getAll_KC($CONTEXT->id);}


if (count($allKC) == 0) {
    echo('<p><em>You currently do not have any knowledge checks in this site. Create a new knowledge check or use the import button below to copy a card set from another site.</em></p>');
}

echo('<div class="row" id="kc-row">');

    foreach ( $allKC as $KC ) {
        if ($KC["Visible"]) {

            if($KC["Active"] == 0) {
                $flag = 1;
                $panelClass = 'default';
                $pubAction = 'Unpublished';
            } else {
                $flag = 0;
                $panelClass = 'success';
                $pubAction = 'Published';
            }

            $questions = $KC_DAO->getQuestions($KC["SetID"]);
		$totalPoints = 0;
        	foreach($questions as $question) {
	                $totalPoints = $totalPoints + $question["Point"];
	        }
	    $exist = $KC_DAO->userDataExists($KC["SetID"], $USER->id);
           
            echo('
                <div class="col-sm-6">
                <div class="row" style="border:1px solid #ccc;border-radius:4px;background-color:#eee;">
                        <div class="col-sm-3 text-center" style="padding:.5em;">
                                <span class="fa fa-check fa-4x text-'.$panelClass.'"></span>
                                <br />
                                <a class="btn btn-'.$panelClass.'" style="margin-top: 1em;" href="actions/Publish.php?SetID='.$KC["SetID"].'&Flag='.$flag.'">'.$pubAction.'</a>
                        </div>
                        <div class="col-sm-7" style="background-color:#fff;padding:1em;">
                            <h3 style="margin-top:0;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">
                                <a href="Qlist.php?SetID='.$KC["SetID"].'">
                                    <span class="fa fa-pencil-square-o"></span>
                                    '.$KC["KCName"].'
                                </a>
                            </h3>
                            <span>'.count($questions).' Questions / '.$totalPoints.' Total Points</span>
                            <div class="row" style="margin-top:1em;">
                                <div class="col-xs-6 text-center" style="border-right:1px solid #ccc;">
                                <h4>
                                    <a href="Usage.php?SetID='.$KC["SetID"].'" ');if(count($questions) == 0){echo('class="disabled"');}echo('>
                                    <span class="fa fa-bar-chart"></span>
                                    Usage
                                    </a>                                    
                                </h4>
                                </div>

                                <div class="col-xs-6 text-center">
                                <h4>
                                    <a href="Settings.php?SetID='.$KC["SetID"].'">
                                    <span class="fa fa-cog"></span>
                                    Settings
                                    </a>
                                </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center" style="padding:.5em;">
                                <div style="font-size: 1.2em;padding-top:.5em;">
                                    <a href="Take.php?SetID='.$KC["SetID"].'" ');if(count($questions) == 0){echo('class="disabled"');}echo('>
                                    <span class="fa fa-check-square-o"></span><br />
                                    <small>Preview</small>
                                    </a>
                                </div>

                                <div style="font-size:1.2em;margin-top:.5em;">
                                    <a href="Review.php?SetID='.$KC["SetID"].'" ');if($exist != 1){echo('class="disabled"');}echo('>
                                    <span class="fa fa-flag"></span><br />                                  
                                    <small>Feedback</small>
                                    </a>
                                </div>
                        </div>
                    </div>
		</div>
            ');
        }
    }

echo('</div>');


if (!isset($newSetID["SetID"])) {

/* Import from site */


$courses = $KC_DAO->getAllSites($USER->id, $CONTEXT->id);

echo('
    
    <h3><button class="btn btn-primary ');
            if(count($courses)==0) {
                echo('disabled');
            }
    echo('" data-toggle="collapse" data-target="#import-q-row">Import Knowledge Check</button></h3>
    
    <div id="import-q-row" class="row collapse">
');

foreach ( $courses as $course ) {

    $sets = $KC_DAO->getAll_KC($course["context_id"]);

    echo('<div id="kc-list" class="list-group col-md-4">');

    echo('<h4>'.$KC_DAO->getCourseNameForId($course["context_id"]).'</h4>');

    foreach ($sets as $set) {

        $questions2 = $KC_DAO->getQuestions($set["SetID"]);

        if (count($questions2) > 0) {
            $countLabel = 'success';
            $textLabel = 'success';
        } else {
            $countLabel = 'default';
            $textLabel = 'muted';
        }

        echo('
            <a href="actions/ImportKC.php?SetID='.$set["SetID"].'"  onclick="return confirmCopyKC();" class="list-group-item">
                <div class="list-group-item-heading">
                    <span class="label label-'.$countLabel.' pull-right">'.count($questions2).' Questions</span>
                    <span class="fa-stack small text-'.$textLabel.'">
                        <span class="fa fa-square fa-stack-2x" style="top:-6px;"></span>
                        <span class="fa fa-square-o fa-stack-2x" style="top:2px;left:-8px;"></span>
                        <span class="fa fa-inverse fa-upload fa-stack-1x" style="top:-6px;"></span>
                    </span>
                    <h5 class="kc-list-name text-'.$textLabel.'" style="background:none;">'.$set["KCName"].'</h5>                    
                </div>
            </a>
        ');
    }

    echo('</div>');
}

    echo('</div>');
}
