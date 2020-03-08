<?php
class User_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_basic_user($username) {
        $query = $this->db->select('username,email,max_amnt,no_clients,UPI')
                            ->where('username', $username)
                            ->get('users');
        $user = $query->row_array();
        return $user;
    }

    public function get_user_requests($username) {
        $query = $this->db->select('id,req_no,from,cash,return_date,return_cash')
                            ->where('to',$username)
                            ->get('requests');
        $reqs = $query->row_array();
        return $reqs;
    }

    public function get_balance_info($username) {
        $query = $this->db->select('balance,no_lents,no_borrows,late_count,total_amout_paid')
                            ->where('username',$username)
                            ->get('balance');
        $reqs = $query->row_array();
        return $reqs;
    }

    public function get_loans_togive($username) {
        $query = $this->db->select('*')
                            ->where('receiver',$username)
                            ->where('completed',0)
                            ->get('activeloans');
        return $query->result();
    }

    public function get_loans_toget($username) {
        $query = $this->db->select('*')
                            ->where('giver',$username)
                            ->where('completed',0)
                            ->get('activeloans');
        return $query->result();
    }

    public function get_completed_transactions($username) {
        $query = $this->db->select('*')
                            ->where('giver',$username)
                            ->where('completed',1)
                            ->get('activeloans');
        return $query->result();

    }

    public function get_user($username) {
        $basic = $this->get_basic_user($username);
        $requests = $this->get_user_requests($username);
        $balance = $this->get_balance_info($username);
        $give = $this->get_loans_togive($username);
        $get = $this->get_loans_toget($username);
        $completed_transactions = $this->get_completed_transactions($username);
        $res = Array(
            'basic'=>$basic,
            'balance'=>$balance,
            'requests'=>$requests,
            'give'=>$give,
            'get'=>$get,
            'completed_transactions'=>$completed_transactions
        );
        return $res;
    }


    public function get_prospective_creditors($amount) {
        $query = $this->db->select('username')
                            ->where('max_amnt >=',$amount)
                            ->get('users');
        $creditors = $query->result();
        return $creditors;                    
    }

}
?>