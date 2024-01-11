<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisitionitem_m extends MY_Model {

	protected $_table_name = 'requisitionitem';
	protected $_primary_key = 'requisitionitemID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "requisitionitemID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_requisitionitem($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_requisitionitem($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_requisitionitem($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_requisitionitem($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_requisitionitem($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_requisitionitem($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_requisitionitem($id){
		parent::delete($id);
	}

	public function delete_requisitionitem_by_requisitionID($id) {
		$this->db->delete($this->_table_name, array('requisitionID' => $id)); 
		return TRUE;
	}

	public function get_requisitionitem_sum($array) {
		if(isset($array['requisitionID']) && isset($array['schoolyearID'])) {
			$string = "SELECT SUM(requisitionunitprice), SUM(requisitionquantity), SUM(requisitionunitprice*requisitionquantity) AS result FROM ".$this->_table_name." WHERE requisitionID = '".$array['requisitionID']."' && schoolyearID = '".$array['schoolyearID']."'";
		} else {
			$string = "SELECT SUM(requisitionunitprice), SUM(requisitionquantity), SUM(requisitionunitprice*requisitionquantity) AS result FROM ".$this->_table_name;
		}

		$query = $this->db->query($string);
		return $query->row();
	}

	public function get_requisitionitem_quantity() {
		$string = 'SELECT SUM(requisitionitem.requisitionquantity) AS quantity, requisitionitem.productID AS productID FROM requisitionitem LEFT JOIN requisition on requisition.requisitionID = requisitionitem.requisitionID WHERE requisitionrefund = 0 GROUP BY requisitionitem.productID';
		$query = $this->db->query($string);
		return $query->result();
	}
}