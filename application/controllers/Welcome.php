<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Login_model');
        $this->load->model('Excelmodel');
    }

    public function index()
    {
        if (isset($this->session->userdata['logged_in'])) {
            $data['party'] = $this->Excelmodel->party_list();
            $this->load->view('index', $data);
        } else {
            $this->load->view('login');
        }

    }

    public function login()
    {
        $this->load->view('login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('welcome/login'));

    }

    public function postlogin()
    {
        if ($this->input->post('submit')) {
            $username = $this->input->post('username');
            $password = sha1($this->input->post('password'));
            $result = $this->Login_model->fetchUserDetails($username, $password);
            $data['party'] = $this->Excelmodel->party_list();
            // echo'<pre>';
            // print_r($data['party']);
            // echo'</pre>';
            if ($result) {
                echo "login successful";
                $this->session->set_flashdata('success', 'Action Completed');

                //creating session for user
                $data['userdata'] = array(
                    'user_id' => $result[0]['user_id'],
                    'username' => $username,
                    'logged_in' => true,
                    'last_access_date' => mdate('%Y-%m-%d %H:%i:%s', now()),
                );
                $this->session->set_userdata($data['userdata']);

                //updating last access time of user to users table
                $last_access_time = array('last_access_time' => $this->session->userdata('last_access_date'), 'user_id' => $result[0]['user_id']);
                $this->Login_model->setAccessTime($last_access_time);
                // redirect(base_url('welcome/index'), $data);
                $this->load->view('index', $data);

            } else {

                echo "login unsccessful";
                $this->session->set_flashdata('failed', 'Athentication Failed !! please try again');
                redirect(base_url('welcome/login'));

            }
        }

    }

}