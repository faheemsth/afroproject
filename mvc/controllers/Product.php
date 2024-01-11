<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends Admin_Controller
{
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

    function __construct()
    {
        parent::__construct();
        $this->load->model("product_m");
        $this->load->model("productcategory_m");
        $this->load->model("product_inout_m");
        $this->load->model("quotation_m");
        $this->load->model("productsupplier_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('asset', $language);
        $this->lang->load('product', $language);
    }

    public function index()
    {
        $filter = [];

        if (isset($_GET)) {
          
            if (isset($_GET['productcategoryID']) && !empty($_GET['productcategoryID'])) {
                $this->data['productcategoryID'] = $_GET['productcategoryID'];
                $filter['product.productcategoryID'] = $_GET['productcategoryID'];
            } else {
                $this->data["productcategoryID"] = "";
            }

            if (isset($_GET['productCode']) && !empty($_GET['productCode'])) {
                $this->data['productCode'] = str_replace(' ', '', $_GET['productCode']);
                $filter['product.code_reference LIKE'] = '%' . str_replace(' ', '', $_GET['productCode']) . '%';
            } else {
                $this->data["productCode"] = "";
            }
            
            if (isset($_GET['productTitle']) && !empty($_GET['productTitle'])) {
                $this->data['productTitle'] = str_replace(' ', '', $_GET['productTitle']);
                $filter['product.productname LIKE'] = '%' . str_replace(' ', '', $_GET['productTitle']) . '%';
            } else {
                $this->data["productTitle"] = "";
            }
            
        }
        


        $this->data['productcategorys'] = pluck($this->productcategory_m->get_productcategory(), 'productcategoryname', 'productcategoryID');
        $this->data['products'] = $this->product_m->get_order_by_product($filter);

        $this->data["subview"] = "product/index";
        $this->load->view('_layout_main', $this->data);
    }

    /*******   
     * Quotation Start
     * *********/

    public function quotation()
    {

        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['set']     = '';
        if ((int)$id) {
            $this->data['defaultschoolyearID'] = $this->session->userdata('defaultschoolyearID');
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
            $this->data['productId'] = $id;

            $this->data['product']  = $this->product_m->get_single_product(array('productID' => $id));
            $this->data['productcategorys'] = pluck($this->productcategory_m->get_productcategory(), 'productcategoryname', 'productcategoryID');



            if (customCompute($this->data['product'])) {
                // supplier_or_vendor == 0 for supplier, supplier_or_vendor == 1 for vendor
                $this->data['allquotations']    = $this->quotation_m->get_order_by_product_quotation(array('productID' => $id, 'supplier_or_vendor' => '0'));
                $this->data["subview"] = "product/quotation";

                $id = htmlentities(escapeString($this->uri->segment(3)));
                $url = htmlentities(escapeString($this->uri->segment(4)));

                //self::debug($url);

                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main');
        }
    }

    private static function debug($data)
    {
        echo "<pre>";
        print_r($data);
        die();
    }

    public function photoupload()
    {
        $id   = htmlentities(escapeString($this->uri->segment(3)));
        $product = [];
        if ((int) $id) {
            $product = $this->product_m->get_single_product(array('productID' => $id));
        }

        $new_file = "default.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name        = $_FILES["photo"]['name'];
            $random           = random19();
            $makeRandom       = hash(
                'sha512',
                $random . $this->input->post('username') . config_item("encryption_key")
            );
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = '1024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            $this->upload_data['file'] = ['file_name' => $new_file];
            return true;
        }
    }

    public function addQuotation()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $product_id = htmlentities(escapeString($this->uri->segment(3)));


        if ($_POST) {
            $rules = $this->quotation_rules();

            // self::debug($_FILES);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Please fill all the field.');
                $this->data["subview"] = "product/addQuotation/$product_id";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productID" => $product_id,
                    "supplier_vendorID" => $this->input->post("supplierID"),
                    "supplier_or_vendor" => '0',       // supplier_or_vendor = 0 mean this quoation is for supplier
                    "description" => $this->input->post("description"),
                    "quantity" => $this->input->post("quantity"),
                    "unit_price" => $this->input->post("unit_price"),
                    "quotation_img" => $this->upload_data['file']['file_name']
                );

                $this->quotation_m->insert_quotation($array);
                $this->session->set_flashdata('success', 'Quotation saved successfully.');
                redirect(base_url("product/quotation/$product_id"));
            }
        } else {
            $this->data['productsuppliers'] = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');


            //self::debug($this->data['productsuppliers']);
            $this->data["subview"] = "product/add_quotation";
            $this->load->view('_layout_main', $this->data);
        }
    }


    public function print_preview()
    {
        if (permissionChecker('quotation_add') || (($this->session->userdata('usertypeID') == 1))) {
            $usertypeID = $this->session->userdata('usertypeID');
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(3)));

            if ((int)$id) {
                $this->data["product"] = $this->product_m->get_single_product(array('productID' => $id));
                if (customCompute($this->data["product"])) {
                    $this->data["allquotations"] = $this->quotation_m->get_order_by_product_quotation(array('productID' => $id, 'supplier_or_vendor' => '0'));
                    $this->data['productcategorys'] = pluck($this->productcategory_m->get_productcategory(), 'productcategoryname', 'productcategoryID');
                    $this->reportPDF('quotationmodule.css', $this->data, 'product/print_preview');
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

    public function download()
    {
        $file = htmlentities(escapeString($this->uri->segment(3)));
        if (!empty($file)) {
            $url = base_url("uploads/images/$file");
            $this->load->helper('download');
            $data = file_get_contents($url);
            force_download($file, $data);
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function send_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("student_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("student_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("student_message"),
                'rules' => 'trim|xss_clean'
            )
        );
        return $rules;
    }


    public function send_mail()
    {
        // self::debug($_POST);
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if (permissionChecker('quotation_add') || (($this->session->userdata('usertypeID') == 1))) {
            if ($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $id = $this->input->post('productid');

                    if ((int)$id) {
                        $this->data["product"] = $this->product_m->get_single_product(array('productID' => $id));
                        if (customCompute($this->data["product"])) {
                            $this->data["allquotations"] = $this->quotation_m->get_order_by_product_quotation(array('productID' => $id, 'supplier_or_vendor' => '0'));
                            $this->data['productcategorys'] = pluck($this->productcategory_m->get_productcategory(), 'productcategoryname', 'productcategoryID');
                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');
                            $this->reportSendToMail('quotationmodule.css', $this->data, 'product/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view('_layout_main', $this->data);
                        }
                    } else {
                        $retArray['message'] =  'No product id';
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] =  'No post data';
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = 'You are not allowed to send mail.';
            echo json_encode($retArray);
            exit;
        }
    }
    /*******   
     * Quotation End
     * *********/



    public function ledger()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $this->data['asset']        = $this->product_m->get_single_product(array('productID' => $id));
            if (customCompute($this->data['asset'])) {
                $this->data['allinouts']    = $this->product_inout_m->get_order_by_product_inout(array('productID' => $id));
                $this->data["subview"] = "product/ledger";
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main');
        }
    }

    public function ledger_print_preview()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $this->data['asset']        = $this->product_m->get_single_product(array('productID' => $id));
            if (customCompute($this->data['asset'])) {
                $this->data['allinouts']    = $this->product_inout_m->get_order_by_product_inout(array('productID' => $id));
                // $this->data["subview"] = "product/ledger";
                // $this->load->view('_layout_main', $this->data);
                $this->reportPDF('assetmodule.css', $this->data, 'product/ledger_print_preview');
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main');
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'productname',
                'label' => $this->lang->line("product_product"),
                'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_productname'
            ),
            array(
                'field' => 'productcategoryID',
                'label' => $this->lang->line("product_category"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_prodectcategory'
            ),
            array(
                'field' => 'productbuyingprice',
                'label' => $this->lang->line("product_buyingprice"),
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            ), array(
                'field' => 'productsellingprice',
                'label' => $this->lang->line("product_sellingprice"),
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            ),
            array(
                'field' => 'productdesc',
                'label' => $this->lang->line("product_desc"),
                'rules' => 'trim|xss_clean|max_length[250]'
            )
        );
        return $rules;
    }

    protected function quotation_rules()
    {
        $rules = array(
            array(
                'field' => 'supplierID',
                'label' => 'Supplier',
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'quantity',
                'label' => 'Quantity',
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]'
            ),
            array(
                'field' => 'unit_price',
                'label' => 'Unit Price',
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            ),
            array(
                'field' => 'photo',
                'label' => 'File',
                'rules' => 'trim|max_length[200]|callback_photoupload'
            ),
        );
        return $rules;
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "product/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productname" => $this->input->post("productname"),
                    "productcategoryID" => $this->input->post("productcategoryID"),
                    "productbuyingprice" => $this->input->post("productbuyingprice"),
                    "productsellingprice" => $this->input->post("productsellingprice"),
                    "productdesc" => $this->input->post("productdesc"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID'),
                    "code_reference" => $this->input->post("code_reference")
                );
                $this->product_m->insert_product($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("product/index"));
            }
        } else {
            $this->data["subview"] = "product/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
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
        if ((int)$id) {
            $this->data['product'] = $this->product_m->get_single_product(array('productID' => $id));
            $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
            if ($this->data['product']) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "product/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "productname" => $this->input->post("productname"),
                            "productcategoryID" => $this->input->post("productcategoryID"),
                            "productbuyingprice" => $this->input->post("productbuyingprice"),
                            "productsellingprice" => $this->input->post("productsellingprice"),
                            "productdesc" => $this->input->post("productdesc"),
                            "modify_date" => date("Y-m-d H:i:s"),
                        );

                        $this->product_m->update_product($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("product/index"));
                    }
                } else {
                    $this->data["subview"] = "product/edit";
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
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $this->data['product'] = $this->product_m->get_single_product(array('productID' => $id));
            if ($this->data['product']) {
                $this->product_m->delete_product($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("product/index"));
            } else {
                redirect(base_url("product/index"));
            }
        } else {
            redirect(base_url("product/index"));
        }
    }

    public function unique_productname()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $product = $this->product_m->get_order_by_product(array("productname" => $this->input->post("productname"), "productID !=" => $id));
            if (customCompute($product)) {
                $this->form_validation->set_message("unique_productname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $product = $this->product_m->get_order_by_product(array("productname" => $this->input->post("productname")));
            if (customCompute($product)) {
                $this->form_validation->set_message("unique_productname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_prodectcategory()
    {
        if ($this->input->post("productcategoryID") == 0) {
            $this->form_validation->set_message("unique_prodectcategory", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }
}
