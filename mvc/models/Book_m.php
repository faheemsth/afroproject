<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Book_m extends MY_Model {

	protected $_table_name = 'book';
	protected $_primary_key = 'bookID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "subject_code ASC";

	function __construct() {
		parent::__construct();
	}

	function get_book($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}
    
	function get_order_by_book($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}
    
    function get_orders_by_book($page, $segment) {
        $array=NULL;
		$query = parent::get_orders_by($array,$page, $segment);
		return $query;
	}

 	function get_orders_by_books($page, $segment, $class = NULL) {
 		$this->db->select('book.*,classes.classes');
 		$this->db->from($this->_table_name);
 		$this->db->join('classes', 'book.classesID = classes.classesID', 'LEFT');
 		
 		if ($class != NULL) {
 			$this->db->where('book.classesID', $class);	
 		}
 		
 		$this->db->order_by('book.bookID', 'desc');
 		$this->db->limit($page, $segment);
		$query = $this->db->get();
	 	return $query->result();
  //       $array=NULL;
		// $query = parent::get_orders_by($array,$page, $segment);
		// return $query;
	}
 	function get_books_titles($array = NULL) {
 		$this->db->select('count(DISTINCT(book)) as total_titles');
 		$this->db->from($this->_table_name); 
 		
 		if ($array != NULL) {
 			$this->db->where($array);	
 		}
 		 
		$query = $this->db->get();
	 	return $query->row();
  //       $array=NULL;
		// $query = parent::get_orders_by($array,$page, $segment);
		// return $query;
	}


    public function get_book_with_class_pagination_by_array($page, $segment,$type = 'data',$array = array()) {
    	if ($type=='data') {
    		
		$this->db->select('book.*,classes.classes');
    	}else{

		$this->db->select('book.bookID');	
    	}
		$this->db->from($this->_table_name);
		$this->db->join('classes', 'book.classesID = classes.classesID', 'LEFT'); 
		 
 
		if (count($array)) {
			$this->db->where($array);
		}
		
		 
		$this->db->order_by('book.accession_number', 'ASC');


    	if ($type=='data') {
    	
        $this->db->limit($page, $segment);
		$query = $this->db->get();
		// print_r($this->db->last_query());  
		// exit();
		return $query->result();
    	}else{

		$query = $this->db->get();
		 
		return $query->num_rows();
    	}
	}
	
    function count_book($class = NULL) {
    	if ($class != NULL) {
    		$query = $this->db->query("SELECT COUNT(*) as book_count FROM book WHERE classesID = '$class'");
    	}else{
    		$query = $this->db->query("SELECT COUNT(*) as book_count FROM book");
    	}
		
		return $query->row();
	}
	
	function get_single_book($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_book($array) {
		$error = parent::insert($array);
		return TRUE;
	}
	function insert_batch_book($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	function update_book($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_book($id){
		parent::delete($id);
	}

	function allbook($book) {
		$query = $this->db->query("SELECT * FROM book WHERE book LIKE '$book%'");
		return $query->result();
	}

	function allauthor($author) {
		$query = $this->db->query("SELECT * FROM book WHERE author LIKE '$author%'");
		return $query->result();
	}

	public function get_order_by_books_with_authority($classes) {
		$this->db->select('*');
		$this->db->from('ebooks');
		$this->db->where('authority', '0');
		if(customCompute($classes)) {
			foreach ($classes as $classesID) {
				$this->db->or_where('classesID', $classesID);
			}
		}
		$this->db->order_by('name','ASC');
		$query = $this->db->get();
		return $query->result();
	}

}