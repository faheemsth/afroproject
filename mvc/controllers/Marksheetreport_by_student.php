<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Marksheetreport_by_student extends Admin_Controller
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
		$this->load->model("exam_m");
		$this->load->model("student_m");

		$this->load->model("classes_m");
		$this->load->model('section_m');
		
		$this->load->model("parents_m");
		$this->load->model("subject_m");
		$this->load->model("studentrelation_m");
		$this->load->model("setting_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("markpercentage_m");
		$this->load->model("marksetting_m");
		$this->load->model("markdistribution_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('marksheetreport', $language);
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("marksheetreport_exam"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'studentID',
				'label' => 'Student Id',
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	protected function send_pdf_to_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("marksheetreport_exam"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'studentID',
				'label' => 'Student Id',
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("marksheetreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("marksheetreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("marksheetreport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
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

		//$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data['exams']    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID), 'exam', 'examID');


		$this->data['students'] = pluck($this->student_m->get_student_all('studentID, registerNO'), 'registerNO', 'studentID'); 
		$this->data["subview"] = "report/marksheet_by_student/MarksheetReportViewByStudent";
		$this->load->view('_layout_main', $this->data);
	}

	public function getMarksheetreport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('marksheetreport_by_student')) {
			if ($_POST) {
				// echo "<pre>";
				// print_r($_POST);
				// die();
				$examID       = $this->input->post('examID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules        = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['examID']          = $examID;
					$this->data['studentID']       = $studentID;
					    

					$queryArray['srschoolyearID']  = $schoolyearID;
					

					$exams                  = $this->exam_m->get_single_exam(['examID' => $examID]);
					$queryArray['studentID']  = $studentID;


					$this->data['examName'] = $exams->exam;
					$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);

					
					$classesID = $this->classes_m->get_order_by_classes(array('classesID'=>$students[0]->srclassesID))[0]->classesID;
					$sectionID = $this->section_m->get_order_by_section(array('sectionID'=>$students[0]->srsectionID))[0]->sectionID;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
			
					

					$marks                  = $this->mark_m->student_all_mark_array(array('examID' => $examID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID, 'studentID' => $studentID));
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'sectionID' => $sectionID, 'type' => 1));
					//$subjects               = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'obj', 'subjectID');
					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$this->data['markpercentagesArr']  = $markpercentagesArr;
					$this->data['settingmarktypeID']   = $settingmarktypeID;
					
					$retMark           = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$studenGrades      = [];

					
					//get_total_distribution marks
					$filter['ClassID'] = $classesID;
					$filter['ExamID'] =  $examID;
					$filter['SectionID'] = $sectionID;


					$distribution_marks = $this->markdistribution_m->get_order_by_markdistribution($filter);
					$d_marks = [];
					if (!empty($distribution_marks)) {
						foreach ($distribution_marks as $d_mark) {
							$d_marks[$d_mark->SubjectID][$d_mark->MarkPercentageID] = $d_mark->total_marks;
						}
					}


					//$distibution_marks = 
					$student_totals = [];
					if (customCompute($students)) {
						foreach ($students as $student) {
							if (customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {
									$student_totals[$student->srstudentID][$mandatorySubject->subjectID] = isset($d_marks[$mandatorySubject->subjectID]) ? array_sum($d_marks[$mandatorySubject->subjectID]) : 0;
								}
							}
						}
					}



					$this->data['studentGrades'] = $studenGrades;
					$this->data['student_totals'] = $student_totals;
					$this->data['studentlist'] = $students;
					$this->data['subjects'] = $mandatorySubjects;
					$this->data['marks'] = $retMark;
					$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');
					$this->data['studentID'] = $studentID;

					$retArray['render'] = $this->load->view('report/marksheet_by_student/MarksheetReport', $this->data, true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf()
	{
		if (permissionChecker('marksheetreport_by_student')) {
			$examID       = htmlentities(escapeString($this->uri->segment(3)));
			$classesID    = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID    = htmlentities(escapeString($this->uri->segment(5)));
			$studentID = htmlentities(escapeString($this->uri->segment(6)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0)) {
				$this->data['examID']    = $examID;
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;

				$queryArray['srschoolyearID']  = $schoolyearID;
				if ((int)$classesID > 0) {
					$queryArray['srclassesID'] = $classesID;
				}
				if ((int)$sectionID > 0) {
					$queryArray['srsectionID'] = $sectionID;
				}

				$exams                  = $this->exam_m->get_single_exam(['examID' => $examID]);
				//$grades                 = $this->grade_m->get_grade();
				$queryArray['studentID'] =  $studentID;


				$this->data['examName'] = $exams->exam;
				$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
	
					$marks                  = $this->mark_m->student_all_mark_array(array('examID' => $examID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID, 'studentID' => $studentID));
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'sectionID' => $sectionID, 'type' => 1));

				//$subjects               = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'obj', 'subjectID');
				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$this->data['markpercentagesArr']  = $markpercentagesArr;
				$this->data['settingmarktypeID']   = $settingmarktypeID;

				$retMark           = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}


				//get_total_distribution marks
				$filter['ClassID'] = $classesID;
				$filter['ExamID'] =  $examID;
				$filter['SectionID'] = $sectionID;

				$distribution_marks = $this->markdistribution_m->get_order_by_markdistribution($filter);
				$d_marks = [];
				if (!empty($distribution_marks)) {
					foreach ($distribution_marks as $d_mark) {
						$d_marks[$d_mark->SubjectID][$d_mark->MarkPercentageID] = $d_mark->total_marks;
					}
				}

				$studenGrades      = [];
				if (customCompute($students)) {
					foreach ($students as $student) {
						if (customCompute($mandatorySubjects)) {
							foreach ($mandatorySubjects as $mandatorySubject) {
								$student_totals[$student->srstudentID][$mandatorySubject->subjectID] = isset($d_marks[$mandatorySubject->subjectID]) ? array_sum($d_marks[$mandatorySubject->subjectID]) : 0;
							}
						}
					}
				}

				$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');
				$this->data['studentGrades'] = $studenGrades;
				$this->data['student_totals'] = $student_totals;
				$this->data['studentlist'] = $students;
				$this->data['subjects'] = $mandatorySubjects;
				$this->data['marks'] = $retMark;
				$this->data['studentID'] = $studentID;
				$this->reportPDF('marksheetreport.css', $this->data, 'report/marksheet_by_student/MarksheetReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}



	public function send_pdf_to_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('marksheetreport_by_student')) {
			if ($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$examID       = $this->input->post('examID');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->send_pdf_to_mail_rules();

				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['examID']    = $examID;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;


					$queryArray['srschoolyearID']  = $schoolyearID;
					if ((int)$classesID > 0) {
						$queryArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID > 0) {
						$queryArray['srsectionID'] = $sectionID;
					}

					$exams                  = $this->exam_m->get_single_exam(['examID' => $examID]);
					$grades                 = $this->grade_m->get_grade();
					$this->data['examName'] = $exams->exam;
					$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$marks                  = $this->mark_m->student_all_mark_array(array('examID' => $examID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID));
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					$subjects               = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'obj', 'subjectID');

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
					$this->data['markpercentagesArr']  = $markpercentagesArr;
					$this->data['settingmarktypeID']   = $settingmarktypeID;

					$retMark           = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$studentPositon    = [];
					$studentChecker    = [];
					$studenGradeArray  = [];
					$studenGrades      = [];
					if (customCompute($students)) {
						foreach ($students as $student) {
							$opuniquepercentageArr = [];
							if ($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							$oppercentageMark    = 0;
							if (customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {

									$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];
									if (customCompute($uniquepercentageArr)) {
										$markpercentages     = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
									} else {
										$markpercentages     = [];
									}

									$percentageMark      = 0;
									if (customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentageID) {
											$f = false;
											if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
												$f = true;
												$percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
											}

											if (isset($studentPositon[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPositon[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPositon[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPositon[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPositon[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
												}
											}

											$f = false;
											if (customCompute($opuniquepercentageArr)) {
												if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
													$f = true;
												}
											}
											if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;

												if ($student->sroptionalsubjectID > 0) {
													if (isset($studentPositon[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPositon[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPositon[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPositon[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPositon[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}
												}
												$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
											}
										}
									}

									$studentPositon[$student->srstudentID]['percentageMark'][$mandatorySubject->subjectID] = $percentageMark;
								}
							}
							if ($student->sroptionalsubjectID > 0) {
								$studentPositon[$student->srstudentID]['percentageMark'][$student->sroptionalsubjectID] = $oppercentageMark;
							}

							$percentageMark      = $studentPositon[$student->srstudentID]['percentageMark'];
							$studentSubjectMarks = isset($studentPositon[$student->srstudentID]['subjectMark']) ? $studentPositon[$student->srstudentID]['subjectMark'] : [];
							if (customCompute($studentSubjectMarks)) {
								foreach ($studentSubjectMarks as $subjectID => $subjectMark) {
									$finalMark   = isset($subjects[$subjectID]) ? $subjects[$subjectID]->finalmark : 0;
									$percentMark = isset($percentageMark[$subjectID]) ? $percentageMark[$subjectID] : 0;
									$subjectMark = markCalculationView($subjectMark, $finalMark, $percentMark);
									if (customCompute($grades)) {
										foreach ($grades as $grade) {
											if (($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
												if (isset($studenGradeArray[$student->srstudentID])) {
													$studenGradeArray[$student->srstudentID] += $grade->point;
												} else {
													$studenGradeArray[$student->srstudentID] = $grade->point;
												}
											}
										}
									}
								}
							}

							if (customCompute($studenGradeArray)) {
								$totalSubject = customCompute($studentSubjectMarks);
								if (isset($studenGradeArray[$student->srstudentID])) {
									$studenGrades[$student->srroll] = ini_round($studenGradeArray[$student->srstudentID] / $totalSubject);
								} else {
									$studenGrades[$student->srroll] = ini_round(0);
								}
							}
						}
					}
					$this->data['studentGrades'] = $studenGrades;



					$this->reportSendToMail('marksheetreport.css', $this->data, 'report/marksheet_by_student/MarksheetReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('marksheetreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('marksheetreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function unique_data($data)
	{
		if ($data != "") {
			if ($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		}
		return TRUE;
	}


	public function xlsx()
	{

		//if (permissionChecker('attendanceoverviewreport')) {
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
			header('Content-Disposition: attachment;filename="marksheetreport.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
		// } else {
		// 	$this->data["subview"] = "error";
		// 	$this->load->view('_layout_main', $this->data);
		// }
	}

	private function xmlData()
	{
		if (permissionChecker('terminalreport')) {
			$examID       = htmlentities(escapeString($this->uri->segment(3)));
			$classesID    = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID    = htmlentities(escapeString($this->uri->segment(5)));
			$studentID    = htmlentities(escapeString($this->uri->segment(6)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0)) {
				$this->data['examID']    = $examID;
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;

				$queryArray['srschoolyearID']  = $schoolyearID;
				if ((int)$classesID > 0) {
					$queryArray['srclassesID'] = $classesID;
				}
				if ((int)$sectionID > 0) {
					$queryArray['srsectionID'] = $sectionID;
				}

				$exams                  = $this->exam_m->get_single_exam(['examID' => $examID]);
				//$grades                 = $this->grade_m->get_grade();
				$queryArray['studentID'] =  $studentID;


				$this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');


				$this->data['examName'] = $exams->exam;
				$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
				$marks                  = $this->mark_m->student_all_mark_array(array('examID' => $examID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID, 'studentID' => $studentID));
				$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'sectionID' => $sectionID, 'type' => 1));
				//$subjects               = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'obj', 'subjectID');
				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$this->data['markpercentagesArr']  = $markpercentagesArr;
				$this->data['settingmarktypeID']   = $settingmarktypeID;

				$retMark           = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}


				//get_total_distribution marks
				$filter['ClassID'] = $classesID;
				$filter['ExamID'] =  $examID;
				$filter['SectionID'] = $sectionID;

				$distribution_marks = $this->markdistribution_m->get_order_by_markdistribution($filter);
				$d_marks = [];
				if (!empty($distribution_marks)) {
					foreach ($distribution_marks as $d_mark) {
						$d_marks[$d_mark->SubjectID][$d_mark->MarkPercentageID] = $d_mark->total_marks;
					}
				}

				$studenGrades      = [];
				if (customCompute($students)) {
					foreach ($students as $student) {
						if (customCompute($mandatorySubjects)) {
							foreach ($mandatorySubjects as $mandatorySubject) {
								$student_totals[$student->srstudentID][$mandatorySubject->subjectID] = isset($d_marks[$mandatorySubject->subjectID]) ? array_sum($d_marks[$mandatorySubject->subjectID]) : 0;
							}
						}
					}
				}
				$this->data['studentGrades'] = $studenGrades;
				$this->data['student_totals'] = $student_totals;
				$this->data['studentlist'] = $students;
				$this->data['subjects'] = $mandatorySubjects;
				$this->data['marks'] = $retMark;
				return $this->generateXML($this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			redirect('marksheetreport');
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
			$sheet->setCellValue('A1', 'Student Information');

			$startColumn = 'F';

			$columnIndex = Coordinate::columnIndexFromString($startColumn);
			$endcolumnIndex = $columnIndex + 1; // Add 3 to move 4 columns over

			foreach ($subjects as $key => $subject) {
				$range = Coordinate::stringFromColumnIndex($columnIndex) . '1:' . Coordinate::stringFromColumnIndex($endcolumnIndex) . '1';
				$sheet->mergeCells($range);
				$sheet->setCellValueByColumnAndRow($columnIndex, 1, $subject->subject);
				$columnIndex += 2;
				$endcolumnIndex += 2; // Update the end column index for the next iteration
			}



			// Show student general info in 2 rows
			$sheet->setCellValue('A2', 'SN');
			$sheet->setCellValue('B2', 'Roll No');
			$sheet->setCellValue('C2', 'Student Name');
			$sheet->setCellValue('D2', 'F/Name');
			$sheet->setCellValue('E2', 'Registration No');

			$column = 'F';
			foreach ($subjects as  $subject) {
				// Set the value of the merged cell to the current subject name
				$sheet->setCellValue($column . '2', 'Total');
				$column++;

				$sheet->setCellValue($column . '2', 'Obtained');
				$column++;
			}

			$sheet->setCellValue($column . '2', 'Total');
			$column++;

			$sheet->setCellValue($column . '2', 'Obtained');
			$column++;

			// echo "<pre>";
			// print_r($studentlist);
			// die();


			$row = '3';
			$count = 1;
			foreach ($studentlist as $key => $user) {
				
				$column = 'A';
				
				$count = $key+1;
				$sheet->setCellValue($column.$row, "$count");
				$column++;

				$sheet->setCellValue($column . $row, $user->accounts_reg);
				$column++;

				$sheet->setCellValue($column . $row, $user->name);
				$column++;

				$sheet->setCellValue($column . $row, isset($parents[$user->parentID]) ? $parents[$user->parentID] : ' ');
				$column++;

				$sheet->setCellValue($column . $row, $user->registerNO);
				$column++;

				$total_sub_mark = 0;
				$total_obtain_mark = 0;

				foreach ($subjects as $sub) {
					
					$total_sub_mark += $student_totals[$user->srstudentID][$sub->subjectID];
                    $total_obtain_mark += isset($marks[$user->srstudentID][$sub->subjectID]) ? array_sum($marks[$user->srstudentID][$sub->subjectID]) : 0;

					$mark1 = isset($student_totals[$user->srstudentID][$sub->subjectID]) ? $student_totals[$user->srstudentID][$sub->subjectID] : 0;
					$sheet->setCellValue($column . $row, "$mark1");
					$column++;


					$mark2 = isset($marks[$user->srstudentID][$sub->subjectID]) ? array_sum($marks[$user->srstudentID][$sub->subjectID]) : 0;
					$sheet->setCellValue($column . $row, "$mark2");
					$column++;
				}

				$sheet->setCellValue($column . $row, "$total_sub_mark");
				$column++;

				$sheet->setCellValue($column . $row, "$total_obtain_mark");
				$column++;

				$row++;
			}

		} else {
			redirect('marksheetreport');
		}
	}
}
