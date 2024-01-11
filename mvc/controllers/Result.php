<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Result extends Admin_Controller
{

    public $notdeleteArray = [1];

    public function __construct()
    {
        parent::__construct();
        $this->load->model("result_m");
        $this->load->library('updatechecker');
        $this->data['notdeleteArray'] = $this->notdeleteArray;
        $language = $this->session->userdata('lang');
        $this->lang->load('result', $language);
    }

    public function index()
    {
        $this->data['results']   = $this->result_m->get_order_by_result();
        $this->data["subview"] = "result/index";
        $this->load->view('_layout_main', $this->data);
    }


    protected function rules()
    {
        $rules = [
            [
                'field' => 'result',
                'label' => 'Result',
                'rules' => 'trim|required|xss_clean|max_length[60]'
            ],
            [
                'field' => 'date',
                'label' => 'Result date',
                'rules' => 'trim|required|max_length[10]|xss_clean'
            ],
            [
                'field' => 'result_year',
                'label' => 'Result Year',
                'rules' => 'trim|required|max_length[20]|xss_clean'
            ],
            [
                'field' => 'note',
                'label' => 'Note',
                'rules' => 'trim|max_length[200]|xss_clean'
            ]
        ];
        return $rules;
    }

    public function add()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ],
            'js'  => [
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            ]
        ];

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"]         = "result/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                if (config_item('demo') == false) {
                    $updateValidation = $this->updatechecker->verifyValidUser();
                    if ($updateValidation->status == false) {
                        $this->session->set_flashdata('error', $updateValidation->message);
                        redirect(base_url('result/add'));
                    }
                }

                $array["result"]      = $this->input->post("result");
                $array["result_year"] = $this->input->post("result_year");
                $array["date"]      = date("Y-m-d", strtotime($this->input->post("date")));
                $array["note"]      = $this->input->post("note");

                $this->result_m->insert_result($array);
                $this->session->set_flashdata('success', 'Result created successfully.');
                redirect(base_url("result/index"));
            }
        } else {
            $this->data["subview"] = "result/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ],
            'js'  => [
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            ]
        ];

        $resultID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $resultID) {
            $this->data['result'] = $this->result_m->get_result($resultID);
            if ($this->data['result']) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "result/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array["result"]      = $this->input->post("result");
                        $array["result_year"] = $this->input->post("result_year");
                        $array["date"]      = date("Y-m-d", strtotime($this->input->post("date")));
                        $array["note"]      = $this->input->post("note");

                        $this->result_m->update_result($array, $resultID);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("result/index"));
                    }
                } else {
                    $this->data["subview"] = "result/edit";
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

    public function delete()
    {
        $resultID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$resultID && !in_array($resultID, $this->notdeleteArray)) {
            $this->result_m->delete_result($resultID);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            redirect(base_url("result/index"));
        } else {
            redirect(base_url("result/index"));
        }
    }

    public function uploadresultcard()
    {

        $resultID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $resultID) {
            $this->data['result'] = $this->result_m->get_result($resultID);
            if ($this->data['result']) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "result/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array["result"]      = $this->input->post("result");
                        $array["result_year"] = $this->input->post("result_year");
                        $array["date"]      = date("Y-m-d", strtotime($this->input->post("date")));
                        $array["note"]      = $this->input->post("note");

                        $this->result_m->update_result($array, $resultID);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("result/index"));
                    }
                } else {
                    $this->data["subview"] = "result/upload";
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

    function doupload()
    {
        if (!empty($_FILES)) {
            $resultID = htmlentities(escapeString($this->uri->segment(3)));

            if (!is_dir('uploads/result/' . $resultID)) {
                mkdir('uploads/result/' . $resultID);
            }
            // File upload configuration 
            $uploadPath = 'uploads/result/' . $resultID;
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = '*';
            $config['overwrite'] = TRUE;

            // Load and initialize upload library 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            // Upload file to the server 
            if ($this->upload->do_upload('file')) {
                $fileData = $this->upload->data();
                $uploadData['file_name'] = $fileData['file_name'];
                $uploadData['uploaded_on'] = date("Y-m-d H:i:s");

                var_dump($uploadData);

                // Insert files info into the database 
                // $insert = $this->file->insert($uploadData); 
            }
        }
    }

    public function active()
    {
        if (permissionChecker('result_edit')) {
            $id     = $this->input->post('id');
            $status = $this->input->post('status');
            if ($id != '' && $status != '') {
                if ((int)$id) {

                    $result = $this->result_m->get_single_result(array('ResultID' => $id));
                    if (customCompute($result)) {
                        if ($status == 'chacked') {
                            $this->result_m->update_result(array('active' => 1), $id);
                            echo 'Success';
                        } elseif ($status == 'unchacked') {
                            $this->result_m->update_result(array('active' => 0), $id);

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

    public function is_download()
    {
        if (permissionChecker('result_edit')) {
            $id     = $this->input->post('id');
            $status = $this->input->post('status');
            if ($id != '' && $status != '') {
                if ((int)$id) {

                    $result = $this->result_m->get_single_result(array('ResultID' => $id));
                    if (customCompute($result)) {
                        if ($status == 'chacked') {
                            $this->result_m->update_result(array('is_download' => 1), $id);
                            echo 'Success';
                        } elseif ($status == 'unchacked') {
                            $this->result_m->update_result(array('is_download' => 0), $id);

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
}
