<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('admin_id') == '') {
            redirect('/superadmin/');
            exit;
        }
        $this->load->helper('url');
        $this->load->model('basecontroller');
        $this->load->model('adminmodel');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['body'] = 'index';
        $data['header_title'] = 'Dashboard';
        $this->load->view('admin/template_admin', $data);
    }

    public function logout() {
        $this->session->unset_userdata('logged_in');
        @session_destroy();
        redirect('/superadmin/');
        exit;
    }

    /**
     *
     */
    public function manageadmin(){
        $_REQUEST = $_GET;
        $result = $this->adminmodel->get_adminlist('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_adminlist(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        $linkval='';
        for ($_i = 0; $_i < $resultSize2; ++$_i) {

            $appObject[$_i]['admin_name'] =$appObject[$_i]['admin_fname']." ".$appObject[$_i]['admin_lname'];
            if($appObject[$_i]['is_verify']==1){
                $veify='Verified';
            }else{
                $veify='<a href="' . base_url() . 'admin/verifyadmin/user_id/' . $appObject[$_i]['admin_id'] . '" title="' . $appObject[$_i]['admin_fname'] . '">Verify Admin</a>';
            }
            $linkval=$veify.' | <a href="' . base_url() . 'admin/editadmin/user_id/' . $appObject[$_i]['admin_id'] . '" title="' . $appObject[$_i]['admin_fname'] . '">Edit</a> |';
            $linkval.="<a href='".base_url()."admin/deleteadmin/user_id/".$appObject[$_i]['admin_id']."/".$appObject[$_i]['admin_fname']."' title='".$appObject[$_i]['admin_name']."' onclick='return confirm(\"Are you sure to want delete this record?\");' >Delete</a>";
 
            $appObject[$_i]['Action'] =$linkval;
            unset($appObject[$_i]['admin_fname']);
            unset($appObject[$_i]['admin_lname']);
            unset($appObject[$_i]['admin_id']);
            unset($appObject[$_i]['is_verify']);
        }

        $appObject[-1] = Array(
            "admin_name" => "Name",
            "email" => "Email",
            "phone" => "Phone",
            "company" => "Organisation",
            "Action" => "Action"
        );

        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['user_rights']);

        if (isset($_REQUEST['print_report']))
            unset($appObject[-1]['Action']);

        $scriptName = "'/admin/manageadmin'";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-striped table-bordered ', 1, $scriptName);
        $data['body'] = 'manageadmin';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Administrator';
        $this->load->view('admin/template_admin', $data);
    }
    public function verifyadmin(){
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('admin_master', 'admin_id', $user_id);

        $keyverification=$getUserRec[0]['key_verification'];
        $message='Dear '.$getUserRec[0]['admin_fname'].',<br><br>';
        $message.='Please click on below link to verify your registration in Global Library account.<br> <a href="'.base_url().'registration/verifyuser/key/'.$keyverification.'" target="_blank">'.base_url().'registration/verifiefuser/key/'.$keyverification."</a>";
        $message.="<br><br> Thanks <br> Team Global Library";
        $from_email = "admin@ondai.com";
        $to_email = $getUserRec[0]['email'];

        //Load email library
        $this->load->library('email');
        // prepare email
        $this->email
            ->from('admin@ondai.com', 'Global Library')
            ->to($getUserRec[0]['email'])
            ->subject('Global Library - Account Verification')
            ->message($message)
            ->set_mailtype('html');
       /* $this->email->send();
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($from_email, 'Admin');
        $this->email->to($to_email);
        $this->email->subject('Admin Verification');
        $this->email->message($message); /**/

        //Send mail
        if($this->email->send()){
            $data['message'] = 'Email sent successfully.';
            $this->session->set_flashdata("message",$data);
        }else{
            $data['message'] = 'Error in sending Email.';
            $this->session->set_flashdata("message",$data);
        }    
        redirect('/admin/manageadmin');
    }
    public function createadmin(){
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {

            $name = $this->input->post('name');
            $company = $this->input->post('company');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');

            $checkusername = $this->adminmodel->checkadminusername($email);

            if (count($checkusername) == 0) {
                $this->admin_fname = $this->input->post('fname');
                $fname= $this->input->post('fname');
                $this->admin_lname = $this->input->post('lname');
                $this->company = $this->input->post('company');
                $this->address1 = $this->input->post('address1');
                $this->address2 = $this->input->post('address2');
                $this->zipcode = $this->input->post('zipcode');
                $this->email = $this->input->post('email');
                $this->password = $this->input->post('password');
                $this->created_date = date('Y-m-d H:i:s');
                $this->updated_date = date('Y-m-d H:i:s');
                $this->phone = $this->input->post('phone');
                $this->	key_verification = md5($email);
                $insert = $this->adminmodel->insert_entry('admin_master', $this);


                /*Send A mail to user and admin*/
                $message='Dear Admin,<br><br>';
                $message.="A new admin user ".$fname." is resistered in system please verify him.";
                $message.="<br><br> Thanks <br> Team Global Library";
                $to_email = SUPER_ADMIN_EMAILID;
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

                $bodyContent = $message;

                $mail->Subject = 'Create a New admin user';
                $mail->Body    = $bodyContent;
                if(!$mail->send()) {
                    $data['message'] = 'User has been added but mail not send.';
                    $this->session->set_flashdata('message', $data);
                } else {
                    $data['message'] = 'User has been added and mail send successfully.';
                    $this->session->set_flashdata('message', $data);
                }


                $mail->addAddress("gpsraj.govind@gmail.com");
                $mail->Subject = 'Create a New admin user';
                $message='Dear '.$fname.',<br><br>';
                $message.="System administration has setup you as an admin user, after verification you can login in system.";
                $message.="<br><br> Thanks <br> Team Global Library";
                $bodyContent = $message;
                $mail->Body    = $bodyContent;
                $mail->send();
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



                redirect('/admin/manageadmin');
            } else {
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'createadmin';
        $data['header_title'] = 'Manage Administrator';
        $this->load->view('admin/template_admin', $data);
    }
    public function editadmin() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $user_id = $this->input->post('user_id');
            $this->admin_fname = $this->input->post('fname');
            $this->admin_lname = $this->input->post('lname');
            $this->company = $this->input->post('company');
            $this->address1 = $this->input->post('address1');
            $this->address2 = $this->input->post('address2');
            $this->zipcode = $this->input->post('zipcode');
            $this->email = $this->input->post('email');
            $this->password = $this->input->post('password');
            $this->updated_date = date('Y-m-d H:i:s');
            $this->phone = $this->input->post('phone');
            $insert = $this->adminmodel->update_entry('admin_master', $this, 'admin_id', $user_id);
            redirect('/admin/manageadmin');
        }
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('admin_master', 'admin_id', $user_id);
        $data['body'] = 'editadmin';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Manage Administrator';
        $this->load->view('admin/template_admin', $data);
    }
    public function deleteadmin() {
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->delData('admin_master', 'admin_id', $user_id);
        redirect('/admin/manageadmin');

    }
    public function manageuser() {
        $_REQUEST = $_GET;
        $appObject = $this->adminmodel->get_entries('');
        /*$resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        /*
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/edituser/user_id/' . $appObject[$_i]['user_id'] . '" title="' . $appObject[$_i]['user_fname'] . '">Edit</a> / 
            <a href="' . base_url() . 'admin/deleteuser/user_id/' . $appObject[$_i]['user_id'] . '/' . $appObject[$_i]['user_fname'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="' . $appObject[$_i]['user_fname'] . '">Delete</a>';
            unset($appObject[$_i]['user_id']);
            unset($appObject[$_i]['company']);
        }

        $appObject[-1] = Array(
            "user_fname" => "First Name",
            "user_lname" => "Last Name",
            "email" => "Email",
            "phone" => "Phone",
            //"company" => "Organisation",
            "Action" => "Action"
        );
/**/
       // $scriptName = "'/admin/manageuser'";
        //$htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-striped table-bordered', 1, $scriptName,'search-user');
        $data['body'] = 'manageuser';
        $data['data'] = $appObject;
        //$data['TotalPages'] = $TotalPages;
       // $data['totalRows'] = $totalRows;
        //$data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage User';
        $this->load->view('admin/template_admin', $data);
    }

    public function createuser() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $name = $this->input->post('name');
            $company = $this->input->post('company');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');

            $checkusername = $this->adminmodel->checkusername($admin_id,$email);
            if (count($checkusername) == 0) {
                $this->user_fname = $this->input->post('fname');
                $this->user_lname = $this->input->post('lname');
                $this->company = $this->input->post('company');
                $this->address1 = $this->input->post('address1');
                $this->address2 = $this->input->post('address2');
                $this->zipcode = $this->input->post('zipcode');
                $this->email = $this->input->post('email');
                $this->phone = $this->input->post('phone');
                $this->admin_id = $admin_id;
                $this->created_date = date('Y-m-d H:i:s');
                $this->updated_date = date('Y-m-d H:i:s');

                $insert = $this->adminmodel->insert_entry('user_master', $this);
                redirect('/admin/manageuser');
            } else {
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'createuser';
        $data['header_title'] = 'Create User';
        $this->load->view('admin/template_admin', $data);
    }

    public function edituser() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $user_id = $this->input->post('user_id');
            $this->user_fname = $this->input->post('fname');
            $this->user_lname = $this->input->post('lname');
            $this->company = $this->input->post('company');
            $this->address1 = $this->input->post('address1');
            $this->address2 = $this->input->post('address2');
            $this->zipcode = $this->input->post('zipcode');
            $this->phone = $this->input->post('phone');
            $this->updated_date = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->update_entry('user_master', $this, 'user_id', $user_id);
            redirect('/admin/manageuser');
        }
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('user_master', 'user_id', $user_id);
        $data['body'] = 'edituser';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Edit User';
        $this->load->view('admin/template_admin', $data);
    }

    public function deleteuser() {
        //if ($this->input->post('submit')) {
            $user_id = $this->uri->segment(4);
            $getUserRec = $this->adminmodel->delData('user_master', 'user_id', $user_id);
            redirect('/admin/manageuser');
            //echo "success";
            //exit;
        //}
        /*$user_id = $this->uri->segment(4);
        $username = $this->uri->segment(5);
        $data['body'] = 'deleteuser';
        $data['user_id'] = $user_id;
        $data['deletetype'] = 'User';
        $data['deleteusername'] = $username;
        $data['deleteurl'] = base_url() . 'admin/deleteuser/user_id/' . $user_id;
        //$data['data'] = $getUserRec[0];
        $this->load->view('admin/deleteuser', $data); /**/
    }
    public function managesurvey() {
        $_REQUEST = $_GET;
        $result = $this->adminmodel->get_entries_survey('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries_survey(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $actionurl='<a href="' . base_url() . 'admin/editsurvey/survey_id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Edit</a> | ';
            $actionurl.='<a href="' . base_url() . 'admin/deletesurvey/survey_id/' . $appObject[$_i]['Survey_Id'] . '/' . $appObject[$_i]['Heading'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="' . $appObject[$_i]['Heading'] . '">Delete</a> | ';
            $actionurl.='<a href="' . base_url() . 'admin/managesurveyuser/survey_id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Survey Users</a> | ';
            $actionurl.='<a href="' . base_url() . 'admin/managesurveyurl/survey_id/' . $appObject[$_i]['Survey_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Assign URL</a>';
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

        $scriptName = "'/admin/managesurvey'";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'managesurvey';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Survey';
        $this->load->view('admin/template_admin', $data);
    }

    public function createsurvey() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
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
            $surveyid = $this->adminmodel->insert_entry('Survey', $this);
			
			$surveygroup=array("Survey_Id"=>$surveyid,"CreatedBy"=>$admin_id,"CreatedDate"=>date('Y-m-d H:i:s'),"UpdatedBy"=>$admin_id,"UpdatedDate"=>date('Y-m-d H:i:s'));
			$this->adminmodel->insert_entry('SurveyGroups', $surveygroup);			
            redirect('/admin/managesurvey');
        }
        $data['body'] = 'createsurvey';
        $data['header_title'] = 'Create Survey';
        $this->load->view('admin/template_admin', $data);
    }

    public function editsurvey() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $Survey_Id = $this->input->post('Survey_Id');
            $this->Heading = $this->input->post('Heading');
            $this->Subheading = $this->input->post('Subheading');
            $this->ExplanatoryNote = $this->input->post('ExplanatoryNote');
            $this->WelcomeParagraph = $this->input->post('WelcomeParagraph');
            //$this->Subtitle = $this->input->post('Subtitle');
            $this->SummaryNote = $this->input->post('SummaryNote');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->update_entry('Survey', $this, 'Survey_Id', $Survey_Id);
            redirect('/admin/managesurvey');
        }
        $survey_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('Survey', 'survey_Id', $survey_Id);
        $data['body'] = 'editsurvey';
        $data['data'] = $getUserRec[0];
        $data['header_title'] = 'Edit Survey';
        $this->load->view('admin/template_admin', $data);
    }

    public function deletesurvey() {
        $survey_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->delData('Survey', 'Survey_Id', $survey_Id);
		$deleteSurveygroup = $this->adminmodel->delDataAll('SurveyGroups','Survey_Id',$survey_Id);
        redirect('/admin/managesurvey');            
    }
    public function manageproposition() {
        $_REQUEST = $_GET;
        $appObject = $this->adminmodel->get_entries_propositions('');
		//echopre($appObject);
        /*$resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries_propositions(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";/**/
        
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
			$appObject[$st]['Action'] = '<a href="' . base_url() . 'admin/editproposition/Survey_Id/' . @$val['Survey_Id'][0] . '">Edit</a>';
			$st++;
		}
		//echopre($appObject);
		/*
        $appObject[-1] = Array(
            "Heading" => "Survey",
            "LeftText" => "Left Text",
            "RightText" => "Right Text",
            "LeftText2" => "D. Left Text",
            "RightText2" => "D. Right Text",
            "Action" => "Action"
        );/**/
       // $scriptName = "'/admin/manageproposition'";
       // $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName,'search-user');
        $data['body'] = 'manageproposition';
        $data['data'] = $appObject;
        //$data['TotalPages'] = $TotalPages;
        //$data['totalRows'] = $totalRows;
       // $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Proposition';
        $this->load->view('admin/template_admin', $data);
    }

    public function createproposition() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
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
            $insert = $this->adminmodel->insert_entry('SurveyPropositions', $this);
			/**/
			$newSurveyPrepotiotion=array('PrepositionType'=>2,'Survey_Id'=>$Survey_Id,'LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));			
            $insert = $this->adminmodel->insert_entry('SurveyPropositions', $newSurveyPrepotiotion);
			/**/
			
			/* Here Enter */
			$commentarray1=array('Survey_Id'=>$Survey_Id,'Iteration'=>1,'CommentText'=>$firstpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
			$commentarray2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2,'CommentText'=>$secondpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
			$checkDataall=array('Survey_Id'=>$Survey_Id,'Iteration'=>1);
			$checkdata=$this->adminmodel->getcommandata('SurveyComments',$checkDataall);
			if(count($checkdata)==0){				
				$this->adminmodel->insert_entry('SurveyComments', $commentarray1);
			}else{
				$commentarray11=array('CommentText'=>$firstpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->adminmodel->update_entry('SurveyComments', $commentarray11, 'Survey_Id', $Survey_Id);				
			}	
			$checkDataall2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2);
			$checkdata2=$this->adminmodel->getcommandata('SurveyComments',$checkDataall2);
			if(count($checkdata2)==0){				
				$this->adminmodel->insert_entry('SurveyComments', $commentarray2);
			}else{
				$commentarray22=array('CommentText'=>$secondpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->adminmodel->update_entry('SurveyComments', $commentarray22, 'Survey_Id', $Survey_Id);				
			}				
			/*End Here*/
            redirect('/admin/manageproposition');
        }
        $distinctsurveyid=$this->adminmodel->distinctsurveyid();
		if(count($distinctsurveyid)==0){
			$distinctsurveyid[0]=array();
		}
		$surveylist = $this->adminmodel->surveylist();
		
        $data['body'] = 'createproposition';
        $data['surveylist'] = $surveylist;
		$data['distinctsurveyid'] = $distinctsurveyid[0];
        $data['header_title'] = 'Create Proposition';
        $this->load->view('admin/template_admin', $data);
    }

    public function editproposition() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $this->LeftText = $this->input->post('likehood_LeftText');
            $this->RightText = $this->input->post('likehood_RightText');
            $firstpass_CommentText= $this->input->post('firstpass_CommentText');
			$secondpass_CommentText = $this->input->post('secondpass_CommentText');
			$Survey_Id = $this->input->post('Survey_Id');			
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->updateentry('SurveyPropositions', $this, array('Survey_Id'=>$Survey_Id,'PrepositionType'=>1,'CreatedBy'=>$admin_id));
			
			$getpredata = $this->adminmodel->getcommandata('SurveyPropositions', array('Survey_Id'=>$Survey_Id,'PrepositionType'=>2,'CreatedBy'=>$admin_id));
			
			if(count($getpredata)==0){
				$newSurveyPrepotiotion=array('PrepositionType'=>2,'Survey_Id'=>$Survey_Id,'LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));			
				$insert = $this->adminmodel->insert_entry('SurveyPropositions', $newSurveyPrepotiotion);
			}else{	
				$newSurveyPrepotiotion=array('LeftText'=>$this->input->post('desirable_LeftText'),'RightText'=>$this->input->post('desirable_RightText'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->adminmodel->updateentry('SurveyPropositions', $newSurveyPrepotiotion, array('Survey_Id'=>$Survey_Id,'PrepositionType'=>2,'CreatedBy'=>$admin_id));
			}	
			/* Here Enter */
			
			//$getpredata = $this->adminmodel->getData('SurveyPropositions', 'SurveyProposition_Id', $SurveyProposition_Id);
			//$Iteration=$getpredata[0]['Iteration'];
			$getpredatac = $this->adminmodel->getcommandata('SurveyComments', array('Survey_Id'=>$Survey_Id,'Iteration'=>1,'CreatedBy'=>$admin_id));		
			if(count($getpredatac)==0){
				$commentarray1=array('Survey_Id'=>$Survey_Id,'Iteration'=>1,'CommentText'=>$firstpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->adminmodel->insert_entry('SurveyComments', $commentarray1);
			}else{
				$commentarray11=array('CommentText'=>$firstpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$fieldvaluearray=array('Survey_Id'=>$Survey_Id,'Iteration'=>1);
				$this->adminmodel->updateentry('SurveyComments',$commentarray11,$fieldvaluearray);	
			}
			
			$getpredatac2 = $this->adminmodel->getcommandata('SurveyComments', array('Survey_Id'=>$Survey_Id,'Iteration'=>2,'CreatedBy'=>$admin_id));
			if(count($getpredatac2)==0){
				$commentarray2=array('Survey_Id'=>$Survey_Id,'Iteration'=>2,'CommentText'=>$secondpass_CommentText,'CreatedBy'=>$admin_id,'CreatedDate'=>date('Y-m-d H:i:s'),'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$this->adminmodel->insert_entry('SurveyComments', $commentarray2);
			}else{
				$commentarray22=array('CommentText'=>$secondpass_CommentText,'UpdatedBy'=>$admin_id,'UpdatedDate'=>date('Y-m-d H:i:s'));
				$fieldvaluearray=array('Survey_Id'=>$Survey_Id,'Iteration'=>2);
				$this->adminmodel->updateentry('SurveyComments',$commentarray22,$fieldvaluearray);
			}
			
			
			/*End Here*/
			
            redirect('/admin/manageproposition');
        }
        $Survey_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('SurveyPropositions', 'Survey_Id', $Survey_Id);
		
		$rec=array();
		foreach($getUserRec as $vl){
			//$rec[$vl['Survey_Id']]['Heading'][]=$vl['Heading'];	
			$rec[$vl['Survey_Id']]['LeftText'][]=$vl['LeftText'];	
			$rec[$vl['Survey_Id']]['RightText'][]=$vl['RightText'];	
			$rec[$vl['Survey_Id']]['Survey_Id'][]=$vl['Survey_Id'];	
		}
		
		
		$appObject=array();
		$st=0;
		foreach($rec as $key=>$val){
			//$appObject[$st]['Heading'] = $val['Heading'][0];
			$appObject[$st]['LeftText'] = $val['LeftText'][0];
			$appObject[$st]['RightText'] = $val['RightText'][0];
			$appObject[$st]['Survey_Id'] = $val['Survey_Id'][0];
			$appObject[$st]['LeftText2'] = @$val['LeftText'][1];
			$appObject[$st]['RightText2'] = @$val['RightText'][1];
			$firstrec=$this->adminmodel->getcommandata('SurveyComments',array('Survey_Id'=>$Survey_Id,'Iteration'=>1));
			$appObject[$st]['firstpass_CommentText'] = @$firstrec[0]['CommentText'];
			$secondrec=$this->adminmodel->getcommandata('SurveyComments',array('Survey_Id'=>$Survey_Id,'Iteration'=>2));
			$appObject[$st]['secondpass_CommentText'] = @$secondrec[0]['CommentText'];
			
			//$appObject[$st]['Action'] = '<a href="' . base_url() . 'admin/editproposition/Survey_Id/' . $val['Survey_Id'][0] . '">Edit</a>';
		}
		
        $data['body'] = 'editproposition';
        $data['data'] = $appObject[0];
        
        $surveylist = $this->adminmodel->surveylist();
        $data['header_title'] = 'Edit Proposition';
        $data['surveylist'] = $surveylist;
        
        $this->load->view('admin/template_admin', $data);
    }
    public function managequestiongroup() {
        $_REQUEST = $_GET;
        $result = $this->adminmodel->get_entries_questiongroup('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries_questiongroup(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/editquestiongroup/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Edit</a> | 
                <a href="' . base_url() . 'admin/deletequestiongroup/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '/' . $appObject[$_i]['Heading'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="' . $appObject[$_i]['Heading'] . '">Delete</a><!-- |
                <a href="' . base_url() . 'admin/managequestion/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '" title="' . $appObject[$_i]['Heading'] . '">Manage Question</a -->
            ';
            unset($appObject[$_i]['SurveyQuestionGroup_Id']);
            unset($appObject[$_i]['CommentText']);
            unset($appObject[$_i]['Survey_Id']);
        }

        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach ($defaultgroup as $def){
            $appObject[$_i]['Title'] =$def['Title'];
            $appObject[$_i]['Heading'] ="-";
            //$appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/managedefaultquestion/" title="' . $def['Title'] . '">Manage Question</a>';
			$appObject[$_i]['Action'] = '-';
        }
        $appObject[-1] = Array(
            "Title" => "Title",
            "Heading" => "Survey",
            "Action" => "Action"
        );
        $scriptName = "/admin/managequestiongroup";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'managequestiongroup';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question Group';


        //$data['defaultgroup'] = $defaultgroup;
        $this->load->view('admin/template_admin', $data);
    }
    public function deletequestiongroup() {
        $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->delData('SurveyQuestionGroups', 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
        redirect('/admin/managequestiongroup');
    }
    public function createquestiongroup() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $this->Title = $this->input->post('Title');
            $this->Survey_Id = $this->input->post('Survey_Id');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->insert_entry('SurveyQuestionGroups', $this);
            redirect('/admin/managequestiongroup');
        }

        $surveylist = $this->adminmodel->surveylist();
        $data['body'] = 'createquestiongroup';
        $data['surveylist'] = $surveylist;
        $data['header_title'] = 'Create Question Group';
        $this->load->view('admin/template_admin', $data);
    }

    public function editquestiongroup() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->Survey_Id = $this->input->post('Survey_Id');
            $this->Title = $this->input->post('Title');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->update_entry('SurveyQuestionGroups', $this, 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
            redirect('/admin/managequestiongroup');
        }
        $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('SurveyQuestionGroups', 'SurveyQuestionGroup_Id', $SurveyQuestionGroup_Id);
        $data['body'] = 'editquestiongroup';
        $data['data'] = $getUserRec[0];
        
        $surveylist = $this->adminmodel->surveylist();
        $data['surveylist'] = $surveylist;
        $data['header_title'] = 'Edit Question Group';
        $this->load->view('admin/template_admin', $data);
    }
    public function managequestion() {
        $_REQUEST = $_GET;
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $result = $this->adminmodel->get_entries_question($SurveyQuestionGroup_Id,'');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries_question($SurveyQuestionGroup_Id,1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/editquestion/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '/SurveyQuestion_Id/'.$appObject[$_i]['SurveyQuestion_Id'].'" title="">Edit</a>
            | <a href="' . base_url() . 'admin/deletequestion/SurveyQuestion_Id/' . $appObject[$_i]['SurveyQuestion_Id'] . '/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
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
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'managequestion';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question';
        $this->load->view('admin/template_admin', $data);
    }

    public function createquestion() {
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->insert_entry('SurveyQuestions', $this);
            redirect('/admin/managequestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id);
        }
        $questiongroup=array();
        $result = $this->adminmodel->get_entries_questiongroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }

        $data['questiongroup'] = $questiongroup;
        $data['body'] = 'createquestion';
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Create Question';
        $this->load->view('admin/template_admin', $data);
    }

    public function editquestion() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $OldSurveyQuestionGroup_Id=$this->input->post('OldSurveyQuestionGroup_Id');
            $SurveyQuestion_Id = $this->input->post('SurveyQuestion_Id');
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
           // echopre($_POST);
            $insert = $this->adminmodel->update_entry('SurveyQuestions', $this, 'SurveyQuestion_Id', $SurveyQuestion_Id);
            redirect('/admin/managequestion/SurveyQuestionGroup_Id/'.$OldSurveyQuestionGroup_Id);
        }
        $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $SurveyQuestion_Id = $this->uri->segment(6);
        $getUserRec = $this->adminmodel->getData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['body'] = 'editquestion';
        $data['data'] = $getUserRec[0];

        $questiongroup=array();
        $result = $this->adminmodel->get_entries_questiongroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }
        $data['questiongroup'] = $questiongroup;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Edit Question';
        $this->load->view('admin/template_admin', $data);
    }
    public function deletequestion() {
        $SurveyQuestion_Id = $this->uri->segment(4);
        $SurveyQuestionGroup_Id = $this->uri->segment(6);
        $uid = $this->adminmodel->delData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['message'] = 'Question deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/managequestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id);
    }
	
	/*Here Create manage question new*/
	public function manageequestion() {
        $_REQUEST = $_GET;
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        //$result = $this->adminmodel->get_entries_question($SurveyQuestionGroup_Id,'');
		$result = $this->adminmodel->get_entries_question('','');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        //$appObject = $this->adminmodel->get_entries_question($SurveyQuestionGroup_Id,1,$pagelimit, $paging);
		$appObject = $this->adminmodel->get_entries_question('',1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/editequestion/SurveyQuestion_Id/'.$appObject[$_i]['SurveyQuestion_Id'].'" title="">Edit</a>
            | <a href="' . base_url() . 'admin/deleteequestion/SurveyQuestion_Id/' . $appObject[$_i]['SurveyQuestion_Id'] . '/SurveyQuestionGroup_Id/' . $appObject[$_i]['SurveyQuestionGroup_Id'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
            unset($appObject[$_i]['SurveyQuestion_Id']);
            //unset($appObject[$_i]['QuestionText']);
            unset($appObject[$_i]['SurveyQuestionGroup_Id']);
        }
        $appObject[-1] = Array(
            "QuestionText" => "Question Text",
            "Title" => "Group Title",
            "Action" => "Action"
        );
        $scriptName = "/admin/manageequestion";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'manageequestion';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question';
        $this->load->view('admin/template_admin', $data);
    }
	public function createequestion() {
        $SurveyQuestionGroup_Id=$this->uri->segment(4);
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
			$this->SurveyId = $this->input->post('SurveyId');
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->insert_entry('SurveyQuestions', $this);
            redirect('/admin/manageequestion/');
        }
		
		$getSurveylist=$this->adminmodel->surveylist();
		
        $questiongroup=array();
        $result = $this->adminmodel->get_entries_questiongroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }

        $data['questiongroup'] = $questiongroup;
		$data['surveylist'] = $getSurveylist;
        $data['body'] = 'createequestion';
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Create Question';
        $this->load->view('admin/template_admin', $data);
    }
	public function editequestion() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $OldSurveyQuestionGroup_Id=$this->input->post('OldSurveyQuestionGroup_Id');
            $SurveyQuestion_Id = $this->input->post('SurveyQuestion_Id');
			$this->SurveyId = $this->input->post('SurveyId');
            $this->QuestionText = $this->input->post('QuestionText');
            $this->SurveyQuestionGroup_Id = $this->input->post('SurveyQuestionGroup_Id');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
           // echopre($_POST);
            $insert = $this->adminmodel->update_entry('SurveyQuestions', $this, 'SurveyQuestion_Id', $SurveyQuestion_Id);
            redirect('/admin/manageequestion/');
        }
       // $SurveyQuestionGroup_Id = $this->uri->segment(4);
        $SurveyQuestion_Id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['body'] = 'editequestion';
        $data['data'] = $getUserRec[0];

        $questiongroup=array();
        $result = $this->adminmodel->get_entries_questiongroup('');
        foreach($result as $group){
            $questiongroup[$group['SurveyQuestionGroup_Id']]=$group['Title'];
        }
        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach($defaultgroup as $group2){
            $questiongroup[$group2['SurveyQuestionGroup_Id']]=$group2['Title'];
        }
        $data['questiongroup'] = $questiongroup;
		$getSurveylist=$this->adminmodel->surveylist();
		$data['surveylist'] = $getSurveylist;
        //$data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['header_title'] = 'Edit Question';
        $this->load->view('admin/template_admin', $data);
    }
	public function deleteequestion() {
        $SurveyQuestion_Id = $this->uri->segment(4);
        $SurveyQuestionGroup_Id = $this->uri->segment(6);
        $uid = $this->adminmodel->delData('SurveyQuestions', 'SurveyQuestion_Id', $SurveyQuestion_Id);
        $data['message'] = 'Question deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/manageequestion');
    }
	public function getsurveygroup(){
		
		$surveyid=$_POST['surveyid'];
		$getSurveyData=$this->adminmodel->getData('SurveyQuestionGroups','Survey_Id',$surveyid);
		
		$option="<option value=''>Select Group</option>";
		foreach($getSurveyData as $grp){
			$option.="<option value='".$grp['SurveyQuestionGroup_Id']."'>".$grp['Title']."</option>";
		}
		$defaultgroup=$this->adminmodel->get_default_questiongroup();
        foreach($defaultgroup as $group2){
            $option.="<option value='".$group2['SurveyQuestionGroup_Id']."'>".$group2['Title']."</option>";
        }
		echo $option;
		die;
	}
	
	/*End Here*/
	
    public function managedefaultquestion() {
        $_REQUEST = $_GET;
        $defaultgroup=$this->adminmodel->get_default_questiongroup();
        $SurveyQuestionGroup_Id=$defaultgroup[0]['SurveyQuestionGroup_Id'];
        $result = $this->adminmodel->get_default_question($SurveyQuestionGroup_Id,'');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_default_question($SurveyQuestionGroup_Id,1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/editquestion/SurveyQuestionGroup_Id/'.$SurveyQuestionGroup_Id.'/SurveyQuestion_Id/'.$appObject[$_i]['SurveyQuestion_Id'].'" title="">Edit</a>
            | <a href="' . base_url() . 'admin/deletequestion/SurveyQuestion_Id/' . $appObject[$_i]['SurveyQuestion_Id'] . '/"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
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
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'managequestion';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['SurveyQuestionGroup_Id'] = $SurveyQuestionGroup_Id;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Question';
        $this->load->view('admin/template_admin', $data);
    }
    public function managetemplate() {
        $_REQUEST = $_GET;
        $result = $this->adminmodel->get_entries_template('');
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_entries_template(1,$pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/edittemplate/TemplateId/' . $appObject[$_i]['TemplateId'] . '" title="">Edit</a> 
             | <a href="' . base_url() . 'admin/deletetemplate/TemplateId/' . $appObject[$_i]['TemplateId'] . '"  onclick=\'return confirm("Are you sure to want delete this record?");\' title="">Delete</a>';
            unset($appObject[$_i]['TemplateId']);
        }
        $appObject[-1] = Array(
            "TemplateName" => "Template Name",
            "Action" => "Action"
        );
        $scriptName = "/admin/managetemplate";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);
        $data['body'] = 'managetemplate';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $data['header_title'] = 'Manage Template';
        $this->load->view('admin/template_admin', $data);
    }

    public function createtemplate() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $this->TemplateName = $this->input->post('TemplateName');
            $this->TemplateText = $this->input->post('TemplateText');
            $this->CreatedBy = $admin_id;
            $this->CreatedDate = date('Y-m-d H:i:s');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->insert_entry('TemplateMaster', $this);
            $data['message'] = 'Template created successfully.';
            $this->session->set_flashdata('message', $data);
            redirect('/admin/managetemplate/');
        }
        $data['body'] = 'createtemplate';
        $data['header_title'] = 'Create Template';
        $this->load->view('admin/template_admin', $data);
    }

    public function edittemplate() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $admin_id = $_SESSION['admin_id'];
            $TemplateId = $this->input->post('TemplateId');
            $this->TemplateName = $this->input->post('TemplateName');
            $this->TemplateText = $this->input->post('TemplateText');
            $this->UpdatedBy = $admin_id;
            $this->UpdatedDate = date('Y-m-d H:i:s');
            $insert = $this->adminmodel->update_entry('TemplateMaster', $this, 'TemplateId', $TemplateId);
            $data['message'] = 'Template updated successfully.';
            $this->session->set_flashdata('message', $data);
            redirect('/admin/managetemplate/');
        }
        $TemplateId = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('TemplateMaster', 'TemplateId', $TemplateId);
        $data['body'] = 'edittemplate';
        $data['data'] = $getUserRec[0];       
        $data['header_title'] = 'Edit Template';
        $this->load->view('admin/template_admin', $data);
    }
    public function deletetemplate() {
        $TemplateId = $this->uri->segment(4);
        $uid = $this->adminmodel->delData('TemplateMaster', 'TemplateId', $TemplateId);
        $data['message'] = 'Template deleted successfully.';
        $this->session->set_flashdata('message', $data);
        redirect('/admin/managetemplate');
    }

    public function managecompany() {
        $_REQUEST = $_GET;
        $result = $this->adminmodel->get_company();
        $resultSize = sizeof($result);
        $totalRows = $resultSize;
        $StartFrom = 0;
        $paging = 10;
        if (@$_GET['pageNumber'] != "") {
            $StartFrom = (($_GET['pageNumber'] * $paging) - $paging);
        }
        $TotalPages = $totalRows / $paging;
        if (($totalRows % $paging) > 0) {
            $TotalPages++;
        }
        $pagelimit = $StartFrom;
        $appObject = $this->adminmodel->get_company($pagelimit, $paging);
        $resultSetCount = "Showing " . count($appObject) . " rows of total $totalRows record(s)";
        $resultSize2 = count($appObject);
        for ($_i = 0; $_i < $resultSize2; ++$_i) {
            $appObject[$_i]['Action'] = '<a href="' . base_url() . 'admin/editcompany/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Edit</a> / <a href="' . base_url() . 'admin/deletecompany/company_id/' . $appObject[$_i]['company_id'] . '/' . $appObject[$_i]['company_name'] . '?lightbox[iframe]=true&lightbox[width]=560&lightbox[height]=280"  class="ui-lightbox" title="' . $appObject[$_i]['company_name'] . '">Delete</a> / <a href="' . base_url() . 'admin/managecuser/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Manage User</a> <br> <a href="' . base_url() . 'admin/viewdata/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">View Data</a> / <a href="' . base_url() . 'admin/peopleonthemove/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">People on the move</a>';
            $appObject[$_i]['primary_contact'] = $appObject[$_i]['primary_contact'] . '&nbsp;&nbsp;(' . $appObject[$_i]['primary_email'] . ')';
			$appObject[$_i]['Form'] = '<a href="' . base_url() . 'admin/formedit/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Valuemap Form</a> / <a href="' . base_url() . 'adminleadership/leadershipformedit/company_id/' . $appObject[$_i]['company_id'] . '" title="' . $appObject[$_i]['company_name'] . '"">Leadership Dashbord Form</a>';
			
            $appObject[$_i]['start_date'] = date('m-d-Y', strtotime($appObject[$_i]['start_date']));
            $appObject[$_i]['end_date'] = date('m-d-Y', strtotime($appObject[$_i]['end_date']));
            unset($appObject[$_i]['company_id']);
            unset($appObject[$_i]['industry']);
            unset($appObject[$_i]['primary_email']);
            unset($appObject[$_i]['contact_phone']);
            unset($appObject[$_i]['address1']);
            unset($appObject[$_i]['address2']);
            unset($appObject[$_i]['city']);
            unset($appObject[$_i]['state']);
            unset($appObject[$_i]['zip']);
            unset($appObject[$_i]['phone']);
            unset($appObject[$_i]['fax']);
            unset($appObject[$_i]['is_delete']);
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
        $scriptName = "'/admin/manageuser'";
        $htmlcreate = $this->basecontroller->createdHtmlList($appObject, 'table table-bordered table-hover table-striped table-fixed-header', 1, $scriptName);

        $data['body'] = 'managecompany';
        $data['data'] = $htmlcreate;
        $data['TotalPages'] = $TotalPages;
        $data['totalRows'] = $totalRows;
        $data['resultSetCount'] = $resultSetCount;
        $this->load->view('admin/template_admin', $data);
    }

    public function createcompany() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $username = $this->input->post('email');
            $companyname = $this->input->post('company_name');
            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('vpassword');
            $errorset = 0;
            if ($password != $confirmpassword) {
                $data['message'] = 'Password not matched';
                $this->session->set_flashdata('message', $data);
                $errorset = 1;
            }
            $checkusername = $this->adminmodel->checkcompanyuser($username);
            $checkcompany = $this->adminmodel->checkcompanyname($companyname);
            if (count($checkusername) == 0 && count($checkcompany) == 0 && $errorset == 0) {
                $this->company_name = $this->input->post('company_name');
                $this->address1 = $this->input->post('address1');
                $this->department = $this->input->post('department');
                $this->address2 = $this->input->post('address2');
                $this->industry = $this->input->post('industry');
                $this->city = $this->input->post('city');
                $this->primary_contact = $this->input->post('primary_contact');
                $this->state = $this->input->post('state');
                $this->primary_email = $this->input->post('primary_email');
                $this->zip = $this->input->post('zip');
                $this->contact_phone = $this->input->post('contact_phone');
                $this->phone = $this->input->post('phone');
                $stdate = explode("-", $this->input->post('fdate'));
                $this->start_date = $stdate[2] . '-' . $stdate[0] . '-' . $stdate[1];
                $this->fax = $this->input->post('fax');
                $tdate = explode("-", $this->input->post('tdate'));
                $this->end_date = $tdate[2] . '-' . $tdate[0] . '-' . $tdate[1];
                if ($_FILES['user_logo']['name'] != "") {
                    $filename = time() . str_replace(" ", "_", $_FILES['user_logo']['name']);
                    move_uploaded_file($_FILES['user_logo']['tmp_name'], "userlogo/" . $filename);
                    $this->user_logo = $filename;
                }

                $company_id = $this->adminmodel->insert_entry('company', $this);
                $parimary = array('fname' => $this->input->post('fname'), 'username' => $this->input->post('email'), 'email' => $this->input->post('email'), 'password' => $this->input->post('password'));
                $user_id = $this->adminmodel->insert_entry('user_master', $parimary);
                $parimaryrole = array('company_id' => $company_id, 'user_id' => $user_id, 'role_id' => 3);
                $this->adminmodel->insert_entry('company_primary_user', $parimaryrole);
                redirect('/admin/managecompany');
            } else {
                if (count($checkusername) > 0) {
                    $data['message'] = 'Email should be unique';
                }
                if (count($checkcompany) > 0) {
                    $data['message'] = 'Company with the same name already exist. Please enter a new company name';
                }
                $this->session->set_flashdata('message', $data);
            }
        }
        $statelist = $this->adminmodel->states();
        $data['statelist'] = $statelist;
        $data['body'] = 'createcompany';
        $this->load->view('admin/template_admin', $data);
    }

    public function editcompany() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $username = $this->input->post('email');
            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('vpassword');
            $errorset = 0;
            if ($password != $confirmpassword) {
                $data['message'] = 'Password not matched';
                $this->session->set_flashdata('message', $data);
                $errorset = 1;
            }
            //$checkusername=$this->adminmodel->checkcompanyuser($username);
            if ($errorset == 0) {
                $this->company_name = $this->input->post('company_name');
                $this->address1 = $this->input->post('address1');
                $this->department = $this->input->post('department');
                $this->address2 = $this->input->post('address2');
                $this->industry = $this->input->post('industry');
                $this->city = $this->input->post('city');
                $this->primary_contact = $this->input->post('primary_contact');
                $this->state = $this->input->post('state');
                $this->primary_email = $this->input->post('primary_email');
                $this->zip = $this->input->post('zip');
                $this->contact_phone = $this->input->post('contact_phone');
                $this->phone = $this->input->post('phone');
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
                $insert = $this->adminmodel->update_entry('company', $this, 'company_id', $company_id);
                $parimary = array('fname' => $this->input->post('fname'), 'username' => $this->input->post('email'), 'password' => $this->input->post('password'));
                $insert = $this->adminmodel->update_entry('user_master', $parimary, 'seq_id', $user_id);
                redirect('/admin/managecompany');
            } else {
                // $data['message'] = 'Email should be unique';
                $this->session->set_flashdata('message', $data);
            }
        }
        $company_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('company', 'company_id', $company_id);
        $statelist = $this->adminmodel->states();
        $data['statelist'] = $statelist;
        $data['body'] = 'editcompany';
        $data['data'] = $getUserRec[0];
        $getUserRelation = $this->adminmodel->getData('company_primary_user', 'company_id', $company_id);
        $user_id = $getUserRelation[0]['user_id'];
        $getUserRec = $this->adminmodel->getData('user_master', 'seq_id', $user_id);
        $data['data2'] = $getUserRec[0];
        $this->load->view('admin/template_admin', $data);
    }

    public function userlogo() {
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $user_id = $this->input->post('user_id');
            if ($_FILES['user_logo']['name'] != "") {
                $filename = time() . str_replace(" ", "_", $_FILES['user_logo']['name']);
                move_uploaded_file($_FILES['user_logo']['tmp_name'], "userlogo/" . $filename);
                $this->user_logo = $filename;
                $insert = $this->adminmodel->update_entry('user_master', $this, 'seq_id', $user_id);
                redirect('/admin/manageuser');
            } else {
                redirect('/admin/manageuser');
            }
        }
        $user_id = $this->uri->segment(4);
        $getUserRec = $this->adminmodel->getData('user_master', 'seq_id', $user_id);
        $data['body'] = 'admin/userlogo';
        $data['data'] = $getUserRec[0];
        $this->load->view('admin/template_admin', $data);
    }

    public function imagethumbnail() {
        $imageurl = $this->uri->segment(3);
        $data['imagepath'] = base_url() . 'userlogo/' . $imageurl;
        $this->load->view('admin/imagethumbnail', $data);
    }

    public function changepassword() {
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('update')) {
            $old_password= $this->input->post('old_password');
            $new_password= $this->input->post('new_password');
            $cnew_password= $this->input->post('cnew_password');
            $admin_id=$_SESSION['admin_id'];
            $chkpass=$this->adminmodel->checkpassword($admin_id,$old_password);
            if(count($chkpass)==0){
                $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissable">Old password not matched.</div>');
            }elseif($new_password!=$cnew_password){
                $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissable">New password and confirm password not matched.</div>');
            }else{
                if ($this->adminmodel->update_pasword($this->input->post('old_password'), $this->input->post('new_password'))) {
                    $this->session->set_flashdata("message", 'Password Changed.');
                    redirect('admin/changepassword');
                }else {
                    $data['message'] = 'Password not matched';
                    $this->session->set_flashdata("message", 'Password Not Changed.');
                }
            }
        }
        //$this->load->view('admin/header');
        $data['body']='changepassword';
		$data['header_title'] = 'Change Password';
        $this->load->view('admin/template_admin', $data);
        //$this->load->view('admin/footer');
    }
    public function managesurveyuser() {
        $data['error'] = false;
        $survey_Id = $this->uri->segment(4);
        $data['success'] = true;
        if ($this->input->post('submit')) {
            $survey_Id = $this->input->post('Survey_Id');

           // $this->adminmodel->delDataAll('usersurvey', 'SureveyId', $survey_Id); // first delete then insert data
            $admin_id = $_SESSION['admin_id'];
            
			$getSurvey=$this->adminmodel->getData('Survey', 'Survey_Id', $survey_Id);
			$LibraryName=$getSurvey[0]['Heading'];
			
			$getSurveyGroupId=$this->adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
			$SurveyGroupId=$getSurveyGroupId[0]['SurveyGroup_Id'];
			$getSurveyUSERID=$this->adminmodel->getData('SurveyGroupUsers', 'SurveyGroup_Id', $SurveyGroupId);
			$alred=array();
			foreach($getSurveyUSERID as $alreadyuser){
				$alred[]=$alreadyuser['UserId'];
			}	
			//$this->adminmodel->delDataAll('SurveyGroupUsers', 'SurveyGroup_Id', $SurveyGroupId);			
			$surveyuserlist = $this->input->post('surveyuserlist');
			$diffarray=array_diff($alred,$surveyuserlist);
			
            if(count($surveyuserlist)>0){
                foreach($surveyuserlist as $ulist){
                    $this->SureveyId = $survey_Id;
                    $this->UserId = $ulist;
                    $this->CreatedBy = $admin_id;
                    $this->CreatedDate = date('Y-m-d H:i:s');
                    $this->UpdatedBy = $admin_id;
                    $this->UpdatedDate = date('Y-m-d H:i:s');
					$checkUserSurvey=$this->adminmodel->getcommandata('usersurvey', array('UserId'=>$ulist,'SureveyId'=>$survey_Id));
					if(count($checkUserSurvey)==0){
						$insert = $this->adminmodel->insert_entry('usersurvey', $this);	
					}
                    
					
					/*Here Insert data in SurveyGroupUsers */
					$checkUserSurveyU=$this->adminmodel->getcommandata('SurveyGroupUsers', array('UserId'=>$ulist,'SurveyGroup_Id'=>$SurveyGroupId));
					if(count($checkUserSurveyU)==0){
						$getUserDetails=$this->adminmodel->getData('user_master', 'user_id', $ulist);
						$email=$getUserDetails[0]['email'];
						$username=$getUserDetails[0]['user_fname']." ".$getUserDetails[0]['user_lname'];
						
						$usergarray=array('UserId'=>$ulist, 'UserName'=>$username, 'Email'=>$email, 'LibraryName'=>$LibraryName, 'SurveyGroup_Id'=>$SurveyGroupId, 'CreatedBy'=>$admin_id, 'CreatedDate'=>date('Y-m-d H:i:s'), 'UpdatedBy'=>$admin_id, 'UpdatedDate'=>date('Y-m-d H:i:s'));
						$insertGropuser = $this->adminmodel->insert_entry('SurveyGroupUsers', $usergarray);
					}
					/*End Here*/					
                }
                /*Remove left user*/
                $this->adminmodel->deletemultipleuser($surveyuserlist);
				
				if(count($diffarray)>0){
					foreach($diffarray as $left){
						$deletearray=array('UserId'=>$left,'SurveyGroup_Id'=>$SurveyGroupId);
						$this->adminmodel->delDataAllCOM('SurveyGroupUsers',$deletearray);
						
						$deletearray=array('UserId'=>$left,'SureveyId'=>$survey_Id);
						$this->adminmodel->delDataAllCOM('usersurvey',$deletearray);
					}
				}
                /*End Here*/
                $data['message'] = 'Survey users added successfully.';
                $this->session->set_flashdata('message', $data);
                redirect('/admin/managesurveyuser/survey_id/'.$survey_Id);
            }
			
			
        }
        $getUserRec = $this->adminmodel->getData('usersurvey', 'SureveyId', $survey_Id);
        $selecteduser=array();
        foreach($getUserRec as $selulist){
            $selecteduser[]=$selulist['UserId'];
        }
        $data['body'] = 'managesurveyuser';
        $data['data'] = @$selecteduser;

        $userlist = $this->adminmodel->userlist();
        $data['header_title'] = 'Manage Users';
        $data['userlist'] = $userlist;
        $data['survey_id'] = $survey_Id;
        $this->load->view('admin/template_admin', $data);
    }
    public function managesurveyurl(){
        $survey_Id = $this->uri->segment(4);
        $roundid = @$this->uri->segment(6);
        if($roundid==""){
            $roundid=1;
        }

       // echopre($getUserRec);
        if ($this->input->post('submit')) {
            $survey_Id = $this->input->post('Survey_Id');
            $surveyuserlist = $this->input->post('surveyuserlist');
            //$survey_url = $this->input->post('survey_url');
            $roundid = $this->input->post('roundid');
            $admin_id = $_SESSION['admin_id'];
            $deletearray=array('SureveyId'=>$survey_Id,'Round'=>$roundid);
            $this->adminmodel->delDataAllCOM('usersurveyround', $deletearray);
			
			$getSurveyGroupId=$this->adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
			$SurveyGroupId=$getSurveyGroupId[0]['SurveyGroup_Id'];
			
            if(count($surveyuserlist)>0){
                foreach($surveyuserlist as $key=>$ulist){
                    $arraylist=array('SureveyId'=>$survey_Id,'Round'=>$roundid,'UserId'=>$ulist);
                    $checkuserRoundassign=$this->adminmodel->checkround('usersurveyround',$arraylist);
                    //if(count($checkuserRoundassign)==0){
                        $this->SureveyId = $survey_Id;
                        $this->UserId = $ulist;
                        $this->Round = $roundid;
                        $getdynamicURLnumber=$this->adminmodel->createurlnumber();
						//$surveyURL=BASE_URL_SURVEY."/survey/".$getdynamicURLnumber;
						$surveyURL=$getdynamicURLnumber;
                        $this->RoundURL = $surveyURL;
                        $this->CreatedBy = $admin_id;
                        $this->CreatedDate = date('Y-m-d H:i:s');
                        $this->UpdatedBy = $admin_id;
                        $this->UpdatedDate = date('Y-m-d H:i:s');
                        $insert = $this->adminmodel->insert_entry('usersurveyround', $this);
                    //}
					
					/*Here insert into SurveyIteration */
					$getSurveyGroupUser_Id=$this->adminmodel->getcommandata('SurveyGroupUsers', array('SurveyGroup_Id'=>$SurveyGroupId,'UserId'=>$ulist));
					$SurveyGroupUser_Id=$getSurveyGroupUser_Id[0]['SurveyGroupUser_Id'];
					$checkexitassignURL=$this->adminmodel->getcommandata('SurveyIteration', array('SurveyGroupUser_Id'=>$SurveyGroupUser_Id,'Iteration'=>$roundid));
					if(count($checkexitassignURL)==0){
						$iterationarray=array("SurveyGroupUser_Id"=>$SurveyGroupUser_Id,'Token'=>$getdynamicURLnumber,'Iteration'=>$roundid,'NumberOfBoxes'=>7, 'NumberofQuestions'=>26, 'SurveyURL'=>$surveyURL);
						$insert = $this->adminmodel->insert_entry('SurveyIteration', $iterationarray);						
					}else{
						$iterationarray=array('Token'=>$getdynamicURLnumber,'SurveyURL'=>$surveyURL);
						$wherearray=array('SurveyGroupUser_Id'=>$SurveyGroupUser_Id,'Iteration'=>$roundid);
						$update = $this->adminmodel->updateentry('SurveyIteration', $iterationarray,$wherearray);					
					}
					
					/*End Here*/
					
                }
                $data['message'] = 'Survey users url updated successfully.';
                $this->session->set_flashdata('message', $data);
                redirect('/admin/managesurveyurl/survey_id/'.$survey_Id."/rount/".$roundid);
            }
        }
        if ($this->input->post('sendurlmail')) {
            $survey_Id = $this->input->post('Survey_Id');
            $surveyuserlist = $this->input->post('surveyuserlist');
            //$survey_url = $this->input->post('survey_url');
            $roundid = $this->input->post('roundid');
            $admin_id = $_SESSION['admin_id'];
            $deletearray=array('SureveyId'=>$survey_Id,'Round'=>$roundid);
            //$this->adminmodel->delDataAllCOM('usersurveyround', $deletearray);
            if(count($surveyuserlist)>0){
                foreach($surveyuserlist as $key=>$ulist){
                    $arraylist=array('SureveyId'=>$survey_Id,'Round'=>$roundid,'UserId'=>$ulist);
                    $checkuserRoundassign=$this->adminmodel->checkround('usersurveyround',$arraylist);
                    $url=$checkuserRoundassign[0]['RoundURL'];
                    $url='<a href="'.$url.'" target="_blank">'.$url.'</a>';

                    $userdata=$this->adminmodel->getData('user_master','user_id',$ulist);
                    $fname=$userdata[0]['user_fname'];
                    $lname=$userdata[0]['user_lname'];
                    $usermail=$userdata[0]['email'];

                    $templatedata=$this->adminmodel->getcommandata('TemplateMaster',array('TemplateName'=>'Email','CreatedBy'=>$admin_id));
                    $bodyContent=$templatedata[0]['TemplateText'];

                    $bodyContent=str_replace("##FNAME##",$fname,$bodyContent);
                    $bodyContent=str_replace("##LNAME##",$lname,$bodyContent);
                    $bodyContent=str_replace("##EMAIL##",$usermail,$bodyContent);
                    $bodyContent=str_replace("##URL##",$url,$bodyContent);

                    $to_email = $usermail;
                    $this->adminmodel->sendmail($to_email,$bodyContent);
                }
                $data['message'] = 'Survey users url email send successfully.';
                $this->session->set_flashdata('message', $data);
                redirect('/admin/managesurveyurl/survey_id/'.$survey_Id."/rount/".$roundid);
            }
        }



        $getUserRec = $this->adminmodel->getDataUserassignsurvey($survey_Id);

        //$getUserRecSel = $this->adminmodel->getDataUserassignsurveyURL($survey_Id,$roundid);
		$getSurveyGroupId=$this->adminmodel->getData('SurveyGroups', 'Survey_Id', $survey_Id);
		$SurveyGroupId=$getSurveyGroupId[0]['SurveyGroup_Id'];
		
		$getUserRecSel = $this->adminmodel->getDataUserassignsurveyURL($SurveyGroupId,$roundid);
			
        $selecteduser=array();
        foreach($getUserRecSel as $selulist){
            $selecteduser[$selulist['UserId']]=$selulist['SurveyURL'];
        }

        $data['selecteduser'] = @$selecteduser;
        $getSurveyname=$this->adminmodel->getData("Survey",'Survey_Id',$survey_Id);
        $data['body'] = 'managesurveyurl';
        $data['data'] = @$getUserRec;
        $data['header_title'] = 'Manage Users';
        $data['roundid'] = $roundid;
        $data['survey_id'] = $survey_Id;
        $data['survey_name'] = $getSurveyname[0]['Heading'];
        $this->load->view('admin/template_admin', $data);
    }

}