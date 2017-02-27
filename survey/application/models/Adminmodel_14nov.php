<?php

class Adminmodel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('cache');
    }
    function adminlogin($username = '', $password = '') {
        $sql = "SELECT * FROM admin_master WHERE email = '" . $username . "' AND  `password` = '" . $password . "' AND  `is_delete` = '0' AND `is_verify`=1";
        $row = $this->db->query($sql)->row_array();
        if (count($row) > 0) {
            $this->session->set_userdata($row);
            return true;
        } else
            return false;
    }

    function get_adminlist($flag,$pagelimit=0,$paging=10){
        $this->db->select('a.admin_id,admin_fname,admin_lname,company,email,phone,is_verify');
        $this->db->from("admin_master a");
        $this->db->where("a.is_delete","0");
        //$this->db->where("a.is_verify","1");
        $this->db->where("a.is_superadmin","0");
        $this->db->order_by('a.admin_id', 'desc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();

        //$query=$this->db->get_where('user_access_audit',array('access_id!='=>0),$paging,$pagelimit);
        //return $query->result_array();
    }
    function get_entries($flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('a.user_id,user_fname,user_lname,company,email,phone');
        $this->db->from("user_master a");
        $this->db->where("a.is_delete","0");
		$this->db->where("a.admin_id",$admin_id);
        $this->db->order_by('a.user_id', 'asc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_entries_survey($flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('a.Survey_Id,a.Heading,a.Subheading,a.ExplanatoryNote,a.WelcomeParagraph,a.Subtitle,a.SummaryNote');
        $this->db->from("Survey a");
		$this->db->where("a.CreatedBy",$admin_id);
        $this->db->where("a.is_delete","0");
        $this->db->order_by('a.Survey_Id', 'desc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_entries_propositions($flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('b.Heading,a.SurveyProposition_Id,a.likehood_LeftText,a.likehood_RightText,a.desirable_LeftText,a.desirable_RightText,a.Survey_Id');
        $this->db->from("SurveyPropositions a");
        $this->db->join('Survey b','a.Survey_Id=b.Survey_Id');
		$this->db->where("a.CreatedBy",$admin_id);
        //$this->db->where("a.is_delete","0");
        $this->db->order_by('a.SurveyProposition_Id', 'desc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_entries_questiongroup($flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('a.SurveyQuestionGroup_Id,a.Title,a.Survey_Id,b.Heading');
        $this->db->from("SurveyQuestionGroups a");
        $this->db->join('Survey b','a.Survey_Id=b.Survey_Id');
		$this->db->where("a.CreatedBy",$admin_id);
        //$this->db->where("a.is_delete","0");
        $this->db->order_by('a.SurveyQuestionGroup_Id', 'desc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_default_questiongroup(){
        $this->db->select('a.SurveyQuestionGroup_Id,a.Title,a.Survey_Id');
        $this->db->from("SurveyQuestionGroups a");
        $this->db->where("a.Survey_Id",'11001100');
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_entries_question($SurveyQuestionGroup_Id,$flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('a.SurveyQuestion_Id,a.QuestionText,a.SurveyQuestionGroup_Id,b.Title');
        $this->db->from("SurveyQuestions a");
        $this->db->join('SurveyQuestionGroups b','a.SurveyQuestionGroup_Id=b.SurveyQuestionGroup_Id');
        $this->db->where("a.SurveyQuestionGroup_Id",$SurveyQuestionGroup_Id);
        $this->db->where("a.is_delete",0);
		$this->db->where("a.CreatedBy",$admin_id);
        $this->db->order_by('a.SurveyQuestion_Id', 'asc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_default_question($SurveyQuestionGroup_Id,$flag,$pagelimit=0,$paging=10){
        $admin_id = $_SESSION['admin_id'];
        $this->db->select('a.SurveyQuestion_Id,a.QuestionText,a.SurveyQuestionGroup_Id');
        $this->db->from("SurveyQuestions a");
        $this->db->where("a.SurveyQuestionGroup_Id",$SurveyQuestionGroup_Id);
        $this->db->where("a.is_delete",0);
        $this->db->where("a.CreatedBy",$admin_id);
        $this->db->order_by('a.SurveyQuestion_Id', 'asc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    function get_entries_template($flag,$pagelimit=0,$paging=10){
		$admin_id = $_SESSION['admin_id'];
        $this->db->select('a.TemplateId,a.TemplateName');
        $this->db->from("TemplateMaster a");
        //$this->db->join('SurveyQuestionGroups b','a.SurveyQuestionGroup_Id=b.SurveyQuestionGroup_Id');
        $this->db->where("a.is_delete",0);
		$this->db->where("a.CreatedBy",$admin_id);
        $this->db->order_by('a.TemplateId', 'asc');
        if($flag==1) {
            $this->db->limit($paging, $pagelimit);
        }
        // echo $this->db->get_compiled_select(); die;
        $query=$this->db->get();
        return $query->result_array();
    }
    
    function get_company($pagelimit=0,$paging=10){
        $query=$this->db->get_where('company',array('company_id!='=>0,'is_delete'=>1),$paging,$pagelimit);
        return $query->result_array(); 
    }
    function get_company_form($pagelimit=0,$paging=10){
	    $this->db->select('a.*,b.company_name');
		$this->db->from('formtype a');
		$this->db->join('company b','a.company_id=b.company_id','inner');
		$this->db->where('a.Name', 'Customer');
		/*$this->db->select('a.*,b.company_name');
        $this->db->from('form a');
        $this->db->join('company b','a.client_id=b.company_id','left');
		$this->db->where('a.parent_form', 0);/**/
        $this->db->limit($paging,$pagelimit);
        $query=$this->db->get();
        return $query->result_array(); 
    }
    function get_company_formdate($companyid){
		$query=$this->db->get_where('formtype',array('company_id='=>$companyid,"Name="=>'Customer'));
        $getformid=$query->result_array(); 
		$formtypeid = @$getformid[0]['FormTypeId'];
	
        $this->db->select('a.*,b.*,c.ctrl1,c.ctrl2,c.ctrl3');
        $this->db->from('form a');
        $this->db->join('formcontrol b','a.form_id=b.form_id');
        $this->db->join('control c','b.ctr_id=c.ctrl_id');
        $this->db->where('a.client_id', $companyid);
		$this->db->where('b.FormTypeId', $formtypeid);
		$this->db->where('b.is_delete', 1);
        $this->db->order_by('b.rankid', 'asc');
        $query=$this->db->get();
        return $query->result_array(); 
    }
    
	function get_formdatatype($companyid,$name){
        $query=$this->db->get_where('formtype',array('company_id='=>$companyid,'Name='=>$name));
        return $query->result_array();
    }
    function get_contactform_form($companyid){
        $query=$this->db->get_where('formtype',array('company_id='=>$companyid,'Name='=>'Contact'));
        return $query->result_array();
    }
	function get_customer_form($companyid){
        $query=$this->db->get_where('formtype',array('company_id='=>$companyid,'Name='=>'Customer'));
		
        return $query->result_array();
    }
    function getDataContact($companyid,$formid){
        $query=$this->db->get_where('form',array('client_id='=>$companyid,'FormTypeId='=>$formid));
        return $query->result_array();
    }
    function get_contact_formdate($companyid){
		$query=$this->db->get_where('formtype',array('company_id='=>$companyid,"Name="=>'Contact'));
        $getformid=$query->result_array(); 
		$formtypeid = @$getformid[0]['FormTypeId'];
		
		$this->db->select('a.*,b.*,c.ctrl1,c.ctrl2,c.ctrl3');
        $this->db->from('form a');
        $this->db->join('formcontrol b','a.form_id=b.form_id');
        $this->db->join('control c','b.ctr_id=c.ctrl_id');
        $this->db->where('a.client_id', $companyid);
		$this->db->where('b.FormTypeId', $formtypeid);
		$this->db->where('b.is_delete', 1);
        $this->db->order_by('b.rankid', 'asc');
		
        $query=$this->db->get();
        return $query->result_array();
	}
	function getformtypeid($company_id,$name){
		$query=$this->db->get_where('formtype',array('company_id='=>$company_id,"Name="=>$name));
        $getformid=$query->result_array(); 
		$formtypeid = @$getformid[0]['FormTypeId'];
		return $formtypeid;
	}
    function checkmaxrankid($formid) {       
        $this->db->select('max(`rankid`) as maxrank');
        $this->db->from('formcontrol');
        $this->db->where('form_id', $formid);
        $query=$this->db->get();
        return $query->result_array(); 
    }
	function checkmaxrankidcontact($formid,$formtypeid) {       
        $this->db->select('max(`rankid`) as maxrank');
        $this->db->from('formcontrol');
        $this->db->where('form_id', $formid);
		$this->db->where('FormTypeId', $formtypeid);
        $query=$this->db->get();
        return $query->result_array(); 
    }
    function get_control(){
        $query=$this->db->get_where('control',array('ctrl_id!='=>0));
        return $query->result_array(); 
    }
    function getcontrolname($ctrlid){
        $query=$this->db->get_where('control',array('ctrl_id='=>$ctrlid));
        return $query->result_array(); 
    }
    function checkadminusername($username) {
        $query=$this->db->get_where('admin_master',array('email='=>$username,'is_delete='=>0));
        return $query->result_array(); 
    }
    function checkusername($admin_id,$username) {
        $query=$this->db->get_where('user_master',array('email='=>$username,'is_delete='=>0,'admin_id='=>$admin_id));
        return $query->result_array();
    }
    function checkforgotusername($username) {
        $query=$this->db->get_where('admin_master',array('email='=>$username,'is_delete='=>0));
        return $query->result_array();
    }
    function checkpassword($admin_id,$password) {
        $query=$this->db->get_where('admin_master',array('password='=>$password,'admin_id='=>$admin_id));
        return $query->result_array();
    }
    function checkcompanyuser($email) {
        $query=$this->db->get_where('user_master',array('username='=>$email));
        return $query->result_array(); 
    }
    function checkcompanyname($company_name) {
        $query=$this->db->get_where('company',array('company_name='=>$company_name));
        return $query->result_array(); 
    }
    
    function insert_entry($table,$data){
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    function getData($table,$field,$fieldvalue){
        $query=  $this->db->get_where($table,array($field=>$fieldvalue));
        return $query->result_array();
    }
    function update_entry($table,$data,$filed,$fieldvalue){
        $this->db->update($table,$data,array($filed=>$fieldvalue));
    }
    function delData($table,$filed,$fieldvalue){        
        $this->db->update($table,array("is_delete"=>1),array($filed=>$fieldvalue));
        //$this->db->delete($table,array($filed=>$fieldvalue));
    }
    function delDataAll($table,$filed,$fieldvalue){
       // $this->db->update($table,array("is_delete"=>1),array($filed=>$fieldvalue));
        $this->db->delete($table,array($filed=>$fieldvalue));
    }
    
    function update_pasword($old_pass, $new_pass) {
        $sql = "UPDATE admin_master SET password = '" . $new_pass . "' WHERE admin_id = '" . $this->session->userdata['admin_id'] . "'";
        return $this->db->query($sql);

    }
    function surveylist(){
        $admin_id = $_SESSION['admin_id'];
        $query=$this->db->get_where('Survey',array('Survey_Id!='=>0,'CreatedBy'=>$admin_id));
        $result=$query->result_array(); 
        return $result; 
    }
    function userlist(){
        $admin_id = $_SESSION['admin_id'];
        $query=$this->db->get_where('user_master',array('user_id!='=>0,'admin_id'=>$admin_id,'is_delete'=>0));
        //$query=$this->db->get_where('user_master',array('user_id!='=>0));
        $result=$query->result_array();
        return $result;
    }
    function getDataUserassignsurvey($Survey_Id){
        $this->db->select('a.*,b.user_fname,b.user_lname');
        $this->db->from('usersurvey a');
        $this->db->join('user_master b','a.UserId=b.user_id');
        $this->db->where('a.SureveyId', $Survey_Id);
        $this->db->where('a.is_delete', 0);
        //$this->db->order_by('b.rankid', 'asc');

        $query=$this->db->get();
        return $query->result_array();
    }
    function getDataUserassignsurveyURL($Survey_Id,$roundid){
        $query=$this->db->get_where('usersurveyround',array('SureveyId'=>$Survey_Id,'Round'=>$roundid));
        $result=$query->result_array();
        return $result;
    }
    function delDataAllCOM($table,$deletearray){
        $this->db->delete($table,$deletearray);
    }
    function createurlnumber(){
        $number=$this->genratenumber();
        $checknumber=$this->db->get_where('usersurveyround',array('RoundURL like '=>$number));
        $result=$checknumber->result_array();
        $count=count($result);
        if($count==0){
            return $number;
        }else{
            $this->createurlnumber();
        }
    }
    function genratenumber(){
        return strtoupper(uniqid());
    }
    function deletemultipleuser($userarray)
    {
        $impldestr=implode(",",$userarray);
        $sql = "DELETE FROM usersurveyround where UserId not in (".$impldestr.")";
        $this->db->query($sql);
        return true;
    }
    function checkround($table,$arraylist){
        $query=  $this->db->get_where($table,$arraylist);
        return $query->result_array();
    }
    function sendmail($to_email,$bodyContent){
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
        $mail->Subject = 'Survey URL';
        $mail->Body    = $bodyContent;
        $mail->send();
    }

}?>