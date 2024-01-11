<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_requisition_m extends MY_Model {

    protected $_table_name = 'asset_requisition';
    protected $_primary_key = 'asset_requisitionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "asset_requisitionID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset_requisition($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_asset_requisition_with_userypeID($array=NULL) {
        $this->db->select('asset_requisition.*, asset.description,asset.asset_number, usertype.usertype');
        $this->db->from('asset_requisition');
        $this->db->join('asset', 'asset_requisition.assetID = asset.assetID', 'LEFT'); $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_requisition.usertypeID', 'LEFT');
        if($array!=NULL){
             $this->db->where($array);   
        }
        $this->db->order_by("asset_requisitionID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_asset_requisition_with_usertypeID($array) {
        $this->db->select('asset_requisition.*, asset.description, usertype.usertype, location.location');
        $this->db->from('asset_requisition');
        $this->db->join('asset', 'asset_requisition.assetID = asset.assetID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_requisition.usertypeID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset_requisition.asset_locationID', 'LEFT');
        $this->db->order_by("asset_requisitionID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function get_single_asset_requisition($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_asset_requisition($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset_requisition($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset_requisition($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset_requisition($id){
        parent::delete($id);
    }


    public function get_all_asset_requisition_for_report($queryArray) {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
       
        $this->db->select('*');
        $this->db->from('asset_requisition');
        $this->db->join('asset', 'asset.assetID = asset_requisition.assetID');
        $this->db->join('usertype', 'usertype.usertypeID = asset_requisition.usertypeID', 'LEFT');

        if(isset($queryArray['asset_requisitioncustomertypeID']) && $queryArray['asset_requisitioncustomertypeID'] != 0) {
            $this->db->where('asset_requisition.usertypeID', $queryArray['asset_requisitioncustomertypeID']);
        }

        if(isset($queryArray['asset_requisitioncustomerID']) && $queryArray['asset_requisitioncustomerID'] != 0) {
            $this->db->where('asset_requisition.check_out_to', $queryArray['asset_requisitioncustomerID']);
        }

        

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));
            $this->db->where('check_out_date >=', $fromdate);
            $this->db->where('check_out_date <=', $todate);
        } 
        $this->db->order_by('asset_requisition.asset_requisitionID','DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
