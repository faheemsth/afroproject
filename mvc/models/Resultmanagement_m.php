<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resultmanagement_m extends MY_Model {

    protected $_table_name      = 'resultmanagement';
    protected $_primary_key     = 'resultmanagementID';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = "resultmanagementID DESC";

    function __construct() {
        parent::__construct();
    }

    function get_resultmanagement($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_resultmanagement($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_resultmanagement($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_resultmanagement($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_resultmanagement($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_resultmanagement($id){
        parent::delete($id);
    }

    public function insert_batch_resultmanagement($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    public function update_resultmanagement_by_array($data, $array = NULL) {
        $this->db->set($data);
        $this->db->where($array);
        $this->db->update($this->_table_name);
    }
}
