<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Issue extends Admin_Controller {
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
		$this->load->model("lmember_m");
		$this->load->model("book_m");
		$this->load->model("issue_m");
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model("parents_m");
		$this->load->model('invoice_m');
		$this->load->model('feetypes_m');
		$this->load->model("maininvoice_m");
		$this->load->model("section_m");
		
		$language = $this->session->userdata('lang');
		$this->lang->load('issue', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("issue_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("issue_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("issue_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'id',
				'label' => $this->lang->line("issue_issueID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID = $this->session->userdata("loginuserID");			
			$student = $this->studentrelation_m->get_single_student(array("srstudentID" => $studentID, 'srschoolyearID' => $schoolyearID));
			if(customCompute($student) && $student->library === '1') {
				$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $student->studentID));
				$lID = $lmember->lID;
				$this->data['libraryID'] = $lID;

				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['libraryID'] = 0;
				$this->data['issues'] = [];
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$parentID = $this->session->userdata("loginuserID");
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$parent = $this->parents_m->get_single_parents(array('parentsID' => $parentID));
			if(customCompute($parent)) {
				$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('parentID' => $parent->parentsID, 'srschoolyearID' => $schoolyearID));
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$this->data['set'] = $id;
					$checkstudent = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($checkstudent)) {
						if($checkstudent->library === '1' && 0 == 1) {
							$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $checkstudent->studentID));
							$lID = $lmember->lID;
							$this->data['libraryID'] = $lID;

							$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
							$this->data["subview"] = "issue/index";
							$this->load->view('_layout_main', $this->data);
						} else {
							$this->data['libraryID'] = 0;
							$this->data['issues'] = [];
							$this->data["subview"] = "issue/index";
							$this->load->view('_layout_main', $this->data);
						}
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data['set'] = 0;
					$this->data['issues'] = [];
					$this->data["subview"] = "issue/search_parent";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$ulID = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->input->post("lid")));
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js'
				)
			);
			if($lID != "" || !empty($lID)) {
				redirect(base_url('issue/index/'.$lID));
			} elseif($ulID) {
				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $ulID));
				$this->data['libraryID'] = $ulID;
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['issues'] = $this->issue_m->get_issue_with_books();
				$this->data['libraryID'] = NULL;
				// $this->data["subview"] = "issue/search";
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	protected function rules() {
		$rules = array(
			// array(
			// 	'field' => 'lid', 
			// 	'label' => $this->lang->line("issue_lid"), 
			// 	'rules' => 'trim|required|xss_clean|max_length[40]|callback_unique_lID'
			// ), 
			array(
				'field' => 'book', 
				'label' => $this->lang->line("issue_book"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_book_call|callback_unique_quantity|callback_unique_book'
			), 
			array(
				'field' => 'author', 
				'label' => $this->lang->line("issue_author"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'subject_code', 
				'label' => $this->lang->line("issue_subject_code"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'serial_no', 
				'label' => $this->lang->line("issue_serial_no"),
				'rules' => 'trim|required|xss_clean|max_length[40]'
			),
			array(
				'field' => 'due_date', 
				'label' => $this->lang->line("issue_due_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_wrong_date'
			),
			array(
				'field' => 'note', 
				'label' => $this->lang->line("issue_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}


	protected function edit_rules() {
		$rules = array(
			 
			array(
				'field' => 'serial_no', 
				'label' => $this->lang->line("issue_serial_no"),
				'rules' => 'trim|required|xss_clean|max_length[40]'
			),
			array(
				'field' => 'due_date', 
				'label' => $this->lang->line("issue_due_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_wrong_date'
			),
			array(
				'field' => 'note', 
				'label' => $this->lang->line("issue_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['showClass'] = FALSE;
	        $this->data['sendClasses'] = array();
	        $this->data['checkOutToUesrs'] = array();
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js'
				)
			);
			$this->data['serial_no'] = $this->issue_m->get_issue_lastID_by_add();
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['books'] = $this->book_m->get_book();
			$this->data['usertypes'] = $this->usertype_m->get_usertype();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "issue/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$usertypeID = $this->input->post("usertypeID");
					
					if($usertypeID == 3){

						$check_out_to = $this->input->post("check_out_to");
						// $lmember = $this->lmember_m->get_lmember();
						$lmember = $this->lmember_m->get_single_lmember(array("studentID" => $check_out_to,"usertypeID" => $usertypeID));

						if(!customCompute($lmember)){

							$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $check_out_to, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
							$lastid = $this->lmember_m->get_lmember_lastID();

							if(customCompute($student)){
								 
									$lID = $lastid->lID+1;
								 
								
								$lID.'Library ID';
								$array = array(
									"lID" 			=> $this->input->post("check_out_to").'-'.$usertypeID,
									"usertypeID" 	=> $usertypeID,
									"studentID" 	=> $student->studentID,
									"name" 			=> $student->name,
									"email" 		=> $student->email,
									"phone" 		=> $student->phone,
									"lbalance" 		=> 0,
									"ljoindate" 	=> date("Y-m-d")
								); 
								$this->lmember_m->insert_lmember($array);
								$this->student_m->update_student(array("library" => 1), $student->studentID);
							}
						}else{
							 $lID = $lmember->lID; 
						} 
					}else{
						 


						$check_out_to = $this->input->post("check_out_to");
						// $lmember = $this->lmember_m->get_lmember();
						$lmember = $this->lmember_m->get_single_lmember(
							array(
								"userID" 		=> $check_out_to,
								"usertypeID" 	=> $this->input->post("usertypeID")
								));
						 
						if(!customCompute($lmember)){
							 
							$userobject = getObjectByUserTypeIDAndUserID( 
								$this->input->post("usertypeID"), $check_out_to );
							$lastid = $this->lmember_m->get_lmember_lastID();
							if(customCompute($userobject)){
								if(customCompute($lmember)){
									$lID = $lastid->lID+1;
								}else{
									$data = date('Y');
									$lID = $data.'01';
								}

								$pr_key 	=	key($userobject);
								 
								
								$lID.'Library ID';
								$array = array(
									"lID" => $this->input->post("check_out_to").'-'.$usertypeID,
									"userID" => $userobject->$pr_key,
									"usertypeID" => $this->input->post("usertypeID"),
									"studentID" => $userobject->$pr_key,
									"name" => $userobject->name,
									"email" => $userobject->email,
									"phone" => $userobject->phone,
									"lbalance" => 0,
									"ljoindate" => date("Y-m-d")
								);
								 
								$this->lmember_m->insert_lmember($array);
								 
							}
						}else{
							 
							 $lID = $lmember->lID;
							 
						}
						 
					
					}

				$array = array(
					"lID" 				=> $this->input->post("check_out_to").'-'.$usertypeID,
					"bookID" 			=> $this->input->post("book"),
					"serial_no" 		=> $this->input->post("serial_no"),
					"author" 			=> $this->input->post("author"),
					"subject_code" 		=> $this->input->post("subject_code"),
					"accession_number" 	=> $this->input->post("accession_number"),
					"usertypeID" 		=> $this->input->post("usertypeID"),
					"classesID" 		=> $this->input->post("classesID"),
					"sectionID" 		=> $this->input->post("sectionID"),
					"check_out_to" 		=> $this->input->post("check_out_to"),
					"bookclassesID" 	=> $this->input->post("bookclassesID"),
					"issue_date" 		=> date("Y-m-d", strtotime($this->input->post("issue_date"))),
					"due_date" 			=> date("Y-m-d", strtotime($this->input->post("due_date"))),
					"note" 				=> $this->input->post("note")
				);
					// echo '<pre>';
					// print_r($array);
					// echo '</pre>';
					// exit();
					$quantity = $this->book_m->get_single_book(array("bookID" => $this->input->post("book")));
					$allDueQuantity = ($quantity->due_quantity)+1;

					$this->book_m->update_book(array("due_quantity" => $allDueQuantity), $this->input->post("book"));
					$this->issue_m->insert_issue($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("issue/index"));
				}
			} else {
				$this->data["subview"] = "issue/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js'
				)
			);
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->uri->segment(4)));

	        $this->data['sendClasses'] = array();
	        $this->data['checkOutToUesrs'] = array();
			$this->data['books'] = $this->book_m->get_book();
			$this->data['classes'] = $this->classes_m->get_classes();  
			$this->data['usertypes'] = $this->usertype_m->get_usertype();
			if((int)$id && $lID) {
				$this->data['issue'] = $this->issue_m->get_issue_by_array(array('issueID' => $id), TRUE);
				$dbGet_bookID = $this->data['issue']->bookID;
				$this->data['bookinfo'] = $this->book_m->get_book($dbGet_bookID);

			$this->data['sections'] =    $this->section_m->general_get_order_by_section(array('classesID' => $this->data['issue']->classesID));

				if(customCompute($this->data['issue'])) {
					$this->data['set'] = $lID;
					if($_POST) {
						$rules = $this->edit_rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "issue/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {
							 
							 
					$array = array( 
					"serial_no" 		=> $this->input->post("serial_no"), 
					"due_date" 			=> date("Y-m-d", strtotime($this->input->post("due_date"))),
					"note" 				=> $this->input->post("note")
				);

							$this->issue_m->update_issue($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							if($this->session->userdata('usertypeID') == 4) {
								$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['issue']->lID));
								redirect(base_url("issue/index/$lmember->studentID"));
							} else {
								redirect(base_url("issue/index/$lID"));
							}
						}
					} else {
						$this->data["subview"] = "issue/edit";
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function get_issue_data() {

		$id =	 $this->input->post('issueID');
		$this->data['issue'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
		 
		$this->load->view('issue/get_issue_data', $this->data);
	}
	public function get_issue_history() {

		$usertypeID 	=	 $this->input->post('usertypeID');
		$userID 		=	 $this->input->post('userID');
		$issues = $this->issue_m->get_issue_with_books(array('usertypeID' => $usertypeID,'check_out_to' => $userID), FALSE);
		 
		 
		?>

                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th>
                                        <th><?=$this->lang->line('issue_book')?></th>
                                        <th><?=$this->lang->line('issue_serial_no')?></th>
                                        <th><?=$this->lang->line('issue_issue_date')?></th>
                                        <th><?=$this->lang->line('issue_due_date')?></th>
                                        <th><?=$this->lang->line('issue_status')?></th>
                                        

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(customCompute($issues)) {$i = 1; foreach($issues as $issue) { 

                                        if($issue->return_date == "" || empty($issue->return_date)) {
                                    ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <?php echo $i; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_book')?>">
                                                <?php echo $issue->book; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_serial_no')?>">
                                                <?php echo $issue->serial_no; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_issue_date')?>">
                                                <?php echo date("d M Y", strtotime($issue->issue_date)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_due_date')?>">
                                                <?php echo date("d M Y", strtotime($issue->due_date)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_status')?>">
                                                <?php
                                                    $date = date("Y-m-d");
                                                    if(strtotime($date) > strtotime($issue->due_date)) {
                                                        echo '<button class="btn btn-xs btn-danger">';
                                                        echo $this->lang->line('issue_overdue');
                                                        echo '</button>';
                                                    }
                                                ?>  
                                            </td>
                                             
                                        </tr>
                                    <?php $i++; }}} ?>
                                </tbody>
                            </table>
		<?php
	}

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
			if(customCompute($this->data['book'])) {
				$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
				if(customCompute($lmember)) {
					$this->data['lmember'] = $lmember;
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
					if(!customCompute($this->data['student'])) {
						$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
						if(customCompute($this->data['student'])) {
							$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
						}
					}

					if(customCompute($this->data['student'])) {
						$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['book']->usertypeID));
						$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
						$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
						$usertypeID = $this->session->userdata('usertypeID');
						$userID = $this->session->userdata('loginuserID');
						if($usertypeID == 3) {
							if($this->data['student']->studentID == $userID) {
								if($this->data['book']->return_date == NULL) {
									$this->data["subview"] = "issue/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
						} elseif($usertypeID == 4) {
							if($this->data['student']->parentID == $userID) {
								if($this->data['book']->return_date == NULL) {
									$this->data["subview"] = "issue/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
						} else {
							if($this->data['book']->return_date == NULL) {
								$this->data["subview"] = "issue/view";
								$this->load->view('_layout_main', $this->data);
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
						}
					} else {
						$this->data["subview"] = "error";
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function print_preview() {
		if(permissionChecker('issue_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
				if($this->data['book']) {
					$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
					if(customCompute($lmember)) {
						$this->data['lmember'] = $lmember;
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
						if(!customCompute($this->data['student'])) {
							$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
							if(customCompute($this->data['student'])) {
								$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
							}
						}

						if(customCompute($this->data['student'])) {
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
							$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
							$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));

							$usertypeID = $this->session->userdata('usertypeID');
							$userID = $this->session->userdata('loginuserID');
							if($usertypeID == 3) {
								if($this->data['student']->studentID == $userID) {
									if($this->data['book']->return_date == NULL) {
										$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} elseif($usertypeID == 4) {
								if($this->data['student']->parentID == $userID) {
									if($this->data['book']->return_date == NULL) {
										$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								if($this->data['book']->return_date == NULL) {
									$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							}
						} else {
							$this->data["subview"] = "error";
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
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('issue_view')) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$id = $this->input->post('id');
					if((int)$id) {
						$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
						if($this->data['book']) {
							$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
							if(customCompute($lmember)) {
								$this->data['lmember'] = $lmember;

								$schoolyearID = $this->session->userdata('defaultschoolyearID');
								$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
								if(!customCompute($this->data['student'])) {
									$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
									if(customCompute($this->data['student'])) {
										$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
									}
								}

								if(customCompute($this->data['student'])) {
									$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
									$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
									$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
									if($this->data['book']->return_date == NULL) {
										$email = $this->input->post('to');
										$subject = $this->input->post('subject');
										$message = $this->input->post('message');
										$this->reportSendToMail('issuemodule.css',$this->data, 'issue/print_preview', $email, $subject, $message);
										$retArray['message'] = "Message";
										$retArray['status'] = TRUE;
										echo json_encode($retArray);
									    exit;
									} else {
										$retArray['message'] = $this->lang->line('issue_data_not_found');
										echo json_encode($retArray);
										exit;
									}
								} else {
									$retArray['message'] = $this->lang->line('issue_data_not_found');
									echo json_encode($retArray);
									exit;
								}
							} else {
								$retArray['message'] = $this->lang->line('issue_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('issue_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('issue_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('issue_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('issue_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function returnbook() {

		 
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->uri->segment(4)));
			if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
				if((int)$id && $lID) {
					
					$date = date("Y-m-d");
					$issue = $this->issue_m->get_issue($id);
					if(customCompute($issue)) {
						$dbGet_bookID = $issue->bookID;
						$book = $this->book_m->get_book($dbGet_bookID);
						$due_quantity = ($book->due_quantity-1);
						$this->book_m->update_book(array("due_quantity" => $due_quantity), $dbGet_bookID);
						$this->issue_m->update_issue(array("return_date" => $date), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						 
						if($this->session->userdata('usertypeID') == 4) {
							$lmember = $this->lmember_m->get_single_lmember(array('lID' => $issue->lID));
							redirect(base_url("issue/index/$lmember->studentID"));
						} else {
							redirect(base_url("issue/index/$lID"));
						}
					} else {
						redirect(base_url("issue/index/$lID"));
					}
				} else {
					redirect(base_url("issue/index/$lID"));
				}
			} else {
				redirect(base_url("issue/index/$lID"));
			}
		} else {
			redirect(base_url("issue/index/$lID"));
		}
	}

	public function unique_quantity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");
		if($id) {
			if((int)$bookID) {
				$bookandauthor = $this->issue_m->get_single_issue(array("bookID" => $bookID, "issueID" => $id));
				if(customCompute($bookandauthor)) {
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s are not available.");
				return FALSE;
			}
		} else {
			if((int)$bookID) {
				$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));
				if(customCompute($bookandauthor)) {
					if($bookandauthor->due_quantity >= $bookandauthor->quantity) {
						$this->form_validation->set_message("unique_quantity", "%s are not available.");
						return FALSE;
					}
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s are not available.");
				return FALSE;
			}
		}	
	}

	public function unique_lID() {
		$lID = $this->lmember_m->get_single_lmember(array("lID" => $this->input->post("lid")));
		if(!customCompute($lID)) {
			$this->form_validation->set_message("unique_lID", "%s  is wrong.");
			return FALSE;	
		}
		return TRUE;
	}

	public function unique_book() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if($id) {
			$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "issueID" => $id));
			if(customCompute($book)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_book", "%s already issue.");
				return FALSE;
			}
		} else {
			$bookandauthor = $this->book_m->get_single_book(array("bookID" => $this->input->post("book")));
			$quantitys = $bookandauthor->quantity - $bookandauthor->due_quantity;
			if($quantitys > 0){
				return TRUE;	
			}else{
				$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "lID" => $this->input->post("lid")));
				if(customCompute($book)) {
					$this->form_validation->set_message("unique_book", "%s already issue.");
					return FALSE;
				}	
			}
			
			return TRUE;
		}
	}

	public function unique_book_call() {
		if($this->input->post('book') === '0') {
			$this->form_validation->set_message("unique_book_call", "The %s field is required.");
	     	return FALSE;
		}
		return TRUE;
	}

	public function wrong_date() {
		$due_date 	= strtotime(date("Y-m-d", strtotime($this->input->post("due_date"))));
		$date 		= strtotime(date("Y-m-d", strtotime($this->input->post("issue_date"))));
		if($due_date < $date) {
			$this->form_validation->set_message("wrong_date", "%s is smaller of present date");
	     	return FALSE;
		} else {
			return TRUE;
		}
	}

	public function date_valid($date) {
		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);   
	        $dd = $arr[0];            
	        $mm = $arr[1];              
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    } 
	} 

	public function bookIDcall() {
		$bookID = $this->input->post('bookID');
		if($bookID) {
			$bookinfo = $this->book_m->get_book($bookID);
			$author = $bookinfo->author;
			$subject_code = $bookinfo->subject_code;
			$accession_number = $bookinfo->accession_number;
			$json = array("author" => $author, "subject_code" => $subject_code, "accession_number" => $accession_number);
			header("Content-Type: application/json", true);
			echo json_encode($json);
			exit;
		}
	}

	public function match_bookauthor() {
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");

		if((int)$bookID && $bookID != "") {
			$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));
			if($bookandauthor) {
				if($bookandauthor->author == $author) {
					return TRUE;
				} else {
					$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
					return FALSE;
				}
			} else {
				$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
			return FALSE;
		}
	}

	public function valid_number () {
		if($this->input->post('fine') && $this->input->post('fine') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("issue/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("issue/index"));
		}
	}

	public function add_invoice() {
		$libraryID 	= $this->input->post('libraryID');
		$amount 	= $this->input->post('amount');
		$due_date 	= $this->input->post('due_date');
		$issueID 	= $this->input->post('issueID');
		$include_fine 	= $this->input->post('include_fine');
		$issue 		= $this->issue_m->get_issue_with_books(array('issueID' => $issueID), TRUE);
 
		if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
			if($libraryID && $amount) {
				$librarymember = $this->issue_m->get_student_by_libraryID_with_studenallinfo($libraryID);

				if(customCompute($librarymember)) {
				if ($include_fine) {
					 
					$feetype = $this->feetypes_m->get_single_feetypes(array('feetypes' => $this->lang->line('issue_bookfine')));

					if(!customCompute($feetype)) {
						$this->feetypes_m->insert_feetypes(array('feetypes' => $this->lang->line('issue_bookfine'), 'note' => "Don't delete it!"));
					}

					$feetype = $this->feetypes_m->get_single_feetypes(array('feetypes' => $this->lang->line('issue_bookfine')));
					 
					$invoiceMainArray = array(
                 		'maininvoiceschoolyearID' 	=> $this->data['siteinfos']->school_year,
                 		'maininvoiceclassesID' 		=> $librarymember->classesID,
                 		'maininvoicestudentID' 		=> $librarymember->studentID,
                 		'maininvoicestatus' 		=> 0,
                 		'maininvoice_type_v' 		=> 'library_fine',
                 		'maininvoiceuserID' 		=> $this->session->userdata('loginuserID'),
                 		'maininvoiceusertypeID' 	=> $this->session->userdata('usertypeID'),
                 		'maininvoiceuname' 			=> $this->session->userdata('name'), 
                 		'maininvoicedate' 			=> date('Y-m-d'),
                        'maininvoicedue_date' 		=> date('Y-m-d',strtotime($due_date)),
                        'maininvoicecreate_date' 	=> date('Y-m-d'),
                        'maininvoicetotal_fee' 		=> $amount,
                        'maininvoice_discount' 		=>  0,
                        'maininvoicenet_fee' 		=> $amount,
                        'maininvoicestd_srid' 		=> $librarymember->sectionID,
                 		'maininvoiceday' 			=> date('d'),
                 		'maininvoicemonth' 			=> date('m'),
                 		'maininvoiceyear' 			=> date('Y'),
                 		'maininvoicedeleted_at' 	=> 1
             		);

             		$this->maininvoice_m->insert_maininvoice($invoiceMainArray);
             		$maininvoiceID = $this->db->insert_id();

					$invoiceArray = array(
						'schoolyearID' 	=> $this->data['siteinfos']->school_year,
						'classesID' 	=> $librarymember->classesID,
						'sectionID' 	=> $librarymember->sectionID,
						'studentID' 	=> $librarymember->studentID,
						'feetypeID' 	=> (customCompute($feetype) ? $feetype->feetypesID : 0),
						'feetype' 		=> (customCompute($feetype) ? $feetype->feetypes : $this->lang->line('issue_bookfine')),
						'amount' 		=> $amount,
						'net_fee' 		=> $amount,
						'discount'		=> 0,
						'refrence_no' 	=> $this->invoice_m->get_invoice_ref()+1,
                 		'type_v' 		=> 'library_fine',
						'paidstatus'	=> 0,
						'userID' 		=> $this->session->userdata('loginuserID'),
						'usertypeID' 	=> $this->session->userdata('usertypeID'),
						'uname' 		=> $this->session->userdata('name'),
						'date' 			=> date('Y-m-d'),
						'create_date' 	=> date('Y-m-d'),
                        'due_date' 		=> date('Y-m-d',strtotime($due_date)),
						'day' 			=> date('d'),
						'month' 		=> date('m'),
						'year' 			=> date('Y'),
						'deleted_at' 	=> 1,
						'maininvoiceID' => $maininvoiceID
					);

					$this->invoice_m->insert_invoice($invoiceArray);
				}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					
					if($this->session->userdata('usertypeID') == 4) {
						  redirect(base_url("issue/index/".$librarymember->studentID));
					} else {

						redirect( base_url('issue/returnbook/'.$issue->issueID."/".$issue->lID));
					}
				} else {
					 redirect(base_url('issue/returnbook/'.$issue->issueID."/".$issue->lID));
				}
			} else {
				redirect(base_url('issue/returnbook/'.$issue->issueID."/".$issue->lID));
			}
		} else {
			redirect(base_url('issue/returnbook/'.$issue->issueID."/".$issue->lID));
		}
	}
}