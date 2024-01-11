<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student extends Admin_Controller
{
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
	function __construct()
	{
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("studentmeta_m");
		$this->load->model("parents_m");
		$this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model("setting_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('subject_m');
		$this->load->model('routine_m');
		$this->load->model('teacher_m');
		$this->load->model('subjectattendance_m');
		$this->load->model('sattendance_m');
		$this->load->model('invoice_m');
		$this->load->model('payment_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('feetypes_m');
		$this->load->model('exam_m');
		$this->load->model('grade_m');
		$this->load->model('markpercentage_m');
		$this->load->model('markrelation_m');
		$this->load->model('mark_m');
		$this->load->model('document_m');
		$this->load->model('leaveapplication_m');
		$this->load->model('marksetting_m');
		$this->load->model("invoice_m");
		$this->load->model("maininvoice_m");
		$this->load->model("globalpayment_m");
		$this->load->model("payment_m");
		$this->load->model("weaverandfine_m");
		$this->load->model("courseoption_m");
		$language = $this->session->userdata('lang');

		$this->lang->load("attendanceoverviewreport", $language);
		$this->lang->load('student', $language);
	}

	public function send_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("student_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("student_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("student_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("student_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classesID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	private function getView($id, $url)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
		if (isset($fetchClasses[$url])) {
			if ((int)$id && (int)$url) {
				$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), TRUE);
				$this->data['total_fee'] = $this->courseoption_m->get_sum_label_amount_order_by_array(array('salary_templateID' => $studentInfo->fee_structureID))->total_fee;
				// $studentInfo->balance;
				//var_dump( $studentInfo->fee_structureID);
				

				$this->pluckInfo();
				$this->basicInfo($studentInfo);
				$this->parentInfo($studentInfo);
				$this->routineInfo($studentInfo);
				$this->attendanceInfo($studentInfo);
				$this->markInfo($studentInfo);
				$this->invoiceInfo($studentInfo);
				$this->paymentInfo($studentInfo);
				$this->documentInfo($studentInfo);

				if (customCompute($studentInfo)) {
					$this->data['set']     = $url;
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id, $schoolyearID, $studentInfo->usertypeID);
					$this->data['allhistory'] = $this->studentmeta_m->get_order_by_studentmeta(
						array(
							'studentID' => $id,
							'meta_key' => 'status_update',
						)
					);
					$this->data["subview"] = "student/getView";
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
	}

	private function allPaymentByInvoice($payments)
	{
		$retPaymentArr = [];
		if ($payments) {
			foreach ($payments as $payment) {
				if (isset($retPaymentArr[$payment->invoiceID])) {
					$retPaymentArr[$payment->invoiceID] += $payment->paymentamount;
				} else {
					$retPaymentArr[$payment->invoiceID] = $payment->paymentamount;
				}
			}
		}
		return $retPaymentArr;
	}

	private function allWeaverAndFineByInvoice($weaverandfines)
	{
		$retWeaverAndFineArr = [];
		if ($weaverandfines) {
			foreach ($weaverandfines as $weaverandfine) {
				if (isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] += $weaverandfine->weaver;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] = $weaverandfine->weaver;
				}

				if (isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] += $weaverandfine->fine;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] = $weaverandfine->fine;
				}
			}
		}
		return $retWeaverAndFineArr;
	}


	private function getMark($studentID, $classesID)
	{
		$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr'] = [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
				return;
		if ((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if (customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID,
					'schoolyearID' => $schoolyearID,
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr = [];
				if (customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if ($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr'] = $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr'] = [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr'] = [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
		}
	}

	private function pluckInfo()
	{
		$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
		$this->data['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
		$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(), 'feetypes', 'feetypesID');
		$this->data['classesp'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
		$this->data['sectionp'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
	}

	private function basicInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->data['profile'] = $studentInfo;
			$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => 3));
			$this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
			$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));
			$this->data['group'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $studentInfo->srstudentgroupID));
			$this->data['optionalsubject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $studentInfo->sroptionalsubjectID));
		} else {
			$this->data['profile'] = [];
		}
	}

	private function parentInfo($studentInfo)
	{
		$this->data['parents'] = [];
		if (customCompute($studentInfo)) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $studentInfo->parentID));
		} else {
			$this->data['parents'] = [];
		}
	}

	private function routineInfo($studentInfo)
	{
		$this->data['routines'] = [];

		$settingWeekends = [];
		if ($this->data['siteinfos']->weekends != '') {
			$settingWeekends = explode(',', $this->data['siteinfos']->weekends);
		}
		$this->data['routineweekends'] = $settingWeekends;

		$this->data['routines'] = [];
		if (customCompute($studentInfo)) {
			$schoolyearID           = $this->session->userdata('defaultschoolyearID');
			$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID' => $studentInfo->srclassesID, 'sectionID' => $studentInfo->srsectionID, 'schoolyearID' => $schoolyearID)), 'obj', 'day');
		}
	}

	private function attendanceInfo($studentInfo)
	{
		// $this->data['setting'] = [];
		// $this->data['attendancesubjects'] = [];
		// $this->data['attendances_subjectwisess'] = [];
		// $this->data['attendancesArray'] = [];

		// $this->data['holidays'] =  [];
		// $this->data['getWeekendDays'] =  [];
		// return;

		$this->data['holidays'] =  $this->getHolidaysSession();
		$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();
		if (customCompute($studentInfo)) {
			$this->data['setting'] = $this->setting_m->get_setting();
			if ($this->data['setting']->attendance == "subject") {
				$this->data["attendancesubjects"] = $this->subject_m->general_get_order_by_subject(array("sectionID" => $studentInfo->srsectionID));
			}

			if ($this->data['setting']->attendance == "subject") {
				$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $studentInfo->srstudentID, "classesID" => $studentInfo->srclassesID));
				$this->data['attendances_subjectwisess'] = pluck_multi_array_key($attendances, 'obj', 'subjectID', 'monthyear');
			} else {
				$attendances = $this->sattendance_m->get_order_by_attendance(array("studentID" => $studentInfo->srstudentID, "classesID" => $studentInfo->srclassesID));
				$this->data['attendancesArray'] = pluck($attendances, 'obj', 'monthyear');
			}
		} else {
			$this->data['setting'] = [];
			$this->data['attendancesubjects'] = [];
			$this->data['attendances_subjectwisess'] = [];
			$this->data['attendancesArray'] = [];
		}
	}

	private function markInfo($studentInfo)
	{
				
		if (customCompute($studentInfo)) {
			$this->getMark($studentInfo->srstudentID, $studentInfo->srclassesID);
		} else {
			$this->data['set'] 				= [];
			$this->data["exams"] 			= [];
			$this->data["grades"] 			= [];
			$this->data['markpercentages']	= [];
			$this->data['validExam'] 		= [];
			$this->data['separatedMarks'] 	= [];
			$this->data["highestMarks"] 	= [];
			$this->data["section"] 			= [];
		}
	}

	private function invoiceInfo($studentInfo)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if (customCompute($studentInfo)) {
			$this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID, 'deleted_at' => 1));

			$payments = $this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));
			$weaverandfines = $this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));

			$this->data['allpaymentbyinvoice'] = $this->allPaymentByInvoice($payments);
			$this->data['allweaverandpaymentbyinvoice'] = $this->allWeaverAndFineByInvoice($weaverandfines);
		} else {
			$this->data['invoices'] = [];
			$this->data['allpaymentbyinvoice'] = [];
			$this->data['allweaverandpaymentbyinvoice'] = [];
		}
	}

	private function paymentInfo($studentInfo)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if (customCompute($studentInfo)) {
			$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID_and_schoolyearID($studentInfo->srstudentID, $schoolyearID);
		} else {
			$this->data['payments'] = [];
		}
	}

	protected function rules_documentupload()
	{
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("student_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
			)
		);

		return $rules;
	}

	public function unique_document_upload()
	{
		$new_file = '';
		if ($_FILES["file"]['name'] != "") {
			$file_name = $_FILES["file"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/documents";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '5120';
				$config['max_width'] = '10000';
				$config['max_height'] = '10000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
					return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_document_upload", "Invalid file");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_document_upload", "The file is required.");
			return FALSE;
		}
	}

	protected function rules_feeconcession()
	{
		$rules = array(
			array(
				'field' => 'decision_title',
				'label' => $this->lang->line("decision_title"),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'totaldiscount',
				'label' => 'Discount',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("student_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
			)
		);

		return $rules;
	}

	public function concession_fee()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('student_add')) {
			if ($_POST) {
				$rules = $this->rules_feeconcession();
				$this->form_validation->set_rules($rules);
				$userID = $this->input->post('studentID');
				$studentID = $userID;
				$classesID = $this->input->post('classesID');
				if ($this->form_validation->run() == FALSE) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					$this->session->set_flashdata('errors', $this->form_validation->error_array());
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));

					exit; // echo json_encode($retArray);
				} else {
					$title = $this->input->post('decision_title');
					$file = $this->upload_data['file']['file_name'];
					$totalfee = $this->input->post('totalfee');
					$std_ol_discount = $this->input->post('std_ol_discount');
					$totaldiscount = $this->input->post('totaldiscount');
					$total_discount = $std_ol_discount + $totaldiscount;
					$net_fee = $this->input->post('net_fee');
					$date = date("Y-m-d");
					$data = array(
						'studentID' => $userID,
						'feetype' => 'Installments'
					);



					$result = $this->student_m->update_student(array('discount' => $total_discount, 'net_fee' => $net_fee, 'total_fee' => $totalfee), $userID);


					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 3,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID')
					);

					$this->document_m->insert_document($array);

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
					// $retArray['status'] = TRUE;
					// $retArray['render'] = 'Success';
					//    echo json_encode($retArray);
					//    exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['render'] = 'Error';
				echo json_encode($retArray);
				exit;
			}
		}
	}

	public function documentUpload()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if (permissionChecker('student_add')) {
			if ($_POST) {
				$rules = $this->rules_documentupload();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$title = $this->input->post('title');
					$file = $this->upload_data['file']['file_name'];
					$userID = $this->input->post('studentID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 3,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID')
					);

					$this->document_m->insert_document($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));

					$retArray['status'] = TRUE;
					$retArray['render'] = 'Success';
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['render'] = 'Error';
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['render'] = 'Permission Denay.';
			echo json_encode($retArray);
			exit;
		}
	}

	private function documentInfo($studentInfo)
	{
		$this->data['documents'] = [];
		return;

		if (customCompute($studentInfo)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 3, 'userID' => $studentInfo->srstudentID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function download_document()
	{
		$id 		= htmlentities(escapeString($this->uri->segment(3)));
		$studentID 	= htmlentities(escapeString($this->uri->segment(4)));
		$classesID 	= htmlentities(escapeString($this->uri->segment(5)));
		if ((int)$id && (int)$studentID && (int)$classesID) {
			if ((permissionChecker('student_add') && permissionChecker('student_delete')) || ($this->session->userdata('usertypeID') == 3 && $this->session->userdata('loginuserID') == $studentID)) {
				$document = $this->document_m->get_single_document(array('documentID' => $id));
				if (customCompute($document)) {
					$file = realpath('uploads/documents/' . $document->file);
					if (file_exists($file)) {
						$expFileName = explode('.', $file);
						$originalname = ($document->title) . '.' . end($expFileName);
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize($file));
						readfile($file);
						exit;
					} else {
						redirect(base_url('student/view/' . $studentID . '/' . $classesID));
					}
				} else {
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				}
			} else {
				redirect(base_url('student/view/' . $studentID . '/' . $classesID));
			}
		} else {
			redirect(base_url('student/index'));
		}
	}

	public function delete_document()
	{
		$id 		= htmlentities(escapeString($this->uri->segment(3)));
		$studentID 	= htmlentities(escapeString($this->uri->segment(4)));
		$classesID 	= htmlentities(escapeString($this->uri->segment(5)));
		if ((int)$id && (int)$studentID && (int)$classesID) {
			if (permissionChecker('student_add') && permissionChecker('student_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $id));
				if (customCompute($document)) {
					if (config_item('demo') == FALSE) {
						if (file_exists(FCPATH . 'uploads/document/' . $document->file)) {
							unlink(FCPATH . 'uploads/document/' . $document->file);
						}
					}

					$this->document_m->delete_document($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				} else {
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				}
			} else {
				redirect(base_url('student/view/' . $studentID . '/' . $classesID));
			}
		} else {
			redirect(base_url('student/index'));
		}
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("student_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("student_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("student_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("student_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("student_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("student_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("student_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("student_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("student_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("student_section"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_sectionID|callback_unique_capacity'
			),
			array(
				'field' => 'registerNO',
				'label' => $this->lang->line("student_registerNO"),
				'rules' => 'trim|required|max_length[40]|callback_unique_registerNO|xss_clean'
			),
			array(
				'field' => 'roll',
				'label' => $this->lang->line("student_roll"),
				'rules' => 'trim|required|max_length[60]|callback_unique_roll|xss_clean'
			),
			array(
				'field' => 'guargianID',
				'label' => $this->lang->line("student_guargian"),
				'rules' => 'trim|required|max_length[11]|xss_clean|numeric'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("student_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),

			array(
				'field' => 'studentGroupID',
				'label' => $this->lang->line("student_studentgroup"),
				'rules' => 'trim|max_length[11]|xss_clean|numeric'
			),

			array(
				'field' => 'optionalSubjectID',
				'label' => $this->lang->line("student_optionalsubject"),
				'rules' => 'trim|max_length[11]|xss_clean|numeric'
			),

			array(
				'field' => 'extraCurricularActivities',
				'label' => $this->lang->line("student_extracurricularactivities"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),

			array(
				'field' => 'remarks',
				'label' => $this->lang->line("student_remarks"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),

			array(
				'field' => 'username',
				'label' => $this->lang->line("student_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("student_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			)
		);
		return $rules;
	}

	protected function status_rules()
	{
		$rules = array(
			array(
				'field' => 'active',
				'label' => 'Status',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'reason',
				'label' => 	'Reason',
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function photoupload()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$student = array();
		if ((int)$id) {
			$student = $this->student_m->general_get_single_student(array('studentID' => $id));
		}

		$new_file = "default.png";
		if ($_FILES["photo"]['name'] != "") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . $this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("photo")) {
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
			if (customCompute($student)) {
				$this->upload_data['file'] = array('file_name' => $student->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	private static function debug($data)
	{
		echo "<pre>";
		print_r($data);
		die();
	}

	public function index()
	{

		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$myProfile = false;
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if ($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if (!permissionChecker('student_view')) {
				$myProfile = true;
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if ($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->getView($id, $url);
		} else {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();

			if ((int)$id) {
				$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID, 'active' => [0, 1, 5]));


				if (customCompute($this->data['students'])) {
					$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID, 'active' => [0, 1, 5]));
					}
				} else {
					$this->data['students'] = [];
				}
			} else {
				$this->data['students'] = [];
			}

			$this->data["subview"] = "student/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function search()
	{

		$this->data["classes"] = $this->classes_m->general_get_classes();
		$classess = $this->classes_m->get_classes();
		$classes = pluck($classess, "obj", "classesID");
		$this->data["pluck_classes"] = $classes;

		$sections = $this->section_m->get_section();
		$sections = pluck($sections, "obj", "sectionID");

		$this->data["pluck_section"] = $sections;

		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$this->data["active"] 				= 1;
		$this->data["studentID"] 			= 0;
		$this->data["classesID"] 			= 0;
		$this->data["sectionID"] 			= 0;
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
			$active = $_GET["active"];
			if ($active != '') {
				$array["active"] 		= $active;
				$this->data["active"] 	= $active;
			}

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

			$InstallmentsID 	= 	$_GET["InstallmentsID"];

			if ($InstallmentsID != "") {
				$array["no_installment"] 		= $InstallmentsID;
				$this->data["InstallmentsID"] 	= $InstallmentsID;
			}


			$this->data['students'] = $this->studentrelation_m->get_order_by_student($array);
		} else {

			$this->data['students'] = array();
		}






		$this->data["subview"] = "student/search";
		$this->load->view('_layout_main', $this->data);
	}


	public function manage()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js'
				)
			);

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data["feetypes"] = $this->feetypes_m->get_order_by_feetypes(["type" => 1,]);
			$this->data['students'] = [];
			if ($_POST) {
				$classesID 		= $this->input->post("classesID");
				$sectionID 		= $this->input->post("sectionID");
				$studentID 		= $this->input->post("studentID");
				$numric_code 	= $this->input->post("numric_code");
				$feess_type 	= $this->input->post("feess_type");
				$feetypesID 	= $this->input->post("feetypesID");
				$fine_include 	= $this->input->post("fine_include");
				$type_v 		= $this->input->post("type_v");
				$from_date 		= date('Y-m-d', strtotime($this->input->post("from_date")));
				$due_date 		= date('Y-m-d', strtotime($this->input->post("to_date")));
				$invoiceMainArray 	= [];
				$invoiceArray 		= [];
				$paymentArray 		= [];
				$studentArray 		= [];
				$reg_no       		= [];
				$ref_array    		= [];

				$searcharray 	=	array();

				if ($classesID != 0) {
					$searcharray['student.classesID'] 	=	$classesID;
				}
				if ($sectionID != 0) {
					$searcharray['student.sectionID'] 	=	$sectionID;
				}
				if ($numric_code != 0) {
					$searcharray['section.numric_code'] 	=	$numric_code;
				}
				if (1) {
					// echo "<pre>";
					// print_r($_POST);
					// die();
					$feess_balance = $this->input->post("feess_balance");
					$percentage = $this->input->post("charges_ammount");
					$searcharray['student.active'] 			=	[0, 1];
					$students = $this->student_m->get_student_all_join_by_array('studentID,student.classesID,student.sectionID,total_fee,discount,net_fee,balance,section,accounts_reg', $searcharray);

					$ref_no = $this->invoice_m->get_invoice_ref_by_date($from_date);
					$ref_array[date('ym', strtotime($from_date))] = $ref_no;

					if (customCompute($students)) {
						$mainlastID = $this->maininvoice_m->get_maininvoice_last_id();


						$st_count 		=	0;
						foreach ($students as $key => $getstudent) {

							if($getstudent->studentID != 601)
							continue;

							
							// echo "<pre>";
							// print_r($getstudent);
							// die();

							$st_count++;
							// $getstudent->sectionID
							// $section_check = $this->student_m->check_section_alum(['section !=' => 11]);
							// var_dump($section_check);
							// echo '<br>';
							$student_charges = $this->student_m->get_student_select('total_fee,discount,net_fee,balance', ['studentID' => $getstudent->studentID]);
							$payments = $this->payment_m->get_payment_sum_for_block($getstudent->classesID, $getstudent->sectionID, $getstudent->studentID);
							// echo $payments->paymentamount).'--null';
							// echo $payments->paymentamount.'Ammount';
							// echo '<br>';
							// echo $getstudent->studentID.'ID';
							// echo '<br>';

							


							$balance = ($feess_balance == 1) ? $student_charges->net_fee : $student_charges->total_fee - $student_charges->discount;
						
						
						
						
							$final = $balance * $percentage / 100;
							// echo '<br>';
							$status = ($payments->paymentamount >= $final) ? 1 : 0;

							

							if ($percentage == 100) {
								$invar = array(
									'invoice_status' 	=> 1,
									'deleted_at' 		=> 1,
									'paidstatus !=' 	=> 2,
									'feetypeID !=' 		=> 22,
									'studentID' 		=> $getstudent->studentID,
									'sectionID' 		=> $getstudent->sectionID,
								);

								if (count($type_v)) {
									$wherin = array(
										'type_v' => $type_v,
									);
								} else {
									$wherin = NULL;
								}

							
								//var_dump($this->invoice_m->get_invoice_by_array_where_in_array($invar,$wherin));
								$incount 	=	count($this->invoice_m->get_invoice_by_array_where_in_array($invar, $wherin));
								//echo $this->db->last_query();

								// print_r($this->invoice_m->get_invoice_by_array_where_in_array($invar, $wherin));
								// die();
								$status = ($incount == 0) ? 1 : 0;
								
							}

							// $arrayName = array(

							// 	'status' 		=> $status,
							// 	'student' 		=> $getstudent->studentID,
							// 	'accounts_reg' 	=> $getstudent->accounts_reg,

							// 	 );

							// var_dump($arrayName);
							if ($status == 0) {
								if ($fine_include == 3 || $fine_include == 1) {

									$get_feetype    = 	$this->feetypes_m->get_feetypes($feetypesID);

									$feetypeIDs     =   $get_feetype->feetypesID;
									$feetype        = 	$get_feetype->feetypes;

									$st_count++;
									$total_fee = $this->input->post("total_fine");
									$discount = 0;
									$net_fee = $this->input->post("total_fine");
									$breakup_info_ar      =   [];
									$breakup_info_ar[]    = array(
										'feetypeID'         => $get_feetype->feetypesID,
										'feetypes'          => $get_feetype->feetypes,
										'fee_discount'      => $discount,
										'fee_amount'        => $total_fee,
										'fee_discount_a'    => $discount,
										'net_amount'        => $net_fee,
										'discount_left'     => 0,
										'feetypedetail'     => $get_feetype,
										'S_DIS'             => $discount,
									);




									$fee_breakup    	=   serialize($breakup_info_ar);



									$mainlastID++;
									$singleinvoiceMainArray = [
										"maininvoiceID"         	=> $mainlastID,
										"maininvoiceschoolyearID"   => $schoolyearID,
										"maininvoiceclassesID"  	=> $getstudent->classesID,
										"maininvoicestudentID"  	=> $getstudent->studentID,
										"maininvoicesectionID"  	=> $getstudent->sectionID,
										"maininvoicestatus"     	=> 0,
										"maininvoiceuserID"     	=> $this->session->userdata("loginuserID"),
										"maininvoiceusertypeID" 	=> $this->session->userdata("usertypeID"),
										"maininvoiceuname"      	=> $this->session->userdata("name"),
										"maininvoicedate"       	=> $from_date,
										"maininvoicedue_date"   	=> $due_date,
										"fee_breakup"           	=> $fee_breakup,
										"maininvoicecreate_date"	=> date("Y-m-d"),
										"maininvoicestd_srid"   	=> $getstudent->section,
										"maininvoiceday"        	=> date("d"),
										"maininvoicemonth"      	=> date("m"),
										"maininvoiceyear"       	=> date("Y"),
										"maininvoice_type_v"    	=> 'other_charges',
										"maininvoicedeleted_at" 	=> 1,
									];

									$singleinvoiceMainArray["maininvoicetotal_fee"] 	= ceil($total_fee);
									$singleinvoiceMainArray["maininvoice_discount"] 	= ceil(0);
									$singleinvoiceMainArray["maininvoicenet_fee"]   	= ceil($total_fee);



									$invoiceMainArray[]					=	$singleinvoiceMainArray;

									$studentArray[] 					= 	$getstudent->studentID;
									$reg_no[$getstudent->studentID] 	= 	$getstudent->accounts_reg;
								}
							}
							if ($fine_include == 2 || $fine_include == 1) {
								$result = $this->student_m->update_student(['active' => $status], $getstudent->studentID);
							}
						}
						//die();

						if (customCompute($invoiceMainArray)) {
							$count = customCompute($invoiceMainArray);

							$firstID = $this->maininvoice_m->insert_batch_maininvoice($invoiceMainArray);

							$lastID = $firstID + ($count - 1);

							$j = 0;
							for ($i = $firstID; $i <= $lastID; $i++) {
								$current_y = date("Y");
								$current_m = date("m");
								if ($invoiceMainArray[$j]["maininvoicestd_srid"] < 10) {
									$maininvoicestd_srid = "0" . $invoiceMainArray[$j]["maininvoicestd_srid"];
								} else {
									$maininvoicestd_srid =    $invoiceMainArray[$j]["maininvoicestd_srid"];
								}

								if (!isset($ref_array[date('ym', strtotime($ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))]))])) {
									# code...

									$ref_no = $this->invoice_m->get_invoice_ref_by_date($ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))]);
									$ref_array[date('ym', strtotime($ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))]))] = $ref_no;
								}

								$invoiceArray[] = [
									"schoolyearID"  	=>  $invoiceMainArray[$j]["maininvoiceschoolyearID"],
									"classesID" 		=>  $invoiceMainArray[$j]["maininvoiceclassesID"],
									"type_v"			=>  'other_charges',
									"studentID" 		=>  $invoiceMainArray[$j]["maininvoicestudentID"],
									"sectionID" 		=>  $invoiceMainArray[$j]["maininvoicesectionID"],
									"fee_breakup"   	=>  $invoiceMainArray[$j]["fee_breakup"],
									"feetypeID" 		=>  $feetypeIDs,
									"feetype"   		=>  $feetype,
									"refrence_no"   	=>  $ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))],
									"amount"    	=> isset($invoiceMainArray[$j]["maininvoicetotal_fee"]) ? $invoiceMainArray[$j]["maininvoicetotal_fee"] : 0,
									"discount"  	=>  isset($invoiceMainArray[$j]["maininvoice_discount"]) ? $invoiceMainArray[$j]["maininvoice_discount"] : 0,
									"net_fee"   	=>  isset($invoiceMainArray[$j]["maininvoicenet_fee"]) ? $invoiceMainArray[$j]["maininvoicenet_fee"] : 0,
									"paidstatus"	=>  "",
									"userID"    	=>  $invoiceMainArray[$j]["maininvoiceuserID"],
									"usertypeID"	=>  $invoiceMainArray[$j]["maininvoiceusertypeID"],
									"uname"     	=>  $invoiceMainArray[$j]["maininvoiceuname"],
									"date"      	=>  $invoiceMainArray[$j]["maininvoicedate"],
									"create_date"   =>  $invoiceMainArray[$j]["maininvoicecreate_date"],
									"due_date"  	=>  $invoiceMainArray[$j]["maininvoicedue_date"],
									"day"       	=>  $invoiceMainArray[$j]["maininvoiceday"],
									"month"     	=>  $invoiceMainArray[$j]["maininvoicemonth"],
									"year"      	=>  $invoiceMainArray[$j]["maininvoiceyear"],
									"deleted_at"	=>  $invoiceMainArray[$j]["maininvoicedeleted_at"],
									"maininvoiceID" =>  $invoiceMainArray[$j]["maininvoiceID"],
								];
								$net_feess = isset($invoiceMainArray[$j]["maininvoicenet_fee"]) ? $invoiceMainArray[$j]["maininvoicenet_fee"] : 0;



								$ledger_array[] = [
									"maininvoiceID" => $invoiceMainArray[$j]["maininvoiceID"],
									"studentID"     => $invoiceMainArray[$j]["maininvoicestudentID"],
									"classesID"     => $invoiceMainArray[$j]["maininvoiceclassesID"],
									"net_fee"       => isset($invoiceMainArray[$j]["maininvoicenet_fee"]) ? $invoiceMainArray[$j]["maininvoicenet_fee"] : 0,
									"refrence_no"   => $ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))],
									'description'	=> $feetype . ' fee for semster ' . $invoiceMainArray[$j]['maininvoicestd_srid'],
									"accounts_reg"  => $reg_no[$invoiceMainArray[$j]["maininvoicestudentID"]],
									"account_credit" => $get_feetype->credit_id,
									"account_debit" => $get_feetype->debit_id,
									"feetypeID"     => $get_feetype->feetypesID,
									"date"          => $invoiceMainArray[$j]["maininvoicedate"],
									"discount"      => $invoiceMainArray[$j]["maininvoice_discount"],
								];


								//$ref_no = (int) $ref_no + 1;
								$ref_array[date('ym', strtotime($invoiceMainArray[$j]["maininvoicedate"]))]++;

								$j++;
							}





							$invoicefirstID = $this->invoice_m->insert_batch_invoice($invoiceArray);
							$student_ledgers_ar     =   [];
							$journal_items_ar       =   [];

							foreach ($ledger_array as $in_ar) {
								$journal_entries_id   	=     $this->invoice_m->journal_entries_id($in_ar);

								$journal_items_ar[]  	= array(
									'journal'       =>  $journal_entries_id,
									'referenceID'   =>  $in_ar['maininvoiceID'],
									'reference_type' =>  'invoice',
									'account'       =>  $in_ar['account_credit'],
									'description'   =>  $in_ar['description'],
									'feetypeID'     =>  $in_ar['feetypeID'],
									'debit'         =>  0,
									'credit'        =>  $in_ar['net_fee'],
									'entry_type'    =>  'CR',
									'created_at'    =>  date('Y-m-d h:i:s'),
									'updated_at'    =>  date('Y-m-d h:i:s'),
								);
								$journal_items_ar[]  = array(
									'journal'       =>  $journal_entries_id,
									'referenceID'   =>  $in_ar['maininvoiceID'],
									'reference_type' =>  'invoice',
									'feetypeID'     =>  $in_ar['feetypeID'],
									'account'       =>  $in_ar['account_debit'],
									'description'   =>  $in_ar['description'],
									'debit'         =>  $in_ar['net_fee'],
									'entry_type'    =>  'DR',
									'credit'        =>  0,
									'created_at'    =>  date('Y-m-d h:i:s'),
									'updated_at'    =>  date('Y-m-d h:i:s'),
								);
								$student_ledgers_ar[]  = array(
									'journal_entries_id'       =>  $journal_entries_id,
									'maininvoice_id'           =>  $in_ar['maininvoiceID'],
									'reference_type'           =>  'invoice',
									'feetypeID'                =>  $in_ar['feetypeID'],
									'date'                     =>  date('Y-m-d', strtotime($in_ar['date'])),
									'type'                     =>  'CR',
									'account'                  =>  $in_ar['account_credit'],
									'vr_no'                    =>  $in_ar['refrence_no'],
									'narration'                =>  $in_ar['description'],
									'debit'                    =>  0,
									'credit'                   =>  $in_ar['net_fee'],
									'balance'                  =>  $in_ar['net_fee'],
									'created_at'               =>  date('Y-m-d h:i:s'),
									'updated_at'               =>  date('Y-m-d h:i:s'),
								);
								$student_ledgers_ar[]  = array(
									'journal_entries_id'       =>  $journal_entries_id,
									'maininvoice_id'           =>  $in_ar['maininvoiceID'],
									'reference_type'           =>  'invoice',
									'feetypeID'                =>  $in_ar['feetypeID'],
									'date'                     =>  date('Y-m-d', strtotime($in_ar['date'])),
									'type'                     =>  'DR',
									'account'                  =>  $in_ar['account_debit'],
									'vr_no'                    =>  $in_ar['refrence_no'],
									'narration'                =>  $in_ar['description'],
									'debit'                    =>  $in_ar['net_fee'],
									'credit'                   =>  0,
									'balance'                  =>  $in_ar['net_fee'],
									'created_at'               =>  date('Y-m-d h:i:s'),
									'updated_at'               =>  date('Y-m-d h:i:s'),
								);
							}

							$ddd =  $this->invoice_m->push_invoice_to_journal_items($journal_items_ar);
							$fff =  $this->invoice_m->push_invoice_to_studnet_ledgers($student_ledgers_ar);
						}


						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("student/index"));
					} else {
						$this->session->set_flashdata('error', "Students not Found");
						redirect(base_url("student/index"));
					}
				} else if ($feess_type == 1) {
					$this->session->set_flashdata('error', "Students not Found");
					redirect(base_url("student/index"));
				} else {
					$this->session->set_flashdata('error', "Students not Found");
					redirect(base_url("student/index"));
				}
			} else {
				$this->data["subview"] = "student/manage";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function add()
	{
		//$this->session->set_flashdata('error', 'You are not allowed to add student');
		//redirect(base_url("student/index"));


		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js'
				)
			);

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['sections'] = $this->section_m->general_get_section();
			$this->data['parents'] = $this->parents_m->get_parents();
			$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

			$classesID = $this->input->post("classesID");

			if ($classesID > 0) {
				$this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
				$this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
			} else {
				$this->data['sections'] = [];
				$this->data['optionalSubjects'] = [];
			}

			$this->data['sectionID'] = $this->input->post("sectionID");
			$this->data['optionalSubjectID'] = 0;

			if ($_POST) {

				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "student/add";
					$this->load->view('_layout_main', $this->data);
				} else {

					$sectionID = $this->input->post("sectionID");
					if ($sectionID == 0) {
						$this->data['sectionID'] = 0;
					} else {
						$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
						$this->data['sectionID'] = $this->input->post("sectionID");
					}

					if ($this->input->post('optionalSubjectID')) {
						$this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
					} else {
						$this->data['optionalSubjectID'] = 0;
					}
					$array["student_id"] = $this->input->post("student_id");
					$array["name"] = $this->input->post("name");
					$array["sex"] = $this->input->post("sex");
					$array["religion"] = $this->input->post("religion");
					$array["email"] = $this->input->post("email");
					$array["phone"] = $this->input->post("phone");
					$array["address"] = $this->input->post("address");
					$array["classesID"] = $this->input->post("classesID");
					$array["sectionID"] = $this->input->post("sectionID");
					$array["roll"] = $this->input->post("roll");
					$array["bloodgroup"] = $this->input->post("bloodgroup");
					$array["state"] = $this->input->post("state");
					$array["country"] = $this->input->post("country");
					$array["registerNO"] = $this->input->post("registerNO");
					$array["username"] = $this->input->post("username");
					$array['password'] = $this->student_m->hash($this->input->post("password"));
					$array['usertypeID'] = 3;
					$array['parentID'] = $this->input->post('guargianID');
					$array['total_fee'] = $this->input->post('totalfee');
					$array['discount'] = $this->input->post('discount');
					$array['net_fee'] = $this->input->post('net_fee');
					$array['no_installment'] = $this->input->post('InstallmentsID');
					$array['library'] = 0;
					$array['hostel'] = 0;
					$array['transport'] = 0;
					$array['createschoolyearID'] = $schoolyearID;
					$array['schoolyearID'] = $schoolyearID;
					$array["create_date"] = date("Y-m-d H:i:s");
					$array["modify_date"] = date("Y-m-d H:i:s");
					$array["create_userID"] = $this->session->userdata('loginuserID');
					$array["create_username"] = $this->session->userdata('username');
					$array["create_usertype"] = $this->session->userdata('usertype');
					$array["active"] = 1;

					if ($this->input->post('dob')) {
						$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
					}
					$array['photo'] = $this->upload_data['file']['file_name'];
					$array['decision_upload'] = $this->upload_data['file']['file_name'];
					@$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

					$this->student_m->insert_student($array);
					$studentID = $this->db->insert_id();

					$section = $this->section_m->general_get_section($this->input->post("sectionID"));
					$classes = $this->classes_m->get_classes($this->input->post("classesID"));

					if (customCompute($classes)) {
						$setClasses = $classes->classes;
					} else {
						$setClasses = NULL;
					}

					if (customCompute($section)) {
						$setSection = $section->section;
					} else {
						$setSection = NULL;
					}

					$arrayStudentRelation = array(
						'srstudentID' => $studentID,
						'srname' => $this->input->post("name"),
						'srclassesID' => $this->input->post("classesID"),
						'srclasses' => $setClasses,
						'srroll' => $this->input->post("roll"),
						'srregisterNO' => $this->input->post("registerNO"),
						'srsectionID' => $this->input->post("sectionID"),
						'srsection' => $setSection,
						'srstudentgroupID' => $this->input->post('studentGroupID'),
						'sroptionalsubjectID' => $this->input->post('optionalSubjectID'),
						'srschoolyearID' => $schoolyearID,
					);

					$studentExtendArray = array(
						'studentID' => $studentID,
						'studentgroupID' => $this->input->post('studentGroupID'),
						'optionalsubjectID' => $this->input->post('optionalSubjectID'),
						'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
						'remarks' => $this->input->post('remarks')
					);

					$this->studentextend_m->insert_studentextend($studentExtendArray);
					$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("student/index"));
				}
			} else {
				$this->data["subview"] = "student/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js'
				)
			);
			$usertype = $this->session->userdata("usertype");
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int)$studentID && (int)$url) {
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);

				$this->data['parents'] = $this->parents_m->get_parents();
				$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

				if (customCompute($this->data['student'])) {
					$classesID = $this->data['student']->srclassesID;
					$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
					$this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
					if ($this->input->post('optionalSubjectID')) {
						$this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
					} else {
						$this->data['optionalSubjectID'] = 0;
					}
				}

				$this->data['set'] = $url;
				if (customCompute($this->data['student'])) {
					if ($_POST) {
						$rules = $this->rules();
						unset($rules[21]);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "student/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array();
							//$array["student_id"] = $this->input->post("student_id");
							$array["name"] = $this->input->post("name");
							$array["sex"] = $this->input->post("sex");
							$array["religion"] = $this->input->post("religion");
							$array["email"] = $this->input->post("email");
							$array["phone"] = $this->input->post("phone");
							$array["address"] = $this->input->post("address");
							$array["classesID"] = $this->input->post("classesID");
							$array["sectionID"] = $this->input->post("sectionID");
							$array["roll"] = $this->input->post("roll");
							$array["bloodgroup"] = $this->input->post("bloodgroup");
							$array["state"] = $this->input->post("state");
							$array["country"] = $this->input->post("country");
							//$array["registerNO"] = $this->input->post("registerNO");
							$array["parentID"] = $this->input->post("guargianID");
							//$array["username"] = $this->input->post("username");
							$array["modify_date"] = date("Y-m-d H:i:s");
							$array['photo'] = $this->upload_data['file']['file_name'];

							if ($this->input->post('dob')) {
								$array["dob"] 	= date("Y-m-d", strtotime($this->input->post("dob")));
							} else {
								$array["dob"] = NULL;
							}


							$studentReletion = $this->studentrelation_m->general_get_order_by_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
							$section = $this->section_m->general_get_section($this->input->post("sectionID"));
							$classes = $this->classes_m->get_classes($this->input->post("classesID"));

							if (customCompute($classes)) {
								$setClasses = $classes->classes;
							} else {
								$setClasses = NULL;
							}

							if (customCompute($section)) {
								$setSection = $section->section;
							} else {
								$setSection = NULL;
							}

							if (!customCompute($studentReletion)) {
								$arrayStudentRelation = array(
									'srstudentID' => $studentID,
									'srname' => $this->input->post("name"),
									'srclassesID' => $this->input->post("classesID"),
									'srclasses' => $setClasses,
									'srroll' => $this->input->post("roll"),
									'srregisterNO' => $this->input->post("registerNO"),
									'srsectionID' => $this->input->post("sectionID"),
									'srsection' => $setSection,
									'srstudentgroupID' => $this->input->post("studentGroupID"),
									'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
									'srschoolyearID' => $schoolyearID
								);
								$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
							} else {
								$arrayStudentRelation = array(
									'srname' => $this->input->post("name"),
									'srclassesID' => $this->input->post("classesID"),
									'srclasses' => $setClasses,
									'srroll' => $this->input->post("roll"),
									'srregisterNO' => $this->input->post("registerNO"),
									'srsectionID' => $this->input->post("sectionID"),
									'srsection' => $setSection,
									'srstudentgroupID' => $this->input->post("studentGroupID"),
									'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
								);
								$this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
							}

							$studentExtendArray = array(
								'studentgroupID' => $this->input->post('studentGroupID'),
								'optionalsubjectID' => $this->input->post('optionalSubjectID'),
								'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
								'remarks' => $this->input->post('remarks')
							);

							$this->studentextend_m->update_studentextend_by_studentID($studentExtendArray, $studentID);
							$this->student_m->update_student($array, $studentID);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("student/index/$url"));
						}
					} else {
						$this->data["subview"] = "student/edit";
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

	public function update_status()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js'
				)
			);
			$usertype = $this->session->userdata("usertype");
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int)$studentID && (int)$url) {
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);

				$this->data['parents'] = $this->parents_m->get_parents();
				$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

				if (customCompute($this->data['student'])) {
					$classesID = $this->data['student']->srclassesID;
					$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
					$this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
					if ($this->input->post('optionalSubjectID')) {
						$this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
					} else {
						$this->data['optionalSubjectID'] = 0;
					}
				}

				$this->data['set'] = $url;
				if (customCompute($this->data['student'])) {
					if ($_POST) {
						$rules = $this->status_rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "student/update_status";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array();
							$array["active"] 		= $this->input->post("active");
							$array["modify_date"] 	= date("Y-m-d H:i:s");

							$this->student_m->update_student($array, $studentID);

							$arr 	 = array(
								'status' 	=> $this->input->post("active"),
								'reason' 	=> $this->input->post("reason"),
								'username' 	=> $this->session->userdata('username'),
							);

							$array_meta = array(
								"studentID" 	=> $studentID,
								"meta_key" 		=> 'status_update',
								"meta_value" 	=> serialize($arr),
							);
							$this->studentmeta_m->insert_studentmeta($array_meta);

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("student/index/$url"));
						}
					} else {
						$this->data["subview"] = "student/update_status";
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

	public function view()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$this->getView($id, $url);
	}

	public function print_preview()
	{


		if (permissionChecker('student_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('student') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$usertypeID = $this->session->userdata('usertypeID');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int)$id && (int)$url) {
				$this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), TRUE);
				if (customCompute($this->data["student"])) {
					$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
					$this->data["class"] = $this->classes_m->general_get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->reportPDF('studentmodule.css', $this->data, 'student/print_preview');
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

	public function send_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('student_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('student') && ($this->session->userdata('loginuserID') == $this->input->post('studentID')))) {
			if ($_POST) {
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
					if ((int)$id && (int)$url) {
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), TRUE);
						if (customCompute($this->data["student"])) {
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
							$this->data["class"] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
							$this->data["section"] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->reportSendToMail('studentmodule.css', $this->data, 'student/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('student_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('student_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('student_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('student_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function delete()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int)$id && (int)$url) {
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if (customCompute($this->data['student'])) {
					if (config_item('demo') == FALSE) {
						if ($this->data['student']->photo != 'default.png' && $this->data['student']->photo != 'defualt.png') {
							if (file_exists(FCPATH . 'uploads/images/' . $this->data['student']->photo)) {
								unlink(FCPATH . 'uploads/images/' . $this->data['student']->photo);
							}
						}
					}
					$this->student_m->delete_student($id);
					$this->studentextend_m->delete_studentextend_by_studentID($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("student/index/$url"));
				} else {
					redirect(base_url("student/index"));
				}
			} else {
				redirect(base_url("student/index/$url"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_roll()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$id) {
			$student = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $this->input->post("roll"), "srstudentID !=" => $id, "srclassesID" => $this->input->post('classesID'), 'srschoolyearID' => $schoolyearID));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $this->input->post("roll"), "srclassesID" => $this->input->post('classesID'), 'srschoolyearID' => $schoolyearID));

			if (customCompute($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function lol_username()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$student = $this->student_m->general_get_single_student(array('studentID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $student->username));
				if (customCompute($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if (in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
				if (customCompute($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if (in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function date_valid($date)
	{
		if ($date) {
			if (strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			} else {
				$arr = explode("-", $date);
				$dd = $arr[0];
				$mm = $arr[1];
				$yyyy = $arr[2];
				if (checkdate($mm, $dd, $yyyy)) {
					return TRUE;
				} else {
					$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function unique_classesID()
	{
		if ($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID()
	{
		if ($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function student_list()
	{
		$classID = $this->input->post('id');
		if ((int)$classID) {
			$string = base_url("student/index/$classID");
			echo $string;
		} else {
			redirect(base_url("student/index"));
		}
	}

	public function unique_email()
	{
		if ($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$student_info = $this->student_m->general_get_single_student(array('studentID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $student_info->username));
					if (customCompute($user)) {
						$this->form_validation->set_message("unique_email", "%s already exists");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}
				if (in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
					if (customCompute($user)) {
						$this->form_validation->set_message("unique_email", "%s already exists");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}

				if (in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
		}
		return TRUE;
	}

	public function sectioncall()
	{
		$classesID = $this->input->post('id');
		if ((int)$classesID) {
			$allsection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("student_select_section"), "</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
			}
		}
	}

	public function optionalsubjectcall()
	{
		$classesID = $this->input->post('id');
		if ((int)$classesID) {
			$allOptionalSubjects = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
			echo "<option value='0'>", $this->lang->line("student_select_optionalsubject"), "</option>";
			foreach ($allOptionalSubjects as $value) {
				echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
			}
		}
	}

	public function unique_capacity()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			if ($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->general_get_section($this->input->post('sectionID'));
				$student = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID, 'srstudentID !=' => $id));
				if (customCompute($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
					return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
				return FALSE;
			}
		} else {
			if ($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->general_get_section($this->input->post('sectionID'));
				$student = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
				if (customCompute($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
					return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
				return FALSE;
			}
		}
	}

	public function unique_registerNO()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $this->input->post("registerNO"), "srstudentID !=" => $id));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $this->input->post("registerNO")));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function active()
	{
		if (permissionChecker('student_edit')) {
			$id     = $this->input->post('id');
			$status = $this->input->post('status');
			if ($id != '' && $status != '') {
				if ((int)$id) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$student = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if (customCompute($student)) {
						if ($status == 'chacked') {
							$this->student_m->update_student(array('active' => 1), $id);
							echo 'Success';
						} elseif ($status == 'unchacked') {
							$this->student_m->update_student(array('active' => 0), $id);
							$this->db->where('userID', $id);
							$this->db->where('usertypeID', 3);
							$this->db->delete($this->config->item('sess_save_path'));
							echo 'Success';
						} else {
							echo "Error";
						}
					} else {
						echo 'Error';
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($userID, $schoolyearID, $usertypeID)
	{
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $userID, 'create_usertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'status' => 1));

		$retArray = [];
		if (customCompute($leaveapplications)) {
			$oneday    = 60 * 60 * 24;
			foreach ($leaveapplications as $leaveapplication) {
				for ($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = $i + $oneday) {
					$retArray[] = date('d-m-Y', $i);
				}
			}
		}
		return $retArray;
	}

	public function unique_data($data)
	{
		if ($data != '') {
			if ($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		}
		return TRUE;
	}
}
