<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Upload_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {

    }

    public function do_upload()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xls|csv|txt';
        // $config['max_size'] = 100;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo $error['error'];

        } else {
            $data = array('upload_data' => $this->upload->data());
            echo "file Uploaded succeefully";
        }
    }

}