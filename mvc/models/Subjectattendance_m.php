<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjectattendance_m extends MY_Model {

	protected $_table_name = 'sub_attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	public function get_sub_attendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_sub_attendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_subject_by_teacher_wherein_subjectID_array($array=NULL){
		 
		$this->db->select("*");
		$this->db->from('sub_attendance'); 
		$this->db->join('subjectteacher', 'subjectteacher.subjectID = sub_attendance.subjectID', 'INNER');
		if (count($array)) {
 

			if (isset($array['subjectID'])) {
				 $this->db->where_in('sub_attendance. subjectID', $array['subjectID']);
					unset($array['subjectID']);
			}
			 


			
			$this->db->where($array);
		}
		$query = $this->db->get();
		
		return $query->result();
	}

	public function get_subject_wherein_subjectID_array($array=NULL){
		 
		$this->db->select("*");
		$this->db->from('sub_attendance'); 
		if (count($array)) {
 

			if (isset($array['subjectID'])) {
				 $this->db->where_in('subjectID', $array['subjectID']);
					unset($array['subjectID']);
			}
		
			$this->db->where($array);
		}
		$query = $this->db->get();
		
		return $query->result();
	}


	public function get_student_subject_attendance($array){
		$this->db->select("*");
		$this->db->from('sub_attendance'); 
		$this->db->join('student', 'sub_attendance.studentID = student.studentID', 'INNER');
		if (count($array)) {			
			$this->db->where($array);
		}
		$query = $this->db->get();
		
		return $query->result();	
	}
	

	public function insert_sub_attendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_sub_attendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_sub_attendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_sub_attendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_sub_attendance($id){
		parent::delete($id);
	}
}