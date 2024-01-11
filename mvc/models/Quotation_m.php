<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation_m extends MY_Model {


    protected $_table_name = 'quotation';
    protected $_primary_key = 'QuotationID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "unit_price asc";

    function __construct() {
        parent::__construct();
    }


    function get_order_by_product_quotation($array=NULL){
        $this->_order_by = "unit_price asc";
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_quotation($array){
        $id = parent::insert($array);
        return $id;
    }

}
