<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hostelroom extends Admin_Controller {
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
        $this->load->model("hostelroom_m");
        $this->load->model("hostel_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('hmember', $language);
        $this->lang->load('hostelroom', $language);
    }

    public function index() {
        $this->data['hostelrooms'] = $this->hostelroom_m->get_hostelroom(); 
        $this->data['hostel'] = pluck($this->hostel_m->get_order_by_hostel(),'obj','hostelID');
        $this->data["subview"] = "/hostelroom/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'hostelroom',
                'label' => 'hostel room',
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'hostelID',
                'label' => 'hostel',
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'roomcapcity',
                'label' => 'room capcity',
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'monthlyfee',
                'label' => 'monthly fee',
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'security',
                'label' => 'security',
                'rules' => 'trim|required|xss_clean|max_length[128]'
            )
        );
        return $rules;
    }

    public function add() {
            $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );
        $this->data['hostels'] = $this->hostel_m->get_order_by_hostel();
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "/hostelroom/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "hostelID"          => $this->input->post("hostelID"),
                    "hostelroom"        => $this->input->post("hostelroom"),
                    "monthlyfee"        => $this->input->post("monthlyfee"),
                    "roomcapcity"       => $this->input->post("roomcapcity"),
                    "capcityoccopied"   => 0,
                    "security"          => $this->input->post("security"),
                );
                $this->hostelroom_m->insert_hostelroom($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("hostelroom/index"));
            }
        } else {
            $this->data["subview"] = "/hostelroom/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {

         $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['hostelroom'] = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $id));
            $this->data['hostels'] = $this->hostel_m->get_order_by_hostel();
            if($this->data['hostelroom']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/hostelroom/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                       $array = array(
                    "hostelID"          => $this->input->post("hostelID"),
                    "hostelroom"        => $this->input->post("hostelroom"),
                    "monthlyfee"        => $this->input->post("monthlyfee"),
                    "roomcapcity"       => $this->input->post("roomcapcity"), 
                    "security"          => $this->input->post("security"),
                );

                        $this->hostelroom_m->update_hostelroom($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("hostelroom/index"));
                    }
                } else {
                    $this->data["subview"] = "/hostelroom/edit";
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
            $this->data['hostelroom'] = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $id));
            if($this->data['hostelroom']) {
                $this->data["subview"] = "/hostelroom/view";
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
            $this->data['hostelroom'] = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $id));
            if(count($this->data['hostelroom'])) {
                $this->hostelroom_m->delete_hostelroom($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("hostelroom/index"));
            } else {
                redirect(base_url("hostelroom/index"));
            }
        } else {
            redirect(base_url("hostelroom/index"));
        }
    }

    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['hostelroom'] = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $id));
            if($this->data['hostelroom']) {
                $this->data['panel_title'] = $this->lang->line('panel_title');
                $this->reportPDF($this->data, '/hostelroom/print_preview');
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
            $this->data['hostelroom'] = $this->hostelroom_m->get_single_hostelroom(array('hostelroomID' => $id));
            if($this->data['hostelroom']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->reportSendToMail($this->data['hostelroom'], '/hostelroom/print_preview', $email, $subject, $message);
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
