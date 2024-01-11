<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_category extends Admin_Controller {
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
        $this->load->model("asset_category_m");
        $this->load->model("invoice_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('asset_category', $language);
    }

    public function index() {
        $this->data['asset_categorys'] = $this->asset_category_m->get_order_by_asset_category();
        $this->data["subview"] = "asset_category/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'category',
                'label' => $this->lang->line("asset_category"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            ),
            array(
                'field' => 'category_abbr',
                'label' => $this->lang->line("asset_category_abbr"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            ),
            array(
                'field' => 'cat_type',
                'label' => 'Type',
                'rules' => 'trim|required|xss_clean|max_length[255]'
            ),
            array(
                'field' => 'credit_id',
                'label' => 'Credit Account',
                'rules' => 'trim|required|xss_clean|max_length[255]'
            ),
            array(
                'field' => 'debit_id',
                'label' => 'Debit Account',
                'rules' => 'trim|required|xss_clean|max_length[255]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datepicker/datepicker.js'
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "asset_category/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "cat_type"  => $this->input->post("cat_type"),
                    "category"  => $this->input->post("category"), 
                    "credit_id"  => $this->input->post("credit_id"),
                    "debit_id"  => $this->input->post("debit_id")
                );
                
                $array["create_date"]           = date("Y-m-d");
                $array["modify_date"]           = date("Y-m-d");
                $array["create_userID"]         = $this->session->userdata('loginuserID');
                $array["create_usertypeID"]     = $this->session->userdata('usertypeID');
                $array["active"]                = 1;
                
                 $this->asset_category_m->insert_asset_category($array) ;
               
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_category/index"));
            }
        } else {
            $this->data['chartofaccounts'] = $this->invoice_m->get_chartofaccount_data();
            $this->data["subview"] = "asset_category/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_category'] = $this->asset_category_m->get_single_asset_category(array('asset_categoryID' => $id));
            $this->data['chartofaccounts'] = $this->invoice_m->get_chartofaccount_data();
            if($this->data['asset_category']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/asset_category/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "cat_type"      => $this->input->post("cat_type"),
                            "category"      => $this->input->post("category"),
                            "credit_id"     => $this->input->post("credit_id"),
                            "debit_id"      => $this->input->post("debit_id"),
                            "category_abbr" => $this->input->post("category_abbr")
                        );
                        $array["modify_date"] = date("Y-m-d");

                        $this->asset_category_m->update_asset_category($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("asset_category/index"));
                    }
                } else {
                    $this->data["subview"] = "asset_category/edit";
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

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_category'] = $this->asset_category_m->get_single_asset_category(array('asset_categoryID' => $id));
            if($this->data['asset_category']) {
                $this->asset_category_m->delete_asset_category($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_category/index"));
            } else {
                redirect(base_url("asset_category/index"));
            }
        } else {
            redirect(base_url("asset_category/index"));
        }
    }


    public function getcategorylist() {
        $cat_type = $this->input->post('cat_type');
        if((int)$cat_type) {
            echo "<option value='0'>Please Select</option>";
            $allcategories = $this->asset_category_m->get_order_by_asset_category(array('cat_type' => $cat_type));
            if(customCompute($allcategories)) {
                foreach ($allcategories as $value) {
                    echo "<option value=\"$value->asset_categoryID\">",$value->category,"</option>";
                }
            }
        }
    }

}
