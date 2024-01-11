<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkimport extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME:     INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:           INILABS TEAM
| -----------------------------------------------------
| EMAIL:            info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:        RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:          http://inilabs.net
| -----------------------------------------------------
*/
    function __construct() {
        parent::__construct();
        $this->load->model("teacher_m");
        $this->load->model("parents_m");
        $this->load->model("student_m");
        $this->load->model("user_m");
        $this->load->model("book_m");
        $this->load->model("studentrelation_m");
        $this->load->model("section_m");
        $this->load->model("classes_m");
        $this->load->model("studentextend_m");
        $this->load->model("subject_m");
        $this->load->model("studentgroup_m");
        $this->load->model("invoice_m");
        $this->load->model("maininvoice_m");
        $this->load->model("payment_m");
        $this->load->model("weaverandfine_m");
        $this->load->model("globalpayment_m");
        $this->load->model("subjectteacher_m");
        $this->load->model("asset_m");
        $this->load->model("asset_category_m");
        $this->load->model("purchase_m");
        $this->load->model("assets_product_inout_m");
        $this->load->model("product_inout_m");
        $this->load->model("vendor_m");
        $this->load->model("product_m");
        $this->load->model("productpurchase_m");
        $this->load->model("productpurchaseitem_m");
        $this->load->model("productcategory_m");
        $this->load->model("productsupplier_m");
        $this->load->library('csvimport');

        $language = $this->session->userdata('lang');
        $this->lang->load('bulkimport', $language);
    }

    public function index() {
         $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'  
            ],
            'js'  => [ 
                'assets/select2/select2.js'
            ]
        ];
        $this->data['reconcile'] = $this->invoice_m->get_reconcile_data();

        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data['sections'] = $this->section_m->general_get_section();
        $this->data["subview"] = "bulkimport/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function subject_bulkimport() {

        if(isset($_FILES["csvSubject"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvSubject"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvSubject")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                 
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data = $this->upload->data();
                $file_path =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("type", "passmark", "finalmark", "subject", "subject_author", "subject_code", "teachers");

                if ($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $msg = "";
                        $i       = 1;
                        $csv_col = [];
                        foreach ($csv_array as $row) {

                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                 
                                $array              = $this->arrayToPost($row);
                                $singlesubjectCheck = $this->singlesubjectCheck($array);

                                

                                if($singlesubjectCheck['status']) {
                                    
            
            $array_sub = array(
                "classesID"         => $this->input->post("classesID"),
                "sectionID"         => $this->input->post("sectionID"),
                "subject"           => $row['subject'],
                'type'              => $row['type'],
                'passmark'          => $row['passmark'],
                'finalmark'         => $row['finalmark'],
                "subject_author"    => $row['subject_author'],
                "subject_code"      => $row['subject_code'],
                "teacher_name"      => '',
                "create_date"       => date("Y-m-d h:i:s"),
                "modify_date"       => date("Y-m-d h:i:s"),
                "create_userID"     => $this->session->userdata('loginuserID'),
                "create_username"   => $this->session->userdata('username'),
                "create_usertype"   => $this->session->userdata('usertype')
            );
                $this->subject_m->insert_subject($array_sub);
                $subjectID = $this->db->insert_id();

                $teachers = explode(',', $row['teachers']);
                 

                $subjectteacherArray = [];
                if(count($teachers)) {
                    foreach ($teachers as $teacherID) {
                        $subjectteacherArray[] = [
                            'subjectID' => $subjectID,
                            'teacherID' => $teacherID,
                            'classesID' => $this->input->post("classesID"),
                            'sectionID' => $this->input->post("sectionID"),
                        ];
                    }
                }
                  
                if(customCompute($subjectteacherArray)) {
                    $this->subjectteacher_m->insert_batch_subjectteacher($subjectteacherArray);
                }

               
                 
            
                                } else {
                                    $msg .= $i.". ". $row['subject']." is not added! , ";
                                    $msg .= implode(' , ', $singlesubjectCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function teacher_bulkimport() {
        if(isset($_FILES["csvFile"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvFile"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvFile")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data = $this->upload->data();
                $file_path =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Designation", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Jod", "Username", "Password");

                if ($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $msg = "";
                        $i       = 1;
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array              = $this->arrayToPost($row);
                                $singleteacherCheck = $this->singleteacherCheck($array);

                                if($singleteacherCheck['status']) {
                                    $insert_data = array(
                                        'name'            => $row['Name'],
                                        'designation'     => $row['Designation'],
                                        'dob'             => $this->trim_required_convertdate($row['Dob']),
                                        'sex'             => $row['Gender'],
                                        'religion'        => $row['Religion'],
                                        'email'           => $row['Email'],
                                        'phone'           => $row['Phone'],
                                        'address'         => $row['Address'],
                                        'jod'             => $this->trim_required_convertdate($row['Jod']),
                                         'tID'          => $this->teacher_m->get_tID_by_date($this->trim_required_convertdate($row['Jod'])),
                                        'username'        => $row['Username'],
                                        'password'        => $this->teacher_m->hash($row['Password']),
                                        'usertypeID'      => 2,
                                        'photo'           => 'default.png',
                                        "create_date"     => date("Y-m-d h:i:s"),
                                        "modify_date"     => date("Y-m-d h:i:s"),
                                        "create_userID"   => $this->session->userdata('loginuserID'),
                                        "create_username" => $this->session->userdata('username'),
                                        "create_usertype" => $this->session->userdata('usertype'),
                                        "active"          => 1,
                                    );
                                    $this->usercreatemail($row['Email'], $row['Username'], $row['Password']);
                                    $this->teacher_m->insert_teacher($insert_data);
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleteacherCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

   

    public function asset_bulkimport() {
        if(isset($_FILES["csvFile"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvFile"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvFile")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data = $this->upload->data();
                $file_path =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Code", "Items", "Status", "Category", "Qty");

                if ($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $msg = "";
                        $i       = 1; 
                        $csv_col = [];
                        $cats = $this->asset_category_m->get_asset_category();
                        $cat_arr    =   array();
                        foreach ($cats as $c) {
                             $cat_arr[$c->category]    =   $c->asset_categoryID;
                        }
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array              = $this->arrayToPost($row);
                               // var_dump($array);
                               // exit();
                                $singleteacherCheck = $this->singleassetCheck($array);

                                if($singleteacherCheck['status']) {
                                    $insert_data = array( 
                                        'serial'            => $row['Code'],
                                        'description'       => $row['Items'], 
                                        'quantity'          => 0,
                                        'asset_categoryID'  => $cat_arr[$row['Category']], 
                                        "create_date"     => date("Y-m-d h:i:s"),
                                        "modify_date"     => date("Y-m-d h:i:s"),
                                        "create_userID"   => $this->session->userdata('loginuserID'), 
                                        "create_usertypeID" => $this->session->userdata('usertypeID'), 
                                    ); 
                                    $this->asset_m->insert_asset($insert_data);
                                    $assetID = $this->db->insert_id();

                                    if ($insert_data['quantity']>0) {
                                      $array = array(
                                                "assetID"           => $assetID,
                                                "vendorID"          => 0,
                                                "purchased_by"      => $this->session->userdata('loginuserID'),
                                                "quantity"          => $insert_data['quantity'],
                                                "unit"              => 0,
                                                "purchase_price"    => 0,
                                                'aprove_status_first' => 1,
                                                'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                'firstAprove_time' => date('Y-m-d H:i:s'),
                                                'aprove_status_second' => 1,
                                                'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                'secondAprove_time' => date('Y-m-d H:i:s')
                                            );

                                          
                                             
                                            $array['usertypeID'] =$this->session->userdata('usertypeID');
                                         

                                        
                                            $array["purchase_date"]     = date("Y-m-d");
                                         

                                            $array["service_date"]      = date("Y-m-d");
                                         
                                            $array["expire_date"]       = date("Y-m-d");
                                           
                                            
                                            $array["create_date"] = date("Y-m-d");
                                            $array["modify_date"] = date("Y-m-d");
                                            $array["create_userID"] = $this->session->userdata('loginuserID');
                                            $array["create_usertypeID"] = $this->session->userdata('usertypeID');
                             
                                            $array["status"] = 1;
                                           
                                            $this->purchase_m->insert_purchase($array);

                                            $purchaseID = $this->db->insert_id();

                                            $post = array(
                                                        'status'=>'chacked',
                                                        'id'=>$purchaseID 
                                                        );

                                           
            $id     = $purchaseID;
            $status = 'chacked';
            if($id != '' && $status != '') {
                if((int)$id) {
                    $purchase = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
                    if(customCompute($purchase)) {


                        if($status == 'chacked') { 
                            $averageprice   =   $this->purchase_m->get_average_price_by_assetID(array('assetID'=>$purchase->assetID)); 
                            
                            $single_asset   =   $this->asset_m->get_single_asset(['assetID' => $purchase->assetID]);
                            $single_vendor  =   $this->vendor_m->get_single_vendor(['vendorID' => $purchase->vendorID]);
                            $totalprice     =   $purchase->quantity*$purchase->purchase_price;
                            $tax_amount     =   0;
                            if (customCompute($single_vendor)) {
                                $tax_amount     =   ceil(($totalprice*$single_vendor->tax_rate)/100);
                            }
                            
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
                
                            //echo 'Success';
                        } elseif($status == 'unchacked') {
                             
                        } else {
                            //echo "Error";
                        }
                    } else {
                        //echo 'Error';
                    }
                } else {
                   // echo "Error";
                }
            }
                                                                }

                                } else {
                                    $msg .= $i.". ". $row['Items']." is not added! , ";
                                    $msg .= implode(' , ', $singleteacherCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

   

   

    public function product_bulkimport() {

        if(isset($_FILES["csvFile"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvFile"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvFile")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data = $this->upload->data();
                $file_path =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array(  "Items", "Category", "Qty", "Buying", "Selling");

                if ($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $msg = "";
                        $i       = 1; 
                        $csv_col = [];
                        $cats = $this->productcategory_m->get_productcategory();
                        $cat_arr    =   array();
                        foreach ($cats as $c) {
                             $cat_arr[$c->productcategoryname]    =   $c->productcategoryID;
                        }

                        ;
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                             
                            $match = array_diff($column_headers, $csv_col);
                            
                            if (customCompute($match) <= 0) {
                                $array              = $this->arrayToPost($row);
                              


                                $singleteacherCheck = $this->singleproductCheck($array);
                                  
                                if($singleteacherCheck['status']) {
                                    $insert_data = array(  
                                        'productname'           => $row['Items'],  
                                        'productbuyingprice'    => $row['Buying'],  
                                        'productsellingprice'   => $row['Selling'],  
                                        'productcategoryID'     => $cat_arr[$row['Category']], 
                                        "create_date"           => date("Y-m-d h:i:s"),
                                        "modify_date"           => date("Y-m-d h:i:s"),
                                        "create_userID"         => $this->session->userdata('loginuserID'), 
                                        "create_usertypeID"     => $this->session->userdata('usertypeID'), 
                                    ); 
                                    $this->product_m->insert_product($insert_data);
                                    $assetID = $this->db->insert_id();

                                    if ($row['Qty']>0) {
                                      $schoolyearID = $this->session->userdata('defaultschoolyearID');
                                            $array = array(
                                                'schoolyearID' => $schoolyearID,
                                                "productsupplierID"          => 0,
                                                "productwarehouseID"          => 0,
                                                "productpurchasereferenceno"          => $assetID,
                                                "productpurchasedate" => date('Y-m-d'),
                                                "productpurchasedescription" => '',
                                                "productpurchasestatus" => 0,
                                                "productpurchaserefund" => 0,
                                                'aprove_status_first' => 1,
                                                'firstAprove_userID' => $this->session->userdata('loginuserID'),
                                                'firstAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                'firstAprove_time' => date('Y-m-d H:i:s'),
                                                'aprove_status_second' => 1,
                                                'secondAprove_userID' => $this->session->userdata('loginuserID'),
                                                'secondAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                'secondAprove_time' => date('Y-m-d H:i:s')
                                            );
 
                                           
                                            
                                            $array["create_date"] = date("Y-m-d");
                                            $array["modify_date"] = date("Y-m-d");
                                            $array["create_userID"] = $this->session->userdata('loginuserID');
                                            $array["create_usertypeID"] = $this->session->userdata('usertypeID');
                              
                                           
                                            $this->productpurchase_m->insert_productpurchase($array);

                                            $purchaseID = $this->db->insert_id();


                                             $productpurchaseitem[] = array(
                                                'schoolyearID'              => $schoolyearID,
                                                'productpurchaseID'         => $purchaseID,
                                                'productID'                 => $assetID,
                                                'productpurchaseunitprice'  => $row['Buying'],
                                                'productpurchasequantity'   => $row['Qty'],
                                            ); 
                                             $this->productpurchaseitem_m->insert_batch_productpurchaseitem($productpurchaseitem);
                                            $post = array(
                                                        'status'=>'chacked',
                                                        'id'=>$purchaseID 
                                                        );

                                           
            $id     = $purchaseID;
            $status = 'chacked';
            if($id != '' && $status != '') {
                if((int)$id) {
                    $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id));
                    if(customCompute($productpurchase)) {


                        if($status == 'chacked') { 
                            $items = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array( 'productpurchaseID' => $id));
                            
                            foreach ($items as $p) {
                                $averageprice   =   $this->productpurchase_m->get_average_price_by_productID(array('productID'=>$p->productID));
                                $this->product_m->update_product([ 'product_average_price' => $averageprice],$p->productID);
                                 $array =    array(
                                                'type'              => 'purchase', 
                                                'productID'         => $p->productID, 
                                                'quantity'          => $p->productpurchasequantity, 
                                                'price'             => $p->productpurchaseunitprice, 
                                                'createuserID'      => $this->session->userdata('loginuserID'), 
                                                'createusertypeID'  => $this->session->userdata('usertypeID'), 
                                                'date'              => date('Y-m-d'), 
                                                'created'           => date('Y-m-d H:i:s'), 
                                             );

                                $this->product_inout_m->insert_product_inout($array);
                            }

                              
                            $schoolyearID = $this->session->userdata('defaultschoolyearID');
                            $totalprice     =   $this->productpurchaseitem_m->get_productpurchaseitem_sum(array('productpurchaseID' =>$id, 'schoolyearID' => $schoolyearID))->result; 
                            $productsupplier = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $productpurchase->productsupplierID));
                            $tax_amount     =   0;
                            if (customCompute($productsupplier)) {
                                $tax_amount     =   ceil(($totalprice*$productsupplier->tax_rate)/100);
                            }
                            
                            $this->productpurchase_m->update_productpurchase(array( 
                                                                    'aprove_status_delivery'    => 1,
                                                                    'productpurchasestatus'     => 1,
                                                                    'deliveryAprove_userID'     => $this->session->userdata('loginuserID'),
                                                                    'deliveryAprove_usertypeID' => $this->session->userdata('usertypeID'),
                                                                    'deliveryAprove_time'       => date('Y-m-d H:i:s')
                                                                            ), $id);
                           

                            $ledger_array[] = [
                                                "maininvoiceID" => $productpurchase->productpurchaseID,
                                                "studentID"     => $productpurchase->productsupplierID  ,
                                                "entry_userID"  => $productpurchase->productsupplierID,
                                                "entry_type"    => 'supplier',
                                                "classesID"     => $productpurchase->productpurchaseID,
                                                "net_fee"       => $totalprice,
                                                "refrence_no"   => $productpurchase->productpurchasereferenceno,
                                                'description'   => $productpurchase->productpurchasereferenceno.' Purchase for asset ' ,
                                                "accounts_reg"  => $productpurchase->productpurchasereferenceno, 
                                                "account_credit"=> $this->data["siteinfos"]->asset_purchase_credit, 
                                                "account_debit" => $this->data["siteinfos"]->asset_purchase_debit, 
                                                "feetypeID"     => 9000099,
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
                
                           // echo 'Success';
                        } elseif($status == 'unchacked') {
                             
                        } else {
                           // echo "Error";
                        }
                    } else {
                       // echo 'Error';
                    }
                } else {
                  //  echo "Error";
                }
            }
                                                                }

                                } else {
                                    $msg .= $i.". ". $row['Items']." is not added! , ";
                                    $msg .= implode(' , ', $singleteacherCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function parent_bulkimport() {
        if(isset($_FILES["csvParent"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvParent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvParent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Father Name", "Mother Name", "Father Profession","Mother Profession", "Email", "Phone", "Address", "Username", "Password");

                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match       = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singleparentCheck = $this->singleparentCheck($array);
                                if($singleparentCheck['status']) {
                                    $insert_data = array(
                                        'name'              => $row['Name'],
                                        'father_name'       => $row['Father Name'],
                                        'mother_name'       => $row['Mother Name'],
                                        'father_profession' => $row['Father Profession'],
                                        'mother_profession' => $row['Mother Profession'],
                                        'email'             => $row['Email'],
                                        'phone'             => $row['Phone'],
                                        'photo'             => 'default.png',
                                        'address'           => $row['Address'],
                                        'username'          => $row['Username'],
                                        'password'          => $this->parents_m->hash($row['Password']),
                                        'usertypeID'        => 2,
                                        'photo'             => 'default.png',
                                        "create_date"       => date("Y-m-d h:i:s"),
                                        "modify_date"       => date("Y-m-d h:i:s"),
                                        "create_userID"     => $this->session->userdata('loginuserID'),
                                        "create_username"   => $this->session->userdata('username'),
                                        "create_usertype"   => $this->session->userdata('usertype'),
                                        "active"            => 1,
                                    );
                                    // For Email
                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                    $this->parents_m->insert_parents($insert_data);
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleparentCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }
    
    public function user_bulkimport() {
        if(isset($_FILES["csvUser"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvUser"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvUser")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Jod", "Username", "Password", "Usertype");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singleuserCheck = $this->singleuserCheck($array);
                                if($singleuserCheck['status']) {
                                    $dob = $this->trim_required_convertdate($row['Dob']);
                                    $jod = $this->trim_required_convertdate($row['Jod']);
                                    $insert_data = array(
                                        'name'            => $row['Name'],
                                        'dob'             => $dob,
                                        'sex'             => $row['Gender'],
                                        'religion'        => $row['Religion'],
                                        'email'           => $row['Email'],
                                        'phone'           => $row['Phone'],
                                        'address'         => $row['Address'],
                                        'jod'             => $jod,
                                        'photo'           => 'default.png',
                                        'username'        => $row['Username'],
                                        'password'        => $this->user_m->hash($row['Password']),
                                        'usertypeID'      => $this->trim_check_usertype($row['Usertype']),
                                        "create_date"     => date("Y-m-d h:i:s"),
                                        "modify_date"     => date("Y-m-d h:i:s"),
                                        "create_userID"   => $this->session->userdata('loginuserID'),
                                        "create_username" => $this->session->userdata('username'),
                                        "create_usertype" => $this->session->userdata('usertype'),
                                        "active"   => 1,
                                    );
                                    $this->user_m->insert_user($insert_data);
                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleuserCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function book_bulkimport() {
        if(isset($_FILES["csvBook"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvBook"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvBook")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array(
                                        "accession_number",
                                        "ddc_number",
                                        "book",
                                        "subject_code",
                                        "author",
                                        "book_category",
                                        "book_publisher", 
                                        "book_pages",
                                        "book_year",
                                        "book_binding",
                                        "book_source",
                                        "price",
                                        "book_volume",
                                        "quantity",
                                        "classesID",
                                        "book_remarks",
                                        "rack" 
                                        );
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        $insert_data_ar  =  [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);


                                $singlebookCheck = $this->singlebookCheck($array);
                                
                                if($singlebookCheck['status']) {
                                    $insert_data_ar[] = array(
                                        'accession_number'  => $row['accession_number'],
                                        'ddc_number'        => $row['ddc_number'],
                                        'book'              => $row['book'],
                                        'subject_code'      => $row['subject_code'],
                                        'author'            => $row['author'],
                                        'book_category'     => $row['book_category'], 
                                        'book_publisher'    => $row['book_publisher'],
                                        'book_pages'        => $row['book_pages'],
                                        'book_year'         => $row['book_year'],
                                        'book_binding'      => $row['book_binding'],
                                        'book_source'       => $row['book_source'],
                                        'price'             => $row['price'],
                                        'book_volume'       => $row['book_volume'],
                                        'quantity'          => $row['quantity'],
                                        'classesID'         => $row['classesID'],
                                        'book_remarks'      => $row['book_remarks'],
                                        'rack'              => $row['rack'],
                                        'due_quantity'      => 0,
                                    );
                              
                                } else {
                                    $msg .= $i.". ". $row['book']." is not added! , ";
                                    $msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        $this->book_m->insert_batch_book($insert_data_ar);
                              
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                         
                         $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                         redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }

     public function user_status_bulkimport() {
        if(isset($_FILES["csvstdstatus"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvstdstatus"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvstdstatus")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Accounts no", "Status");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);
                                
                                $data = array(
                                    'accounts_reg'     => $row['Accounts no'],
                                    'active' => $row['Status']
                                );
                                $student = $this->student_m->get_student_select('studentID', ['accounts_reg'     => $row['Accounts no']]);
                                if (customCompute($student)) {
                                    $this->student_m->update_student(array('active' => $row['Status']), $student->studentID);
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function get_reconcile_data(){
        $duplicate_record = $this->invoice_m->get_reconcile_duplicate_data();

        if (count($duplicate_record)) {
            foreach ($duplicate_record as $dr) {
                $data = array(
                    'status' => 0,
                    'challan_no_status' => 3,
                     );
            $this->db->set($data);
            $this->db->where('challan_no', $dr->challan_no);
            $this->db->update('upload_cpv_bpv');
            }
        }

        $this->data['reconcile']        = $this->invoice_m->get_reconcile_data();
        $this->data['bank_accounts']    = $this->invoice_m->get_bank_accounts();
        if (count($this->data['reconcile'])) {
           
        $this->data['uploadrecord'] = $this->invoice_m->get_upload_record_data($this->data['reconcile'][0]->cpv_bpv_recordID);
        }
        $this->data["subview"] = "bulkimport/reconcile";
        $this->load->view('_layout_main', $this->data);
    }

    public function reconcile_bulkimport(){
        if($_POST){
            $data = $this->invoice_m->get_reconcile_data(1);
            $msg     = "";
            $journal_items_ar       =   [];
            $student_ledgers_ar     =   [];

            foreach ($data as $row){
                $id             = $row->id;
                $student_id     = $row->student_id;
                $student_roll   = $row->student_roll;
                $payment_date   = $row->payment_date;
                $challan_no     = $row->challan_no;
                $amount         = $row->amount;
                $paymenttype    = $row->pay_type;
                $pay_type       = $row->payment_type;
                $student        = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $student_id, 'roll' => $student_roll]);
                if (customCompute($student)){
                    $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$challan_no);
                    $excel_amount = $amount;
                    $r_amount = 0;
                    $p_amount = $excel_amount;
                    $date = $payment_date;
                    if(customCompute($maininvoice_result)){
                        foreach ($maininvoice_result as $invoice){
                            if (($p_amount == ceil($invoice->maininvoicenet_fee)) && $invoice->maininvoicestatus != 2) {
                      
                                $p_amount = $p_amount - ceil($invoice->maininvoicenet_fee);
                                
                               
                                
                                $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));

                                //var_dump($this->data['invoices']);
                                
                                $globalpayment = array(
                                            'classesID'             => $student->classesID,
                                            'sectionID'             => $student->sectionID,
                                            'studentID'             => $student->studentID,
                                            'clearancetype'         => 'partial',
                                            'invoicename'           => $student->registerNO .'-'. $student->name,
                                            'invoicedescription'    => '',
                                            'paymentyear'           => date("Y", strtotime($date)),
                                            'schoolyearID'          => 1,
                                        );
                                $this->globalpayment_m->insert_globalpayment($globalpayment);
                                $globalLastID = $this->db->insert_id();
                                $paymentArray = array(
                                                'invoiceID'         => $this->data['invoices'][0]->invoiceID,
                                                'schoolyearID'      => 1,
                                                'studentID'         => $student->studentID,
                                                'paymentamount'     => ceil($invoice->maininvoicenet_fee),
                                                'paymenttype'       => $paymenttype,
                                                'paymentdate'       => $date,
                                                'paymentday'        => date("d", strtotime($date)),
                                                'paymentmonth'      => date("m", strtotime($date)),
                                                'paymentyear'       => date("Y", strtotime($date)),
                                                'userID'            => $this->session->userdata('loginuserID'),
                                                'usertypeID'        => $this->session->userdata('usertypeID'),
                                                'uname'             => $this->session->userdata('name'),
                                                'transactionID'     => 'CASHANDCHEQUE'.random19(),
                                                'globalpaymentID'   => $globalLastID,
                                            );
                               
                                $this->payment_m->insert_payment($paymentArray);
                                $paymentLastID = $this->db->insert_id();

                                 if ($invoice->maininvoicestatus == 1) {
                                    $main_net = $invoice->maininvoicetotal_fee - $invoice->maininvoice_discount;
                                    $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date, 'maininvoicenet_fee' => $main_net],$invoice->maininvoiceID);
                                }else{
                                     $main_net = $invoice->maininvoicenet_fee;
                                    $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date],$invoice->maininvoiceID);
                                }


                $journal_entries_id   =     $this->invoice_m->journal_entries_id(array('studentID'=>$paymentArray['studentID']));

                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $paymentLastID,
                            'reference_type'=>  'payment',
                            'account'       =>  $this->data['siteinfos']->student_cr_account, 
                            'description'   =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'feetypeID'     =>  $this->data['invoices'][0]->feetypeID, 
                            'debit'         =>  0, 
                            'credit'        =>  $paymentArray['paymentamount'], 
                            'entry_type'    =>  'CR', 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $paymentLastID,
                            'reference_type'=>  'payment',
                            'feetypeID'     =>  $this->data['invoices'][0]->feetypeID,
                            'account'       =>  $this->input->post("chart_account_id"), 
                            'description'   =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'         =>  $paymentArray['paymentamount'], 
                            'entry_type'    =>  'DR', 
                            'credit'        =>  0, 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $paymentLastID, 
                            'reference_type'           =>  'payment', 
                            'feetypeID'                =>  $this->data['invoices'][0]->feetypeID, 
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $this->data['siteinfos']->student_cr_account, 
                            'vr_no'                    =>  $this->data['invoices'][0]->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'                    =>  0, 
                            'credit'                   =>  $paymentArray['paymentamount'], 
                            'balance'                  =>  $paymentArray['paymentamount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $paymentLastID, 
                            'reference_type'           =>  'payment',
                            'feetypeID'                =>  $this->data['invoices'][0]->feetypeID,  
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $this->input->post("chart_account_id"), 
                            'vr_no'                    =>  $this->data['invoices'][0]->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'                    =>  $paymentArray['paymentamount'], 
                            'credit'                   =>  0, 
                            'balance'                  =>  $paymentArray['paymentamount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                 

                               
                  
                                $paidstatus = 2;
                                $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'paid'), $globalLastID);
                                $this->invoice_m->delete_cpv_bpv($id);

                            }else if ($p_amount < ceil($invoice->maininvoicenet_fee) && $p_amount > 0 && $invoice->maininvoicestatus != 2) {
                                
                                $this->maininvoice_m->update_maininvoice(['maininvoicenet_fee' => $invoice->maininvoicenet_fee - $p_amount],$invoice->maininvoiceID);
                                 $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 1, 'maininvoicecreate_date' => $date], $invoice->maininvoiceID);
                                 $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                
                                $globalpayment = array(
                                            'classesID'             => $student->classesID,
                                            'sectionID'             => $student->sectionID,
                                            'studentID'             => $student->studentID,
                                            'clearancetype'         => 'partial',
                                            'invoicename'           => $student->registerNO .'-'. $student->name,
                                            'invoicedescription'    => '',
                                            'paymentyear'           => date("Y", strtotime($date)),
                                            'schoolyearID'          => 1,
                                        );
                                $this->globalpayment_m->insert_globalpayment($globalpayment);
                                $globalLastID = $this->db->insert_id();
                                $paymentArray = array(
                                                'invoiceID'         => $this->data['invoices'][0]->invoiceID,
                                                'schoolyearID'      => 1,
                                                'studentID'         => $student->studentID,
                                                'paymentamount'     => $p_amount,
                                                'paymenttype'       => $paymenttype,
                                                'paymentdate'       => $date,
                                               'paymentday'         => date("d", strtotime($date)),
                                                'paymentmonth'      => date("m", strtotime($date)),
                                                'paymentyear'       => date("Y", strtotime($date)),
                                                'userID'            => $this->session->userdata('loginuserID'),
                                                'usertypeID'        => $this->session->userdata('usertypeID'),
                                                'uname'             => $this->session->userdata('name'),
                                                'transactionID'     => 'CASHANDCHEQUE'.random19(),
                                                'globalpaymentID'   => $globalLastID,
                                            );
                                $this->payment_m->insert_payment($paymentArray);
                                $paymentLastID = $this->db->insert_id();

                                $journal_entries_id   =     $this->invoice_m->journal_entries_id(array('studentID'=>$paymentArray['studentID']));

                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $paymentLastID,
                            'reference_type'=>  'payment',
                            'account'       =>  $this->data['siteinfos']->student_cr_account, 
                            'description'   =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'feetypeID'     =>  99999, 
                            'debit'         =>  0, 
                            'credit'        =>  $paymentArray['paymentamount'], 
                            'entry_type'    =>  'CR', 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $paymentLastID,
                            'reference_type'=>  'payment',
                            'feetypeID'     =>  99999,
                            'account'       =>  $this->input->post("chart_account_id"), 
                            'description'   =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'         =>  $paymentArray['paymentamount'], 
                            'entry_type'    =>  'DR', 
                            'credit'        =>  0, 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $paymentLastID, 
                            'reference_type'           =>  'payment', 
                            'feetypeID'                =>  99999, 
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $this->data['siteinfos']->student_cr_account, 
                            'vr_no'                    =>  $this->data['invoices'][0]->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'                    =>  0, 
                            'credit'                   =>  $paymentArray['paymentamount'], 
                            'balance'                  =>  $paymentArray['paymentamount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $paymentLastID, 
                            'reference_type'           =>  'payment',
                            'feetypeID'                =>  99999,  
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $this->input->post("chart_account_id"), 
                            'vr_no'                    =>  $this->data['invoices'][0]->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$this->data['invoices'][0]->refrence_no, 
                            'debit'                    =>  $paymentArray['paymentamount'], 
                            'credit'                   =>  0, 
                            'balance'                  =>  $paymentArray['paymentamount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                 


                                $p_amount = $p_amount - ceil($invoice->maininvoicenet_fee);
                                $paidstatus = 1;
                                $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'partial'), $globalLastID);
                                $this->invoice_m->delete_cpv_bpv($id);
                            }
                        }
                    }

                    
                }
            }
            if($msg != "") {
                $this->session->set_flashdata('msg', $msg);
            }

            $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
            $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );
            $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
            redirect(base_url("bulkimport/get_reconcile_data"));
            // exit();
        }else{
            $this->data["subview"] = "bulkimport/reconcile";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function reconcile_rollback(){
        if($_POST){

            $cpv_bpv_recordID = $this->input->post('cpv_bpv_recordID');
            $upload_data = $this->invoice_m->get_upload_record_by_array(array('cpv_bpv_recordID' => $cpv_bpv_recordID ))[0];
                       
            if(file_exists(FCPATH.'uploads/csv/'.$upload_data->uploadFileName)) {
                    unlink(FCPATH.'uploads/csv/'.$upload_data->uploadFileName);
                     
                }else{

                    
                }
             
            $this->invoice_m->delete_cpv_bpv_by_cpv_bpv_recordID($cpv_bpv_recordID);
            $this->invoice_m->delete_upload_by_cpv_bpv_recordID($cpv_bpv_recordID);
             
            $this->session->set_flashdata('success', 'Entries successfully roll backed');
            redirect(base_url("bulkimport/get_reconcile_data"));
            // exit();
        }else{
            $this->data["subview"] = "bulkimport/reconcile";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function bpv_new_bulkimport(){
        if(isset($_FILES["bpvStudent"])){
            $paymenttype = $this->input->post('paymenttype');
            $pay_type = $this->input->post('pay_type');
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["bpvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("bpvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            }else{
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Student ID", "Date", "Student Roll", "Challan No", "Amount");


                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)){
                    if(customCompute($csv_array)){
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];

                        foreach ($csv_array as $row){
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0){
                                $array           = $this->arrayToPost($row);
                                $check = $this->invoice_m->check_cpv_bpv($array['student_id'],$array['student_roll'],$array['challan_no']);
                                
                                if ($check > 0) {
                                    $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id'], 'roll' => $array['student_roll']]);
                                    if (customCompute($student)){
                                        $excel_date = $array['date'];
                                        $dates = explode('/', $excel_date);
                                        if (isset($dates[0]) && isset($dates[1]) && isset($dates[2])){
                                            $dates = $dates[2].'-'.$dates[1].'-'.$dates[0];
                                            $date = date("Y-m-d", strtotime($dates));
                                        
                                            $excel_amount = $array['amount'];
                                            $r_amount = 0;
                                            $p_amount = $excel_amount;
                                            $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$array['challan_no']);
                                            if(customCompute($maininvoice_result)){
                                                    foreach ($maininvoice_result as $invoice){
                                                        if ($p_amount > ceil($invoice->maininvoicenet_fee)){
                                                            // $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],0);
                                                            $this->invoice_m->update_upload_cpv_bpv(['student_id' => $array['student_id'],'payment_date' => $date, 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no'],'amount' => $array['amount'],'status' => 0],['student_id' => $array['student_id'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no']]);
                                                        }else{
                                                            // $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],1);
                                                            $this->invoice_m->update_upload_cpv_bpv(['student_id' => $array['student_id'],'payment_date' => $date, 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no'],'amount' => $array['amount'],'status' => 1],['student_id' => $array['student_id'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no']]);
                                                        }
                                                    }
                                            }else{
                                                // $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],0);
                                                $this->invoice_m->update_upload_cpv_bpv(['student_id' => $array['student_id'],'payment_date' => $date, 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no'],'amount' => $array['amount'],'status' => 0],['student_id' => $array['student_id'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no']]);
                                            }
                                        }else{
                                            $this->invoice_m->update_upload_cpv_bpv(['student_id' => $array['student_id'],'payment_date' => $array['date'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no'],'amount' => $array['amount'],'status' => 0],['student_id' => $array['student_id'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no']]);
                                            // $this->invoice_m->upload_cpv_bpv($array['student_id'],$array['date'],$array['student_roll'],$array['challan_no'],$array['amount'],0);
                                        }
                                    }else{
                                        $this->invoice_m->update_upload_cpv_bpv(['student_id' => $array['student_id'],'payment_date' => $array['date'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no'],'amount' => $array['amount'],'status' => 0],['student_id' => $array['student_id'], 'student_roll' => $array['student_roll'], 'challan_no' => $array['challan_no']]);
                                    }
                                }else{
                                    $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id'], 'roll' => $array['student_roll']]);
                                        if (customCompute($student)){
                                            $excel_date = $array['date'];
                                            $dates = explode('/', $excel_date);
                                            if (isset($dates[0]) && isset($dates[1]) && isset($dates[2])){
                                                $dates = $dates[2].'-'.$dates[1].'-'.$dates[0];
                                                $date = date("Y-m-d", strtotime($dates));
                                            
                                                $excel_amount = $array['amount'];
                                                $r_amount = 0;
                                                $p_amount = $excel_amount;
                                                $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$array['challan_no']);
                                                if(customCompute($maininvoice_result)){
                                                    foreach ($maininvoice_result as $invoice){
                                                        if ($p_amount > ceil($invoice->maininvoicenet_fee)){
                                                            $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],0,$paymenttype,$pay_type);
                                                        }else{
                                                            $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],1,$paymenttype,$pay_type);
                                                        }
                                                    }
                                                }else{
                                                    $this->invoice_m->upload_cpv_bpv($array['student_id'],$date,$array['student_roll'],$array['challan_no'],$array['amount'],0,$paymenttype,$pay_type);
                                                }
                                            }else{
                                                $this->invoice_m->upload_cpv_bpv($array['student_id'],$array['date'],$array['student_roll'],$array['challan_no'],$array['amount'],0,$paymenttype,$pay_type);
                                            }
                                        }else{
                                            $this->invoice_m->upload_cpv_bpv($array['student_id'],$array['date'],$array['student_roll'],$array['challan_no'],$array['amount'],0,$paymenttype,$pay_type);
                                        }
                                }

                                
                            }else{
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }

                        }

                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/get_reconcile_data"));

                    }else{
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else{
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }


    public function bpv_new_bulkimport_rollback(){
        if(isset($_FILES["bpvStudent"])){
            $paymenttype             = $this->input->post('paymenttype');
            $pay_type                = $this->input->post('pay_type');
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = 'cpv-bpv-'.random19().'-'.$_FILES["bpvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("bpvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            }else{
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];

                 

                $column_headers = array("Student ID", "Date", "Student Roll", "Challan No", "Amount");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)){
                    if(customCompute($csv_array)){

                   
                $imp_rec_array  = array(
                                'createUserID'      => $this->session->userdata('loginuserID'),
                                'createUsertypeID'  => $this->session->userdata('usertypeID'),
                                'createUsername'    => $this->session->userdata('username'),
                                'uploadFileName'    => $file_data['file_name'],
                                'total_record'      => count($csv_array),
                                         );

                $this->db->insert('bpv_cpv_upload_record',$imp_rec_array);
                 $cpv_bpv_recordID  = $this->db->insert_id();

                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                         
                        foreach ($csv_array as $row){
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                    if (customCompute($match) <= 0){
                        $array           = $this->arrayToPost($row);
                         
                        
                       
                            $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id'], 'roll' => $array['student_roll']]);
                                if (customCompute($student)){
                                    $excel_date = $array['date'];
                                    $dates = explode('/', $excel_date);
                                    if (isset($dates[0]) && isset($dates[1]) && isset($dates[2])){
                                        $dates = $dates[2].'-'.$dates[1].'-'.$dates[0];
                                        // $dates = date("Y-m-d", strtotime($excel_date));
                                        $excel_amount = $array['amount'];
                                        $r_amount = 0;
                                        $p_amount = $excel_amount;
                                        $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$array['challan_no']);
                                        if(customCompute($maininvoice_result)){
                                            foreach ($maininvoice_result as $invoice){
                                                if ($p_amount == ceil($invoice->maininvoicenet_fee ) and $invoice->maininvoicestatus==0 ){
                                                     
                                    if ($invoice->maininvoicedeleted_at==0) {
                                         $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 1,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status'=> 1,
                                                'amount'            => $array['amount'], 
                                                'invoice_amount'    => $invoice->maininvoicenet_fee, 
                                                'maininvoiceID'     => $invoice->maininvoiceID, 
                                                'amount_status'     => 1, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 4, 
                                                'status'            => 0, 
                                                         );
                                    }else{
                                         $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 1,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status'=> 1,
                                                'amount'            => $array['amount'], 
                                                'invoice_amount'    => $invoice->maininvoicenet_fee, 
                                                'maininvoiceID'     => $invoice->maininvoiceID, 
                                                'amount_status'     => 1, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 1, 
                                                'status'            => 1, 
                                                         );
                                    }
                                    
                                   

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array);
                                                }else if (  $invoice->maininvoicestatus!=0){
                                                     


                                     $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 1,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status' => 1,
                                                'amount'            => $array['amount'], 
                                                'invoice_amount'    => $invoice->maininvoicenet_fee,
                                                'maininvoiceID'     => $invoice->maininvoiceID,  
                                                'amount_status'     => 3, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 1, 
                                                'status'            => 0, 
                                                         );

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array);
                                                }else{
                                                     


                                     $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 1,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status' => 1,
                                                'amount'            => $array['amount'], 
                                                'invoice_amount'    => $invoice->maininvoicenet_fee,
                                                'maininvoiceID'     => $invoice->maininvoiceID,  
                                                'amount_status'     => 2, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 1, 
                                                'status'            => 0, 
                                                         );

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array);
                                                }
                                            }
                                        }else{
                                            

                                     $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 1,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status' => 1,
                                                'amount'            => $array['amount'],
                                                'invoice_amount'    => 0,
                                                'maininvoiceID'    => 0,  
                                                'amount_status'     => 0, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 2, 
                                                'status'            => 0, 
                                                         );

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array); 
                                        }
                                    }else{
                                        

                                     $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 2,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 1, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status' => 1,
                                                'amount'            => $array['amount'],
                                                'invoice_amount'    => 0,
                                                'maininvoiceID'    => 0,                                  
                                                'amount_status'     => 0, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 0, 
                                                'status'            => 0, 
                                                         );

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array); 
                                    }
                                }else{

                                     $upload_cpv_bpv_array  = array(
                                                'cpv_bpv_recordID'  => $cpv_bpv_recordID,
                                                'pay_type'          => $pay_type,
                                                'payment_type'      => $paymenttype,
                                                'payment_date'      => $dates,  
                                                'payment_date_status'=> 0,  
                                                'student_id'        => $array['student_id'], 
                                                'student_id_status' => 2, 
                                                'student_roll'      => $array['student_roll'], 
                                                'student_roll_status' => 2,
                                                'amount'            => $array['amount'],
                                                'invoice_amount'    => 0, 
                                                'maininvoiceID'    => 0,  
                                                'amount_status'     => 0, 
                                                'challan_no'        => $array['challan_no'], 
                                                'challan_no_status' => 0, 
                                                'status'            => 0, 
                                                         );

                                $this->db->insert('upload_cpv_bpv',$upload_cpv_bpv_array); 
                                     
                                }
                       
                        
                    }else{
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }

                        }

                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/get_reconcile_data"));

                    }else{
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else{
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function bpv_bulkimport(){
        if(isset($_FILES["bpvStudent"])) {
            $paymenttype = $this->input->post('paymenttype');
            $pay_type = $this->input->post('pay_type');
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["bpvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("bpvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Student ID", "Accounts Registration No", "Date", "Student Name", "Father Name", "Amount");
                // $column_headers = array("Student ID", "Date", "Student Roll", "Challan No", "Amount");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);
                                // $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id'], 'roll' => $array['student_roll']]);
                                $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id']]);
                                if (customCompute($student)) {
                                    
                                    // $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$array['challan_no']);

                                    $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID);
                                    $excel_date = $array['date'];
                                    $dates = explode('/', $excel_date);
                                    
                                    if (isset($dates[0]) && isset($dates[1]) && isset($dates[2])){
                                        $dates = $dates[2].'-'.$dates[1].'-'.$dates[0];
                                        $date = date("Y-m-d", strtotime($dates));
                                    
                                        $excel_amount = $array['amount'];
                                        $r_amount = 0;
                                        $p_amount = $excel_amount;
                                        if(customCompute($maininvoice_result)){
                                            foreach ($maininvoice_result as $invoice){

                                                if (($p_amount > $invoice->maininvoicenet_fee || $p_amount == $invoice->maininvoicenet_fee) && $invoice->maininvoicestatus != 2) {
                                                    
                                                    $p_amount = $p_amount - $invoice->maininvoicenet_fee;
                                                   
                                                    if ($invoice->maininvoicestatus == 1) {
                                                        $main_net = $invoice->maininvoicetotal_fee - $invoice->maininvoice_discount;
                                                        $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date, 'maininvoicenet_fee' => $main_net],$invoice->maininvoiceID);
                                                    }else{
                                                         $main_net = $invoice->maininvoicenet_fee;
                                                        $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date],$invoice->maininvoiceID);
                                                    }
                                                    
                                                    $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                                
                                                    $globalpayment = array(
                                                                'classesID'     => $student->classesID,
                                                                'sectionID'     => $student->sectionID,
                                                                'studentID'     => $student->studentID,
                                                                'clearancetype' => 'partial',
                                                                'invoicename'   => $student->registerNO .'-'. $student->name,
                                                                'invoicedescription' => '',
                                                                'paymentyear'   => date("Y", strtotime($dates)),
                                                                'schoolyearID'  => 1,
                                                            );
                                                    $this->globalpayment_m->insert_globalpayment($globalpayment);
                                                    $globalLastID = $this->db->insert_id();
                                                    $paymentArray = array(
                                                                    'invoiceID'     => $this->data['invoices'][0]->invoiceID,
                                                                    'schoolyearID'  => 1,
                                                                    'studentID'     => $student->studentID,
                                                                    'paymentamount' => $invoice->maininvoicenet_fee,
                                                                    'paymenttype'   => $paymenttype,
                                                                    'paymentdate'   => $date,
                                                                    'paymentday'    => date("d", strtotime($dates)),
                                                                    'paymentmonth'  => date("m", strtotime($dates)),
                                                                    'paymentyear'   => date("Y", strtotime($dates)),
                                                                    'userID'        => $this->session->userdata('loginuserID'),
                                                                    'usertypeID'    => $this->session->userdata('usertypeID'),
                                                                    'uname'         => $this->session->userdata('name'),
                                                                    'transactionID' => 'CASHANDCHEQUE'.random19(),
                                                                    'globalpaymentID' => $globalLastID,
                                                                );
                                                
                                                    $this->payment_m->insert_payment($paymentArray);
                                                    $paymentLastID = $this->db->insert_id();
                                                    $paidstatus = 2;
                                                    $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                                    $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'paid'), $globalLastID);

                                                }else if ($p_amount < $invoice->maininvoicenet_fee && $p_amount > 0 && $invoice->maininvoicestatus != 2) {
                                                    
                                                    $this->maininvoice_m->update_maininvoice(['maininvoicenet_fee' => $invoice->maininvoicenet_fee - $p_amount],$invoice->maininvoiceID);
                                                    $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 1, 'maininvoicecreate_date' => $date], $invoice->maininvoiceID);
                                                    $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                                    
                                                    $globalpayment = array(
                                                                'classesID'     => $student->classesID,
                                                                'sectionID'     => $student->sectionID,
                                                                'studentID'     => $student->studentID,
                                                                'clearancetype' => 'partial',
                                                                'invoicename'   => $student->registerNO .'-'. $student->name,
                                                                'invoicedescription' => '',
                                                                'paymentyear'   => date("Y", strtotime($dates)),
                                                                'schoolyearID'  => 1,
                                                            );
                                                    $this->globalpayment_m->insert_globalpayment($globalpayment);
                                                    $globalLastID = $this->db->insert_id();
                                                    $paymentArray = array(
                                                                    'invoiceID'     => $this->data['invoices'][0]->invoiceID,
                                                                    'schoolyearID'  => 1,
                                                                    'studentID'     => $student->studentID,
                                                                    'paymentamount' => $p_amount,
                                                                    'paymenttype'   => $paymenttype,
                                                                    'paymentdate'   => $date,
                                                                   'paymentday'     => date("d", strtotime($dates)),
                                                                    'paymentmonth'  => date("m", strtotime($dates)),
                                                                    'paymentyear'   => date("Y", strtotime($dates)),
                                                                    'userID'        => $this->session->userdata('loginuserID'),
                                                                    'usertypeID'    => $this->session->userdata('usertypeID'),
                                                                    'uname'         => $this->session->userdata('name'),
                                                                    'transactionID' => 'CASHANDCHEQUE'.random19(),
                                                                    'globalpaymentID' => $globalLastID,
                                                    );
                                                    $this->payment_m->insert_payment($paymentArray);
                                                    $paymentLastID = $this->db->insert_id();
                                                    $p_amount = $p_amount - $invoice->maininvoicenet_fee;
                                                    $paidstatus = 1;
                                                    $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                                    $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'partial'), $globalLastID);
                                                }

                                            }
                                        }else{
                                            // $this->session->set_flashdata('error', "Challan no Wrong");
                                            // redirect(base_url("bulkimport/index"));
                                        }
                                    }else{
                                        $this->session->set_flashdata('error', "Date Format wrong");
                                        redirect(base_url("bulkimport/index"));
                                    }
                                    
                                } else{
                                    $this->session->set_flashdata('error', "Student info Wrong");
                                    redirect(base_url("bulkimport/index"));
                                }
                                
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function cpv_bulkimport() {
        if(isset($_FILES["cpvStudent"])) {
            $paymenttype = $this->input->post('paymenttype');
            $pay_type = $this->input->post('pay_type');
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["cpvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("cpvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Student ID", "Date", "Student Roll", "Challan No", "Amount");
                // echo '<pre>';
                // print_r($column_headers);
                // echo '</pre>';
                // echo '<br>';
                // // echo '<pre>';
                // // print_r($csv_col);
                // // echo '</pre>';
                // exit();
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                       
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array           = $this->arrayToPost($row);

                                // $singlebookCheck = $this->singlebookCheck($array);
                                $student = $this->student_m->get_student_select('studentID, student_id, classesID, sectionID, registerNO, name', ['student_id' => $array['student_id'], 'roll' => $array['student_roll']]);
                                
                                // echo '<pre>';
                                // print_r($student);
                                // echo '</pre>';
                                // // exit();
                                // echo '<br>';
                                // echo $array['challan_no'];
                                // echo '<br>';
                                if (customCompute($student)) {
                                    $maininvoice_result = $this->maininvoice_m->get_maininvoice_with_cpv($student->studentID,'','',$array['challan_no']);

                                    // echo '<pre>';
                                    // print_r($maininvoice_result);
                                    // echo '</pre>';
                                    // // exit();
                                    // echo '<br>';
                                    $excel_date = $array['date'];
                                    $dates = explode('/', $excel_date);
                                   
                                    if (isset($dates[0]) && isset($dates[1]) && isset($dates[2])) {
                                        $dates = $dates[2].'-'.$dates[1].'-'.$dates[0];
                                       
                                        $date = date("Y-m-d", strtotime($dates));
                                        $excel_amount = $array['amount'];
                                        $r_amount = 0;
                                        $p_amount = $excel_amount;
                                        if(customCompute($maininvoice_result)){
                                            foreach ($maininvoice_result as $invoice){
                                                // if ($p_amount > $invoice->maininvoicenet_fee) {
                                                //     $this->session->set_flashdata('error', "Amount is not Greater than invoice amount");
                                                //     redirect(base_url("bulkimport/index"));
                                                // }else
                                                if (($p_amount == $invoice->maininvoicenet_fee) && $invoice->maininvoicestatus != 2) {
                                                  
                                                    $p_amount = $p_amount - $invoice->maininvoicenet_fee;
                                                    
                                                    if ($invoice->maininvoicestatus == 1) {
                                                        $main_net = $invoice->maininvoicetotal_fee - $invoice->maininvoice_discount;
                                                        $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date, 'maininvoicenet_fee' => $main_net],$invoice->maininvoiceID);
                                                    }else{
                                                         $main_net = $invoice->maininvoicenet_fee;
                                                        $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 2, 'maininvoicecreate_date' => $date],$invoice->maininvoiceID);
                                                    }
                                                    
                                                    $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                                    
                                                    $globalpayment = array(
                                                                'classesID'     => $student->classesID,
                                                                'sectionID'     => $student->sectionID,
                                                                'studentID'     => $student->studentID,
                                                                'clearancetype' => 'partial',
                                                                'invoicename'   => $student->registerNO .'-'. $student->name,
                                                                'invoicedescription' => '',
                                                                'paymentyear'   => date("Y", strtotime($dates)),
                                                                'schoolyearID'  => 1,
                                                            );
                                                    $this->globalpayment_m->insert_globalpayment($globalpayment);
                                                    $globalLastID = $this->db->insert_id();
                                                    $paymentArray = array(
                                                                    'invoiceID'     => $this->data['invoices'][0]->invoiceID,
                                                                    'schoolyearID'  => 1,
                                                                    'studentID'     => $student->studentID,
                                                                    'paymentamount' => $invoice->maininvoicenet_fee,
                                                                    'paymenttype'   => $paymenttype,
                                                                    'paymentdate'   => $date,
                                                                    'paymentday'    => date("d", strtotime($dates)),
                                                                    'paymentmonth'  => date("m", strtotime($dates)),
                                                                    'paymentyear'   => date("Y", strtotime($dates)),
                                                                    'userID'        => $this->session->userdata('loginuserID'),
                                                                    'usertypeID'    => $this->session->userdata('usertypeID'),
                                                                    'uname'         => $this->session->userdata('name'),
                                                                    'transactionID' => 'CASHANDCHEQUE'.random19(),
                                                                    'globalpaymentID' => $globalLastID,
                                                                );
                                                   
                                                    $this->payment_m->insert_payment($paymentArray);
                                                    $paymentLastID = $this->db->insert_id();
                                                    $paidstatus = 2;
                                                    $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                                    $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'paid'), $globalLastID);

                                                }else if ($p_amount < $invoice->maininvoicenet_fee && $p_amount > 0 && $invoice->maininvoicestatus != 2) {
                                                    
                                                    $this->maininvoice_m->update_maininvoice(['maininvoicenet_fee' => $invoice->maininvoicenet_fee - $p_amount],$invoice->maininvoiceID);
                                                     $this->maininvoice_m->update_maininvoice(['maininvoicestatus' => 1, 'maininvoicecreate_date' => $date], $invoice->maininvoiceID);
                                                     $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                                    
                                                    $globalpayment = array(
                                                                'classesID'     => $student->classesID,
                                                                'sectionID'     => $student->sectionID,
                                                                'studentID'     => $student->studentID,
                                                                'clearancetype' => 'partial',
                                                                'invoicename'   => $student->registerNO .'-'. $student->name,
                                                                'invoicedescription' => '',
                                                                'paymentyear'   => date("Y", strtotime($dates)),
                                                                'schoolyearID'  => 1,
                                                            );
                                                    $this->globalpayment_m->insert_globalpayment($globalpayment);
                                                    $globalLastID = $this->db->insert_id();
                                                    $paymentArray = array(
                                                                    'invoiceID'     => $this->data['invoices'][0]->invoiceID,
                                                                    'schoolyearID'  => 1,
                                                                    'studentID'     => $student->studentID,
                                                                    'paymentamount' => $p_amount,
                                                                    'paymenttype'   => $paymenttype,
                                                                    'paymentdate'   => $date,
                                                                   'paymentday'     => date("d", strtotime($dates)),
                                                                    'paymentmonth'  => date("m", strtotime($dates)),
                                                                    'paymentyear'   => date("Y", strtotime($dates)),
                                                                    'userID'        => $this->session->userdata('loginuserID'),
                                                                    'usertypeID'    => $this->session->userdata('usertypeID'),
                                                                    'uname'         => $this->session->userdata('name'),
                                                                    'transactionID' => 'CASHANDCHEQUE'.random19(),
                                                                    'globalpaymentID' => $globalLastID,
                                                                );
                                                    $this->payment_m->insert_payment($paymentArray);
                                                    $paymentLastID = $this->db->insert_id();
                                                    $p_amount = $p_amount - $invoice->maininvoicenet_fee;
                                                    $paidstatus = 1;
                                                    $this->invoice_m->update_invoice(array('paidstatus' => $paidstatus,'pay_type' => $pay_type,'create_date' => $date), $this->data['invoices'][0]->invoiceID);
                                                    $this->globalpayment_m->update_globalpayment(array('clearancetype' => 'partial'), $globalLastID);
                                                }

                                            }
                                        }else{
                                            $this->session->set_flashdata('error', "Challan no Wrong");
                                            redirect(base_url("bulkimport/index"));
                                        }
                                    }else{
                                        $this->session->set_flashdata('error', "Date Format wrong");
                                        redirect(base_url("bulkimport/index"));
                                    }

                                }else{
                                    $this->session->set_flashdata('error', "Student info Wrong");
                                    redirect(base_url("bulkimport/index"));
                                } 
                                // echo '<br>';
                                // var_dump($array);
                                // echo '<br>';
                                // echo '<pre>';
                                // print_r($maininvoice_result);
                                // echo '</pre>';
                                // exit();

                                // // if($singlebookCheck['status']) {
                                //     $insert_data = array(
                                //         'book'     => $row['Book'],
                                //         'subject_code' => $row['Subject code'],
                                //         'author'   => $row['Author'],
                                //         'price'    => $row['Price'],
                                //         'quantity' => $row['Quantity'],
                                //         'due_quantity' => 0,
                                //         'rack'     => $row['Rack']
                                //     );
                                //     $this->book_m->insert_book($insert_data);
                                // } else {
                                //     $msg .= $i.". ". $row['Book']." is not added! , ";
                                //     $msg .= implode(' , ', $singlebookCheck['error']);
                                //     $msg .= ". <br/>";
                                // }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }


    public function student_bulkimport() {
        if(isset($_FILES["csvStudent"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Class", "Section", "Username", "Password", "Roll", "BloodGroup", "State", "Country", "RegistrationNO", "Group", "OptionalSubject");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(customCompute($csv_array)) {
                        $msg     = "";
                        $i       = 1;
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (customCompute($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singlestudentCheck  = $this->singlestudentCheck($array);
                                if($singlestudentCheck['status']) {
                                    $classID         = $this->get_student_class($row['Class']);
                                    $sections        = $this->get_student_section($classID, $row['Section']);
                                    $group           = $this->get_student_group($row['Group']);
                                    $optionalSubject = $this->get_student_optional_subject($classID, $row['OptionalSubject']);
                                    $dob             = $this->trim_required_convertdate($row['Dob']);

                                    $insert_data = array(
                                        'name'       => $row['Name'],
                                        'dob'        => $dob,
                                        'sex'        => $row['Gender'],
                                        'religion'   => $row['Religion'],
                                        'email'      => $row['Email'],
                                        'phone'      => $row['Phone'],
                                        'photo'      => 'default.png',
                                        'address'    => $row['Address'],
                                        "bloodgroup" => $row['BloodGroup'],
                                        "state"      => $row['State'],
                                        "country"    => $this->get_student_country($row['Country']),
                                        "registerNO" => $row['RegistrationNO'],
                                        'classesID'  => $classID,
                                        'sectionID'  => $sections->sectionID,
                                        'roll'       => $row['Roll'],
                                        'username'   => $row['Username'],
                                        'password'   => $this->student_m->hash($row['Password']),
                                        'usertypeID' => 3,
                                        'parentID'   => 0,
                                        'library'    => 0,
                                        'hostel'     => 0,
                                        'transport'  => 0,
                                        'createschoolyearID' => $this->session->userdata('defaultschoolyearID'),
                                        'schoolyearID'       => $this->session->userdata('defaultschoolyearID'),
                                        "create_date"        => date("Y-m-d h:i:s"),
                                        "modify_date"        => date("Y-m-d h:i:s"),
                                        "create_userID"      => $this->session->userdata('loginuserID'),
                                        "create_username"    => $this->session->userdata('username'),
                                        "create_usertype"    => $this->session->userdata('usertype'),
                                        "active"     => 1,
                                    );

                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                    $this->student_m->insert_student($insert_data);
                                    $studentID = $this->db->insert_id();

                                    $classes = $this->classes_m ->general_get_single_classes(array('classesID'=>$classID));
                                    $section = $this->section_m->general_get_single_section(array('classesID'=>$classID, 'sectionID'=>$sections->sectionID));

                                    if(customCompute($classes)) {
                                        $setClasses = $classes->classes;
                                    } else {
                                        $setClasses = NULL;
                                    }

                                    if(customCompute($section)) {
                                        $setSection = $section->section;
                                    } else {
                                        $setSection = NULL;
                                    }

                                    $studentReletion = $this->studentrelation_m->get_order_by_studentrelation(array('srstudentID' => $studentID, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
                                    if(!customCompute($studentReletion)) {
                                        $arrayStudentRelation = array(
                                            'srstudentID'         => $studentID,
                                            'srname'              => $row['Name'],
                                            'srclassesID'         => $classID,
                                            'srclasses'           => $setClasses,
                                            'srroll'              => $row['Roll'],
                                            'srregisterNO'        => $row['RegistrationNO'],
                                            'srsectionID'         => $sections->sectionID,
                                            'srsection'           => $setSection,
                                            'srstudentgroupID'    => $group->studentgroupID,
                                            'sroptionalsubjectID' => $optionalSubject->subjectID,
                                            'srschoolyearID'      => $this->session->userdata('defaultschoolyearID')
                                        );
                                        $this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
                                    } else {
                                        $arrayStudentRelation = array(
                                            'srname'              => $row['Name'],
                                            'srclassesID'         => $classID,
                                            'srclasses'           => $setClasses,
                                            'srroll'              => $row['Roll'],
                                            'srregisterNO'        => $row['RegistrationNO'],
                                            'srsectionID'         => $sections->sectionID,
                                            'srsection'           => $setSection,
                                            'srstudentgroupID'    => $group->studentgroupID,
                                            'sroptionalsubjectID' => $optionalSubject->subjectID,
                                        );
                                        $this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $studentID, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
                                    }

                                    $studentExtend = $this->studentextend_m->get_single_studentextend(array('studentID' => $studentID));
                                    if(!customCompute($studentExtend)) {
                                        $studentExtendArray = array(
                                            'studentID'                 => $studentID,
                                            'studentgroupID'            => $group->studentgroupID,
                                            'optionalsubjectID'         => $optionalSubject->subjectID,
                                            'extracurricularactivities' => NULL,
                                            'remarks' => NULL
                                        );
                                        $this->studentextend_m->insert_studentextend($studentExtendArray);
                                    } else {
                                        $studentExtendArray = array(
                                            'studentID'                 => $studentID,
                                            'studentgroupID'            => $group->studentgroupID,
                                            'optionalsubjectID'         => $optionalSubject->subjectID,
                                            'extracurricularactivities' => NULL,
                                            'remarks' => NULL
                                        );
                                        $this->studentextend_m->update_studentextend($studentExtendArray, $studentExtend->studentextendID);
                                    }
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singlestudentCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        } 
    }

    // Single  Validation Check
    private function singleteacherCheck($array) {
        $name     = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $designation = $this->trim_required_string_maxlength_minlength_Check($array['designation'],128);
        $dob      = $this->trim_required_date_Check($array['dob']);    
        $gender   = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        $religion = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $jod      = $this->trim_required_date_Check($array['jod']);
        $username = $this->trim_check_unique_username($array['username'],40);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);

        $retArray['status'] = TRUE;
        if($name && $designation && $dob && $gender  && $email   && $jod && $username && $password) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Teacher Name';
            }
            if(!$designation) {
                $retArray['error']['designation'] = 'Invalid Designation';
            }
            if(!$dob) {
                $retArray['error']['dob'] = 'Invalid Date Of Birth';
            }
            if(!$gender) {
                $retArray['error']['gender'] = 'Invalid Gender';
            } 
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            } 
            if(!$jod) {
                $retArray['error']['jod'] = 'Invalid Date Of Birth';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
        }
        return $retArray;
    }

    // Single  Validation Check
    private function singleproductCheck($array) { 
        $Items    = $this->trim_required_string_maxlength_minlength_Check($array['items'],128);  
        $category = $this->trim_check_product_catogory($array['category']); 

        $retArray['status'] = TRUE;
        if( $Items  && $category) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
           
            if(!$Items) {
                $retArray['error']['items'] = 'Invalid Items';
            } 
            if(!$category) {
                $retArray['error']['category'] = 'Category not found';
            }  
            
        }
        return $retArray;
    }
     // asset category Validation Check
    private function trim_check_product_catogory($category) {
        $category = strtolower(trim($category));
        if($category) {
            $result         = $this->productcategory_m->get_single_productcategory(array('LOWER(productcategoryname)'=>$category));
            if(customCompute($result)) {
                $usertypeID   = $result->productcategoryID;
                
                return $usertypeID;
                 
            }
        }
        return FALSE;
    }

    // Single  Validation Check
    private function singleassetCheck($array) {
        $Code     = $this->trim_check_unique_serial_code($array['code']);
        $Items    = $this->trim_required_string_maxlength_minlength_Check($array['items'],128); 
        $category = $this->trim_check_catogory($array['category']); 

        $retArray['status'] = TRUE;
        if($Code && $Items && $category) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$Code) {
                $retArray['error']['code'] = 'Invalid Code or already added';
            }
            if(!$Items) {
                $retArray['error']['items'] = 'Invalid Items';
            } 
            if(!$category) {
                $retArray['error']['category'] = 'Category not found';
            } 
        }
        return $retArray;
    }
      // Username and Email Validation Check
    private function trim_check_unique_serial_code($data) {
        $data = (string)trim($data);
        if($data) {
            $tables = array('asset');
            $i = 0;
            $array = array();
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("serial" => $data));
                if(customCompute($user)) {
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }

            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }
    // Single  Validation Check
    private function singlesubjectCheck($array) {
        $type     = $this->trim_required_string_maxlength_minlength_Check($array['type'],1);
        $passmark     = $this->trim_required_string_maxlength_minlength_Check($array['passmark'],3);
        $finalmark     = $this->trim_required_string_maxlength_minlength_Check($array['finalmark'],3);
        $subject = $this->trim_required_string_maxlength_minlength_Check($array['subject'],128);
        
        $subject_author = $this->trim_required_string_maxlength_minlength_Check($array['subject_author'],128);
          
        $subject_code  = $this->trim_required_string_maxlength_minlength_Check($array['subject_code'],200);
        $teachers  = $this->trim_required_string_maxlength_minlength_Check($array['teachers'],200);
         

        $retArray['status'] = TRUE;
        if(($type==0 || $type==1) && $passmark && $finalmark && $subject  && $subject_author   && $subject_code    && $teachers  ) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$type) {
                $retArray['error']['type'] = 'Invalid Type';
            }
            if(!$passmark) {
                $retArray['error']['passmark'] = 'Invalid passmark';
            }
            if(!$finalmark) {
                $retArray['error']['finalmark'] = 'Invalid finalmark';
            }
            if(!$subject) {
                $retArray['error']['subject'] = 'Invalid subject';
            }
            if(!$subject_author) {
                $retArray['error']['subject_author'] = 'Invalid subject author';
            }
            if(!$subject_author) {
                $retArray['error']['subject_code'] = 'Invalid subject code';
            } 
            if(!$teachers) {
                $retArray['error']['teachers'] = 'Invalid teachers id';
            } 
        }
        return $retArray;
    }

    private function singleparentCheck($array) {
        $name            = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $father_name     = $this->trim_required_string_maxlength_minlength_Check($array['father_name'],60);
        $mother_name     = $this->trim_required_string_maxlength_minlength_Check($array['mother_name'],40);
        $father_profession = $this->trim_required_string_maxlength_minlength_Check($array['father_profession'],40);
        $mother_profession = $this->trim_required_string_maxlength_minlength_Check($array['mother_profession'],40);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $username = $this->trim_check_unique_username($array['username'],40,4);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40,4);

        $retArray['status'] = TRUE;
        if($name && $father_name && $mother_name && $father_profession && $mother_profession && $email && $phone && $address && $username && $password) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Parent Name';
            }
            if(!$father_name) {
                $retArray['error']['father_name'] = 'Invalid Father Name';
            }
            if(!$mother_name) {
                $retArray['error']['mother_name'] = 'Invalid Mother Name';
            }
            if(!$father_profession) {
                $retArray['error']['father_profession'] = 'Invalid Father Profession';
            }
            if(!$mother_profession) {
                $retArray['error']['mother_profession'] = 'Invalid Mother Profession';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
        }
        return $retArray;
    }

    private function singleuserCheck($array) {
        $name     = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $dob      = $this->trim_required_date_Check($array['dob']);    
        $gender   = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        $religion = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $jod      = $this->trim_required_date_Check($array['jod']);
        $username = $this->trim_check_unique_username($array['username'],40);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);
        $usertype = $this->trim_check_usertype($array['usertype']);

        $retArray['status'] = TRUE;
        if($name && $dob && $gender && $religion && $email && $phone && $address && $jod && $username && $password && $usertype) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid User Name';
            }
            if(!$dob) {
                $retArray['error']['dob'] = 'Invalid Date Of Birth';
            }
            if(!$gender) {
                $retArray['error']['gender'] = 'Invalid Gender';
            }
            if(!$religion) {
                $retArray['error']['religion'] = 'Invalid Riligion';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$jod) {
                $retArray['error']['jod'] = 'Invalid Date Of Birth';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
            if(!$usertype) {
                $retArray['error']['usertype'] = 'Invalid Usertype';
            }
        }
        return $retArray;
    }

    private function singlebookCheck($array) {

        
        $book        = $this->trim_required_string_maxlength_minlength_Check($array['book'], 255);
        $accession_number        = $this->trim_required_string_maxlength_minlength_Check($array['accession_number'], 255);
        $ddc_number        = $this->trim_required_string_maxlength_minlength_Check($array['ddc_number'], 255);
        $book_category        = $this->trim_required_string_maxlength_minlength_Check($array['book_category'], 255);
        $book_binding        = $this->trim_required_string_maxlength_minlength_Check($array['book_binding'], 255);
         $classesID        = $this->trim_required_string_maxlength_minlength_Check($array['classesid'], 25);
        $author      = $this->trim_required_string_maxlength_minlength_Check($array['author'], 255);
        $quantity    = $this->trim_required_int_maxlength_minlength_Check($array['quantity'], 10);
        $subject_code= $this->trim_required_string_maxlength_minlength_Check($array['subject_code'], 255);

        $retArray['status'] = TRUE;
        if($book && $accession_number && $ddc_number && $book_category  && $book_binding && $classesID   && $quantity && $subject_code) {
            $books = $this->book_m->get_single_book(array('LOWER(book)' => strtolower($book), 'LOWER(accession_number)' => strtolower($accession_number), 'LOWER(ddc_number)' => strtolower($ddc_number)));
            if(customCompute($books)) {
                $retArray['status'] = FALSE;
                $retArray['error']['book'] = 'Book already exits';
            } else {
                $retArray['status'] = TRUE;
            }
        } else {
            $retArray['status'] = FALSE;
            if(!$book) {
                $retArray['error']['book'] = 'Invalid Book Name';
            }
            if(!$accession_number) {
                $retArray['error']['accession_number'] = 'Accession number are not valid';
            }
            if(!$ddc_number) {
                $retArray['error']['ddc_number'] = 'DDC  number are not valid';
            }
            if(!$book_category) {
                $retArray['error']['book_category'] = 'Book category  number are not valid';
            }
            if(!$book_binding) {
                $retArray['error']['book_binding'] = 'Book  binding  number are not valid';
            }
            if(!$classesID) {
                $retArray['error']['classesID'] = 'classesID are not valid';
            }
            
            if(!$quantity) {
                $retArray['error']['quantity'] = 'Quantity are not valid';
            }
             
        }
        return $retArray;
    }

    public function singlestudentCheck($array) {
        $name       = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $dob        = $this->trim_required_date_Check($array['dob']);    
        $gender     = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        $religion   = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        $email      = $this->trim_check_unique_email($array['email'],40);
        $phone      = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address    = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $class      = $this->trim_required_class_Check($array['class']);
        $section    = $this->trim_required_section_Check($array['class'], $array['section']);
        $username   = $this->trim_check_unique_username($array['username'],40);
        $password   = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);
        $roll       = $this->trim_roll_Check($array);
        $bloodgroup = $this->trim_required_string_maxlength_minlength_Check($array['bloodgroup'],5);
        $state      = $this->trim_required_string_maxlength_minlength_Check($array['state'],128);
        $country    = $this->trim_required_string_maxlength_minlength_Check($array['country'],128);
        $registrationno  = $this->trim_required_registration_Check($array['registrationno']);
        $group           = $this->trim_group_Check($array['group'],40);
        $optionalsubject = $this->trim_optionalsubject_Check($array['optionalsubject'], $array['class']);

        $checkStudent    = $this->trim_check_section_student($array);

        $retArray['status'] = FALSE;
        if($name && $dob && $gender && $religion && $email && $phone && $address && $class && $section && $username && $password && $roll && $bloodgroup && $state && $country && $registrationno && $group && $optionalsubject && $checkStudent) {
            $retArray['status'] = TRUE;
        } else {
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Teacher Name';
            }
            if(!$dob) {
                $retArray['error']['dob'] = 'Invalid Date Of Birth';
            }
            if(!$gender) {
                $retArray['error']['gender'] = 'Invalid Gender';
            }
            if(!$religion) {
                $retArray['error']['religion'] = 'Invalid Riligion';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$class) {
                $retArray['error']['class'] = 'Invalid Class';
            }
            if(!$section) {
                $retArray['error']['section'] = 'Invalid Section';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
            if(!$roll) {
                $retArray['error']['roll'] = 'Invalid roll or roll already exists in class';
            }
            if(!$bloodgroup) {
                $retArray['error']['bloodgroup'] = 'Invalid bloodgroup';
            }
            if(!$state) {
                $retArray['error']['state'] = 'Invalid state';
            }
            if(!$country) {
                $retArray['error']['country'] = 'Invalid country';
            }
            if(!$registrationno) {
                $retArray['error']['registrationno'] = 'Invalid registration no or registration no already exists';
            }
            if(!$group) {
                $retArray['error']['group'] = 'Invalid Group';
            }
            if(!$optionalsubject) {
                $retArray['error']['optionalsubject'] = 'Invalid OptionalSubject Subject';
            } 
            if(!$checkStudent) {
                $retArray['error']['checkStudent'] = 'Student can not add in section';
            }
        }
        return $retArray;
    }

    // Student Valiadtion Check
    private function trim_check_section_student($array) {
        $classes  = strtolower(trim($array['class']));
        $section  = strtolower(trim($array['section']));

        if($classes && $section) {
            $result = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(customCompute($result)) {
                $result   = $this->section_m->general_get_single_section(array('classesID'=> $result->classesID, 'LOWER(section)'=> $section));
                if(customCompute($result)) {
                    $capacity     = $result->capacity;
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $students     = $this->studentrelation_m->general_get_order_by_student(array('srclassesID'=>$result->classesID,'srsectionID'=>$result->sectionID,'srschoolyearID'=>$schoolyearID));
                    $totalStudent = customCompute($students);
                    if($totalStudent <= $capacity) {
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    private function trim_required_registration_Check($data) {
        $data = trim($data);
        if($data) {
            $student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $data));
            if(customCompute($student)) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }

    private function trim_roll_Check($data) {
        $roll    = trim($data['roll']);
        $classes = strtolower(trim($data['class']));
        if($roll && $classes) {
            $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(customCompute($result)) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $student      = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $roll, "srclassesID" => $result->classesID, 'srschoolyearID' => $schoolyearID));
                if(customCompute($student)) {
                    return FALSE;
                } else {
                    return $roll;
                }
            }
        }
        return FALSE;
    }

    private function trim_optionalsubject_Check($subject, $classes) {
        if($subject == '') {
            $array = array(
                'subjectID' => 0,
                'subject' => ''
            );
            $array = (object) $array;
            return $array;
        } else {
            $subject = strtolower(trim($subject));
            $classes = strtolower(trim($classes));
            if($subject && $classes) {
                $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
                if(customCompute($result)) {
                    $result   = $this->subject_m->general_get_single_subject(array('classesID'=> $result->classesID, 'type'=> 0, 'LOWER(subject)'=> $subject));
                    if(customCompute($result)) {
                        return $result;
                    }
                }
            } 
            return FALSE;
        }
    }

    private function trim_required_class_Check($classes) {
        $classes = strtolower(trim($classes));
        if($classes) {
            $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(customCompute($result)) {
                return $result;
            }
        }
        return FALSE;
    }

    private function trim_group_Check($group) {
        $group1 = strtolower(trim($group));
        $group2 = trim($group);
        if($group1 && $group2) {
            $result1   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group1));
            $result2   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group2));
            if(customCompute($result1)) {
                return $result1;
            } elseif(customCompute($result2)) {
                return $result2;
            }
        }
        return FALSE;
    }

    private function trim_required_section_Check($classes, $section) {
        $classes  = strtolower(trim($classes));
        $section  = strtolower(trim($section));
        if($classes && $section) {
            $result = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(customCompute($result)) {
                $result   = $this->section_m->general_get_single_section(array('classesID'=> $result->classesID, 'LOWER(section)'=> $section));
                if(customCompute($result)) {
                    return $result;
                }
            }
        }
        return FALSE;
    }

    // User Validation Check
    private function trim_check_usertype($usertype) {
        $usertype = strtolower(trim($usertype));
        if($usertype) {
            $result         = $this->usertype_m->get_single_usertype(array('LOWER(usertype)'=>$usertype));
            if(customCompute($result)) {
                $usertypeID   = $result->usertypeID;
                $blockuserArr = array(1, 2, 3, 4);
                if(in_array($usertypeID, $blockuserArr)) {
                    return FALSE;
                } else {
                    return $usertypeID;
                }
            }
        }
        return FALSE;
    }

    // asset category Validation Check
    private function trim_check_catogory($category) {
        $category = strtolower(trim($category));
        if($category) {
            $result         = $this->asset_category_m->get_single_asset_category(array('LOWER(category)'=>$category));
            if(customCompute($result)) {
                $usertypeID   = $result->asset_categoryID ;
                
                return $usertypeID;
                 
            }
        }
        return FALSE;
    }

    // Username and Email Validation Check
    private function trim_check_unique_username($data) {
        $data = (string)trim($data);
        if($data) {
            $tables = array('student', 'parents', 'teacher', 'user', 'systemadmin');
            $i = 0;
            $array = array();
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("username" => $data));
                if(customCompute($user)) {
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }

            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }

    private function trim_check_unique_email($data) {
        $data = trim($data);
        if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $tables = array('student', 'parents', 'teacher', 'user', 'systemadmin');
            $array  = array();
            $i = 0;
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("email" => $data));
                if(customCompute($user)) {
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }
            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
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

    private function trim_required_string_maxlength_minlength_Check($data,$maxlength= 10, $minlength= 0) {
        $data       = (string)trim($data);
        $dataLength = strlen($data);

        if(($dataLength == 0) || ($dataLength > $maxlength) || ($dataLength < $minlength)) {
            return FALSE;
        } else {
            if(is_string($data)) {
                return $data;
            }
            return FALSE;
        }
    }

    private function trim_required_int_maxlength_minlength_Check($data,$maxlength= 10, $minlength = 0) {
        $data = (int)trim($data);
        $dataLength = strlen($data);

        if(($dataLength == 0) || ($dataLength > $maxlength) || ($dataLength < $minlength)) {
            return FALSE;
        } else {
            if(is_int($data)) {
                return $data;
            }
            return FALSE;
        }
    }

    private function trim_required_date_Check($date) {
        $date = trim($date);
        if($date) {
            $date = str_replace('/', '-', $date);
            return date("Y-m-d", strtotime($date));
        } 
        return FALSE;
    }
    
    private function trim_required_convertdate($date) {
        $date = trim($date);
        if($date) {
            $date = str_replace('/', '-', $date);
            return date("Y-m-d", strtotime($date));
        }
        return 0;
    }

    // For Only Student Import Check Query
    public function get_student_class($classes) {
        $classes  = strtolower(trim($classes));
        if($classes) {
            $result  = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(customCompute($result)) {
                return $result->classesID;
            }
        }
        return 0;
    }

    public function get_student_section($classesID, $section) {
        $section  = strtolower(trim($section));
        if($classesID) {
            $result   = $this->section_m->general_get_single_section(array('classesID'=> $classesID, 'LOWER(section)'=> $section));
            if(customCompute($result)) {
                return $result;
            }
        }
        return 0;
    }

    public function get_student_group($group) {
        $group1 = strtolower(trim($group));
        $group2 = trim($group);
        if($group1 && $group2) {
            $result1   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group1));
            $result2   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group2));
            if(customCompute($result1)) {
                return $result1;
            } elseif(customCompute($result2)) {
                return $result2;
            }
        }
        $array = array(
            'studentgroupID' => 0,
            'group' => ''
        );
        $array = (object) $array;
        return $array;
    }

    public function get_student_optional_subject($classesID, $subject) {
        $subject  = strtolower(trim($subject));
        if($subject) {
            $result   = $this->subject_m->general_get_single_subject(array('classesID'=> $classesID, 'type'=> 0, 'LOWER(subject)'=> $subject));
            if(customCompute($result)) {
                return $result;
            }
        }
        $array = array(
            'subjectID' => 0,
            'subject'   => ''
        );
        $array = (object) $array;
        return $array;
    }

    public function get_student_country($country) {
        $countryArr = $this->getAllCountry();
        $key        = array_search($country, $countryArr);
        return ($key) ? $key : 0;
    }

}