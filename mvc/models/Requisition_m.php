<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class requisition_m extends MY_Model {

    protected $_table_name = 'requisition';
    protected $_primary_key = 'requisitionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "requisitionID desc";

    function __construct() {
        parent::__construct();
    }

    function get_requisition($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_requisition($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_requisition($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_requisition($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_requisition($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_requisition($id){
        parent::delete($id);
    }



    public function get_req_number_by_date($date = NULL){
        $check_dd   =   date('ym',strtotime($date));
        $query = $this->db->query("SELECT refrence_no FROM `requisition` WHERE `refrence_no` LIKE '$check_dd%' ORDER BY refrence_no DESC LIMIT 1");
        if ($query->num_rows()){
            $ref_result = $query->row();
            $refrence_no = $ref_result->refrence_no + 1;
        }else{
            $refrence_no = (int) $check_dd.'00001';
        }
        
        return $refrence_no;
    }
    

    
    public function get_all_requisition_for_report($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->db->select('*');
        $this->db->from('requisition');
        $this->db->join('requisitionitem', 'requisition.requisitionID = requisitionitem.requisitionID');

        if(isset($queryArray['productsupplierID']) && $queryArray['productsupplierID'] != 0) {
            $this->db->where('requisition.productsupplierID', $queryArray['productsupplierID']);
        }

        if(isset($queryArray['productwarehouseID']) && $queryArray['productwarehouseID'] != 0) {
            $this->db->where('requisition.productwarehouseID', $queryArray['productwarehouseID']);
        }

        if(isset($queryArray['reference_no']) && !empty($queryArray['reference_no'])) {
            $this->db->where('requisition.requisitionreferenceno', $queryArray['reference_no']);
        }

        if(isset($queryArray['statusID']) && $queryArray['statusID'] != 0) {
            if($queryArray['statusID'] == 1) {
                $this->db->where('requisition.requisitionstatus', 0);
                $this->db->where('requisition.requisitionrefund', 0);
            } elseif($queryArray['statusID'] == 2) {
                $this->db->where('requisition.requisitionstatus', 1);
                $this->db->where('requisition.requisitionrefund', 0);
            } elseif($queryArray['statusID'] == 3) {
                $this->db->where('requisition.requisitionstatus', 2);
                $this->db->where('requisition.requisitionrefund', 0);
            } elseif($queryArray['statusID'] == 4) {
                $this->db->where('requisition.requisitionrefund', 1);
            }
        } else {
            $this->db->where('requisition.requisitionrefund', 0);
        }

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));
            $this->db->where('requisitiondate >=', $fromdate);
            $this->db->where('requisitiondate <=', $todate);
        }

        $this->db->where('requisition.schoolyearID',$schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }



}
