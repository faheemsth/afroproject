<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Teachersubject_m.php";
require_once "Studentparentsubject_m.php";

class Subject_m extends MY_Model {
	protected $_table_name = 'subject';
	protected $_primary_key = 'subjectID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_subjects_by_teacher($teacher_id, $section_ids){
		$this->db->select(['subject.subjectID', 'subject.subject']);
		$this->db->from('subject');
		$this->db->join('subjectteacher', 'subject.subjectID = subjectteacher.subjectID', 'INNER');
		//$this->db->join('teacher', 'teacher.teacherID = subjectteacher.teacherID', 'INNER');
	    $this->db->join('section', 'subject.sectionID = section.sectionID', 'INNER');
		$this->db->where('subjectteacher.teacherID', $teacher_id);
		$this->db->where_in('section.numric_code', $section_ids);
		$this->db->group_by('subject.subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_join_subject($id) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_subject_with_class($id);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_subject_with_class($id);
		} else {
			$this->db->select('subject.*, classes.classesID, classes.classes, classes.classes_numeric, classes.studentmaxID, classes.note');
			$this->db->from('subject');
			$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
			$this->db->where('subject.classesID', $id);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_subject_bystudent_wherein_subjectID_array($array=NULL){
		 
		$this->db->select("subject.subjectID, subject.subject");
		$this->db->from('subject'); 
		$this->db->join('studentsubjects', 'studentsubjects.subjectID = subject.subjectID', 'INNER');
		$this->db->join('subjectenrollment', 'subjectenrollment.subjectenrollmentID = studentsubjects.subjectenrollmentID', 'INNER');
		$this->db->join('student', 'student.studentID = subjectenrollment.studentID', 'INNER');
		if (count($array)) {			
			$this->db->where($array);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_subject_byteacher_wherein_subjectID_array($array=NULL){
		 
		$this->db->select("*");
		$this->db->from('subject'); 
		$this->db->join('subjectteacher', 'subjectteacher.subjectID = subject.subjectID', 'INNER');
		if (count($array)) {
 
			if (isset($array['subjectID'])) {
				 $this->db->where_in('subject.subjectID', $array['subjectID']);
					unset($array['subjectID']);
			}
			
			$this->db->where($array);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_subject_wherein_subjectID_array($array=NULL){
		 
		$this->db->select("*");
		$this->db->from('subject'); 
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

	public function general_get_subject($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_single_subject($array) {
        $query = parent::get_single($array);
        return $query;
    }
    
	public function general_get_order_by_subject($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_subject($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_teacher_subject($id, $single);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_studentparent_subject($id, $single);
		} else {
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function get_single_subject($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_single_teacher_subject($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_single_studentparent_subject($array);
		} else {
			$query = parent::get_single($array);
        	return $query;
		}
    }
    
	public function get_order_by_subject($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_order_by_teacher_subject($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_order_by_studentparent_subject($array);
		} else {
			$query = parent::get_order_by($array);
        	return $query;
		}
	}

	public function insert_subject($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_subject($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_subject($id){
		parent::delete($id);
	}
}