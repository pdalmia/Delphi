<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller 
{
    function __construct() {
        parent::__construct();
        if ($this->session->userData('Admin_Id') == '') {
            redirect('/Superadmin/');
            exit;
        }
        $this->load->helper('url');
        $this->load->model('Basecontroller');
        $this->load->model('Adminmodel');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() 
	{
        $data['body'] = 'index';
        $data['header_title'] = 'Support Centre';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function Logout() 
	{
        $this->session->unset_userdata('logged_in');
        @session_destroy();
        redirect('/Superadmin/');
        exit;
    }

    public function ManageAdmin()
	{
        $_REQUEST = $_GET;
        $result = $this->Adminmodel->getAdminList('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") 
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pageLimit = $StartFrom;
        $appObject = $this->Adminmodel->getAdminList(1,$pageLimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        $linkval='';
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{

           
            if($appObject[$_i]['IsVerify']==1){
                $veify='Verified';
            }else{
                $veify='<a href="' . base_url() . 'Admin/VerifyAdmin/User_Id/' . $appObject[$_i]['Admin_Id'] . '" title="' . $appObject[$_i]['AdminName'] . '">Verify Admin</a>';
            }
            $linkval=$veify.' | <a href="' . base_url() . 'Admin/EditAdmin/User_Id/' . $appObject[$_i]['Admin_Id'] . '" title="' . $appObject[$_i]['AdminName'] . '">Edit</a> |';
            $linkval.="<a href='".base_url()."Admin/DeleteAdmin/User_Id/".$appObject[$_i]['Admin_Id']."/".$appObject[$_i]['AdminName']."' title='".$appObject[$_i]['AdminName']."' onclick='return confirm(\"Are you sure to want delete this record?\");' >Delete</a>";
 
            $appObject[$_i]['Action'] =$linkval;
            unset($appObject[$_i]['Admin_Id']);
            unset($appObject[$_i]['IsVerify']);
        }

        $appObject[-1] = Array(
            "AdminName" => "Name",
            "Email" => "Email",
            "Phone" => "Phone",
            "Company" => "Organisation",
            "Action" => "Action"
        );

        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['user_rights']);

        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['Action']);

        $scriptName = "'/admin/ManageAdmin'";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-striped table-bordered ', 1, $scriptName);
        $data['body'] = 'ManageAdmin';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Admins Management';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function VerifyAdmin()
	{
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('AdminMaster', 'Admin_Id', $user_id);

        $keyverification=$getUserRec[0]['KeyVerification'];
        $message='Dear '.$getUserRec[0]['FirstName'].',<br><br>';
        $message.='Please click on below link to verify your registration in Global Library account.<br> <a href="'.base_url().'Registration/VerifyUser/key/'.$keyverification.'" target="_blank">'.base_url().'Registration/VerifyUser/key/'.$keyverification."</a>";
        $message.="<br><br> Thanks <br> Team Global Library";
        $from_email = "admin@ondai.com";
        $to_email = $getUserRec[0]['Email'];
        $Subject = 'Global Library - Account Verification';
        $return =  $this->Adminmodel->sendMail($to_email,$message,$Subject);
        //Send mail
        if($return)
		{
            $data['message'] = 'Email sent successfully.';
            $this->session->set_flashdata("message",$data);
        }
		else
		{
            $data['message'] = 'Error in sending Email.';
            $this->session->set_flashdata("message",$data);
        }    
        redirect('/admin/ManageAdmin');
    }
	
    public function CreateAdmin()
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {

            $name = $this->input->post('Name');
            $company = $this->input->post('Company');
            $address = $this->input->post('Address');
            $email = $this->input->post('Email');
            $password = $this->input->post('Password');
            $phone = $this->input->post('Phone');

            $checkusername = $this->Adminmodel->checkAdminUserName($email);

            if (count($checkusername) == 0) 
			{
                $this->FirstName = $this->input->post('FirstName');
                $fname= $this->input->post('FirstName');
                $this->LastName = $this->input->post('LastName');
                $this->Company = $this->input->post('Company');
                $this->Address1 = $this->input->post('Address1');
                $this->Address2 = $this->input->post('Address2');
                $this->ZipCode = $this->input->post('ZipCode');
                $this->Email = $this->input->post('Email');
                $this->Password = $this->input->post('Password');
                $this->CreatedDate = date('Y-m-d H:i:s');
                $this->UpdatedDate = date('Y-m-d H:i:s');
                $this->Phone = $this->input->post('Phone');
                $this->	KeyVerification = md5($email);
                $insert = $this->Adminmodel->insertEntry('AdminMaster', $this);


                /*Send A mail to user and admin*/
                $message='Dear Admin,<br><br>';
                $message.="A new admin user ".$fname." is resistered in system please verify him.";
                $message.="<br><br> Thanks <br> Team Global Library";
                $to_email = SUPER_ADMIN_EMAILID;
                $Subject = 'Create a New admin user';
                $return =  $this->Adminmodel->sendMail($to_email,$message,$Subject);
                if(!$return) {
                    $data['message'] = 'User has been added but mail not send.';
                    $this->session->set_flashdata('message', $data);
                } else {
                    $data['message'] = 'User has been added and mail send successfully.';
                    $this->session->set_flashdata('message', $data);
                }
               /* //Load email library
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

                $bodyContent = $message;

                $mail->Subject = 'Create a New admin user';
                $mail->Body    = $bodyContent;
                if(!$mail->send()) {
                    $data['message'] = 'User has been added but mail not send.';
                    $this->session->set_flashdata('message', $data);
                } else {
                    $data['message'] = 'User has been added and mail send successfully.';
                    $this->session->set_flashdata('message', $data);
                }/**/
                //$return =  $this->Adminmodel->sendmail($to_email,$bodyContent);

                //$mail->addAddress($this->input->post('email'));
               // $Subject = 'Create a New admin user';
                $message='Dear '.$fname.',<br><br>';
                $message.="System administration has setup you as an admin user, after verification you can login in system.";
                $message.="<br><br> Thanks <br> Team Global Library";
                //$bodyContent = $message;
               // $mail->Body    = $bodyContent;
               // $mail->send();
                $to_email=$this->input->post('Email');
                $return =  $this->Adminmodel->sendMail($to_email,$message,$Subject);
                /*
                $this->load->library('email');
                // prepare email
                $this->email
                    ->from('admin@ondai.com', 'Global Library')
                    ->to($to_email)
                    ->subject('Global Library - Password')
                    ->message($message)
                    ->set_mailtype('html');
                //Send mail
                $this->email->send();
                /**/
                /*End Here*/
                redirect('/admin/ManageAdmin');
            } 
			else 
			{
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'CreateAdmin';
        $data['header_title'] = 'Manage Administrator';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function EditAdmin() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $user_id = $this->input->post('User_Id');
            $this->FirstName = $this->input->post('FirstName');
            $this->LastName = $this->input->post('LastName');
            $this->Company = $this->input->post('Company');
            $this->Address1 = $this->input->post('Address1');
            $this->Address2 = $this->input->post('Address2');
            $this->ZipCode = $this->input->post('ZipCode');
            $this->Email = $this->input->post('Email');
            $this->Password = $this->input->post('Password');
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $this->Phone = $this->input->post('Phone');
            $insert = $this->Adminmodel->updateEntry('AdminMaster', $this, 'Admin_Id', $user_id);
            redirect('/admin/ManageAdmin');
        }
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('AdminMaster', 'Admin_Id', $user_id);
        $data['body'] = 'EditAdmin';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Manage Administrator';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function DeleteAdmin() 
	{
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->deleteData('AdminMaster', 'Admin_Id', $user_id);
        redirect('/admin/ManageAdmin');

    }
	
    public function ManageUser() 
	{
        $_REQUEST = $_GET;
        $appObject = $this->Adminmodel->getEntries('');
        $data['body'] = 'ManageUser';
        $data['data'] = $appObject;
        $data['header_title'] = 'Manage Respondent';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function CreateUser() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['Admin_Id'];
            $name = $this->input->post('Name');
            $company = $this->input->post('Company');
            $address = $this->input->post('Address');
            $email = $this->input->post('Email');
            $phone = $this->input->post('Phone');

            $checkusername = $this->Adminmodel->checkUsername($admin_id,$email);
            if (count($checkusername) == 0) 
			{
                $this->FirstName = $this->input->post('FirstName');
                $this->LastName = $this->input->post('LastName');
                $this->Company = $this->input->post('Company');
                $this->Address1 = $this->input->post('Address1');
                $this->Address2 = $this->input->post('Address2');
                $this->ZipCode = $this->input->post('ZipCode');
                $this->Email = $this->input->post('Email');
                $this->Phone = $this->input->post('Phone');
                $this->Admin_Id = $admin_id;
                $this->CreatedDate = date('Y-m-d H:i:s');
                $this->UpdatedDate = date('Y-m-d H:i:s');

                $insert = $this->Adminmodel->insertEntry('UserMaster', $this);
                redirect('/admin/ManageUser');
            } 
			else 
			{
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
		echo '2';
        $data['body'] = 'CreateUser';
        $data['header_title'] = 'Add Respondent';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditUser() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $user_id = $this->input->post('User_Id');
            $this->FirstName = $this->input->post('FirstName');
            $this->LastName = $this->input->post('LastName');
            $this->Company = $this->input->post('Company');
            $this->Address1 = $this->input->post('Address1');
            $this->Address2 = $this->input->post('Address2');
            $this->ZipCode = $this->input->post('ZipCode');
            $this->Phone = $this->input->post('Phone');
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->updateEntry('UserMaster', $this, 'User_Id', $user_id);
            redirect('/admin/ManageUser');
        }

        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('UserMaster', 'User_Id', $user_id);
        $data['body'] = 'EditUser';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Edit Respondent';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function DeleteUser() 
	{
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->deleteData('UserMaster', 'User_Id', $user_id);
        $data['message'] = 'User has been deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/ManageUser');
    }

    public function ManageSurvey() 
	{
        $_REQUEST = $_GET;
        $result = $this->Adminmodel->getEntriesSurvey('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") 
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->Adminmodel->getEntriesSurvey(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{
            $actionurl='<a href="' . base_url() . 'Admin/EditSurvey/Survey_Id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Edit</a> | ';
            $actionurl.='<a href="' . base_url() . 'Admin/DeleteSurvey/Survey_Id/' . $appObject[$_i]['Survey_Id'] . '/' . $appObject[$_i]['Heading'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="' . $appObject[$_i]['Heading'] . '">Delete</a> | ';
            $actionurl.='<a href="' . base_url() . 'Admin/ManageSurveyUser/Survey_Id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Survey Respondents</a> | ';
            $actionurl.='<a href="' . base_url() . 'Admin/ManageSurveyUrl/Survey_Id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Assign Link & Send Email</a> | ';
			$actionurl.='<a href="' . base_url() . 'Admin/ManageSurveyStatus/Survey_Id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Status</a>';
            $appObject[$_i]['Action'] = $actionurl;
            unset($appObject[$_i]['Survey_Id']);
            unset($appObject[$_i]['ExplanatoryNote']);
            unset($appObject[$_i]['Subtitle']);
            unset($appObject[$_i]['WelcomeParagraph']);
            unset($appObject[$_i]['SummaryNote']);
        }

        $appObject[-1] = Array(
            "Heading" => "Survey Heading",
            "Subheading" => "Sub Heading",
            //"Subtitle" => "Subtitle",
            "Action" => "Action"
        );

        $scriptName = "'/admin/manageSurvey'";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'ManageSurvey';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Survey';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function CreateSurvey() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->Heading = $this->input->post('Heading');
			$this->Title = $this->input->post('Heading');
            $this->Subheading = $this->input->post('Subheading');
            $this->ExplanatoryNote = $this->input->post('ExplanatoryNote');
            $this->WelcomeParagraph = $this->input->post('WelcomeParagraph');
            //$this->Subtitle = $this->input->post('Subtitle');
            $this->SummaryNote = $this->input->post('SummaryNote');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $surveyid = $this->Adminmodel->insertEntry('Survey', $this);
			
			$surveygroup=array("Survey_Id"=>$surveyid,"CreatedBy"=>$admin_id,"CreatedDate"=>date('Y-m-d H:i:s'),"UpdatedBy"=>$admin_id,"UpdatedDate"=>date('Y-m-d H:i:s'));
			$this->Adminmodel->insertEntry('SurveyGroups', $surveygroup);			
            redirect('/admin/ManageSurvey');
        }
        $data['body'] = 'CreateSurvey';
        $data['header_title'] = 'Create Survey';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditSurvey() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $Survey_Id = $this->input->post('Survey_Id');
            $this->Heading = $this->input->post('Heading');
            $this->Subheading = $this->input->post('Subheading');
            $this->ExplanatoryNote = $this->input->post('ExplanatoryNote');
            $this->WelcomeParagraph = $this->input->post('WelcomeParagraph');
            //$this->Subtitle = $this->input->post('Subtitle');
            $this->SummaryNote = $this->input->post('SummaryNote');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->updateEntry('Survey', $this, 'Survey_Id', $Survey_Id);
            redirect('/admin/ManageSurvey');
        }
        $survey_Id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('Survey', 'survey_Id', $survey_Id);
        $data['body'] = 'EditSurvey';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Edit Survey';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function DeleteSurvey() 
	{
        $survey_Id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->deleteData('Survey', 'Survey_Id', $survey_Id);
		//$deleteSurveygroup = $this->Adminmodel->delDataAll('SurveyGroups','Survey_Id',$survey_Id);
		$deleteSurveygroup = $this->Adminmodel->deleteData('SurveyGroups','Survey_Id',$survey_Id);
		$deleteSurveygroup = $this->Adminmodel->deleteData('SurveyQuestionGroups', 'Survey_Id', $survey_Id);
		$deleteSurveygroupQuestion = $this->Adminmodel->deleteData('SurveyQuestions','Survey_Id',$survey_Id);
        redirect('/admin/ManageSurvey');            
    }
	
    public function ManageProposition() 
	{
        $_REQUEST = $_GET;
        $appObject = $this->Adminmodel->getEntriesPropositions('');
		$rec=array();
		foreach($appObject as $vl){
			$rec[$vl['Survey_Id']]['Heading'][]=$vl['Heading'];	
			$rec[$vl['Survey_Id']]['LeftText'][]=$vl['LeftText'];	
			$rec[$vl['Survey_Id']]['RightText'][]=$vl['RightText'];	
			$rec[$vl['Survey_Id']]['Survey_Id'][]=$vl['Survey_Id'];	
		}
		$resultSize2 = count($rec);
		$appObject=array();
		$st=0;
		foreach($rec as $key=>$val){
			$appObject[$st]['Heading'] = @$val['Heading'][0];
			$appObject[$st]['LeftText'] = @$val['LeftText'][0];
			$appObject[$st]['RightText'] = @$val['RightText'][0];
			//$appObject[$st]['Survey_Id'] = $val['Survey_Id'][0];
			$appObject[$st]['LeftText2'] = @$val['LeftText'][1];
			$appObject[$st]['RightText2'] = @$val['RightText'][1];
			$appObject[$st]['Action'] = '<a href="' . base_url() . 'Admin/EditProposition/Survey_Id/' . @$val['Survey_Id'][0] . '">Edit</a>';
			$st++;
		}
        $data['body'] = 'ManageProposition';
        $data['data'] = $appObject;
        $data['header_title'] = 'Range Descriptions List';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function CreateProposition() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->LeftText = $this->input->post('likehood_LeftText');
            $this->RightText = $this->input->post('likehood_RightText');
            $firstpass_CommentText= $this->input->post('firstpass_CommentText');
            $secondpass_CommentText = $this->input->post('secondpass_CommentText');
            $this->Survey_Id = $this->input->post('Survey_Id');
			$this->PrepositionType = 1;
			$Survey_Id = $this->input->post('Survey_Id');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->insertEntry('SurveyPropositions', $this);
			/**/
			$newSurveyPrepotiotion=array('PrepositionType'=>2,'Survey_Id'=>$Survey_Id,'LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
            $insert = $this->Adminmodel->insertEntry('SurveyPropositions', $newSurveyPrepotiotion);
			/**/
			
			/* Here Enter */
			$commentarray1=array('Survey_Id'=>$Survey_Id,'Iteration'=>1,'CommentText'=>$firstpass_CommentText,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
			$commentarray2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2,'CommentText'=>$secondpass_CommentText,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
			$checkDataall=array('Survey_Id'=>$Survey_Id,'Iteration'=>1);
			$checkdata=$this->Adminmodel->getCommonData('SurveyComments',$checkDataall);
			if(count($checkdata)==0){				
				$this->Adminmodel->insertEntry('SurveyComments', $commentarray1);
			}else{
				$commentarray11=array('CommentText'=>$firstpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->Adminmodel->updateEntry('SurveyComments', $commentarray11, 'Survey_Id', $Survey_Id);				
			}	
			$checkDataall2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2);
			$checkdata2=$this->Adminmodel->getCommonData('SurveyComments',$checkDataall2);
			if(count($checkdata2)==0){				
				$this->Adminmodel->insertEntry('SurveyComments', $commentarray2);
			}else{
				$commentarray22=array('CommentText'=>$secondpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->Adminmodel->updateEntry('SurveyComments', $commentarray22, 'Survey_Id', $Survey_Id);				
			}				
			/*End Here*/
            redirect('/admin/ManageProposition');
        }
        $distinctsurveyid=$this->Adminmodel->distinctSurveyId();
		if(count($distinctsurveyid)==0){
			$distinctsurveyid[0]=array();
		}
		$surveylist = $this->Adminmodel->surveyList();
        $data['body'] = 'CreateProposition';
        $data['surveylist'] = $surveylist;
		$data['distinctsurveyid'] = $distinctsurveyid[0];
        $data['header_title'] = 'Add Range Descriptions';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditProposition() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->LeftText = $this->input->post('likehood_LeftText');
            $this->RightText = $this->input->post('likehood_RightText');
            $firstpass_CommentText= $this->input->post('firstpass_CommentText');
			$secondpass_CommentText = $this->input->post('secondpass_CommentText');
			$Survey_Id = $this->input->post('Survey_Id');			
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->update_Entry('SurveyPropositions', $this, array('Survey_Id'=>$Survey_Id,'PrepositionType'=>1));
			
			$getpredata = $this->Adminmodel->getCommonData('SurveyPropositions', array('Survey_Id'=>$Survey_Id,'PrepositionType'=>2));
			
			if(count($getpredata)==0)
			{
				$newSurveyPrepotiotion=array('PrepositionType'=>2,'Survey_Id'=>$Survey_Id,'LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$insert = $this->Adminmodel->insertEntry('SurveyPropositions', $newSurveyPrepotiotion);
			}
			else
			{	
				$newSurveyPrepotiotion=array('LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->Adminmodel->update_Entry('SurveyPropositions', $newSurveyPrepotiotion, array('Survey_Id'=>$Survey_Id,'PrepositionType'=>2,'CreatedBy'=>$admin_id));
			}	
			/* Here Enter */
			
			//$getpredata = $this->Adminmodel->getData('SurveyPropositions', 'SurveyProposition_Id', $SurveyProposition_Id);
			//$Iteration=$getpredata[0]['Iteration'];
			$getpredatac = $this->Adminmodel->getCommonData('SurveyComments', array('Survey_Id'=>$Survey_Id,'Iteration'=>1));
			if(count($getpredatac)==0)
			{
				$commentarray1=array('Survey_Id'=>$Survey_Id,'Iteration'=>1,'CommentText'=>$firstpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->Adminmodel->insertEntry('SurveyComments', $commentarray1);
			}
			else
			{
				$commentarray11=array('CommentText'=>$firstpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$fieldvaluearray=array('Survey_Id'=>$Survey_Id,'Iteration'=>1);
				$this->Adminmodel->update_Entry('SurveyComments',$commentarray11,$fieldvaluearray);
			}
			
			$getpredatac2 = $this->Adminmodel->getCommonData('SurveyComments', array('Survey_Id'=>$Survey_Id,'Iteration'=>2));
			if(count($getpredatac2)==0)
			{
				$commentarray2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2,'CommentText'=>$secondpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->Adminmodel->insertEntry('SurveyComments', $commentarray2);
			}
			else
			{
				$commentarray22=array('CommentText'=>$secondpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$fieldvaluearray=array('Survey_Id'=>$Survey_Id,'Iteration'=>2);
				$this->Adminmodel->update_Entry('SurveyComments',$commentarray22,$fieldvaluearray);
			}


			/*End Here*/
			
            redirect('/admin/ManageProposition');
        }
        $Survey_Id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('SurveyPropositions', 'Survey_Id', $Survey_Id);
		
		$rec=array();
		foreach($getUserRec as $vl){
			//$rec[$vl['Survey_Id']]['Heading'][]=$vl['Heading'];	
			$rec[$vl['Survey_Id']]['LeftText'][]=$vl['LeftText'];	
			$rec[$vl['Survey_Id']]['RightText'][]=$vl['RightText'];	
			$rec[$vl['Survey_Id']]['Survey_Id'][]=$vl['Survey_Id'];	
		}
		
		$appObject=array();
		$st=0;
		foreach($rec as $key=>$val)
		{
			//$appObject[$st]['Heading'] = $val['Heading'][0];
			$appObject[$st]['LeftText'] = $val['LeftText'][0];
			$appObject[$st]['RightText'] = $val['RightText'][0];
			$appObject[$st]['Survey_Id'] = $val['Survey_Id'][0];
			$appObject[$st]['LeftText2'] = @$val['LeftText'][1];
			$appObject[$st]['RightText2'] = @$val['RightText'][1];
			$firstrec=$this->Adminmodel->getCommonData('SurveyComments',array('Survey_Id'=>$Survey_Id,'Iteration'=>1));
			$appObject[$st]['firstpass_CommentText'] = @$firstrec[0]['CommentText'];
			$secondrec=$this->Adminmodel->getCommonData('SurveyComments',array('Survey_Id'=>$Survey_Id,'Iteration'=>2));
			$appObject[$st]['secondpass_CommentText'] = @$secondrec[0]['CommentText'];
			//$appObject[$st]['Action'] = '<a href="' . base_url() . 'admin/editproposition/Survey_Id/' . $val['Survey_Id'][0] . '">Edit</a>';
		}
		
        $data['body'] = 'EditProposition';
        $data['data'] = $appObject[0];
        
        $surveylist = $this->Adminmodel->surveyList();
        $data['header_title'] = 'Edit Range Descriptions';
        $data['surveylist'] = $surveylist;
        
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function ManageQuestionGroup() 
	{
        $_REQUEST = $_GET;
        $appObject = $this->Adminmodel->getEntriesQuestionGroup('');
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        $finalarray=array_merge($appObject,$defaultgroup);
        $data['body'] = 'ManageQuestionGroup';
        $data['data'] = $finalarray;
        $data['header_title'] = 'Manage Proposition Group';
        //$data['defaultgroup'] = $defaultgroup;
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function DeleteQuestionGroup() 
	{
        $SurveyQuestionGroup_Id = $this->uri->segment(4);        
		$getUserRec = $this->Adminmodel->deleteData('SurveyQuestionGroups', 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
		$deleteSurveygroupQuestion = $this->Adminmodel->deleteData('SurveyQuestions','SurveyQuestionGroup_Id',$SurveyQuestionGroup_Id);
        redirect('/admin/ManageQuestionGroup');
    }
	
    public function CreateQuestionGroup() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->Title = $this->input->post('Title');
            $this->Survey_Id = $this->input->post('Survey_Id');
            $Survey_Id = $this->input->post('Survey_Id');
            /*Get last order group id*/
            $getGPID=$this->Adminmodel->getLastOrderId($Survey_Id);
            if(count($getGPID)==0){
                $ordergroup=1;
            }else{
                $ordergroup=$getGPID[0]['QGroupOrder'];
                $ordergroup=$ordergroup+1;
            }
            /*End Here*/
            $this->QGroupOrder = $ordergroup;
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->insertEntry('SurveyQuestionGroups', $this);
            redirect('/Admin/ManageQuestionGroup');
        }

        $surveylist = $this->Adminmodel->surveyList();
        $data['body'] = 'CreateQuestionGroup';
        $data['surveylist'] = $surveylist;
        $data['header_title'] = 'Create Proposition Group';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditQuestionGroup() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->Survey_Id = $this->input->post('Survey_Id');
            $this->Title = $this->input->post('Title');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->updateEntry('SurveyQuestionGroups', $this, 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
            redirect('/admin/ManageQuestionGroup');
        }
        $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('SurveyQuestionGroups', 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
        $data['body'] = 'EditQuestionGroup';
        $data['data'] = $getUserRec[0];
        
        $surveylist = $this->Adminmodel->surveyList();
        $data['surveylist'] = $surveylist;
        $data['header_title'] = 'Edit Proposition Group';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function ManageQuestion() 
	{
        $_REQUEST = $_GET;
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $result = $this->Adminmodel->getEntriesQuestion($SurveyQuestionGroup_Id,'');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") 
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->Adminmodel->getEntriesQuestion($SurveyQuestionGroup_Id,1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'Admin/EditQuestion/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '/SurveyQuestion_Id/'.$appObject[$_i]['SurveyQuestion_Id'].'" title="">Edit</a>
            | <a href="' . base_url() . 'Admin/DeleteQuestion/SurveyQuestion_Id/' . $appObject[$_i]['SurveyQuestion_Id'] . '/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
            unset($appObject[$_i]['SurveyQuestion_Id']);
            //unset($appObject[$_i]['QuestionText']);
            unset($appObject[$_i]['SurveyQuestionGroup_Id']);
			unset($appObject[$_i]['QuestionOrder']);
			
        }
		
        $appObject[-1] = Array(
            "QuestionText" => "Question Text",
            "Title" => "Group Title",
            "Action" => "Action"
        );
        $scriptName = "/admin/manageQuestion";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'ManageQuestion';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function CreateQuestion()
	{
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit'))
			{
            $admin_id = $_SESSION['Admin_Id'];
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->insertEntry('SurveyQuestions', $this);
            redirect('/admin/ManageQuestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id);
        }
		
        $questiongroup=array();
        $result = $this->Adminmodel->getEntriesQuestionGroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }

        $data['questiongroup'] = $questiongroup;
        $data['body'] = 'CreateeQuestion';
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Create Question';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditQuestion() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $OldSurveyQuestionGroup_Id=$this->input->post('OldSurveyQuestionGroup_Id');
            $SurveyQuestion_Id = $this->input->post('SurveyQuestion_Id');
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
           // echopre($_POST);
            $insert = $this->Adminmodel->updateEntry('SurveyQuestions', $this, 'SurveyQuestion_Id', $SurveyQuestion_Id);
            redirect('/admin/ManageQuestion/SurveyQuestionGroup_Id/'.$OldSurveyQuestionGroup_Id);
        }
        $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $SurveyQuestion_Id = $this->uri->segment(6);
        $getUserRec = $this->Adminmodel->getData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['body'] = 'EditeQuestion';
        $data['data'] = $getUserRec[0];

        $questiongroup=array();
        $result = $this->Adminmodel->getEntriesQuestionGroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }
		$getSurveylist=$this->Adminmodel->surveyList();
		$data['surveylist'] = $getSurveylist;
        $data['questiongroup'] = $questiongroup;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Edit Question';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function DeleteQuestion() 
	{
        $SurveyQuestion_Id = $this->uri->segment(4);
        $SurveyQuestionGroup_Id = $this->uri->segment(6);
        $uid = $this->Adminmodel->deleteData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['message'] = 'Question deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/ManageQuestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id);
    }
	
	/*Here Create manage question new*/
	public function ManageeQuestion() 
	{
        $_REQUEST = $_GET;
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        //$result = $this->Adminmodel->get_entries_question($SurveyQuestionGroup_Id,'');
		$result = $this->Adminmodel->getEntriesQuestion('','');

        $data['body'] = 'ManageeQuestion';
        $data['data'] = $result;
        //$data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
       // $data['totalRows'] = $totalRows;
        //$data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Proposition';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
	public function CreateeQuestion() 
	{
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
			$this->Survey_Id = $this->input->post('Survey_Id');
            $SurveyId = $this->input->post('Survey_Id');
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->QuestionOrder = 999;
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->insertEntry('SurveyQuestions', $this);

            /*Here again get all group and group id*/
            $getAllGroup=$this->Adminmodel->getSurveyGroup($SurveyId);
            $i=1;
            foreach($getAllGroup as $grp){
                $SurveyQuestionGroup_Id = $grp['SurveyQuestionGroup_Id'];
                $getallQuestion=$this->Adminmodel->getSurveyGroupQuestion($SurveyId,$SurveyQuestionGroup_Id);
                foreach($getallQuestion as $qt){
                    $SurveyQuestion_Id=$qt['SurveyQuestion_Id'];
                    $updatearray=array("QuestionOrder"=>$i);
                    $insert = $this->Adminmodel->updateEntry('SurveyQuestions', $updatearray, 'SurveyQuestion_Id', $SurveyQuestion_Id);
                    $i++;
                }
            }
            /* End Here*/
            redirect('/admin/ManageeQuestion/');
        }
		
		$getSurveylist=$this->Adminmodel->surveyList();
		
        $questiongroup=array();
        $result = $this->Adminmodel->getEntriesQuestionGroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }

        $data['questiongroup'] = $questiongroup;
		$data['surveylist'] = $getSurveylist;
        $data['body'] = 'CreateeQuestion';
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Add Proposition';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
	public function EditeQuestion() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['Admin_Id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $OldSurveyQuestionGroup_Id=$this->input->post('OldSurveyQuestionGroup_Id');
            $SurveyQuestion_Id = $this->input->post('SurveyQuestion_Id');
			$this->Survey_Id = $this->input->post('Survey_Id');
            $SurveyId = $this->input->post('Survey_Id');
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
			if($OldSurveyQuestionGroup_Id != $SurveyQuestionGroup_Id)
			{
				$this->QuestionOrder = 999;
			}
			// echopre($_POST);
            $insert = $this->Adminmodel->updateEntry('SurveyQuestions', $this, 'SurveyQuestion_Id', $SurveyQuestion_Id);
			if($OldSurveyQuestionGroup_Id != $SurveyQuestionGroup_Id)
			{
				/*Here again get all group and group id*/
				$getAllGroup=$this->Adminmodel->getSurveyGroup($SurveyId);
				$i=1;
				foreach($getAllGroup as $grp){
					$SurveyQuestionGroup_Id = $grp['SurveyQuestionGroup_Id'];
					$getallQuestion=$this->Adminmodel->getSurveyGroupQuestion($SurveyId,$SurveyQuestionGroup_Id);
					foreach($getallQuestion as $qt){
						$SurveyQuestion_Id=$qt['SurveyQuestion_Id'];
						$updatearray=array("QuestionOrder"=>$i);
						$insert = $this->Adminmodel->updateEntry('SurveyQuestions', $updatearray, 'SurveyQuestion_Id', $SurveyQuestion_Id);
						$i++;
					}
				}
				/* End Here*/
			}
            redirect('/admin/ManageeQuestion/');
        }
		// $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $SurveyQuestion_Id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['body'] = 'EditeQuestion';
        $data['data'] = $getUserRec[0];

        $questiongroup=array();
        //$result = $this->Adminmodel->get_entries_questiongroup('');
        $surveyid=$getUserRec[0]['Survey_Id'];
        $result=$this->Adminmodel->getData('SurveyQuestionGroups','Survey_Id',$surveyid);
        foreach($result as $group)
		{
			if ($group['IsDelete'] == 0)
			{
				$questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
			}
        }
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }
        $data['questiongroup'] = $questiongroup;
		$getSurveylist=$this->Adminmodel->surveyList();
		$data['surveylist'] = $getSurveylist;
        //$data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Edit Question';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
	public function DeleteeQuestion() 
	{
        $SurveyQuestion_Id = $this->uri->segment(4);
        $SurveyQuestionGroup_Id = $this->uri->segment(6);
        $uid = $this->Adminmodel->deleteData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['message'] = 'Question deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/ManageeQuestion');
    }
	
	public function GetSurveyGroup()
	{
		$surveyid=$_POST['Survey_Id'];
		$getSurveyData=$this->Adminmodel->getData('SurveyQuestionGroups','Survey_Id',$surveyid);
		
		$option="<option value=''>Select Group</option>";
		foreach($getSurveyData as $grp)
		{
			 if ($grp['IsDelete'] == 0)
			 {
				 $option.="<option value='".$grp['SurveyQuestionGroup_Id']."'>".$grp['Title']."</option>";
			 }
		}
		$defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        foreach($defaultgroup as $group2){
            $option.="<option value='".$group2['SurveyQuestionGroup_Id']."'>".$group2['Title']."</option>";
        }
		echo $option;
		die;
	}
	
	/*End Here*/
	
    public function ManageDefaultQuestion() 
	{
        $_REQUEST = $_GET;
        $defaultgroup=$this->Adminmodel->getDefaultQuestionGroup();
        $SurveyQuestionGroup_Id=$defaultgroup[0]['SurveyQuestionGroup_Id'];
        $result = $this->Adminmodel->getDefaultQuestion($SurveyQuestionGroup_Id,'');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "")			
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->Adminmodel->getDefaultQuestion($SurveyQuestionGroup_Id,1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'Admin/EditQuestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id.'/SurveyQuestion_Id/'.$appObject[$_i]['SurveyQuestion_Id'].'" title="">Edit</a>
            | <a href="' . base_url() . 'Admin/DeleteQuestion/SurveyQuestion_Id/' . $appObject[$_i]['SurveyQuestion_Id'] . '/"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
            unset($appObject[$_i]['SurveyQuestion_Id']);
            //unset($appObject[$_i]['QuestionText']);
            unset($appObject[$_i]['SurveyQuestionGroup_Id']);
        }
        $appObject[-1] = Array(
            "QuestionText" => "Question Text",
            "Title" => "Group Title",
            "Action" => "Action"
        );
        $scriptName = "/admin/managequestion";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'ManageQuestion';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function ManageTemplate() 
	{
        $_REQUEST = $_GET;
        $result = $this->Adminmodel->getEntriesTemplate('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") 
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->Adminmodel->getEntriesTemplate(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{
			if($appObject[$_i]['IsDefault'] == 0)
			{
				$appObject[$_i]['Default'] = 'No';
			}
			else
			{
				$appObject[$_i]['Default'] = 'Yes';
			}
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'Admin/EditTemplate/Template_Id/' . $appObject[$_i]['Template_Id'] . '" title="">Edit</a> 
             | <a href="' . base_url() . 'Admin/DeleteTemplate/Template_Id/' . $appObject[$_i]['Template_Id'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>
			 | <a href="' . base_url() . 'Admin/SetDefaultTemplate/Template_Id/' . $appObject[$_i]['Template_Id'] . '" onclick=\'return confirm("Are you sure to set this template default?");\' title="">Set Default</a>';
            unset($appObject[$_i]['Template_Id']);
			unset($appObject[$_i]['IsDefault']);
        }
        $appObject[-1] = Array(
		"Default" => "Default Template",
            "TemplateName" => "Template Name",
            "Action" => "Action"
        );
        $scriptName = "/admin/managetemplate";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'ManageTemplate';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Email Template List';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
	public function SetDefaultTemplate() 
	{
        $TemplateId = $this->uri->segment(4);
		$update = $this->Adminmodel->updateTemplateIsDefault($TemplateId);
        $data['message'] = 'Template updated successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/ManageTemplate');
    }

    public function CreateTemplate() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $this->TemplateName = $this->input->post('TemplateName');
            $this->TemplateText = $this->input->post('TemplateText');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->insertEntry('TemplateMaster', $this);
            $data['message'] = 'Template created successfully.';
            $this->session->set_flashdata('message', $data);
            redirect('/admin/ManageTemplate/');
        }
        $data['body'] = 'CreateTemplate';
        $data['header_title'] = 'Add Email Template';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditTemplate() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $admin_id = $_SESSION['Admin_Id'];
            $TemplateId = $this->input->post('Template_Id');
            $this->TemplateName = $this->input->post('TemplateName');
            $this->TemplateText = $this->input->post('TemplateText');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->Adminmodel->updateEntry('TemplateMaster', $this, 'Template_Id', $TemplateId);
            $data['message'] = 'Template updated successfully.';
            $this->session->set_flashdata('message', $data);
            redirect('/admin/ManageTemplate/');
        }
        $TemplateId = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('TemplateMaster', 'Template_Id', $TemplateId);
        $data['body'] = 'EditTemplate';
        $data['data'] = $getUserRec[0];       
        $data['header_title'] = 'Edit Email Template';
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
    public function DeleteTemplate() 
	{
        $TemplateId = $this->uri->segment(4);
        $uid = $this->Adminmodel->deleteData('TemplateMaster', 'Template_Id', $TemplateId);
        $data['message'] = 'Template deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/ManageTemplate');
    }

    public function ManageCompany() 
	{
        $_REQUEST = $_GET;
        $result = $this->Adminmodel->getCompany();
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") 
		{
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) 
		{
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->Adminmodel->getCompany($pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) 
		{
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'Admin/EditCompany/Company_Id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Edit</a> / <a href="' . base_url() . 'Admin/deletecompany/company_id/' . $appObject[$_i]['company_id'] . '/' . $appObject[$_i]['company_name'] . '?lightbox[iframe]=true&lightbox[width]=560&lightbox[height]=280"  class="ui-lightbox" title="' . $appObject[$_i]['company_name'] . '">Delete</a> / <a href="' . base_url() . 'admin/managecuser/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Manage User</a> <br> <a href="' . base_url() . 'Admin/viewdata/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">View Data</a> / <a href="' . base_url() . 'admin/peopleonthemove/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">People on the move</a>';
            $appObject[$_i]['primary_contact'] = $appObject[$_i]['primary_contact'] . '&nbsp;&nbsp;(' . $appObject[$_i]['primary_email'] . ')';
			$appObject[$_i]['Form'] = '<a href="' . base_url() . 'Admin/FormEdit/Company_Id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Valuemap Form</a> / <a href="' . base_url() . 'adminleadership/leadershipformedit/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Leadership Dashbord Form</a>';
			
            $appObject[$_i]['start_date'] = date('m-d-Y', strtotime($appObject[$_i]['start_date']));
            $appObject[$_i]['end_date'] = date('m-d-Y', strtotime($appObject[$_i]['end_date']));
            unset($appObject[$_i]['company_id']);
            unset($appObject[$_i]['industry']);
            unset($appObject[$_i]['primary_email']);
            unset($appObject[$_i]['contact_phone']);
            unset($appObject[$_i]['Address1']);
            unset($appObject[$_i]['Address2']);
            unset($appObject[$_i]['city']);
            unset($appObject[$_i]['state']);
            unset($appObject[$_i]['zip']);
            unset($appObject[$_i]['Phone']);
            unset($appObject[$_i]['fax']);
            unset($appObject[$_i]['IsDelete']);
            unset($appObject[$_i]['user_logo']);
        }
        $appObject[-1] = Array(
            "company_name" => "Company Name",
            "primary_contact" => "Primary BCG Contact",
            "start_date" => "Start Date",
            "end_date" => "End Date",
            "department" => "Department",
            "Action" => "Action",
            "Form" => "Manage Form"
        );
        /* echo "
          <pre>
          ";
          print_r($appObject);
          exit;/* */
        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['user_rights']);

        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['Action']);
        $scriptName = "'/Admin/ManageUser'";
        $htmlcreate = $this->Basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);

        $data['body'] = 'ManageCompany';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $this->load->view('Admin/TemplateAdmin', $data);
    }

    public function UserLogo() 
	{
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $user_id = $this->input->post('user_id');
            if ($_FILES['user_logo']['name'] != "") 
			{
                $filename = time() . str_replace(" ", "_", $_FILES['user_logo']['name']);
                move_uploaded_file($_FILES['user_logo']['tmp_name'], "userlogo/" . $filename);
                $this->user_logo = $filename;
                $insert = $this->Adminmodel->updateEntry('UserMaster', $this, 'seq_id', $user_id);
                redirect('/admin/ManageUser');
            } 
			else 
			{
                redirect('/admin/ManageUser');
            }
        }
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('UserMaster', 'seq_id', $user_id);
        $data['body'] = 'admin/UserLogo';
        $data['data'] = $getUserRec[0];
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function ImageThumbnail() 
	{
        $imageurl = $this->uri->segment(3);
        $data['imagepath'] = base_url() . 'userlogo/' . $imageurl;
        $this->load->view('admin/imagethumbnail', $data);
    }

    public function ChangePassword() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('update')) 
		{
            $old_password= $this->input->post('old_Password');
            $new_password= $this->input->post('new_Password');
            $cnew_password= $this->input->post('cnew_Password');
            $admin_id=$_SESSION['Admin_Id'];
            $chkpass=$this->Adminmodel->checkPassword($admin_id,$old_password);
            if(count($chkpass)==0)
			{
                $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissable">Old password not matched.</div>');
            }
			elseif($new_password!=$cnew_password)
			{
                $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissable">New password and confirm password not matched.</div>');
            }
			else
			{
                if ($this->Adminmodel->updatePasword($this->input->post('old_password'), $this->input->post('new_password'))) 
				{
                    $this->session->set_flashdata("message", 'Password Changed.');
                    redirect('admin/ChangePassword');
                }
				else 
				{
                    $data['message'] = 'Password not matched';
                    $this->session->set_flashdata("message", 'Password Not Changed.');
                }
            }
        }
        //$this->load->view('admin/header');
        $data['body']='ChangePassword';
		$data['header_title'] = 'Change Password';
        $this->load->view('admin/TemplateAdmin', $data);
        //$this->load->view('Admin/footer');
    }
	
    public function ManageSurveyUser() 
	{
	$data['error'] = false;
	$survey_Id = $this->uri->segment(4);
	$data['success'] = true;
	if ($this->input->post('submit')) 
	{
		$survey_Id = $this->input->post('Survey_Id');
		$admin_Id = $_SESSION['Admin_Id'];
		
		$getSurvey=$this->Adminmodel->getData('Survey', 'Survey_Id', $survey_Id);
		$LibraryName=$getSurvey[0]['Heading'];
		
		$getSurveyGroup=$this->Adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
		$surveyGroupId=$getSurveyGroup[0]['SurveyGroup_Id'];
		
		$getSurveyUsers=$this->Adminmodel->getData('SurveyGroupUsers', 'SurveyGroup_Id', $surveyGroupId);
		
		$alreadyUserList=array();
		foreach($getSurveyUsers as $alreadyuser)
		{
			$alreadyUserList[]=$alreadyuser['User_Id'];
		}	
		
		$surveyUserList = $this->input->post('surveyUserList');
		
		if($surveyUserList=='')
		{
			$surveyUserList=array();
		}
	
		
		$differenceUserList=array_diff($alreadyUserList, @$surveyUserList);
		
		
		if(count($surveyUserList)>0)
		{
			foreach($surveyUserList as $userID){

				/*Here Insert data in SurveyGroupUsers */
				$checkUserRegistered=$this->Adminmodel->getCommonData('SurveyGroupUsers', array('User_Id'=>$userID,'SurveyGroup_Id'=>$surveyGroupId));
				if(count($checkUserRegistered)==0)
				{
					$getUserDetails=$this->Adminmodel->getData('UserMaster', 'User_Id', $userID);
					$email=$getUserDetails[0]['Email'];
					$userName=$getUserDetails[0]['FirstName']." ".$getUserDetails[0]['LastName'];
					
					$groupUserArray=array('User_Id'=>$userID, 'UserName'=>$userName, 'Email'=>$email, 'LibraryName'=>$LibraryName, 'SurveyGroup_Id'=>$surveyGroupId, 'CreatedBy'=>$admin_Id, 'CreatedDate'=>date('Y-m-d H:i:s'), 'UpdatedBy'=>$admin_Id, 'UpdatedDate'=>date('Y-m-d H:i:s'));
					$insertGroupuser = $this->Adminmodel->insertEntry('SurveyGroupUsers', $groupUserArray);
				}
				/*End Here*/					
			}
			
			if(count($differenceUserList)>0)
			{
				foreach($differenceUserList as $userId){
					$deleteUserArray=array('User_Id'=>$userId,'SurveyGroup_Id'=>$surveyGroupId);
					$this->Adminmodel->deleteDataAllCOM('SurveyGroupUsers',$deleteUserArray);
				}
			}
			/*End Here*/
			$data['message'] = 'Survey respondents updated successfully.';
			$this->session->set_flashdata('message', $data);
			redirect('/admin/ManageSurveyUser/Survey_Id/'.$survey_Id);
		}
	}
	$getSurveyGroupId=$this->Adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
	$SurveyGroupId=$getSurveyGroupId[0]['SurveyGroup_Id'];
	$getUserRegistered = $this->Adminmodel->getCommonData('SurveyGroupUsers', array('SurveyGroup_Id'=>$SurveyGroupId));
	
	$selectedUser=array();
	
	foreach($getUserRegistered as $seleleduserList)
	{
		$selectedUser[]=$seleleduserList['User_Id'];
	}
	
	$data['body'] = 'ManageSurveyUser';
	$data['data'] = @$selectedUser;
	$getSurveyname=$this->Adminmodel->getData("Survey",'Survey_Id',$survey_Id);
	$userList = $this->Adminmodel->userList();
	$data['header_title'] = 'Manage Survey Respondents';
	$data['userlist'] = $userList;
	$data['survey_id'] = $survey_Id;
	$data['survey_name'] = $getSurveyname[0]['Heading'];
	$this->load->view('admin/TemplateAdmin', $data);
}



public function ManageSurveyUrl()
	{
        $survey_Id = $this->uri->segment(4);
        $roundId = @$this->uri->segment(6);
        if($roundId==""){
            $roundId=1;
        }

        if ($this->input->post('submit')) 
		{
            $survey_Id = $this->input->post('Survey_Id');
            $surveyUserList = $this->input->post('surveyuserlist');
 
			$roundId = $this->input->post('roundid');
            $admin_Id = $_SESSION['Admin_Id'];
			
			$getSurveyGroupId=$this->Adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
			$surveyGroupId=$getSurveyGroupId[0]['SurveyGroup_Id'];
			
            if(count($surveyUserList)>0)
			{
                foreach($surveyUserList as $key=>$userId)
				{
                    $getdynamicURLnumber=$this->Adminmodel->createUrlNumber();
					//$surveyURL=BASE_URL_SURVEY."/survey/".$getdynamicURLnumber;
					$surveyURL=$getdynamicURLnumber;
					/*Here insert into SurveyIteration */
					$getSurveyGroupUser_Id=$this->Adminmodel->getCommonData('SurveyGroupUsers', array('SurveyGroup_Id'=>$surveyGroupId,'User_Id'=>$userId));
					$SurveyGroupUser_Id=$getSurveyGroupUser_Id[0]['SurveyGroupUser_Id'];
					
					$alreadyURLAssigned=$this->Adminmodel->getCommonData('SurveyIteration', array('SurveyGroupUser_Id'=>$SurveyGroupUser_Id,'Iteration'=>$roundId));
					
					if(count($alreadyURLAssigned)==0)
					{
						$iterationArray=array("SurveyGroupUser_Id"=>$SurveyGroupUser_Id,'Token'=>$getdynamicURLnumber,'Iteration'=>$roundId,'NumberOfBoxes'=>7, 'NumberofQuestions'=>26, 'SurveyURL'=>$surveyURL);
						$insert = $this->Adminmodel->insertEntry('SurveyIteration', $iterationArray);						
					}
					else
					{
						$iterationArray=array('Token'=>$getdynamicURLnumber,'SurveyURL'=>$surveyURL);
						$whereArray=array('SurveyGroupUser_Id'=>$SurveyGroupUser_Id,'Iteration'=>$roundId);
						$update = $this->Adminmodel->update_Entry('SurveyIteration', $iterationArray, $whereArray);					
					}
					
					/*End Here*/
					
                }
                $data['message'] = 'Survey respondents links updated successfully.';
                $this->session->set_flashdata('message', $data);
                redirect('/admin/ManageSurveyUrl/Survey_Id/'.$survey_Id."/Round/".$roundId);
            }
        }
        if ($this->input->post('sendurlmail')) 
		{
            $survey_Id = $this->input->post('Survey_Id');
            $surveyUserList = $this->input->post('surveyuserlist');
            $roundId = $this->input->post('roundid');
            $admin_Id = $_SESSION['Admin_Id'];
			
            if(count($surveyUserList)>0)
			{
                foreach($surveyUserList as $key=>$userID)
				{
                    $row_userSurveyURL=$this->Adminmodel->getSurveyURL($survey_Id, $roundId, $userID);
					if($row_userSurveyURL)
					{
						$url=$row_userSurveyURL->SurveyURL;
						$url='<a href="'.BASE_URL_SURVEY.$url.'" target="_blank">'.BASE_URL_SURVEY.$url.'</a>';
						
						
						
						$userData=$this->Adminmodel->getData('UserMaster','User_Id',$userID);
						$fname=$userData[0]['FirstName'];
						$lname=$userData[0]['LastName'];
						$usermail=$userData[0]['Email'];

						$templatedata=$this->Adminmodel->getCommonData('TemplateMaster',array('IsDefault'=>1,'CreatedBy'=>$admin_Id));
						
						if($templatedata)
						{
							$bodyContent=$templatedata[0]['TemplateText'];
						}
						else
						{
							$bodyContent='<p>Hi&nbsp;##FNAME##&nbsp;##LNAME##,</p><p>Please click on below URL for survey:</p><p>##URL##</p><p>&nbsp;</p><p>Thanks,</p><p>Team Global Library</p>';
						}
						
						
						$bodyContent=str_replace("##FNAME##",$fname,$bodyContent);
						$bodyContent=str_replace("##LNAME##",$lname,$bodyContent);
						$bodyContent=str_replace("##EMAIL##",$usermail,$bodyContent);
						$bodyContent=str_replace("##URL##",$url,$bodyContent);
						//echo $bodyContent;
						//die;
							
						$to_email = $usermail;
						$subject='Survey URL';
						$this->Adminmodel->sendMail($to_email,$bodyContent,$subject);
						$this->Adminmodel->updateSendEmailSurveyIteration($survey_Id, $roundId, $userID);
					}
                }
						
                $data['message'] = 'Survey respondents link email sent successfully.';
                $this->session->set_flashdata('message', $data);
                redirect('/admin/ManageSurveyUrl/Survey_Id/'.$survey_Id."/Round/".$roundId);
            }
        }

		
        $getSurveyGroup=$this->Adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
		$surveyGroupId=$getSurveyGroup[0]['SurveyGroup_Id'];

		$getAllUsers = $this->Adminmodel->getUsersAssignedSurvey($surveyGroupId);		
		$getUsersURLAssigned = $this->Adminmodel->getUsersAssignedSurveyURL($surveyGroupId, $roundId);
        
        $selectedUser=array();
		$mailSent=array();
		
        foreach($getUsersURLAssigned as $userURLAssigned){
            $selectedUser[$userURLAssigned['User_Id']]=$userURLAssigned['SurveyURL'];
			if($userURLAssigned['SendEmail'] == '1')
			{
				$mailSent[$userURLAssigned['User_Id']]='Yes';
			}
			else
			{
				$mailSent[$userURLAssigned['User_Id']]='No';
			}
		}

        $data['selecteduser'] = @$selectedUser;
		$data['mailSended'] = @$mailSent;
		
        $getSurveyname=$this->Adminmodel->getData("Survey",'Survey_Id',$survey_Id);
        $data['body'] = 'ManageSurveyUrl';
        $data['data'] = @$getAllUsers;
        $data['header_title'] = 'Manage Survey Link';
        $data['roundid'] = $roundId;
        $data['survey_id'] = $survey_Id;
        $data['survey_name'] = $getSurveyname[0]['Heading'];
        $this->load->view('admin/TemplateAdmin', $data);
    }

	
	public function ManageSurveyStatus()
	{
		
        $survey_Id = $this->uri->segment(4);
        $roundid = @$this->uri->segment(6);
        if($roundid=="")
		{
            $roundid=1;
        }
		$getSurveyRec = $this->Adminmodel->getSurveyStatus($survey_Id, $roundid);
        $getSurveyname=$this->Adminmodel->getData("Survey",'Survey_Id',$survey_Id);
		
		
		if ($this->input->post('DownloadData')) 
		{
			include("phpexcel/Classes/PHPExcel.php");
			$getSurveyStatusData = $this->Adminmodel->getSurveyStatusData($survey_Id, $roundid);
			//$data['getSurveyStatusData'] = @$getSurveyStatusData;
			
			$objPHPExcel = new PHPExcel();
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Ondai Team")
			->setLastModifiedBy("Ondai Team")
			->setTitle("Office 2007 XLSX Data Document")
			->setSubject("Office 2007 XLSX Data Document")
			->setCategory("Download data file");
			$styleArray = array('font' => array(
									'bold' => true
													));
			$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'UserName')
						->setCellValue('B1', 'SurveyURL')
						->setCellValue('C1', 'QuestionText')
						->setCellValue('D1', 'PropositionType')
						->setCellValue('E1', 'Rating')
						->setCellValue('F1', 'Reason')
						->setCellValue('G1', 'CreatedDate')
						->setCellValue('H1', 'UpdatedDate');
			$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
			for ($col = 'A'; $col !== 'H'; $col++){
				$objPHPExcel->getActiveSheet()
						->getColumnDimension($col)
						->setAutoSize(true);
			}
			$rowCount=2;
			
			foreach($getSurveyStatusData as $rowr) {
			if($rowr['PrepositionType'] == '1')
			{
				$prepositionType = 'Likelihood';
			}
			else
			{
				$prepositionType = 'Desirability';
			}
			$url=$rowr['SurveyURL'];
			$url=BASE_URL_SURVEY.$url;
				
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $rowr['UserName']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $url);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $rowr['QuestionText']);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $prepositionType);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $rowr['Rating']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $rowr['Reason']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $rowr['CreatedDate']);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $rowr['UpdatedDate']);
				$rowCount++;	
			}
			$xlsname = "Survey.xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $xlsname . '"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
			
		}
		//$data['mailSended'] = @$mailSended;
        $data['body'] = 'ManageSurveyStatus';
        $data['data'] = @$getSurveyRec;
        $data['header_title'] = 'Survey Status';
        $data['roundid'] = $roundid;
        $data['survey_id'] = $survey_Id;
        $data['survey_name'] = $getSurveyname[0]['Heading'];
        $this->load->view('admin/TemplateAdmin', $data);
    }
	
	public function CreateCompany() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $username = $this->input->post('email');
            $companyname = $this->input->post('company_name');
            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('vpassword');
            $errorset = 0;
            if ($password != $confirmpassword) 
			{
                $data['message'] = 'Password not matched';
                $this->session->set_flashdata('message', $data);
                $errorset = 1;
            }
            $checkusername = $this->Adminmodel->checkCompanyUser($username);
            $checkcompany = $this->Adminmodel->checkCompanyName($companyname);
            if (count($checkusername) == 0 && count($checkcompany) == 0 && $errorset == 0) 
			{
                $this->company_name = $this->input->post('company_name');
                $this->Address1 = $this->input->post('Address1');
                $this->department = $this->input->post('department');
                $this->Address2 = $this->input->post('Address2');
                $this->industry = $this->input->post('industry');
                $this->city = $this->input->post('city');
                $this->primary_contact = $this->input->post('primary_contact');
                $this->state = $this->input->post('state');
                $this->primary_email = $this->input->post('primary_email');
                $this->zip = $this->input->post('zip');
                $this->contact_phone = $this->input->post('contact_phone');
                $this->Phone = $this->input->post('Phone');
                $stdate = explode("-", $this->input->post('fdate'));
                $this->start_date = $stdate[2] . '-' . $stdate[0] . '-' . $stdate[1];
                $this->fax = $this->input->post('fax');
                $tdate = explode("-", $this->input->post('tdate'));
                $this->end_date = $tdate[2] . '-' . $tdate[0] . '-' . $tdate[1];
                if ($_FILES['user_logo']['name'] != "") 
				{
                    $filename = time() . str_replace(" ", "_", $_FILES['user_logo']['name']);
                    move_uploaded_file($_FILES['user_logo']['tmp_name'], "userlogo/" . $filename);
                    $this->user_logo = $filename;
                }

                $company_id = $this->Adminmodel->insertEntry('company', $this);
                $parimary = array('fname' => $this->input->post('fname'), 'username' => $this->input->post('email'), 'email' => $this->input->post('email'), 'password' => $this->input->post('password'));
                $user_id = $this->Adminmodel->insertEntry('user_master', $parimary);
                $parimaryrole = array('company_id' => $company_id, 'user_id' => $user_id, 'role_id' => 3);
                $this->Adminmodel->insertEntry('company_primary_user', $parimaryrole);
                redirect('/admin/managecompany');
            } 
			else 
			{
                if (count($checkusername) > 0) 
				{
                    $data['message'] = 'Email should be unique';
                }
                if (count($checkcompany) > 0) 
				{
                    $data['message'] = 'Company with the same name already exist. Please enter a new company name';
                }
                $this->session->set_flashdata('message', $data);
            }
        }
        $statelist = $this->Adminmodel->states();
        $data['statelist'] = $statelist;
        $data['body'] = 'CreateCompany';
        $this->load->view('admin/TemplateAdmin', $data);
    }

    public function EditCompany() 
	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 
		{
            $username = $this->input->post('email');
            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('vpassword');
            $errorset = 0;
            if ($password != $confirmpassword) 
			{
                $data['message'] = 'Password not matched';
                $this->session->set_flashdata('message', $data);
                $errorset = 1;
            }
            //$checkusername=$this->Adminmodel->checkcompanyuser($username);
            if ($errorset == 0) 
			{
                $this->company_name = $this->input->post('company_name');
                $this->Address1 = $this->input->post('Address1');
                $this->department = $this->input->post('department');
                $this->Address2 = $this->input->post('Address2');
                $this->industry = $this->input->post('industry');
                $this->city = $this->input->post('city');
                $this->primary_contact = $this->input->post('primary_contact');
                $this->state = $this->input->post('state');
                $this->primary_email = $this->input->post('primary_email');
                $this->zip = $this->input->post('zip');
                $this->contact_phone = $this->input->post('contact_phone');
                $this->Phone = $this->input->post('Phone');
                $stdate = explode("-", $this->input->post('fdate'));
                $this->start_date = $stdate[2] . '-' . $stdate[0] . '-' . $stdate[1];
                $this->fax = $this->input->post('fax');
                $company_id = $this->input->post('company_id');
                $user_id = $this->input->post('user_id');
                $tdate = explode("-", $this->input->post('tdate'));
                $this->end_date = $tdate[2] . '-' . $tdate[0] . '-' . $tdate[1];
                if ($_FILES['user_logo']['name'] != "") {
                    $filename = time() . str_replace(" ", "_", $_FILES['user_logo']['name']);
                    move_uploaded_file($_FILES['user_logo']['tmp_name'], "userlogo/" . $filename);
                    $this->user_logo = $filename;
                }
                $insert = $this->Adminmodel->updateEntry('company', $this, 'company_id', $company_id);
                $parimary = array('FirstName' => $this->input->post('FirstName'), 'username' => $this->input->post('email'), 'password' => $this->input->post('password'));
                $insert = $this->Adminmodel->updateEntry('user_master', $parimary, 'seq_id', $user_id);
                redirect('/admin/manageCompany');
            } 
			else 
			{
                // $data['message'] = 'Email should be unique';
                $this->session->set_flashdata('message', $data);
            }
        }
        $company_id = $this->uri->segment(4);
        $getUserRec = $this->Adminmodel->getData('company', 'company_id', $company_id);
        $statelist = $this->Adminmodel->states();
        $data['statelist'] = $statelist;
        $data['body'] = 'EditCompany';
        $data['data'] = $getUserRec[0];
        $getUserRelation = $this->Adminmodel->getData('company_primary_user', 'company_id', $company_id);
        $user_id = $getUserRelation[0]['user_id'];
        $getUserRec = $this->Adminmodel->getData('UserMaster', 'seq_id', $user_id);
        $data['data2'] = $getUserRec[0];
        $this->load->view('admin/TemplateAdmin', $data);
    }

}