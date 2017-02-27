<?php
class Superadmin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Adminmodel');
    }
    public function index() 	{
        if ($this->input->post()) 		{
            $this->form_validation->set_rules('userName', 'User Name', 'required');
            $this->form_validation->set_rules('Password', 'Password', 'required');			
            if ($this->form_validation->run() == true) 			{                $res = $this->Adminmodel->adminLogin($this->input->post('userName', TRUE), $this->input->post('Password'));
                if ($res) 				{
                    if($_SESSION['IsSuperAdmin']==1)					{
                        redirect('admin/ManageAdmin');
                    }					else					{
                        redirect('admin/ManageUser');
                    }
                } 				else 				{
                    $data['message'] = 'Invalid username or password';
                    $this->session->set_flashdata('message', $data);
                    //redirect('superAdmin/');
                }
            }			else			{                $data['message'] = 'Invalid username or password';
                $this->session->set_flashdata('message', $data);
            }
        }
        $data['body'] = 'Login';
        $data['header_title'] = 'Login';
        $this->load->view('admin/TemplateLogin', $data);
    }
    public function ForgotPassword() 	{
        if ($this->input->post()) 		{
            $this->form_validation->set_rules('userName', 'User Name', 'required');
            if ($this->form_validation->run() == true) 			{
                $res = $this->Adminmodel->checkForgotUserName($this->input->post('userName'));
                if(count($res)>0){
                    /*
					$adminemail= $res[0]['email'];
                    $to = $adminemail;
                    $subject = "Forgot Password";
                    $txt = "Hi ".$res[0]['admin_fname'].",\r\n\n Your password is :".$res[0]['password'];
                    $headers = "From: noreplay@prismatics.com" . "\r\n";
                    mail($to,$subject,$txt,$headers);
					/**/
					$message='Dear '.$res[0]['FirstName'].',<br><br>';
					$message.="Your password is ".$res[0]['Password']."";					
					$message.="<br><br> Thanks <br> Team Global Library";
					$from_email = "admin@ondai.com";
					$to_email = $res[0]['Email'];
					//Load email library
					$this->load->library('email');
					// prepare email
					$this->email
						->from('admin@ondai.com', 'Global Library')
						->to($res[0]['Email'])
						->subject('Global Library - Account Verification')
						->message($message)
						->set_mailtype('html');		
					if($this->email->send())
						$this->session->set_flashdata("message","Email sent successfully.");
					else
						$this->session->set_flashdata("message","Error in sending Email.");
                    //$this->session->set_flashdata("message",'Mail sent successfully');
                } 				else 				{
                    $this->session->set_flashdata("message",'Mail not sent. Please try again.');
                }
                redirect('Superadmin/ForgotPassword');
            }
        }
        //$this->load->view('admin/forgotPassword');
        $data['body'] = 'ForgotPassword';
        $data['header_title'] = 'Forgot Password';
        $this->load->view('admin/TemplateLogin', $data);
    }
}