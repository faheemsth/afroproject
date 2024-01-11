<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentsubjects_m extends MY_Model {

    protected $_table_name = 'studentsubjects';
    protected $_primary_key = 'studentsubjectsID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "studentsubjectsID asc";

    function __construct() {
        parent::__construct();
    }

    function get_studentsubjects($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }


    public function get_subject_join_enrollment_by_array($array=NULL){
        $this->db->select('*');
        $this->db->from('studentsubjects');
        $this->db->join('subject', 'subject.subjectID = studentsubjects.subjectID', 'LEFT'); 
        if ($array!=NULL) {
            $this->db->where($array);
        }
        //$this->db->order_by("subjectenrollmentID", "DESC");
        //$this->db->limit(2000);
        $query = $this->db->get();
        return $query->result();
    }

    
    function get_single_studentsubjects($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_studentsubjects($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_studentsubjects($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_studentsubjects($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_studentsubjects($id){
        parent::delete($id);
    }
    

    public function insert_batch_studentsubjects($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    public function update_studentsubjects_by_array($data, $array = NULL) {
        $this->db->set($data);
        $this->db->where($array);
        $this->db->update($this->_table_name);
    }
}
