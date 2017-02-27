<?php

class Adminmodel extends CI_Model 
{
    function __construct() 
	{
        parent::__construct();
        $this->load->helper('cache');
    }
	
    function adminLogin($userName = '', $password = '') 
	{
        $sql = "SELECT * FROM AdminMaster WHERE Email = '" . $userName . "' AND  `Password` = '" . $password . "' AND  `IsDelete` = '0' AND `IsVerify`=1";
        $row = $this->db->query($sql)->row_array();
        if (count($row) > 0) 
		{
            $this->session->set_userdata($row);
            return true;
        } 
		else
            return false;
    }

	function getAdminList($flag, $pageLimit=0, $paging=10)
	{
        $this->db->select("AM.Admin_Id,Concat(FirstName, ' ' , LastName) As AdminName,Email,Phone,Company,IsVerify");
        $this->db->from("AdminMaster AM");
        $this->db->where("AM.IsDelete",0);
        //$this->db->where("AM.IsVerify","1");
        $this->db->where("AM.IsSuperAdmin",0);
        $this->db->order_by('AM.Admin_Id', 'desc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
        //$query=$this->db->get_where('user_access_audit',array('access_id!='=>0),$paging,$pagelimit);
        //return $query->result_array();
    }
	
    function getEntries($flag,$pageLimit=0,$paging=10)
	{
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('UM.User_Id,FirstName,LastName,Company,Email,Phone');
        $this->db->from("UserMaster UM");
        $this->db->where("UM.IsDelete",0);
		$this->db->where("UM.Admin_Id",$admin_id);
        $this->db->order_by('UM.User_Id', 'asc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function getEntriesSurvey($flag,$pageLimit=0,$paging=10){
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('S.Survey_Id,S.Heading,S.Subheading,S.ExplanatoryNote,S.WelcomeParagraph,S.Subtitle,S.SummaryNote');
        $this->db->from("Survey S");
		$this->db->where("S.CreatedBy",$admin_id);
        $this->db->where("S.IsDelete",0);
        $this->db->order_by('S.Survey_Id', 'desc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getEntriesPropositions($flag,$pageLimit=0,$paging=10)
	{
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('S.Heading,SP.SurveyProposition_Id,SP.LeftText,SP.RightText,SP.Survey_Id');
        $this->db->from("SurveyPropositions SP");
        $this->db->join('Survey S','SP.Survey_Id=S.Survey_Id');
		$this->db->where("SP.CreatedBy",$admin_id);
        $this->db->where("S.IsDelete",0);
        //$this->db->order_by('SP.SurveyProposition_Id', 'desc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }

    function getEntriesQuestionGroup($flag,$pageLimit=0,$paging=10)
	{
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQG.SurveyQuestionGroup_Id,SQG.Title,SQG.Survey_Id,S.Heading,SQG.QGroupOrder');
        $this->db->from("SurveyQuestionGroups SQG");
        $this->db->join('Survey S','SQG.Survey_Id=S.Survey_Id');
		$this->db->where("S.CreatedBy",$admin_id);
        $this->db->where("SQG.IsDelete",0);
        $this->db->order_by('SQG.Survey_Id', 'asc');
        $this->db->order_by('SQG.QGroupOrder', 'asc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getDefaultQuestionGroup()
	{
        $this->db->select('SQG.SurveyQuestionGroup_Id,SQG.Title,SQG.Survey_Id,SQG.QGroupOrder');
        $this->db->from("SurveyQuestionGroups SQG");
        $this->db->where("SQG.Survey_Id",'11001100');
		$this->db->where("SQG.IsDelete",0);
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getEntriesQuestion($SurveyQuestionGroup_Id,$flag,$pageLimit=0,$paging=10)
	{
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQ.SurveyQuestion_Id,SQ.QuestionText,SQ.SurveyQuestionGroup_Id,SQ.QuestionOrder,SQG.Title');
        $this->db->from("SurveyQuestions SQ");
        $this->db->join('SurveyQuestionGroups SQG','SQ.SurveyQuestionGroup_Id=SQG.SurveyQuestionGroup_Id','left' );
        //$this->db->where("SQ.SurveyQuestionGroup_Id",$SurveyQuestionGroup_Id);
        $this->db->where("SQ.IsDelete",0);
		$this->db->where("SQ.CreatedBy",$admin_id);
        $this->db->order_by('SQ.SurveyQuestionGroup_Id', 'asc');
        $this->db->order_by('SQ.QuestionOrder', 'asc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getDefaultQuestion($SurveyQuestionGroup_Id,$flag,$pageLimit=0,$paging=10){
        $admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQ.SurveyQuestion_Id,SQ.QuestionText,SQ.SurveyQuestionGroup_Id');
        $this->db->from("SurveyQuestions SQ");
        $this->db->where("SQ.SurveyQuestionGroup_Id",$SurveyQuestionGroup_Id);
        $this->db->where("SQ.IsDelete",0);
        $this->db->where("SQ.CreatedBy",$admin_id);
        $this->db->order_by('SQ.SurveyQuestion_Id', 'asc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
	
	function updateTemplateIsDefault($TemplateId)
	{
		$admin_id = $_SESSION['Admin_Id'];
		$sql = "UPDATE TemplateMaster SET IsDefault = 0 WHERE CreatedBy = '" . $admin_id . "'";
		$this->db->query($sql);
		$sql = "UPDATE TemplateMaster SET IsDefault = 1 WHERE Template_Id = '" . $TemplateId . "' AND CreatedBy = '" . $admin_id . "'";
        return $this->db->query($sql);
	}
	
	
	
    function getEntriesTemplate($flag,$pageLimit=0,$paging=10)
	{
		$admin_id = $_SESSION['Admin_Id'];
        $this->db->select('TM.Template_Id,TM.IsDefault,TM.TemplateName');
        $this->db->from("TemplateMaster TM");
        //$this->db->join('SurveyQuestionGroups SQG','TM.SurveyQuestionGroup_Id=SQG.SurveyQuestionGroup_Id');
        $this->db->where("TM.IsDelete",0);
		$this->db->where("TM.CreatedBy",$admin_id);
        $this->db->order_by('TM.Template_Id', 'asc');
        if($flag==1) 
		{
            $this->db->limit($paging, $pageLimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function checkAdminUserName($userName) 
	{
        $query=$this->db->get_where('AdminMaster',array('Email='=>$userName,'IsDelete='=>0));
        return $query->result_array(); 
    }
	
    function checkUserName($admin_Id,$userName) 
	{
        $query=$this->db->get_where('UserMaster',array('Email='=>$userName,'IsDelete='=>0,'Admin_Id='=>$admin_Id));
        return $query->result_array();
    }
	
    function checkForgotUserName($userName) 
	{
        $query=$this->db->get_where('AdminMaster',array('Email='=>$userName,'IsDelete='=>0));
        return $query->result_array();
    }
	
    function checkPassword($admin_Id,$password) 
	{
        $query=$this->db->get_where('AdminMaster',array('Password='=>$password,'Admin_Id='=>$admin_Id));
        return $query->result_array();
    }
	
    function checkCompanyUser($email) 
	{
        $query=$this->db->get_where('UserMaster',array('UserName='=>$email));
        return $query->result_array(); 
    }
	
    function checkCompanyName($companyName) 
	{
        $query=$this->db->get_where('Company',array('CompanyName='=>$companyName));
        return $query->result_array(); 
    }
    
    function insertEntry($table,$data)
	{
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
	
    function getData($table,$field,$fieldValue)
	{
        $query=  $this->db->get_where($table,array($field=>$fieldValue));
        return $query->result_array();
    }
	
    function updateEntry($table,$data,$filed,$fieldValue)
	{
        $this->db->update($table,$data,array($filed=>$fieldValue));
    }
	
	function update_Entry($table,$data,$fieldValueArray)
	{
        $this->db->update($table,$data,$fieldValueArray);
    }
	
    function deleteData($table,$filed,$fieldValue)
	{        
        $this->db->update($table,array("IsDelete"=>1),array($filed=>$fieldValue));
        //$this->db->delete($table,array($filed=>$fieldValue));
    }
	
    function deleteAllData($table,$filed,$fieldValue)
	{
       // $this->db->update($table,array("IsDelete"=>1),array($filed=>$fieldValue));
        $this->db->delete($table,array($filed=>$fieldValue));
    }
    
    function updatePassword($oldPass, $newPass) 
	{
        $sql = "UPDATE AdminMaster SET Password = '" . $newPass . "' WHERE Admin_Id = '" . $this->session->userdata['Admin_Id'] . "'";
        return $this->db->query($sql);

    }
	
    function surveyList(){
        $admin_Id = $_SESSION['Admin_Id'];
        $query=$this->db->get_where('Survey',array('Survey_Id!='=>0,'CreatedBy'=>$admin_Id,'IsDelete'=>0));
        $result=$query->result_array(); 
        return $result; 
    }
	
    function userlist()
	{
        $admin_id = $_SESSION['Admin_Id'];
        $query=$this->db->get_where('UserMaster',array('User_Id!='=>0,'Admin_Id'=>$admin_id,'IsDelete'=>0));
        //$query=$this->db->get_where('user_master',array('user_id!='=>0));
        $result=$query->result_array();
        return $result;
    }
	
    	
	function getUsersAssignedSurvey($SurveyGroup_Id)
	{
        $this->db->select('a.*,b.FirstName,b.LastName');
        $this->db->from('SurveyGroupUsers a');
        $this->db->join('UserMaster b','a.User_Id=b.User_Id');
        $this->db->where('a.SurveyGroup_Id', $SurveyGroup_Id);
        $this->db->where('b.IsDelete', 0);

        $query=$this->db->get();
        return $query->result_array();
    }
	
    	
	function getUsersAssignedSurveyURL($SurveyGroup_Id,$Iteration)
	{
		$this->db->select('a.SurveyGroupUser_Id,a.User_Id,b.Token,b.Iteration,b.SurveyURL,b.SendEmail');
        $this->db->from('SurveyGroupUsers a');
        $this->db->join('SurveyIteration b','a.SurveyGroupUser_Id=b.SurveyGroupUser_Id');
		$this->db->join('UserMaster u','a.User_Id=u.User_Id');
        $this->db->where('a.SurveyGroup_Id', $SurveyGroup_Id);
		$this->db->where('b.Iteration', $Iteration);
		$this->db->where('u.IsDelete', 0);
		$query=$this->db->get();
        return $query->result_array();
    }
	
	function updateSendEmailSurveyIteration($Survey_Id, $Iteration, $User_Id) 
	{
        $this->db->select('SI.SurveyIteration_Id');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyGroups SG', 'SG.SurveyGroup_Id = SGU.SurveyGroup_Id');
		$this->db->where('SGU.User_Id',$User_Id);
		$this->db->where('SI.Iteration',$Iteration);
		$this->db->where('SG.Survey_Id',$Survey_Id);
		
		 //echo $this->db->get_compiled_select(); die;
		 $result = $this->db->get()->row();
		$surveyIteration_Id = $result->SurveyIteration_Id;
		
		$sql = "UPDATE SurveyIteration SET SendEmail = 1 WHERE SurveyIteration_Id = '" . $surveyIteration_Id . "'";
        return $this->db->query($sql);
    }
	
	function getSurveyURL($Survey_Id, $Iteration, $User_Id) 
	{
        $this->db->select('SI.SurveyURL');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SGU', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyGroups SG', 'SG.SurveyGroup_Id = SGU.SurveyGroup_Id');
		$this->db->where('SGU.User_Id',$User_Id);
		$this->db->where('SI.Iteration',$Iteration);
		$this->db->where('SG.Survey_Id',$Survey_Id);
		return $this->db->get()->row();
    }
	
    function deleteDataAllCOM($table,$deleteArray)
	{
        $this->db->delete($table,$deleteArray);
    }
	
    function createUrlNumber()
	{
        $number=$this->genrateNumber();
        $checkNumber=$this->db->get_where('SurveyIteration',array('Token like '=>$number));
        $result=$checkNumber->result_array();
        $count=count($result);
        if($count==0){
            return $number;
        }else{
            $this->createurlnumber();
        }
    }
	
    function genrateNumber()
	{
        return strtoupper(uniqid());
    }
	
    function checkRound($table,$arrayList)
	{
        $query=  $this->db->get_where($table,$arrayList);
        return $query->result_array();
    }
	
	
	
	function getCommonData($table,$arrayList)
	{
        $query=  $this->db->get_where($table,$arrayList);
        return $query->result_array();
    }
	
    function sendMail($to_email,$bodyContent,$subject='')
	{
        //Load email library
        $mail = new PHPMailer;
        $mail->isSMTP();                                   // Set mailer to use SMTP
        $mail->Host = SMTP_HOST;                    // Specify main and backup SMTP servers
        $mail->SMTPAuth = SMTPAuth;                            // Enable SMTP authentication
        $mail->Username = SMTPUSERNAME;          // SMTP username
        $mail->Password = SMTPPASSWORD; // SMTP password
        $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
        $mail->Port = SMTPPORT;                                 // TCP port to connect to

        $mail->setFrom(MAILSENDFROM, MAILSENDERNAME);
        //$mail->addReplyTo('email@codexworld.com', 'CodexWorld');
        $mail->addAddress($to_email);   // Add a recipient
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->isHTML(true);  // Set email format to HTML
       // echo $bodyContent;die;
        $mail->Subject = $subject; //'Survey URL';
        $mail->Body    = $bodyContent;
        $return = $mail->send();
        return $return;
    }
	
	function distinctSurveyId()
	{
		$this->db->select('DISTINCT (`Survey_Id`)');
        $this->db->from('SurveyPropositions');
        //$this->db->where('SurveyPropositions.IsDelete', 0);
        $query=$this->db->get();
        return $query->result_array();		
	}
	
    function getLastorderId($Survey_Id)
	{
        $admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQG.QGroupOrder');
        $this->db->from('SurveyQuestionGroups SQG');
        $this->db->where('SQG.Survey_Id', $Survey_Id);
        $this->db->where(' SQG.IsDelete', 0);
        $this->db->order_by('SQG.QGroupOrder', 'desc');
        $this->db->limit(0,1);
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getSurveyGroup($surveyId)
	{
        $admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQG.*');
        $this->db->from('SurveyQuestionGroups SQG');
        $this->db->where('SQG.Survey_Id', $surveyId);
		$this->db->where('SQG.IsDelete', 0);
        $this->db->order_by('SQG.QGroupOrder', 'asc');
        $query=$this->db->get();
        return $query->result_array();
    }
	
    function getSurveyGroupQuestion($SurveyId,$SurveyQuestionGroup_Id)
	{
        $admin_id = $_SESSION['Admin_Id'];
        $this->db->select('SQ.*');
        $this->db->from('SurveyQuestions SQ');
        $this->db->where('SQ.Survey_Id', $SurveyId);
        $this->db->where('SQ.SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
		$this->db->where('SQ.IsDelete', 0);
        $this->db->order_by('SQ.QuestionOrder', 'asc');
        $query=$this->db->get();
        return $query->result_array();
    }
	
	function getSurveyStatusData($Survey_Id,$Iteration)
	{
		$this->db->select('SGU.UserName,SI.SurveyURL,SQ.QuestionText,SP.PrepositionType,SIA.Rating,SIA.Reason,SIA.CreatedDate,SIA.UpdatedDate');
		$this->db->from('SurveyGroupUsers SGU');
		$this->db->join('SurveyIteration SI', 'SGU.SurveyGroupUser_Id = SI.SurveyGroupUser_Id');
		$this->db->join('SurveyIterationAnswers SIA', ' SI.SurveyIteration_Id = SIA.SurveyIteration_Id');
		$this->db->join('SurveyGroups SG', ' SGU.SurveyGroup_Id = SG.SurveyGroup_Id');
		$this->db->join('SurveyQuestions SQ', ' SIA.SurveyQuestion_Id = SQ.SurveyQuestion_Id');
		$this->db->join('SurveyPropositions SP', ' SIA.SurveyProposition_Id = SP.SurveyProposition_Id');
		$this->db->where('SG.Survey_Id', $Survey_Id);
		$this->db->where('SI.Iteration', $Iteration);
		$this->db->where('SI.IsActive = 1');
		$this->db->order_by('SGU.UserName, SIA.SurveyQuestion_Id, SIA.SurveyProposition_Id');
		//echo $this->db->get_compiled_select(); die;
		$query=$this->db->get();
        return $query->result_array();
		//return $this->db->get()->row();
	}

	function getSurveyStatus($Survey_Id,$Iteration)
	{
		$this->db->select('SU.UserName, SU.Email, SI.SurveyStatus, SI.SurveyURL, SI.SendEmail');
		$this->db->from('SurveyIteration SI');
		$this->db->join('SurveyGroupUsers SU','SI.SurveyGroupUser_Id = SU.SurveyGroupUser_Id');
		$this->db->join('UserMaster UM','SU.User_Id=UM.User_Id');
		$this->db->where('SU.SurveyGroup_Id', $Survey_Id);
		$this->db->where('SI.Iteration',$Iteration);
		$this->db->where('SI.IsActive',1);
		$this->db->where('UM.IsDelete', 0);
		$this->db->order_by('SU.UserName','asc');
		$query = $this->db->get();        
		return $query->result_array();
	}
}?>