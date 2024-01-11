<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_m extends MY_Model 
{

    protected $_table_name          = 'courses';
    protected $_primary_key         = 'salary_templateID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "salary_templateID asc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_salary_template($array=null, $signal=false) 
    {
        return parent::get($array, $signal);

    }

    public function get_single_salary_template($array) 
    {
        // return parent::get_single($array);
        $this->db->select('*');
		$this->db->from($this->_table_name);
        $this->db->join('classes', 'classes.classesID = courses.salary_grades', 'LEFT');
        $this->db->where($array);
		$query = $this->db->get();
		return $query->row();
// 		return $query->result();
    }

    public function get_order_by_salary_template($array=null) 
    {
        // return parent::get_order_by($array);
        $this->db->select('*');
		$this->db->from($this->_table_name);
        $this->db->join('classes', 'classes.classesID = courses.salary_grades', 'LEFT');		
        if ($array!=null) {
            $this->db->where($array);             
        }
        $query = $this->db->get();
		return $query->result();
    }

    public function get_distinct_year() 
    {
        // return parent::get_order_by($array);
        $this->db->select(' DISTINCT(basic_salary)');
        $this->db->from($this->_table_name);        
         
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_salary_template($array) 
    {
        return parent::insert($array);
    }

    public function update_salary_template($data, $id = null) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_salary_template($id)
    {
        parent::delete($id);
    }
}
