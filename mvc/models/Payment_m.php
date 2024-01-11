<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_m extends MY_Model {

	protected $_table_name = 'payment';
	protected $_primary_key = 'paymentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "paymentID desc";

	public function __construct() {
		parent::__construct();
	}

	public function order_payment($order) {
		parent::order($order);
	}

	public function get_payment_with_studentrelation_by_studentID($studentID, $schoolyearID) {
		$this->db->select('payment.*, invoice.invoiceID, invoice.feetype, invoice.amount, studentrelation.*');
		$this->db->from('payment');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = payment.studentID AND studentrelation.srschoolyearID = payment.schoolyearID', 'LEFT');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');

		if(is_array($studentID)) {
			$this->db->where_in('payment.studentID', $studentID);
		} else {
			$this->db->where(array('payment.studentID' => $studentID));
		}

		$this->db->where(array('payment.schoolyearID' => $schoolyearID));
		$this->db->order_by($this->_order_by);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_payment_with_studentrelation($schoolyearID) {
		$this->db->select('payment.*, invoice.invoiceID, invoice.feetype, invoice.amount, invoice.refrence_no, studentrelation.*');
		$this->db->from('payment');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = payment.studentID AND studentrelation.srschoolyearID = payment.schoolyearID', 'LEFT');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
		$this->db->where(array('payment.schoolyearID' => $schoolyearID));
		$this->db->order_by($this->_order_by);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_payment_with_studentrelation_by_studentID_and_schoolyearID($studentID, $schoolyearID) {
		$this->db->select('payment.*, invoice.invoiceID, invoice.feetype, invoice.feetypeID, invoice.sectionID, invoice.amount, invoice.refrence_no, weaverandfine.weaver, weaverandfine.fine');
		$this->db->from('payment');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
		$this->db->join('weaverandfine', 'payment.paymentID = weaverandfine.paymentID', 'LEFT');
		$this->db->where(array('payment.studentID' => $studentID));
		$this->db->where(array('payment.schoolyearID' => $schoolyearID));
		$this->db->where(array('payment.paymentamount !=' => ''));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_payment_by_sum($invoiceID) {
		$this->db->select_sum('paymentamount');
		$this->db->where(array('invoiceID' => $invoiceID));
		$query = $this->db->get($this->_table_name);
		return $query->row();
	}

	public function get_payment_by_sum_for_edit($invoiceID, $paymentID) {
		$this->db->select_sum('paymentamount');
		$this->db->where(array('invoiceID' => $invoiceID, 'paymentID !=' => $paymentID));
		$query = $this->db->get($this->_table_name);
		return $query->row();
	}

	public function get_payment_sum_for_block($classesID,$sectionID,$studentID){
		$this->db->select_sum('payment.paymentamount');
		$this->db->from('globalpayment');
		$this->db->join('payment', 'globalpayment.globalpaymentID = payment.globalpaymentID', 'INNER');
		$this->db->where(array('globalpayment.studentID' => $studentID, 'globalpayment.sectionID' => $sectionID, 'globalpayment.classesID' => $classesID));
		$query = $this->db->get();
		return $query->row();
	}
	public function get_payment($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_orders_by_payment($array=NULL){
		$schoolyearID = $array['schoolyearID'];
		$this->db->select('payment.*');
		$this->db->from('payment');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
		$this->db->where(array('payment.schoolyearID' => $schoolyearID));
		$this->db->where(array('invoice.invoice_status' => 1));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_orderr_by_payment($array=NULL){
		$invoiceID = $array['invoiceID'];
		$studentID = $array['studentID'];
		$this->db->select('payment.*,invoice.refrence_no');
		$this->db->from('payment');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
		$this->db->where(array('payment.invoiceID' => $invoiceID));
		$this->db->where(array('payment.studentID' => $studentID));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_payment_join_invoice_by_array($array=NULL,$limit=NULL){ 
		$this->db->select('payment.*,invoice.feetypeID,invoice.refrence_no,invoice.pay_type');
		$this->db->from('payment');
		$this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
		if ($array!=NULL) {
			$this->db->where($array);
		} 
		if ($limit!=NULL) {
			$this->db->limit($limit);
		} 
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_payment($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_payment($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_where_in_payment($array, $key=NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function insert_payment($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_payment($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

	public function update_payment($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_payment($id){
		parent::delete($id);
	}

	public function delete_batch_payment($array){
		parent::delete_batch($array);
	}

	public function get_all_payment_for_report($queryArray) {
		$this->db->select('*');
		$this->db->from('payment');
		$this->db->where('payment.schoolyearID',$queryArray['schoolyearID']);

		if((isset($queryArray['classesID']) && $queryArray['classesID'] != 0) || (isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) || (isset($queryArray['studentID']) && $queryArray['studentID'] != 0)) {

			$this->db->join('globalpayment', 'payment.globalpaymentID = globalpayment.globalpaymentID','LEFT');
			
			if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
				$this->db->where('globalpayment.classesID', $queryArray['classesID']);
			}

			if(isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) {
				$this->db->where('globalpayment.sectionID', $queryArray['sectionID']);
			}

			if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
				$this->db->where('globalpayment.studentID', $queryArray['studentID']);
			}
		}

		if(isset($queryArray['feetypeID']) && $queryArray['feetypeID'] != 0) {
			$this->db->join('invoice', 'payment.invoiceID = invoice.invoiceID','LEFT');
			$this->db->where('invoice.feetypeID', $queryArray['feetypeID']);
		}

		if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
			$fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
			$todate = date('Y-m-d', strtotime($queryArray['todate']));
			$this->db->where('paymentdate >=', $fromdate);
			$this->db->where('paymentdate <=', $todate);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_payments($array) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('invoice','payment.invoiceID = invoice.invoiceID');
		$this->db->where('payment.paymentdate >=',$array['fromdate']);
		$this->db->where('payment.paymentdate <=',$array['todate']);
		$this->db->where('payment.schoolyearID',$array['schoolyearID']);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_fee_types($array) {
		$this->db->select('payment.*,invoice.feetypeID,invoice.feetype,invoice.classesID');
		$this->db->from('payment');
		$this->db->join('invoice','invoice.invoiceID = payment.invoiceID','LEFT');
		$this->db->where('payment.schoolyearID',$array['schoolyearID']);
		$this->db->where('invoice.classesID',$array['classesID']);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_globalpayments($id) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join('invoice','payment.invoiceID = invoice.invoiceID');
        $this->db->join('globalpayment','payment.globalpaymentID = globalpayment.globalpaymentID');
        $this->db->where('payment.globalpaymentID',$id);
        $query = $this->db->get();
        return $query->result();

    }

	public function get_globalpayments_ref_no($id) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join('invoice','payment.invoiceID = invoice.invoiceID');
        $this->db->join('globalpayment','payment.globalpaymentID = globalpayment.globalpaymentID');
        $this->db->where('invoice.refrence_no',$id);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_singlepayments($id) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('invoiceID',$id);
        $this->db->where('schoolyearID',$schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_payment_sum($clmn, $array) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	public function get_where_payment_sum($clmn, $wherein, $arrays, $groupID = NULL) {
		$query = parent::get_where_sum($clmn, $wherein, $arrays, $groupID);
		return $query;
	}

	public function get_single_payment_by_globalpaymentID($globalpaymentID) {
		$this->db->select('payment.*,weaverandfine.weaver,weaverandfine.fine,invoice.feetypeID,invoice.paidstatus');
        $this->db->from('globalpayment');
        $this->db->join('payment','globalpayment.globalpaymentID=payment.globalpaymentID');
        $this->db->join('invoice','invoice.invoiceID=payment.invoiceID');
        $this->db->join('weaverandfine','payment.paymentID=weaverandfine.paymentID','LEFT');
        $this->db->where('globalpayment.globalpaymentID',$globalpaymentID);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_single_payment_by_ref_no($refrence_no) {
		$this->db->select('payment.*,weaverandfine.weaver,weaverandfine.fine,invoice.feetypeID,invoice.paidstatus');
        $this->db->from('globalpayment');
        $this->db->join('payment','globalpayment.globalpaymentID=payment.globalpaymentID');
        $this->db->join('invoice','invoice.invoiceID=payment.invoiceID');
        $this->db->join('weaverandfine','payment.paymentID=weaverandfine.paymentID','LEFT');
        $this->db->where('invoice.refrence_no',$refrence_no);
        $query = $this->db->get();
        return $query->result();
	}


	public function get_payment_with_fine_schoolyear($array) {
		$this->db->select('payment.*,weaverandfine.fine');
		$this->db->from($this->_table_name);
		$this->db->join('weaverandfine','payment.paymentID = weaverandfine.paymentID','LEFT');
		if(isset($array['fromdate']) && isset($array['todate'])) {
			$this->db->where('payment.paymentdate >=',$array['fromdate']);
			$this->db->where('payment.paymentdate <=',$array['todate']);
		} 
		if(isset($array['schoolyearID'])) {
			$this->db->where('payment.schoolyearID',$array['schoolyearID']);
		}
		$query = $this->db->get();
		return $query->result();
	}
}
