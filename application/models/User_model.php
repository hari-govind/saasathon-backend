<?php
class User_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_basic_user($username) {
        $query = $this->db->select('username,email,max_amnt,no_clients')
                            ->where('username', $username)
                            ->get('users');
        $user = $query->row_array();
        return $user;
    }

    public function get_user_requests($username) {
        $query = $this->db->select('from,cash,return_date,return_cash')
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

    public function get_user($username) {
        $basic = $this->get_basic_user($username);
        $requests = $this->get_user_requests($username);
        $balance = $this->get_balance_info($username);
        $res = Array(
            'basic'=>$basic,
            'balance'=>$balance,
            'requests'=>$requests
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