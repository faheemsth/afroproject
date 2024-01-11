<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resultmanagement extends Admin_Controller {
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
        $this->load->model("resultmanagement_m");
        $this->load->model("student_m");
        $this->load->model("subject_m");
        $this->load->model("section_m");
        $this->load->model("classes_m");
        $this->load->model("studentrelation_m");
        $this->load->model("grade_m");
        $this->load->library('csvimport');
        $language = $this->session->userdata('lang');
        $this->lang->load('certificatereport', $language);
        $this->lang->load('tabulationsheetreport', $language);
        $this->lang->load('resultmanagement', $language);
    }

    public function index() {

         $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            )
        );
        
         $log_type  =   $this->session->userdata('usertypeID');
        if ($log_type==3) {
             $student = $this->studentrelation_m->get_single_student(
                    [
                    'srstudentID'      => $this->session->userdata('loginuserID'),
                    'srschoolyearID'   => $this->session->userdata('defaultschoolyearID')
                    ]
                );

            $this->data['classes']     = $this->classes_m->get_order_by_classes(array('classesID'=>$student->srclassesID));
            $this->data['sections']    = $this->section_m->get_order_by_section(array('sectionID'=>$student->srsectionID));
            $this->data['students']    = $this->student_m->get_order_by_student(array('studentID'=>$student->srstudentID));
            $this->data['log_type']    = $log_type;
              
            
         }else{
            $this->data['classes']     = $this->classes_m->get_classes();
            $this->data['sections']    = array();
            $this->data['students']    = array();
            $this->data['exams']       = array();
            $this->data['log_type']    = $log_type;
        }
        $this->data['resultmanagements']    = array();
        $this->data["subview"]              = "/resultmanagement/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
           
            array(
                'field' => 'file',
                'label' => 'CSV File Required',
                'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
            )
        );
        return $rules;
    }

    public function unique_document_upload() {
        $new_file = '';
        if($_FILES["file"]['name'] !="") {
            $file_name = $_FILES["file"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(customCompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/documents";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '5120';
                $config['max_width'] = '10000';
                $config['max_height'] = '10000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("file")) {
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


    protected function get_student_rules() {
        $rules = array(
            array(
                'field' => 'classesID',
                'label' => $this->lang->line('certificatereport_classname'),
                'rules' => 'trim|required|xss_clean|callback_unique_data'
            ),
            array(
                'field' => 'sectionID',
                'label' => $this->lang->line('certificatereport_sectionname'),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'studentID',
                'label' => $this->lang->line('certificatereport_studentname'),
                'rules' => 'trim|required|xss_clean'
            )
        );

        return $rules;
    }


    public function unique_data($data) {
        if($data === "0") {
            $this->form_validation->set_message('unique_data', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }



    public function getStudentList() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';

        if(permissionChecker('resultmanagement')) {
            if($_POST) {
                $rules = $this->get_student_rules();
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {   

           
                    $classID        = $this->input->post('classesID');
                    $sectionID      = $this->input->post('sectionID');
                    $templateID     = $this->input->post('templateID');
                    $studentID      = $this->input->post('studentID');
                    $schoolyearID   = $this->session->userdata('defaultschoolyearID');
                    
                    $sections       = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
                    $classes        = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');

                    $studentArray   = [];
                    $studentArray['srschoolyearID'] = $schoolyearID;
                    if($classID > 0) {
                        $studentArray['srclassesID'] = $classID;
                    } 
                    
                    if($sectionID > 0) {
                        $studentArray['srsectionID'] = $sectionID;
                    }
                    
                    if($studentID > 0) {
                        $studentArray['srstudentID'] = $studentID;
                    }

                    $students = $this->studentrelation_m->general_get_order_by_student($studentArray);
                    $this->data['students']     = $students;
                    $this->data['classes']      = $classes;
                    $this->data['sections']     = $sections;
                    $this->data['classesID']    = $classID;
                    $this->data['sectionID']    = $sectionID;
                    $this->data['templateID']   = $templateID;
                    $retArray['render'] = $this->load->view('resultmanagement/getStudentList', $this->data, true);
                    $retArray['status'] = TRUE;
                    echo json_encode($retArray);
                    exit;
                }
            }
        } else {
            echo json_encode($retArray);
            exit;
        }
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
       
        if($_POST) {

           
           
             
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
               
                $this->data["subview"] = "/resultmanagement/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                  
                //start


        if(isset($_FILES["file"])) {
           
            $config['upload_path']   = "./uploads/result/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '5048';
            $config['file_name']     = $_FILES["file"]['name'];
            $config['overwrite']     = TRUE; 
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("file")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/add"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/documents/'.$file_data['file_name'];

                 
                $column_headers = array(
                                        "Registration No.",
                                        "Attempt",
                                        "Internal (20%)",
                                        "Mid Term",
                                        "Final Term Marks(80%)",
                                        "Practical Work (20)",
                                        "Total Obtain Marks", 
                                        "Total mark",
                                        "Course Code",
                                        "Semester",
                                        "Session"  
                                        );
                
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i                  =   1;
                        $student_not_found  =   0;
                        $subject_not_found  =   0;
                        $result_found       =   0;
                        $notvalid_attempt   =   0;
                        $stu   =   0;
                        $sub   =   0;
                        $msg                =   "";
                        $csv_col            =   [];
                        $insert_data_ar     =   [];
                        $all_students       =   $this->student_m->get_student();
                        $all_subjects       =   $this->subject_m->get_subject();
                        $all_sections       =   $this->section_m->get_section();
                        $all_result         =   $this->resultmanagement_m->get_resultmanagement();

                         

                        $student_array      =   [];

                        foreach ($all_students as $st) {
                        $student_array[$st->registerNO]      =   $st;
                            
                        }

                         


                        $subject_array      =   [];

                        foreach ($all_subjects as $sb) {
                        $subject_array[$sb->subject_code]      =   $sb;
                            
                        }


                        $section_array      =   [];

                        foreach ($all_sections as $sc) {
                        $section_array[$sc->classesID][$sc->numric_code]      =   $sc->sectionID;
                            
                        }

                        
                        $result_array      =   [];

                        foreach ($all_result as $rm) {
                        $result_array[$rm->classesID][$rm->sectionID][$rm->studentID][$rm->subjectID][$rm->attempt]      =   TRUE;
                            
                        }
                        $total_recincsv     =   count($csv_array);

                        error_reporting(0);
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);


                                 
                                
                                if(array_key_exists($row['Registration No.'],$student_array)) {
                                    

                                    if(array_key_exists($row['Course Code'],$subject_array)) {

                                        if ($row['Attempt']!='' && $row['Semester']!='') {

                                            $stu_detail         =   $student_array[$row['Registration No.']];
                                            $sub_detail         =   $subject_array[$row['Course Code']];     
                                            $sectionID          =   $section_array
                                                                                    [$stu_detail->classesID]
                                                                                    [$row['Semester']];
                                            if (count($result_array)) {
                                                # code...
                                            if ($sectionID ) {
                                               $checkresultkey     =   $result_array[$stu_detail->classesID]
                                                                                [$sectionID]
                                                                                [$stu_detail->studentID]
                                                                                [$sub_detail->subjectID]
                                                                                [$row['Attempt']] ;

                                            }else{
                                                
                                                $stu++;
                                            }

                                             
                                           
                                                                        }else{

                                              $checkresultkey     =     FALSE;                               
                                                                        }
                                            

                                                
                                                if($checkresultkey) {

                                                    

                                                  
                                                    $msg .= $i.". Resutl already present: Subject Code  ". $row['Course Code'].", Student Reg.  ". $row['Registration No.'];
                                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                                    $msg .= "! <br/>";
                                                    $result_found++;

                                              
                                                } else {

                                                    
                                                     $insert_data_ar[] = array(
                                                        'classesID'         => $stu_detail->classesID,
                                                        'sectionID'         => $sectionID,
                                                        'studentID'         => $stu_detail->studentID,
                                                        'subjectID'         => $sub_detail->subjectID,
                                                        'total_mark'        => $row['Total mark'],
                                                        'obtain_mark'       => $row['Total Obtain Marks'], 
                                                        'attempt'           => $row['Attempt'],
                                                        'internal_mark'     => $row['Internal (20%)'],
                                                        'midterm_mark'      => $row['Mid Term'],
                                                        'finalterm_mark'    => $row['Final Term Marks(80%)'],
                                                        'practical_mark'    => $row['Practical Work (20)'],
                                                        'created'           => date('Y-m-d H:i:s'), 
                                                    );
                                                 
                                                      
                                                }


                                       }else {
                                            $msg .= $i.". Attempt field is not valid:  ". $row['Attempt']." !";
                                            //$msg .= implode(' , ', $singlebookCheck['error']);
                                            $msg .= " <br/>";
                                            $notvalid_attempt++;
                                        }
                                } else {
                                    $msg .= $i.". Subject Code  ". $row['Course Code.']." is not found!";
                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= " <br/>";
                                    $subject_not_found++;
                                }

                              
                                } else {
                                    $msg .= $i.". Student Reg.  ". $row['Registration No.']." is not found!";
                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= " <br/>";
                                    $student_not_found++;
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                $this->data["subview"] = "/resultmanagement/add";
                                $this->load->view('_layout_main', $this->data);
                            }
                            $i++;
                        }
                        
                        if (count($insert_data_ar)) {
                           
                        $this->resultmanagement_m->insert_batch_resultmanagement($insert_data_ar);
                         }     
                        if($msg != "") {
                            
                            if ($student_not_found>0) {
                            $msg .= "Total Student Not Found ".$student_not_found ;
                                     
                                    $msg .= ". <br/>";
                                }
                            if ($subject_not_found>0) {
                                 
                            
                            $msg .= "Total Subject Not Found ".$subject_not_found ;
                                     
                            $msg .= ". <br/>";
                                    }
                            if ($result_found>0) {
                                 
                            
                            $msg .= "Total Result Already Present ".$result_found ;
                                     
                            $msg .= ". <br/>";
                                    }
                            if ($notvalid_attempt>0) {
                                 
                            
                            $msg .= "Total Attempt Not Valid ".$notvalid_attempt ;
                                     
                            $msg .= ". <br/>";
                                    }


                       
                            if ($total_recincsv > 0) {
                                 
                            
                            $msg .= "Total Record In CSV ".$total_recincsv ;
                                     
                            $msg .= ". <br/>";
                                    }

                       // $this->session->set_flashdata('msg', $msg);

                         $this->data['msg'] = $msg;
                                     
                        }
                         
                         $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                         $this->data["subview"] = "/resultmanagement/add";
                         $this->load->view('_layout_main', $this->data);
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        $this->data["subview"] = "/resultmanagement/add";
                        $this->load->view('_layout_main', $this->data);
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file! column not match");
                    $this->data["subview"] = "/resultmanagement/add";
                    $this->load->view('_layout_main', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            $this->data["subview"] = "/resultmanagement/add";
            $this->load->view('_layout_main', $this->data);
        }
    

                // end



            }
        } else {
            $this->data["subview"] = "/resultmanagement/add";
            $this->load->view('_layout_main', $this->data);
        }
    }



    public function update() {


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
       
        if($_POST) {

           
           
             
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
               
                $this->data["subview"] = "/resultmanagement/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                  
                //start


        if(isset($_FILES["file"])) {
           
            $config['upload_path']   = "./uploads/result/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["file"]['name'];
            $config['overwrite']     = TRUE; 
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("file")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/add"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/documents/'.$file_data['file_name'];

                 
                $column_headers = array(
                                        "Registration No.",
                                        "Attempt",
                                        "Internal (20%)",
                                        "Mid Term",
                                        "Final Term Marks(80%)",
                                        "Practical Work (20)",
                                        "Total Obtain Marks", 
                                        "Total mark",
                                        "Course Code",
                                        "Semester",
                                        "Session"  
                                        );
                
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i                  =   1;
                        $student_not_found  =   0;
                        $subject_not_found  =   0;
                        $result_found       =   0;
                        $notvalid_attempt   =   0;
                        $msg                =   "";
                        $csv_col            =   [];
                        $insert_data_ar     =   [];
                        $all_students       =   $this->student_m->get_student();
                        $all_subjects       =   $this->subject_m->get_subject();
                        $all_sections       =   $this->section_m->get_section();
                        $all_result         =   $this->resultmanagement_m->get_resultmanagement();

                         

                        $student_array      =   [];

                        foreach ($all_students as $st) {
                        $student_array[$st->registerNO]      =   $st;
                            
                        }

                         


                        $subject_array      =   [];

                        foreach ($all_subjects as $sb) {
                        $subject_array[$sb->subject_code]      =   $sb;
                            
                        }


                        $section_array      =   [];

                        foreach ($all_sections as $sc) {
                        $section_array[$sc->classesID][$sc->numric_code]      =   $sc->sectionID;
                            
                        }

                        
                        $result_array      =   [];

                        foreach ($all_result as $rm) {
                        $result_array[$rm->classesID][$rm->sectionID][$rm->studentID][$rm->subjectID]      =  $rm;
                            
                        }
                       

                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);


                                 
                                
                                if(array_key_exists($row['Registration No.'],$student_array)) {
                                    

                                    if(array_key_exists($row['Course Code'],$subject_array)) {

                                    $stu_detail         =   $student_array[$row['Registration No.']];
                                    $sub_detail         =   $subject_array[$row['Course Code']];     
                                    $sectionID          =   $section_array[$stu_detail->classesID][$row['Semester']];
 
                                if ($row['Attempt']!='1st' and $row['Attempt']!='') {

                                     if (count($result_array)) {
                                    
                                            $checkresultkey     =   $result_array[$stu_detail->classesID]
                                                                                [$sectionID]
                                                                                [$stu_detail->studentID]
                                                                                [$sub_detail->subjectID]
                                                                                ;

                                                        if ($checkresultkey) {
                                                                $insert_data_ar[] = array(
                                                                'resultmanagementID'=> $checkresultkey->resultmanagementID,
                                                                'classesID'         => $stu_detail->classesID,
                                                                'sectionID'         => $sectionID,
                                                                'studentID'         => $stu_detail->studentID,
                                                                'subjectID'         => $sub_detail->subjectID,
                                                                'total_mark'        => $row['Total mark'],
                                                                'obtain_mark'       => $row['Total Obtain Marks'], 
                                                                'attempt'           => $row['Attempt'],
                                                                'internal_mark'     => $row['Internal (20%)'],
                                                                'midterm_mark'      => $row['Mid Term'],
                                                                'finalterm_mark'    => $row['Final Term Marks(80%)'],
                                                                'practical_mark'    => $row['Practical Work (20)'],
                                                                'updated'           => date('Y-m-d H:i:s'), 
                                                            );
                                                        }else{

                                                           $msg .= $i.". Attempt field is not valid:  ". $row['Attempt']." !";
                                                            //$msg .= implode(' , ', $singlebookCheck['error']);
                                                            $msg .= " <br/>";                     
                                                        }

                                                    }
                                 
                                }else {
                                    $msg .= $i.". Attempt field is not valid:  ". $row['Attempt']." !";
                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= " <br/>";
                                    $notvalid_attempt++;
                                }
                                              
                                        


                              
                                } else {
                                    $msg .= $i.". Subject Code  ". $row['Course Code.']." is not found!";
                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= " <br/>";
                                    $subject_not_found++;
                                }

                              
                                } else {
                                    $msg .= $i.". Student Reg.  ". $row['Registration No.']." is not found!";
                                    //$msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= " <br/>";
                                    $student_not_found++;
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                $this->data["subview"] = "/resultmanagement/add";
                                $this->load->view('_layout_main', $this->data);
                            }
                            $i++;
                        }

                        

                        if (count($insert_data_ar)) {
                           
                       // $this->resultmanagement_m->insert_batch_resultmanagement($insert_data_ar);
                        $this->db->update_batch('resultmanagement', $insert_data_ar, 'resultmanagementID');
                         }     
                        if($msg != "") {
                            
                            if ($student_not_found>0) {
                            $msg .= "Total Student Not Found ".$student_not_found ;
                                     
                                    $msg .= ". <br/>";
                                }
                            if ($subject_not_found>0) {
                                 
                            
                            $msg .= "Total Subject Not Found ".$subject_not_found ;
                                     
                            $msg .= ". <br/>";
                                    }
                            if ($result_found>0) {
                                 
                            
                            $msg .= "Total Result Already Present ".$result_found ;
                                     
                            $msg .= ". <br/>";
                                    }
                            if ($notvalid_attempt>0) {
                                 
                            
                            $msg .= "Total Attempt Not Valid ".$notvalid_attempt ;
                                     
                            $msg .= ". <br/>";
                                    }

                       // $this->session->set_flashdata('msg', $msg);

                         $this->data['msg'] = $msg;
                                     
                        }
                         
                         $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                         $this->data["subview"] = "/resultmanagement/add";
                         $this->load->view('_layout_main', $this->data);
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        $this->data["subview"] = "/resultmanagement/add";
                        $this->load->view('_layout_main', $this->data);
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file! column not match");
                    $this->data["subview"] = "/resultmanagement/add";
                    $this->load->view('_layout_main', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            $this->data["subview"] = "/resultmanagement/add";
            $this->load->view('_layout_main', $this->data);
        }
    

                // end



            }
        } else {
            $this->data["subview"] = "/resultmanagement/add";
            $this->load->view('_layout_main', $this->data);
        }
    }
       // Default Function All Import Validation Check
    public function arrayToPost($data) {
        if(is_array($data)) {
            $post = [];
            foreach ($data as $key => $item) {
                $key = preg_replace('/\s+/', '_', $key);
                $key = strtolower($key);
                $post[$key] = $item;
            }
            return $post;
        }
        return [];
    }

    public function update_result() {
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
             
            $this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id), TRUE);
            if($this->data['student']) {
                
                    if ($_POST) {
                        $resultmanagementID     =       $this->input->post('resultmanagementID');

                        $data                   =       array(
                                                            'resultmanagementID'    => $this->input->post('resultmanagementID'), 
                                                            'total_mark'            => $this->input->post('total_mark'), 
                                                            'obtain_mark'           => $this->input->post('obtain_mark'), 
                                                            'internal_mark'         => $this->input->post('internal_mark'), 
                                                            'midterm_mark'          => $this->input->post('midterm_mark'), 
                                                            'finalterm_mark'        => $this->input->post('finalterm_mark'), 
                                                            'practical_mark'        => $this->input->post('practical_mark'), 
                                                            );
                        $this->resultmanagement_m->update_resultmanagement($data, $resultmanagementID);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                         redirect(base_url("resultmanagement/update_result/$id"));
                    }

                    $subjects                   =       $this->subject_m->get_order_by_subject(array('classesID' => $this->data["student"]->srclassesID ));
                    $this->data["subjects"]     =       pluck($subjects, 'obj', 'subjectID');

                    

                    $this->data['sections']     =       $this->section_m->general_get_order_by_section(array('classesID' => $this->data["student"]->srclassesID));
                    $this->data['grades']       =       $this->grade_m->get_grade();

                    foreach ($this->data['sections'] as $sc) {
                    $this->data["result"][$sc->sectionID]  =     $this->resultmanagement_m->get_order_by_resultmanagement( array('studentID' => $id,'sectionID' => $sc->sectionID ));  
                    }

                    $this->data["subview"]  = "/resultmanagement/edit";
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

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['resultmanagement'] = $this->resultmanagement_m->get_single_resultmanagement(array('resultmanagementID' => $id));
            if($this->data['resultmanagement']) {
                $this->data["subview"] = "/resultmanagement/view";
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
            $this->data['resultmanagement'] = $this->resultmanagement_m->get_single_resultmanagement(array('resultmanagementID' => $id));
            if(count($this->data['resultmanagement'])) {
                $this->resultmanagement_m->delete_resultmanagement($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("resultmanagement/index"));
            } else {
                redirect(base_url("resultmanagement/index"));
            }
        } else {
            redirect(base_url("resultmanagement/index"));
        }
    }

    public function print_preview() {
        error_reporting(0);
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['resultmanagement'] = $this->resultmanagement_m->get_single_resultmanagement(array('resultmanagementID' => $id));
            if($this->data['resultmanagement']) {
                $this->data['panel_title'] = $this->lang->line('panel_title');




            $this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id), TRUE);
            if($this->data['student']) {
                
                    $student                    =       $this->data["student"];
                    $siteinfos                  =       $this->data["siteinfos"];

                    $subjects                   =       $this->subject_m->get_order_by_subject(array('classesID' => $this->data["student"]->srclassesID ));
                    $this->data["subjects"]     =       pluck($subjects, 'obj', 'subjectID');

                    

                    $this->data['sections']     =       $this->section_m->general_get_order_by_section(array('classesID' => $this->data["student"]->srclassesID));
                    $this->data['grades']       =       $this->grade_m->get_grade();

                    foreach ($this->data['sections'] as $sc) {
                    $this->data["result"][$sc->sectionID]  =     $this->resultmanagement_m->get_order_by_resultmanagement( array('studentID' => $id,'sectionID' => $sc->sectionID ));  
                    }

                   
                
            } 

               // $this->reportPDF($this->data, '/resultmanagement/print_preview');

            $header =   '<header style="padding-bottom: 20px">
                        <div class="header-top" style="text-align: center padding-right: 3rem ; padding-left: 3rem ; padding-top: 3rem ; padding-bottom: 3rem ;">
                            <table><tbody>
                            <tr>
                            <td width="30px"><img src="'.base_url().'assets/media/logos/afro-logo.png" alt="" width="200px" >
                               </td>
                               <td  style="padding-left: 0px; text-align: center;"><h1 style="text-align: center; font-size: 30px; font-family: Old English Text MT">'.$siteinfos->sname.'</h1>
                                <h1 style="text-align: center; font-size: 30px; font-family: Old English Text MT;">'.$siteinfos->address.'</h1>

                               </td>
                            </tr>
                            </tbody></table>
                                                            <table><tbody><tr><td style="text-align: center; padding-bottom: 20px;"> <h3 style="text-align: center; font-size: 16px; padding-top: 20px; text-decoration: underline;" class="text-underline">TRANSCRIPT</h3></td></tr></tbody></table>
                        </div>
                        <table><tbody><tr><td style="text-align: center; pading-left: 380px;"> <h3 style="text-align: center; font-size: 16px; padding-top: 20px; text-decoration: underline;" class="text-underline">
                                                '.$student->srclasses.', '.$student->session_year.'
                                        </td></tr></tbody></table>
                        <table>
                              <tbody>  <tr>
                                        <th class="" style="font-size: 14px; text-align: left;">NAME :</th>
                                        <td class="text-underline" style="font-size: 14px; text-decoration: underline;">'.$student->srname.'</td>
                                        <th class="" style="font-size: 14px; padding-right:20px;">REG. NO :</th>
                                        <td class="text-underline" style="font-size: 14px;text-decoration: underline; "> '.$student->srregisterNO.'</td>
                                </tr>
                                 <tr>
                                        <th class="" style="font-size: 14px; text-align:left;">FATHER NAME :</th>
                                        <td class="text-underline" style="font-size: 14px; text-decoration: underline;">N/A</td>
                                        <th class="" style="font-size: 14px; padding-left:-7px;">ROLL. NO :</th>
                                        <td class="text-underline" style="font-size: 14px;text-decoration: underline; ">'.$student->roll.'</td>
                                </tr>

                               </tbody>
                        </table>

                </header>';

                $footer     =   '  <footer>
                        <table>
                                <tr>
                                        <td style="width: 25%; padding-top:20px;">
                                                <p>COLLEGE NAME :</p>
                                                <p style="padding-top:5px;">PREPARED BY :</p>
                                                <p style="padding-top:5px;">CHECKED BY :</p>
                                                <p style="padding-top:5px;">DY. CONTROLLER :</p>
                                                <p style="padding-top:5px;">RESULT DECLARATION DATE :</p>
                                                <p style="padding-top:5px;">DATE OF ISSUE :</p>
                                                <p style="padding-top:5px;">SERIAL NO :</p>
                                        </td>
                                        <td style="width: 25%; padding-top:20px;">

                                                <!-- <h6 class="element-underline" style="width: 100%"></h6>
                                                <h6 class="element-underline" style="width: 100%"></h6>
                                                <h6 class="element-underline" style="width: 100%"></h6>
                                                <h6 class="element-underline" style="width: 100%"></h6>
                                                <h6 class="element-underline" style="width: 100%"></h6>
                                                <h6 class="element-underline" style="width: 100%"></h6> -->

                                                <p class="element-underline" style="width: 100%; text-decoration:underline;">'.$siteinfos->sname.'</p>
                                                <p class="element-underline" style="width: 100%">_______________________________________________________</p>
                                                <p class="element-underline" style="width: 100%">_______________________________________________________</p>
                                                <p class="element-underline" style="width: 100%">_______________________________________________________</p>
                                                <p class="element-underline" style="width: 100%">_______________________________________________________</p>
                                                <p class="element-underline" style="width: 100%; text-decoration:underline;">'.date("F d, Y, h:i A").'</p>
                                                <p class="element-underline" style="width: 100%">_______________________________________________________</p>
                                        </td>
                                        <td style="display: flex; flex-direction: column; width: 25%; padding-top: 50px;">

                                                <p class="text-overline-custom" style="border-top: 2px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROLLER OF EXAMINATIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$siteinfos->sname.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                <br>
                                                <img src="'.base_url().'assets/media/logos/afro-logo.png" alt="" width="100px" height="20px">

                                        </td>
                                </tr>
                        </table>
                </footer>';

                 
                $this->reportPDF('resultmanagement.css',$this->data, 'resultmanagement/print_preview','view','legal', 'portrait', $header, $footer);
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
            $this->data['resultmanagement'] = $this->resultmanagement_m->get_single_resultmanagement(array('resultmanagementID' => $id));
            if($this->data['resultmanagement']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->reportSendToMail($this->data['resultmanagement'], '/resultmanagement/print_preview', $email, $subject, $message);
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
