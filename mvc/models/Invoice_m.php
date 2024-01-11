<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends MY_Model {

	protected $_table_name = 'invoice';
	protected $_primary_key = 'invoiceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "invoiceID asc";
	

	public function __construct() {
		parent::__construct();
	}

	public function get_invoice_with_studentrelation() {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_with_studentrelation_by_studentID($studentID) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.studentID', $studentID);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_expense_report_by_array($array=NULL) {
		$this->db->select('journal_entries.id as journal_entries_id , journal_entries.journal_id , journal_entries.date as transaction_date , journal_entries.vouchertype , journal_entries.admission_id , journal_entries.accounts_reg , admission.form_no , users_admission.fullName , classes.classes , journal_items.*');
		$this->db->from('journal_items');
		$this->db->join('journal_entries', 'journal_entries.id = journal_items.journal', 'LEFT');
		$this->db->join('admission', 'admission.id = journal_entries.admission_id', 'LEFT');
		$this->db->join('users_admission', 'users_admission.id = admission.user_id', 'LEFT');
		$this->db->join('classes', 'journal_entries.class_id = classes.classesID', 'LEFT');
		$this->db->join('chart_of_accounts', 'chart_of_accounts.id = journal_items.account', 'LEFT');
		if (count($array)) {
			$this->db->where($array); 
		}
		
		 
		$query = $this->db->get();
		return $query->result();
	}
    
    public function upload_cpv_bpv($student_id,$paymentdate,$roll,$challan,$amount,$status,$paymenttype,$pay_type){
	 	$sql_entries = "INSERT INTO upload_cpv_bpv (`student_id`, payment_date, student_roll, challan_no, amount,`status`,`payment_type`,`pay_type`) VALUES ('$student_id', '$paymentdate', '$roll', '$challan', '$amount', '$status', '$pay_type', '$paymenttype')";
        $query_entries = $this->db->query($sql_entries);
        return $query_entries;
    }

    public function update_upload_cpv_bpv($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update("upload_cpv_bpv");
	}

    public function invoice_accounts($student_id,$class_id,$net_fee,$ref_no,$maininvoiceID,$accounts_reg,$description){
        $strings = "SELECT journal_id FROM journal_entries ORDER BY journal_id DESC LIMIT 1";
        $query_journal = $this->db->query($strings);
        $journal_result = $query_journal->row();
        if ($journal_result) {
        	$journal_id = $journal_result->journal_id;
        }else{
        	$journal_id = 0;
        }
        
        $journal_id = $journal_id + 1;
        $current_date = date("Y-m-d");
        $time = date("h:i:s");
        $current_date_time = $current_date.' '.$time;

       	$this->db->select('*');
		$this->db->from('journal_entries');
		 
		$this->db->where('journal_entries.student_id', $student_id); 
		$query = $this->db->get();
		$old_data =	 $query->row();
		if ($old_data) {
			$insert_id = $old_data->id;
		}else{
			$sql_entries = "INSERT INTO journal_entries (`date`, journal_id, created_by, created_at, updated_at,student_id, class_id, invoice_id, vouchertype, reference, accounts_reg, description) VALUES ('$current_date', '$journal_id', '2', '$current_date_time', '$current_date_time', '$student_id', '$class_id', '$maininvoiceID','JUR','$ref_no','$accounts_reg','$description')";
        $query_entries = $this->db->query($sql_entries);
        $insert_id = $this->db->insert_id();
		}

        



        $sql_items = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '298' , '0.00', '$net_fee', '$current_date_time', '$current_date_time')";
        $query_items = $this->db->query($sql_items);
        
        $sql_itemsj = "INSERT INTO journal_items (`journal`, account, debit, credit, created_at, updated_at) VALUES ('$insert_id', '163' , '$net_fee', '0.00', '$current_date_time', '$current_date_time')";
       
        $query_itemsj = $this->db->query($sql_itemsj);

        $sql_itemsjs = "INSERT INTO students_ledgers (`journal_entries_id`, `maininvoice_id`, `date`, `type`, account, vr_no , narration , debit, credit, balance ,created_at, updated_at) VALUES ('$insert_id', '$maininvoiceID' , '$current_date' , 'CR' , '163' , '$ref_no' , '$ref_no','$net_fee', '0.00', '$net_fee' , '$current_date_time', '$current_date_time')";

        $this->db->query($sql_itemsjs);

        $sql_itemsjss = "INSERT INTO students_ledgers (`journal_entries_id`, `maininvoice_id` , `date`, `type`, account, vr_no , narration , debit, credit, balance ,created_at, updated_at) VALUES ('$insert_id', '$maininvoiceID' , '$current_date' , 'DR' , '298' , '$ref_no' , '$ref_no','0.00', '$net_fee', '$net_fee' , '$current_date_time', '$current_date_time')";

        $this->db->query($sql_itemsjss);
        
        return $query_itemsj;
    }


    public function journal_entries_id($array=NULL){
       

       	$this->db->select('*');
		$this->db->from('journal_entries');
		 
		$this->db->where('journal_entries.student_id', $array['studentID']); 
		$query = $this->db->get();
		$old_data =	 $query->row();
		if ($old_data) {
			$insert_id = $old_data->id;
		}else{

		$strings = "SELECT journal_id FROM journal_entries ORDER BY journal_id DESC LIMIT 1";
        $query_journal = $this->db->query($strings);
        $journal_result = $query_journal->row();
        if ($journal_result) {
        	$journal_id = $journal_result->journal_id;
        }else{
        	$journal_id = 1;
        }
        
        $journal_id = $journal_id + 1;
        
        

			$data = array(
		        'date' 			=> date('Y-m-d'),
		        'reference' 	=> $array['refrence_no'],
		        'description' 	=> $array['description'],
		        'journal_id' 	=> $journal_id,
		        'created_by' 	=> 2,
		        'created_at' 	=> date('Y-m-d h:i:s'),
		        'updated_at' 	=> date('Y-m-d h:i:s'),
		        'admission_id' 	=> 0,
		        'class_id' 		=> $array['classesID'],
		        'accounts_reg' 	=> $array['accounts_reg'],
		        'student_id' 	=> $array['studentID'],
		        'entry_userID' 	=> $array['studentID'],
		        'entry_type' 	=> 'student',
		        'invoice_id' 	=> $array['maininvoiceID'],
		        'vouchertype' 	=> 'JUR',
		         
			);

		$this->db->insert('journal_entries', $data);
        $insert_id = $this->db->insert_id();
		}

		return $insert_id;
		}
    


    public function get_journal_entries_id_by_array($array=NULL){
       

       	$this->db->select('*');
		$this->db->from('journal_entries');
		 
		$this->db->where('journal_entries.entry_userID', $array['entry_userID']); 
		$this->db->where('journal_entries.entry_type', $array['entry_type']); 
		$query = $this->db->get();
		$old_data =	 $query->row();
		if ($old_data) {
			$insert_id = $old_data->id;
		}else{

		$strings = "SELECT journal_id FROM journal_entries ORDER BY journal_id DESC LIMIT 1";
        $query_journal = $this->db->query($strings);
        $journal_result = $query_journal->row();
        if ($journal_result) {
        	$journal_id = $journal_result->journal_id;
        }else{
        	$journal_id = 1;
        }
        
        $journal_id = $journal_id + 1;
        	$data = array(
		        'date' 			=> date('Y-m-d'),
		        'reference' 	=> 0,
		        'description' 	=> 0,
		        'journal_id' 	=> $journal_id,
		        'created_by' 	=> 2,
		        'created_at' 	=> date('Y-m-d h:i:s'),
		        'updated_at' 	=> date('Y-m-d h:i:s'),
		        'admission_id' 	=> 0,
		        'class_id' 		=> 0,
		        'accounts_reg' 	=> 0,
		        'student_id' 	=> $array['entry_userID'],
		        'invoice_id' 	=> 0,
		        'vouchertype' 	=> 'JUR',
		        'entry_userID' 	=> $array['entry_userID'],
		        'entry_type' 	=> $array['entry_type'],
		    );

		$this->db->insert('journal_entries', $data);
        $insert_id = $this->db->insert_id();
		}

		return $insert_id;
		}
    public function push_invoice_to_studnet_ledgers($array = NULL)
    {
    	if ($array!=NULL) {
    		$this->db->insert_batch('students_ledgers', $array);
    		return TRUE;
    	}else{
    		return FALSE;
    	}
    	
    }
    

    public function update_studnet_ledgers($update=NULL,$where=NULL)
    {	
    	if ($where!=NULL && $update!=NULL) {
    		$this->db->where($where);
        $this->db->update('students_ledgers', $update); 
    	}
    	
    }
    

    public function delete_students_ledgers($where=NULL)
    {	
    	if ($where!=NULL ) {
    		$this->db->where($where);
        $this->db->delete('students_ledgers'); 
    	}
    	
    }
    public function push_invoice_to_journal_items($array = NULL)
    {
    	if ($array!=NULL) {
    		$this->db->insert_batch('journal_items', $array);
    		return TRUE;
    	}else{
    		return FALSE;
    	}
    	
    }

    public function update_journal_items($update=NULL,$where=NULL)
    {	
    	if ($where!=NULL && $update!=NULL) {
    		$this->db->where($where);
        $this->db->update('journal_items', $update); 
    	}
    	
    }

    public function delete_journal_items($where=NULL)
    {	
    	if ($where!=NULL ) {
    		$this->db->where($where);
        $this->db->delete('journal_items'); 
    	}
    	
    }

    public function check_cpv_bpv($student_id,$roll,$challan){
    	$this->db->select('*');
		$this->db->from('upload_cpv_bpv');
		$this->db->where('upload_cpv_bpv.student_id', $student_id);
		$this->db->where('upload_cpv_bpv.student_roll', $roll);
		$this->db->where('upload_cpv_bpv.challan_no', $challan);
		$query = $this->db->get();
		return $query->num_rows();
    }

    public function get_reconcile_data($status = NULL){
    	$this->db->select('*');
    	$this->db->from('upload_cpv_bpv');
    	if ($status != NULL) {
    		$this->db->where('upload_cpv_bpv.status', $status);
    	}
    	$query = $this->db->get();
    	return $query->result();
    }

    public function get_bank_accounts($array = NULL){
    	$this->db->select('*');
    	$this->db->from('bank_accounts');
    	if ($array != NULL) {
    		$this->db->where($array);
    	}
    	$query = $this->db->get();
    	return $query->result();
    }

    public function get_chartofaccount_data($array = NULL){
    	$this->db->select('*');
    	$this->db->from('chart_of_accounts');
    	if ($array != NULL) {
    		$this->db->where($array);
    	}
    	$query = $this->db->get();
    	return $query->result();
    }

    public function get_reconcile_duplicate_data($status = NULL){
    	$query = $this->db->query('SELECT challan_no, COUNT(*) c FROM upload_cpv_bpv GROUP BY challan_no HAVING c > 1');     	 
    	//$query = $this->db->get();
    	//var_dump($query);
    	return $query->result();
    }
    public function get_upload_record_data($cpv_bpv_recordID = NULL){
    	$this->db->select('*');
    	$this->db->from('bpv_cpv_upload_record');
    	if ($cpv_bpv_recordID != NULL) {
    		$this->db->where('cpv_bpv_recordID', $cpv_bpv_recordID);

    	$query = $this->db->get();
    	return $query->row();
    	}else{
    		return FALSE;
    	}
    }
    public function get_upload_record_by_array($array = NULL){
    	$this->db->select('*');
    	$this->db->from('bpv_cpv_upload_record');
    	if ($array != NULL) {
    		$this->db->where( $array);

    	$query = $this->db->get();
    	return $query->result();
    	}else{
    		return FALSE;
    	}
    }

	public function get_invoice_with_studentrelation_by_invoiceID($invoiceID) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.invoiceID', $invoiceID);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_invoice_ref(){
		$check_dd = date("Y-m-d");
		$query = $this->db->query("SELECT refrence_no FROM `invoice` ORDER BY refrence_no DESC LIMIT 1");
		// $ref_result = $query->row();
		// $refrence_no = $ref_result->refrence_no;
		// return $refrence_no;
		if ($query->num_rows()){
		    $ref_result = $query->row();
		    $refrence_no = $ref_result->refrence_no + 1;
		}else{
		    $refrence_no = $check_dd.'001';  
		}
		return $refrence_no;
	}

	public function get_invoice_ref_by_date($date = NULL){
	    $check_dd   =   date('ym',strtotime($date));
	    $query = $this->db->query("SELECT refrence_no FROM `invoice` WHERE `refrence_no` LIKE '$check_dd%' ORDER BY refrence_no DESC LIMIT 1");
	    if ($query->num_rows()){
	        $ref_result = $query->row();
	        if ($ref_result->refrence_no==($check_dd.'9999')) {
	        	$up_ref_number 	=	$check_dd.'09999';
	        	$refrence_no 	= 	$up_ref_number + 1;
	        }else{
	        	
	        	$refrence_no = $ref_result->refrence_no + 1;
	        }
	        
		    
	    }else{
	        $refrence_no = (int) $check_dd.'00001';
	    }


	     
	    return $refrence_no;
	}
	
public function get_invoice_or_where_by_array($where=NULL,$or_where=NULL){
		$this->db->select('*');
		$this->db->from('invoice'); 
		if ($where!=NULL) {
			$this->db->where($where);
		} 
		if ($or_where!=NULL) {
			$this->db->group_start();
			$this->db->or_where($or_where);
			$this->db->group_end();
		} 
		$query = $this->db->get();
		return $query->result();
	}
	public function delete_cpv_bpv($id){
		$query = $this->db->query("DELETE FROM `upload_cpv_bpv` WHERE `id` = $id");
		return $query;
	}
	public function delete_cpv_bpv_by_cpv_bpv_recordID($id){
		$query = $this->db->query("DELETE FROM `upload_cpv_bpv` WHERE `cpv_bpv_recordID` = $id");
		return $query;
	}
	public function delete_upload_by_cpv_bpv_recordID($id){
		$query = $this->db->query("DELETE FROM `bpv_cpv_upload_record` WHERE `cpv_bpv_recordID` = $id");
		return $query;
	}

	public function get_invoice_ref_by_col($id){
		$query = $this->db->query("SELECT refrence_no FROM `invoice` WHERE maininvoiceID = '$id'");
		$ref_result = $query->row();
		$refrence_no = $ref_result->refrence_no;
		return $refrence_no;
	}

	public function get_invoice_order($maininvoiceID){
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('maininvoice', 'maininvoice.maininvoiceID = invoice.maininvoiceID', 'LEFT');
		$this->db->where('invoice.maininvoiceID', $maininvoiceID);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_join_main_order($array=null){
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('maininvoice', 'maininvoice.maininvoiceID = invoice.maininvoiceID', 'LEFT');
		if ($array!=null) {
			$this->db->where($array);
		}
		$this->db->limit(100);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_join_student_by_array($array=NULL){
		$this->db->select('invoice.*,feetypes.*,student.accounts_reg');
		$this->db->from('invoice');
		$this->db->join('student', 'student.studentID = invoice.studentID', 'LEFT');
		$this->db->join('feetypes', 'feetypes.feetypesID = invoice.feetypeID', 'LEFT');
		if ($array!=NULL) {
			$this->db->where($array);
		}
		$this->db->order_by("invoiceID", "DESC");
		$this->db->limit(2000);
		$query = $this->db->get();
		return $query->result();
	}

 

	public function get_invoice($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_invoice($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_invoice($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_invoice($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function insert_batch_invoice($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_student_invoice($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update($this->_table_name);
	}

	public function update_invoice($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_invoice_by_maininvoiceID($data, $id = NULL) {
		$this->db->set($data);
		$this->db->where('maininvoiceID', $id);
		$this->db->update($this->_table_name);
		return $id;
	}

	public function update_batch_invoice($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_invoice($id){
		parent::delete($id);
	}

	public function delete_invoice_by_maininvoiceID($id){
		$this->db->delete($this->_table_name, array('maininvoiceID' => $id)); 
		return TRUE;
	}	

	public function get_all_duefees_for_report($queryArray) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->where('invoice.schoolyearID',$queryArray['schoolyearID']);

		if((isset($queryArray['classesID']) && $queryArray['classesID'] != 0) || (isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) || (isset($queryArray['studentID']) && $queryArray['studentID'] != 0)) {
			
			if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
				$this->db->where('invoice.classesID', $queryArray['classesID']);
			}

			if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
				$this->db->where('invoice.studentID', $queryArray['studentID']);
			}
		}

		if(isset($queryArray['feetypeID']) && $queryArray['feetypeID'] != 0) {
			$this->db->where('invoice.feetypeID', $queryArray['feetypeID']);
		}

		if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
			$fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
			$todate = date('Y-m-d', strtotime($queryArray['todate']));
			$this->db->where('create_date >=', $fromdate);
			$this->db->where('create_date <=', $todate);
		}

		$this->db->where('invoice.paidstatus !=', 2);
		$this->db->where('invoice.deleted_at', 1);
		$this->db->where('invoice.invoice_status', 1);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_balancefees_for_report($queryArray) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->where('invoice.schoolyearID',$queryArray['schoolyearID']);

		if((isset($queryArray['classesID']) && $queryArray['classesID'] != 0) || (isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) || (isset($queryArray['studentID']) && $queryArray['studentID'] != 0)) {
			
			if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
				$this->db->where('invoice.classesID', $queryArray['classesID']);
			}

			if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
				$this->db->where('invoice.studentID', $queryArray['studentID']);
			}
		}

		if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
			$fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
			$todate = date('Y-m-d', strtotime($queryArray['todate']));
			$this->db->where('create_date >=', $fromdate);
			$this->db->where('create_date <=', $todate);
		}
		
		$this->db->where('invoice.deleted_at', 1);
		$this->db->where('invoice.invoice_status', 1);

		$query = $this->db->get();
		return $query->result();
	}

	

	public function get_invoice_by_array_where_in($queryArray) {
		$this->db->select('invoice.studentID,amount,discount,type_v,feetype, SUM(payment.paymentamount) as total_paid ');
		$this->db->from('invoice');

		$this->db->join('payment','invoice.invoiceID=payment.invoiceID','LEFT');
		if(isset($queryArray['maininvoice_type_v']) && $queryArray['maininvoice_type_v'] != '') {
			$this->db->where_in('invoice.type_v', $queryArray['maininvoice_type_v']);
			unset($queryArray['maininvoice_type_v']);
		}
		$this->db->where($queryArray);
		$this->db->group_by('invoice.invoiceID');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_by_array_where_in_array($queryArray=NULL,$wherein=NULL) {
		$this->db->select('*');
		$this->db->from('invoice');
  
		if($wherein!=NULL) {
			 
			foreach ($wherein as $key => $value) {
				$this->db->where_in($key,$value);
			}
			
			 
		}
		if ($queryArray!=NULL) {
			$this->db->where($queryArray);
		}
		$this->db->group_by('invoice.invoiceID');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_dueamount($array) {
		$this->db->select('invoice.*,weaverandfine.weaver,weaverandfine.fine');
		$this->db->from('invoice');
		$this->db->join('weaverandfine','invoice.invoiceID=weaverandfine.invoiceID','LEFT');
		$this->db->where('invoice.schoolyearID',$array['schoolyearID']);
		$this->db->where('invoice.classesID',$array['classesID']);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_sum($array = NULL) {
		if(isset($array['maininvoiceID'])) {
			$string = "SELECT SUM(amount) AS amount, SUM(discount) AS discount, SUM((amount/100)*discount) AS discountamount, SUM(amount-((amount/100)*discount)) AS invoiceamount FROM ".$this->_table_name." WHERE maininvoiceID = '".$array['maininvoiceID']."'";
		} else {
			$string = "SELECT SUM(amount) AS amount, SUM(discount) AS discount, SUM((amount/100)*discount) AS discountamount, SUM(amount-((amount/100)*discount)) AS invoiceamount FROM ".$this->_table_name;
		}

		$query = $this->db->query($string);
		return $query->row();
	}
}