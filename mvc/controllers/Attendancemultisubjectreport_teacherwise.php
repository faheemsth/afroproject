<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Attendancemultisubjectreport_teacherwise extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("subject_m");
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("student_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("studentrelation_m");
		$this->load->model("leaveapplication_m");
		$this->load->model("tattendance_m");
		$this->load->model("uattendance_m");
		$this->load->model("parents_m");
		$this->load->model("subjectteacher_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('attendanceoverviewreport', $language);
	}

	public function rules($usertype)
	{
		$rules = array(
			array(
				'field' => 'usertype',
				'label' => $this->lang->line('attendanceoverviewreport_reportfor'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'startdate',
				'label' => 'Start Date',
				'rules' => 'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'enddate',
				'label' => 'End Date',
				'rules' => 'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'teacherID',
				'label' => 'Teacher',
				'rules' => 'trim|xss_clean'
			),
		);

		if ($usertype == 1) {
			if ($this->data["siteinfos"]->attendance == 'subject') {
				$rules[] = array(
					'field' => 'subjectID',
					'label' => $this->lang->line('attendanceoverviewreport_subject'),
					'rules' => 'trim|xss_clean|callback_unique_data'
				);
			}
		}
		return $rules;
	}

	public function send_pdf_to_mail_rules($usertype)
	{
		$rules = array(
			array(
				'field' => 'usertype',
				'label' => $this->lang->line('attendanceoverviewreport_reportfor'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'monthID',
				'label' => $this->lang->line('attendanceoverviewreport_month'),
				'rules' => 'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'userID',
				'label' => $this->lang->line('attendanceoverviewreport_user'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line('attendanceoverviewreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line('attendanceoverviewreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line('attendanceoverviewreport_message'),
				'rules' => 'trim|xss_clean'
			),
		);

		if ($usertype == 1) {
			$rules[] = array(
				'field' => 'classesID',
				'label' => $this->lang->line('attendanceoverviewreport_class'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
			$rules[] = array(
				'field' => 'sectionID',
				'label' => $this->lang->line('attendanceoverviewreport_section'),
				'rules' => 'trim|xss_clean'
			);
			if ($this->data["siteinfos"]->attendance == 'subject') {
				$rules[] = array(
					'field' => 'subjectID',
					'label' => $this->lang->line('attendanceoverviewreport_subject'),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				);
			}
		}
		return $rules;
	}

	public function index()
	{
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data['teachers'] = pluck($this->teacher_m->get_order_by_teacher(), 'name', 'teacherID');

		$this->data['sections'] = pluck($this->section_m->get_order_by_section(['numric_code']), 'section', 'sectionID');
		asort($this->data['sections']);
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
		$this->data["subview"] = "report/attendancemultisubjectreport_teacherwise/AttendanceOverviewReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection()
	{
		$classesID = $this->input->post('classesID');
		if ((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendanceoverviewreport_please_select"), "</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">" . $section->section . "</option>";
			}
		}
	}

	//call this function from ajax. to load teacher wise subject
	public function getSubjects()
	{
		$teacherID = $this->input->post('teacherID');
		$sectionIDs = $this->input->post('sectionIDs');

		if ((int)$teacherID) {
			$subjects = $this->subject_m->get_subjects_by_teacher($teacherID, $sectionIDs);
			//echo "<option value='0'>", $this->lang->line("attendanceoverviewreport_please_select"),"</option>";
			foreach ($subjects as $subject) {
				echo "<option value=\"$subject->subjectID\">" . $subject->subject . "</option>";
			}
		}
	}

	public function getSubject()
	{
		$sectionID = $this->input->post('sectionID');
		if ((int)$sectionID) {
			$subjects = $this->subject_m->general_get_order_by_subject(array('sectionID' => $sectionID));
			//echo "<option value='0'>", $this->lang->line("attendanceoverviewreport_please_select"),"</option>";
			foreach ($subjects as $subject) {
				echo "<option value=\"$subject->subjectID\">" . $subject->subject . "</option>";
			}
		}
	}

	public function getStudent()
	{
		$usertype  = $this->input->post('usertype');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if ((int)$usertype && (int)$classesID && (int)$sectionID) {
			echo "<option value='0'>" . $this->lang->line("attendanceoverviewreport_please_select") . "</option>";
			if ($usertype == 1) {
				$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
				foreach ($students as $student) {
					echo "<option value=\"$student->srstudentID\">" . $student->srname . "</option>";
				}
			}
		}
	}

	public function getUser()
	{
		$usertype  = $this->input->post('usertype');
		if ((int)$usertype) {
			echo "<option value='0'>" . $this->lang->line("attendanceoverviewreport_please_select") . "</option>";
			if ($usertype == 2) {
				$teachers = $this->teacher_m->general_get_teacher();
				foreach ($teachers as $teacher) {
					echo "<option value=\"$teacher->teacherID\">" . $teacher->name . "</option>";
				}
			} elseif ($usertype == 3) {
				$users = $this->user_m->get_user();
				foreach ($users as $user) {
					echo "<option value=\"$user->userID\">" . $user->name . "</option>";
				}
			}
		}
	}

	public function getAttendacneOverviewReport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		//if (permissionChecker('attendancereport')) {
			if ($_POST) {
				$usertype 	= $this->input->post('usertype');
				$teacherID 	= $this->input->post('teacherID');
				$subjectID 	= $this->input->post('subjectID');
				$startdate 	= $this->input->post('startdate');
				$enddate 	= $this->input->post('enddate');
				// echo "<pre>";
				// print_r($enddate);
				// die();

				$rules = $this->rules($usertype);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['usertype'] = $usertype;
					$this->data['teacherID'] = $teacherID;
					$this->data['subjectID'] = $subjectID;
					$this->data['startdate']   = $startdate;
					$this->data['enddate']   = $enddate;


					$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
					if ($usertype == 1) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
					} elseif ($usertype == 2) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
					} else {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
					}
					$queryArray = $this->queryArray($this->input->post());
					$userQueryArray = $this->userQueryArray($this->input->post());

					if ($usertype == '1') {
						if ($this->data["siteinfos"]->attendance == 'subject') {

							$sub_array  = array('teacherID' => $teacherID);

							if (($subjectID) != '') {
								if (count($subjectID) > 0) {
									$sub_array['subjectID'] =	$subjectID;
								}
							}

							$attendances =  $this->subjectattendance_m->get_subject_by_teacher_wherein_subjectID_array($queryArray);

							$this->data['subjects'] = $this->subject_m->get_subject_byteacher_wherein_subjectID_array($sub_array);

							$this->data['subjects_pluck'] = pluck($this->data['subjects'], 'subject', 'subjectID');
							$allMonths = get_month_and_year_using_two_date($startdate, $enddate);

							// echo "<pre>";
							// print_r($allMonths);
							// die();

							$pluck_student_attendance = pluck_multi_array($attendances, 'obj', 'studentID');

							$arr = [
								'teacherID' => $teacherID,
								'subjectID' => $subjectID
							];


							//return teacher degree and semesters
							$teacher_subjects = $this->subjectteacher_m->get_teacher_subject($arr);

							$get_st_array = [
								'classesID' => [],
								'sectionID' => []
							];
							
							foreach ($teacher_subjects as $sub) {
								if (!in_array($sub->classesID, $get_st_array['classesID'])) {
									$get_st_array['classesID'][] = $sub->classesID;
								}
								if (!in_array($sub->sectionID, $get_st_array['sectionID'])) {
									$get_st_array['sectionID'][] = $sub->sectionID;
								}
							}


							$studentlist 	=	$this->student_m->get_order_by_student_by_teacherwise($get_st_array);
							
							foreach ($studentlist as $st) {
								
								if (isset($pluck_student_attendance[$st->studentID])) {
									$attendances_subjectwisess = pluck_multi_array_key($pluck_student_attendance[$st->studentID], 'obj', 'subjectID', 'monthyear');
								} else {

									$attendances_subjectwisess = array();
								}						
								
								
								$presentCount_total[$st->studentID] 		= 0;
								$lateexcuseCount_total[$st->studentID] 		= 0;
								$lateCount_total[$st->studentID] 			= 0;
								$absentCount_total[$st->studentID] 			= 0;

								foreach ($this->data['subjects'] as $subject) {

									
									$presentCount[$st->studentID][$subject->subjectID] 		= 0;
									$lateexcuseCount[$st->studentID][$subject->subjectID] 	= 0;
									$lateCount[$st->studentID][$subject->subjectID] 		= 0;
									$absentCount[$st->studentID][$subject->subjectID] 		= 0;



									// echo "<pre>";
									// print_r($startdate);
									// die();

									$allMonthsArray = array();

									foreach ($allMonths as $yearKey => $allMonth) {
										foreach ($allMonth as $month) {
											$monthAndYear = $month . '-' . $yearKey;
											if (isset($attendances_subjectwisess[$subject->subjectID][$monthAndYear])) {
												$attendanceMonthAndYear = $attendances_subjectwisess[$subject->subjectID][$monthAndYear];

												for ($i = 1; $i <= 31; $i++) {
													$acolumnname = 'a' . $i;
													$d = sprintf('%02d', $i);

													$date = $d . "-" . $month . "-" . $yearKey;

													
													$att_date = strtotime($date); //attendance date
													$filter_enddate = strtotime($enddate); // end date
													$filter_startdate = strtotime($startdate); // start date

													if($att_date >= $filter_startdate && $att_date <= $filter_enddate){
														
															$textcolorclass = '';
															$val = false;
															if (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'P') {
																$presentCount[$st->studentID][$subject->subjectID]++;
																$textcolorclass = 'ini-bg-success';
															} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'LE') {
																$lateexcuseCount[$st->studentID][$subject->subjectID]++;
																$textcolorclass = 'ini-bg-success';
															} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'L') {
																$lateCount[$st->studentID][$subject->subjectID]++;
																$textcolorclass = 'ini-bg-success';
															} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'A') {
																$absentCount[$st->studentID][$subject->subjectID]++;
																$textcolorclass = 'ini-bg-danger';
															} elseif ((isset($attendanceMonthAndYear) && ($attendanceMonthAndYear->$acolumnname == NULL || $attendanceMonthAndYear->$acolumnname == ''))) {
																$textcolorclass = 'ini-bg-secondary';
																$defaultVal = 'N/A';
																$val = true;
															}
													}
												}
											}
										}
									}

									$presentCount_total[$st->studentID] 		+= $presentCount[$st->studentID][$subject->subjectID];
									$lateexcuseCount_total[$st->studentID] 		+= $lateexcuseCount[$st->studentID][$subject->subjectID];
									$lateCount_total[$st->studentID] 			+= $lateCount[$st->studentID][$subject->subjectID];
									$absentCount_total[$st->studentID] 			+= $absentCount[$st->studentID][$subject->subjectID];
								}  //subject foreach close
								//var_dump($presentCount_total[$st->studentID]);
								//var_dump($lateexcuseCount_total[$st->studentID]);
								//var_dump($lateCount_total[$st->studentID]);
								//var_dump($absentCount_total[$st->studentID]);
							} //student  foreach close

						}
					}
				}

				$this->data['studentlist'] 				=	 $studentlist;
				$this->data['presentCount_total'] 		=	 isset($presentCount_total) ? $presentCount_total : [];
				$this->data['lateexcuseCount_total'] 	=    isset($lateexcuseCount_total) ? $lateexcuseCount_total : [];
				$this->data['lateCount_total'] 			=   isset($lateCount_total) ?  $lateCount_total : [];
				$this->data['absentCount_total'] 		=    isset($absentCount_total) ? $absentCount_total : [];

				$this->data['attendances'] = isset($attendances) ? $attendances : [];
				$this->data['presentCount'] 			=	 isset($presentCount) ? $presentCount : [];
				$this->data['lateexcuseCount'] 			=    isset($lateexcuseCount) ? $lateexcuseCount : [];
				$this->data['lateCount'] 				=    isset($lateCount) ? $lateCount : [];
				$this->data['absentCount'] 				=    isset($absentCount) ? $absentCount : [];
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');


				$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');
				$retArray['render'] = $this->load->view('report/attendancemultisubjectreport_teacherwise/AttendanceOverviewReport', $this->data, true);
				$retArray['status'] = TRUE;

				echo json_encode($retArray);
				exit;
			}
		// } else {
		// 	$retArray['message'] = $this->lang->line('attendanceoverviewreport_permission');;
		// 	echo json_encode($retArray);
		// 	exit;
		// }
	}

	public function debug($data)
	{
		echo "<pre>";
		print_r($data);
		die();
	}

	private function xmlData()
	{
		$usertype   = htmlentities(escapeString($this->uri->segment(3)));
		$teacherID  = htmlentities(escapeString($this->uri->segment(4)));
		$flag = TRUE;
		$subjectID = htmlentities(escapeString($this->uri->segment(5)));

		if ($this->data["siteinfos"]->attendance == 'subject') {
			$startdate    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(6))));
			$enddate    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(7))));

			$subjectID = explode('%20', $subjectID);

			if ($usertype == 1) {
				$flag = FALSE;
			}
		} else {
			$startdate    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(6))));
			$enddate    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(7))));
		}

		if ((int)$usertype && ((int)$teacherID || $teacherID >= 0) && ($flag || (int)$subjectID)) {
			$_POST['usertype'] = $usertype;
			$_POST['teacherID'] = $teacherID;
			$_POST['subjectID'] = $subjectID;
			$_POST['startdate'] = $startdate;
			$_POST['enddate'] = $enddate;

			$this->data['usertype'] = $usertype;
			$this->data['teacherID'] = $teacherID;
			$this->data['subjectID'] = $subjectID;
			$this->data['startdate']   = $startdate;
			$this->data['enddate']   = $enddate;

			$queryArray = $this->queryArray($_POST);
			$userQueryArray = $this->userQueryArray($this->input->post());


			if ($usertype == 1) {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
			} elseif ($usertype == 2) {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
			} else {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
			}



			if ($usertype == '1') {
				if ($this->data["siteinfos"]->attendance == 'subject') {
					$sub_array  = array('teacherID' => $teacherID);

					if (($subjectID) != '') {
						if (count($subjectID) > 0) {
							$sub_array['subjectID'] =	$subjectID;
						}
					}

					$attendances =  $this->subjectattendance_m->get_subject_by_teacher_wherein_subjectID_array($queryArray);

					$this->data['subjects'] = $this->subject_m->get_subject_byteacher_wherein_subjectID_array($sub_array);

					$this->data['subjects_pluck'] = pluck($this->data['subjects'], 'subject', 'subjectID');
					$allMonths = get_month_and_year_using_two_date($startdate, $enddate);

					$pluck_student_attendance = pluck_multi_array($attendances, 'obj', 'studentID');

					$arr = [
						'teacherID' => $teacherID,
						'subjectID' => $subjectID
					];


					//return teacher degree and semesters
					$teacher_subjects = $this->subjectteacher_m->get_teacher_subject($arr);


					$get_st_array = [];
					foreach ($teacher_subjects as $sub) {
						$get_st_array['classesID'][] = $sub->classesID;
						$get_st_array['sectionID'][] = $sub->sectionID;
					}

					$studentlist 	=	$this->student_m->get_order_by_student_by_teacherwise($get_st_array);
					foreach ($studentlist as $st) {


						if (isset($pluck_student_attendance[$st->studentID])) {
							$attendances_subjectwisess = pluck_multi_array_key($pluck_student_attendance[$st->studentID], 'obj', 'subjectID', 'monthyear');
						} else {

							$attendances_subjectwisess = array();
						}



						$presentCount_total[$st->studentID] 		= 0;
						$lateexcuseCount_total[$st->studentID] 		= 0;
						$lateCount_total[$st->studentID] 			= 0;
						$absentCount_total[$st->studentID] 			= 0;

						foreach ($this->data['subjects'] as $subject) {
							$presentCount[$st->studentID][$subject->subjectID] 		= 0;
							$lateexcuseCount[$st->studentID][$subject->subjectID] 	= 0;
							$lateCount[$st->studentID][$subject->subjectID] 		= 0;
							$absentCount[$st->studentID][$subject->subjectID] 		= 0;





							$allMonthsArray = array();

							foreach ($allMonths as $yearKey => $allMonth) {
								foreach ($allMonth as $month) {
									$monthAndYear = $month . '-' . $yearKey;
									if (isset($attendances_subjectwisess[$subject->subjectID][$monthAndYear])) {
										$attendanceMonthAndYear = $attendances_subjectwisess[$subject->subjectID][$monthAndYear];

										for ($i = 1; $i <= 31; $i++) {
											$acolumnname = 'a' . $i;
											$d = sprintf('%02d', $i);

											$date = $d . "-" . $month . "-" . $yearKey;

											$att_date = strtotime($date); //attendance date
											$filter_enddate = strtotime($enddate); // end date
											$filter_startdate = strtotime($startdate); // start date

											if($att_date >= $filter_startdate && $att_date <= $filter_enddate){
												
													$textcolorclass = '';
													$val = false;
													if (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'P') {
														$presentCount[$st->studentID][$subject->subjectID]++;
														$textcolorclass = 'ini-bg-success';
													} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'LE') {
														$lateexcuseCount[$st->studentID][$subject->subjectID]++;
														$textcolorclass = 'ini-bg-success';
													} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'L') {
														$lateCount[$st->studentID][$subject->subjectID]++;
														$textcolorclass = 'ini-bg-success';
													} elseif (isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'A') {
														$absentCount[$st->studentID][$subject->subjectID]++;
														$textcolorclass = 'ini-bg-danger';
													} elseif ((isset($attendanceMonthAndYear) && ($attendanceMonthAndYear->$acolumnname == NULL || $attendanceMonthAndYear->$acolumnname == ''))) {
														$textcolorclass = 'ini-bg-secondary';
														$defaultVal = 'N/A';
														$val = true;
													}
											}
										}
									}
								}
							}

							$presentCount_total[$st->studentID] 		+= $presentCount[$st->studentID][$subject->subjectID];
							$lateexcuseCount_total[$st->studentID] 		+= $lateexcuseCount[$st->studentID][$subject->subjectID];
							$lateCount_total[$st->studentID] 			+= $lateCount[$st->studentID][$subject->subjectID];
							$absentCount_total[$st->studentID] 			+= $absentCount[$st->studentID][$subject->subjectID];
						}  //subject foreach close
						//var_dump($presentCount_total[$st->studentID]);
						//var_dump($lateexcuseCount_total[$st->studentID]);
						//var_dump($lateCount_total[$st->studentID]);
						//var_dump($absentCount_total[$st->studentID]);
					} //student  foreach close

				}
			}

			// if ($usertype == '1') {
			// 	$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
			// } elseif ($usertype == '2') {
			// 	$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
			// } elseif ($usertype == '3') {
			// 	$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
			// }


			$this->data['studentlist'] 				=	 $studentlist;
			$this->data['presentCount_total'] 		=	 isset($presentCount_total) ? $presentCount_total : [];
			$this->data['lateexcuseCount_total'] 	=    isset($lateexcuseCount_total) ? $lateexcuseCount_total : [];
			$this->data['lateCount_total'] 			=   isset($lateCount_total) ?  $lateCount_total : [];
			$this->data['absentCount_total'] 		=    isset($absentCount_total) ? $absentCount_total : [];

			$this->data['attendances'] = isset($attendances) ? $attendances : [];
			$this->data['presentCount'] 			=	 isset($presentCount) ? $presentCount : [];
			$this->data['lateexcuseCount'] 			=    isset($lateexcuseCount) ? $lateexcuseCount : [];
			$this->data['lateCount'] 				=    isset($lateCount) ? $lateCount : [];
			$this->data['absentCount'] 				=    isset($absentCount) ? $absentCount : [];
			$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
			$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
			$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');
			return $this->generateXML($this->data);

			// echo "<pre>";
			// print_r($this->data);
			// die();

		} else {
			redirect('attendancemultisubjectreport_teacherwise');
		}
	}

	private function userQueryArray($posts)
	{
		$userQueryArray = [];
		if ($posts['usertype'] == '1') {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$userQueryArray['srschoolyearID'] = $schoolyearID;
			$userQueryArray['teacherID']     = $posts['teacherID'];
		} elseif ($posts['usertype'] == '2') {
			if ($posts['userID'] > 0) {
				$userQueryArray['teacherID'] = $posts['userID'];
			}
		}
		return $userQueryArray;
	}

	private function queryArray($posts)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		//$queryArray['schoolyearID'] = $schoolyearID; 				
		// if($posts['monthID'] !='') {
		// 	$queryArray['monthyear'] = $posts['monthID'];
		// }
		if ($posts['usertype'] == '1') {
			$queryArray['teacherID']     = $posts['teacherID'];

			if ($this->data["siteinfos"]->attendance == 'subject') {
				if ($posts['subjectID'] > 0) {
					$queryArray['subjectID'] = $posts['subjectID'];
				}
			}
		}
		return $queryArray;
	}

	public function pdf()
	{
		if (permissionChecker('attendanceoverviewreport')) {
			$usertype   = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));

			$flag = TRUE;
			$subjectID = 0;
			if ($this->data["siteinfos"]->attendance == 'subject') {
				$subjectID  = htmlentities(escapeString($this->uri->segment(6)));
				$userID     = htmlentities(escapeString($this->uri->segment(7)));
				$monthID    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(8))));
				if ($usertype == 1) {
					$flag = FALSE;
				}
			} else {
				$userID     = htmlentities(escapeString($this->uri->segment(6)));
				$monthID    = date('d-m-Y', (int)htmlentities(escapeString($this->uri->segment(7))));
			}

			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			$monthyears     = explode('-', $monthID);
			$monthyear      = $monthyears[1] . '-' . $monthyears[2];

			if ((int)$usertype && ((int)$classesID || $classesID >= 0) && ((int)$sectionID || $sectionID >= 0) && ($flag || (int)$subjectID) && ((int)$userID || $userID >= 0) && (int)strtotime($monthID)) {
				$this->data['usertype']  = $usertype;
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				if ($this->data["siteinfos"]->attendance == 'subject') {
					$this->data['subjectID'] = $subjectID;
				}
				$this->data['userID']    = $userID;
				$this->data['monthID']   = $monthyear;

				if ($usertype == 1) {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
				} elseif ($usertype == 2) {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
				} else {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
				}

				$postsArray['usertype']  = $usertype;
				$postsArray['classesID'] = $classesID;
				$postsArray['sectionID'] = $sectionID;
				if ($this->data["siteinfos"]->attendance == 'subject') {
					$postsArray['subjectID'] = $subjectID;
				}
				$postsArray['userID']    = $userID;
				$postsArray['monthID']   = $monthyear;

				$queryArray = $this->queryArray($postsArray);
				$userQueryArray = $this->userQueryArray($postsArray);

				if ($usertype == '1') {
					if ($this->data["siteinfos"]->attendance == 'subject') {
						$attendances =  pluck($this->subjectattendance_m->get_order_by_sub_attendance($queryArray), 'obj', 'studentID');
						$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'subject', 'subjectID');
					} else {
						$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray), 'obj', 'studentID');
					}
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1, $schoolyearID);
					$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
				} elseif ($usertype == '2') {
					$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray), 'obj', 'teacherID');
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2, $schoolyearID);
					$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
				} elseif ($usertype == '3') {
					$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray), 'obj', 'userID');
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3, $schoolyearID);
					$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
				}
				$this->data['attendances'] = $attendances;
				$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
				$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$this->reportPDF('attendanceoverviewreport.css', $this->data, 'report/attendancemultisubjectreport_teacherwise/AttendanceOverviewReportPDF', 'view', 'a4', 'l');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_data($data)
	{
		if ($data != "") {
			if ($data == "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function send_pdf_to_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('attendanceoverviewreport')) {
			if ($_POST) {
				$usertype  = $this->input->post('usertype');
				$classesID = $this->input->post('classesID');
				$sectionID = $this->input->post('sectionID');
				$subjectID = $this->input->post('subjectID');
				$userID    = $this->input->post('userID');
				$monthID   = $this->input->post('monthID');
				$to        = $this->input->post('to');
				$subject   = $this->input->post('subject');
				$message   = $this->input->post('message');
				$rules = $this->send_pdf_to_mail_rules($usertype);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
				} else {
					$this->data['usertype']  = $usertype;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['subjectID'] = $subjectID;
					$this->data['userID']    = $userID;
					$this->data['monthID']   = $monthID;
					$schoolyearID   = $this->session->userdata('schoolyearID');

					if ($usertype == 1) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
					} elseif ($usertype == 2) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
					} else {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
					}
					$queryArray = $this->queryArray($this->input->post());
					$userQueryArray = $this->userQueryArray($this->input->post());

					if ($usertype == '1') {
						if ($this->data["siteinfos"]->attendance == 'subject') {
							$attendances =  pluck(
								$this->subjectattendance_m->get_order_by_sub_attendance($queryArray),
								'obj',
								'studentID'
							);
							$this->data['subjects'] = pluck($this->subject_m->get_order_by_subject(array('classesID' => $classesID)), 'subject', 'subjectID');
						} else {
							$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray), 'obj', 'studentID');
						}
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1, $schoolyearID);
						$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
					} elseif ($usertype == '2') {
						$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray), 'obj', 'teacherID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2, $schoolyearID);
						$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
					} elseif ($usertype == '3') {
						$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray), 'obj', 'userID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3, $schoolyearID);
						$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
					}
					$this->data['attendances'] = $attendances;
					$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
					$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->reportSendToMail('attendanceoverviewreport.css', $this->data, 'report/attendancemultisubjectreport_teacherwise/AttendanceOverviewReportPDF', $to, $subject, $message, 'a4', 'l');
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('attendanceoverviewreport_permissionmethod');;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('attendanceoverviewreport_permission');;
			echo json_encode($retArray);
			exit;
		}
	}

	// public function valid_date() {
	// 	$date = $this->input->post('monthID');
	// 	$date = '01-'.$date;
	// 	if(!empty($date)) {
	// 		if(strlen($date) == 10) {
	// 			$expDate = explode('-', $date);
	// 			if(checkdate($expDate[1], $expDate[0], $expDate[2])) {
	// 				return TRUE;
	// 			} else {
	// 				$this->form_validation->set_message('valid_date', 'The %s is dd-mm-yyyy');
	// 				return FALSE;
	// 			}
	// 		} else {
	// 			$this->form_validation->set_message('valid_date', 'The %s is dd-mm-yyyy');
	// 			return FALSE;
	// 		}
	// 	} 
	// 	return TRUE;
	// }
	public function valid_date($date)
	{
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

	public function xlsx()
	{

		if (permissionChecker('attendanceoverviewreport')) {
			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(5);
			$sheet->getDefaultRowDimension()->setRowHeight(25);

			$sheet->getPageSetup()->setFitToWidth(1);
			$sheet->getPageSetup()->setFitToHeight(0);

			$sheet->getPageMargins()->setTop(1);
			$sheet->getPageMargins()->setRight(0.75);
			$sheet->getPageMargins()->setLeft(0.75);
			$sheet->getPageMargins()->setBottom(1);

			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="attendanceoverviewreport.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function generateXML($data)
	{
		// echo "<pre>";
		// print_r($data);
		// die();
		extract($data);
		if (customCompute($studentlist)) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

			$sheet->mergeCells('A1:E1');
			$sheet->setCellValue('A1', 'Subjects Name');

			$startColumn = 'F';
			$endColumn = 'I';

			$columnIndex = Coordinate::columnIndexFromString($startColumn);
			$endcolumnIndex = $columnIndex + 3; // Add 3 to move 4 columns over

			foreach ($subjects as $key => $subject) {
				$range = Coordinate::stringFromColumnIndex($columnIndex) . '1:' . Coordinate::stringFromColumnIndex($endcolumnIndex) . '1';
				$sheet->mergeCells($range);
				$sheet->setCellValueByColumnAndRow($columnIndex, 1, $subject->subject);
				$columnIndex += 4;
				$endcolumnIndex += 4; // Update the end column index for the next iteration
			}



			//show student general info in 2 row
			$sheet->setCellValue('A2', 'SN');
			$sheet->setCellValue('B2', 'Roll No');
			$sheet->setCellValue('C2', 'Student Name');
			$sheet->setCellValue('D2', 'F/Name');
			$sheet->setCellValue('E2', 'Registration No');

			$column = 'F';
			foreach ($subjects as  $subject) {
				// Set the value of the merged cell to the current subject name
				$sheet->setCellValue($column . '2', 'LECTURES HELD');
				$column++;

				$sheet->setCellValue($column . '2', 'ABSENTS');
				$column++;

				$sheet->setCellValue($column . '2', '% age');
				$column++;

				$sheet->setCellValue($column . '2', 'Fine');
				$column++;
			}


			$sheet->setCellValue($column . '2', 'Total Lectures Held');
			$column++;

			$sheet->setCellValue($column . '2', 'Total Absents');
			$column++;

			$sheet->setCellValue($column . '2', '% age');
			$column++;


			$sheet->setCellValue($column . '2', 'Fine');
			$column++;



			$row = '3';
			//now getting student data
			foreach ($studentlist as $key => $user) {
				$student_percantage         = 0;
				$student_fine               = 0;
				$student_total_attendance   = 0;
				$student_total_absent       = 0;


				$column = 'A';
				$count = $key + 1;
				$sheet->setCellValue($column . $row, "$count");
				$column++;

				$sheet->setCellValue($column . $row,"$user->accounts_reg");
				$column++;

				$sheet->setCellValue($column . $row, $user->name);
				$column++;

				$parent = isset($parents[$user->parentID]) ? $parents[$user->parentID] : ' ';
				$sheet->setCellValue($column . $row,"$parent");
				$column++;

				$sheet->setCellValue($column . $row,"$user->registerNO");
				$column++;


				foreach ($subjects as $sub) {
					$sub_per = 0;
					$sub_fine = 0;

					$total_sub_at_held =  $presentCount[$user->studentID][$sub->subjectID] + $lateexcuseCount[$user->studentID][$sub->subjectID] + $lateCount[$user->studentID][$sub->subjectID] + $absentCount[$user->studentID][$sub->subjectID];
					$student_total_attendance   += $total_sub_at_held;
					$student_total_absent       += $absentCount[$user->studentID][$sub->subjectID];


					$sheet->setCellValue($column . $row,"$total_sub_at_held");
					$column++;


					$abs = $absentCount[$user->studentID][$sub->subjectID];
					$sheet->setCellValue($column . $row,"$abs");
					$column++;

					if ($total_sub_at_held == 0)
						$sub_per = 100;
					else
						$sub_per    =   100 - round(((($absentCount[$user->studentID][$sub->subjectID]) / $total_sub_at_held) * 100), 2);


					$sheet->setCellValue($column . $row,"$sub_per");
					$column++;

					$student_percantage += $sub_per;
					// var_dump($sub_per < $siteinfos->attendance_fine_percentage);
					// die();
					if ($sub_per < $siteinfos->attendance_fine_percentage) {

						$per_fine_number    =   $siteinfos->attendance_fine_percentage - $sub_per;
						$sub_fine           =   $siteinfos->attendance_per_percantage_fine * $per_fine_number;


						$sheet->setCellValue($column . $row,"$sub_fine");
						$column++;

						$student_fine       += $sub_fine;
					} else {

						$sheet->setCellValue($column . $row, "0");
						$column++;
					}
				}


				$sheet->setCellValue($column . $row,"$student_total_attendance");
				$column++;

				$sheet->setCellValue($column . $row,"$student_total_absent");
				$column++;

				$total_per = ($student_percantage / count($subjects));
				$sheet->setCellValue($column . $row,"$total_per" );
				$column++;

				$sheet->setCellValue($column . $row,"$student_fine");
				$column++;

				$row++;
			}
		} else {
			redirect('attendancemultisubjectreport_teacherwise');
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($usertype, $schoolyearID)
	{
		$queryArray = [];
		$queryArray['usercheck'] = FALSE;
		if ($usertype == 1) {
			$queryArray['create_usertypeID'] = 3;
		} elseif ($usertype == 2) {
			$queryArray['create_usertypeID'] = 2;
		} elseif ($usertype == 3) {
			$queryArray['usercheck'] = TRUE;
		}
		$queryArray['status'] = 1;
		$queryArray['schoolyearID'] = $schoolyearID;

		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication_where_in($queryArray);

		$retArray = [];
		if (customCompute($leaveapplications)) {
			$oneday    = 60 * 60 * 24;
			foreach ($leaveapplications as $leaveapplication) {
				for ($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = $i + $oneday) {
					$retArray[$leaveapplication->create_userID][] = date('d-m-Y', $i);
				}
			}
		}
		return $retArray;
	}
}
