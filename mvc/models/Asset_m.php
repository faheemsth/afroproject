<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_m extends MY_Model {

    protected $_table_name = 'asset';
    protected $_primary_key = 'assetID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "assetID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_asset_ref($id){
        $query = $this->db->query("SELECT `serial` FROM `asset` WHERE asset_categoryID = '$id' ORDER BY assetID DESC LIMIT 1");
        if ($query->row() == null) {
            return false;
        }else{
            $ref_result     = $query->row();
            $refrence_no    = $ref_result->serial;
            return $refrence_no;
        }
    }
    public function get_asset_l($id){
        $query = $this->db->query("SELECT `quantity` FROM `purchase` WHERE assetID = '$id' ORDER BY purchaseID DESC LIMIT 1");
        if ($query->row() == null) {
            return false;
        }else{
            $ref_result = $query->row();
            $quantity = $ref_result->quantity;
            return $quantity;
        }
    }

    function get_single_asset($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_numrows($table, $data=NULL){
        $query = $this->db->get_where($table, $data);
        return $query->num_rows();
    }

    function get_asset_with_category_and_location($array=NULL) {
        $this->db->select('asset.*, asset_category.category, location.location');
        $this->db->from('asset');
        $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset.asset_locationID', 'LEFT');
        if($array!=NULL){
             $this->db->where($array);   
        }
        $this->db->order_by("assetID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_asset_with_category_and_location($array=array()) {
        $this->db->select('asset.assetID, asset.serial, asset.description as adescription, asset.manufacturer, asset.brand, asset.asset_number, asset.status, asset.asset_condition, asset.attachment, asset.originalfile, asset.asset_categoryID, asset.asset_locationID, asset.create_date as acreate_date, asset_category.*,  location.location, location.description, location.active as lactive');
        $this->db->from('asset');
        $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset.asset_locationID', 'LEFT');
        $this->db->order_by("assetID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function get_order_by_asset($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset($id){
        parent::delete($id);
    }
}
