<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentmeta_m extends MY_Model {

    protected $_table_name = 'studentmeta';
    protected $_primary_key = 'studentmetaID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "studentmetaID asc";

    function __construct() {
        parent::__construct();
    }

    function get_studentmeta($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_studentmeta($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_studentmeta($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_studentmeta($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_studentmeta($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_studentmeta($id){
        parent::delete($id);
    }
}
