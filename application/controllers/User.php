<?php

class User extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->helper('url');
    }
    public function index() {
        echo "USER API";
    }
    public function get_user() {
        $username = $this->session->user;
        $user = $this->user_model->get_user($username);
        $user = json_encode($user);
        echo($user);
    }

    public function login($username) {
        $this->session->set_userdata('user',$username);
    }
}