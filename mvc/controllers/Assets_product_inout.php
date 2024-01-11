<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets_product_inout extends Admin_Controller {
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
        $this->load->model("assets_product_inout_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('assets_product_inout', $language);
    }

    public function index() {
        $this->data['assets_product_inouts'] = $this->assets_product_inout_m->get_assets_product_inout();
        $this->data["subview"] = "/assets_product_inout/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("assets_product_inout_title"),
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
                $this->data["subview"] = "/assets_product_inout/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->assets_product_inout_m->insert_assets_product_inout($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("assets_product_inout/index"));
            }
        } else {
            $this->data["subview"] = "/assets_product_inout/add";
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
            $this->data['assets_product_inout'] = $this->assets_product_inout_m->get_single_assets_product_inout(array('assets_product_inoutID' => $id));
            if($this->data['assets_product_inout']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/assets_product_inout/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->assets_product_inout_m->update_assets_product_inout($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("assets_product_inout/index"));
                    }
                } else {
                    $this->data["subview"] = "/assets_product_inout/edit";
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
            $this->data['assets_product_inout'] = $this->assets_product_inout_m->get_single_assets_product_inout(array('assets_product_inoutID' => $id));
            if($this->data['assets_product_inout']) {
                $this->data["subview"] = "/assets_product_inout/view";
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
            $this->data['assets_product_inout'] = $this->assets_product_inout_m->get_single_assets_product_inout(array('assets_product_inoutID' => $id));
            if(count($this->data['assets_product_inout'])) {
                $this->assets_product_inout_m->delete_assets_product_inout($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("assets_product_inout/index"));
            } else {
                redirect(base_url("assets_product_inout/index"));
            }
        } else {
            redirect(base_url("assets_product_inout/index"));
        }
    }

    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['assets_product_inout'] = $this->assets_product_inout_m->get_single_assets_product_inout(array('assets_product_inoutID' => $id));
            if($this->data['assets_product_inout']) {
                $this->data['panel_title'] = $this->lang->line('panel_title');
                $this->reportPDF($this->data, '/assets_product_inout/print_preview');
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
            $this->data['assets_product_inout'] = $this->assets_product_inout_m->get_single_assets_product_inout(array('assets_product_inoutID' => $id));
            if($this->data['assets_product_inout']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->reportSendToMail($this->data['assets_product_inout'], '/assets_product_inout/print_preview', $email, $subject, $message);
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
