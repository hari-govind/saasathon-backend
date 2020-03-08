<?php
class Transaction_model extends CI_Model {
    public function __construct() {
        $this->load->database();
        $this->load->model('user_model');
    }

    public function transact_blockchain($from,$to,$amount) {
        $url = "http://localhost:5000/transactions/new";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $data = json_encode(array(
            'sender'=>$from,
            'recipient'=>$to,
            'amount'=>$amount
        ));
        echo $data;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        echo "Success";
        curl_close($ch);
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
        $creds = $this->user_model->get_prospective_creditors($amount,$from);
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

    public function accept_request($req_num, $user) {
        if($this->session->user === null) {
            die('please login');
        }
        $req = $this->db->select('*')
                        ->where('req_no',$req_num)
                        ->where('to',$user)
                        ->get('requests');
        $req = $req->row_array();
        $this->db->delete('requests',array('req_no'=>$req_num));
        $data = array(
            'receiver'=>$req['from'],
            'giver'=>$user,
            'date'=>$req['return_date'],
            'amount'=>$req['cash'],
            'repayment'=>$req['return_cash']
        );
        $this->db->insert('activeloans',$data);
        $this->transact_blockchain($data['giver'],$data['receiver'],$data['amount']);
        echo "Success";
    }

    public function repay_loan($user) {
        $query = $this->db->select('*')
                            ->where('receiver',$user)
                            ->where('completed',0)
                            ->get('activeloans');
        $res = $query->row_array();
        $res['completed'] = 1;
        $this->db->replace('activeloans',$res);
        echo "Success";
    }


}