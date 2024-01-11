<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase extends Admin_Controller {
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
        $this->load->model("purchase_m");
        $this->load->model("user_m");
        $this->load->model("asset_m");
        $this->load->model("vendor_m");
        $this->load->model("invoice_m");
        $this->load->model("assets_product_inout_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('purchase', $language);
    }

    public function index() {
        $this->data['unit'] = array(
            1 => $this->lang->line('purchase_unit_kg'), 
            2 => $this->lang->line('purchase_unit_piece'), 
            3 => $this->lang->line('purchase_unit_other')
        );

        $this->data['purchases'] = $this->purchase_m->get_purchase_with_all();
        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
        $this->data["subview"] = "purchase/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'assetID',
                'label' => $this->lang->line("purchase_assetID"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[128]|callback_unique_asset',
            ),
            array(
                'field' => 'vendorID',
                'label' => $this->lang->line("purchase_vendorID"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_vendor',
            ),
            array(
                'field' => 'purchased_by',
                'label' => $this->lang->line("purchased_by"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_purchase_by',
            ),
            array(
                'field' => 'quantity',
                'label' => $this->lang->line("purchase_quantity"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'unit',
                'label' => $this->lang->line("purchase_unit"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_unit'
            ),
            array(
                'field' => 'purchase_price',
                'label' => $this->lang->line("purchase_price"),
                'rules' => 'trim|required|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'purchase_date',
                'label' => $this->lang->line("purchase_date"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid'
            ),
            array(
                'field' => 'service_date',
                'label' => $this->lang->line("purchase_service_date"),
                'rules' => 'trim|xss_clean|max_length[10]|callback_date_valid'
            ),
            array(
                'field' => 'expire_date',
                'label' => $this->lang->line("purchase_expire_date"),
                'rules' => 'trim|xss_clean|max_length[10]|callback_date_valid'
            ),
            
        );
        return $rules;
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

    public function check_quantity(){
        if ($_POST) {
            $assetID = $this->input->post("assetID");
            $quantity = $this->input->post("quantity");
            $status = $this->input->post("status");
            $single_asset = $this->asset_m->get_single_asset(['assetID' => $this->input->post("assetID")]);
            $asset_quantity = $single_asset->quantity;
            if ($status == 2) {
                $purchase_quantity = $this->asset_m->get_asset_l($assetID);
                if ($purchase_quantity !== false) {
                    $total_quantity = $asset_quantity + $purchase_quantity;
                    if ($quantity > $total_quantity) {
                            $message = [
                                    'status'          => 404,
                                    'message'   => 'Quantity not available. Maximum quantity must be less than or equal to '.$total_quantity
                                ];
                    }else{
                            $message = [
                                'status'          => 200,
                                'message'   => 'Quantity available'
                            ];
                    }
                }else{
                    if ($quantity > $asset_quantity) {
                        $message = [
                                'status'          => 404,
                                'message'   => 'Quantity not available. Maximum quantity must be less than or equal to '.$asset_quantity
                            ];
                    }else{
                        $message = [
                                'status'          => 200,
                                'message'   => 'Quantity available'
                            ];
                    }
                }
            }else{
                if ($quantity > $asset_quantity) {
                    $message = [
                            'status'          => 404,
                            'message'   => 'Quantity not available. Maximum quantity must be less than or equal to '.$asset_quantity
                        ];
                }else{
                    $message = [
                            'status'          => 200,
                            'message'   => 'Quantity available'
                        ];
                }
            }
            
            echo json_encode($message);
        }
    }

    public function add() {
        $usertypeID = $this->session->userdata("usertypeID");
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
        $this->data['users'] = $this->user_m->get_user();
        $this->data['assets'] = $this->asset_m->get_asset();
        $this->data['vendors'] = $this->vendor_m->get_vendor();

        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "purchase/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "assetID"           => $this->input->post("assetID"),
                    "vendorID"          => $this->input->post("vendorID"),
                    "purchased_by"      => $this->input->post("purchased_by"),
                    "quantity"          => $this->input->post("quantity"),
                    "unit"              => $this->input->post("unit"),
                    "purchase_price"    => $this->input->post("purchase_price"),
                );

                //$single_asset   = $this->asset_m->get_single_asset(['assetID' => $this->input->post("assetID")]);
                //$asset_quantity = $single_asset->quantity;
                //$asset_quantity = $asset_quantity + $this->input->post("quantity");
                // Update Asset Quantity
                //$this->asset_m->update_asset(['quantity' => $asset_quantity],$this->input->post("assetID"));

                if($this->input->post('purchased_by')) {
                    $user = $this->user_m->get_user($this->input->post('purchased_by'));
                    $array['usertypeID'] = isset($user->usertypeID) ? $user->usertypeID : 0 ;
                }

                if($this->input->post('purchase_date')) {
                    $array["purchase_date"] 	= date("Y-m-d", strtotime($this->input->post("purchase_date")));
                }

                if($this->input->post('service_date')) {
                    $array["service_date"] 		= date("Y-m-d", strtotime($this->input->post("service_date")));
                }

                if($this->input->post('expire_date')) {
                    $array["expire_date"] 		= date("Y-m-d", strtotime($this->input->post("expire_date")));
                }
                
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');

                if ($usertypeID == 1 || $usertypeID == 5) {
                    $array["status"] = 0;
                } else {
                    $array["status"] = 0;
                }
                $this->purchase_m->insert_purchase($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("purchase/index"));
            }
        } else {
            $this->data["subview"] = "purchase/add";
            $this->load->view('_layout_main', $this->data);
        }
    }


    public function active() {

        if(permissionChecker('purchase_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');

            if($id != '' && $status != '') {

                if((int)$id) {
                    $purchase = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
                    if(customCompute($purchase)) {
                        if($status == 'chacked') { 
                            $this->purchase_m->update_purchase(array( 
                                                    'aprove_status_first' => 1,
                                                    'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'firstAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->purchase_m->update_purchase(array( 
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
        if(permissionChecker('purchase_edit')) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $purchase = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
                    if(customCompute($purchase)) {
                        if($status == 'chacked') { 
                            $this->purchase_m->update_purchase(array( 
                                                    'aprove_status_second' => 1,
                                                    'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                    'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                    'secondAprove_time' => date('Y-m-d H:i:s')
                                                                            ), $id);
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                            $this->purchase_m->update_purchase(array( 
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
    
    
    public function active_delivery() {
        if(permissionChecker('purchase_edit')) {
            if (isset($_GET)) {
               $_POST   =   $_GET;
            }
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if($id != '' && $status != '') {
                if((int)$id) {
                    $purchase = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
                    if(customCompute($purchase)) {


                        if($status == 'chacked') { 
                            $averageprice   =   $this->purchase_m->get_average_price_by_assetID(array('assetID'=>$purchase->assetID)); 
                            
                            $single_asset   =   $this->asset_m->get_single_asset(['assetID' => $purchase->assetID]);
                            $single_vendor  =   $this->vendor_m->get_single_vendor(['vendorID' => $purchase->vendorID]);
                            $totalprice     =   $purchase->quantity*$purchase->purchase_price;
                            $tax_amount     =   ceil(($totalprice*$single_vendor->tax_rate)/100);
                           // var_dump($tax_amount);
                            //var_dump($single_vendor);
                            //exit();
                            $asset_quantity = $single_asset->quantity;
                            $asset_quantity = $asset_quantity + $purchase->quantity;
                            //Update Asset Quantity
                            $this->asset_m->update_asset(['quantity' => $asset_quantity,'average_price' => $averageprice],$purchase->assetID);
                            $this->purchase_m->update_purchase(array( 
                                                                    'aprove_status_delivery'    => 1,
                                                                    'status'                    => 1,
                                                                    'deliveryAprove_userID'     => $this->session->userdata('loginuserID'),
                                                                    'deliveryAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                                    'deliveryAprove_time'       => date('Y-m-d H:i:s')
                                                                            ), $id);
                            $array =    array(
                                                'type'              => 'purchase', 
                                                'productID'         => $purchase->assetID, 
                                                'quantity'          => $purchase->quantity, 
                                                'price'             => $purchase->purchase_price, 
                                                'createuserID'      => $this->session->userdata('loginuserID'), 
                                                'createusertypeID'  => $this->session->userdata('usertypeID'), 
                                                'date'              => date('Y-m-d'), 
                                                'created'           => date('Y-m-d H:i:s'), 
                                             );

                            $this->assets_product_inout_m->insert_assets_product_inout($array);

                            $ledger_array[] = [
                                                "maininvoiceID" => $purchase->purchaseID,
                                                "studentID"     => $purchase->vendorID  ,
                                                "entry_userID"  => $purchase->vendorID,
                                                "entry_type"    => 'vendor',
                                                "classesID"     => $purchase->purchaseID,
                                                "net_fee"       => $purchase->quantity*$purchase->purchase_price,
                                                "refrence_no"   => $single_asset->serial,
                                                'description'   => $single_asset->serial.' Purchase for asset ' ,
                                                "accounts_reg"  => $single_asset->serial, 
                                                "account_credit"=> $this->data["siteinfos"]->asset_purchase_credit, 
                                                "account_debit" => $this->data["siteinfos"]->asset_purchase_debit, 
                                                "feetypeID"     => 9000009,
                                                "date"          => date('Y-m-d'), 
                                                "discount"      => 0, 
                                                ];            
         
                $student_ledgers_ar     =   [];
                $journal_items_ar       =   [];
                foreach ($ledger_array as $in_ar) {
                $journal_entries_id   =     $this->invoice_m->get_journal_entries_id_by_array($in_ar);

                

                $journal_items_ar[]  = array(
                                                'journal'       =>  $journal_entries_id, 
                                                'referenceID'   =>  $in_ar['maininvoiceID'],            
                                                'reference_type'=>  'invoice',            
                                                'account'       =>  $in_ar['account_credit'], 
                                                'description'   =>  $in_ar['description'], 
                                                'feetypeID'     =>  $in_ar['feetypeID'], 
                                                'debit'         =>  0, 
                                                'credit'        =>  $in_ar['net_fee'], 
                                                'entry_type'    =>  'CR', 
                                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                                                'journal'       =>  $journal_entries_id, 
                                                'referenceID'   =>  $in_ar['maininvoiceID'],            
                                                'reference_type'=>  'invoice',            
                                                'feetypeID'     =>  $in_ar['feetypeID'],             
                                                'account'      =>   $in_ar['account_debit'], 
                                                'description'   =>  $in_ar['description'], 
                                                'debit'         =>  $in_ar['net_fee'], 
                                                'entry_type'    =>  'DR', 
                                                'credit'        =>  0, 
                                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'], 
                            'reference_type'           =>  'invoice', 
                            'feetypeID'                =>  $in_ar['feetypeID'], 
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $in_ar['account_credit'], 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  0, 
                            'credit'                   =>  $in_ar['net_fee'], 
                            'balance'                  =>  $in_ar['net_fee'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'], 
                            'reference_type'           =>  'invoice',
                            'feetypeID'                =>  $in_ar['feetypeID'],  
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $in_ar['account_debit'], 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  $in_ar['net_fee'], 
                            'credit'                   =>  0, 
                            'balance'                  =>  $in_ar['net_fee'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                
                                }
                $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                
                            echo 'Success';
                        } elseif($status == 'unchacked') {
                             
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
        $usertypeID = $this->session->userdata("usertypeID");
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
            $this->data['purchase'] = $this->purchase_m->get_single_purchase_with_all(array('purchaseID' => $id));
            if($this->data['purchase']) {
                $this->data['users'] = $this->user_m->get_user();
                $this->data['assets'] = $this->asset_m->get_asset();
                $this->data['vendors'] = $this->vendor_m->get_vendor();

                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/purchase/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "assetID" => $this->input->post("assetID"),
                            "vendorID" => $this->input->post("vendorID"),
                            "purchased_by" => $this->input->post("purchased_by"),
                            "quantity" => $this->input->post("quantity"),
                            "unit" => $this->input->post("unit"),
                            "purchase_price" => $this->input->post("purchase_price"),
                        );

                        if($this->input->post('purchased_by')) {
                            $user = $this->user_m->get_user($this->input->post('purchased_by'));
                            $array['usertypeID'] = isset($user->usertypeID) ? $user->usertypeID : 0 ;
                        }

                        if($this->input->post('purchase_date')) {
                            $array["purchase_date"] 		= date("Y-m-d", strtotime($this->input->post("purchase_date")));
                        } else {
                            $array["purchase_date"] = NULL;
                        }

                        if($this->input->post('service_date')) {
                            $array["service_date"] 		= date("Y-m-d", strtotime($this->input->post("service_date")));
                        } else {
                            $array["service_date"] = NULL;
                        }

                        if($this->input->post('expire_date')) {
                            $array["expire_date"] 		= date("Y-m-d", strtotime($this->input->post("expire_date")));
                        } else {
                            $array["expire_date"] = NULL;
                        }

                        $array["modify_date"] = date("Y-m-d");

                        if ($usertypeID == 1 || $usertypeID == 5) {
                            $array["status"] = 1;
                        } else {
                            $array["status"] = 0;
                        }

                        $this->purchase_m->update_purchase($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("purchase/index"));
                       // dd($array);
                    }
                } else {
                    $this->data["subview"] = "/purchase/edit";
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
        $usertypeID = $this->session->userdata("usertypeID");
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
            $this->data['purchase'] = $this->purchase_m->get_single_purchase_with_all(array('purchaseID' => $id));
            if($this->data['purchase']) {

                $this->data['usertypes']    = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

                 $this->data['createuser']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->create_usertypeID, $this->data['purchase']->create_userID);
                $this->data['fromuser']     = getObjectByUserTypeIDAndUserID($this->data['purchase']->create_usertypeID, $this->data['purchase']->create_userID);
                $this->data['approve_one']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->firstAprove_usertypeID, $this->data['purchase']->firstAprove_userID);
                $this->data['approve_two']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->secondAprove_usertypeID, $this->data['purchase']->secondAprove_userID);

                $this->data['users'] = $this->user_m->get_user();
                $this->data['assets'] = $this->asset_m->get_asset($this->data['purchase']->assetID);
                $this->data['vendor'] = $this->vendor_m->get_vendor($this->data['purchase']->vendorID);

                
                 
                $this->data["subview"] = "/purchase/view";
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
        $usertypeID = $this->session->userdata("usertypeID");
         
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['purchase'] = $this->purchase_m->get_single_purchase_with_all(array('purchaseID' => $id));
            if($this->data['purchase']) {

                $this->data['usertypes']    = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');

                 $this->data['createuser']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->create_usertypeID, $this->data['purchase']->create_userID);
                $this->data['fromuser']     = getObjectByUserTypeIDAndUserID($this->data['purchase']->create_usertypeID, $this->data['purchase']->create_userID);
                $this->data['approve_one']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->firstAprove_usertypeID, $this->data['purchase']->firstAprove_userID);
                $this->data['approve_two']  = getObjectByUserTypeIDAndUserID($this->data['purchase']->secondAprove_usertypeID, $this->data['purchase']->secondAprove_userID);

                $this->data['users']        = $this->user_m->get_user();
                $this->data['assets']       = $this->asset_m->get_asset($this->data['purchase']->assetID);
                $this->data['vendor']       = $this->vendor_m->get_vendor($this->data['purchase']->vendorID);

                
                 
                // $this->data["subview"] = "/purchase/view";
                // $this->load->view('_layout_main', $this->data);

                 $this->reportPDF('requisitionmodule.css', $this->data, 'purchase/print_preview');
                
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
            $this->data['purchase'] = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
            if($this->data['purchase']) {
                $this->purchase_m->delete_purchase($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("purchase/index"));
            } else {
                redirect(base_url("purchase/index"));
            }
        } else {
            redirect(base_url("purchase/index"));
        }
    }

    function status() {
        if(permissionChecker('purchase_edit')) {
            $id = $id = htmlentities(escapeString($this->uri->segment(3)));

            if($id != '') {
                if((int)$id) {
                    $purchase = $this->purchase_m->get_purchase($id);
                    if(customCompute($purchase)) {
                        if($purchase->status == 1) {
                            $this->purchase_m->update_purchase(array('status' => 0), $id);
                        } else {
                            $this->purchase_m->update_purchase(array('status' => 1), $id);
                        }
                        redirect(base_url("purchase/index"));
                    } else {
                        redirect(base_url("purchase/index"));
                    }
                } else {
                    redirect(base_url("purchase/index"));
                }
            } else {
                redirect(base_url("purchase/index"));
            }
        } else {
            redirect(base_url("exceptionpage/index"));
        }
    }

    public function unique_asset() {
        if($this->input->post('assetID') == 0) {
            $this->form_validation->set_message('unique_asset', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_vendor() {
        if($this->input->post('vendorID') == 0) {
            $this->form_validation->set_message('unique_vendor', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_purchase_by() {
        if($this->input->post('purchased_by') == 0) {
            $this->form_validation->set_message('unique_purchase_by', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_unit() {
        if($this->input->post('unit') == 0) {
            $this->form_validation->set_message('unique_unit', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    
}
