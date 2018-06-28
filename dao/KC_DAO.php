<?php
namespace KC\DAO;

class KC_DAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }

    function getSetIDForLink($link_id) {
        $query = "SELECT * FROM {$this->p}kc_link WHERE link_id = '".$link_id."';";
        return $this->PDOX->rowDie($query);
    }

    function getKC($SetID) {
        $query = "SELECT * FROM {$this->p}kc_main WHERE SetID = '".$SetID."';";
        return $this->PDOX->rowDie($query);
    }

    function getAll_KC($context_id) {
        $query = "SELECT * FROM {$this->p}kc_main WHERE context_id='".$context_id."' ORDER BY KCName;";
        return $this->PDOX->allRowsDie($query);
    }

    function getAll_VisibleKC($context_id) {
        $query = "SELECT * FROM {$this->p}kc_main where context_id = :contextId AND Active=1 AND Visible=1 ORDER BY KCName;";
        $arr = array(':contextId' => $context_id);
        return $this->PDOX->allRowsDie($query, $arr);
    }

	function getOneKC($SetID) {
        //$query = "SELECT * FROM {$this->p}kc_main where SetID = :SetID AND Active=1 AND Visible=1 ORDER BY KCName;";
        $query = "SELECT * FROM {$this->p}kc_main where SetID = :SetID AND Visible=1 ORDER BY KCName;";
        $arr = array(':SetID' => $SetID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getOneKCStudent($SetID) {
        $query = "SELECT * FROM {$this->p}kc_main where SetID = :SetID AND Active=1 AND Visible=1 ORDER BY KCName;";
        $arr = array(':SetID' => $SetID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getAllSites($userId, $context_id) {
        $query = "SELECT DISTINCT context_id FROM {$this->p}kc_main where UserID='".$userId."' AND context_id !='".$context_id."' AND Visible=1";
        return $this->PDOX->allRowsDie($query);
    }

    function getQuestions($SetID) {
        $query = "SELECT * FROM {$this->p}kc_questions WHERE SetID=".$SetID." order by QNum;";
        return $this->PDOX->allRowsDie($query);
    }
	
	function eachQuestion($QID) {
        $query = "SELECT * FROM {$this->p}kc_questions WHERE QID=".$QID;
        return $this->PDOX->allRowsDie($query);
    }
	
	
    function createKC($userId, $context_id, $KCName) {
        $query = "INSERT INTO {$this->p}kc_main (UserID, context_id, KCName) VALUES (:userId, :contextId, :KCName);";
        $arr = array(':userId' => $userId, ':contextId' => $context_id, ':KCName' => $KCName);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateKC($SetID, $KCName, $active, $random) {
        $query = "UPDATE {$this->p}kc_main SET KCName = :KCName, Active = :active, Random = :random WHERE SetID = :SetID;";
        $arr = array(':KCName' => $KCName, ':active' => $active, ':random' => $random, ':SetID' => $SetID);
        $this->PDOX->queryDie($query, $arr);
    }

    function togglePublish($SetID, $toggle) {
        $query = "UPDATE {$this->p}kc_main SET Active = :toggle WHERE SetID = :SetID;";
        $arr = array(':toggle' => $toggle, ':SetID' => $SetID);
        $this->PDOX->queryDie($query, $arr);
    }

    function createQuestion($SetID, $QNum, $Question, $Answer, $QType, $A, $B, $C, $D,$Point, $FR, $FW, $RA) {
		
		$query = "INSERT INTO {$this->p}kc_questions (SetID, QNum, Question, Answer, QType, A, B, C, D,Point,FR,FW, RA) VALUES (:SetID, :QNum, :Question, :Answer, :QType, :A, :B, :C, :D,:Point,:FR,:FW,:RA);";
        $arr = array(':SetID' => $SetID, ':QNum' => $QNum, ':Question' => $Question, ':Answer' => $Answer, ':QType' => $QType, ':A' => $A,':B' => $B,':C' => $C,':D' => $D,':Point' => $Point,':FR' => $FR,':FW' => $FW,':RA' => $RA);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }
	
	 function createQuestion2($SetID, $QNum, $Question, $Answer, $QType,$Point, $FR, $FW) {
		
		$query = "INSERT INTO {$this->p}kc_questions (SetID, QNum, Question, Answer, QType,Point,FR,FW) VALUES (:SetID, :QNum, :Question, :Answer, :QType,:Point,:FR,:FW);";
        $arr = array(':SetID' => $SetID, ':QNum' => $QNum, ':Question' => $Question, ':Answer' => $Answer, ':QType' => $QType,':Point' => $Point,':FR' => $FR,':FW' => $FW);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }
	
	
	
    function updateQuestion($QID, $Question, $Answer, $QType, $A, $B, $C, $D,$Point, $FR, $FW, $RA) {
        $query = "UPDATE {$this->p}kc_questions set Question = :Question, Answer = :Answer, QType = :QType, A = :A, B = :B, C = :C, D = :D, Point = :Point, FR=:FR, FW=:FW, RA=:RA where QID = :QID;";
        $arr = array(':Question' => $Question, ':Answer' => $Answer, ':QType' => $QType, ':QID' => $QID, ':A' => $A,':B' => $B,':C' => $C,':D' => $D,':Point' => $Point,':FR' => $FR,':FW' => $FW,':RA' => $RA);
        $this->PDOX->queryDie($query, $arr);
    }
	
	function updateQuestion2($QID, $Question, $Answer, $QType,$Point, $FR, $FW) {
        $query = "UPDATE {$this->p}kc_questions set Question = :Question, Answer = :Answer, QType = :QType, Point = :Point, FR=:FR, FW=:FW where QID = :QID;";
        $arr = array(':Question' => $Question, ':Answer' => $Answer, ':QType' => $QType, ':QID' => $QID,':Point' => $Point,':FR' => $FR,':FW' => $FW);
        $this->PDOX->queryDie($query, $arr);
    }
	
	
    function getQuestionById($QID) {
        $query = "SELECT * FROM {$this->p}kc_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getQuestionBySetAndNumber($SetID, $QNum) {
        $query = "SELECT * FROM {$this->p}kc_questions WHERE QNum = :QNum AND SetID = :SetID;";
        $arr = array(':QNum' => $QNum, ':SetID' => $SetID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getQuestionBySetAndNumber2($SetID, $QNum2) {
        $query = "SELECT * FROM {$this->p}kc_questions WHERE QNum2 = :QNum2 AND SetID = :SetID;";
        $arr = array(':QNum2' => $QNum2, ':SetID' => $SetID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function deleteQuestion($QID) {
        $query = "DELETE FROM {$this->p}kc_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function deleteAllQuestion($SetID) {
        $query = "DELETE FROM {$this->p}kc_questions WHERE SetID = :SetID;";
        $arr = array(':SetID' => $SetID);
        $this->PDOX->queryDie($query, $arr);
    }

    function deleteKC($SetID) {
        $query = "DELETE FROM {$this->p}kc_main WHERE SetID = :SetID ;";
        $arr = array(':SetID' => $SetID);
        $this->PDOX->queryDie($query, $arr);
    }


	
    function getCourseNameForId($context_id) {
        $query = "SELECT title FROM {$this->p}lti_context WHERE context_id = :contextId;";
        $arr = array(':contextId' => $context_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["title"];
    }

    

    function QuestionCorrect($userId, $SetID, $QID) {
        $query = "SELECT * FROM {$this->p}kc_review WHERE UserID = :userId AND SetID = :SetID AND QID = :QID;";
        $arr = array(':userId' => $userId, ':SetID' => $SetID, ':QID' => $QID);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }

  
    function getLinkedSet($linkId) {
        $query = "SELECT * FROM {$this->p}kc_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function saveOrUpdateLink($SetID, $linkId) {
         if ($this->linkIsSet($linkId)) {
             $query = "UPDATE {$this->p}kc_link SET SetID = :SetID WHERE link_id = :linkId;";
         } else {
             $query = "INSERT INTO {$this->p}kc_link (SetID, link_id) VALUES (:SetID, :linkId);";
         }
         $arr = array(':SetID' => $SetID, ':linkId' => $linkId);
         $this->PDOX->queryDie($query, $arr);
    }

    function deleteLink($linkId) {
        $query = "DELETE FROM {$this->p}kc_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        $this->PDOX->queryDie($query, $arr);
    }

    private function linkIsSet($linkId) {
        $query = "SELECT * FROM {$this->p}kc_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        $theLink = $this->PDOX->rowDie($query, $arr);
        return $theLink !== false;
    }
	
	
	
	function addUserData($SetID, $QID, $userId, $Answer,$Attempt,$Date2) {
         $query = "INSERT INTO {$this->p}kc_activity (SetID, QID, UserID, Answer, Attempt, Modified) VALUES ( :SetID, :QID, :userId, :Answer, :Attempt, :Modified);";
         $arr = array(':SetID' => $SetID, ':QID' => $QID, ':userId' => $userId, ':Answer' => $Answer, ':Attempt' => $Attempt, ':Modified' => $Date2);
         $this->PDOX->queryDie($query, $arr);
       
    }
	
	
	function userDataExists($SetID, $userId) {
        $query = "SELECT * FROM {$this->p}kc_activity WHERE SetID = :SetID AND UserID = :userId AND Attempt=1";
        $arr = array(':SetID' => $SetID, ':userId' => $userId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }
	
	function getUserData($SetID, $userId) {
        $query = "SELECT * FROM {$this->p}kc_activity WHERE SetID = :SetID AND UserID = :userId order by Attempt DESC; ";
        $arr = array(':SetID' => $SetID, ':userId' => $userId);
        return $this->PDOX->rowDie($query, $arr);
    }
	
	function Review($QID, $userId, $Attempt) {
        $query = "SELECT * FROM {$this->p}kc_activity WHERE QID = :QID AND UserID = :userId AND Attempt = :Attempt ; ";
        $arr = array(':QID' => $QID, ':userId' => $userId, ':Attempt' => $Attempt);
        return $this->PDOX->rowDie($query, $arr);
    }

	
 function Report($SetID) {
        $query = "SELECT DISTINCT UserID FROM {$this->p}kc_activity WHERE SetID = :SetID;";
        $arr = array(':SetID' => $SetID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

	
function checkStudent($context_id, $UserID){
		$query = "SELECT * FROM {$this->p}kc_students WHERE context_id = :context_id AND UserID = :UserID";
        $arr = array(':context_id' => $context_id, ':UserID' => $UserID);        
		return $result = $this->PDOX->rowDie($query, $arr);	
}


function addStudent($userId, $context_id, $LastName, $FirstName) {
        $query = "INSERT INTO {$this->p}kc_students (UserID, context_id, LastName, FirstName) VALUES (:userId, :context_id, :LastName,:FirstName);";
        $arr = array(':userId' => $userId, ':context_id' => $context_id, ':LastName' => $LastName,':FirstName' => $FirstName);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
}

function getStudentName($UserID) {
        $query = "SELECT * FROM {$this->p}kc_students WHERE UserID = :UserID;";
        $arr = array(':UserID' => $UserID);        
        return $this->PDOX->allRowsDie($query, $arr);
}

	
function getStudentList($context_id) {
        $query = "SELECT * FROM {$this->p}kc_students WHERE context_id = :context_id order by LastName;";
        $arr = array(':context_id' => $context_id);        
        return $this->PDOX->allRowsDie($query, $arr);
}
	
	
function getSetID($context_id) {
        $query = "SELECT SetID FROM {$this->p}kc_main WHERE context_id = :contextId;";
        $arr = array(':contextId' => $context_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["SetID"];
}


function updateQNumber($QID, $QNum) {
        $query = "UPDATE {$this->p}kc_questions set QNum = :QNum where QID = :QID;";
        $arr = array(':QNum' =>$QNum, ':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
}
	
	
function findUserID($user_key) {
        $query = "SELECT user_id FROM {$this->p}lti_user WHERE user_key = :user_key;";
        $arr = array(':user_key' => $user_key);        
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["user_id"];
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}