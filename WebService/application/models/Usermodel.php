<?php

class Usermodel extends CI_Model {
    function __construct() 	{
        parent::__construct();
        $this->load->helper('cache');
    }
    function checkAdminUsername($userName) 	{
        $query=$this->db->get_where('AdminMaster',array('Email='=>$userName,'IsDelete='=>0));
        return $query->result_array();
    }
    function insertEntry($table,$data)	{
        $this->db->insert($table,$data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    function checkVerificationKey($key) 	{
        $query=$this->db->get_where('AdminMaster',array('KeyVerification='=>$key));
        return $query->result_array();
    }
    function updateEntry($table,$data,$filed,$fieldvalue)	{
        $this->db->update($table,$data,array($filed=>$fieldvalue));
    }
}

?>