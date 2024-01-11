<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maininvoice_m extends MY_Model {

	protected $_table_name = 'maininvoice';
	protected $_primary_key = 'maininvoiceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "maininvoiceID desc";
	

	public function __construct() {
		parent::__construct();
	}

	public function get_maininvoice_with_studentrelation($schoolyearID = NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);
		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'desc');

		$query = $this->db->get();
// 		$this->db->limit($page, $segment);
// 		print_r($this->db->last_query());  
// 		exit();
		return $query->result();
	}

	public function get_maininvoice_with_cpv($studentID,$order = NULL,$type = NULL,$challanNo = NULL){
		$this->db->select('maininvoice.*,invoice.refrence_no');
		$this->db->from($this->_table_name);
		$this->db->join('invoice', 'invoice.maininvoiceID = maininvoice.maininvoiceID', 'LEFT');
		$this->db->where('maininvoice.maininvoicestudentID', $studentID);
		$this->db->where('maininvoice.maininvoiceschoolyearID', 1);
		$this->db->where('maininvoice.maininvoice_status', 1);
		
		if ($challanNo != NULL) {
			$this->db->where('invoice.refrence_no', $challanNo);
		}

		if($type != NULL){
		    $this->db->where('maininvoice.maininvoice_type_v', $type);
		}

		if ($order != NULL) {
			$this->db->order_by('maininvoice.maininvoiceID', $order);	
		}

		$query = $this->db->get();
		return $query->result();
	}

    public function get_maininvoice_with_studentrelation_pag($page, $segment,$schoolyearID = NULL,$type = NULL,$searchInvoice = NULL,$yearly = NULL) {
		$this->db->select('studentrelation.*,maininvoice.*,student.accounts_reg,student.registerNO,student.username,student.balance,student.no_installment');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);
		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}
		if($searchInvoice != NULL){
			$this->db->group_start();
		    $this->db->like('studentrelation.srname', $searchInvoice);
		    $this->db->or_like('student.accounts_reg', $searchInvoice);
		    $this->db->or_like('student.registerNO', $searchInvoice);
		    $this->db->or_like('student.username', $searchInvoice);
		    $this->db->group_end();
		}

		if ($yearly != NULL) {
			$v = ($yearly = 2)? '>=' : '<';
			$this->db->where('maininvoice.maininvoicedate '.$v, '2022-03-01');
		}
		
		if($type != NULL){
		    $this->db->where('maininvoice.maininvoice_type_v', $type);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'ASC');
        $this->db->limit($page, $segment);
		$query = $this->db->get();
		// print_r($this->db->last_query());  
		// exit();
		return $query->result();
	}
	
	public function count_maininvoice_with_studentrelation($schoolyearID = NULL,$type = NULL,$searchInvoice = NULL, $yearly = NULL) {
		$this->db->select('studentrelation.*,maininvoice.*,student.accounts_reg,student.registerNO,student.username');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);
		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}
		if($searchInvoice != NULL){
		    $this->db->like('studentrelation.srname', $searchInvoice);
		    $this->db->or_like('student.accounts_reg', $searchInvoice);
		    $this->db->or_like('student.registerNO', $searchInvoice);
		    $this->db->or_like('student.username', $searchInvoice);
		}

		if ($yearly != NULL) {
			$v = ($yearly = 2)? '>=' : '<';
			$this->db->where('maininvoice.maininvoicedate '.$v, '2022-03-01');
		}

		if($type != NULL){
		    $this->db->where('maininvoice.maininvoice_type_v', $type);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'desc');

		$query = $this->db->get();
		return $query->num_rows();
	}

    public function get_maininvoice_with_studentrelation_by_array($page, $segment,$schoolyearID = NULL,$type = 'data',$array = array()) {
    	if ($type=='data') {
    		
		$this->db->select('invoice.totalfine,invoice.studentID,student_id,student_id,accounts_reg,registerNO,refrence_no,maininvoicestudentID,srname,srclasses,maininvoicesectionID,srsectionID,srclassesID,balance,no_installment,maininvoicetotal_fee,maininvoice_discount,maininvoicenet_fee,maininvoice.maininvoiceID,maininvoicedate,maininvoicedue_date,maininvoice_status,maininvoicestatus,maininvoiceclassesID');
    	}else{

		$this->db->select('maininvoice.maininvoiceID');	
    	}
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID', 'LEFT');
		$this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID', 'LEFT');
		$this->db->join('invoice', 'invoice.maininvoiceID = maininvoice.maininvoiceID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);
		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		if (count($array)) {
			if(isset($array['nameSearch']) AND $array['nameSearch'] != ''){
				$this->db->group_start();
			    $this->db->like('studentrelation.srname', $array['nameSearch']);
			    $this->db->or_like('student.accounts_reg', $array['nameSearch']);
			    $this->db->or_like('student.registerNO', $array['nameSearch']);
			    $this->db->or_like('student.username', $array['nameSearch']);
			    $this->db->group_end();
			    unset($array['nameSearch']);
			}
		}
		if (count($array)) {
			$this->db->where($array);
		}
		
		 
		$this->db->order_by('maininvoice.maininvoiceID', 'ASC');


    	if ($type=='data') {
    	
        $this->db->limit($page, $segment);
		$query = $this->db->get();
		// print_r($this->db->last_query());  
		// exit();
		return $query->result();
    	}else{

		$query = $this->db->get();
		 
		return $query->num_rows();
    	}
	}

    public function get_maininvoice_last_id() {
    	 
    	 	
		 

		$this->db->select('maininvoiceID');	
    	 
		$this->db->from('maininvoice'); 
 
		
		 
		$this->db->order_by('maininvoiceID', 'desc');

 
    	 
    	
        $this->db->limit(1);
		$query = $this->db->get();
	 
		$maininvoiceID =	 $query->row();
		if ($maininvoiceID) {
        	return $maininvoiceID->maininvoiceID;
        }else{
        	return 0;
        }
		 
    	 
	}
	

	public function get_maininvoice_with_studentrelation_by_studentID($studentID, $schoolyearID = NULL) {
		$this->db->select('invoice.totalfine,studentrelation.*,maininvoice.*,student.accounts_reg,student.registerNO,student.username,student.balance,student.no_installment');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID', 'LEFT');
        $this->db->join('invoice', 'invoice.maininvoiceID = maininvoice.maininvoiceID', 'LEFT');
		$this->db->where('maininvoice.maininvoicestudentID', $studentID);
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
			$this->db->where('maininvoice.maininvoice_status', 1);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'ASC');
		// $this->db->limit($page, $segment);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_maininvoice_with_studentrelation_by_multi_studentID($studentIDArrays, $schoolyearID = NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if(customCompute($studentIDArrays)) {
			$this->db->where_in('maininvoice.maininvoicestudentID', $studentIDArrays);
		}

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_maininvoice_with_studentrelation_by_maininvoiceID($invoiceID, $schoolyearID = NULL) {
		$this->db->select('studentrelation.*,maininvoice.*,invoice.*,student.accounts_reg,student.registerNO,student.username,student.balance,student.no_installment');
// 		$this->db->select('invoice.refrence_no');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
        $this->db->join('invoice', 'invoice.maininvoiceID = maininvoice.maininvoiceID', 'LEFT');
        $this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID', 'LEFT');		
		$this->db->where('maininvoice.maininvoiceID', $invoiceID);
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_maininvoice($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_maininvoice($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_maininvoice($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_maininvoice($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function insert_batch_maininvoice($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_maininvoice($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_student_maininvoice($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update($this->_table_name);
	}

	public function delete_maininvoice($id){
		parent::delete($id);
	}
}

/* End of file invoice_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/invoice_m.php */