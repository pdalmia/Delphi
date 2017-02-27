<?php
ini_set("error_reporting","E_ALL ^ E_DEPRECATED"); 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Usermode extends CI_Controller {
    public function __construct() 	{
        parent::__construct();
        $this->load->model('Usermodel');
    }
    public function index() 	{
        $this->load->library('form_validation');
        $data['error'] = false;
        $data['success'] = true;
        if ($this->input->post('submit')) 		
		{
            $email = $this->input->post('email');
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
                $createpassword = $createpassword."@*".  rand();
                $this->Password = $createpassword;
                $this->Phone = $this->input->post('Phone');
                $this->	KeyVerification = md5($email);
                $this->CreatedDate = date('Y-m-d H:i:s');
                $this->UpdatedDate = date('Y-m-d H:i:s');
                $insert = $this->Usermodel->insertEntry('AdminMaster', $this);
                redirect('/Usermode/Thankyou');
            } 			else 			{
                $data['message'] = 'User with the provided email address already exist. Please enter a new email address.';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'CreateUser';
        $data['header_title'] = 'Registration';
        $this->load->view('user/TemplateUser', $data);
    }
    public function Thankyou() 	{
        $data['body'] = 'Thankyou';
        $data['header_title'] = 'Thankyou';
        $this->load->view('user/TemplateUser', $data);
    }
    public function VerifiedUser()	{
        $userkey = $this->uri->segment(4);
        $checkverification=$this->Usermodel->checkVerificationKey($userkey);
        if(count($checkverification)==0)		{
            $data['message'] = 'You have wrong authentication.';
            $this->session->set_flashdata('message', $data);
        }		else		{
            $is_verified=$checkverification[0]['IsVerify'];
            $adminid=$checkverification[0]['Admin_Id'];
            if($is_verified==1){
                $data['message'] = 'Already verified.';
                $this->session->set_flashdata('message', $data);
            }			else			{
                $dataset=array('IsVerify'=>1,'	UpdatedDate'=>date('Y-m-d H:i:s'));
                $update=$this->Usermodel->updateEntry('AdminMaster',$dataset,'Admin_Id',$adminid);
                $password = $checkverification[0]['Password'];
                $data['message'] = 'You have verified successfully.<p>Your email would be your username and password is '.$password.', '
                        . 'you can change your password after login.</p></p><p>Please click <a href="'.base_url().'Superadmin/">Login</a>';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'Verified';
        $data['header_title'] = 'Verification';
        $this->load->view('user/TemplateUser', $data);
    }
    public function ForgotPassword() 	{
        if ($this->input->post()) {
            $this->form_validation->set_rules('userName', 'User Name', 'required');
            if ($this->form_validation->run() == true) {
                $res = $this->Adminmodel->checkUserName($this->input->post('username'));
                if (count($res) > 0) {
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
        $this->load->view('Forgotpassword');
    }

} 