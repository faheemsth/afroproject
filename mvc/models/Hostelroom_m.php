<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hostelroom_m extends MY_Model {

    protected $_table_name = 'hostelroom';
    protected $_primary_key = 'hostelroomID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "hostelroomID asc";

    function __construct() {
        parent::__construct();
    }

    function get_hostelroom($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_hostelroom($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_hostelroom($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_hostelroom($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_hostelroom($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_hostelroom($id){
        parent::delete($id);
    }
    

    public function insert_batch_hostelroom($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    public function update_hostelroom_by_array($data, $array = NULL) {
        $this->db->set($data);
        $this->db->where($array);
        $this->db->update($this->_table_name);
    }
}
