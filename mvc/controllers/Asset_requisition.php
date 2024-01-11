<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_requisition extends Admin_Controller {
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
        $this->load->model("user_m");
        $this->load->model("asset_m");
        $this->load->model("location_m");
        $this->load->model("purchase_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model('classes_m');
        $this->load->model('invoice_m');
        $this->load->model("asset_requisition_m");
        $this->load->model("studentrelation_m");
        $this->load->model("asset_category_m");
        $this->load->model("assets_product_inout_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('asset', $language);
        $this->lang->load('asset_requisition', $language);
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
        $this->data['categories'] = $this->asset_category_m->get_asset_category();

        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
        $this->data["cat_type"]               = 0;
        $this->data["asset_categoryID"]       = 0;
        $this->data["status"]                 = 0; 
        $this->data["asset_number"]           = "";
        $this->data["description"]            = ""; 

        


            if ($_GET) {
                $array = [];
                $cat_type = $_GET["cat_type"];
                if ($cat_type != 0) {
                    $array["cat_type"]        = $cat_type;
                    $this->data["cat_type"]   = $cat_type;
                     
                }

                $asset_categoryID = $_GET["asset_categoryID"];
                if ($asset_categoryID != 0) {

                    $array["asset_category.asset_categoryID"]         = $asset_categoryID;
                    $this->data["asset_categoryID"]    = $asset_categoryID;
                     
                }
                $status      = $_GET["status"];

                if ($status != 0) {
                    $array["asset_requisition.status"]         = $status;
                    $this->data["status"]    = $status;
 
                }


                // $asset_number      =   $_GET["asset_number"];

                // if ($asset_number != 0) {
                //     $array["asset_number"]      = $asset_number;
                //     $this->data["asset_number"] = $asset_number;
                // }

                $description   =   $_GET["description"];

                if ($description != "") {
                    $array["asset.description LIKE"]     = "%$description%";
                    $this->data["description"]     = $description;
                } 
                $asset_number   =   $_GET["asset_number"];

                if ($asset_number != "") {
                    $array["asset_number LIKE"]     = "%$asset_number%";
                    $this->data["asset_number"]     = $asset_number;
                } 
                 

                 
            }else{

                $array = NULL;
            }
        $this->data['asset_requisitions'] = $this->asset_requisition_m->get_asset_requisition_with_userypeID($array);
        foreach ($this->data['asset_requisitions'] as $key => $assignment) {
            $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
            $this->data['asset_requisitions'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
        }
        $this->data["subview"] = "asset_requisition/index";
        $this->load->view('_layout_main', $this->data);
    }

    

    public function asset_assignment() {

        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );
        $this->data['categories'] = $this->asset_category_m->get_asset_category();

        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
        $this->data["cat_type"]               = 0;
        $this->data["asset_categoryID"]       = 0;
        $this->data["status"]                 = 0; 
        $this->data["asset_number"]           = "";
        $this->data["description"]            = ""; 
 

                $array = [];
                $array["asset_requisition.aprove_status_first"]         = 1;
                $array["asset_requisition.aprove_status_second"]         = 1;
             
        $this->data['asset_requisitions'] = $this->asset_requisition_m->get_asset_requisition_with_userypeID($array);
        foreach ($this->data['asset_requisitions'] as $key => $assignment) {
            $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
            $this->data['asset_requisitions'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
        }
        $this->data["subview"] = "asset_requisition/asset_assignment";
        $this->load->view('_layout_main', $this->data);
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

    protected function rules() {
        $rules = array(
            array(
                'field' => 'assetID',
                'label' => $this->lang->line("asset_requisition_assetID"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_assetID',
            ),
            array(
                'field' => 'assigned_quantity',
                'label' => $this->lang->line("asset_requisition_assigned_quantity"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_valid_quantity'
            ),
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line("asset_requisition_usertypeID"),
                'rules' => 'trim|required|numeric|xss_clean|max_length[128]',
            ),
            array(
                'field'  => 'check_out_to',
                'label'  => $this->lang->line("asset_requisition_check_out_to"),
                'rules'  => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'due_date',
                'label' => $this->lang->line("asset_requisition_due_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'check_out_date',
                'label' => $this->lang->line("asset_requisition_check_out_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'check_in_date',
                'label' => $this->lang->line("asset_requisition_check_in_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'asset_locationID',
                'label' => $this->lang->line("asset_requisition_location"),
                'rules' => 'trim|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("asset_requisition_status"),
                'rules' => 'trim|xss_clean|max_length[11]|callback_unique_status'
            ),
            array(
                'field' => 'note',
                'label' => $this->lang->line("asset_requisition_note"),
                'rules' => 'trim|xss_clean'
            ),
            

        );
        return $rules;
    }

    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'asset_requisitionID',
                'label' => $this->lang->line("asset_requisition_asset_requisition"),
                'rules' => 'trim|required|max_length[10]|xss_clean'
            )
        );
        return $rules;
    }

    public function add() {

        $this->data['categories'] = $this->asset_category_m->get_asset_category();
        $this->data['showClass'] = FALSE;
        $this->data['sendClasses'] = array();
        $this->data['checkOutToUesrs'] = array();
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['locations'] = $this->location_m->get_location();
        $this->data['assets'] = $this->asset_m->get_asset();

        if($_POST) {
            if ($this->input->post('usertypeID')) {
                $this->data['usertypeID'] = $this->input->post('usertypeID');
            }

            if($this->input->post('usertypeID') == 3) {
                $this->data['showClass'] = TRUE;
                $this->data['sendClasses'] = $this->classes_m->get_classes();
                $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'), $this->input->post('classesID'));
            } else {
                $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'));
            }


            $rules = $this->rules();
          
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "/asset_requisition/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "assetID" => $this->input->post("assetID"),
                    "assigned_quantity" => $this->input->post("assigned_quantity"),
                    "usertypeID" => $this->input->post("usertypeID"),
                    "check_out_to" => $this->input->post("check_out_to"),
                    "asset_locationID" => $this->input->post("asset_locationID"),
                    "note" => $this->input->post("note"),
                    "status" => $this->input->post("status")
                );

                

                if ($this->input->post("check_out_to") == 'select') {
                    $array["check_out_to"] = 0;
                }
                if($this->input->post('check_out_date')) {
                    $array["check_out_date"] = date("Y-m-d", strtotime($this->input->post("check_out_date")));
                }
                if($this->input->post('due_date')) {
                    $array["due_date"] 		= date("Y-m-d", strtotime($this->input->post("due_date")));
                }
                if($this->input->post('check_in_date')) {
                    $array["check_in_date"] = date("Y-m-d", strtotime($this->input->post("check_in_date")));
                }
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');

                $assignmentID    =    $this->asset_requisition_m->insert_asset_requisition($array);
 
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_requisition/index"));
            }
        } else {
            $this->data["subview"] = "/asset_requisition/add";
            $this->load->view('_layout_main', $this->data);
        }
    }



    public function active() {
        if(permissionChecker('asset_requisition_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $requisition = $this->asset_requisition_m->get_single_asset_requisition(array('asset_requisitionID' => $id));
                    if(customCompute($requisition)) {
                        if($status == 'chacked') { 
                            $this->asset_requisition_m->update_asset_requisition(array( 
                                                    'aprove_status_first' => 1,
                                                    'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'firstAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->asset_requisition_m->update_asset_requisition(array( 
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
        if(permissionChecker('asset_requisition_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $requisition = $this->asset_requisition_m->get_single_asset_requisition(array('asset_requisitionID' => $id));
                    if(customCompute($requisition)) {
                        if($status == 'chacked') { 
                            $this->asset_requisition_m->update_asset_requisition(array( 
                                                    'aprove_status_second' => 1,
                                                    'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'secondAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->asset_requisition_m->update_asset_requisition(array( 
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

    public function edit() {
        $this->data['showClass'] = FALSE;
        $this->data['sendClasses'] = array();
        $this->data['checkOutToUesrs'] = array();
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['locations'] = $this->location_m->get_location();
        $this->data['assets'] = $this->asset_m->get_asset();
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_requisition'] = $this->asset_requisition_m->get_single_asset_requisition(array('asset_requisitionID' => $id));
            if($this->data['asset_requisition']) {
                $usertypeID = $this->data['asset_requisition']->usertypeID;


                if($usertypeID == 3) {
                    $this->data['showClass'] = TRUE;
                    $this->data['sendClasses'] = $this->classes_m->get_classes();
                    $this->data['checkOutToUesrs'] = $this->allUsersArray($usertypeID, $this->input->post('classesID'));
                } else {
                    $this->data['checkOutToUesrs'] = $this->allUsersArray($usertypeID);
                }

                if($_POST) {

                    if($this->input->post('usertypeID') == 3) {
                        $this->data['showClass'] = TRUE;
                        $this->data['sendClasses'] = $this->classes_m->get_classes();
                        $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'), $this->input->post('classesID'));
                    } else {
                        $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'));
                    }

                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/asset_requisition/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "assetID" => $this->input->post("assetID"),
                            "assigned_quantity" => $this->input->post("assigned_quantity"),
                            "usertypeID" => $this->input->post("usertypeID"),
                            "check_out_to" => $this->input->post("check_out_to"),
                            "asset_locationID" => $this->input->post("asset_locationID"),
                            "note" => $this->input->post("note"),
                            "status" => $this->input->post("status")
                        );
                        if ($this->input->post("check_out_to")=='select') {
                            $array["check_out_to"] = 0;
                        }
                        if($this->input->post('check_out_date')) {
                            $array["check_out_date"] = date("Y-m-d", strtotime($this->input->post("check_out_date")));
                        } else {
                            $array["check_out_date"] = Null;
                        }

                        if($this->input->post('due_date')) {
                            $array["due_date"] 		= date("Y-m-d", strtotime($this->input->post("due_date")));
                        } else {
                            $array["due_date"] = Null;
                        }
                        if($this->input->post('check_in_date')) {
                            $array["check_in_date"] = date("Y-m-d", strtotime($this->input->post("check_in_date")));
                        } else {
                            $array["check_in_date"] = Null;
                        }

                        $array["modify_date"] = date("Y-m-d");


                        $this->asset_requisition_m->update_asset_requisition($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("asset_requisition/index"));
                    }
                } else {
                    $this->data["subview"] = "/asset_requisition/edit";
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

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_requisition'] = $this->asset_requisition_m->get_single_asset_requisition_with_usertypeID(array('asset_requisitionID' => $id));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

            if(customCompute($this->data['asset_requisition'])) {
                $usertypeID = $this->data['asset_requisition']->usertypeID;
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

                $this->data['createuser'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->create_usertypeID, $this->data['asset_requisition']->create_userID);
                $this->data['fromuser'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->usertypeID, $this->data['asset_requisition']->check_out_to);
                $this->data['approve_one'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->firstAprove_usertypeID, $this->data['asset_requisition']->firstAprove_userID);
                $this->data['approve_two'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->secondAprove_usertypeID, $this->data['asset_requisition']->secondAprove_userID);

                if($usertypeID == 3) {
                    $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_requisition']->check_out_to));

                    if(customCompute($student)) {
                        $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                    } else {
                        $this->data['user'] = array();
                    }
                } else {
                    $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                }

                $this->data["subview"] = "/asset_requisition/view";
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


    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_requisition'] = $this->asset_requisition_m->get_single_asset_requisition_with_usertypeID(array('asset_requisitionID' => $id));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

            if(customCompute($this->data['asset_requisition'])) {
                $usertypeID = $this->data['asset_requisition']->usertypeID; 
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

                $this->data['createuser'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->create_usertypeID, $this->data['asset_requisition']->create_userID);
                $this->data['fromuser'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->usertypeID, $this->data['asset_requisition']->check_out_to);
                $this->data['approve_one'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->firstAprove_usertypeID, $this->data['asset_requisition']->firstAprove_userID);
                $this->data['approve_two'] = getObjectByUserTypeIDAndUserID($this->data['asset_requisition']->secondAprove_usertypeID, $this->data['asset_requisition']->secondAprove_userID);

                if($usertypeID == 3) {
                    $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_requisition']->check_out_to));

                    if(customCompute($student)) {
                        $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                    } else {
                        $this->data['user'] = array();
                    }
                } else {
                    $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                }

                $this->reportPDF('assetassignmentmodule.css',$this->data, 'asset_requisition/print_preview');
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_mail() {
        $retArray['status']  = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('asset_requisition_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $email = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');
                    $asset_requisitionID = $this->input->post('asset_requisitionID');

                    if((int)$asset_requisitionID) {
                        $this->data['asset_requisition'] = $this->asset_requisition_m->get_single_asset_requisition_with_usertypeID(array('asset_requisitionID' => $asset_requisitionID));
                        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

                        if(customCompute($this->data['asset_requisition'])) {
                            $usertypeID = $this->data['asset_requisition']->usertypeID;

                            if($usertypeID == 3) {
                                $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_requisition']->check_out_to));
                                if(customCompute($student)) {
                                    $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                                } else {
                                    $this->data['user'] = array();
                                }
                            } else {
                                $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                            }

                            $this->reportSendToMail('assetassignmentmodule.css', $this->data, 'asset_requisition/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('asset_requisition_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('asset_requisition_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('asset_requisition_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('asset_requisition_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_requisition'] = $this->asset_requisition_m->get_single_asset_requisition(array('asset_requisitionID' => $id));
            if($this->data['asset_requisition']) {
                $this->asset_requisition_m->delete_asset_requisition($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_requisition/index"));
            } else {
                redirect(base_url("asset_requisition/index"));
            }
        } else {
            redirect(base_url("asset_requisition/index"));
        }
    }

    public function unique_assetID() {
        if($this->input->post('assetID') == 0) {
            $this->form_validation->set_message('unique_assetID', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function valid_quantity($quantity) {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $assigned_asset = array();
        
        if((int)$id) {
            $assigned_asset = $this->asset_requisition_m->get_asset_requisition($id);
        }

        $assetID = $this->input->post('assetID');

        if($quantity && $assetID) {
            $total_quantity = 0;
            $assigned_quantity = 0;
            $balance_quantity = 0;
            // echo $quantity;
            // echo '<br>';
            // echo $assetID;
            // echo '<br>';
            $asset_purchases = $this->purchase_m->get_order_by_purchase(array('assetID' => $assetID));
            // var_dump($asset_purchases);
            // exit();
            $asset_assigned = $this->asset_requisition_m->get_order_by_asset_requisition(array('assetID' => $assetID, 'status' => 1));
            $single_asset = $this->asset_m->get_single_asset(['assetID' => $this->input->post("assetID")]);
            $asset_quantity = $single_asset->quantity;

            if(customCompute($asset_purchases) || $asset_quantity !== 0) {
                foreach ($asset_purchases as $key => $purchase) {
                    $total_quantity += $purchase->quantity;
                   
                }


                if (customCompute($asset_assigned)) {
                    foreach ($asset_assigned as $key => $assigned) {
                        $assigned_quantity += $assigned->assigned_quantity;
                    }
                    $balance_quantity = $total_quantity-$assigned_quantity;

                } else {
                    $balance_quantity = $total_quantity;
                }


                if (customCompute($assigned_asset)) {
                    if($assigned_asset->assetID == $assetID) {
                        $balance_quantity += $assigned_asset->assigned_quantity;
                    }
                }

                if ($quantity <= $balance_quantity || $asset_quantity != 0) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("valid_quantity", "The item is not available this quantity ");
                    return FALSE;
                }

            } else {
                $balance_quantity = $total_quantity;
                $this->form_validation->set_message("valid_quantity", "Please first purchase it.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function quantity_number() {
        $assetID = $this->input->post('assetID');
        $quantity = 0;
        $assigned_quantity = 0;
        if( (int)$assetID) {
            $asset_purchases = $this->purchase_m->get_order_by_purchase(array('assetID' => $assetID));
            $asset_assigned = $this->asset_requisition_m->get_order_by_asset_requisition(array('assetID' => $assetID, 'status' => 0));
            if(customCompute($asset_purchases)) {
                foreach ($asset_purchases as $key => $purchase) {
                    $quantity += $purchase->quantity;
                }
                if (customCompute($asset_assigned)) {
                    foreach ($asset_assigned as $key => $assigned) {
                        $assigned_quantity += $assigned->assigned_quantity;
                    }
                    echo $quantity-$assigned_quantity;
                } else {
                    echo $quantity;
                }
            } else {
                echo $quantity;
            }
        } else {
            echo $quantity;
        }
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

    public function unique_status() {
        if($this->input->post('status') == 0) {
            $this->form_validation->set_message('unique_status', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }


    function allusers() {
        if($this->input->post('usertypeID') == 0) {
            echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
        } else {
            $usertypeID = $this->input->post('usertypeID');

            if($usertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(customCompute($systemadmins)) {
                    echo "<option value=''>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($systemadmins as $key => $systemadmin) {
                        echo "<option value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
                    }
                } else {
                    echo '<option value="">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(customCompute($teachers)) {
                    echo "<option value=''>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($teachers as $key => $teacher) {
                        echo "<option value='".$teacher->teacherID."'>".$teacher->name.'</option>';
                    }
                } else {
                    echo '<option value="">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 3) {
                $classes = $this->classes_m->get_classes();
                if(customCompute($classes)) {
                    echo "<option value=''>".$this->lang->line('asset_requisition_select_class')."</option>";
                    foreach ($classes as $key => $classm) {
                        echo "<option value='".$classm->classesID."'>".$classm->classes.'</option>';
                    }
                } else {
                    echo '<option value="">'.$this->lang->line('asset_requisition_select_class').'</option>';
                }
            } elseif($usertypeID == 4) {
                $parents = $this->parents_m->get_parents();
                if(customCompute($parents)) {
                    echo "<option value=''>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($parents as $key => $parent) {
                        echo "<option value='".$parent->parentsID."'>".$parent->name.'</option>';
                    }
                } else {
                    echo '<option value="">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(customCompute($users)) {
                    echo "<option value=''>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($users as $key => $user) {
                        echo "<option value='".$user->userID."'>".$user->name.'</option>';
                    }
                } else {
                    echo '<option value="">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            }
        }
    }
    function allusers_by_usertypeID() {
        if($this->input->post('usertypeID') == 0) {
            echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
        } else {
            $usertypeID = $this->input->post('usertypeID');

            if($usertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(customCompute($systemadmins)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($systemadmins as $key => $systemadmin) {
                        echo "<option value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(customCompute($teachers)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($teachers as $key => $teacher) {
                        echo "<option value='".$teacher->teacherID."'>".$teacher->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 3) {
                $students = $this->studentrelation_m->get_order_by_student(array( 'active' =>[0,1]));

            if(customCompute($students)) {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                foreach ($students as   $student) {
                    echo '<option value="'.$student->srstudentID.'">'.$student->srname.' '.$student->srroll.' '.$student->srregisterNO.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
            }
            } elseif($usertypeID == 4) {
                $parents = $this->parents_m->get_parents();
                if(customCompute($parents)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($parents as $key => $parent) {
                        echo "<option value='".$parent->parentsID."'>".$parent->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(customCompute($users)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($users as $key => $user) {
                        echo "<option value='".$user->userID."'>".$user->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            }
        }
    }
     function allusers_get() {
        if($this->input->post('usertypeID') == 0) {
            echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
        } else {
            $usertypeID = $this->input->post('usertypeID');
            $selectedid = $this->input->post('selectedid');

            if($usertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(customCompute($systemadmins)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                    foreach ($systemadmins as $key => $systemadmin) {

                        if ($selectedid==$systemadmin->systemadminID) {
                           $selected    =   'selected';
                        }

                        echo "<option $selected  value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(customCompute($teachers)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                     
                    foreach ($teachers as $key => $teacher) {
                        if ($selectedid==$teacher->teacherID) {
                           $selected    =   'selected';
                        }else{
                            $selected    =   '';
                        }
                        echo "<option $selected  value='".$teacher->teacherID."'>".$teacher->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } elseif($usertypeID == 3) {
                $classes = $this->classes_m->get_classes();
                if(customCompute($classes)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_class')."</option>";
                   
                    foreach ($classes as $key => $classm) {
                         if ($selectedid==$classm->classesID) {
                           $selected    =   'selected';
                        }else{
                            $selected    =   '';
                        }
                        echo "<option $selected  value='".$classm->classesID."'>".$classm->classes.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_class').'</option>';
                }
            } elseif($usertypeID == 4) {
                $parents = $this->parents_m->get_parents();
                if(customCompute($parents)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                   
                    foreach ($parents as $key => $parent) {
                         if ($selectedid==$parent->parentsID) {
                           $selected    =   'selected';
                        }else{
                            $selected    =   '';
                        }
                        echo "<option $selected  value='".$parent->parentsID."'>".$parent->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(customCompute($users)) {
                    echo "<option value='0'>".$this->lang->line('asset_requisition_select_user')."</option>";
                   
                    foreach ($users as $key => $user) {
                         if ($selectedid==$user->userID) {
                           $selected    =   'selected';
                        }else{
                            $selected    =   '';
                        }
                        echo "<option $selected  value='".$user->userID."'>".$user->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                }
            }
        }
    }

    function allstudent() {
        $schoolyearID = $this->data['siteinfos']->school_year;
        $classesID = $this->input->post('classesID');
        if((int)$schoolyearID && (int)$classesID) {
            $students = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID));

            if(customCompute($students)) {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                foreach ($students as $key => $student) {
                    echo '<option value="'.$student->studentID.'">'.$student->name.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
            }
        } else {
            echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
        }
    }

    

    function allstudent_get() {
        $schoolyearID = $this->data['siteinfos']->school_year;
        $classesID = $this->input->post('classesID');
        $studentID = $this->input->post('studentID');
        if((int)$schoolyearID && (int)$classesID) {
            $students = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID));

            if(customCompute($students)) {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
                foreach ($students as $key => $student) {
                     if ($studentID==$student->studentID) {
                           $selected    =   'selected';
                        }else{
                            $selected    =   '';
                        }
                    echo '<option '.$selected.' value="'.$student->studentID.'">'.$student->name.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
            }
        } else {
            echo '<option value="0">'.$this->lang->line('asset_requisition_select_user').'</option>';
        }
    }

    public function allUsersArray($usertypeID, $classesID = 0) {
        $returnArray[0] = $this->lang->line('asset_requisition_select_user');
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
            $students = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
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

    public function allUsersArrayObject($usertypeID, $classesID = 0) { 
        $returnArray = [];
        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if(customCompute($systemadmins)) {
                foreach ($systemadmins as $key => $systemadmin) {
                    $returnArray[$systemadmin->systemadminID] = $systemadmin;
                }
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if(customCompute($teachers)) {
                foreach ($teachers as $key => $teacher) {
                    $returnArray[$teacher->teacherID] = $teacher;
                }
            }
        } elseif($usertypeID == 3) {
            $students = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
            if(customCompute($students)) {
                foreach ($students as $key => $student) {
                    $returnArray[$student->studentID] = $student;
                }
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if(customCompute($parents)) {
                foreach ($parents as $key => $parent) {
                    $returnArray[$parent->parentsID] = $parent;
                }
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if(customCompute($users)) {
                foreach ($users as $key => $user) {
                    $returnArray[$user->userID] = $user;
                }
            }
        }
        return $returnArray;
    }

}
