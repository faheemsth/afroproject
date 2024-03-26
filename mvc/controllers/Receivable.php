<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Receivable extends Admin_Controller
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
		$this->data["subview"] = "report/receivable/ReceivableReportView";
		$this->load->view('_layout_main', $this->data);


	}



	public function getReceivableReport()
	{
		// echo "<pre>";
		// print_r($_GET);
		// die();
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
		$this->data['classes_arr'] = pluck($this->data['classes'], 'classes', 'classesID');
		if (permissionChecker('receivable_report')) {

			// convert $_GET to $_POST
			if ($_GET) {
				$_POST =	$_GET;
			}

			//if post data
			if ($_POST) { //start $_POST
				$schoolyearID 			= $this->session->userdata('defaultschoolyearID');
				$_POST['schoolyearID'] 	= $schoolyearID;
				$classesID    			= $this->input->post('classesID');
				$numric_code  			= $this->input->post('numric_code');
				$fromdate     			= $this->input->post('fromdate');
				$todate       			= $this->input->post('todate');
				$rendertype       		= $this->input->post('veiw_down');
				
				//getting degrees and sections for filters dropdown
				$this->data['classes'] 	= pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

				//Degree for tables
				$this->data['filterClasses'] = $classesID;

				//arrarys variable for opening balance
				$inv_array = [];
				$inv_array1 = [];

				//varibles to store start and end date
				$start_date     =  $this->input->post('start_date');
				$end_date       =  $this->input->post('end_date');

				$inv_array["create_date >="] = date("Y-m-d", strtotime($start_date));
				$inv_array["create_date <="] = date("Y-m-d", strtotime($end_date));
				$inv_array1["create_date <"] = date("Y-m-d", strtotime($start_date));

				$this->data['invoice_test'] = $this->invoice_m->get_invoice_by_array_where_in($inv_array);
				$this->data['invoice_test1'] = $this->invoice_m->get_invoice_by_array_where_in($inv_array1);



				$invoices = [
					"invoice",
					"other charges",
					"library fine",
					"hostel fee",
					"transport fee"
				];

				//loop through classIDs
				foreach ($classesID as $classID) {
					
					if(!empty($numric_code)){
						$result = $this->section_m->get_section_by_numeric_code($classID, $numric_code);
					}else{
						$result = $this->section_m->get_section_by_numeric_code($classID);
					}
					

					$sectionIDs = array_map(function ($row) {
						return $row->sectionID;
					}, $result);

					//loop through section ID
					foreach ($sectionIDs as $sectionID) {
						$this->data['filterSections'][] = $sectionIDs;

						//get semester records
						$student_fees_detail = $this->student_m->semesterFees($classID, $sectionID);
						$semester_detail = $student_fees_detail[0];

						//update main array detail
						$data[$sectionID]['classesID'] = $classID;
						$data[$sectionID]['sectionID'] = $sectionID;
						$data[$sectionID]['no_of_students'] =  $semester_detail->total_students;
						$data[$sectionID]['student_total_fees'] = $semester_detail->total_fee ?? 0;
						$data[$sectionID]['student_discounts'] = $semester_detail->total_discount ?? 0;
						$data[$sectionID]['student_net_fees'] =  $data[$sectionID]['student_total_fees'] - $data[$sectionID]['student_discounts'];


						//loop through invoice
						foreach ($invoices as $invoice) {
							$inv_array1['type_v'] = $invoice;
							$inv_array1['sectionID'] = $sectionID;
							$inv_array1['classesID'] = $classID;

							$inv_array['type_v'] = $invoice;
							$inv_array['sectionID'] = $sectionID;
							$inv_array['classesID'] = $classID;

							//previous dues
							$detail_for_openings[$invoice][] = $this->invoice_m->get_invoice_by_array_for_receiving_where_in($inv_array1);

							//in the given range 
							$current_range[$invoice][] = $this->invoice_m->get_invoice_by_array_for_receiving_where_in($inv_array);
						}

						//now loop on the getting invoices
						$amount_for_op_balance = 0;
						$discount_for_op_balance = 0;
						$paid_for_op_balance = 0;

						foreach ($detail_for_openings as $key => $detail) {
							$amount = 0;
							$discount = 0;
							$total_paid = 0;

							foreach ($detail as $inner_detail) {
								$amount += $inner_detail[0]->amount;
								$discount += $inner_detail[0]->discount;
								$total_paid += $inner_detail[0]->total_paid;
							}

							$amount_for_op_balance += $amount;
							$discount_for_op_balance += $discount;
							$paid_for_op_balance += $total_paid;

							$data[$sectionID]['invoices'][$key]['amount'] = $amount;
							$data[$sectionID]['invoices'][$key]['discount'] = $discount;
							$data[$sectionID]['invoices'][$key]['total_paid'] = $total_paid;
						}


						foreach ($current_range as $key => $detail) {
							$amount = 0;
							$discount = 0;
							$total_paid = 0;

							foreach ($detail as $inner_detail) {
								$amount += $inner_detail[0]->amount;
								$discount += $inner_detail[0]->discount;
								$total_paid += $inner_detail[0]->total_paid;
							}

							$data[$sectionID]['invoices'][$key]['amount'] = $amount;
							$data[$sectionID]['invoices'][$key]['discount'] = $discount;
							$data[$sectionID]['invoices'][$key]['total_paid'] = $total_paid;
						}

						//opening balance
						$opening_bal = ($amount_for_op_balance - $discount_for_op_balance - $paid_for_op_balance);
						$data[$sectionID]['opening_balance'] = $opening_bal;


					}//end section loop
				}//end class loop



				$this->data['data'] = $data;
				$this->data['invoices'] = $invoices;

				if ($rendertype == "download") { //for download
					$header = array(
						lang("Sr"),
						"Degree",
						"Semester",
						"No of Students",
						"Opening Balance",
						"Total Fee",
						"Discount",
						"Net Fee"
					);

					foreach($invoices as $invoice){
						 if($invoice == 'invoice')
						 	continue;
						$header[] = $invoice; 
					}

					$header[] = "Discount on Others";
					$header[] = "Net Receivable";
					$header[] = "Received";
					$header[] = "Receivable";

					 $download = [];
					 $count = 1;
					 $total_students = 0;
					$total_opening_bal = 0;
					$total_fee = 0;
					$total_discount = 0;
					$total_net_fee = 0;
					$total_total_other_charges = 0;
					$total_library_fine = 0;
					$total_hostel_fee = 0;
					$total_transport_fee = 0;
					$total_discount_others = 0;
					$total_net_receivable = 0;
					$total_received = 0;
					$total_receivable = 0;
					foreach($data as $sectionID => $detail){
						$other_discounts = 0;    
						$total_amounts = 0;
						$total_paid = 0;
						$down = [];

						$total_students += $detail['no_of_students'];
						$total_opening_bal += $detail['opening_balance'];
						$total_fee += $detail['student_total_fees'];
						$total_discount += $detail['student_discounts'];
						$total_net_fee += $detail['student_net_fees'];
						$total_total_other_charges += 0;
						$total_library_fine += 0;
						$total_hostel_fee += 0;
						$total_transport_fee += 0;
						$total_discount_others += 0;

						$down['sr_no'] = $count++;
						$down['class'] = $this->data["classes_arr"][$detail["classesID"]] ;
						$down['section'] = $this->data["sections"][$detail["sectionID"]] ;
						$down['no_of_students'] = $detail['no_of_students'];
						$down['opening_balance'] = $detail['opening_balance'];
						$down['student_total_fees'] = $detail['student_total_fees'] ;
						$down['student_discounts'] = $detail["student_discounts"];
						$down['student_net_fees'] = $detail["student_net_fees"];
						foreach ($detail['invoices'] as $key => $inv_detail)  { 
							$total_paid += $inv_detail['total_paid'];

							if($key == 'invoice')
							continue; 
							
							$other_discounts += $inv_detail['discount'];
							$total_amounts += $inv_detail['amount']; 
							
							$down[$key] = $inv_detail['amount'];
					    }
						$net_receivable =   $detail['opening_balance'] + $detail['student_net_fees'] + $total_amounts - $other_discounts;
						$total_discount_others += $other_discounts;
						$total_net_receivable += $net_receivable;
						$total_received += $total_paid;
						$total_receivable += ($net_receivable - $total_paid);


						$down['other_discounts'] = $other_discounts;
						$down['net_receivable'] = $net_receivable;
						$down['total_paid'] = $total_paid;
						$down['receivable'] = $net_receivable - $total_paid;

						$download[$count] = $down;
					}

					$download[$count+1] = [
						'sr_no' => '',
						'class' => 'Total',
						'semester' => '',
						'no_of_students' => $total_students,
						'opening_balance' => $total_opening_bal,
						'student_total_fee' => $total_fee,
						'student_discount' => $total_discount,
						'student_net_receivable' => $total_net_receivable,
					];

					foreach($this->data['invoices'] as $invoice){
						if($invoice == 'invoice')
						continue;
					
						$download[$count+1][$invoice] = '';
					}

					$download[$count+1]['other_discounts'] = $total_discount_others;
					$download[$count+1]['net_receivable'] = $total_net_receivable;
					$download[$count+1]['received'] = $total_received;
					$download[$count+1]['receivable'] = $total_receivable;
					



					helper_xlsx('Receivable Report', $header, $download);

				} else { //for showing in table
					$this->data["subview"] = "report/receivable/ReceivableReport";
					$this->load->view('_layout_main', $this->data);
				}
			} //end $_POST

		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}





	private function customCompute($arrays, $inv_types)
	{
		error_reporting(0);
		$totalAmountAndDiscount = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $key => $array) {

				if (isset($totalAmountAndDiscount[$array->sectionID]['totalfine'])) {
					$totalAmountAndDiscount[$array->sectionID]['totalfine'] += $array->totalfine;
				} else {
					$totalAmountAndDiscount[$array->sectionID]['totalfine'] = isset($array->totalfine) ? $array->totalfine : 0;
				}

				if (isset($totalAmountAndDiscount[$array->sectionID]['amount'])) {
					$totalAmountAndDiscount[$array->sectionID]['amount'] += $array->amount;
				} else {
					$totalAmountAndDiscount[$array->sectionID]['amount'] = $array->amount;
				}

				if (isset($totalAmountAndDiscount[$array->sectionID]['total_paid'])) {
					$total_paid = ($array->total_paid);
					$totalAmountAndDiscount[$array->sectionID]['total_paid'] += $total_paid;
				} else {
					$total_paid = ($array->total_paid);
					$totalAmountAndDiscount[$array->sectionID]['total_paid'] = $total_paid;
				}

				foreach ($inv_types as $type) {



					if (isset($totalAmountAndDiscount[$array->sectionID]['type_v'][$type])) {
						if ($totalAmountAndDiscount[$array->sectionID]['type_v'][$array->type_v] == $type) {
							$other_charges = ($array->amount);
							$totalAmountAndDiscount[$array->sectionID]['type_amount'][$type] 		+= 	$other_charges;
							$discount = ($array->discount);
							$totalAmountAndDiscount[$array->sectionID]['type_discount'][$type] 	+= 	$discount;
							$totalAmountAndDiscount[$array->sectionID]['feetype'] 				= 	$type == 'invoice' ? 'Tuition Fee' : $array->feetype;
						}
					} else {
						if ($array->type_v == $type) {
							$other_charges = ($array->amount);
							$totalAmountAndDiscount[$array->sectionID]['type_amount'][$type] 		= 	$other_charges;
							$discount = ($array->discount);
							$totalAmountAndDiscount[$array->sectionID]['type_discount'][$type] 	= 	$discount;
							$totalAmountAndDiscount[$array->sectionID]['feetype'] 				= 	$type == 'invoice' ? 'Tuition Fee' : $array->feetype;
							$totalAmountAndDiscount[$array->sectionID]['type_v'][$type] 		= 	$array->type_v;
						}
					}
				}
			}
		}
		return $totalAmountAndDiscount;
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
}
