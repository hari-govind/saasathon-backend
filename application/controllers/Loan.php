<?php
class Loan extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('transaction_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index(){
        echo "LOAN API<br>";
        echo "/request_loan/amount/repay_date<br>";
        echo "/accept_request/req_num<br>";
        echo "/repay_loan";

    }

    public function request_loan($amount,$repay_date) {
        $user = $this->session->user;
        if($user === null) {
            die('Not logged in. Error.');
        }
        if($this->transaction_model->loan_exists($user)) {
            die('There is a current active loan in your account.');
        }
        $amount = (int)$amount;
        $company_amount = 0.048*$amount;
        $creditor_amount = 0.05*$amount;
        $repay = $amount + 0.098*$amount;
        $this->transaction_model->request_credit($user,$amount,$repay_date,$repay);
    }

    public function accept_request($req_num) {
        $user = $this->session->user;
        $this->transaction_model->accept_request($req_num,$user);
    }

    public function repay_loan() {
        $user = $this->session->user;
        $this->transaction_model->repay_loan($user);
    }

    public function loan_test(){
        $this->transaction_model->transact_blockchain("a","B","c");
    }
 }