<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjectenrollment extends Admin_Controller {
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
        $this->load->model("subjectenrollment_m");
        $this->load->model("studentsubjects_m");
        $this->load->model("studentrelation_m");
        $this->load->model("subject_m");
        $this->load->model("parents_m");
        $this->load->model("invoice_m");
        $this->load->model("section_m");
        $this->load->model("classes_m");
        error_reporting(0);
        $language = $this->session->userdata('lang');
        $this->lang->load('subjectenrollment', $language);
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
        $usertypeID     =    $this->session->userdata('usertypeID');
        if ($usertypeID==3) {
            $student = $this->studentrelation_m->get_single_student([
                'srstudentID'    => $this->session->userdata('loginuserID'),
                'srschoolyearID' => $this->session->userdata('defaultschoolyearID')
            ]);
            $array  = array(
                'subjectenrollment.studentID' => $this->session->userdata('loginuserID'),
                'subjectenrollment.classesID' => $student->srclassesID,
                'subjectenrollment.sectionID' => $student->srsectionID,
                 );


            $this->data['section']      = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));
            $this->data['invoice']      = $this->invoice_m->get_order_by_invoice(
                                                                                array(
                                                                                    'studentID' => $this->session->userdata('loginuserID'),
                                                                                    'classesID' => $student->srclassesID,
                                                                                    'sectionID' => $student->srsectionID,
                                                                                    'paidstatus' => 2,
                                                                                 )
                                                                                );
        }else{
            $array  = NULL;


            $this->data["active"]               = 1;
            $this->data["studentID"]            = 0;
            $this->data["classesID"]            = 0;
            $this->data["sectionID"]            = 0;
            $this->data["name"]                 = "";
            $this->data["email"]                = "";
            $this->data["phone"]                = "";
            $this->data["address"]              = "";
            $this->data["registerNO"]           = "";
            $this->data["roll"]                 = "";
            $this->data["username"]             = "";
            $this->data["InstallmentsID"]       = "";  
            $this->data["allstudents"]          = [];
 


            if ($_GET) {
                $array = [];
                $active = $_GET["active"];
                if ($active != '') {
                    $array["active"]        = $active;
                    $this->data["active"]   = $active;
                     
                }

                $classesID = $_GET["classesID"];
                if ($classesID != 0) {

                    $array["subjectenrollment.classesID"]         = $classesID;
                    $this->data["classesID"]    = $classesID;
                    $this->data["allsection"] = $this->section_m->get_order_by_section(["classesID" => $classesID,]);
                }
                $sectionID      = $_GET["sectionID"];

                if ($sectionID != 0) {
                    $array["subjectenrollment.sectionID"]         = $sectionID;
                    $this->data["sectionID"]    = $sectionID;

                    $studentArrays = ["srclassesID" => $classesID];
                    if ((int) $sectionID) {
                        $studentArrays["srsectionID"]   = $sectionID;
                    }
                    $this->data["allstudents"]          = $this->studentrelation_m->get_order_by_student($studentArrays);
                }


                $studentID      =   $_GET["studentID"];

                if ($studentID != 0) {
                    $array["subjectenrollment.studentID"] = $studentID;
                    $this->data["studentID"] = $studentID;
                }

                $name   =   $_GET["name"];

                if ($name != "") {
                    $array["name LIKE"]     = "%$name%";
                    $this->data["name"]     = $name;
                } 
                
                $email  =   $_GET["email"];

                if ($email != "") {
                    $array["email LIKE"]    = "%$email%";
                    $this->data["email"]    = $email;
                } 
                
                $phone  =   $_GET["phone"];

                if ($phone != "") {
                    $array["phone LIKE"]    = "%$phone%";
                    $this->data["phone"]    = $phone;
                }
                
                $phone  =   $_GET["address"];

                if ($phone != "") {
                    $array["address LIKE"]  = "%$address%";
                    $this->data["address"]  = $address;
                }
                
                $registerNO     =   $_GET["registerNO"];

                if ($registerNO != "") {
                    $array["registerNO"]        = $registerNO;
                    $this->data["registerNO"]   = $registerNO;
                } 
                
                $roll   =   $_GET["roll"];

                if ($roll != "") {
                    $array["roll"]          = $roll;
                    $this->data["roll"]     = $roll;
                } 
                
                $username   =   $_GET["username"];

                if ($username != "") {
                    $array["username LIKE"]     = "%$username%";
                    $this->data["username"]     = $username;
                }
                
                $InstallmentsID     =   $_GET["InstallmentsID"];

                if ($InstallmentsID != "") {
                    $array["no_installment"]        = $InstallmentsID;
                    $this->data["InstallmentsID"]   = $InstallmentsID;
                } 
                 

                 
            }else{

                $array = NULL;
            }
        }

        $section = $this->section_m->get_section();
        $section = pluck($section, "obj", "sectionID");
        $this->data["section_pluck"] = $section;


        $classess = $this->classes_m->get_classes();
        $classes = pluck($classess, "obj", "classesID");
        $this->data["classes_pluck"] = $classes;
        $this->data["classes"] = $this->classes_m->general_get_classes();

        if(isset($_GET['enrollment']) && $_GET['enrollment'] == 'not_enrollment'){
            $this->data['subjectenrollments'] = $this->subjectenrollment_m->get_subjectnotenrollment_join_student_by_array($array);
        }else{
            $this->data['subjectenrollments'] = $this->subjectenrollment_m->get_subjectenrollment_join_student_by_array($array);
        }

       // $this->data['subjectenrollments'] = $this->subjectenrollment_m->get_subjectenrollment_join_student_by_array($array);
       
        
        
        $this->data['parents'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');

        // echo "<pre>";
        // print_r($this->data['subjectenrollments']);
        // die();
        $this->data["subview"] = "/subjectenrollment/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("subjectenrollment_title"),
                'rules' => 'trim|xss_clean|max_length[128]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                // 'assets/datepicker/datepicker.css',
                // 'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                // 'assets/editor/jquery-te-1.4.0.min.js',
                // 'assets/datepicker/datepicker.js'
            )
        );

        $usertypeID     =    $this->session->userdata('usertypeID');
        if ($usertypeID==3) {
            $student = $this->studentrelation_m->get_single_student([
                'srstudentID'    => $this->session->userdata('loginuserID'),
                'srschoolyearID' => $this->session->userdata('defaultschoolyearID')
            ]);
            $array  = array(
                'subjectenrollment.studentID' => $this->session->userdata('loginuserID'),
                'subjectenrollment.classesID' => $student->srclassesID,
                'subjectenrollment.sectionID' => $student->srsectionID,
                 );
        }else{
            $array  = NULL;
        }

        $thisubjectenrollments = $this->subjectenrollment_m->get_subjectenrollment_join_student_by_array($array);

        if(count($thisubjectenrollments)) {
            $this->session->set_flashdata('error', "Already Enrolled");
            redirect(base_url("subjectenrollment/index"));
        }else{
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "/subjectenrollment/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                     
                    $array  = array(
                        'studentID' => $this->session->userdata('loginuserID'),
                        'classesID' => $student->srclassesID,
                        'sectionID' => $student->srsectionID,
                    );
                   $id =  $this->subjectenrollment_m->insert_subjectenrollment($array);
                   
                   $allsubjects = $this->subject_m->general_get_order_by_subject(array("classesID" =>$student->srclassesID, 'sectionID' => $student->srsectionID));
                        foreach ($allsubjects as $s) {
                            $arrays  = array( 
                            'classesID'         => $student->srclassesID,
                            'sectionID'         => $student->srsectionID,
                            'subjectID'         => $s->subjectID,
                            'subjectenrollmentID' => $id,
                            );
                            $this->studentsubjects_m->insert_studentsubjects($arrays);
                        }
                    
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("subjectenrollment/index"));
                }
            } else {
                $this->data["allsubjects"] = $this->subject_m->general_get_order_by_subject(array("classesID" =>$student->srclassesID, 'sectionID' => $student->srsectionID));
                $this->data["subview"] = "/subjectenrollment/add";
                $this->load->view('_layout_main', $this->data);
            }
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['subjectenrollment'] = $this->subjectenrollment_m->get_single_subjectenrollment(array('subjectenrollmentID' => $id));
            if($this->data['subjectenrollment']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/subjectenrollment/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->subjectenrollment_m->update_subjectenrollment($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("subjectenrollment/index"));
                    }
                } else {
                    $this->data["subview"] = "/subjectenrollment/edit";
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
            $this->data['subjectenrollment'] = $this->subjectenrollment_m->get_single_subjectenrollment(array('subjectenrollmentID' => $id));
            $this->data['enrolledsubjects'] = $this->studentsubjects_m->get_subject_join_enrollment_by_array(array('subjectenrollmentID' => $id));
            $usertypeID     =    $this->session->userdata('usertypeID');
        
            $array  = array(
                'subjectenrollment.studentID' => $this->data['subjectenrollment']->studentID,
                'subjectenrollment.classesID' => $this->data['subjectenrollment']->classesID,
                'subjectenrollment.sectionID' => $this->data['subjectenrollment']->sectionID,
                 );
            
           
            $subjectenrollments = $this->subjectenrollment_m->get_subjectenrollment_join_student_by_array($array);
           
            $this->data['enroll_student'] = $subjectenrollments[0];
             $this->data['student']  = $this->studentrelation_m->get_single_student([
                'srstudentID'    => $this->data['enroll_student']->studentID,
                'srschoolyearID' => $this->data['enroll_student']->schoolyearID
            ]);

            $this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $subjectenrollments[0]->parentID));
            if($this->data['subjectenrollment']) {
                $this->data["subview"] = "/subjectenrollment/view";
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
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['subjectenrollment'] = $this->subjectenrollment_m->get_single_subjectenrollment(array('subjectenrollmentID' => $id));
            if(count($this->data['subjectenrollment'])) {
                $this->subjectenrollment_m->delete_subjectenrollment($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("subjectenrollment/index"));
            } else {
                redirect(base_url("subjectenrollment/index"));
            }
        } else {
            redirect(base_url("subjectenrollment/index"));
        }
    }

    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));

        if((int)$id) { 
            $this->data['subjectenrollment'] = $this->subjectenrollment_m->get_single_subjectenrollment(array('subjectenrollmentID' => $id));
            $this->data['enrolledsubjects'] = $this->studentsubjects_m->get_subject_join_enrollment_by_array(array('subjectenrollmentID' => $id));
            $usertypeID     =    $this->session->userdata('usertypeID');
        
            $array  = array(
                'subjectenrollment.studentID' => $this->data['subjectenrollment']->studentID,
                'subjectenrollment.classesID' => $this->data['subjectenrollment']->classesID,
                'subjectenrollment.sectionID' => $this->data['subjectenrollment']->sectionID,
                 );
            
           
            $subjectenrollments = $this->subjectenrollment_m->get_subjectenrollment_join_student_by_array($array);
           
            $this->data['enroll_student'] = $subjectenrollments[0];
             $this->data['student']  = $this->studentrelation_m->get_single_student([
                'srstudentID'    => $this->data['enroll_student']->studentID,
                'srschoolyearID' => $this->data['enroll_student']->schoolyearID
            ]);

            $this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $subjectenrollments[0]->parentID));

            if($this->data['subjectenrollment']) {

                $this->data['panel_title'] = $this->lang->line('panel_title');
                //$this->reportPDF($this->data, '/subjectenrollment/print_preview');
                $this->reportPDF('requisitionmodule.css', $this->data, '/subjectenrollment/print_preview');
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
        $id = $this->input->post('id');
        if ((int)$id) {
            $this->data['subjectenrollment'] = $this->subjectenrollment_m->get_single_subjectenrollment(array('subjectenrollmentID' => $id));
            if($this->data['subjectenrollment']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->reportSendToMail($this->data['subjectenrollment'], '/subjectenrollment/print_preview', $email, $subject, $message);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }

    }
}
