<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teacherstudent_m.php';
require_once 'Studentparentstudent_m.php';

class student_m extends MY_Model {

	protected $_table_name = 'student';
	protected $_primary_key = 'student.studentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "roll asc";

	function __construct() {
		parent::__construct();
	}

	public function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_numrows($table, $data=NULL){
		$query = $this->db->get_where($table, $data);
		return $query->num_rows();
	}
	
	public function get_student_invoicecount($array=[]) {
		
			$select = 'COUNT(invoiceID) as totalcount ';
		

		$this->db->select($select);
		$this->db->from('invoice');

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->row();
	}
	
	public function get_single_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->row();
	}

	function get_class($id=NULL) {
		$class = new Classes_m;
	    return $class->get_classes($id);
	}

	function get_classes() {
	    $class = new Classes_m;
	    return $class->get_order_by_classes();
	}


	public function general_get_student($array=NULL, $signal=FALSE) {
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_student($array=NULL) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_student($array) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_where_in_student($array, $key = NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function get_student($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_teacher_student($id, $single);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_studentparent_student($id, $single);
		} else {
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function get_single_student($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_single_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_single_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_order_by_student_by_teacherwise($array=[]) {

			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);

			$this->db->select("*");
			$this->db->from('student'); 
	        //$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		    $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'INNER JOIN');
			if (count($array)) {

				// if (isset($array['student.classesID'])) {
				// 	    $this->db->where_in('student.classesID', implode(',', $array['student.classesID']));
				// 		unset($array['student.classesID']);
				// }

				// if (isset($array['student.sectionID'])) {
				// 	$this->db->where_in('student.sectionID', implode(',', $array['student.sectionID']));
				// 	unset($array['student.sectionID']);
				// }

				if (isset($array['student.classesID'])) {
					$classesIDs = $array['student.classesID'];
					foreach ($classesIDs as $classesID) {
						$this->db->or_where('student.classesID', $classesID);
					}
					unset($array['student.classesID']);
				}
				
				if (isset($array['student.sectionID'])) {
					$this->db->where('student.sectionID', $array['student.sectionID'][0]);
					$sectionIDs = $array['student.sectionID'];
					foreach ($sectionIDs as $key => $sectionID) {
						if($key == 0)
						continue;
						$this->db->or_where('student.sectionID', $sectionID);
					}
					unset($array['student.sectionID']);
				}



				$this->db->where($array);
			}

			$query = $this->db->get();
			return $query->result();

		// $this->db->select("student.*");
		// $this->db->from('student'); 
		// $this->db->join('subjectenrollment', 'subjectenrollment.studentID = student.studentID', 'LEFT');
		// $this->db->join('studentsubjects', 'studentsubjects.subjectenrollmentID = subjectenrollment.subjectenrollmentID', 'LEFT');
		// $this->db->join('subject', 'subject.subjectID = studentsubjects.subjectID', 'LEFT');
		// $this->db->join('subjectteacher', 'subjectteacher.subjectID = subject.subjectID', 'LEFT');
		// if (count($array)) {
		// 	if (isset($array['subjectID'])) {
		// 		 $this->db->where_in('subject.subjectID', $array['subjectID']);
		// 			unset($array['subjectID']);
		// 	}
		// 	$this->db->where($array);
		// }
		// $query = $this->db->get();
		// return $query->result();
	}

	public function get_order_by_student($array=[], $actives = []) {
        $usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_order_by_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_order_by_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
	        if (!empty( $actives)) {
				$this->db->where_in('student.active', $actives);
			}
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function get_student_all($select = NULL){
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}
		$this->db->select($select);
		$this->db->from($this->_table_name);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_student_all_join_by_array($select = NULL,$array=NULL){
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}
		$this->db->select($select);
		$this->db->from('student');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		if (count($array)) {
 

			if (isset($array['student.classesID'])) {
				 $this->db->where_in('student.classesID', $array['student.classesID']);
					unset($array['student.classesID']);
			}
			if (isset($array['section.numric_code'])) {
				$this->db->where_in('section.numric_code', $array['section.numric_code']);
					unset($array['section.numric_code']); 
			}

			
        	if (isset($array['student.active'])) {
        	 	 
	        	if (is_array($array['student.active'])) {
	        		$this->db->group_start(); 
	        		foreach ($array['student.active'] as $ac) {
	        			
	        			$this->db->or_where('active',$ac); 
	        		}
	        		$this->db->group_end(); 
	        		      
	        		unset($array['student.active']);
	        	} 
        	}
			

			
			$this->db->where($array);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_student_select($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->row();
	}
	
	public function check_section_alum($array=[]){
		$this->db->select('sectionID');
		$this->db->from('section');
		$this->db->where($array);
		$query = $this->db->get();
		$result = $query->row();
		return ($query->row() == 1)? true : false;
	}

	public function get_select_student($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_student($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function insert_parent($array) {
		$this->db->insert('parent', $array);
		return TRUE;
	}

	public function update_student($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_student_classes($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update($this->_table_name);
	}

	public function delete_student($id){
		parent::delete($id);
	}

	public function delete_parent($id){
		$this->db->delete('parent', array('studentID' => $id));
	}

	public function hash($string) {
		return parent::hash($string);
	}

	public function profileUpdate($table, $data, $username) {
		$this->db->update($table, $data, "username = '".$username."'");
		return TRUE;
	}

	public function profileRelationUpdate($table, $data, $studentID, $schoolyearID) {
		$this->db->update($table, $data, "srstudentID = '".$studentID."' AND srschoolyearID = '".$schoolyearID."'");
		return TRUE;
	}

	/* Start For Promotion */
	public function get_order_by_student_year($classesID) {
		$query = $this->db->query("SELECT * FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->result();
	}

	public function get_order_by_student_single_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}

	public function get_order_by_student_single_max_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MAX(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}
	/* End For Promotion */


	/* Start For Report */
	public function get_order_by_student_with_section($classesID, $schoolyearID, $sectionID=NULL) {
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('classes', 'student.classesID = classes.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->where('student.classesID', $classesID);
		$this->db->where('student.schoolyearID', $schoolyearID);
		if($sectionID != NULL) {
			$this->db->where('student.sectionID', $sectionID);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/* End For Report */

	public function get_max_student() {
		$query = $this->db->query("SELECT * FROM $this->_table_name WHERE studentID = (SELECT MAX(studentID) FROM $this->_table_name)");
		return $query->row();
	}
}