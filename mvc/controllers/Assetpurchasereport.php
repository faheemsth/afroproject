<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assetpurchasereport extends Admin_Controller {
	public function __construct() {
		parent::__construct();
 
        $this->load->model("vendor_m");
        $this->load->model("purchase_m"); 
        $language = $this->session->userdata('lang');
		$this->lang->load('assetpurchasereport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
	                'field' => 'assetsupplierID',
	                'label' => $this->lang->line('assetpurchasereport_supplier'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'assetwarehouseID',
	                'label' => $this->lang->line('assetpurchasereport_warehouse'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'reference_no',
	                'label' => $this->lang->line('assetpurchasereport_referenceNo'),
	                'rules' => 'trim|xss_clean|callback_unique_data'
	        ),
            array(
	                'field' => 'statusID',
	                'label' => $this->lang->line('assetpurchasereport_status'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'fromdate',
	                'label' => $this->lang->line('assetpurchasereport_fromdate'),
	                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
	        ),
	        array(
	                'field' => 'todate',
	                'label' => $this->lang->line('assetpurchasereport_todate'),
	                'rules' => 'trim|xss_clean|callback_date_valid'
	        )
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
            array(
                'field' => 'assetsupplierID',
                'label' => $this->lang->line('assetpurchasereport_supplier'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'assetwarehouseID',
                'label' => $this->lang->line('assetpurchasereport_warehouse'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('assetpurchasereport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('assetpurchasereport_status'),
                'rules' => 'trim|xss_clean'
            ),
		    array(
	                'field' => 'fromdate',
	                'label' => $this->lang->line('assetpurchasereport_fromdate'),
                    'rules' => 'trim|xss_clean'
	        ),
	        array(
	                'field' => 'todate',
	                'label' => $this->lang->line('assetpurchasereport_todate'),
                    'rules' => 'trim|xss_clean'
	        ),
	        array(
	                'field' => 'to',
	                'label' => $this->lang->line('assetpurchasereport_to'),
	                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
	                'field' => 'subject',
	                'label' => $this->lang->line('assetpurchasereport_subject'),
	                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
	                'field' => 'message',
	                'label' => $this->lang->line('assetpurchasereport_message'),
	                'rules' => 'trim|xss_clean'
	        ),
	        
		);
		return $rules;
	}

	public function index() {
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
 
        $this->data['vendors'] = $this->vendor_m->get_vendor();
        $this->data["subview"] = "report/assetpurchase/assetpurchaseReportView";
		$this->load->view('_layout_main', $this->data);
	}

    public function unique_data($data) {
        if($data != "") {
            if($data == "0") {
                $this->form_validation->set_message('unique_data', 'The %s field value invalid.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

	public function getassetpurchaseReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('assetpurchasereport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
				    $retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['vendorID'] = $this->input->post('vendorID');
                     
                    $this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');

                    $vendors = pluck($this->vendor_m->get_vendor(), 'name', 'vendorID');
                     

                    $this->data['vendors'] = $vendors; 

					$assetpurchases = $this->purchase_m->get_all_assetpurchase_for_report($this->input->post());

                   
                    

                    $assetpurchaseArray = [];
                    $totalassetpurchaseprice = 0;
                    $totalassetpurchasepaidamount = 0;
                    $totalassetpurchasebalanceamount = 0;

                    
                     
                    $this->data['assetpurchases'] = $assetpurchases;
					$retArray['render'] = $this->load->view('report/assetpurchase/assetpurchaseReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['status'] = TRUE;
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

	public function pdf() {
		if(permissionChecker('assetpurchasereport')) {
            $vendorID  = htmlentities(escapeString($this->uri->segment(3))); 
            $fromdate = htmlentities(escapeString($this->uri->segment(4)));
            $todate   = htmlentities(escapeString($this->uri->segment(5)));
            if((int)($vendorID >= 0)     ||     ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['vendorID']  = $vendorID;
                 
                $this->data['fromdate'] = $fromdate;
                $this->data['todate']   = $todate;

                $postArray = [];
                $postArray['vendorID']     = $vendorID; 
                if($fromdate !='' && $todate != '') {
                    $postArray['fromdate'] = date('d-m-Y',$fromdate);
                    $postArray['todate']   = date('d-m-Y',$todate);
                }

                $vendors = pluck($this->vendor_m->get_vendor(), 'name', 'vendorID');
               
                $this->data['vendors'] = $vendors;
                
                $assetpurchases = $this->purchase_m->get_all_assetpurchase_for_report($postArray);

                 
 
                 


                
                $this->data['assetpurchases'] = $assetpurchases;
                $this->reportPDF('assetpurchasereport.css', $this->data, 'report/assetpurchase/assetpurchaseReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		if(permissionChecker('assetpurchasereport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
				    $retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$to = $this->input->post('to');
					$subject = $this->input->post('subject');
					$message = $this->input->post('message');

                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['assetsupplierID'] = $this->input->post('assetsupplierID');
                    $this->data['assetwarehouseID'] = $this->input->post('assetwarehouseID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = strtotime($this->input->post('fromdate'));
                    $this->data['todate'] = strtotime($this->input->post('todate'));

                    $assetsuppliers = pluck($this->assetsupplier_m->get_assetsupplier(), 'assetsuppliercompanyname', 'assetsupplierID');
                    $assetwarehouses = pluck($this->assetwarehouse_m->get_assetwarehouse(), 'assetwarehousename', 'assetwarehouseID');
                    $this->data['assetsuppliers'] = $assetsuppliers;
                    $this->data['assetwarehouses'] = $assetwarehouses;

                    $assetpurchases = $this->assetpurchase_m->get_all_assetpurchase_for_report($this->input->post());


                    $assetpurchasepaids = $this->assetpurchasepaid_m->get_order_by_assetpurchasepaid(array('schoolyearID' => $schoolyearID));
                    $assetpurchasepaidsArray = [];
                    if(customCompute($assetpurchasepaids)) {
                        foreach($assetpurchasepaids as $assetpurchasepaid) {
                            if(isset($assetpurchasepaidsArray[$assetpurchasepaid->assetpurchaseID])) {
                                $assetpurchasepaidsArray[$assetpurchasepaid->assetpurchaseID] += $assetpurchasepaid->assetpurchasepaidamount;
                            } else {
                                $assetpurchasepaidsArray[$assetpurchasepaid->assetpurchaseID] = $assetpurchasepaid->assetpurchasepaidamount;
                            }
                        }
                    }

                    $assetpurchaseArray = [];
                    $totalassetpurchaseprice = 0;
                    $totalassetpurchasepaidamount = 0;
                    $totalassetpurchasebalanceamount = 0;

                    if(customCompute($assetpurchases)) {
                        foreach ($assetpurchases as $assetpurchase) {
                            if(isset($assetpurchaseArray[$assetpurchase->assetpurchaseID])) {
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['total'] += ($assetpurchase->assetpurchaseunitprice * $assetpurchase->assetpurchasequantity);
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['balance'] += ($assetpurchase->assetpurchaseunitprice * $assetpurchase->assetpurchasequantity);
                            } else {
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['reference_no'] = $assetpurchase->assetpurchasereferenceno;
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['supplier'] = isset($assetsuppliers[$assetpurchase->assetsupplierID]) ? $assetsuppliers[$assetpurchase->assetsupplierID] : '';
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['warehouse'] = isset($assetwarehouses[$assetpurchase->assetwarehouseID]) ? $assetwarehouses[$assetpurchase->assetwarehouseID] : '';
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['date'] = date('d M Y',strtotime($assetpurchase->assetpurchasedate));
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['total'] = ($assetpurchase->assetpurchaseunitprice * $assetpurchase->assetpurchasequantity);
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['paid'] = isset($assetpurchasepaidsArray[$assetpurchase->assetpurchaseID]) ? $assetpurchasepaidsArray[$assetpurchase->assetpurchaseID] : '0';
                                $assetpurchaseArray[$assetpurchase->assetpurchaseID]['balance'] = ($assetpurchaseArray[$assetpurchase->assetpurchaseID]['total'] - $assetpurchaseArray[$assetpurchase->assetpurchaseID]['paid']);
                                $totalassetpurchasepaidamount += $assetpurchaseArray[$assetpurchase->assetpurchaseID]['paid'];
                            }
                            $totalassetpurchaseprice += ($assetpurchase->assetpurchaseunitprice * $assetpurchase->assetpurchasequantity);
                        }
                        $totalassetpurchasebalanceamount = ($totalassetpurchaseprice - $totalassetpurchasepaidamount);
                    }

                    $this->data['totalassetpurchaseprice'] = $totalassetpurchaseprice;
                    $this->data['totalassetpurchasepaidamount'] = $totalassetpurchasepaidamount;
                    $this->data['totalassetpurchasebalanceamount'] = $totalassetpurchasebalanceamount;
                    $this->data['assetpurchases'] = $assetpurchaseArray;

                    $this->reportSendToMail('assetpurchasereport.css', $this->data, 'report/assetpurchase/assetpurchaseReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				}
			} else {
				$retArray['message'] = $this->lang->line('assetpurchasereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('assetpurchasereport_permission');
			echo json_encode($retArray);
			exit;
		}

	}

	public function xlsx() {
		if(permissionChecker('assetpurchasereport')) {
			$this->load->library('phpspreadsheet');
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="assetpurchasereport.xlsx"');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	} 

	private function xmlData() {
          $vendorID  = htmlentities(escapeString($this->uri->segment(3))); 
            $fromdate = htmlentities(escapeString($this->uri->segment(4)));
            $todate   = htmlentities(escapeString($this->uri->segment(5)));
            if((int)($vendorID >= 0)     ||     ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['vendorID']  = $vendorID;
                 
                $this->data['fromdate'] = $fromdate;
                $this->data['todate']   = $todate;

                $postArray = [];
                $postArray['vendorID']     = $vendorID; 
                if($fromdate !='' && $todate != '') {
                    $postArray['fromdate'] = date('d-m-Y',$fromdate);
                    $postArray['todate']   = date('d-m-Y',$todate);
                }

                $vendors = pluck($this->vendor_m->get_vendor(), 'name', 'vendorID');
               
                $this->data['vendors'] = $vendors;
                
                $assetpurchases = $this->purchase_m->get_all_assetpurchase_for_report($postArray);

                 
 
                 


                
                $this->data['assetpurchases'] = $assetpurchases;
            return $this->generateXML($this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

	private function generateXML($arrays) {
		extract($arrays);
        if(customCompute($assetpurchases)) {
	        $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $topCellMerge = TRUE;
            if($fromdate != '' && $todate != '' ) { 
                $fdate = $this->lang->line('assetpurchasereport_fromdate')." : ";
                $fdate .= date('d M Y',$fromdate);

                $tdate = $this->lang->line('assetpurchasereport_todate')." : ";
                $tdate .= date('d M Y',$todate);

                $sheet->setCellValue('A1',$fdate);
                $sheet->setCellValue('H1',$tdate);
            }  elseif($vendorID != 0) {
                $supplier =  "Vendor : ";
                $supplier .= isset($vendors[$vendorID]) ? $vendors[$vendorID] : '';
                $topCellMerge = FALSE;
                
                $sheet->setCellValue('A1',$supplier);
            }  else {
                $sheet->getRowDimension('1')->setVisible(false);
            }

            $headers = array();
			$headers['slno'] = $this->lang->line('slno');
			$headers['Asset'] = 'Asset';
            $headers['Vendor'] = 'Vendor';
			$headers['Quantity'] = 'Quantity';
            $headers['Unit'] = 'Unit';
            $headers['Price'] = 'Price';
            $headers['Date'] = 'Date'; 

            $unitar     = array( 0 => $this->lang->line('purchase_select_unit'), 1 => $this->lang->line('purchase_unit_kg'), 2 => $this->lang->line('purchase_unit_piece'), 3 => $this->lang->line('purchase_unit_other'));

            $i=0;
			$bodys = array();
			foreach($assetpurchases as $assetpurchase) {
				$bodys[$i][] = $i+1;
				$bodys[$i][] = $assetpurchase->description;
                $bodys[$i][] = $vendors[$assetpurchase->vendorID];
                $bodys[$i][] = $assetpurchase->p_quantity;
                $bodys[$i][] = $unitar[$assetpurchase->unit]; 
                $bodys[$i][] = $assetpurchase->purchase_price;
                $bodys[$i][] = date('d-m-Y',strtotime($assetpurchase->purchase_date));
                $i++;
			}

             
            
			if(customCompute($headers)) {
				$row = 2;
				$column = "A";
				foreach($headers as $header) {
					$sheet->setCellValue($column.$row, $header);
	    			$column++;
				}
			}

			if(customCompute($bodys)) {
				$row = 3;
				foreach($bodys as $rows) {
					$column = 'A';
					foreach ($rows as $value) {
						$sheet->setCellValue($column.$row, $value);
	    				$column++;
					}
					$row++;
				}
			}

			$styleArray = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			$sheet->getStyle('A1:H1')->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => FALSE,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			$styleColumn = $row-2;
			$sheet->getStyle('A3:G'.$styleColumn)->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => false,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			$styleColumn = $row-1;
			$sheet->getStyle('A'.$styleColumn.':G'.$styleColumn)->applyFromArray($styleArray);

             

            
		} else {
		  redirect('assetpurchasereport');
		}
	}

    public function date_valid($date) {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy.");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy.");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function unique_date() {
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');

        $startingdate = $this->data['schoolyearsessionobj']->startingdate;
        $endingdate = $this->data['schoolyearsessionobj']->endingdate;

        if($fromdate != '' && $todate == '') {
            $this->form_validation->set_message("unique_date", "The to date field not be empty .");
            return FALSE;
        }

        if($fromdate == '' && $todate != '') {
            $this->form_validation->set_message("unique_date", "The from date field not be empty .");
            return FALSE;
        }

        if($fromdate != '' && $todate != '') {
            if(strtotime($fromdate) > strtotime($todate)) {
                $this->form_validation->set_message("unique_date", "The from date can not be upper than todate .");
                return FALSE;
            }

            if((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
                $this->form_validation->set_message("unique_date", "The from date are invalid .");
                return FALSE;
            }

            if((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
                $this->form_validation->set_message("unique_date", "The to date are invalid .");
                return FALSE;
            }
            return TRUE;
        }

        return TRUE;
    }


}
