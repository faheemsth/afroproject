<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentsubjects extends Admin_Controller {
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
        $this->load->model("studentsubjects_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('studentsubjects', $language);
    }

    public function index() {
        $this->data['studentsubjectss'] = $this->studentsubjects_m->get_studentsubjects();
        $this->data["subview"] = "/studentsubjects/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("studentsubjects_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
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
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "/studentsubjects/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->studentsubjects_m->insert_studentsubjects($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("studentsubjects/index"));
            }
        } else {
            $this->data["subview"] = "/studentsubjects/add";
            $this->load->view('_layout_main', $this->data);
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
            $this->data['studentsubjects'] = $this->studentsubjects_m->get_single_studentsubjects(array('studentsubjectsID' => $id));
            if($this->data['studentsubjects']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/studentsubjects/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->studentsubjects_m->update_studentsubjects($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("studentsubjects/index"));
                    }
                } else {
                    $this->data["subview"] = "/studentsubjects/edit";
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
            $this->data['studentsubjects'] = $this->studentsubjects_m->get_single_studentsubjects(array('studentsubjectsID' => $id));
            if($this->data['studentsubjects']) {
                $this->data["subview"] = "/studentsubjects/view";
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
            $this->data['studentsubjects'] = $this->studentsubjects_m->get_single_studentsubjects(array('studentsubjectsID' => $id));
            if(count($this->data['studentsubjects'])) {
                $this->studentsubjects_m->delete_studentsubjects($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("studentsubjects/index"));
            } else {
                redirect(base_url("studentsubjects/index"));
            }
        } else {
            redirect(base_url("studentsubjects/index"));
        }
    }

    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['studentsubjects'] = $this->studentsubjects_m->get_single_studentsubjects(array('studentsubjectsID' => $id));
            if($this->data['studentsubjects']) {
                $this->data['panel_title'] = $this->lang->line('panel_title');
                $this->reportPDF($this->data, '/studentsubjects/print_preview');
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
            $this->data['studentsubjects'] = $this->studentsubjects_m->get_single_studentsubjects(array('studentsubjectsID' => $id));
            if($this->data['studentsubjects']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->reportSendToMail($this->data['studentsubjects'], '/studentsubjects/print_preview', $email, $subject, $message);
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
