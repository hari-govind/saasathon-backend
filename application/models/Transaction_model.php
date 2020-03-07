<?php
class Transaction_model extends CI_Model {
    public function __construct() {
        $this->load->database();
        $this->load->model('user_model');
    }

    public function add_request($no,$from,$to,$cash,$repay,$repay_date) {
        $data = array(
            'req_no'=>$no,
            'from'=>$from,
            'to'=>$to->username,
            'cash'=>$cash,
            'return_date'=>$repay_date,
            'return_cash'=>$repay,
            'status'=>0
        );
        $query = $this->db->insert('requests',$data);
    }

    public function request_credit($from,$amount, $date,$repay) {
        $creds = $this->user_model->get_prospective_creditors($amount);
        $num = rand(10,100000);
        foreach ($creds as $key => $value) {
            $this->add_request($num,$from,$value,$amount,$repay,$date);
        }
        echo "Success";
    }

    public function loan_exists($user) {
        $c = $this->db->select('*')
                        ->where('from',$user)
                        ->count_all_results('requests');
        if ($c > 0) {
            return true;
        }
        return false;
    }
}