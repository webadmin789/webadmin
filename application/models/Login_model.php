<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        //Do your magic here
    }

    public function fetchUserDetails($userName, $Password)
    {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_name', $userName);
        $this->db->where('password', $Password);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function setAccessTime($last_access_time)
    {
        $this->db->set('last_access_date', $last_access_time['last_access_time']);
        $this->db->where('user_id', $last_access_time['user_id']);
        $this->db->update('users');

    }

}

/* End of file ModelName.php */