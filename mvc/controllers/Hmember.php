<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hmember extends Admin_Controller {
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
		$this->load->model("hmember_m");
		$this->load->model("category_m");
		$this->load->model("hostel_m");
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("section_m");
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
        $this->load->model('hostelroom_m');
        $this->load->model('document_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('student', $language);	
		$this->lang->load('hmember', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("hmember_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("hmember_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("hmember_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("hmember_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("hmember_classesID"),
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

	protected function rules() {
		$rules = array(
			array(
				'field' => 'hostelID', 
				'label' => $this->lang->line("hmember_hname"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_gender'
			),
			array(
				'field' => 'categoryID', 
				'label' => $this->lang->line("hmember_class_type"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_select|callback_unique_category'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("student_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			)
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$this->data['hmember'] = pluck($this->hmember_m->get_hmember(),'obj','studentID');
		$myProfile = false;
		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if(!permissionChecker('hmember_view')) {
				$myProfile = true;
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}
		
		if($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->view($id, $url);
		} else {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id) {
				$this->data['set'] = $id;
				$this->data['classes'] = $this->classes_m->get_classes();
				$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
				if(isset($fetchClass[$id])) {
					$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($this->data['students'])) {
						$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
						$this->data['sections'] = $sections;
						foreach ($sections as $key => $section) {
							$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
						}
					} else {
						$this->data['students'] = [];
					}
				} else {
					$this->data['students'] = [];
				}

				

				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {

				$this->data['set'] = $id;

				$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('hostel' => 1));
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js',
				)
			);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$this->data["hostels"] = $this->hostel_m->get_hostel();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			$hostelID = $this->input->post("hostelID");
			if($hostelID > 0) {
				$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
				$this->data['hostelrooms'] = $this->hostelroom_m->get_order_by_hostelroom(array("hostelID" => $hostelID));
			} else {
				$this->data['categorys'] 	= [];
				$this->data['hostelrooms'] 	= [];
			}

			if((int)$id && (int)$url) {
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if(customCompute($student)) {
					if($student->hostel == 0) {
						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) { 
								$this->data["subview"] = "hmember/add";
								$this->load->view('_layout_main', $this->data);			
							} else {
								$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
								$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));
								if($hostel_main_id) {
									if($category_main_id) {

										$sr 	=	0;
										$fee_detail 	=	[];
										foreach((array)$_POST['other_charges'] as $key=>$val){

									    $other_charges	 		= 	$val;
									    $other_charges_amount 	=   $_POST['other_charges_amount'][$sr];
										   $sr++; 
										 $fee_detail[] 	 = array(
										 						'other_charges' => $other_charges,
										 						'other_charges_amount' => $other_charges_amount,
										 						 ); 
										}
										$fee_details =	serialize($fee_detail);
										$array = array(
											"bookDate" => date('Y-m-d'),
											"hostelID" => $this->input->post("hostelID"),
											"categoryID" => $this->input->post("categoryID"),
											"hostel_discount" => $this->input->post("hostel_discount_amount"),
											"studentID" => $id,
											"fee_details" => $fee_details,
											"hbalance" => $this->input->post("total_amount")+$this->input->post("hostel_discount_amount"),
											"hostelroomID" => $this->input->post("hostelroomID"),
											"hjoindate" => date("Y-m-d",strtotime($this->input->post("bookDate")))
										);
										if (isset($this->upload_data['file'])) {
											$array['decision'] = $this->upload_data['file']['file_name'];
										}else{
											$array['decision'] = '';
										}
										
										$this->hmember_m->insert_hmember($array);
										$hostelroom = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $array['hostelroomID']));
										$newcapicity 	=	$hostelroom->capcityoccopied+1;
										   $upar = array(
						                    "capcityoccopied"          => $newcapicity
						                );

						                $this->hostelroom_m->update_hostelroom($upar,  $array['hostelroomID']);
										$this->student_m->update_student(array("hostel" => 1), $id);
										$this->session->set_flashdata('success', $this->lang->line('menu_success'));
										redirect(base_url("hmember/index/$url"));
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							}
						} else {
							$this->data["subview"] = "hmember/add";
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


	
	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
				$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js',
				)
			);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($student)) {
						$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id ,"status" => 1));

						if($this->data["hmember"]) {
							$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
							if($this->data["categorys"]) {

								$this->data["hostels"] = $this->hostel_m->get_hostel();

							$this->data['hostelrooms'] = $this->hostelroom_m->get_order_by_hostelroom(array("hostelID" => $this->data["hmember"]->hostelID));
								$this->data['set'] = $url;
								$hostelID = $this->input->post("hostelID");
								if($hostelID > 0) {
									$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
								} else {
									$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
								}

								if($student->hostel == 1) {
									if($_POST) {
										$rules = $this->rules();
										$this->form_validation->set_rules($rules);
										if ($this->form_validation->run() == FALSE) { 
											$this->data["subview"] = "hmember/edit";
											$this->load->view('_layout_main', $this->data);
										} else {
											$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
											$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));

											if($hostel_main_id) {
												if($category_main_id) {
													$sr 	=	0;
										$fee_detail 	=	[];
										foreach((array)$_POST['other_charges'] as $key=>$val){

									    $other_charges	 		= 	$val;
									    $other_charges_amount 	=   $_POST['other_charges_amount'][$sr];
										   $sr++; 
										 $fee_detail[] 	 = array(
										 						'other_charges' => $other_charges,
										 						'other_charges_amount' => $other_charges_amount,
										 						 ); 
										}
										$fee_details =	serialize($fee_detail);
													$array = array(
														
														"fee_details" => $fee_details,  
														"hostel_discount" => $this->input->post("hostel_discount_amount"), 
														"hbalance" => $this->input->post("total_amount")+$this->input->post("hostel_discount_amount"),
														
													);

													$this->hmember_m->update_hmember($array, $this->data['hmember']->hmemberID);
													$this->session->set_flashdata('success', $this->lang->line('menu_success'));
													redirect(base_url("hmember/index/"));
												} else {
													$this->data["subview"] = "error";
													$this->load->view('_layout_main', $this->data);
												}
											} else {
												$this->data["subview"] = "error";
												$this->load->view('_layout_main', $this->data);
											}				
										}
									} else {
										$this->data["subview"] = "hmember/edit";
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
	public function leave() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($student)) {
						$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id,"status" => 1));
						if($this->data["hmember"]) {
							$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
							if($this->data["categorys"]) {

								$this->data["hostels"] = $this->hostel_m->get_hostel();
								$this->data['set'] = $url;
								$hostelID = $this->input->post("hostelID");
								if($hostelID > 0) {
									$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
								} else {
									$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
								}

								if($student->hostel == 1) {
									if($_POST) {
										 
											$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
											$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));

											if($hostel_main_id) {
												if($category_main_id) {
													$array = array(
														"leftDate" => date('Y-m-d',strtotime($this->input->post("leftDate"))),
														"leftReason" 	=> $this->input->post("leftReason") ,
														"status" 		=> 0 
													);

													$this->hmember_m->update_hmember($array, $this->data['hmember']->hmemberID);
													$hostelroom = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $this->data['hmember']->hostelroomID));
													$newcapicity 	=	$hostelroom->capcityoccopied-1;
													   $upar = array(
									                    "capcityoccopied"          => $newcapicity
									                );

						                $this->hostelroom_m->update_hostelroom($upar,  $this->data['hmember']->hostelroomID);
													$this->session->set_flashdata('success', $this->lang->line('menu_success'));
													$this->student_m->update_student(array("hostel" => 0), $this->data['hmember']->studentID); 
													redirect(base_url("hmember/index/$url"));
												} else {
													$this->data["subview"] = "error";
													$this->load->view('_layout_main', $this->data);
												}
											} else {
												$this->data["subview"] = "error";
												$this->load->view('_layout_main', $this->data);
											}				
										 
									} else {
										$this->data["subview"] = "hmember/leave";
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

	public function delete() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if($student) {
					$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
					if($this->data["hmember"]) {
						$this->hmember_m->delete_hmember($this->data['hmember']->hmemberID);
						$this->student_m->update_student(array("hostel" => 0), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("hmember/index/$url"));
					} else {
						redirect(base_url("hmember/index"));
					}
				} else {
					redirect(base_url("hmember/index"));
				}
			} else {
				redirect(base_url("hmember/index"));
			}
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	public function view($id = null , $url = null) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), TRUE);
				$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
	        	$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
	        	$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
				if(customCompute($this->data['student'])) {
					$this->data["class"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id,"status" => 1));
					if(customCompute($this->data['hmember'])) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);

						$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $this->data['student']->srstudentID, 'srclassesID' => $this->data['student']->srclassesID, 'srschoolyearID' => $schoolyearID), TRUE);
				// $studentInfo->balance;
				
				
				 
						$this->documentInfo($studentInfo);
						$this->data['profile'] = $studentInfo;
						$this->data["subview"] = "hmember/getView";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data['hostel'] = [];
						$this->data['category'] = [];
						$this->data["subview"] = "hmember/getView";
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

	public function clearanceslip($id = null , $url = null) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), TRUE);
				$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
	        	$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
	        	$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
				if(customCompute($this->data['student'])) {
					$this->data["class"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
					if(customCompute($this->data['hmember'])) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);

						$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $this->data['student']->srstudentID, 'srclassesID' => $this->data['student']->srclassesID, 'srschoolyearID' => $schoolyearID), TRUE);
				// $studentInfo->balance;
				
						 
						 	$this->data['unpaidcount'] = $this->student_m->get_student_invoicecount(['studentID' =>  $this->data['student']->srstudentID ,'deleted_at'=>1,'feetypeID'=>4,'paidstatus !='=>2]);
							 
							 
				 		 
						$this->documentInfo($studentInfo);
						$this->data['profile'] = $studentInfo;
						$this->reportPDF('hmembermodule.css',$this->data, 'hmember/clearanceslip');
					} else {
						$this->data['hostel'] = [];
						$this->data['category'] = [];
						$this->reportPDF('hmembermodule.css',$this->data, 'hmember/clearanceslip');
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

	private function documentInfo($studentInfo) {
		if(customCompute($studentInfo)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 3, 'userID' => $studentInfo->srstudentID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
		if(permissionChecker('hmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('hmember') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), TRUE);
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					if(customCompute($this->data['student'])) {
						$this->data["classes"] = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
						$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
						$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
						if(customCompute($this->data['hmember'])) {
							$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
							$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
							$this->reportPDF('hmembermodule.css',$this->data, 'hmember/print_preview');
						} else {
							$this->data['hostel'] = [];
							$this->data['category'] = [];
							$this->reportPDF('hmembermodule.css',$this->data, 'hmember/print_preview');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		// if(permissionChecker('hmember_view')) {
		if(permissionChecker('hmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('hmember') && ($this->session->userdata('loginuserID') == $this->input->post('studentID')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
			        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			        $this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

					$id = $this->input->post('studentID');
					$url = $this->input->post('classesID');
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					if ((int)$id && (int)$url) {
						$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
						if(isset($fetchClass[$url])) {
							$this->data["student"] = $this->studentrelation_m->get_single_student(array('srschoolyearID' => $schoolyearID, 'srstudentID' => $id));
							$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
							if(customCompute($this->data["student"])) {
								$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
								$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
								$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
								if($this->data['hmember']) {
									$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
									$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
								} else {
									$this->data['hostel'] = [];
									$this->data['category'] = [];
								}

								$email = $this->input->post('to');
								$subject = $this->input->post('subject');
								$message = $this->input->post('message');
								$this->reportSendToMail('hmembermodule.css',$this->data, 'hmember/print_preview', $email, $subject, $message);
								$retArray['message'] = "Message";
								$retArray['status'] = TRUE;
								echo json_encode($retArray);
							} else {
								$retArray['message'] = $this->lang->line('hmember_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('hmember_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('hmember_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('hmember_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('hmember_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("hmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	public function categorycall() {
		$classtype = $this->input->post('id');
		echo "<option value='0'>", $this->lang->line("hmember_select_class_type"),"</option>";
		if((int)$classtype) {
			$allclasstype = $this->category_m->get_order_by_category(array("hostelID" => $classtype));
			foreach ($allclasstype as $value) {
				echo "<option value=\"$value->categoryID\">",$value->class_type,"</option>";
			}
		} 
	}

	public function roomcall() {
		$classtype = $this->input->post('id');
		echo "<option value='0'>Select Room </option>";
		if((int)$classtype) {
			$allclasstype = $this->hostelroom_m->get_order_by_hostelroom(array("hostelID" => $classtype));
			foreach ($allclasstype as $value) {
				echo "<option value=\"$value->hostelroomID\">",$value->hostelroom,"</option>";
			}
		} 
	}

	public function roomsinglecall() {
		$classtype = $this->input->post('id'); 
		if((int)$classtype) {
			$allclasstype = $this->hostelroom_m->get_order_by_hostelroom(array("hostelroomID" => $classtype));
			  echo json_encode($allclasstype[0]);
		} 
	}

	public function unique_select() {
		if($this->input->post("categoryID") == 0) {
			$this->form_validation->set_message("unique_select", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_gender() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post("hostelID") == 0) {
				$this->form_validation->set_message("unique_gender", "The %s field is required");
				return FALSE;
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				$hostel = $this->hostel_m->get_single_hostel(array("hostelID" => $this->input->post("hostelID")));
				if($hostel) {
					$gender = "";
					if($student->sex == "Male") {
						$gender = "Boys";
					} else {
						$gender = "Girls";
					}

					if($hostel->htype == $gender) {
						return TRUE;
					} elseif($hostel->htype == "Combine") {
						return TRUE;
					} else {
						$this->form_validation->set_message("unique_gender", "This hostel only for $hostel->htype.");
						return FALSE;
					}
				} else {
					$this->form_validation->set_message("unique_gender", "The %s field is required");
					return FALSE;
				}
			}
		}
		return FALSE;	
	}

	public function unique_category() {
		$hostelID = $this->input->post('hostelID');
		$categoryID = $this->input->post('categoryID');
		if($hostelID != 0 && $categoryID != 0 ) {
			$category = $this->category_m->get_single_category(array('hostelID' => $hostelID, 'categoryID' => $categoryID));
			if(!customCompute($category)) {
				$this->form_validation->set_message("unique_category", "The %s field is required");
				return FALSE;
			}
			return TRUE;
		} else {
			$this->form_validation->set_message("unique_category", "The %s field is required");
			return FALSE;
		}
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$student = array();
		if((int)$id) {
			$student = $this->student_m->general_get_single_student(array('studentID' => $id));
		}
		error_reporting(0);
		$new_file = "default.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(customCompute($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(customCompute($student)) {
				$this->upload_data['file'] = array('file_name' => $student->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}
}