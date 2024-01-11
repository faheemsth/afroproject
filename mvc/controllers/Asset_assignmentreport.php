<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_assignmentreport extends Admin_Controller {
	public function __construct() {
		parent::__construct();

        $this->load->model('usertype_m');
        $this->load->model('classes_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model("studentrelation_m");
        $this->load->model('parents_m');
        $this->load->model('user_m');
        $this->load->model("productcategory_m");
        $this->load->model("product_m");
        $this->load->model('asset_assignment_m'); 

        $language = $this->session->userdata('lang');
		$this->lang->load('asset_assignmentreport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
                'field' => 'asset_assignmentcustomertypeID',
                'label' => $this->lang->line('asset_assignmentreport_role'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'asset_assignmentclassesID',
                'label' => $this->lang->line('asset_assignmentreport_classes'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'asset_assignmentcustomerID',
                'label' => $this->lang->line('asset_assignmentreport_user'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('asset_assignmentreport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('asset_assignmentreport_status'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'fromdate',
                'label' => $this->lang->line('asset_assignmentreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
            array(
                'field' => 'todate',
                'label' => $this->lang->line('asset_assignmentreport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            )
        );
        return $rules;
    }

    public function send_pdf_to_mail_rules() {
        $rules = array(
	        array(
                'field' => 'to',
                'label' => $this->lang->line('asset_assignmentreport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
                'field' => 'subject',
                'label' => $this->lang->line('asset_assignmentreport_subject'),
                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
                'field' => 'message',
                'label' => $this->lang->line('asset_assignmentreport_message'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'asset_assignmentcustomertypeID',
                'label' => $this->lang->line('asset_assignmentreport_role'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'asset_assignmentclassesID',
                'label' => $this->lang->line('asset_assignmentreport_classes'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'asset_assignmentcustomerID',
                'label' => $this->lang->line('asset_assignmentreport_user'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('asset_assignmentreport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('asset_assignmentreport_status'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'fromdate',
                'label' => $this->lang->line('asset_assignmentreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
            array(
                'field' => 'todate',
                'label' => $this->lang->line('asset_assignmentreport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            )                                                               
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
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data["subview"] = "report/asset_assignment/asset_assignmentReportView";
		$this->load->view('_layout_main', $this->data);
	}

    public function getasset_assignmentReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('asset_assignmentreport')) {
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
                    $this->data['asset_assignmentcustomertypeID'] = $this->input->post('asset_assignmentcustomertypeID');
                    $this->data['asset_assignmentclassesID'] = $this->input->post('asset_assignmentclassesID');
                    $this->data['asset_assignmentcustomerID'] = $this->input->post('asset_assignmentcustomerID');
                     
                    $this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');

                    $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                    $this->data['usertypes'] = $usertypes;
                    $users = $this->getuserlist($_POST);
                    $this->data['users'] = $users;
					$asset_assignments = $this->asset_assignment_m->get_all_asset_assignment_for_report($this->input->post());

                      
                    
                     
 

                    $this->data['asset_assignments'] = $asset_assignments;

                     foreach ($this->data['asset_assignments'] as $key => $assignment) {
                        $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
                        $this->data['asset_assignments'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
                    }

					$retArray['render'] = $this->load->view('report/asset_assignment/asset_assignmentReport',$this->data,true);
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


    private function userTableCall($usertypeID, $userID) {
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('student_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');

        $findUserName = '';
        if($usertypeID == 1) {
            $user = $this->db->get_where('systemadmin', array("usertypeID" => $usertypeID, 'systemadminID' => $userID));
            $alluserdata = $user->row();
            if(customCompute($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 2) {
            $user = $this->db->get_where('teacher', array("usertypeID" => $usertypeID, 'teacherID' => $userID));
            $alluserdata = $user->row();
            if(customCompute($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 3) {
            $user = $this->db->get_where('student', array("usertypeID" => $usertypeID, 'studentID' => $userID));
            $alluserdata = $user->row();
            if(customCompute($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 4) {
            $user = $this->db->get_where('parents', array("usertypeID" => $usertypeID, 'parentsID' => $userID));
            $alluserdata = $user->row();
            if(customCompute($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } else {
            $user = $this->db->get_where('user', array("usertypeID" => $usertypeID, 'userID' => $userID));
            $alluserdata = $user->row();
            if(customCompute($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        }
    }

	public function pdf() {
		if(permissionChecker('asset_assignmentreport')) {
            $asset_assignmentcustomertypeID = htmlentities(escapeString($this->uri->segment(3)));
             
            $asset_assignmentcustomerID     = htmlentities(escapeString($this->uri->segment(4))); 
            $fromdate     = htmlentities(escapeString($this->uri->segment(5)));
            $todate       = htmlentities(escapeString($this->uri->segment(6)));

            if(((int)$asset_assignmentcustomertypeID >= 0)  && ((int)$asset_assignmentcustomerID >= 0)   && (((int)$fromdate >= 0) || ((int)$fromdate =='')) && (((int)$todate >= 0) || ((int)$todate == ''))) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['asset_assignmentcustomertypeID'] = $asset_assignmentcustomertypeID;
                  $this->data['asset_assignmentcustomerID'] = $asset_assignmentcustomerID;
                 
                 if($fromdate != '' && $todate != '') {
                    $this->data['fromdate'] = date('d-m-Y',$fromdate);
                    $this->data['todate'] = date('d-m-Y',$todate);
                } else {
                    $this->data['fromdate'] = '';
                    $this->data['todate'] = '';
                }

                $postArray = [];
                $postArray['asset_assignmentcustomertypeID'] = $asset_assignmentcustomertypeID;
                  $postArray['asset_assignmentcustomerID']     = $asset_assignmentcustomerID;
                 $postArray['statusID']       = $statusID;
                if($fromdate != '' && $todate != '') {
                    $postArray['fromdate'] = date('d-m-Y',$fromdate);
                    $postArray['todate'] = date('d-m-Y',$todate);
                }

                $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                $this->data['usertypes'] = $usertypes;
                $users = $this->getuserlist($postArray);
                $this->data['users'] = $users;
                $asset_assignments = $this->asset_assignment_m->get_all_asset_assignment_for_report($postArray);
 
 
                $this->data['asset_assignments'] = $asset_assignments;


                     foreach ($this->data['asset_assignments'] as $key => $assignment) {
                        $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
                        $this->data['asset_assignments'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
                    }
                $this->reportPDF('asset_assignmentreport.css', $this->data, 'report/asset_assignment/asset_assignmentReportPDF');
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
		if(permissionChecker('asset_assignmentreport')) {
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
                    $this->data['asset_assignmentcustomertypeID'] = $this->input->post('asset_assignmentcustomertypeID');
                    $this->data['asset_assignmentclassesID'] = $this->input->post('asset_assignmentclassesID');
                    $this->data['asset_assignmentcustomerID'] = $this->input->post('asset_assignmentcustomerID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = $this->input->post('fromdate');
                    $this->data['todate'] = $this->input->post('todate');

                    $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                    $this->data['usertypes'] = $usertypes;
                    $users = $this->getuserlist($_POST);
                    $this->data['users'] = $users;
                    $asset_assignments = $this->asset_assignment_m->get_all_asset_assignment_for_report($this->input->post());

                    $asset_assignmentpaidsArray = [];
                    $asset_assignmentpaids = $this->asset_assignmentpaid_m->get_order_by_asset_assignmentpaid(array('schoolyearID' => $schoolyearID));
                    if(customCompute($asset_assignmentpaids)) {
                        foreach($asset_assignmentpaids as $asset_assignmentpaid) {
                            if(isset($asset_assignmentpaidsArray[$asset_assignmentpaid->asset_assignmentID])) {
                                $asset_assignmentpaidsArray[$asset_assignmentpaid->asset_assignmentID] += $asset_assignmentpaid->asset_assignmentpaidamount;
                            } else {
                                $asset_assignmentpaidsArray[$asset_assignmentpaid->asset_assignmentID] = $asset_assignmentpaid->asset_assignmentpaidamount;
                            }
                        }
                    }

                    $productArray = [];
                    $totalasset_assignmentprice = 0;
                    $totalasset_assignmentpaidamount = 0;
                    $totalasset_assignmentbalanceamount = 0;
                    
                    $lCheck = FALSE;
                    if($this->data['asset_assignmentcustomertypeID'] == 3) {
                        $lCheck = TRUE;
                    }

                    if(customCompute($asset_assignments)) {
                        foreach($asset_assignments as $product) {
                            if($lCheck) {
                                $classesID = (int)$this->data['asset_assignmentclassesID'];
                                if($classesID) {
                                    if(!(isset($users[3][$product->asset_assignmentcustomerID]) && ($users[3][$product->asset_assignmentcustomerID]->srclassesID == $classesID))) {
                                        continue;
                                    }
                                }
                            }   

                            if(isset($productArray[$product->asset_assignmentID])) {
                                $asset_assignmentbalanceamount = $productArray[$product->asset_assignmentID]['asset_assignmentbalanceamount'];

                                $productArray[$product->asset_assignmentID]['asset_assignmentprice'] += ($product->asset_assignmentunitprice * $product->asset_assignmentquantity);

                                $productArray[$product->asset_assignmentID]['asset_assignmentbalanceamount'] = ($product->asset_assignmentunitprice * $product->asset_assignmentquantity) + $asset_assignmentbalanceamount;
                            } else {
                                $productArray[$product->asset_assignmentID]['asset_assignmentID'] = $product->asset_assignmentID;
                                $productArray[$product->asset_assignmentID]['asset_assignmentreferenceno'] = $product->asset_assignmentreferenceno;
                                $productArray[$product->asset_assignmentID]['asset_assignmentrefund'] = $product->asset_assignmentrefund;
                                $productArray[$product->asset_assignmentID]['asset_assignmentcustomertype'] = isset($usertypes[$product->asset_assignmentcustomertypeID]) ? $usertypes[$product->asset_assignmentcustomertypeID] : '';
                                
                                $name = '';
                                if(isset($users[$product->asset_assignmentcustomertypeID][$product->asset_assignmentcustomerID])) {
                                    $name = isset($users[$product->asset_assignmentcustomertypeID][$product->asset_assignmentcustomerID]->name) ? $users[$product->asset_assignmentcustomertypeID][$product->asset_assignmentcustomerID]->name : $users[$product->asset_assignmentcustomertypeID][$product->asset_assignmentcustomerID]->srname;
                                }
                                $productArray[$product->asset_assignmentID]['asset_assignmentcustomerName'] = $name;
                                

                                $productArray[$product->asset_assignmentID]['asset_assignmentdate'] = date('d M Y', strtotime($product->asset_assignmentdate));
                                $productArray[$product->asset_assignmentID]['asset_assignmentprice'] = ($product->asset_assignmentunitprice * $product->asset_assignmentquantity);
                                $productArray[$product->asset_assignmentID]['asset_assignmentpaidamount'] = isset($asset_assignmentpaidsArray[$product->asset_assignmentID]) ? $asset_assignmentpaidsArray[$product->asset_assignmentID] : '0';
                                $productArray[$product->asset_assignmentID]['asset_assignmentbalanceamount'] = ($productArray[$product->asset_assignmentID]['asset_assignmentprice'] - $productArray[$product->asset_assignmentID]['asset_assignmentpaidamount']);
                                
                                $totalasset_assignmentpaidamount += isset($asset_assignmentpaidsArray[$product->asset_assignmentID]) ? $asset_assignmentpaidsArray[$product->asset_assignmentID] : '0';
                            }
                            $totalasset_assignmentprice += ($product->asset_assignmentunitprice * $product->asset_assignmentquantity);
                        }
                        $totalasset_assignmentbalanceamount = $totalasset_assignmentprice - $totalasset_assignmentpaidamount;
                    }

                    $this->data['totalasset_assignmentprice'] = $totalasset_assignmentprice;
                    $this->data['totalasset_assignmentpaidamount'] = $totalasset_assignmentpaidamount;
                    $this->data['totalasset_assignmentbalanceamount'] = $totalasset_assignmentbalanceamount;
                    $this->data['asset_assignments'] = $productArray;

                    $this->reportSendToMail('asset_assignmentreport.css', $this->data, 'report/asset_assignment/asset_assignmentReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				}
			} else {
				$retArray['message'] = $this->lang->line('asset_assignmentreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('asset_assignmentreport_permission');
			echo json_encode($retArray);
			exit;
		}

	}

	public function xlsx() {
		if(permissionChecker('asset_assignmentreport')) {
			$this->load->library('phpspreadsheet');
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="asset_assignmentreport.xlsx"');
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
        $asset_assignmentcustomertypeID = htmlentities(escapeString($this->uri->segment(3)));
         
        $asset_assignmentcustomerID     = htmlentities(escapeString($this->uri->segment(4))); 
        $fromdate     = htmlentities(escapeString($this->uri->segment(5)));
        $todate       = htmlentities(escapeString($this->uri->segment(6)));

        if((int)($asset_assignmentcustomertypeID >= 0)   && (int)($asset_assignmentcustomerID >= 0) &&   ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $this->data['asset_assignmentcustomertypeID'] = $asset_assignmentcustomertypeID; 
            $this->data['asset_assignmentcustomerID'] = $asset_assignmentcustomerID; 
            $this->data['fromdate'] = $fromdate;
            $this->data['todate'] = $todate;

            $postArray = [];
            $postArray['asset_assignmentcustomertypeID'] = $asset_assignmentcustomertypeID;
             $postArray['asset_assignmentcustomerID']     = $asset_assignmentcustomerID;
             
            if($fromdate != '' && $todate != '') {
                $postArray['fromdate'] = date('d-m-Y',$fromdate);
                $postArray['todate'] = date('d-m-Y',$todate);
            }

            $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
            $this->data['usertypes'] = $usertypes;
            $users = $this->getuserlist($postArray);
            $this->data['users'] = $users;
            $asset_assignments = $this->asset_assignment_m->get_all_asset_assignment_for_report($postArray);
 
            
                $this->data['asset_assignments'] = $asset_assignments;


                     foreach ($this->data['asset_assignments'] as $key => $assignment) {
                        $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
                        $this->data['asset_assignments'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
                    }

            return $this->generateXML($this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);		
		}
	}

	private function generateXML($arrays) {
		extract($arrays);
		if(customCompute($asset_assignments)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $topCellMerge = TRUE;
            $row = 1;        
            if($fromdate != '' || $todate != '') {
                $datefrom   = $this->lang->line('asset_assignmentreport_fromdate')." : ";
                $datefrom   .= date('d M Y',$fromdate);
                $dateto     = $this->lang->line('asset_assignmentreport_todate')." : ";
                $dateto     .= date('d M Y', $todate);

                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue('H'.$row, $dateto);
            }  elseif($asset_assignmentcustomertypeID != 0 && $asset_assignmentcustomerID != 0) {
                
                $usertype = $this->lang->line('asset_assignmentreport_role')." : ";
                $usertype .= isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : '';
                
                $userName = $this->lang->line('asset_assignmentreport_user')." : ";

                if(isset($users[3][$asset_assignmentcustomerID])) {
                    $userName .= isset($users[3][$asset_assignmentcustomerID]->name) ? $users[3][$asset_assignmentcustomerID]->name : $users[3][$asset_assignmentcustomerID]->srname;
                }

                $sheet->setCellValue('A'.$row, $usertype);
                $sheet->setCellValue('H'.$row, $userName);
            } else {
                $topCellMerge = FALSE;
                $usertype = $this->lang->line('asset_assignmentreport_role')." : ";
                $usertype .= isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : $this->lang->line('asset_assignmentreport_all');
                $sheet->setCellValue('A'.$row, $usertype);
            }

			$headers = array();
			$headers['slno'] = $this->lang->line('slno');
			$headers['asset_assignment_assetID'] = $this->lang->line('asset_assignment_assetID');
			$headers['asset_assignment_assigned_quantity'] = $this->lang->line('asset_assignment_assigned_quantity');
			$headers['asset_assignment_usertypeID'] = $this->lang->line('asset_assignment_usertypeID');
            $headers['asset_assignment_check_out_to'] = $this->lang->line('asset_assignment_check_out_to');
            $headers['asset_assignment_due_date'] = $this->lang->line('asset_assignment_due_date');
            $headers['asset_assignment_check_out_date'] = $this->lang->line('asset_assignment_check_out_date');
            $headers['asset_assignment_check_in_date'] = $this->lang->line('asset_assignment_check_in_date');
            $headers['asset_assignment_status'] = $this->lang->line('asset_assignment_status');

            if(customCompute($headers)) {
                $column = "A";
                $row = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }



			$i= 0;
            $bodys = array();
            foreach($asset_assignments as $asset_assignment) {

                if($asset_assignment->status  == 1) {
                                             $st =     $this->lang->line('asset_assignment_checked_out');
                                            } elseif($asset_assignment->status == 2) {
                                              $st =     $this->lang->line('asset_assignment_in_storage');
                                            }
                $bodys[$i][] = $i+1;
                $bodys[$i][] = $asset_assignment->description ;
                $bodys[$i][] = $asset_assignment->assigned_quantity ;
                $bodys[$i][] = $asset_assignment->usertype ;
                $bodys[$i][] = $asset_assignment->assigned_to ; 
                $bodys[$i][] = isset($asset_assignment->due_date ) ? date('d M Y', strtotime($asset_assignment->due_date )) : ''; 
                $bodys[$i][] = isset($asset_assignment->check_out_date ) ? date('d M Y', strtotime($asset_assignment->check_out_date )) : ''; 
                $bodys[$i][] = isset($asset_assignment->check_in_date ) ? date('d M Y', strtotime($asset_assignment->check_in_date )) : ''; 
                $bodys[$i][] =     $st;
                 
                $i++;
            }
              

            if(customCompute($bodys)) {
                $row = 3;
                foreach($bodys as $single_rows) {
                    $column = 'A';
                    foreach($single_rows as $value) {
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
			$sheet->getStyle('A1:I2')->applyFromArray($styleArray);

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
			$sheet->getStyle('A3:I'.$styleColumn)->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => TRUE,
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
			$sheet->getStyle('A'.$styleColumn.':I'.$styleColumn)->applyFromArray($styleArray);

             

 				
		} else {
		    redirect('asset_assignmentreport');
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


    private function getuserlist($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $retArray = [];

        $systemadmins = $this->systemadmin_m->get_systemadmin();
        if(customCompute($systemadmins)) {
            foreach ($systemadmins as $systemadmin) {
                $retArray[1][$systemadmin->systemadminID] = $systemadmin;
            }
        }

        $teachers = $this->teacher_m->general_get_teacher();
        if(customCompute($teachers)) {
            foreach ($teachers as $teacher) {
                $retArray[2][$teacher->teacherID] = $teacher;
            }
        }

        $sArray = [];
        $sArray['srschoolyearID'] = $schoolyearID;
        if(isset($queryArray['asset_assignmentcustomertypeID']) && $queryArray['asset_assignmentcustomertypeID'] == 3) {
            if(isset($queryArray['asset_assignmentclassesID']) && (int)$queryArray['asset_assignmentclassesID']) {
                $sArray['srclassesID'] = $queryArray['asset_assignmentclassesID'];
            }

            if(isset($queryArray['asset_assignmentcustomerID']) && (int)$queryArray['asset_assignmentcustomerID']) {
                $sArray['srstudentID'] = $queryArray['asset_assignmentcustomerID'];
            }
        }

        $students = $this->studentrelation_m->get_order_by_studentrelation($sArray);
        if(customCompute($students)) {
            foreach ($students as $student) {
                $retArray[3][$student->srstudentID] = $student;
            }
        }

        $parentss = $this->parents_m->get_parents();
        if(customCompute($parentss)) {
            foreach ($parentss as $parents) {
                $retArray[4][$parents->parentsID] = $parents;
            }
        }

        $users = $this->user_m->get_user();
        if(customCompute($users)) {
            foreach ($users as $user) {
                $retArray[$user->usertypeID][$user->userID] = $user;
            }
        }

        return $retArray;
    }

    public function getuser() {
        $asset_assignmentcustomertypeID = $this->input->post('asset_assignmentcustomertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        echo "<option value=\"0\">",$this->lang->line('asset_assignmentreport_please_select'),"</option>";
        if((int)$asset_assignmentcustomertypeID) {
            if($asset_assignmentcustomertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(customCompute($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        echo "<option value=\"$systemadmin->systemadminID\">",$systemadmin->name,"</option>";
                    }
                }
            } elseif($asset_assignmentcustomertypeID == 2) {
                $teachers = $this->teacher_m->general_get_teacher();
                if(customCompute($teachers)) {
                    foreach ($teachers as $teacher) {
                        echo "<option value=\"$teacher->teacherID\">",$teacher->name,"</option>";
                    }
                }
            } elseif($asset_assignmentcustomertypeID == 3) {
                $classesID = $this->input->post('asset_assignmentclassesID');
                if((int)$classesID) {
                    $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
                    if(customCompute($students)) {
                        foreach ($students as $student) {
                            echo "<option value=\"$student->srstudentID\">".$student->srname." - ".$this->lang->line('asset_assignmentreport_roll')." - ".$student->srroll."</option>";
                        }
                    }
                }
            } elseif($asset_assignmentcustomertypeID == 4) {
                $parentss = $this->parents_m->get_parents();
                if(customCompute($parentss)) {
                    foreach ($parentss as $parents) {
                        echo "<option value=\"$parents->parentsID\">",$parents->name,"</option>";
                    }
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $asset_assignmentcustomertypeID));
                if(customCompute($users)) {
                    foreach ($users as $user) {
                        echo "<option value=\"$user->userID\">",$user->name,"</option>";
                    }
                }
            }
        }
    }



}
