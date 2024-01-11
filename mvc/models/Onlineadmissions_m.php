<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Onlineadmissions_m extends MY_Model {


    protected $_table_name = 'admission';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "id desc";

	function __construct() {
		parent::__construct();
	}

    public function get_onlineadmission($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_onlineadmission($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_onlineadmission_with_status(){
        $string = "SELECT admission.*,admissionremark.class_id,classes.classes,student.accounts_reg FROM `admission` LEFT JOIN admissionremark ON admission.id = admissionremark.fid LEFT JOIN classes ON admissionremark.class_id = classes.classesID LEFT JOIN student ON admission.student_ID = student.student_id AND admission.student_ID != 0 WHERE admission.status IN('In process','Confirmed') AND admissionremark.id IN (SELECT MAX(admissionremark.id) FROM admissionremark GROUP BY admissionremark.fid)";
        $query = $this->db->query($string);
        return $query->result();
    }
    
    public function get_invoice_data($array = NULL){
        $string = "select admission.*,classes.classes,sessions.name FROM admission LEFT JOIN admissionremark ON admission.id = admissionremark.fid LEFT JOIN classes ON admissionremark.class_id = classes.classesID LEFT JOIN sessions ON admissionremark.session_id = sessions.id WHERE admission.id = '".$array['id']."' ORDER BY admissionremark.id DESC LIMIT 1";
		$query = $this->db->query($string);
 		return $query->row();
    }
    
    public function get_bank_accounts(){
        $string = "SELECT * FROM bank_accounts";
        $query = $this->db->query($string);
        return $query->result();
    }
    
    public function change_paid_status($id, $admission_fee, $bank_accounts){
        $sql = "UPDATE admission SET payment_status='paid' WHERE id=$id";
        $query = $this->db->query($sql);
        $string = "SELECT opening_balance FROM bank_accounts WHERE id = '".$bank_accounts."'";
        $query = $this->db->query($string);
        $result = $query->row();
        $opening_balance = $result->opening_balance;
        $total = $opening_balance + $admission_fee;
        $sqls= "UPDATE bank_accounts SET opening_balance='$total' WHERE id='".$bank_accounts."'";
        $querys = $this->db->query($sqls);
        // Class Id
        $stringclass = "SELECT class_id FROM admissionremark ORDER BY id DESC LIMIT 1";
        $query_class = $this->db->query($stringclass);
        $class_result = $query_class->row();
        $class_id = $class_result->class_id;
        //
        $strings = "SELECT journal_id FROM journal_entries ORDER BY journal_id DESC LIMIT 1";
        $query_journal = $this->db->query($strings);
        $journal_result = $query_journal->row();
        $journal_id = $journal_result->journal_id;
        $journal_id = $journal_id + 1;
        $current_date = date("Y-m-d");
        $time = date("h:i:s");
        $current_date_time = $current_date.' '.$time;
        $sql_entries = "INSERT INTO journal_entries (`date`, journal_id, created_by, created_at, updated_at, admission_id, class_id) VALUES ('$current_date', '$journal_id', '2', '$current_date_time', '$current_date_time', '$id', '$class_id')";
        $query_entries = $this->db->query($sql_entries);
        $insert_id = $this->db->insert_id();
        $sql_items = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '297' , '0.00', '$admission_fee', '$current_date_time', '$current_date_time')";
        $query_items = $this->db->query($sql_items);
        
        $sql_itemsj = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '164' , '0.00', '$admission_fee', '$current_date_time', '$current_date_time')";
       
        $query_itemsj = $this->db->query($sql_itemsj);
        
        $sql_itemso = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '98' , '$admission_fee', '0.00', '$current_date_time', '$current_date_time')";
       
        $query_itemso = $this->db->query($sql_itemso);
        
        if($bank_accounts == 4){
            $sql_itemsba = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '193' , '$admission_fee', '0.00', '$current_date_time', '$current_date_time')";
       
            $query_itemsba = $this->db->query($sql_itemsba);
        }else if($bank_accounts == 5){
            $sql_itemsba = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '194' , '$admission_fee', '0.00', '$current_date_time', '$current_date_time')";
       
            $query_itemsba = $this->db->query($sql_itemsba);
        }
        return $query_itemsj;
        // var_dump($querys);
        // exit();
    }
    
    public function get_where_in_onlineadmission($array, $key = NULL, $whereArray = NULL) {
        $query = parent::get_where_in($array, $key, $whereArray);
        return $query;
    }

    public function get_single_onlineadmission($array=NULL) {
        $query = parent::get_single($array);
        return $query;
    }

	public function insert_onlineadmission($array) {
		$id = parent::insert($array);
		return $id;
	}
    
    public function update_onlineadmission($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_onlineadmission($id){
        parent::delete($id);
    }
}