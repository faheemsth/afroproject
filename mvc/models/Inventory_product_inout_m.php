<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_product_inout_m extends MY_Model {

    protected $_table_name = 'inventory_product_inout';
    protected $_primary_key = 'inventory_product_inoutID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "inventory_product_inoutID asc";

    function __construct() {
        parent::__construct();
    }

    function get_inventory_product_inout($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_inventory_product_inout($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_inventory_product_inout($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_inventory_product_inout($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_inventory_product_inout($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_inventory_product_inout($id){
        parent::delete($id);
    }
    

    public function insert_batch_inventory_product_inout($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    public function update_inventory_product_inout_by_array($data, $array = NULL) {
        $this->db->set($data);
        $this->db->where($array);
        $this->db->update($this->_table_name);
    }
}
