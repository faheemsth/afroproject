<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class markdistribution_m extends MY_Model {

	protected $_table_name = 'mark_distribution';
	protected $_primary_key = 'MarkDistirbutionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "MarkDistirbutionID asc";

	function __construct() {
		parent::__construct();
	}

    public function change_order($column, $sort) {
        $this->_order_by    = $column.' '.$sort;
    }

	public function get_markdistribution($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_markdistribution($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_markdistribution($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_markdistribution($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_markdistribution($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_markdistribution($id){
		parent::delete($id);
	}
}

/* End of file category_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/category_m.php */