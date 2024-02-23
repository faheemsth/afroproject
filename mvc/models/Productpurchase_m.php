<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productpurchase_m extends MY_Model {

    protected $_table_name = 'productpurchase';
    protected $_primary_key = 'productpurchaseID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "productpurchase.productpurchaseID desc";

    function __construct() {
        parent::__construct();
    }

    function get_productpurchase($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_productpurchase($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_productpurchase($array=NULL) {
        $this->db->from($this->_table_name)
        ->join('productpurchaseitem', 'productpurchaseitem.productpurchaseID ='.$this->_table_name.'.productpurchaseID')
        ->join('product', 'product.productID = productpurchaseitem.productID')
        ->join('productcategory', 'productcategory.productcategoryID = product.productcategoryID')
        ->where($array)
        ->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    function insert_productpurchase($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_productpurchase($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_productpurchase($id){
        parent::delete($id);
    }


    public function get_all_productpurchase_for_report($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->db->select('*');
        $this->db->from('productpurchase');
        $this->db->join('productpurchaseitem', 'productpurchase.productpurchaseID = productpurchaseitem.productpurchaseID');

        if(isset($queryArray['productsupplierID']) && $queryArray['productsupplierID'] != 0) {
            $this->db->where('productpurchase.productsupplierID', $queryArray['productsupplierID']);
        }

        if(isset($queryArray['productwarehouseID']) && $queryArray['productwarehouseID'] != 0) {
            $this->db->where('productpurchase.productwarehouseID', $queryArray['productwarehouseID']);
        }

        if(isset($queryArray['reference_no']) && !empty($queryArray['reference_no'])) {
            $this->db->where('productpurchase.productpurchasereferenceno', $queryArray['reference_no']);
        }

        if(isset($queryArray['statusID']) && $queryArray['statusID'] != 0) {
            if($queryArray['statusID'] == 1) {
                $this->db->where('productpurchase.productpurchasestatus', 0);
                $this->db->where('productpurchase.productpurchaserefund', 0);
            } elseif($queryArray['statusID'] == 2) {
                $this->db->where('productpurchase.productpurchasestatus', 1);
                $this->db->where('productpurchase.productpurchaserefund', 0);
            } elseif($queryArray['statusID'] == 3) {
                $this->db->where('productpurchase.productpurchasestatus', 2);
                $this->db->where('productpurchase.productpurchaserefund', 0);
            } elseif($queryArray['statusID'] == 4) {
                $this->db->where('productpurchase.productpurchaserefund', 1);
            }
        } else {
            $this->db->where('productpurchase.productpurchaserefund', 0);
        }

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));
            $this->db->where('productpurchasedate >=', $fromdate);
            $this->db->where('productpurchasedate <=', $todate);
        }

        $this->db->where('productpurchase.schoolyearID',$schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }



    public function get_average_price_by_productID($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->db->select('SUM(productpurchasequantity) as total_quatity,SUM(productpurchasequantity*productpurchaseunitprice) as total_price ');
        $this->db->from('productpurchaseitem');
         
        if(count($queryArray)) {
            $this->db->where($queryArray);
        }
        $query = $this->db->get();
        $rec = $query->row();
         
        if (is_null($rec->total_price) ) {
            return 0;
        }else{
            $av_price   =   ceil($rec->total_price/$rec->total_quatity);
            return $av_price;
        }
        
    }

}
