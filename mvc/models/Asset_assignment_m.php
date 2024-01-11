<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_assignment_m extends MY_Model {

    protected $_table_name = 'asset_assignment';
    protected $_primary_key = 'asset_assignmentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "asset_assignmentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset_assignment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_asset_assignment_with_userypeID($array=NULL) {
        $this->db->select('asset_assignment.*, asset.description,asset.asset_number, usertype.usertype');
        $this->db->from('asset_assignment');
        $this->db->join('asset', 'asset_assignment.assetID = asset.assetID', 'LEFT'); $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_assignment.usertypeID', 'LEFT');
        if($array!=NULL){
             $this->db->where($array);   
        }
        $this->db->order_by("asset_assignmentID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_asset_assignment_with_usertypeID($array) {
        $this->db->select('asset_assignment.*, asset.description, usertype.usertype, location.location');
        $this->db->from('asset_assignment');
        $this->db->join('asset', 'asset_assignment.assetID = asset.assetID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_assignment.usertypeID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset_assignment.asset_locationID', 'LEFT');
        $this->db->order_by("asset_assignmentID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function get_single_asset_assignment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_asset_assignment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset_assignment($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset_assignment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset_assignment($id){
        parent::delete($id);
    }


    public function get_all_asset_assignment_for_report($queryArray) {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
       
        $this->db->select('*');
        $this->db->from('asset_assignment');
        $this->db->join('asset', 'asset.assetID = asset_assignment.assetID');
        $this->db->join('usertype', 'usertype.usertypeID = asset_assignment.usertypeID', 'LEFT');

        if(isset($queryArray['asset_assignmentcustomertypeID']) && $queryArray['asset_assignmentcustomertypeID'] != 0) {
            $this->db->where('asset_assignment.usertypeID', $queryArray['asset_assignmentcustomertypeID']);
        }

        if(isset($queryArray['asset_assignmentcustomerID']) && $queryArray['asset_assignmentcustomerID'] != 0) {
            $this->db->where('asset_assignment.check_out_to', $queryArray['asset_assignmentcustomerID']);
        }

        

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));
            $this->db->where('check_out_date >=', $fromdate);
            $this->db->where('check_out_date <=', $todate);
        } 
        $this->db->order_by('asset_assignment.asset_assignmentID','DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
