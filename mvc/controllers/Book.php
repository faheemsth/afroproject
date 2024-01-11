<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Book extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("book_m");
		$this->load->model('classes_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('book', $language);	
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/uploadjs/imageuploadify.min.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/uploadjs/imageuploadify.min.js'
			)
		);
		
		$classess = $this->classes_m->get_classes();
		$classes = pluck($classess,'classesID','classesID');
		$this->data['classes'] 			= $classess;
		$this->data['classesID'] 		= 0;
		$this->data['subject_code'] 	= '';
		$this->data['book'] 			= '';
		$this->data['accession_number'] = '';
		$this->data['book_publisher'] 	= '';
		$this->data['author'] 			= '';

		$this->load->library("pagination");
            $array = [];

             

            if ($_GET) {

		                $classesID = $_GET["classesID"];
		                if ($classesID != 0) {
							    $array["book.classesID"] = $classesID;
							    $this->data["classesID"] = $classesID; 
		                }

		                $book = $_GET["book"];
		                if ($book != '') {
							    $array["book.book like"] = "%$book%";
							    $this->data["book"] = $book; 
		                }

		                $accession_number = $_GET["accession_number"];
		                if ($accession_number != '') {
							    $array["book.accession_number like"] = "%$accession_number%";
							    $this->data["accession_number"] = $accession_number; 
		                }

		                $subject_code = $_GET["subject_code"];
		                if ($subject_code !=  '') {
							    $array["book.subject_code like"] = "%$subject_code%";
							    $this->data["subject_code"] = $subject_code; 
		                }

		                $book_publisher = $_GET["book_publisher"];
		                if ($book_publisher !=  '') {
							    $array["book.book_publisher like"] = "%$book_publisher%";
							    $this->data["book_publisher"] = $book_publisher; 
		                }
		                $author = $_GET["author"];
		                if ($author !=  '') {
							    $array["book.author like"] = "%$author%";
							    $this->data["author"] = $author; 
		                }
             		}

                $count = $this->book_m->get_book_with_class_pagination_by_array(
                    0,
                    0,
				    "count",
				    $array);
                $this->data["count"] = $count;
                // var_dump($count);
                $returns = $this->paginationCompress("book/", $count, 10);

                $this->data["books"] = $this->book_m->get_book_with_class_pagination_by_array(
			    $returns["page"],
			    $returns["segment"],
			    "data",
			    $array);

			   // echo $this->db->last_query();
			    if ($returns["segment"]>0) {
			    	 $this->data['sr'] = $returns["segment"]+1;
			    }else{
			    	 $this->data['sr'] = 1;
			    }
			     
		// $this->load->library('pagination');
		// if ($_GET) {
		// 	$class = $_POST['classesID'];
		// 	$count = $this->book_m->count_book($class);
		// 	$count = $count->book_count;
  //           $returns = $this->paginationCompress ( "book/", $count, 10 );
  //           $this->data['books'] = $this->book_m->get_orders_by_books($returns["page"], $returns["segment"],$class);

		// }else{
		//     $count = $this->book_m->count_book();
		// 	$count = $count->book_count;
		// 	$returns = $this->paginationCompress ( "book/", $count, 10 );
	 // 		$this->data['books'] = $this->book_m->get_orders_by_books($returns["page"], $returns["segment"]);


		// }
		
		
		$this->data["subview"] = "book/index";
		$this->load->view('_layout_main', $this->data);
	}
    
    public function paginationCompress($link, $count, $perPage = 5) {
        $this->load->library("pagination");

        $config["base_url"] = base_url() . $link;
        // $config['page_query_string'] = TRUE;
        $config["total_rows"] = $count;
        //      $config ['uri_segment'] = SEGMENT;
        $config["uri_segment"] = 2;
        $config["per_page"] = $this->data["siteinfos"]->record_per_page;
        $config["num_links"] = 5;
        $config["full_tag_open"] = '<nav><ul class="pagination">';
        $config["full_tag_close"] = "</ul></nav>";
        $config["first_tag_open"] = '<li class="arrow">';
        $config["first_link"] = "First";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "Previous";
        $config["prev_tag_open"] = '<li class="arrow">';
        $config["prev_tag_close"] = "</li>";
        $config["next_link"] = "Next";
        $config["next_tag_open"] = '<li class="arrow">';
        $config["next_tag_close"] = "</li>";
        $config["cur_tag_open"] = '<li class="active"><a href="#">';
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li>";
        $config["num_tag_close"] = "</li>";
        $config["last_tag_open"] = '<li class="arrow">';
        $config["last_link"] = "Last";
        $config["last_tag_close"] = "</li>";
        $config["reuse_query_string"] = true;

        $this->pagination->initialize($config);
        $page = $config["per_page"];
        $segment = $this->uri->segment(2);

        return [
"page" => $page,
"segment" => $segment,
        ];
    }
	

	protected function rules() {
		$rules = array(
				array(
					'field' => 'accession_number', 
					'label' => $this->lang->line("accession_number"), 
			'rules' => 'trim|required|numeric|max_length[20]|xss_clean|callback_valid_number'
				), 
				array(
					'field' => 'ddc_number', 
					'label' => $this->lang->line("ddc_number"), 
					'rules' => 'trim|required|xss_clean|max_length[20]|callback_unique_book'
				), 
				array(
					'field' => 'subject_code', 
					'label' => $this->lang->line("book_subject_code"), 
					'rules' => 'trim|required|xss_clean|max_length[20]|callback_unique_book'
				), 
				array(
					'field' => 'book', 
					'label' => $this->lang->line("book_name"), 
					'rules' => 'trim|required|xss_clean|max_length[200]|callback_unique_book'
				), 
				array(
					'field' => 'author', 
					'label' => $this->lang->line("book_author"),
					'rules' => 'trim|max_length[200]|xss_clean|callback_unique_book'
				), 
				array(
					'field' => 'book_category', 
					'label' => $this->lang->line("book_category"),
					'rules' => 'trim|required|xss_clean|max_length[100]|callback_unique_book'
				), 
				array(
					'field' => 'book_publisher', 
					'label' => $this->lang->line("book_publisher"),
					'rules' => 'trim|max_length[100]|xss_clean|callback_unique_book'
				),	
				array(
					'field' => 'book_pages', 
					'label' => $this->lang->line("book_pages"),
					'rules' => 'trim|xss_clean|max_length[60]|callback_unique_book'
				), 
				array(
					'field' => 'book_year', 
					'label' => $this->lang->line("book_year"),
					'rules' => 'trim|xss_clean|max_length[60]|callback_unique_book'
				),  
				array(
					'field' => 'book_binding', 
					'label' => $this->lang->line("book_binding"),
					'rules' => 'trim|required|max_length[100]|xss_clean|callback_unique_book'
				),	
				array(
					'field' => 'book_source', 
					'label' => $this->lang->line("book_source"),
					'rules' => 'trim|max_length[100]|xss_clean|callback_unique_book'
				),	
				array(
					'field' => 'price', 
					'label' => $this->lang->line("book_price"),
					'rules' => 'trim|xss_clean|max_length[60]|callback_unique_book'
				),  
				array(
					'field' => 'quantity', 
					'label' => $this->lang->line("book_quantity"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_book'
				), 
				array(
					'field' => 'book_volume', 
					'label' => $this->lang->line("book_volume"),
					'rules' => 'trim|max_length[60]|xss_clean|callback_unique_book'
				),
				array(
					'field' => 'book_edtion', 
					'label' => 'Book Edition',
					'rules' => 'trim|max_length[150]|xss_clean'
				),
				array(
					'field' => 'book_remarks', 
					'label' => $this->lang->line("book_remarks"),
					'rules' => 'trim|max_length[60]|xss_clean|callback_unique_book'
				),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("books_classes"),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
					'field' => 'rack', 
					'label' => $this->lang->line("book_rack_no"), 
					'rules' => 'trim|max_length[60]|xss_clean'
				)
			);
		return $rules;
	}

		public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		} 
		return TRUE;
	}

	public function getBook() {
		$classesID = $this->input->post('bookclassesID');
		
		if((int)$classesID) {
			// $allBooks = $this->book_m->get_orders_by_books($classesID);
			$allBooks = $this->book_m->get_order_by_book(array('classesID' => $classesID));
			// $allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", 'Please Select',"</option>";
			if(customCompute($allBooks)) {
				foreach ($allBooks as $value) {
					echo "<option value=\"$value->bookID\">".$value->book ." (". $value->accession_number .") (". $value->subject_code .")</option>";
				}
			}
		}
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
			'assets/select2/css/select2.css',
			'assets/select2/css/select2-bootstrap.css',
			'assets/uploadjs/imageuploadify.min.css'
			),
			'js' => array(
			'assets/select2/select2.js',
			'assets/uploadjs/imageuploadify.min.js'
			)
		);


		$this->data['classes'] = $this->classes_m->get_classes();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "book/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"accession_number" => $this->input->post("accession_number"),
					"ddc_number" => $this->input->post("ddc_number"),
					"book" => $this->input->post("book"),
					"author" => $this->input->post("author"),
					"subject_code" => $this->input->post("subject_code"),
					"book_remarks" => $this->input->post("book_remarks"),
					"price" => $this->input->post("price"),
					"classesID" => $this->input->post('classesID'),
						// if($this->input->post('authority')) {
						// 	$array['authority'] = $this->input->post('authority');
						// } else {
						// 	$array['authority'] = 0;
						// }
					"quantity" => $this->input->post("quantity"),
					"book_volume" => $this->input->post("book_volume"),
					"book_edtion" => $this->input->post("book_edtion"),
					"book_source" => $this->input->post("book_source"),
					"book_binding" => $this->input->post("book_binding"),
					"book_year" => $this->input->post("book_year"),
					"book_pages" => $this->input->post("book_pages"),
					"book_publisher" => $this->input->post("book_publisher"),
					"book_category" => $this->input->post("book_category"),
					"rack" => $this->input->post("rack"),
					"due_quantity" => 0,
				);
				$this->book_m->insert_book($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("book/index"));
			}
		} else {
			$this->data["subview"] = "book/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/uploadjs/imageuploadify.min.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/uploadjs/imageuploadify.min.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['book'] = $this->book_m->get_book($id);
			$this->data['classes'] = $this->classes_m->get_classes();
			if($this->data['book']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data['form_validation'] = validation_errors(); 
						$this->data["subview"] = "book/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
					"accession_number" => $this->input->post("accession_number"),
					"ddc_number" => $this->input->post("ddc_number"),
					"book" => $this->input->post("book"),
					"author" => $this->input->post("author"),
					"subject_code" => $this->input->post("subject_code"),
					"book_remarks" => $this->input->post("book_remarks"),
					"price" => $this->input->post("price"),
					"classesID" => $this->input->post('classesID'),
					"quantity" => $this->input->post("quantity"),
					"book_volume" => $this->input->post("book_volume"),
					"book_edtion" => $this->input->post("book_edtion"),
					"book_source" => $this->input->post("book_source"),
					"book_binding" => $this->input->post("book_binding"),
					"book_year" => $this->input->post("book_year"),
					"book_pages" => $this->input->post("book_pages"),
					"book_publisher" => $this->input->post("book_publisher"),
					"book_category" => $this->input->post("book_category"),
					"rack" => $this->input->post("rack"),
						);
						$this->book_m->update_book($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("book/index"));
					}
				} else {
					$this->data["subview"] = "book/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$book = $this->book_m->get_book($id);
			if(customCompute($book)) {
				$this->book_m->delete_book($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("book/index"));
			} else {
				redirect(base_url("book/index"));
			}
		} else {
			redirect(base_url("book/index"));
		}
	}

	public function unique_book() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$student = $this->book_m->get_order_by_book(array("book" => $this->input->post("book"), "bookID !=" => $id, "author" => $this->input->post('author'), "subject_code" => $this->input->post('subject_code')));
			if(customCompute($student)) {
				$this->form_validation->set_message("unique_book", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->book_m->get_order_by_book(array("book" => $this->input->post("book"), "author" => $this->input->post('author'), "subject_code" => $this->input->post('subject_code')));

			if(customCompute($student)) {
				$this->form_validation->set_message("unique_book", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	function valid_number() {
		if($this->input->post('price') && $this->input->post('price') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	function valid_number_for_quantity() {
		if($this->input->post('quantity') && $this->input->post('quantity') < 0) {
			$this->form_validation->set_message("valid_number_for_quantity", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}
}

// End of file book.php /
// Location: .//D/xampp/htdocs/school/mvc/controllers/book.php /