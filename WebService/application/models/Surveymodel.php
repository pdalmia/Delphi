<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Surveymodel extends CI_Model {

	// conneting DB here
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
 
	//get user by token here
	function getUserByToken($token)
	{
		$this->db->select('S.ConfirmationText, S.TextCancel, SU.UserName, SU.Email, SU.SurveyGroup_Id, SU.LibraryName, SU.SurveyGroupUser_Id, SI.SurveyIteration_Id, SI.NumberofQuestions, SI.SurveyStatus, SI.Iteration');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SU','SI.SurveyGroupUser_Id = SU.SurveyGroupUser_Id');
        $this->db->join('SurveyGroups SG','SU.SurveyGroup_Id = SG.SurveyGroup_Id');
        $this->db->join('Survey S','SG.Survey_Id = S.Survey_Id');
		$this->db->join('UserMaster UM','SU.User_Id = UM.User_Id');
		$this->db->where('SI.Token',$token);
		$this->db->where('S.IsDelete',0);
		$this->db->where('UM.IsDelete',0);
		return $this->db->get()->row();
	}
	
	//getting survey details
	function getSurveyByGroup($surveyGroup_Id)
	{
		$this->db->select('S.Heading, S.SubHeading, S.WelcomeParagraph, S.Survey_Id');
		$this->db->from('SurveyGroups SG');
		$this->db->join('Survey S','S.Survey_Id = SG.Survey_Id');
		$this->db->where('SG.SurveyGroup_Id',$surveyGroup_Id);
		$this->db->where('S.IsDelete',0);
		return $this->db->get()->row();
	}
	
	function getSurveyIterationAnswer($surveyIteration_Id)
	{
		$this->db->select('SIA.SurveyQuestion_Id, SIA.SurveyProposition_Id, SIA.Rating, SIA.Reason,SQ.QuestionOrder,SP.PrepositionType');
		$this->db->from('SurveyIterationAnswers SIA');
		$this->db->join('SurveyQuestions SQ','SQ.SurveyQuestion_Id = SIA.SurveyQuestion_Id');
		$this->db->join('SurveyPropositions SP','SP.SurveyProposition_Id = SIA.SurveyProposition_Id');
		$this->db->order_by('SIA.SurveyIterationAnswers_Id',"DESC");
		$this->db->where('SIA.SurveyIteration_Id',$surveyIteration_Id);
		$this->db->where('SQ.IsDelete',0);
		$this->db->limit(1);
		return $this->db->get()->row();
	}
	
	function getSurveyQuestionDetail($surveyId, $questionOrder, $propositionType, $iteration)
	{
		$this->db->select('S.Heading, S.Title, S.SubTitle, S.TextOf, S.TextYourComment, S.TextSave, SQ.QuestionText, SG.Title AS QuestionGroupText, SP.LeftText, SP.RightText, SC.CommentText, SQ.SurveyQuestion_Id, SP.SurveyProposition_Id,SQ.QuestionOrder,SP.PrepositionType');
		$this->db->from('Survey S');
		$this->db->join('SurveyQuestionGroups SG','SG.Survey_Id = S.Survey_Id');
		$this->db->join('SurveyQuestions SQ','SQ.SurveyQuestionGroup_Id = SG.SurveyQuestionGroup_Id');
		$this->db->join('SurveyPropositions SP','SP.Survey_Id = S.Survey_Id');
		$this->db->join('SurveyComments SC','SC.Survey_Id = S.Survey_Id');
		$this->db->where('SG.Survey_Id', $surveyId);
		$this->db->where('SQ.QuestionOrder', $questionOrder);
		$this->db->where('SP.PrepositionType', $propositionType);
		$this->db->where('SC.Iteration', $iteration);
		$this->db->where('S.IsDelete', 0);
		$this->db->where('SG.IsDelete', 0);
		$this->db->where('SQ.IsDelete', 0);
//echo $this->db->get_compiled_select(); die;
		return $this->db->get()->row();
	}
	
	function getSurveyQuestionOrder($surveyQuestionId)
	{
		$this->db->select('SQ.QuestionOrder');
		$this->db->from('SurveyQuestions SQ');
		$this->db->where('SQ.SurveyQuestion_Id', $surveyQuestionId);
		$this->db->where('SQ.IsDelete', 0);
		return $this->db->get()->row();
	}
	
	function getSurveyPrepositionType($surveyPropositionId)
	{
		$this->db->select('SP.PrepositionType');
		$this->db->from('SurveyPropositions SP');
		$this->db->where('SP.SurveyProposition_Id', $surveyPropositionId);
		return $this->db->get()->row();
	}
	
	public function setSurveyIterationAnswer($surveyIterationId, $surveyQuestionId, $surveyPropositionId, $rating, $reason)
	{
		$response = false;
		$data = array();
		if ($reason == '')
		{
			$data = array('Rating'	=> $rating,
		    			'UpdatedDate'	=> date('Y-m-d H:i:s'));
			$this->db->where('SurveyIteration_Id' ,$surveyIterationId);
			$this->db->where('SurveyQuestion_Id' ,$surveyQuestionId);
			$this->db->where('SurveyProposition_Id' ,$surveyPropositionId);
            $result= $this->db->update('SurveyIterationAnswers', $data);
			
			if($this->db->affected_rows() <=0){
				$data = array( 	'SurveyIteration_Id'	=> $surveyIterationId,
							'SurveyQuestion_Id'		=> $surveyQuestionId,
							'SurveyProposition_Id' 	=> $surveyPropositionId,
							'Rating'	=> $rating,
							'CreatedDate'	=> date('Y-m-d H:i:s'),
							'UpdatedDate'	=> date('Y-m-d H:i:s'));
				$result= $this->db->insert('SurveyIterationAnswers', $data);
				if($this->db->affected_rows() >0){
					$response = true;
				}
			}
			else
			{
				$response = true;
			}
		}
		else
		{
			$data = array('Reason'	=> $reason,
		    			'UpdatedDate'	=> date('Y-m-d H:i:s'));
			$this->db->where('SurveyIteration_Id' ,$surveyIterationId);
			$this->db->where('SurveyQuestion_Id' ,$surveyQuestionId);
			$this->db->where('SurveyProposition_Id' ,$surveyPropositionId);
            $result= $this->db->update('SurveyIterationAnswers', $data);
			if($this->db->affected_rows() >0){
				$response = true;
			}
		}
		
		// if ($result)
		// {
			// //Set status of survey
			// $surveyStatus = 1;
			// if ($surveyQuestionId == '26' && $surveyPropositionId == '2')
			// {
				// if ($reason == '')
				// {
					// //need to compare current rating with previous survey rating
					// $previousRating = $this->getPreviousRating($surveyIterationId, $surveyQuestionId, $surveyPropositionId);
					// //$previousRating = $previousRating->Rating + 1;
					// $previousRating = $previousRating->Rating;
					// $currentRating = $rating;
					// if ((($currentRating >= $previousRating) && ($currentRating - $previousRating <= 1)) || (($previousRating>=    $currentRating ) && ($previousRating - $currentRating <= 1)))
					// {
						// $surveyStatus = 2;
					// }
				// }
				// else
				// {
					// $surveyStatus = 2;
				// }
			// }
			// $result= $this->setSurveyStatus($surveyIterationId, $surveyStatus);
		// }
		return $response;
	}
	

	public function getPreviousRating($surveyIterationId, $questionId, $propositionType)
	{
		$this->db->select('SI.SurveyGroupUser_Id, SI.Iteration');
		$this->db->from('SurveyIteration SI');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		$result = $this->db->get()->row();
		
		$surveyGroupUserId = $result->SurveyGroupUser_Id;
		$iteration = $result->Iteration;
		$iteration = $iteration - 1;
		
		$this->db->select('SIA.Rating');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyIterationAnswers SIA', 'SIA.SurveyIteration_Id = SI.SurveyIteration_Id');
		$this->db->join('SurveyPropositions SP', 'SP.SurveyProposition_Id = SIA.SurveyProposition_Id');
		$this->db->where('SI.SurveyGroupUser_Id',$surveyGroupUserId);
		$this->db->where('SI.Iteration',$iteration);
		$this->db->where('SIA.SurveyQuestion_Id',$questionId);
		$this->db->where('SP.PrepositionType',$propositionType);
		return $this->db->get()->row();
	}
	
	public function getSurveyQuestions($surveyGroupId)
	{
		$this->db->select('S.Heading, S.ImportantQuestionsTitle, S.ImportantQuestionsNote, S.TextFiveStatements, S.TextSave, SQ.SurveyQuestion_Id, SQ.QuestionText, SQ.QuestionOrder');
		$this->db->from('SurveyGroups SG');
		$this->db->join('Survey S','S.Survey_Id = SG.Survey_Id');
		$this->db->join('SurveyQuestionGroups SQG','SQG.Survey_Id = S.Survey_Id');
		$this->db->join('SurveyQuestions SQ','SQ.SurveyQuestionGroup_Id = SQG.SurveyQuestionGroup_Id');
		$this->db->where('SG.SurveyGroup_Id',$surveyGroupId);
		$this->db->order_by('SQ.QuestionOrder','asc');
        $query = $this->db->get();        
        return $query->result();
	}
	
	public function setSurveyQuestions($surveyIterationId, $surveyQuestionId1, $surveyQuestionId2, $surveyQuestionId3, $surveyQuestionId4, $surveyQuestionId5)
	{
		$data = array();
		$data = array( 	'SurveyIteration_Id'	=> $surveyIterationId,
		    			'SurveyQuestionId1'		=> $surveyQuestionId1,
						'SurveyQuestionId2'		=> $surveyQuestionId2,
						'SurveyQuestionId3'		=> $surveyQuestionId3,
						'SurveyQuestionId4'		=> $surveyQuestionId4,
						'SurveyQuestionId5'		=> $surveyQuestionId5,
		    			'CreatedDate'	=> date('Y-m-d H:i:s'),
		    			'UpdatedDate'	=> date('Y-m-d H:i:s'));
		$result= $this->db->insert('SurveyIterationQuestionsAnswer', $data);
		
		if ($result)
		{
			//Set status of survey
			$surveyStatus = 3;
			$result = $this->setSurveyStatus($surveyIterationId, $surveyStatus);
		}
		return $result;   
	}
	
	public function getThankyouDetail($surveyGroupId)
	{
		$this->db->select('S.Heading, S.ThankyouSubHeading, S.ThankyouText, S.ThankyouNote, S.InValidUserNote, S.TextSave');
		$this->db->from('SurveyGroups SG');
		$this->db->join('Survey S','S.Survey_Id = SG.Survey_Id');
		$this->db->where('SG.SurveyGroup_Id',$surveyGroupId);
		return $this->db->get()->row();
	}
	
	function setSurveyStatus($surveyIterationId, $surveyStatus)
	{
		$data = array();
		$data = array('SurveyStatus'  => $surveyStatus);
		$this->db->where('SurveyIteration_id' ,$surveyIterationId);
		$result= $this->db->update('SurveyIteration', $data);
		return $result;   
	}
	
	function setSurveyComplete($surveyIterationId, $surveyStatus)
	{
		$result= $this->setSurveyStatus($surveyIterationId, $surveyStatus);
		return $result;   
	}

	function getSurveyPropositionDetail($surveyId, $questionOrder, $propositionType, $surveyIterationId)
	{
        $this->db->select('SP.LeftText, SP.RightText, SC.CommentText, SIA.SurveyQuestion_Id, SIA.SurveyProposition_Id, SIA.Rating, SIA.Reason,SQ.QuestionOrder,SP.PrepositionType');
		$this->db->from('SurveyQuestionGroups SG');
		$this->db->join('SurveyQuestions SQ','SQ.SurveyQuestionGroup_Id = SG.SurveyQuestionGroup_Id');
		$this->db->join('SurveyPropositions SP','SP.Survey_Id = SG.Survey_Id');
		$this->db->join('SurveyComments SC','SC.Survey_Id = SG.Survey_Id');
		$this->db->join('SurveyIterationAnswers SIA', 'SIA.SurveyQuestion_Id = SQ.SurveyQuestion_Id and SIA.SurveyProposition_Id = SP.SurveyProposition_Id');
		$this->db->join('SurveyIteration SI', 'SI.SurveyIteration_Id = SIA.SurveyIteration_Id and SI.Iteration = SC.Iteration');
		$this->db->where('SG.Survey_Id', $surveyId);
		$this->db->where('SQ.QuestionOrder', $questionOrder);
		$this->db->where('SP.PrepositionType', $propositionType);
		$this->db->where('SIA.SurveyIteration_Id',$surveyIterationId);
		$this->db->where('SG.IsDelete',0);
		$this->db->where('SQ.IsDelete',0);
		return $this->db->get()->row();
	}

//get user by token here
	function getSurveyStatus()
	{
		$this->db->select('SU.UserName, SU.Email, SI.SurveyStatus, SI.SurveyURL');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SU','SI.SurveyGroupUser_Id = SU.SurveyGroupUser_Id');
		$this->db->where('SI.Iteration',2);
		$this->db->where('SI.IsActive',1);
		$this->db->order_by('SU.UserName','asc');
		$query = $this->db->get();        
		return $query->result();
	}
	
	public function getIteration($surveyIterationId)
	{
		$this->db->select('SI.Iteration');
		$this->db->from('SurveyIteration SI');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		return $this->db->get()->row();
	}
	
	public function getRating($surveyIterationId, $questionId, $propositionType)
	{
		$this->db->select('SIA.Rating');
		$this->db->from('SurveyIterationAnswers SIA');
		$this->db->join('SurveyIteration SI', 'SI.SurveyIteration_Id = SIA.SurveyIteration_Id');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyPropositions SP', 'SP.SurveyProposition_Id = SIA.SurveyProposition_Id');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		$this->db->where('SIA.SurveyQuestion_Id',$questionId);
		$this->db->where('SP.PrepositionType',$propositionType);
		return $this->db->get()->row();
	}
	
	public function getUserCount($surveyIterationId, $questionId, $propositionType)
	{
		$this->db->select('SGU.Email');
		$this->db->from('SurveyIterationAnswers SIA');
		$this->db->join('SurveyIteration SI', 'SI.SurveyIteration_Id = SIA.SurveyIteration_Id');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyPropositions SP', 'SP.SurveyProposition_Id = SIA.SurveyProposition_Id');
		$this->db->join('UserMaster UM','SGU.User_Id = UM.user_id');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		$this->db->where('SIA.SurveyQuestion_Id',$questionId);
		$this->db->where('SP.PrepositionType',$propositionType);
		$this->db->where('UM.IsDelete',0);
		$query = $this->db->get();
		// return Number of records
		$result=$query->result();
		$totalcount=count($result);
		return $totalcount;
	}
	
	public function getAverageRating($surveyIterationId, $questionId, $propositionType)
	{
		$this->db->select('SIA.Rating');
		$this->db->from('SurveyIterationAnswers SIA');
		$this->db->join('SurveyIteration SI', 'SI.SurveyIteration_Id = SIA.SurveyIteration_Id');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyPropositions SP', 'SP.SurveyProposition_Id = SIA.SurveyProposition_Id');
		$this->db->join('UserMaster UM','SGU.User_Id = UM.user_id');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		$this->db->where('SIA.SurveyQuestion_Id',$questionId);
		$this->db->where('SP.PrepositionType',$propositionType);
		$this->db->where('UM.IsDelete',0);
		$query = $this->db->get();
		
		//Sum of ratings
		//Divide by number of records
		// return int
		$result = $query->result();        
		//Sum of ratings
		$count = 0;
		foreach($result as $ret){
		 $count = $count + $ret->Rating;
		}
		$totalrows=count($result);
		//Divide by number of records
		$avarage=$count/$totalrows;
		// return int
		return floor($avarage);
	}
	
	public function getQuestionCount($surveyIterationId)
	{
		$this->db->select('SG.Survey_Id');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyGroups SG', 'SG.SurveyGroup_Id = SGU.SurveyGroup_Id');
		$this->db->where('SI.SurveyIteration_Id',$surveyIterationId);
		$result = $this->db->get()->row();
		
		$surveyId = $result->Survey_Id;
		
		$this->db->select('SQ.SurveyQuestion_Id');
		$this->db->from('SurveyQuestions SQ');
		$this->db->join('SurveyQuestionGroups SQG', 'SQG.SurveyQuestionGroup_Id = SQ.SurveyQuestionGroup_Id');
		$this->db->where('SQG.Survey_Id',$surveyId);
		$this->db->where('SQ.IsDelete',0);
		$this->db->where('SQG.IsDelete',0);
		$query = $this->db->get();
		// return Number of records
		$result=$query->result();
        $totalcount=count($result);
        return $totalcount;
	}
}
?>