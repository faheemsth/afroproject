<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition extends Admin_Controller {
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
        $this->load->model("requisition_m");
        $this->load->model("productcategory_m");
        $this->load->model("product_m");
        $this->load->model("productsupplier_m");
        $this->load->model("productwarehouse_m");
        $this->load->model("requisitionitem_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("user_m");
        //$this->load->model("requisitionpaid_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('requisition', $language);
    }

    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
            )
        );

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
       
        $this->data['requisitions'] = $this->requisition_m->get_order_by_requisition(array('schoolyearID' => $schoolyearID));
        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
      //  $this->data['grandtotalandpaid'] = $this->grandtotalandpaid($this->data['requisitions'], $schoolyearID);
        $this->data["subview"] = "requisition/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function issueinventory() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
            )
        );

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
         
        $this->data['requisitions'] = $this->requisition_m->get_order_by_requisition(array('requisitionstatus' => 0,'aprove_status_first' => 1,'aprove_status_second' => 1));
        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
      //  $this->data['grandtotalandpaid'] = $this->grandtotalandpaid($this->data['requisitions'], $schoolyearID);
        $this->data["subview"] = "requisition/issueinventory";
        $this->load->view('_layout_main', $this->data);
    }

    public function active() {
        if(permissionChecker('requisition_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $id));
                    if(customCompute($requisition)) {
                        if($status == 'chacked') { 
                            $this->requisition_m->update_requisition(array( 
                                                    'aprove_status_first' => 1,
                                                    'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'firstAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->requisition_m->update_requisition(array( 
                                                    'aprove_status_first' => 0,
                                                    'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'firstAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
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
    
    public function active_second() {
        if(permissionChecker('requisition_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $id));
                    if(customCompute($requisition)) {
                        if($status == 'chacked') { 
                            $this->requisition_m->update_requisition(array( 
                                                    'aprove_status_second' => 1,
                                                    'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'secondAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->requisition_m->update_requisition(array( 
                                                    'aprove_status_second' => 0,
                                                    'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'secondAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
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
    protected function rules() {
        $rules = array(
            array(
                'field' => 'userID',
                'label' => 'Check out User',
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
            ),
            array(
                'field' => 'usertypeID',
                'label' => 'User Role',
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
            ), 
            array(
                'field' => 'requisitiondate',
                'label' => $this->lang->line("requisition_date"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_date_valid'
            ),
            array(
                'field' => 'requisitionfile',
                'label' => $this->lang->line("requisition_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_fileupload'
            ),
            array(
                'field' => 'requisitiondescription',
                'label' => $this->lang->line("requisition_description"),
                'rules' => 'trim|xss_clean|max_length[520]'
            ),
            array(
                'field' => 'productitem',
                'label' => $this->lang->line("requisition_productitem"),
                'rules' => 'trim|xss_clean|callback_unique_productitem'
            ),
            array(
                'field' => 'editID',
                'label' => $this->lang->line("requisition_editid"),
                'rules' => 'trim|required|xss_clean|numeric'
            )
        );
        return $rules;
    }

    public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
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
            
            $this->data['productcategorys'] = $this->productcategory_m->get_productcategory(); 
            $this->data['productobj'] = json_encode(pluck($this->product_m->get_product(), 'obj', 'productID'));

            $this->data['usertypes'] = $this->usertype_m->get_usertype();
            $this->data['checkOutToUesrs'] = array();

            $this->data["subview"] = "requisition/add";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
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
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->data['requisitionID'] = $id;
                $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
                $this->data['usertypes'] = $this->usertype_m->get_usertype();

                 $usertypeID    = $this->data['requisition']->usertypeID;
                 $userID        = $this->data['requisition']->userID;

                 $this->data['checkOutToUesrs']      =   $this->allUsersArray_edit($usertypeID,$userID);
               
                $this->data['productwarehouseArray'] = $this->allUsersArray_edit($usertypeID,$userID);
                

                $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
                $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID'); 

                 

                if($this->data['requisition']) {
                     
                        $this->data['requisitionitems'] = $this->requisitionitem_m->get_order_by_requisitionitem(array('schoolyearID' => $schoolyearID, 'requisitionID' => $id));
                        $this->data["subview"] = "requisition/edit";
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


    public function allUsersArray($usertypeID, $classesID = 0) {
        $returnArray[0] = $this->lang->line('asset_assignment_select_user');
        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if(customCompute($systemadmins)) {
                foreach ($systemadmins as $key => $systemadmin) {
                    $returnArray[$systemadmin->systemadminID] = $systemadmin->name;
                }
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if(customCompute($teachers)) {
                foreach ($teachers as $key => $teacher) {
                    $returnArray[$teacher->teacherID] = $teacher->name;
                }
            }
        } elseif($usertypeID == 3) {
            $students = $this->student_m->get_order_by_student(array( 'schoolyearID' => $this->data['siteinfos']->school_year));
            if(customCompute($students)) {
                foreach ($students as $key => $student) {
                    $returnArray[$student->studentID] = $student->name;
                }
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if(customCompute($parents)) {
                foreach ($parents as $key => $parent) {
                    $returnArray[$parent->parentsID] = $parent->name;
                }
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if(customCompute($users)) {
                foreach ($users as $key => $user) {
                    $returnArray[$user->userID] = $user->name;
                }
            }
        }

        return $returnArray;
    }

    


    public function allUsersArray_edit($usertypeID, $getuserID ) {
       // $returnArray[0] = $this->lang->line('asset_assignment_select_user');
        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if(customCompute($systemadmins)) {
                foreach ($systemadmins as $key => $systemadmin) {
                    if ($getuserID == $systemadmin->systemadminID) {
                     
                    $returnArray[$systemadmin->systemadminID] = $systemadmin->name;
                    }
                }
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if(customCompute($teachers)) {
                foreach ($teachers as $key => $teacher) {
                    if ($getuserID == $teacher->teacherID) {
                    $returnArray[$teacher->teacherID] = $teacher->name;
                    }
                }
            }
        } elseif($usertypeID == 3) {
            $students = $this->student_m->get_order_by_student(array( 'schoolyearID' => $this->data['siteinfos']->school_year));
            if(customCompute($students)) {
                foreach ($students as $key => $student) {
                    if ($getuserID == $student->studentID) {
                    $returnArray[$student->studentID] = $student->name;
                    }
                }
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if(customCompute($parents)) {
                foreach ($parents as $key => $parent) {
                    if ($getuserID == $parent->parentsID) {
                    $returnArray[$parent->parentsID] = $parent->name;
                    }
                }
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if(customCompute($users)) {
                foreach ($users as $key => $user) {
                    if ($getuserID == $user->userID) {
                    $returnArray[$user->userID] = $user->name;
                    }
                }
            }
        }

        return $returnArray;
    }

    public function view() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id) {
            $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->data['requisitionitems'] = $this->requisitionitem_m->get_order_by_requisitionitem(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

             


            if($this->data['requisition']) {
                $this->data['createuser'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->create_usertypeID, $this->data['requisition']->create_userID);
                $this->data['fromuser'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->usertypeID, $this->data['requisition']->userID);
                $this->data['approve_one'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->firstAprove_usertypeID, $this->data['requisition']->firstAprove_userID);
                $this->data['approve_two'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->secondAprove_usertypeID, $this->data['requisition']->secondAprove_userID);

                 

                $this->data["subview"] = "requisition/view";
                $this->load->view('_layout_main', $this->data);
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
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
                if(customCompute($this->data['requisition'])) {
                    $this->data['requisitionpaid'] = $this->requisitionpaid_m->get_requisitionpaid_sum('requisitionpaidamount', array('requisitionID' => $id));
                    if(($this->data['requisition']->requisitionrefund == 0) && ($this->data['requisitionpaid']->requisitionpaidamount == NULL)) {
                        $this->requisition_m->delete_requisition($id);
                        $this->requisitionitem_m->delete_requisitionitem_by_requisitionID($id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("requisition/index"));
                    } else {
                        redirect(base_url("requisition/index"));
                    }
                } else {
                    redirect(base_url("requisition/index"));
                }
            } else {
                redirect(base_url("requisition/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function cancel() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            if(permissionChecker('requisition_edit')) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if((int)$id) {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
                    if(customCompute($this->data['requisition'])) {
                        $this->requisition_m->update_requisition(array('requisitionrefund' => 1), $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("requisition/index"));
                    } else {
                        redirect(base_url("requisition/index"));
                    }
                } else {
                    redirect(base_url("requisition/index"));
                }
            } else {
                redirect(base_url("requisition/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function print_preview() {
        if(permissionChecker('requisition_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->data['requisitionitems'] = $this->requisitionitem_m->get_order_by_requisitionitem(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

             



                if($this->data['requisition']) {
                $this->data['createuser'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->create_usertypeID, $this->data['requisition']->create_userID);
                $this->data['fromuser'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->usertypeID, $this->data['requisition']->userID);
                $this->data['approve_one'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->firstAprove_usertypeID, $this->data['requisition']->firstAprove_userID);
                $this->data['approve_two'] = getObjectByUserTypeIDAndUserID($this->data['requisition']->secondAprove_usertypeID, $this->data['requisition']->secondAprove_userID);

                 

                    $this->reportPDF('requisitionmodule.css', $this->data, 'requisition/print_preview');
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

    public function valid_data($data) {
        if($data != '') {
            if($data == 0) {
                $this->form_validation->set_message('valid_data','The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    protected function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'requisitionID',
                'label' => $this->lang->line('requisition_id'),
                'rules' => 'trim|required|xss_clean|numeric|callback_valid_data'
            ), array(
                'field' => 'to',
                'label' => $this->lang->line('to'),
                'rules' => 'trim|required|xss_clean|valid_email'
            ), array(
                'field' => 'subject',
                'label' => $this->lang->line('subject'),
                'rules' => 'trim|required|xss_clean'
            ), array(
                'field' => 'message',
                'label' => $this->lang->line('message'),
                'rules' => 'trim|xss_clean'
            )
        );
        return $rules;
    }

    public function send_mail() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('requisition_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $to         = $this->input->post('to');
                    $subject    = $this->input->post('subject');
                    $message    = $this->input->post('message');
                    $id         = $this->input->post('requisitionID');

                    $this->data['requisition'] = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
            
                    $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                    
                    $this->data['requisitionitems'] = $this->requisitionitem_m->get_order_by_requisitionitem(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));

                    $this->data['requisitionpaid'] = $this->requisitionpaid_m->get_requisitionpaid_sum('requisitionpaidamount', array('requisitionID' => $id));

                    if($this->data['requisition']) {
                        $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['requisition']->create_usertypeID, $this->data['requisition']->create_userID);

                        $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $this->data['requisition']->productsupplierID));
                        $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $this->data['requisition']->productwarehouseID));

                        $this->reportSendToMail('requisitionmodule.css', $this->data, 'requisition/print_preview', $to, $subject, $message);
                        $retArray['message'] = "Success";
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $retArray['message'] = $this->lang->line('requisition_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('requisition_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('requisition_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function download() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('requisition')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$requisition->requisitionfile);
                $originalname = $requisition->requisitionfileorginalname;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('requisition/index'));
                }
            } else {
                redirect(base_url('requisition/index'));
            }
        } else {
            redirect(base_url('requisition/index'));
        }
    }

    public function getrequisition() {
        $productcategoryID = $this->input->post('productcategoryID');
        if((int)$productcategoryID) {
            $products = $this->product_m->get_order_by_product(array('productcategoryID' => $productcategoryID));
            echo "<option value='0'>", $this->lang->line("requisition_select_product"),"</option>";
            foreach ($products as $product) {
                echo "<option value=\"$product->productID\">",$product->productname,"</option>";
            }
        }
    }

    public function unique_productsupplierID() {
        if($this->input->post('productsupplierID') == 0) {
            $this->form_validation->set_message("unique_productsupplierID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_productwarehouseID() {
        if($this->input->post('productwarehouseID') == 0) {
            $this->form_validation->set_message("unique_productwarehouseID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function date_valid($date) {
        if($date) {
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
        return TRUE;
    }

    public function unique_productitem() {
        $productitems = json_decode($this->input->post('productitem'));
        $status = FALSE;
        if(customCompute($productitems)) {
            foreach ($productitems as $productitem) {

                if($productitem->quantity != '') {
                    $status = TRUE;
                }
            }
        }

        if($status) {
            return TRUE;
        } else {
            $this->form_validation->set_message("unique_productitem", "The product item is required.");
            return FALSE;
        }
    }

    public function fileupload() {
        $id = $this->input->post('editID');
        $requisition = [];
        if((int)$id && $id > 0) {
            $requisition = $this->requisition_m->get_requisition($id);
        }

        $new_file = "";
        $original_file_name = '';
        if($_FILES["requisitionfile"]['name'] !="") {
            $file_name = $_FILES["requisitionfile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'requisition'.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(customCompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '2048';
                $config['max_width'] = '30000';
                $config['max_height'] = '30000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("requisitionfile")) {
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("fileupload", "Invalid file");
                return FALSE;
            }
        } else {
            if(customCompute($requisition)) {
                $this->upload_data['file'] = array('file_name' => $requisition->requisitionfile);
                $this->upload_data['file']['original_file_name'] = $requisition->requisitionfileorginalname;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return TRUE;
            }
        }
    }

    private function grandtotalandpaid($requisitions, $schoolyearID) {
        $retArray = [];
        
        $requisitionitems = pluck_multi_array($this->requisitionitem_m->get_order_by_requisitionitem(array('schoolyearID' => $schoolyearID)), 'obj', 'requisitionID');

        $requisitionpaids = pluck_multi_array($this->requisitionpaid_m->get_order_by_requisitionpaid(array('schoolyearID' => $schoolyearID)), 'obj', 'requisitionID');

        if(customCompute($requisitions)) {
            foreach ($requisitions as $requisition) {
                if(isset($requisitionitems[$requisition->requisitionID])) {
                    if(customCompute($requisitionitems[$requisition->requisitionID])) {
                        foreach ($requisitionitems[$requisition->requisitionID] as $requisitionitem) {
                            if(isset($retArray['grandtotal'][$requisitionitem->requisitionID])) {
                                $retArray['grandtotal'][$requisitionitem->requisitionID] = (($retArray['grandtotal'][$requisitionitem->requisitionID]) + ($requisitionitem->requisitionunitprice*$requisitionitem->requisitionquantity));
                            } else {
                                $retArray['grandtotal'][$requisitionitem->requisitionID] = ($requisitionitem->requisitionunitprice*$requisitionitem->requisitionquantity);
                            }
                        }
                    }
                }

                if(isset($requisitionpaids[$requisition->requisitionID])) {
                    if(customCompute($requisitionpaids[$requisition->requisitionID])) {
                        foreach ($requisitionpaids[$requisition->requisitionID] as $requisitionpaid) {
                            if(isset($retArray['totalpaid'][$requisitionpaid->requisitionID])) {
                                $retArray['totalpaid'][$requisitionpaid->requisitionID] = (($retArray['totalpaid'][$requisitionpaid->requisitionID]) + ($requisitionpaid->requisitionpaidamount));
                            } else {
                                $retArray['totalpaid'][$requisitionpaid->requisitionID] = ($requisitionpaid->requisitionpaidamount);
                            }
                        }
                    }
                }
            }
        }
        return $retArray;
    }

    public function saverequisition() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $requisitionID = 0;
            $retArray['status'] = FALSE;
            if(permissionChecker('requisition_add') || permissionChecker('requisition_edit')) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $retArray['error'] = $this->form_validation->error_array();
                        $retArray['status'] = FALSE;
                        echo json_encode($retArray);
                        exit;
                    } else {

                        // var_dump(json_decode($this->input->post('productitem')));
                        // exit();
                        $schoolyearID = $this->session->userdata('defaultschoolyearID');
                        $array = array(
                            'schoolyearID' => $schoolyearID,
                            "userID" => $this->input->post("userID"),
                            "usertypeID" => $this->input->post("usertypeID"),
                            "requisitionreferenceno" => $this->input->post("requisitionreferenceno"),
                            "requisitiondate" => date('Y-m-d', strtotime($this->input->post("requisitiondate"))),
                            "requisitiondescription" => $this->input->post("requisitiondescription"),
                            "requisitionstatus" => 0,
                            "requisitionrefund" => 0,
                            "requisitionfile" => $this ->upload_data['file']['file_name'],
                            "requisitionfileorginalname" => $this ->upload_data['file']['original_file_name'],
                            'create_date' => date('Y-m-d H:i:s'),
                            'modify_date' => date('Y-m-d H:i:s'),
                            'create_userID' => $this->session->userdata('loginuserID'),
                            'create_usertypeID' => $this->session->userdata('usertypeID')
                        );

                        $updateID = $this->input->post('editID');
                        if(permissionChecker('requisition_edit')) {
                            if($updateID > 0) {
                                $requisitionID = $updateID;
                                $this->requisitionitem_m->delete_requisitionitem_by_requisitionID($requisitionID);
                            } else {
                                 $array['refrence_no']    =   $this->requisition_m->get_req_number_by_date($this->input->post("requisitiondate"));
                                $this->requisition_m->insert_requisition($array);
                                $requisitionID = $this->db->insert_id();
                            }
                        } else {
                            $array['refrence_no']    =   $this->requisition_m->get_req_number_by_date($this->input->post("requisitiondate"));
                            $this->requisition_m->insert_requisition($array);
                            $requisitionID = $this->db->insert_id();
                        }

                        $totalAmount = 0;
                        $requisitionitem = [];
                        $productitems = json_decode($this->input->post('productitem'));
                        if(customCompute($productitems)) {

                            if($updateID == 0) {
                                $productitemschoolyearID = $schoolyearID;
                            } else {
                                $updatedata = $this->requisition_m->get_single_requisition(array('requisitionID' => $updateID));
                                if(customCompute($updatedata)) {
                                    $productitemschoolyearID = $updatedata->schoolyearID;
                                } else {
                                    $productitemschoolyearID = $schoolyearID;
                                }
                            }

                            foreach ($productitems as $productitem) {
                                if($productitem->unitprice != '' && $productitem->quantity != '') {
                                    $totalAmount += (($productitem->unitprice * $productitem->quantity));
                                    $requisitionitem[] = array(
                                        'schoolyearID' => $productitemschoolyearID,
                                        'requisitionID' => $requisitionID,
                                        'productID' => $productitem->productID, 
                                        'requisitionquantity' => $productitem->quantity,
                                        'requisitionunitprice' => $productitem->unitprice,
                                    );          
                                }
                            }
                        }

                        if(permissionChecker('requisition_edit')) {
                            if($updateID > 0) {
                                unset($array['create_date']);
                                $this->requisition_m->update_requisition($array, $updateID);
                            }
                        }

                        $this->requisitionitem_m->insert_batch_requisitionitem($requisitionitem);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $retArray['status'] = TRUE;
                        $retArray['message'] = 'Success';
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    $retArray['error'] = array('posttype', 'Post type is required.');
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['error'] = array('permission', 'Purchase permission is required.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission', 'Purchase permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }

    public function paymentlist() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $requisitionID = $this->input->post('requisitionID');

        $paymentmethodarray = array(
            1 => $this->lang->line('requisition_cash'),
            2 => $this->lang->line('requisition_cheque'),
            3 => $this->lang->line('requisition_credit_card'),
            4 => $this->lang->line('requisition_other'),
        );

        if(!empty($requisitionID) && (int)$requisitionID && $requisitionID > 0) {
            $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $requisitionID, 'schoolyearID' => $schoolyearID));
            if(customCompute($requisition)) {
                $requisitionpaids = $this->requisitionpaid_m->get_order_by_requisitionpaid(array('requisitionID' => $requisitionID));
                if(customCompute($requisitionpaids)) {
                    $i = 1; 
                    foreach ($requisitionpaids as $requisitionpaid) {
                        echo '<tr>';
                            echo '<td data-title="'.$this->lang->line('slno').'">';
                                echo $i;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('requisition_date').'">';
                                echo date('d M Y', strtotime($requisitionpaid->requisitionpaiddate));
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('requisition_referenceno').'">';
                                echo namesorting($requisitionpaid->requisitionpaidreferenceno, 30);
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('requisition_amount').'">';
                                echo number_format($requisitionpaid->requisitionpaidamount, 2);
                                if($requisitionpaid->requisitionpaidfile != "") {
                                    echo ' <a href="'.base_url("requisition/paymentfiledownload/".$requisitionpaid->requisitionpaidID).'" style="color:#428bca"><i class="fa fa-chain"></i></a>';
                                    
                                }
                            echo '</td>'; 

                            echo '<td data-title="'.$this->lang->line('requisition_paid_by').'">';
                                if(isset($paymentmethodarray[$requisitionpaid->requisitionpaidpaymentmethod])) {
                                    echo $paymentmethodarray[$requisitionpaid->requisitionpaidpaymentmethod];
                                }
                            echo '</td>';

                            if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
                                echo '<td data-title="'.$this->lang->line('action').'">';
                                    if($requisition->requisitionrefund == 0) {
                                        if(permissionChecker('requisition_delete')) {
                                            echo '<a href="'.base_url('requisition/deletepurchasepaid/'.$requisitionpaid->requisitionpaidID).'" onclick="return confirm('."'".'you are about to delete a record. This cannot be undone. are you sure?'."'".')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>';
                                        }
                                    }
                                echo '</td>';
                            }
                        echo '</tr>';

                        $i++;
                    }
                }
            }
        }
    }

    public function deletepurchasepaid() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $requisitionpaidID = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            
            if(permissionChecker('requisition_delete')) {
                if((int)$requisitionpaidID) {
                    $requisitionpaid = $this->requisitionpaid_m->get_single_requisitionpaid(array('requisitionpaidID' => $requisitionpaidID));
                    if(customCompute($requisitionpaid)) {
                        $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $requisitionpaid->requisitionID, 'schoolyearID' => $schoolyearID));
                        if(customCompute($requisition) && $requisition->requisitionrefund == 0) {

                            $this->requisitionpaid_m->delete_requisitionpaid($requisitionpaidID);

                            $requisitionitemsum = $this->requisitionitem_m->get_requisitionitem_sum(array('requisitionID' => $requisition->requisitionID, 'schoolyearID' => $schoolyearID));

                            $requisitionpaidsum = $this->requisitionpaid_m->get_requisitionpaid_sum('requisitionpaidamount', array('requisitionID' => $requisition->requisitionID));

                            $array = [];
                            if($requisitionpaidsum->requisitionpaidamount == NULL) {
                                $array['requisitionstatus'] = 0;
                            } elseif((float)$requisitionitemsum->result == (float)$requisitionpaidsum->requisitionpaidamount) {
                                $array['requisitionstatus'] = 2;
                            } elseif((float)$requisitionpaidsum->requisitionpaidamount > 0 && ((float)$requisitionitemsum->result > (float)$requisitionpaidsum->requisitionpaidamount)) {
                                $array['requisitionstatus'] = 1;
                            } elseif((float)$requisitionpaidsum->requisitionpaidamount > 0 && ((float)$requisitionitemsum->result < (float)$requisitionpaidsum->requisitionpaidamount)) {
                                $array['requisitionstatus'] = 2;
                            }

                            $this->requisition_m->update_requisition($array, $requisition->requisitionID);

                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url('requisition/index'));
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

    public function paymentfiledownload() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('requisition')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $requisitionpaid = $this->requisitionpaid_m->get_single_requisitionpaid(array('requisitionpaidID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$requisitionpaid->requisitionpaidfile);
                $originalname = $requisitionpaid->requisitionpaidorginalname;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('requisition/index'));
                }
            } else {
                redirect(base_url('requisition/index'));
            }
        } else {
            redirect(base_url('requisition/index'));
        }
    }

    public function getpurchaseinfo() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $requisitionID = $this->input->post('requisitionID');
        
        $retArray['status'] = FALSE;
        $retArray['dueamount'] = 0.00; 
        if(permissionChecker('requisition_add')) {
            if(!empty($requisitionID) && (int)$requisitionID && $requisitionID > 0) {
                $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $requisitionID, 'schoolyearID' => $schoolyearID));
                if(customCompute($requisition)) {
                    if($requisition->requisitionrefund == 0 && $requisition->requisitionstatus != 2) {
                        $requisitionitemsum = $this->requisitionitem_m->get_requisitionitem_sum(array('requisitionID' => $requisitionID, 'schoolyearID' => $schoolyearID));
                        $requisitionpaidsum = $this->requisitionpaid_m->get_requisitionpaid_sum('requisitionpaidamount', array('requisitionID' => $requisitionID));

                        $retArray['dueamount'] = number_format((($requisitionitemsum->result) - ($requisitionpaidsum->requisitionpaidamount)), 2, '.', '');
                        $retArray['status'] = TRUE;
                    }
                }
            }
        }   

        echo json_encode($retArray);
        exit;
    }

    protected function rules_payment() {
        $rules = array(
            array(
                'field' => 'requisitionpaiddate',
                'label' => $this->lang->line("requisition_date"),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
            ),
            array(
                'field' => 'requisitionpaidreferenceno',
                'label' => $this->lang->line("requisition_referenceno"),
                'rules' => 'trim|required|xss_clean|max_length[99]'
            ),
            array(
                'field' => 'requisitionpaidamount',
                'label' => $this->lang->line("requisition_amount"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[15]'
            ),
            array(
                'field' => 'requisitionpaidpaymentmethod',
                'label' => $this->lang->line("requisition_paymentmethod"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[1]|callback_valid_data'
            ),
            array(
                'field' => 'requisitionID',
                'label' => $this->lang->line("requisition_description"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]'
            ),
            array(
                'field' => 'requisitionpaidfile',
                'label' => $this->lang->line("requisition_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_paidfileupload'
            )
        );
        return $rules;
    }

    public function paidfileupload() {
        $new_file = "";
        $original_file_name = '';
        if($_FILES["requisitionpaidfile"]['name'] !="") {
            $file_name = $_FILES["requisitionpaidfile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'requisitionpaidfile'.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(customCompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '2048';
                $config['max_width'] = '30000';
                $config['max_height'] = '30000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("requisitionpaidfile")) {
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("fileupload", "Invalid file");
                return FALSE;
            }
        } else {
            $this->upload_data['file']['file_name'] = '';
            $this->upload_data['file']['original_file_name'] = '';
            return TRUE;
        }
    }

    public function saverequisitionpayment() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $requisitionID = 0;
        $retArray['status'] = FALSE;
        if(permissionChecker('requisition_add')) {
            $requisition = $this->requisition_m->get_single_requisition(array('requisitionID' => $this->input->post('requisitionID')));

            if(customCompute($requisition)) {
                if($requisition->requisitionrefund == 0 && $requisition->requisitionstatus != 2) {
                    if($_POST) {
                        $rules = $this->rules_payment();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $retArray['error'] = $this->form_validation->error_array();
                            $retArray['status'] = FALSE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $array = array(
                                'schoolyearID' => $schoolyearID,
                                'requisitionpaidschoolyearID' => $this->data['siteinfos']->school_year,
                                'requisitionID' => $this->input->post('requisitionID'),
                                'requisitionpaiddate' => date('Y-m-d', strtotime($this->input->post("requisitionpaiddate"))), 
                                'requisitionpaidreferenceno' => $this->input->post('requisitionpaidreferenceno'),
                                'requisitionpaidamount' => $this->input->post('requisitionpaidamount'),
                                'requisitionpaidpaymentmethod  ' => $this->input->post('requisitionpaidpaymentmethod'),
                                'requisitionpaiddescription  ' => '',

                                "requisitionpaidfile" => $this ->upload_data['file']['file_name'],
                                "requisitionpaidorginalname" => $this ->upload_data['file']['original_file_name'],

                                'create_date' => date('Y-m-d H:i:s'),
                                'modify_date' => date('Y-m-d H:i:s'),
                                'create_userID' => $this->session->userdata('loginuserID'),
                                'create_usertypeID' => $this->session->userdata('usertypeID'),
                            );

                            $this->requisitionpaid_m->insert_requisitionpaid($array);

                            $requisitionitemsum = $this->requisitionitem_m->get_requisitionitem_sum(array('requisitionID' => $this->input->post('requisitionID'), 'schoolyearID' => $schoolyearID));

                            $requisitionpaidsum = $this->requisitionpaid_m->get_requisitionpaid_sum('requisitionpaidamount', array('requisitionID' => $this->input->post('requisitionID')));

                            $requisitionarray['requisitionstatus'] = 1; 
                            if((float)$requisitionitemsum->result == (float)$requisitionpaidsum->requisitionpaidamount) {
                                $requisitionarray['requisitionstatus'] = 2;
                            } elseif((float)$requisitionpaidsum->requisitionpaidamount > 0 && ((float)$requisitionitemsum->result > (float)$requisitionpaidsum->requisitionpaidamount)) {
                                $requisitionarray['requisitionstatus'] = 1;
                            } elseif((float)$requisitionpaidsum->requisitionpaidamount > 0 && ((float)$requisitionitemsum->result < (float)$requisitionpaidsum->requisitionpaidamount)) {
                                $requisitionarray['requisitionstatus'] = 2;
                            }

                            $this->requisition_m->update_requisition($requisitionarray, $this->input->post('requisitionID'));
                        
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            $retArray['status'] = TRUE;
                            $retArray['message'] = 'Success';
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['error'] = array('posttype' => 'Post type is required.');
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    $retArray['error'] = array('permission' => 'This invoice already fully paid.');
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['error'] = array('permission' => 'Purchase ID does not found.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission' => 'Add payment permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }
}
