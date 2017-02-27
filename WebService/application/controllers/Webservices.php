<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class webservices extends CI_Controller 
{
	
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->model('Surveymodel');
		$this->load->library("pagination");
	}
        
	   
   // authentication user token
	public function AuthenticateUser($token,$resp=true)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$row_user = $this->Surveymodel->getUserByToken($token);
		if(is_object($row_user))
		{
			$json['status'] = 'true';
			if($resp)
				$json['user'] = (array)$row_user;
			else
				return $row_user;
		}
		else
		{
			$json['message'] = "Invalid token supplied";
		} 
		echo json_encode($json);
		exit;
	}


	public function GetSurveyDetail($token)
	{
        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$row_user = $this->AuthenticateUser($token, false);
		if($row_user)
		{
			$row_survey = $this->Surveymodel->getSurveyByGroup($row_user->SurveyGroup_Id);
			if($row_survey)
			{
				$json['status'] = 'true';
				$json['survey'] = (array)$row_survey;
			}
			else
			{
				$json['message'] = 'Survey not available.';
			}
		}	
		echo json_encode($json);
	}
	
	public function GetNextSurveyQuestion($token)
	{

		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$showNextProposition = false;
		$surveyId = 0;
		$questionId = 0;
		$propositionId = 0;
		$surveyIterationId = 0;
		$surveyStatus = 1;
		$showCommentText = false;
	        $showNextQuestion = false;
		$currentRating = 0;
		$iteration = 0;
		$row_user = $this->AuthenticateUser($token,false);
		$currentQuestionId = 999999;
		if($row_user)
		{
			//print_r($row_user);
			$surveyStatus = $row_user->SurveyStatus;
			if ($surveyStatus == 0 || $surveyStatus == 1)
			{
				$surveyIterationId = $row_user->SurveyIteration_Id;
				$iteration = $row_user->Iteration;
				$row_survey = $this->Surveymodel->getSurveyByGroup($row_user->SurveyGroup_Id);
				if($row_survey)
				{
					//print_r($row_survey);
					$surveyId = $row_survey->Survey_Id; 		
					$row_surveyIterationAnswer = $this->Surveymodel->getSurveyIterationAnswer($surveyIterationId);
					//print_r($row_surveyIterationAnswer);
					if ($row_surveyIterationAnswer)
					{
						$questionId = $row_surveyIterationAnswer->SurveyQuestion_Id;
						$questionOrder = $row_surveyIterationAnswer->QuestionOrder;
						$currentQuestionOrder = $questionOrder;
						$propositionId = $row_surveyIterationAnswer->SurveyProposition_Id;
						$propositionType = $row_surveyIterationAnswer->PrepositionType;
						if ($propositionType == 1)
						{
							$propositionType = 2;	
							$currentRating = 0;
						}
						else
						{
							if ($row_surveyIterationAnswer->Reason != '')
							{
								//need to show next proposition of same question or next question
								$showNextQuestion = true;
							}
							else
							{
								$currentRating = $row_surveyIterationAnswer->Rating;
								$showNextQuestion = $this->ShowNextQuestion($surveyIterationId, $questionId, $propositionId, $currentRating, $propositionType);
							}

							if ($showNextQuestion)
							{
								$questionOrder = $questionOrder + 1;
								$propositionType = 1;
								$currentRating = 0;
							}
							else
							{
								$showCommentText = true;
							}
						}	
					}
					else
					{
						$questionOrder = 1;
						$propositionType = 1;	
						$currentRating = 0;
					}	
					$json['status'] = 'true';
					$json['surveyStatus'] = $surveyStatus;
					//echo $surveyId;
					//echo $questionOrder;
					//echo $propositionType;
					//echo $iteration;
//die;
					$row_questionDetail = $this->Surveymodel->getSurveyQuestionDetail($surveyId, $questionOrder, $propositionType, $iteration);
					if($row_questionDetail)
					{
						$row_propositionDetail = array();
						$previousRating=0;
						if ($propositionType == 2)
						{
							$row_propositionDetail = $this->Surveymodel->getSurveyPropositionDetail($surveyId, $questionOrder, 1, $surveyIterationId);
							//echo 'hello4';
							//print_r($row_propositionDetail);
							$json['propositionDetail'] = (array)$row_propositionDetail;
							if($iteration == 2)
							{
								$previousRating = $this->GetPreviousRating($surveyIterationId, $questionId, $propositionType);
							}
						}
						//print_r($previousRating);
						$previousRating = $previousRating + 1;
						$json['questionDetail'] = (array)$row_questionDetail;
						$json['currentRating'] = $currentRating;
						$json['previousRating'] = $previousRating;
						$json['showCommentText'] = $showCommentText;
					}
					else
					{
						if ($showNextQuestion)
						{
							$questionCount = $this->GetQuestionCount($surveyIterationId);
							if ($questionCount == $currentQuestionOrder)
							{
								$surveyStatus=2;
								$result= $this->Surveymodel->setSurveyStatus($surveyIterationId, $surveyStatus);
								$json['surveyStatus'] = $surveyStatus;
								$json['status'] = 'true';
								$json['message'] = 'All Questions done';
							}
						}
					}
				}
				else
				{       
					$json['message'] = 'Survey Not available';
				}
			}
			else
			{
				$json['surveyStatus'] = $surveyStatus;
				$json['status'] = 'true';
				$json['message'] = 'All Questions done';
			}
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}	
		echo json_encode($json);
	}


	public function SetSurveyIterationAnswer($token, $surveyQuestionId, $surveyPropositionId, $rating, $reason = '')
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token, false);
		if($row_user)
		{
			$surveyIterationId = $row_user->SurveyIteration_Id;
			$result = $this->Surveymodel->setSurveyIterationAnswer($surveyIterationId, $surveyQuestionId, $surveyPropositionId, $rating, $reason);
			if (!$result)
			{
				$json['message'] = 'Some issue in network. Please try after some time.';
			}
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		$json['status'] = $result;
		echo json_encode($json);
	}

	public function SetSurveyIterationAnswer1($token)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token, false);
		if($row_user)
		{
			$surveyQuestionId = $_POST['surveyQuestionId'];
			$surveyPropositionId = $_POST['surveyPropositionId'];
			$rating = $_POST['rating'];
			$reason = $_POST['reason'];
			$surveyIterationId = $row_user->SurveyIteration_Id;
			$result = $this->Surveymodel->setSurveyIterationAnswer($surveyIterationId, $surveyQuestionId, $surveyPropositionId, $rating, $reason);
			if (!$result)
			{
				$json['message'] = 'Some issue in network. Please try after some time.';
			}
			else
			{
				$surveyStatus = 1;
				$surveyPropositionType = $this->Surveymodel->getSurveyPrepositionType($surveyPropositionId);
				$surveyQuestionOrder = $this->Surveymodel->getSurveyQuestionOrder($surveyQuestionId);
				if ($surveyPropositionType && $surveyQuestionOrder && $surveyPropositionType->PrepositionType == 2 && $reason == '')
				{
					$questionCount = $this->GetQuestionCount($surveyIterationId);
					if ($questionCount == $surveyQuestionId)
					{
						//$showNextQuestion = $this->ShowNextQuestion($surveyIterationId, $surveyQuestionOrder->QuestionOrder, $surveyPropositionType->PrepositionType, $rating);		
						$showNextQuestion = $this->ShowNextQuestion($surveyIterationId, $surveyQuestionId, $surveyPropositionId, $rating, $surveyPropositionType->PrepositionType);	
						if ($showNextQuestion)
						{
							$surveyStatus = 2;		
						}
					}
				}
				$result= $this->Surveymodel->setSurveyStatus($surveyIterationId, $surveyStatus);
			}
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		$json['status'] = $result;
		echo json_encode($json);
	}

	public function getSurveyQuestions($token)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token,false);
		if($row_user)
		{
			//print_r($row_user);
			$json['status'] = true;
			$surveyGroupId = $row_user->SurveyGroup_Id;
			$row_surveyQuestions = $this->Surveymodel->getSurveyQuestions($surveyGroupId);
			//print_r($row_surveyQuestions);
			$json['surveyQuestions'] = (array)$row_surveyQuestions;
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		echo json_encode($json);
	}

	public function SetSurveyQuestions($token, $surveyQuestionId1, $surveyQuestionId2, $surveyQuestionId3, $surveyQuestionId4, $surveyQuestionId5)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token,false);
		if($row_user)
		{
			$surveyIterationId = $row_user->SurveyIteration_Id;
			$result = $this->Surveymodel->setSurveyQuestions($surveyIterationId, $surveyQuestionId1, $surveyQuestionId2, $surveyQuestionId3, $surveyQuestionId4, $surveyQuestionId5);
			if (!$result)
			{
				$json['message'] = 'Some issue in network. Please try after some time.';
			}
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		$json['status'] = $result;
		echo json_encode($json);
	}
	
	public function GetPreviousRating($iterationId, $questionId, $propositionId)
	{
		$row_rating = $this->Surveymodel->getPreviousRating($iterationId, $questionId, $propositionId);
		return $row_rating->Rating;
	}
	
	public function GetQuestionCount($surveyIterationId)
	{
		$questionCount = $this->Surveymodel->getQuestionCount($surveyIterationId);
		return $questionCount;
	}
	
	public function ShowNextQuestion($surveyIterationId, $questionId, $propositionId, $secondPropositionCurrentRating, $propositionType)
	{
		$showNextQuestion = true;
		$row_Iteration = $this->Surveymodel->getIteration($surveyIterationId);
		//print_r($row_Iteration);
		$iterationId = $row_Iteration->Iteration;
		if ($iterationId == 1)
		{
			//echo $iterationId.'-'.$questionId.'-'.$propositionId;
			$userCount = $this->Surveymodel->getUserCount($surveyIterationId, $questionId, $propositionType);
			//echo 'hello6';
			//print_r($userCount);
			//echo 'hello8';
			if ($userCount > 10)//10
			{
				$row_secondPropositionAverageRating = $this->Surveymodel->getAverageRating($surveyIterationId, $questionId, $propositionType);
				//echo 'rating-'.$row_secondPropositionAverageRating;
				$showNextQuestion = $this->IsRatingOk($secondPropositionCurrentRating, $row_secondPropositionAverageRating);

				if ($showNextQuestion)
				{
					$row_firstPropositionCurrentRating = $this->Surveymodel->getRating($surveyIterationId, $questionId, $propositionType - 1);
					$firstPropositionCurrentRating = $row_firstPropositionCurrentRating->Rating;
					$row_firstPropositionAverageRating = $this->Surveymodel->getAverageRating($surveyIterationId, $questionId, $propositionType - 1);
					$showNextQuestion = $this->IsRatingOk($firstPropositionCurrentRating, $row_firstPropositionAverageRating);
				}
			}
		}
		else
		{
			$row_secondPropositionPreviousRating = $this->Surveymodel->getPreviousRating($surveyIterationId, $questionId, $propositionType);
			$secondPropositionPreviousRating = $row_secondPropositionPreviousRating->Rating;
			
			$showNextQuestion = $this->IsRatingOk($secondPropositionCurrentRating, $secondPropositionPreviousRating);

			if ($showNextQuestion)
			{
				$row_firstPropositionCurrentRating = $this->Surveymodel->getRating($surveyIterationId, $questionId, $propositionType - 1);
				$firstPropositionCurrentRating = $row_firstPropositionCurrentRating->Rating;
			
				$row_firstPropositionPreviousRating = $this->Surveymodel->getPreviousRating($surveyIterationId, $questionId, $propositionType - 1);
				$firstPropositionPreviousRating = $row_firstPropositionPreviousRating->Rating;
		
				$showNextQuestion = $this->IsRatingOk($firstPropositionCurrentRating, $firstPropositionPreviousRating);
			}
		}
		return $showNextQuestion;
	}
	
	public function IsRatingOk($currentRating, $previousRating)
	{
		$isRatingOk = false;
		if ((($currentRating >= $previousRating) && ($currentRating - $previousRating <= 1)) || (($previousRating>= $currentRating ) && ($previousRating - $currentRating <= 1)))
		{
			$isRatingOk = true;
		}
		return $isRatingOk;
	}
	
	public function GetThankyouDetail($token)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token,false);
		if($row_user)
		{
			$surveyGroupId = $row_user->SurveyGroup_Id;
			$row_thanks = $this->Surveymodel->getThankyouDetail($surveyGroupId);
			$json['surveyThanks'] = (array)$row_thanks;
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		$json['status'] = $result;
		echo json_encode($json);
	}
	
	public function SetSurveyComplete($token)
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

		header('Content-Type: application/json'); 
		$json['status'] = 'false';
		$json['message'] = '';
		$result = false;
		$row_user = $this->AuthenticateUser($token,false);
		if($row_user)
		{
			$surveyIterationId = $row_user->SurveyIteration_Id;
			$result = $this->Surveymodel->setSurveyComplete($surveyIterationId, 4);
			if (!$result)
			{
				$json['message'] = 'Some issue in network. Please try after some time.';
			}
		}
		else
		{
			$json['message'] = 'Invalid token supplied.';
		}
		$json['status'] = $result;
		echo json_encode($json);
	}

	public function GetSurveyStatus()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header('Content-Type: application/json'); 
		$json['status'] = false;
		$json['message'] = '';
		$row_survey = $this->Surveymodel->getSurveyStatus();
		if($row_survey)
		{
		     $json['status'] = true;
		     $json['survey'] = (array)$row_survey;
		}
		else
		{
		      $json['message'] = 'Survey not available.';
		}
		echo json_encode($json);
	}
}