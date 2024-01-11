<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Result_m extends MY_Model {

	protected $_table_name = 'result';
	protected $_primary_key = 'resultID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "result asc";

	public function __construct() {
		parent::__construct();
	}

	public function get_result($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_result($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_result($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_result($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_result($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_result($id){
		parent::delete($id);
	}
}

/* End of file exam_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/exam_m.php */