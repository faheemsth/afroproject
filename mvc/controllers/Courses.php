<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses extends Admin_Controller {
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
        $this->load->model("course_m");
        $this->load->model('courseoption_m');
        $this->load->model("classes_m");
        $this->load->model("feetypes_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('course_lang', $language);
    }

    public function index() {
        $this->data['allyears'] = $this->course_m->get_distinct_year();

           $this->data['salary_templatesall'] = $this->course_m->get_order_by_salary_template();

        foreach ($this->data['allyears'] as $y) {
           $this->data['salary_templates'][$y->basic_salary] = $this->course_m->get_order_by_salary_template(array('basic_salary' => $y->basic_salary));
        }
        
        $this->data["subview"] = "/courses/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'salary_grades',
                'label' => $this->lang->line("salary_template_salary_grades"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_salary_grades'
            ),
            array(
                'field' => 'basic_salary',
                'label' => $this->lang->line("salary_template_basic_salary"),
                'rules' => 'trim|required|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'overtime_rate',
                'label' => $this->lang->line("salary_template_overtime_rate"),
                'rules' => 'trim|required|xss_clean|max_length[11]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['classes']  = $this->classes_m->general_get_classes();
        $this->data['feetypes'] = $this->feetypes_m->get_feetypes();
        $this->data["subview"]  = "courses/add";
        $this->load->view('_layout_main', $this->data); 
    }

    public function add_ajax() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->lang->line('menu_error'));
                $json = array("status" => 'error', 'errors' => $this->form_validation->error_array());
                header("Content-Type: application/json", true);
                echo json_encode($json);
                exit;
            } else {
                $array = array(
                    "salary_grades"     => $this->input->post("salary_grades"),
                    "basic_salary"      => $this->input->post("basic_salary"),
                    "overtime_rate"     => $this->input->post("overtime_rate"),
                );

                $this->course_m->insert_salary_template($array);
                $salary_templateID = $this->db->insert_id();

                $allowances_number = $this->input->post('allowances_number');
                if(customCompute($allowances_number)) {
                    for ($i=1; $i <= $allowances_number; $i++) { 
                        if($this->input->post('allowanceslabel'.$i) !='' && $this->input->post('allowancesamount'.$i) !='') {
                            $allowancesArray = array(
                                'salary_templateID' => $salary_templateID,
                                'option_type'       => 1,
                                'label_name'        => $this->input->post('allowanceslabel'.$i),
                                'label_amount'      => $this->input->post('allowancesamount'.$i)
                            );
                            $this->courseoption_m->insert_salaryoption($allowancesArray);
                        }
                    }
                }

                $deductions_number = $this->input->post('deductions_number');
                if(customCompute($deductions_number)) {
                    for ($i=1; $i <= $deductions_number; $i++) { 
                        if($this->input->post('deductionslabel'.$i) !='' && $this->input->post('deductionsamount'.$i) !='') {
                            $deductionsArray = array(
                                'salary_templateID' => $salary_templateID,
                                'option_type'       => 2,
                                'label_name'        => $this->input->post('deductionslabel'.$i),
                                'label_amount'      => $this->input->post('deductionsamount'.$i)
                            );
                            $this->courseoption_m->insert_salaryoption($deductionsArray);
                        }
                    }
                }

                $json = array("status" => 'success');
                header("Content-Type: application/json", true);
                echo json_encode($json);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            }
        }

    }

    public function edit_ajax() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', $this->lang->line('menu_error'));
                $json = array("status" => 'error', 'errors' => $this->form_validation->error_array());
                header("Content-Type: application/json", true);
                echo json_encode($json);
                exit;
            } else {

                $array = array(
                    "salary_grades"     => $this->input->post("salary_grades"),
                    "basic_salary"      => $this->input->post("basic_salary"),
                    "overtime_rate"     => $this->input->post("overtime_rate"),
                );

                $id = htmlentities($this->input->post('id')); 
                $this->course_m->update_salary_template($array, $id);

                $allowances_number = $this->input->post('allowances_number');
                $this->courseoption_m->delete_salaryoption_by_salary_templateID($id);

                if(customCompute($allowances_number)) {
                    for ($i=1; $i <= $allowances_number; $i++) { 
                        if($this->input->post('allowanceslabel'.$i) !='' && $this->input->post('allowancesamount'.$i) !='') {
                            $allowancesArray = array(
                                'salary_templateID' => $id,
                                'option_type'       => 1,
                                'label_name'        => $this->input->post('allowanceslabel'.$i),
                                'label_amount'      => $this->input->post('allowancesamount'.$i)
                            );
                            $this->courseoption_m->insert_salaryoption($allowancesArray);
                        }
                    }
                }

                $deductions_number = $this->input->post('deductions_number');
                if(customCompute($deductions_number)) {
                    for ($i=1; $i <= $deductions_number; $i++) { 
                        if($this->input->post('deductionslabel'.$i) !='' && $this->input->post('deductionsamount'.$i) !='') {
                            $deductionsArray = array(
                                'salary_templateID' => $id,
                                'option_type'       => 2,
                                'label_name'        => $this->input->post('deductionslabel'.$i),
                                'label_amount'      => $this->input->post('deductionsamount'.$i)
                            );
                            $this->courseoption_m->insert_salaryoption($deductionsArray);
                        }
                    }
                }

                $json = array("status" => 'success');
                header("Content-Type: application/json", true);
                echo json_encode($json);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            }
        }

    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['salary_template'] = $this->course_m->get_single_salary_template(array('salary_templateID' => $id));
            if($this->data['salary_template']) {
                $this->db->order_by("salary_optionID", "asc");
                $this->data['salaryoptions'] = $this->courseoption_m->get_order_by_salaryoption(array('salary_templateID' => $id));
                $this->data['setid'] = $id;

                $grosssalary = 0;
                $totaldeduction = 0;
                $netsalary = $this->data['salary_template']->basic_salary;
                $orginalNetsalary = $this->data['salary_template']->basic_salary;
                $grosssalarylist = array();
                $totaldeductionlist = array();

                if(customCompute($this->data['salaryoptions'])) {
                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary += $salaryOption->label_amount;
                            $grosssalary += $salaryOption->label_amount;
                            $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                            $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                        }
                    }
                }
                
                $this->data['grosssalary'] = $grosssalary+$orginalNetsalary;
                $this->data['totaldeduction'] = $totaldeduction;
                $this->data['netsalary'] = $netsalary;
                $this->data['grosssalarylist'] = $grosssalarylist;
                $this->data['totaldeductionlist'] = $totaldeductionlist;



                $this->data["subview"] = "salary_template/edit";
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
            $this->data['salary_template'] = $this->course_m->get_single_salary_template(array('salary_templateID' => $id));
            if($this->data['salary_template']) {
                $this->db->order_by("salary_optionID", "asc");
                $this->data['salaryoptions'] = $this->courseoption_m->get_order_by_salaryoption(array('salary_templateID' => $id));


                $grosssalary = 0;
                $totaldeduction = 0;
                $netsalary = 0;
                // $netsalary = $this->data['salary_template']->basic_salary;
                $orginalNetsalary = $this->data['salary_template']->basic_salary;

                if(customCompute($this->data['salaryoptions'])) {
                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary += $salaryOption->label_amount;
                            $grosssalary += $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                        }
                    }
                }
                
                $this->data['grosssalary'] = $grosssalary+$orginalNetsalary;
                $this->data['totaldeduction'] = $totaldeduction;
                $this->data['netsalary'] = $netsalary;
                $get_feetypes = $this->feetypes_m->get_feetypes();
                $get_feetypes = pluck($get_feetypes, "obj", "feetypesID");
                $this->data["get_feetypes_pluck"] = $get_feetypes;

                $this->data["subview"] = "courses/view";
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
            $this->data['salary_template'] = $this->course_m->get_single_salary_template(array('salary_templateID' => $id));
            if($this->data['salary_template']) {
                $this->course_m->delete_salary_template($id);
                $this->courseoption_m->delete_salaryoption_by_salary_templateID($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("courses/index"));
            } else {
                redirect(base_url("courses/index"));
            }
        } else {
            redirect(base_url("courses/index"));
        }
    }


    public function unique_salary_grades() {
        if($this->input->post('salary_grades')) {
            $id = htmlentities(escapeString($this->input->post('id')));
            if((int)$id) {
                $salary_grades = $this->course_m->get_single_salary_template(array('basic_salary' => $this->input->post("basic_salary"),'salary_grades' => $this->input->post("salary_grades"), 'salary_templateID !=' => $id));
                if(customCompute($salary_grades)) {
                    $this->form_validation->set_message("unique_salary_grades", "%s already exists in this year");
                    return FALSE;
                }
                return TRUE;
            } else {
                $salary_grades = $this->course_m->get_single_salary_template(array('salary_grades' => $this->input->post("salary_grades"),'basic_salary' => $this->input->post("basic_salary")));
                if(customCompute($salary_grades)) {
                    $this->form_validation->set_message("unique_salary_grades", "%s already exists in this year");
                    return FALSE;
                }
                return TRUE;
            }
        }
        return TRUE;
    }
}
