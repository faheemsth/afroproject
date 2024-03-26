<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teachersection_m.php';

class Section_m extends MY_Model {

	protected $_table_name = 'section';
	protected $_primary_key = 'sectionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "section asc";

	function __construct() {
		parent::__construct();
	}

	public function get_join_section($id) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherSection = new Teachersection_m();
			return $teacherSection->get_teacher_with_section($id);
		} else {
			$this->db->select('*');
			$this->db->from('section');
			$this->db->join('teacher', 'section.teacherID = teacher.teacherID', 'LEFT');
			$this->db->where('section.classesID', $id);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_section_select($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'sectionID, section, category';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		// print_r($this->db->last_query());  
		// exit();
		return $query->row();
	}


	public function general_get_section($id=NULL, $single=FALSE) {
		$query = parent::get($id, $single);
		return $query;
	}

	public function general_get_single_section($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_order_by_section($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}


	public function get_section($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherSection = new Teachersection_m();
			return $teacherSection->get_teacher_section($id, $single);
		} else {
			$query = parent::get($id, $single);
			return $query;
		}
	}
	
	public function get_single_section($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherSection = new Teachersection_m();
			return $teacherSection->get_single_teacher_section($array);
		} else {
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_order_by_section($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherSection = new Teachersection_m();
			return $teacherSection->get_order_by_teacher_section($array);
		} else {
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function insert_section($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_section($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_section($id){
		parent::delete($id);
	}

	public function get_section_by_numeric_code($classID, $numeric_codes = []){
		$this->db->select('*');
		$this->db->from('section');
		$this->db->where('classesID', $classID);
		if(!empty($numeric_codes)){
			$this->db->where_in('numric_code', $numeric_codes);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
}

