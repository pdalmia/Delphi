<?php
ini_set("error_reporting","E_ALL ^ E_DEPRECATED"); 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Registration extends CI_Controller {
    public function __construct() 	{
        parent::__construct();
        $this->load->model('Usermodel');
    }
    public function index() 	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 		{
            $email = $this->input->post('Email');
            $checkusername = $this->Usermodel->checkAdminUserName($email);
            if (count($checkusername) == 0) 			{
                $this->FirstName = $this->input->post('FirstName');
                $fname = $this->input->post('FirstName');
                $this->LastName = $this->input->post('LastName');
                $this->Company = $this->input->post('Company');
                $this->Address1 = $this->input->post('Address1');
                $this->Address2 = $this->input->post('Address2');
                $this->ZipCode = $this->input->post('ZipCode');
                $this->Email = $this->input->post('Email');
                $createpassword =  ucfirst(substr($fname, 0, 2));                
                $createpassword = $createpassword."@*".rand(1000,9999);
                $this->Password = $createpassword;
                $this->Phone = $this->input->post('Phone');
                $this->	KeyVerification = md5($email);
                $this->CreatedDate = date('Y-m-d H:i:s');
                $this->UpdatedDate = date('Y-m-d H:i:s');
                $insert = $this->Usermodel->insertEntry('AdminMaster', $this);
                /*Send A mail to admin*/
                $message='Dear Admin,<br><br>';
                $message.="A new admin user ".$fname." is registered in system please verify him/her.";
                $message.="<br><br> Thanks <br> Team Global Library";
                $from_email = "admin@ondai.com";
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
                $mail->Subject = 'New admin user created';
                $mail->Body    = $bodyContent;
                $mail->send();
                redirect('/Registration/thankyou');
            } 			else 			{
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'CreateUser';
        $data['header_title'] = 'Registration';
        $this->load->view('user/TemplateUser', $data);
    }
    public function thankyou() 	{
        $data['body'] = 'Thankyou';
        $data['header_title'] = 'Thankyou';
        $this->load->view('user/TemplateUser', $data);
    }
    public function verifyUser()	{
        $userkey = $this->uri->segment(4);
        if($userkey!=""){
            $checkverification=$this->Usermodel->checkverificationkey($userkey);
            if(count($checkverification)==0){
                $data['message'] = 'You have wrong authentication.';
                $this->session->set_flashdata('message', $data);
            }			else			{
                $is_verified=$checkverification[0]['IsVerify'];
                $adminid=$checkverification[0]['Admin_Id'];
                if($is_verified==1){
                    $data['message'] = 'You have already verified your account.';
                    $this->session->set_flashdata('message', $data);
                }				else				{
                    $dataset=array('IsVerify'=>1,'	UpdatedDate'=>date('Y-m-d H:i:s'));
                    $update=$this->Usermodel->updateEntry('AdminMaster',$dataset,'Admin_Id',$adminid);
                    $password = $checkverification[0]['Password'];
                    $data['message'] = '<p>Your account has been verified successfully.</p><br>
                                    <p>Your email would be your username and password is '.$password.', you can change your password after login.</p><br>
                                    <p>Please click <a href="'.base_url().'Superadmin/index">here</a> to Login</p>';
                    $this->session->set_flashdata('message', $data);
                    /*Send A new mail when a verify user*/                    
                    $message='Dear '.$checkverification[0]['FirstName'].',<br><br>';
                    $message.="Your system generated password for Global Library account is below:<br>".$checkverification[0]['Password']."";					
                    $message.="<br><br> Thanks <br> Team Global Library";
                    $from_email = "admin@ondai.com";
                    $to_email = $checkverification[0]['Email'];
                    //Load email library
                    /*$this->load->library('email');
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
                    $mail->Subject = 'Global Library - Password';
                    $mail->Body    = $bodyContent;
                    $mail->send();
                    /*End Here*/
                }
            }
            redirect('/Registration/verifyUser');
        }
        $data['body'] = 'Verified';
        $data['header_title'] = 'Account Verification';
        $this->load->view('user/TemplateUser', $data);
    }
    public function forgotPassword() 	{
        if ($this->input->post()) 		{
            $this->form_validation->set_rules('userName', 'User Name', 'required');
            if ($this->form_validation->run() == true) 			{
                $res = $this->adminmodel->checkUserName($this->input->post('userName'));
                if (count($res) > 0) 				{
                    $adminemail = $res[0]['Email'];
                    $to = $adminemail;
                    $subject = "Forgot Password";
                    $txt = "Your password :" . $res[0]['Email'];
                    $headers = "From: noreplay@prismatics.com" . "\r\n";
                    mail($to, $subject, $txt, $headers);
                    echo 'success';
                } 				else 				{
                    echo 'unsuccess';
                }
            }
            die;
        }
        $data['header_title'] = 'Forgot Password';
        $this->load->view('ForgotPassword');
    }

} 