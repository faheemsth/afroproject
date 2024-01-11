<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lmember extends Admin_Controller {
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
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("issue_m");
		$this->load->model('section_m');
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('user_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('attendanceoverviewreport', $language);	
		$this->lang->load('lmember', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("lmember_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("lmember_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("lmember_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'id',
				'label' => $this->lang->line("lmember_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'set',
				'label' => $this->lang->line("lmember_classesID"),
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
				'field' => 'lID', 
				'label' => $this->lang->line("lmember_lID"),
				'rules' => 'trim|required|max_length[40]|callback_unique_lID|xss_clean'
			),
			array(
				'field' => 'lbalance', 
				'label' => $this->lang->line("lmember_lfee"), 
				'rules' => 'trim|required|max_length[20]|xss_clean|numeric|callback_valid_number'
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


		$this->data['students'] = [];
		$this->data['usertypes'] = $this->usertype_m->get_usertype();
		$this->data["classes"] = $this->classes_m->general_get_classes();
        $classess = $this->classes_m->get_classes();
        $classes = pluck($classess, "obj", "classesID");
        $this->data["pluck_classes"] = $classes;

        $sections = $this->section_m->get_section();
        $sections = pluck($sections, "obj", "sectionID");

        $this->data["pluck_section"] = $sections;

		 

		 	$this->data["active"] 				= 1;
		 	$this->data["studentID"] 			= 0;
            $this->data["classesID"] 			= 0;
            $this->data["sectionID"] 			= 0;
            $this->data["usertypeID"] 			= 0;
            $this->data["name"] 				= "";
            $this->data["email"] 				= "";
            $this->data["phone"] 				= "";
            $this->data["address"] 				= "";
            $this->data["registerNO"] 			= "";
            $this->data["roll"] 				= "";
            $this->data["username"] 			= "";
            $this->data["InstallmentsID"] 		= "";  
            $this->data["allstudents"] 			= [];

		$myProfile = false;
		$schoolyearID = $this->session->userdata('defaultschoolyearID');


            if ($_GET) {
            	$this->data["usertypeID"] 	= $_GET["usertypeID"];
            	if ($_GET["usertypeID"]==3) {
            		

	            	
	              

	                $classesID = $_GET["classesID"];
	                if ($classesID != 0) {

	                    $array["classesID"] 		= $classesID;
	                    $this->data["classesID"] 	= $classesID;
	                    
	                    $this->data["allsection"] = $this->section_m->get_order_by_section(["classesID" => $classesID,]);
	                }
	                $sectionID 		= $_GET["sectionID"];

	                if ($sectionID != 0) {
	                    $array["sectionID"] 		= $sectionID;
	                    $this->data["sectionID"] 	= $sectionID;

	                    $studentArrays = ["srclassesID" => $classesID];
	                    if ((int) $sectionID) {
	                        $studentArrays["srsectionID"] 	= $sectionID;
	                    }
	                    $this->data["allstudents"] 			= $this->studentrelation_m->get_order_by_student($studentArrays);
	                }


	                $studentID 		= 	$_GET["studentID"];

	                if ($studentID != 0) {
	                    $array["studentID"] = $studentID;
	                    $this->data["studentID"] = $studentID;
	                }

	                $name 	= 	$_GET["name"];

	                if ($name != "") {
	                    $array["name LIKE"] 	= "%$name%";
	                    $this->data["name"] 	= $name;
	                } 
	                
	                $email 	= 	$_GET["email"];

	                if ($email != "") {
	                    $array["email LIKE"] 	= "%$email%";
	                    $this->data["email"] 	= $email;
	                } 
	                
	                $phone 	= 	$_GET["phone"];

	                if ($phone != "") {
	                    $array["phone LIKE"] 	= "%$phone%";
	                    $this->data["phone"] 	= $phone;
	                }
	                
	                $phone 	= 	$_GET["address"];

	                if ($phone != "") {
	                    $array["address LIKE"] 	= "%$address%";
	                    $this->data["address"] 	= $address;
	                }
	                
	                $registerNO 	= 	$_GET["registerNO"];

	                if ($registerNO != "") {
	                    $array["registerNO"] 		= $registerNO;
	                    $this->data["registerNO"] 	= $registerNO;
	                } 
	                
	                $usertypeID 	= 	$_GET["usertypeID"];

	                if ($usertypeID != "") {
	                    $array["usertypeID"] 		= $usertypeID;
	                    $this->data["usertypeID"] 	= $usertypeID;
	                } 
	                
	                $roll 	= 	$_GET["roll"];

	                if ($roll != "") {
	                    $array["roll"] 			= $roll;
	                    $this->data["roll"] 	= $roll;
	                } 
	                
	                $username 	= 	$_GET["username"];

	                if ($username != "") {
	                    $array["username LIKE"] 	= "%$username%";
	                    $this->data["username"] 	= $username;
	                }
	                
	                 

	                  
	                 
	                $this->data['students'] = $this->studentrelation_m->get_order_by_student($array);

                 
            
            	}
            	if ($_GET["usertypeID"]!=3) {

            		
	                $usertypeID 	= 	$_GET["usertypeID"];

	                if ($usertypeID != "") {
	                    $array["usertypeID"] 		= $usertypeID;
	                    $this->data["usertypeID"] 	= $usertypeID;
	                }
            		
	                $name 	= 	$_GET["name"];

	                if ($name != "") {
	                    $array["name LIKE"] 	= "%$name%";
	                    $this->data["name"] 	= $name;
	                } 
	                
	                $email 	= 	$_GET["email"];

	                if ($email != "") {
	                    $array["email LIKE"] 	= "%$email%";
	                    $this->data["email"] 	= $email;
	                } 
	                
	                $phone 	= 	$_GET["phone"];

	                if ($phone != "") {
	                    $array["phone LIKE"] 	= "%$phone%";
	                    $this->data["phone"] 	= $phone;
	                }
	                
	                $phone 	= 	$_GET["address"];

	                if ($phone != "") {
	                    $array["address LIKE"] 	= "%$address%";
	                    $this->data["address"] 	= $address;
	                }
	                
	                 
	                
	                $username 	= 	$_GET["username"];

	                if ($username != "") {
	                    $array["username LIKE"] 	= "%$username%";
	                    $this->data["username"] 	= $username;
	                }
	                

	                if ($_GET["usertypeID"]==1) {
	                 	 $this->data['students'] = $this->systemadmin_m->get_order_by_systemadmin($array);
	                 }else if ($_GET["usertypeID"]==2) {
	                 	 $this->data['students'] = $this->teacher_m->get_order_by_teacher($array);
	                 }else{
	                 	 $this->data['students'] = $this->user_m->get_order_by_user($array);

	                 } 
	               

                 
            
            	}
            } 

		$myProfile = false;
		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if(!permissionChecker('lmember_view')) {
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
			
					 
					// array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID)
					  


					 


				

				$this->data['set'] = 0;  
				 
				$this->data["subview"] = "lmember/index";

				$this->load->view('_layout_main', $this->data);
			 
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$lID = '';
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$lmember = $this->lmember_m->get_lmember();
			$lastid = $this->lmember_m->get_lmember_lastID();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			
			if((int)$id && (int)$url) {
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if(customCompute($student)) {
					if(customCompute($lmember)) {
						$lID = $lastid->lID+1;
					} else {
						$data = date('Y');
						$lID = $data.'01';
					}

					$this->data['libraryID'] = $lID;
					$this->data['student'] = $student;
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data['form_validation'] = validation_errors(); 
							$this->data["subview"] = "lmember/add";
							$this->load->view('_layout_main', $this->data);			
						} else {
							$array = array(
								"lID" => $this->input->post("lID"),
								"studentID" => $student->studentID,
								"name" => $student->name,
								"email" => $student->email,
								"phone" => $student->phone,
								"lbalance" => $this->input->post("lbalance"),
								"ljoindate" => date("Y-m-d")
							);
							$this->lmember_m->insert_lmember($array);
							$this->student_m->update_student(array("library" => 1), $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("lmember/index/$url"));
						}
					} else {
						$this->data["subview"] = "lmember/add";
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
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($this->data['student'])) {
						$this->data['lmember'] = $this->lmember_m->get_single_lmember(array("studentID" => $id));
						if(customCompute($this->data['lmember'])) {
							$this->data['set'] = $url;
							if($_POST) {
								$rules = $this->rules();
								$this->form_validation->set_rules($rules);
								if ($this->form_validation->run() == FALSE) { 
									$this->data["subview"] = "lmember/edit";
									$this->load->view('_layout_main', $this->data);
								} else {
									$array = array(
										"lID" => $this->input->post("lID"),
										"lbalance" => $this->input->post("lbalance")
									);

									$this->lmember_m->update_lmember($array, $this->data['lmember']->lmemberID);
									$this->session->set_flashdata('success', $this->lang->line('menu_success'));
									redirect(base_url("lmember/index/$url"));	
								}
							} else {
								$this->data["subview"] = "lmember/edit";
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
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$student = $this->studentrelation_m->get_order_by_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if($student) {
						$this->lmember_m->delete_lmember_sID($id);
						$this->student_m->update_student(array("library" => 0), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("lmember/index/$url"));
					} else {
						redirect(base_url("lmember/index"));
					}
				} else {
					redirect(base_url("lmember/index"));
				}
			} else {
				redirect(base_url("lmember/index"));
			}
		} else {
			redirect(base_url("lmember/index"));
		}
	}

	public function view($id = null, $url = null) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), true);
				if(customCompute($this->data['student'])) {
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->data['lmember'] = $this->lmember_m->get_single_lmember(array('studentID' => $id));
					
					$this->data["subview"] = "lmember/getView";
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
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function print_preview() {
		if(permissionChecker('lmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('lmember') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));	
					if(customCompute($this->data['student'])) {
						$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
						$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
						$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
						$this->data['lmember'] = $this->lmember_m->get_single_lmember(array('studentID' => $id));
						$this->reportPDF('lmembermodule.css',$this->data, 'lmember/print_preview');
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
		if(permissionChecker('lmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('lmember') && ($this->session->userdata('loginuserID') == $this->input->post('id')))) {
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
					$url = $this->input->post('set');
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					if ((int)$id && (int)$url) {
						$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
						if(isset($fetchClass[$url])) {
							$this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
							if(customCompute($this->data["student"])) {
								$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
								$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
								$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
								$this->data['lmember'] = $this->lmember_m->get_single_lmember(array('studentID' => $id));
								
								$email = $this->input->post('to');
								$subject = $this->input->post('subject');
								$message = $this->input->post('message');
								$this->reportSendToMail('lmembermodule.css', $this->data, 'lmember/print_preview', $email, $subject, $message);
								$retArray['message'] = "Success";
								$retArray['status'] = TRUE;
								echo json_encode($retArray);
							    exit;
							} else {
								$retArray['message'] = $this->lang->line('lmember_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('lmember_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('lmember_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('lmember_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('lmember_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("lmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("lmember/index"));
		}
	}

	public function unique_lID() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$method = $this->uri->segment(2);
		if($method == "edit") {
			$library = $this->lmember_m->get_single_lmember(array("studentID" => $id));
			$lmember = $this->lmember_m->get_order_by_lmember(array("lID" => $this->input->post("lID"), "lmemberID !=" => $library->lmemberID));
			if(customCompute($lmember)) {
				$this->form_validation->set_message("unique_lID", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$lmember = $this->lmember_m->get_order_by_lmember(array("lID" => $this->input->post("lID")));
			if(customCompute($lmember)) {
				$this->form_validation->set_message("unique_lID", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function valid_number() {
		if($this->input->post('lbalance') && $this->input->post('lbalance') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}
}