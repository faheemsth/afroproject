<?php

class Balancefeesreport extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('feetypes_m');
		$this->load->model('section_m');
		$this->load->model('student_m');
		$this->load->model('schoolyear_m');
		$this->load->model('invoice_m');
		$this->load->model('studentrelation_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('parents_m');
		$this->load->model('payment_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('balancefeesreport', $language);
	}

	public function rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('balancefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('balancefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line('balancefeesreport_student'),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('balancefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('balancefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line('balancefeesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line('balancefeesreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line('balancefeesreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line('balancefeesreport_message'),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index()
	{
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

		$this->data["studentID"]            = "";
		$this->data["classesID"]            = "";
		$this->data["sectionID"]            = "";
		$this->data["nameSearch"]           = "";
		$this->data["maininvoice_type_v"]   = "";
		$this->data["refrence_no"]          = "";
		$this->data["maininvoicestatus"]    = "";
		$this->data["invoice_status"]       = 99;
		$this->data["date_type"]            = "0";
		$this->data["start_date"]           = "";
		$this->data["end_date"]             = "";
		$this->data["count"]                = 0;
		$this->data["allstudents"]          = [];
		$this->data['student_status'] = 1;
		

		$this->data['date'] = date("d-m-Y");
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/balancefees/BalanceFeesReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection()
	{
		$classesID = $this->input->post('classesID');
		if ((int)$classesID) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("balancefeesreport_please_select"), "</option>";
			if (customCompute($allSection)) {
				foreach ($allSection as $value) {
					echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
				}
			}
		}
	}

	public function getStudent()
	{
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		echo "<option value='0'>", $this->lang->line("balancefeesreport_please_select"), "</option>";
		if ((int)$classesID && (int)$sectionID && (int)$schoolyearID) {
			$students = $this->studentrelation_m->get_order_by_studentrelation(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($students)) {
				foreach ($students  as $student) {
					echo "<option value=\"$student->srstudentID\">", $student->srname, " - ", $student->srroll, "  (", $student->srregisterNO, ")</option>";
				}
			}
		}
	}

	public function getStudent_active_inactive()
	{
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		echo "<option value='0'>", $this->lang->line("balancefeesreport_please_select"), "</option>";
		if ((int)$classesID && (int)$sectionID && (int)$schoolyearID) {
			$students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID, 'active' => [0, 1], 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($students)) {
				foreach ($students  as $student) {
					echo "<option value=\"$student->srstudentID\">", $student->srname, " - ", $student->srroll, "  (", $student->srregisterNO, ")</option>";
				}
			}
		}
	}

	public function getBalanceFeesReport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		error_reporting(0);

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



		$this->data["studentID"]            = "";
		$this->data["classesID"]            = "";
		$this->data["sectionID"]            = "";
		$this->data["nameSearch"]           = "";
		$this->data["maininvoice_type_v"]   = "";
		$this->data["refrence_no"]          = "";
		$this->data["maininvoicestatus"]    = "";
		$this->data["invoice_status"]       = 99;
		$this->data["date_type"]            = "";
		$this->data["start_date"]           = "";
		$this->data["end_date"]             = "";
		$this->data["count"]                = 0;
		$this->data["allstudents"]          = [];

		$this->data['date'] = date("d-m-Y");
		$this->data['classes'] = $this->classes_m->general_get_classes();

		if (permissionChecker('balancefeesreport')) {



			if ($_GET) {
				$_POST =	$_GET;
			}



			if ($_POST) {






				$schoolyearID 			= $this->session->userdata('defaultschoolyearID');
				$_POST['schoolyearID'] 	= $schoolyearID;
				$classesID    			= $this->input->post('classesID');
				$numric_code  			= $this->input->post('numric_code');
				$studentID    			= $this->input->post('studentID');
				$fromdate     			= $this->input->post('fromdate');
				$todate       			= $this->input->post('todate');
				$rendertype       		= $this->input->post('veiw_down');
				$student_status 		= $this->input->post('student_status');


				$this->data['classesID']    = $classesID;
				$this->data['numric_code']  = $numric_code;
				$this->data['fromdate']     = $fromdate;
				$this->data['todate']       = $todate;
				$this->data['schoolyearID'] = $schoolyearID;

				$studentArray = [];
				if ((int)$classesID) {
					$studentArray['student.classesID'] 		= $classesID;
				}
				if ((int)$numric_code) {
					$studentArray['section.numric_code'] 	= $numric_code;
				}
				$studentArray['student.active'] 			= [0, 1];
				if (isset($student_status) && !empty($student_status)) {
					$studentArray['student.active'] 	= $student_status;
					$this->data['student_status'] = $student_status;
				}

				$studentArray['schoolyearID'] 				= $schoolyearID;

				//echo date('d-m-Y H:i:s A');
				//echo "<br> student <br>";
				$this->data['students'] = pluck($this->student_m->get_student_all_join_by_array('*', $studentArray), 'obj', 'studentID');
				//echo date('d-m-Y H:i:s A');
				//echo "<br> student close<br>";
				$this->data['classes'] 	= pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

				$inv_array  = array('deleted_at' => 1,);
				$inv_array1  = array('deleted_at' => 1,);


				$maininvoice_type_v =  $this->input->post('maininvoice_type_v');
				if (count($maininvoice_type_v)) {
					$inv_array["maininvoice_type_v"]        = $maininvoice_type_v;
					$inv_array1["maininvoice_type_v"]        = $maininvoice_type_v;
					$this->data["maininvoice_type_v"]   	= $maininvoice_type_v;
				} else {
					$inv_array["maininvoice_type_v"]        = get_general_feetype();
				}
				$paidstatus =  $this->input->post('maininvoicestatus');
				if (($paidstatus) != 0) {
					$inv_array["paidstatus"]        = $paidstatus;
					$this->data["paidstatus"]   	= $paidstatus;
				}

				$invoice_status =  $this->input->post('invoice_status');
				if ($invoice_status != 99) {
					$inv_array["invoice_status"]        = $invoice_status;
					$inv_array1["invoice_status"]        = $invoice_status;
					$this->data["invoice_status"]   	= $invoice_status;
				} elseif ($invoice_status == 99) {
					$this->data["invoice_status"]   	= 99;
				}
				$date_type      =  $this->input->post('date_type');

				if (empty($date_type)) {
					$this->data['date_type'] = 0;
				}

				$start_date     =  $this->input->post('start_date');
				$end_date       =  $this->input->post('end_date');
				if ($date_type != "") {
					if ($date_type == "maininvoicedate") {
						$inv_array["date >="] = date("Y-m-d", strtotime($start_date));
						$inv_array["date <="] = date("Y-m-d", strtotime($end_date));
						$inv_array1["date <"] = date("Y-m-d", strtotime($start_date));
					}
					if ($date_type == "maininvoicedue_date") {
						$inv_array["due_date >="] = date("Y-m-d", strtotime($start_date));
						$inv_array["due_date <="] = date("Y-m-d", strtotime($end_date));
						$inv_array1["due_date <"] = date("Y-m-d", strtotime($start_date));
					}
					if ($date_type == "paymentdate") {
						$inv_array["paymentdate >="] = date("Y-m-d", strtotime($start_date));
						$inv_array["paymentdate <="] = date("Y-m-d", strtotime($end_date));
						$inv_array1["paymentdate <"] = date("Y-m-d", strtotime($start_date));
					}
				}


				//echo date('d-m-Y H:i:s A');
				//echo "<br> invoice<br>";
				$this->data['invoice_test'] 			= $this->invoice_m->get_invoice_by_array_where_in($inv_array);

				$this->data['invoice_test1'] 			= $this->invoice_m->get_invoice_by_array_where_in($inv_array1);
				//echo $this->db->last_query();

				//echo $this->db->last_query();
				//echo date('d-m-Y H:i:s A');
				//echo "<br> invoice close<br>";
				//exit();

				$this->data['totalAmountAndDiscount'] 	= $this->totalAmountAndDiscustomCompute($this->data['invoice_test'], $inv_array["maininvoice_type_v"]);
				$this->data['totalAmountAndDiscount1'] 	= $this->totalAmountAndDiscustomCompute($this->data['invoice_test1'], $inv_array["maininvoice_type_v"]);


				if ($rendertype == "download") {
					//$this->load->helper('file');
					//$this->load->helper('download');
					$classes 	=	$this->data['classes'];
					$sections 	=	$this->data['sections'];
					$totalAmountAndDiscount 	=	$this->data['totalAmountAndDiscount'];
					$totalAmountAndDiscount1 	=	$this->data['totalAmountAndDiscount1'];
					$ivoicetypes    =   get_general_feetype();


					echo "<pre>";
					print_r($totalAmountAndDiscount);
					echo "<br>-------------Total Amount and Discount 1 ------------<br>";
					print_r($totalAmountAndDiscount1);
					die();

					$header = array(
						lang("Sr"),
						lang("Name"),
						lang("Register_NO"),
						lang("Degree"),
						lang("Semester"),
						lang("Roll"),
						lang("Type"),
						lang("Opening_Balance"),
					);
					foreach ($inv_array["maininvoice_type_v"] as $key) {
						$header[]	=	lang($ivoicetypes[$key]);
						$header[]	=	lang("Discount");
					}
					$header[]	=	lang("Total");
					$header[]	=	lang("Total_Discount");
					$header[]	=	lang("Net_Total");
					$header[]	=	lang("Paid");
					$header[]	=	lang("Balance");


					$download_arr 	=	[];
					$sr 	=	1;
					$i 		= 	0;
					$studentArray 	=	[];
					foreach ($this->data['students'] as $student) {
						$st_amount      =   0;
						$st_discount    =   0;
						if (isset($totalAmountAndDiscount[$student->studentID]['total_paid'])) {
							$total_paid     =   $totalAmountAndDiscount[$student->studentID]['total_paid'];
						} else {
							$total_paid     =   0;
						}
						$down 	=	array(
							'Sr' => $sr,
							'Name' => $student->name,
							'Register_NO' => $student->registerNO,
							'Degree' => (isset($classes[$student->classesID]) ? $classes[$student->classesID] : ''),
							'Semester' => (isset($sections[$student->sectionID]) ? $sections[$student->sectionID] : ''),
							'Roll' => $student->roll,
							'Type' => (isset($totalAmountAndDiscount[$student->studentID]['feetype']) ? $totalAmountAndDiscount[$student->studentID]['feetype'] : 'None'),
						);

						$st_amount1      =   0;
						$st_discount1    =   0;
						if (isset($totalAmountAndDiscount1[$student->studentID]['total_paid'])) {
							$total_paid1     =   $totalAmountAndDiscount1[$student->studentID]['total_paid'];
						} else {
							$total_paid1     =   0;
						}
						foreach ($maininvoice_type_v as $invtype) {



							if (isset($totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype])) {
								$st_amount1    += $totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype];
								$typetotal[$invtype]['total']   += $totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype];
							}
							if (isset($totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype])) {
								$st_discount1    += $totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype];
								$typetotal[$invtype]['totaliscount']    += $totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype];
							}
						}
						$net_amount1 = $st_amount1 - $st_discount1;



						$totalAmount1    += $st_amount1;
						$totalDiscount1  += $st_discount1;
						$totalPayments1  += $total_paid1;
						$balance1        =  $net_amount1 - $total_paid1;

						$totalBalance1   += $balance1;
						$down['Opening_Balance'] 				= $balance1;
						$st_amount      =   0;
						$st_discount    =   0;
						if (isset($totalAmountAndDiscount[$student->studentID]['total_paid'])) {
							$total_paid     =   $totalAmountAndDiscount[$student->studentID]['total_paid'];
						} else {
							$total_paid     =   0;
						}
						foreach ($maininvoice_type_v as $invtype) {

							$down[$invtype] 	= 	(isset($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype]) ? ceil($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype]) : 0);
							$down['discount'] 	= 	(isset($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype]) ? ceil($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype]) : 0);


							if (isset($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype])) {
								$st_amount    += $totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype];
								$typetotal[$invtype]['total']   += $totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype];
							}
							if (isset($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype])) {
								$st_discount    += $totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype];
								$typetotal[$invtype]['totaliscount']    += $totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype];
							}
						}
						$down['Total'] 				= 	$st_amount;
						$down['Total_Discount'] 	= 	$st_discount;
						$net_amount 				= 	$st_amount - $st_discount;

						$down['Net_Total'] 			= 	$net_amount;
						$down['Paid'] 				= 	$total_paid;
						$balance        			=  	$net_amount - $total_paid;
						$down['Balance'] 			= 	$balance;

						$i++;
						$studentArray[$i]   =  $down;




						$download_arr[] 			=	$down;
						$sr++;
					}

					//error_reporting(E_ALL);


					$file_name 	= lang("Balance_Fee_Report_") . date('d-m-Y H i A') . '.csv';


					helper_xlsx('Balance_Fee_Report_', $header, $studentArray);
					//    // file creation 
					//    $file = fopen($file_name . (50*1024*1024), 'w');



					//   // var_dump($file);
					//   // fwrite($file, implode(',', $header)."\n"); 
					//    //fputcsv($file, $header);
					//    $i=1;
					//    $ddd	=	implode(',', $header)."\n";
					//    foreach ($download_arr as  $value)
					//     { 
					//     	//var_dump($value);
					//     	//fwrite($file, implode(',', $value)."\n");
					//     	//$file = fopen($file_name . (50*1024*1024), 'w');
					//     	$ddd	.=	 implode(',', $value)."\n";
					//     	fwrite($file, $ddd);
					//     	//fputcsv($file, $value); 
					//     	if ($i==41) {
					//     	break;
					//     	}
					//     	$i++;



					//     }
					// echo ($ddd);
					// fwrite($file, $ddd); 


					// // header("Content-Description: File Transfer");
					// //    header("Content-Type: application/csv;"); 
					// //    header("Content-Disposition: attachment; filename=$file_name"); 
					//      $file = fopen($file_name, 'w');
					//      // var_dump($file);
					//      // exit();
					//     // fwrite( $file_name, "$file");
					// header('Content-Description: File Transfer');
					// header('Content-Type: application/csv');
					// header('Content-Disposition: attachment; filename='.$file_name);
					// //header('Expires: 0');
					// //header('Cache-Control: must-revalidate'); 
					// //header('Content-Length: ' . filesize($file));
					//    //var_dump($file);

					//    fclose($file);


					//  exit();

					$retArray['render'] = $this->load->view('report/balancefees/BalanceFeesReport', $this->data, true);

					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				} else {
					// $retArray['render'] = $this->load->view('report/balancefees/BalanceFeesReport', $this->data, true);

					// $retArray['status'] = TRUE;
					// echo json_encode($retArray);
					//$this->load->view('report/balancefees/BalanceFeesReport', $this->data);
					$this->data["subview"] = "report/balancefees/BalanceFeesReport";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "report/balancefees/BalanceFeesReport";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	private function totalAmountAndDiscustomCompute($arrays, $inv_types = array('invoice'))
	{
		error_reporting(0);
		$totalAmountAndDiscount = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $key => $array) {

				if (isset($totalAmountAndDiscount[$array->studentID]['totalfine'])) {
					$totalAmountAndDiscount[$array->studentID]['totalfine'] += $array->totalfine;
				} else {
					$totalAmountAndDiscount[$array->studentID]['totalfine'] = $array->totalfine;
				}

				if (isset($totalAmountAndDiscount[$array->studentID]['amount'])) {
					$totalAmountAndDiscount[$array->studentID]['amount'] += $array->amount;
				} else {
					$totalAmountAndDiscount[$array->studentID]['amount'] = $array->amount;
				}

				if (isset($totalAmountAndDiscount[$array->studentID]['total_paid'])) {
					$total_paid = ($array->total_paid);
					$totalAmountAndDiscount[$array->studentID]['total_paid'] += $total_paid;
				} else {
					$total_paid = ($array->total_paid);
					$totalAmountAndDiscount[$array->studentID]['total_paid'] = $total_paid;
				}

				foreach ($inv_types as $type) {



					if (isset($totalAmountAndDiscount[$array->studentID]['type_v'][$type])) {
						if ($totalAmountAndDiscount[$array->studentID]['type_v'][$array->type_v] == $type) {
							$other_charges = ($array->amount);
							$totalAmountAndDiscount[$array->studentID]['type_amount'][$type] 		+= 	$other_charges;
							$discount = ($array->discount);
							$totalAmountAndDiscount[$array->studentID]['type_discount'][$type] 	+= 	$discount;
							$totalAmountAndDiscount[$array->studentID]['feetype'] 				= 	$array->feetype;
						}
					} else {
						if ($array->type_v == $type) {
							$other_charges = ($array->amount);
							$totalAmountAndDiscount[$array->studentID]['type_amount'][$type] 		= 	$other_charges;
							$discount = ($array->discount);
							$totalAmountAndDiscount[$array->studentID]['type_discount'][$type] 	= 	$discount;
							$totalAmountAndDiscount[$array->studentID]['feetype'] 				= 	$array->feetype;
							$totalAmountAndDiscount[$array->studentID]['type_v'][$type] 		= 	$array->type_v;
						}
					}
				}
			}
		}
		return $totalAmountAndDiscount;
	}

	private function totalPaymentAndWeaver($arrays)
	{
		$totalPayment = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $key => $array) {
				if (isset($totalPayment[$array->studentID]['payment'])) {
					$totalPayment[$array->studentID]['payment'] += $array->paymentamount;
				} else {
					$totalPayment[$array->studentID]['payment'] = $array->paymentamount;
				}
			}
		}
		return $totalPayment;
	}

	private function totalWeaver($arrays)
	{
		$totalWeaver = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $array) {
				if (isset($totalWeaver[$array->studentID]['weaver'])) {
					$totalWeaver[$array->studentID]['weaver'] += $array->weaver;
				} else {
					$totalWeaver[$array->studentID]['weaver'] = $array->weaver;
				}
			}
		}
		return $totalWeaver;
	}

	private function totalPayment($arrays, $schoolyearID)
	{
		$weaverandfine = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID)), 'obj', 'paymentID');
		$retArray = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $array) {
				if (isset($retArray[$array->invoiceID])) {
					$oldAmount = $retArray[$array->invoiceID];
					$oldAmount += $array->paymentamount;
					$retArray[$array->invoiceID] = (int) $oldAmount;
					if (isset($weaverandfine[$array->paymentID])) {
						$oldAmount = $retArray[$array->invoiceID];
						$oldAmount += $weaverandfine[$array->paymentID]->weaver;
						$retArray[$array->invoiceID] = (int) $oldAmount;
					}
				} else {
					$retArray[$array->invoiceID] = (int) $array->paymentamount;
					if (isset($weaverandfine[$array->paymentID])) {
						$oldAmount = $retArray[$array->invoiceID];
						$oldAmount += $weaverandfine[$array->paymentID]->weaver;
						$retArray[$array->invoiceID] = (int) $oldAmount;
					}
				}
			}
		}

		return $retArray;
	}

	public function pdf()
	{
		if (permissionChecker('balancefeesreport')) {
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));

			if ((int)($classesID >= 0) || (int)($sectionID >= 0) || (int)($studentID >= 0)) {
				$postArray = [];
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

				$studentArray = [];
				if ((int)$classesID) {
					$studentArray['srclassesID'] = $classesID;
				}
				if ((int)$sectionID) {
					$studentArray['srsectionID'] = $sectionID;
				}
				if ((int)$studentID) {
					$studentArray['srstudentID'] = $studentID;
				}
				$studentArray['srschoolyearID'] = $schoolyearID;

				$this->db->order_by('srclassesID', 'ASC');

				$this->data['students'] = pluck($this->studentrelation_m->get_student_with_balance($studentArray), 'obj', 'srstudentID');
				$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscustomCompute($this->invoice_m->get_all_balancefees_for_report($postArray));
				$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
				$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID)));
				// ini_set("pcre.backtrack_limit", "1000000");
				$this->reportPDF('balancefeesreport.css', $this->data, 'report/balancefees/BalanceFeesReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function date_valid($date)
	{
		if ($date) {
			if (strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
				return FALSE;
			} else {
				$arr = explode("-", $date);
				$dd = $arr[0];
				$mm = $arr[1];
				$yyyy = $arr[2];
				if (checkdate($mm, $dd, $yyyy)) {
					return TRUE;
				} else {
					$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	public function unique_date()
	{
		$fromdate = $this->input->post('fromdate');
		$todate   = $this->input->post('todate');

		$startingdate = $this->data['schoolyearobj']->startingdate;
		$endingdate = $this->data['schoolyearobj']->endingdate;

		if ($fromdate != '' && $todate == '') {
			$this->form_validation->set_message("unique_date", "The to date field not be empty .");
			return FALSE;
		}

		if ($fromdate == '' && $todate != '') {
			$this->form_validation->set_message("unique_date", "The from date field not be empty .");
			return FALSE;
		}

		if ($fromdate != '' && $todate != '') {
			if (strtotime($fromdate) > strtotime($todate)) {
				$this->form_validation->set_message("unique_date", "The from date can not be upper than todate .");
				return FALSE;
			}

			if ((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The from date are invalid .");
				return FALSE;
			}

			if ((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The to date are invalid .");
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

		if (permissionChecker('balancefeesreport')) {
			if ($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$classesID = $this->input->post('classesID');
					$sectionID = $this->input->post('sectionID');
					$studentID = $this->input->post('studentID');

					$this->data['schoolyearID'] = $schoolyearID;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$postArray = [];
					$postArray['schoolyearID'] = $schoolyearID;
					$postArray['classesID'] = $classesID;
					$postArray['sectionID'] = $sectionID;
					$postArray['studentID'] = $studentID;

					$to      = $this->input->post('to');
					$subject = $this->input->post('subject');
					$message = $this->input->post('message');


					$studentArray = [];
					if ((int)$classesID) {
						$studentArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID) {
						$studentArray['srsectionID'] = $sectionID;
					}
					if ((int)$studentID) {
						$studentArray['srstudentID'] = $studentID;
					}
					$studentArray['srschoolyearID'] = $schoolyearID;

					$this->db->order_by('srclassesID', 'ASC');
					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studentArray), 'obj', 'srstudentID');

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

					$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscustomCompute($this->invoice_m->get_all_balancefees_for_report($postArray));
					$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
					$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID)));

					$this->reportSendToMail('balancefeesreport.css', $this->data, 'report/balancefees/BalanceFeesReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['message'] = $this->lang->line('balancefeesreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['message'] = $this->lang->line('balancefeesreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}
}
