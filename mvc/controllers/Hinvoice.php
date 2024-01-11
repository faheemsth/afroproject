<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

use Omnipay\Omnipay;
class Hinvoice extends Admin_Controller
{
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
    protected $_amountgivenstatus = "";
    protected $_amountgivenstatuserror = [];

    function __construct()
    {
        parent::__construct();
        $this->load->model("invoice_m");
        $this->load->model("feetypes_m");
        $this->load->model("payment_m");
        $this->load->model("classes_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("section_m");
        $this->load->model("user_m");
        $this->load->model("weaverandfine_m");
        $this->load->model("payment_settings_m");
        $this->load->model("globalpayment_m");
        $this->load->model("maininvoice_m");
        $this->load->model("studentrelation_m");
        $this->load->model("hmember_m");
        $this->load->model("tmember_m");
        $this->load->model("courseoption_m");
        $language = $this->session->userdata("lang");
        $this->lang->load("student", $language);
        $this->lang->load("invoice", $language);
        $this->lang->load("attendanceoverviewreport", $language);
        $this->lang->load("balancefeesreport", $language);
        require_once APPPATH . "libraries/Omnipay/vendor/autoload.php";
    }

    protected function rules($statusID = 0)
    {
        $rules = [
            [   "field" => "classesID",
                "label" => $this->lang->line("invoice_classesID"),
                "rules" =>"trim|required|xss_clean|max_length[11]|numeric|callback_unique_classID",
            ],
            [   "field" => "studentID",
                "label" => $this->lang->line("invoice_studentID"),
                "rules" =>"trim|required|xss_clean|max_length[11]|numeric|callback_unique_studentID",
            ],
            [   "field" => "feess_type",
                "label" => $this->lang->line("invoice_feetypeitem"),
                "rules" => "trim|xss_clean|required",
            ],
            [   "field" => "statusID",
                "label" => $this->lang->line("invoice_status"),
                "rules" =>"trim|xss_clean|required|numeric|callback_unique_status",
            ],
            [   "field" => "date",
                "label" => $this->lang->line("invoice_date"),
                "rules" =>"trim|required|xss_clean|max_length[10]|callback_date_valid",
            ],
            [   "field" => "to_date",
                "label" => "Due Date",
                "rules" =>"trim|required|xss_clean|max_length[10]|callback_date_valid",
            ],
            [   "field" => "amount",
                "label" => "Amount",
                "rules" => "trim|required|xss_clean|max_length[10]",
            ],
            // array(
            //     'field' => 'paymentdate',
            //     'label' => $this->lang->line("invoice_pdate"),
            //     'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid'
            // ),
        ];

        if ($statusID != 0) {
            $rules[] = [    "field" => "paymentmethodID",
                            "label" => $this->lang->line("invoice_paymentmethod"),
                            "rules" =>"trim|required|xss_clean|max_length[20]|callback_unique_paymentmethodID",
                        ];
        }

        return $rules;
    }

    protected function send_mail_rules()
    {
        $rules = [
            [   "field" => "id",
                "label" => $this->lang->line("invoice_id"),
                "rules" =>"trim|required|xss_clean|numeric|callback_valid_data",
            ],
            [   "field" => "to",
                "label" => $this->lang->line("to"),
                "rules" => "trim|required|xss_clean|valid_email",
            ],
            [   "field" => "subject",
                "label" => $this->lang->line("subject"),
                "rules" => "trim|required|xss_clean",
            ],
            [   "field" => "message",
                "label" => $this->lang->line("message"),
                "rules" => "trim|xss_clean",
            ],
        ];
        return $rules;
    }

    public function index()
    {
        if ($this->data["siteinfos"]->school_year ==  $this->session->userdata("defaultschoolyearID") ||  $this->session->userdata("usertypeID") == 1 ||  $this->session->userdata("defaultschoolyearID") == 5) {
            $this->data["headerassets"] = [
                "css" => [  "assets/datepicker/datepicker.css",
                            "assets/select2/css/select2.css",
                            "assets/select2/css/select2-bootstrap.css",],
                "js" =>  [  "assets/datepicker/datepicker.js",
                            "assets/select2/select2.js",],
            ];

            $this->data["classes"] = $this->classes_m->general_get_classes();
            $this->data["feetypes"] = $this->feetypes_m->get_order_by_feetypes(["type" => 1,]);
            $this->data["students"] = [];
            
            $this->data["subview"] = "hinvoice/index";
            $this->load->view("_layout_main", $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function processolinvoice()
    {
        $allinvoice     =   $this->invoice_m->get_invoice_join_student_by_array(array('fee_breakup'=>''));
         $ledger_array  =   [];
        foreach ($allinvoice as $inv) {
            // var_dump($inv);
            // exit();
            // die();

             $ledger_array[] = [
                "maininvoiceID" => $inv->maininvoiceID,
                "studentID"     => $inv->studentID  ,
                "classesID"     => $inv->classesID,
                "net_fee"       => $inv->net_fee,
                "refrence_no"   => $inv->refrence_no,
                'description'   => $inv->feetypes.' fee for semster ' ,
                "accounts_reg"  => $inv->accounts_reg, 
                "account_credit"=> $inv->credit_id, 
                "account_debit" => $inv->debit_id, 
                "feetypeID"     => $inv->feetypesID,
                "date"          => $inv->date, 
                "discount"      => $inv->discount, 
                ];
            $breakup_info_ar        =   [];
            $breakup_info_ar[]      =   array(
                                            'feetypeID'         => $inv->feetypesID, 
                                            'feetypes'          => $inv->feetypes, 
                                            'fee_discount'      => $inv->discount, 
                                            'fee_amount'        => ($inv->net_fee+$inv->discount), 
                                            'fee_discount_a'    => $inv->discount, 
                                            'net_amount'        => $inv->net_fee, 
                                            'discount_left'     => 0,  
                                            'feetypedetail'     => 'old invoice', 
                                            'S_DIS'             => $inv->discount, 
                                            );
                                        
                                  


            $fee_breakup    =   serialize($breakup_info_ar);

            $data           =   array('fee_breakup' =>   $fee_breakup  );

            $this->maininvoice_m->update_maininvoice($data,$inv->maininvoiceID);
            $this->invoice_m->update_invoice_by_maininvoiceID($data,$inv->maininvoiceID);



        }

       
         
                $student_ledgers_ar     =   [];
                $journal_items_ar       =   [];
                foreach ($ledger_array as $in_ar) {
                $journal_entries_id   =     $this->invoice_m->journal_entries_id($in_ar);

                

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
                if ($in_ar['discount']>0) {
                    

                $journal_items_ar[]  = array(
                                'journal'       =>  $journal_entries_id, 
                                'account'       =>  $this->data["siteinfos"]->discount_credit, 
                                'description'   =>  $in_ar['description'], 
                                'debit'         =>  0, 
                                'credit'        =>  $in_ar['discount'], 
                                'entry_type'    =>  'CR',  
                                'referenceID'   =>  $in_ar['maininvoiceID'],    'reference_type'=>  'invoice',    'feetypeID'     =>  $in_ar['feetypeID'],    'created_at'    =>  date('Y-m-d h:i:s'), 
                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                                'journal'       =>  $journal_entries_id, 
                                'account'       =>  $this->data["siteinfos"]->discount_debit, 
                                'description'   =>  $in_ar['description'], 
                                'debit'         =>  $in_ar['discount'], 
                                'entry_type'    =>  'DR', 
                                'credit'        =>  0,  
                                'referenceID'   =>  $in_ar['maininvoiceID'],    'reference_type'=>  'invoice',    'feetypeID'     =>  $in_ar['feetypeID'],    'created_at'    =>  date('Y-m-d h:i:s'), 
                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'],   
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $this->data["siteinfos"]->discount_credit, 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  0, 
                            'reference_type'           =>  'invoice',
                            'feetypeID'                =>  $in_ar['feetypeID'],
                            'credit'                   =>  $in_ar['discount'], 
                            'balance'                  =>  $in_ar['discount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'],  
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $this->data["siteinfos"]->discount_debit, 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  $in_ar['discount'], 
                            'credit'                   =>  0, 
                            'reference_type'           =>  'invoice',
                            'feetypeID'                =>  $in_ar['feetypeID'],
                            'balance'                  =>  $in_ar['discount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );


                }

                                }
                  $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                  $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );

                  $this->session->set_flashdata("success",$this->lang->line("menu_success"));
                        redirect(base_url("invoice/index"));


        }

    public function recoverdelinvoice()
    {
        $maininvoiceID = htmlentities(escapeString($this->uri->segment(3)));
        $allinvoice     =   $this->invoice_m->get_invoice_join_student_by_array(array('maininvoiceID'=>$maininvoiceID,'deleted_at'=>0));
         $ledger_array  =   [];
        foreach ($allinvoice as $inv) {
          
             $ledger_array[] = ["maininvoiceID" => $inv->maininvoiceID,
                                "studentID"     => $inv->studentID  ,
                                "classesID"     => $inv->classesID,
                                "net_fee"       => $inv->net_fee,
                                "refrence_no"   => $inv->refrence_no,
                                'description'   => $inv->feetypes.' fee for semster ' ,
                                "accounts_reg"  => $inv->accounts_reg, 
                                "account_credit"=> $inv->credit_id, 
                                "account_debit" => $inv->debit_id, 
                                "feetypeID"     => $inv->feetypesID,
                                "date"          => $inv->date, 
                                "discount"      => $inv->discount, ];
            
 
 
        $this->maininvoice_m->update_maininvoice(["maininvoicedeleted_at" => 1],$inv->maininvoiceID);
        $this->invoice_m->update_invoice_by_maininvoiceID(["deleted_at" => 1],$inv->maininvoiceID);



        }

       
         
                $student_ledgers_ar     =   [];
                $journal_items_ar       =   [];
                foreach ($ledger_array as $in_ar) {
                $journal_entries_id   =     $this->invoice_m->journal_entries_id($in_ar);

                

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
                if ($in_ar['discount']>0) {
                    

                $journal_items_ar[]  = array(
                                'journal'       =>  $journal_entries_id, 
                                'account'       =>  $this->data["siteinfos"]->discount_credit, 
                                'description'   =>  $in_ar['description'], 
                                'debit'         =>  0, 
                                'credit'        =>  $in_ar['discount'], 
                                'entry_type'    =>  'CR',  
                                'referenceID'   =>  $in_ar['maininvoiceID'],
                                'reference_type'=>  'invoice',
                                'feetypeID'     =>  $in_ar['feetypeID'],
                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                                'journal'       =>  $journal_entries_id, 
                                'account'       =>  $this->data["siteinfos"]->discount_debit, 
                                'description'   =>  $in_ar['description'], 
                                'debit'         =>  $in_ar['discount'], 
                                'entry_type'    =>  'DR', 
                                'credit'        =>  0,  
                                'referenceID'   =>  $in_ar['maininvoiceID'],
                                'reference_type'=>  'invoice',
                                'feetypeID'     =>  $in_ar['feetypeID'],
                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'],   
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $this->data["siteinfos"]->discount_credit, 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  0, 
                            'reference_type'           =>  'invoice',
                            'feetypeID'                =>  $in_ar['feetypeID'],
                            'credit'                   =>  $in_ar['discount'], 
                            'balance'                  =>  $in_ar['discount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $in_ar['maininvoiceID'],  
                            'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $this->data["siteinfos"]->discount_debit, 
                            'vr_no'                    =>  $in_ar['refrence_no'], 
                            'narration'                =>  $in_ar['description'], 
                            'debit'                    =>  $in_ar['discount'], 
                            'credit'                   =>  0, 
                            'reference_type'           =>  'invoice',
                            'feetypeID'                =>  $in_ar['feetypeID'],
                            'balance'                  =>  $in_ar['discount'],  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );


                }

                                }
                  $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                  $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );

                  $this->session->set_flashdata("success",$this->lang->line("menu_success"));
                        redirect(base_url("invoice/index"));


        }
    public function index_old()
    {
        $usertypeID = $this->session->userdata("usertypeID");
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        if ($usertypeID == 3) {
            $username = $this->session->userdata("username");
            $student = $this->student_m->get_single_student(["username" => $username,
            ]);
            if (customCompute($student)) {
                $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_studentID($student->studentID,$schoolyearID);
                $this->data["grandtotalandpayment"] = $this->grandtotalandpaid($this->data["maininvoices"],$schoolyearID);

                $this->data["subview"] = "invoice/index";
                $this->load->view("_layout_main", $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } elseif ($usertypeID == 4) {
            $this->data["headerassets"] = ["css" => ["assets/select2/css/select2.css",
                                                    "assets/select2/css/select2-bootstrap.css",],
                                            "js" => ["assets/select2/select2.js"],
                                            ];

            $parentID = $this->session->userdata("loginuserID");
            $students = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,"srschoolyearID" => $schoolyearID,]);
            if (customCompute($students)) {
                $studentArray = pluck($students, "srstudentID");
                $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_multi_studentID($studentArray,$schoolyearID);
                $this->data["grandtotalandpayment"] = $this->grandtotalandpaid($this->data["maininvoices"],$schoolyearID);
                $this->data["subview"] = "invoice/index";
                $this->load->view("_layout_main", $this->data);
            } else {
                $this->data["maininvoices"] = [];
                $this->data["grandtotalandpayment"] = [];
                $this->data["subview"] = "invoice/index";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->load->library("pagination");

            if (isset($_POST["searchInvoice"])) {
                $searchInvoice = $_POST["searchInvoice"];
                $this->session->set_userdata(["searchInvoice" => $searchInvoice,]);
                $count = $this->maininvoice_m->count_maininvoice_with_studentrelation($schoolyearID,"invoice",$searchInvoice);
                $link = "invoice/?searchInvoice=" . $searchInvoice;
                $returns = $this->paginationCompress("invoice/", $count, 10);
                $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_pag($returns["page"],$returns["segment"],$schoolyearID,"invoice",$searchInvoice);
            } elseif (isset($_POST["statusID"])) {
                $yearly = $_POST["statusID"];
                $count = $this->maininvoice_m->count_maininvoice_with_studentrelation($schoolyearID,"invoice","",$yearly);
                $returns = $this->paginationCompress("invoice/", $count, 10);
                $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_pag($returns["page"],$returns["segment"],$schoolyearID,"invoice","",$yearly);
            } else {
                if ($this->session->userdata("searchInvoice") != null && $this->uri->segment(2)) {
                    // $this->uri->segment ( 2 )
                    $search_text = $this->session->userdata("searchInvoice");
                    $count = $this->maininvoice_m->count_maininvoice_with_studentrelation($schoolyearID,"invoice",$search_text);
                    $returns = $this->paginationCompress("invoice/",$count,10);
                    $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_pag(
                        $returns["page"],
                        $returns["segment"],
                        $schoolyearID,
                        "invoice",
                        $search_text
                    );
                } else {
                    $this->session->unset_userdata("searchInvoice");
                    $count = $this->maininvoice_m->count_maininvoice_with_studentrelation($schoolyearID,"invoice");
                    $returns = $this->paginationCompress("invoice/",$count,10);
                    $this->data["maininvoices"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_pag(
                        $returns["page"],
                        $returns["segment"],
                        $schoolyearID,
                        "invoice"
                    );
                }
            }

            // $this->data['maininvoices'] = $this->maininvoice_m->get_maininvoice_with_studentrelation($schoolyearID);
            $this->data["grandtotalandpayment"] = $this->grandtotalandpaid(
                $this->data["maininvoices"],
                $schoolyearID);
            $this->data["subview"] = "invoice/index";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function paginationCompress($link, $count, $perPage = 10)
    {
        $this->load->library("pagination");

        $config["base_url"] = base_url() . $link;
        // $config['page_query_string'] = TRUE;
        $config["total_rows"] = $count;
        //      $config ['uri_segment'] = SEGMENT;
        $config["uri_segment"] = 2;
        $config["per_page"] = $this->data["siteinfos"]->record_per_page;
        $config["num_links"] = 5;
        $config["full_tag_open"] = '<nav><ul class="pagination">';
        $config["full_tag_close"] = "</ul></nav>";
        $config["first_tag_open"] = '<li class="arrow">';
        $config["first_link"] = "First";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "Previous";
        $config["prev_tag_open"] = '<li class="arrow">';
        $config["prev_tag_close"] = "</li>";
        $config["next_link"] = "Next";
        $config["next_tag_open"] = '<li class="arrow">';
        $config["next_tag_close"] = "</li>";
        $config["cur_tag_open"] = '<li class="active"><a href="#">';
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li>";
        $config["num_tag_close"] = "</li>";
        $config["last_tag_open"] = '<li class="arrow">';
        $config["last_link"] = "Last";
        $config["last_tag_close"] = "</li>";
        $config["reuse_query_string"] = true;

        $this->pagination->initialize($config);
        $page = $config["per_page"];
        $segment = $this->uri->segment(2);

        return ["page" => $page,"segment" => $segment,];
    }

    public function add()
    {
        if ($this->data["siteinfos"]->school_year ==  $this->session->userdata("defaultschoolyearID") ||  $this->session->userdata("usertypeID") == 1 ||  $this->session->userdata("defaultschoolyearID") == 5) {
            $this->data["headerassets"] = [
                "css" => [  "assets/datepicker/datepicker.css",
                            "assets/select2/css/select2.css",
                            "assets/select2/css/select2-bootstrap.css",],
                "js" =>  [  "assets/datepicker/datepicker.js",
                            "assets/select2/select2.js",],
            ];

            $this->data["classes"] = $this->classes_m->general_get_classes();
            $this->data["feetypes"] = $this->feetypes_m->get_order_by_feetypes(["type" => 1,]);
            $this->data["students"] = [];

            $this->data["subview"] = "hinvoice/index";
            $this->load->view("_layout_main", $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function add_hostel_fee(){
        if ($this->data["siteinfos"]->school_year == $this->session->userdata("defaultschoolyearID") ||  $this->session->userdata("usertypeID") == 1 || $this->session->userdata("defaultschoolyearID") == 5) {
            $this->data["headerassets"] = [ "css"   => [   "assets/datepicker/datepicker.css",
                                                            "assets/select2/css/select2.css",
                                                            "assets/select2/css/select2-bootstrap.css",],
                                            "js"    => [    "assets/datepicker/datepicker.js",
                                                            "assets/select2/select2.js",],];

            $this->data["classes"] = $this->classes_m->general_get_classes();
            $this->data["feetypes"] = $this->feetypes_m->get_feetypes();
            $this->data["students"] = [];

            $this->data["subview"] = "invoice/add_hostel_fee";
            $this->load->view("_layout_main", $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function add_transport_fee()
    {
        if ($this->data["siteinfos"]->school_year == $this->session->userdata("defaultschoolyearID") ||  $this->session->userdata("usertypeID") == 1 || $this->session->userdata("defaultschoolyearID") == 5) {
            $this->data["headerassets"] = [ "css"   => [    "assets/datepicker/datepicker.css",
                                                            "assets/select2/css/select2.css",
                                                            "assets/select2/css/select2-bootstrap.css",],
                                            "js"    => [    "assets/datepicker/datepicker.js",
                                                            "assets/select2/select2.js",],
                                                        ];

            $this->data["classes"] = $this->classes_m->general_get_classes();
            $this->data["feetypes"] = $this->feetypes_m->get_feetypes();
            $this->data["students"] = [];

            $this->data["subview"] = "invoice/add_transport_fee";
            $this->load->view("_layout_main", $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function edit()
    {
        if ($this->data["siteinfos"]->school_year == $this->session->userdata("defaultschoolyearID") || $this->session->userdata("usertypeID") == 1 ||$this->session->userdata("defaultschoolyearID") == 5) {
            $this->data["headerassets"] = [ "css"   => [    "assets/datepicker/datepicker.css",
                                                            "assets/select2/css/select2.css",
                                                            "assets/select2/css/select2-bootstrap.css",],
                                            "js"    => [    "assets/datepicker/datepicker.js",
                                                            "assets/select2/select2.js",],
                                                        ];

            $maininvoiceID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $maininvoiceID) {
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $this->data["maininvoiceID"] = $maininvoiceID;
                $this->data["maininvoice"] = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,]);
                if (customCompute($this->data["maininvoice"])) {
                    if ($this->data["maininvoice"]->maininvoicestatus != 2) {

                        $this->data["classes"] = $this->classes_m->general_get_classes();
                        $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"obj", "feetypesID");
                        $this->data["students"] = $this->studentrelation_m->get_order_by_studentrelation(["srclassesID" =>    $this->data["maininvoice"]->maininvoiceclassesID, "srschoolyearID" => $schoolyearID,]);

                        $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $maininvoiceID,]);

                        $this->data["subview"] = "invoice/edit";
                        $this->load->view("_layout_main", $this->data);
                                    } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                    }
                } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function delete()
    {
        if ($this->data["siteinfos"]->school_year == $this->session->userdata("defaultschoolyearID") || $this->session->userdata("usertypeID") == 1 || $this->session->userdata("defaultschoolyearID") == 5) {
            $maininvoiceID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $maininvoiceID) {
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,"maininvoicedeleted_at" => 1,]);
                if (customCompute($maininvoice)) {
                    if ($maininvoice->maininvoicestatus == 0) {
                        $this->maininvoice_m->update_maininvoice(["maininvoicedeleted_at" => 0], $maininvoiceID);
                        $this->invoice_m->update_invoice_by_maininvoiceID(["deleted_at" => 0], $maininvoiceID);
                        $del_array = array( 
                                            'reference_type'    => 'invoice', 
                                            'maininvoice_id'    => $maininvoiceID,
                                             );
                            $this->invoice_m->delete_students_ledgers($del_array);
                            $del_array_j = array(   
                                                'reference_type'    => 'invoice', 
                                                'referenceID'       => $maininvoiceID,
                                                 );
                            $this->invoice_m->delete_journal_items($del_array_j);
                            $this->session->set_flashdata("success",
                            $this->lang->line("menu_success"));
                            redirect(base_url("invoice/index"));
                    } else {
                            $this->data["subview"] = "error";
                            $this->load->view("_layout_main", $this->data);
                    }
                } else {
                            $this->data["subview"] = "error";
                            $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function view()
    {
        $usertypeID = $this->session->userdata("usertypeID");
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"feetypes","feetypesID");

        if ($usertypeID == 3) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $id) {
                $studentID = $this->session->userdata("loginuserID");
                $getstudent = $this->studentrelation_m->get_single_student(["srstudentID" => $studentID, "srschoolyearID" => $schoolyearID,]);
                if (customCompute($getstudent)) {
                    $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id,$schoolyearID);
                    if (customCompute($this->data["maininvoice"]) && $this->data["maininvoice"]->maininvoicestudentID == $getstudent->studentID) {
                    $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);

                    $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"],$schoolyearID, $this->data["maininvoice"]->maininvoicestudentID);

                    $this->data["student"]      = $this->student_m->get_single_student(["studentID" =>$this->data["maininvoice"]->maininvoicestudentID,]);

                    $this->data["createuser"]   = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID, $this->data["maininvoice"]->maininvoiceuserID);

                    $this->data["subview"] = "invoice/view";
                    $this->load->view("_layout_main", $this->data);
                    } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                    }
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } elseif ($usertypeID == 4) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $id) {
                $parentID       = $this->session->userdata("loginuserID");
                $getStudents    = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID, "srschoolyearID" => $schoolyearID,]);
                $fetchStudent = pluck($getStudents,"srstudentID", "srstudentID");
                if (customCompute($fetchStudent)) {
                $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id,$schoolyearID);
                    if ($this->data["maininvoice"]) {
                        if (in_array($this->data["maininvoice"]->maininvoicestudentID,$fetchStudent)) {
                            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);

                            $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"], $schoolyearID, $this->data["maininvoice"]->maininvoicestudentID);

                            $this->data["student"] = $this->student_m->get_single_student(["studentID" =>    $this->data["maininvoice"]->maininvoicestudentID,]);

                            $this->data["createuser"] = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID, $this->data["maininvoice"]->maininvoiceuserID);

                            $this->data["subview"] = "invoice/view";
                            $this->load->view("_layout_main", $this->data);
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                    }
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $id) {
                $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID( $id, $schoolyearID);
                $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);

                if (customCompute($this->data["maininvoice"])) {
                    $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"], $schoolyearID, $this->data["maininvoice"]->maininvoicestudentID);

                    $this->data["student"] = $this->student_m->get_single_student(["studentID" =>$this->data["maininvoice"]->maininvoicestudentID,]);

                    $this->data["createuser"] = getNameByUsertypeIDAndUserID(
                        $this->data["maininvoice"]->maininvoiceusertypeID,
                        $this->data["maininvoice"]->maininvoiceuserID
                    );
                    $this->data["subview"] = "invoice/view";
                    $this->load->view("_layout_main", $this->data);
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        }
    }

    public function print_preview()
    {
        if (permissionChecker("invoice_view")) {
            $usertypeID = $this->session->userdata("usertypeID");
            // var_dump($this->session->userdata('loginuserID'));
            // exit();
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"feetypes","feetypesID");
            if ($usertypeID == 3) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int) $id) {
                    $studentID = $this->session->userdata("loginuserID");
                    $getstudent = $this->studentrelation_m->get_single_student(["srstudentID" => $studentID, "srschoolyearID" => $schoolyearID,]);
                    if (customCompute($getstudent)) {
        $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id, $schoolyearID);
                        if (customCompute($this->data["maininvoice"]) && $this->data["maininvoice"]->maininvoicestudentID == $getstudent->studentID) {
                                $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);
                                $user_visit = $this->data["invoices"][0]->user_c == ""? 1: $this->data["invoices"][0]->user_c + 1;
                                $this->invoice_m->update_invoice_by_maininvoiceID(["user_c" => $user_visit], $id);
                                                // echo '<pre>';
                                                // print_r($this->data['invoices'][0]->user_c);
                                                // echo '</pre>';
                                                // exit();
                                $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"],$schoolyearID, $this->data["maininvoice"]->maininvoicestudentID);

                                $this->data["student"] = $this->student_m->get_single_student(["studentID" =>    $this->data["maininvoice"]->maininvoicestudentID,]);

                                $this->data["createuser"] = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID,$this->data["maininvoice"]->maininvoiceuserID);

                                $this->voucherPDF("vouchermodule.css", $this->data,"invoice/print_preview");
                            // $this->reportPDF('invoicemodule.css',$this->data, 'invoice/print_preview');
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                    }
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            } elseif ($usertypeID == 4) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int) $id) {
                    $parentID = $this->session->userdata("loginuserID");
                    $getstudents = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID, "srschoolyearID" => $schoolyearID,]);
                    $fetchStudent = pluck( $getstudents,"srstudentID","srstudentID");
                    if (customCompute($fetchStudent)) {
                        $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id,$schoolyearID);
                        if ($this->data["maininvoice"]) {
                            if (in_array($this->data["maininvoice"]->maininvoicestudentID,$fetchStudent)) {
                            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);

                            $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"],$schoolyearID,$this->data["maininvoice"]->maininvoicestudentID);

                            $this->data["student"] = $this->student_m->get_single_student(["studentID" =>    $this->data["maininvoice"]->maininvoicestudentID,]);

                            $this->data["createuser"] = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID,$this->data["maininvoice"]->maininvoiceuserID);

                            $this->reportPDF("invoicemodule.css",$this->data, "invoice/print_preview");
                            } else {
                                $this->data["subview"] = "error";
                                $this->load->view("_layout_main", $this->data);
                            }
                        } else {
                            $this->data["subview"] = "error";
                            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                    }
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int) $id) {
                $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID( $id,$schoolyearID);
                $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id, ]);

                    if ($this->data["maininvoice"]) {
                        $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"], $schoolyearID,$this->data["maininvoice"]->maininvoicestudentID);

                        $this->data["student"] = $this->student_m->get_single_student(["studentID" =>$this->data["maininvoice"]->maininvoicestudentID,]);

                        $this->data["createuser"] = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID,$this->data["maininvoice"]->maininvoiceuserID);
                                        // $this->voucherPDF('invoicemodule.css',$this->data, 'invoice/print_preview');
                                       // error_reporting(E_ALL);
                        $this->voucherPDF("vouchermodule.css", $this->data,"invoice/print_preview");
                        // $this->load->view('invoice/print_preview', $this->data);
                        // $this->mpdf->Output($file_name, 'I');
                    } else {
                        $this->data["subview"] = "error";
                        $this->load->view("_layout_main", $this->data);
                    }
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view("_layout_main", $this->data);
                }
            }
        } else {
            $this->data["subview"] = "errorpermission";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function send_mail()
    {
        $usertypeID             = $this->session->userdata("usertypeID");
        $schoolyearID           = $this->session->userdata("defaultschoolyearID");
        $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"feetypes","feetypesID");

        $retArray["status"] = false;
        $retArray["message"] = "";
        if (permissionChecker("invoice_view")) {
            if ($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray = $this->form_validation->error_array();
                    $retArray["status"] = false;
                    echo json_encode($retArray);
                    exit();
                } else {
                    $to = $this->input->post("to");
                    $subject = $this->input->post("subject");
                    $message = $this->input->post("message");
                    $id = $this->input->post("id");
                    $f = false;

                    if ($usertypeID == 3) {
                        if ((int) $id) {
                        $studentID = $this->session->userdata("loginuserID");
                        $getstudent = $this->studentrelation_m->get_single_student(["srstudentID" => $studentID,
                                "srschoolyearID" => $schoolyearID,]);

                        $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id,$schoolyearID);
                            if (customCompute($this->data["maininvoice"]) && $this->data["maininvoice"]->maininvoicestudentID == $getstudent->studentID) {
                                $f = true;
                            }
                        }
                    } elseif ($usertypeID == 4) {
                        if ((int) $id) {
                    $parentID       = $this->session->userdata("loginuserID");
                    $getStudents    = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,"srschoolyearID" => $schoolyearID,]);
                    $fetchStudent = pluck($getStudents,"srstudentID","srstudentID");
                            if (customCompute($fetchStudent)) {
                    $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id,$schoolyearID);
                                if (customCompute($this->data["maininvoice"])) {
                                    if (in_array($this->data["maininvoice"]->maininvoicestudentID,$fetchStudent)) {
                                            $f = true;
                                    }
                                }
                            }
                        }
                    } else {
                        $f = true;
                    }

                    if ($f) {
                        $id = $this->input->post("id");
                        if ((int) $id) {
                            $this->data["maininvoice"] = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($id);
                            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,]);
                            if (customCompute($this->data["maininvoice"])) {
                            $this->data["grandtotalandpayment"] = $this->grandtotalandpaidsingle($this->data["maininvoice"],$schoolyearID,$this->data["maininvoice"]->maininvoicestudentID);

                            $this->data["student"] = $this->student_m->get_single_student(["studentID" =>    $this->data["maininvoice"]->maininvoicestudentID,]);

                            $this->data["createuser"] = getNameByUsertypeIDAndUserID($this->data["maininvoice"]->maininvoiceusertypeID,$this->data["maininvoice"]->maininvoiceuserID);

                            $this->reportSendToMail("invoicemodule.css",$this->data,"invoice/print_preview",$to,$subject,$message);
                            $retArray["message"] = "Success";
                            $retArray["status"] = true;
                            echo json_encode($retArray);
                                exit();
                            } else {
                            $retArray["message"] = $this->lang->line("invoice_data_not_found");
                            echo json_encode($retArray);
                            exit();
                            }
                        } else {
                            $retArray["message"] = $this->lang->line("invoice_id_not_found");
                            echo json_encode($retArray);
                            exit();
                        }
                    } else {
                        $retArray["message"] = $this->lang->line("invoice_authorize");
                        echo json_encode($retArray);
                        exit();
                    }
                }
            } else {
                $retArray["message"] = $this->lang->line("invoice_postmethod");
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["message"] = $this->lang->line("invoice_permission");
            echo json_encode($retArray);
            exit();
        }
    }

    protected function payment_rules($invoices)
    {
        $rules = [
            [
            "field" => "paymentmethodID",
            "label" => $this->lang->line("invoice_paymentmethod"),
            "rules" =>"trim|required|xss_clean|max_length[11]|callback_check_valid_paymentmethod|callback_unique_paymentmethod",
            ],
            [
            "field" => "chart_account_id",
            "label" => 'Payment Method',
            "rules" =>"trim|required|xss_clean|max_length[11]",
            ],  
        ];

        if ($invoices) {
            if (customCompute($invoices)) {
                foreach ($invoices as $invoice) {
                    if ($invoice->paidstatus != 2) {
        $rules[] = ["field" => "paidamount_" . $invoice->invoiceID,
                    "label" => $this->lang->line("invoice_amount"),
                    "rules" =>"trim|xss_clean|max_length[15]|callback_unique_givenamount",
                    ];

                        // $rules[] = array(
                        //     'field' => 'weaver_'.$invoice->invoiceID,
                        //     'label' => $this->lang->line("invoice_weaver"),
                        //     'rules' => 'trim|xss_clean|max_length[15]|callback_unique_givenamount'
                        // );

        $rules[] = ["field" => "fine_" . $invoice->invoiceID,
                    "label" => $this->lang->line("invoice_fine"),
                    "rules" =>"trim|xss_clean|max_length[15]|callback_unique_givenamount",
                    ];
                    }
                }
            }
        }

        return $rules;
    }

    public function unique_givenamount($postValue)
    {
        if ($this->_amountgivenstatus == "") {
            $paidstatus = false;
            $weaverstatus = false;
            $finestatus = false;
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            if ((int) $id) {
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $id,]);
                if (customCompute($maininvoice)) {
                    $invoices = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $id,"deleted_at" => 1,]);
                    $invoicepaymentandweaver = $this->paymentdue(
                                                                    $maininvoice,
                                                                    $schoolyearID,
                                                                    $maininvoice->maininvoicestudentID
                                                                );
                    // var_dump($invoicepaymentandweaver);
                    // exit();
                    if (customCompute($invoices)) {
                        foreach ($invoices as $invoice) {
                            if ($invoice->paidstatus != 2) {
                                if ($this->input->post("paidamount_" . $invoice->invoiceID) != "") {
                                    $paidstatus = true;
                                }

                                if ($this->input->post("weaver_" . $invoice->invoiceID) != "") {
                                    $weaverstatus = true;
                                }

                                if ($this->input->post("fine_" . $invoice->invoiceID) != "") {
                                    $finestatus = true;
                                }
                            }

                            $amount = 0;
                            if (isset($invoicepaymentandweaver["totalamount"][$invoice->invoiceID])) {
                                    $amount +=(float) $invoicepaymentandweaver["totalamount"][$invoice->invoiceID];
                            }

                            if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                                    $amount -=(float) $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID];
                            }

                            if ((float) $amount < (float) ((float) $this->input->post("paidamount_" . $invoice->invoiceID) + (float) $this->input->post("weaver_" . $invoice->invoiceID))) {
                                if ($this->input->post("paidamount_" . $invoice->invoiceID) != "") {
                                    $this->_amountgivenstatuserror[] = (float) $this->input->post("paidamount_" . $invoice->invoiceID);
                                }

                                if ($this->input->post("weaver_" . $invoice->invoiceID) != "") {
                                    $this->_amountgivenstatuserror[] = (float) $this->input->post("weaver_" . $invoice->invoiceID);
                                }
                            }
                        }
                    }
                }
            }

            if (
                $this->session->userdata("usertypeID") == 1 ||
                $this->session->userdata("usertypeID") == 5) {
                if ($paidstatus || $weaverstatus || $finestatus) {
                    $this->_amountgivenstatus = true;
                    return true;
                } else {
                    $this->_amountgivenstatus = false;
                    $this->form_validation->set_message("unique_givenamount","The amount is required.");
                    return false;
                }
            } else {
                if ($paidstatus) {
                    $this->_amountgivenstatus = true;
                    return true;
                } else {
                    $this->_amountgivenstatus = false;
                    $this->form_validation->set_message("unique_givenamount","The amount is required.");
                    return false;
                }
            }
        } else {
            if ($this->_amountgivenstatus) {
                if ($postValue != "") {
                    if (in_array((float) $postValue,$this->_amountgivenstatuserror)) {
                    $this->form_validation->set_message("unique_givenamount","The amount is required.");
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_givenamount","The amount is required.");
                return false;
            }
        }
    }

    public function check_valid_paymentmethod()
    {
        $usertypeID = $this->session->userdata("usertypeID");
        $paymentMethod = ["Cash","Cheque","Bank","Paypal","Stripe","Payumoney","Voguepay",];
        if ($usertypeID == 1 || $usertypeID == 5) {
            if (
                in_array($this->input->post("paymentmethodID"), $paymentMethod)) {
                return true;
            } else {
                $this->form_validation->set_message("check_valid_paymentmethod","Payment method does not found.");
                return false;
            }
        } else {
            unset($paymentMethod[0], $paymentMethod[1]);
            if (
                in_array($this->input->post("paymentmethodID"), $paymentMethod)) {
                return true;
            } else {
                $this->form_validation->set_message("check_valid_paymentmethod","Payment method does not found.");
                return false;
            }
        }
    }

    public function unique_paymentmethod(){
        if ($this->input->post("paymentmethodID") === "0") {
            $this->form_validation->set_message("unique_paymentmethod","Payment method is required.");
            return false;
        } else {
            $api_config = [];
            $get_configs = $this->payment_settings_m->get_order_by_config();
            foreach ($get_configs as $key => $get_key) {
                $api_config[$get_key->config_key] = $get_key->value;
            }

            if ($this->input->post("paymentmethodID") == "Cash" ||
                $this->input->post("paymentmethodID") == "Cheque" ||
                $this->input->post("paymentmethodID") == "Bank") {
                return true;
            } elseif (
                $this->input->post("paymentmethodID") == "Paypal" &&
                $api_config["paypal_status"] == 1) {
                if ($api_config["paypal_api_username"] == "" ||
                    $api_config["paypal_api_password"] == "" ||
                    $api_config["paypal_api_signature"] == "") {
                    $this->form_validation->set_message("unique_paymentmethod","Paypal settings required");
                    return false;
                }
                return true;
            } elseif (
                $this->input->post("paymentmethodID") == "Stripe" &&
                $api_config["stripe_status"] == 1) {
                if ($api_config["stripe_secret"] == "") {
                $this->form_validation->set_message("unique_paymentmethod","Stripe settings required");
                    return false;
                }
                return true;
            } elseif (
                $this->input->post("paymentmethodID") == "Payumoney" &&
                $api_config["payumoney_status"] == 1) {
                if ($api_config["payumoney_key"] == "" ||
                    $api_config["payumoney_salt"] == "") {
                    $this->form_validation->set_message("unique_paymentmethod","Payumoney settings required");
                    return false;
                }
                return true;
            } elseif (
                $this->input->post("paymentmethodID") == "Voguepay" &&
                $api_config["voguepay_status"] == 1) {
                if ($api_config["voguepay_merchant_id"] == "" ||
                    $api_config["voguepay_merchant_ref"] == "" ||
                    $api_config["voguepay_developer_code"] == "") {
                    $this->form_validation->set_message("unique_paymentmethod","Voguepay settings required");
                    return false;
                }
                return true;
            } else {
                $this->form_validation->set_message("unique_paymentmethod","Payment settings required");
                return false;
            }
        }
    }

    public function payment()
    {
        if (permissionChecker("invoice_view")) {
            $this->data["headerassets"] = [ "css" =>[  "assets/select2/css/select2.css",
                                                        "assets/select2/css/select2-bootstrap.css",
                                                        "assets/datepicker/datepicker.css",],
                                            "js"  =>[   "assets/datepicker/datepicker.js",
                                                        "assets/select2/select2.js",],
                                           ];

            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            if ((int) $id) {
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $id,]);
                $this->data['bank_accounts']    = $this->invoice_m->get_bank_accounts();
                if (customCompute($maininvoice)) {
                    if ($maininvoice->maininvoicestatus != 2) {
                        $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>    $maininvoice->maininvoicestudentID,"srschoolyearID" => $schoolyearID,]);
                        $this->data["studentprofile"] = $this->studentrelation_m->get_single_student(["srstudentID" => $maininvoice->maininvoicestudentID,"srschoolyearID" => $schoolyearID,]);
                        if (customCompute($this->data["student"])) {
                            $usertypeID = $this->session->userdata("usertypeID");
                            $userID = $this->session->userdata("loginuserID");
                            $f = false;
                            if ($usertypeID == 3) {
                                if ($this->data["student"]->srstudentID == $userID) {
                                    $f = true;
                                }
                            } elseif ($usertypeID == 4) {
                                $parentID = $this->session->userdata("loginuserID");
                                $getStudents = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,"srschoolyearID" => $schoolyearID,]);
                                $fetchStudent = pluck($getStudents,"srstudentID","srstudentID");
                                if (customCompute($fetchStudent)) {
                                    if (in_array($this->data["student"]->srstudentID,$fetchStudent)) {
                                        $f = true;
                                    }
                                }
                            } else {
                                $f = true;
                            }

                            if ($f) {
                                $this->data["usertype"] = $this->usertype_m->get_single_usertype(["usertypeID" => 3,]);
                                $this->data["class"] = $this->classes_m->general_get_single_classes(["classesID" => $this->data["student"]->srclassesID,]);
                                $this->data["section"] = $this->section_m->general_get_single_section(["sectionID" => $this->data["student"]->srsectionID,]);

                                // $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $id, 'deleted_at' => 1));
                                $this->data["invoices"] = $this->invoice_m->get_invoice_order($id);
                                // if (isset($_GET['dev'])) {
                                //     echo '<pre>';
                                //     print_r($this->data['invoices']);
                                //     echo '</pre>';
                                //     echo '<br>';
                                // }

                                $this->data["maininvoices"] = $this->maininvoice_m->get_order_by_maininvoice(["maininvoiceID" => $id]);
                                // if (isset($_GET['dev'])){
                                //     echo 'maininvoices array';
                                //     echo '<br>';
                                //     echo '<pre>';
                                //     print_r($this->data['maininvoices']);
                                //     echo '</pre>';
                                // }

                                $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"feetypes","feetypesID");

                                $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,$schoolyearID,$this->data["student"]->srstudentID);
                                // if (isset($_GET['dev'])){
                                //     echo '<br>';
                                //     echo 'invoicepaymentandweaver<br>';
                                //     echo '<pre>';
                                //     print_r($this->data['invoicepaymentandweaver']);
                                //     echo '</pre>';
                                //     echo '<br>';
                                // }

                                $get_configs = $this->payment_settings_m->get_order_by_config();
                                foreach ($get_configs as $key => $get_key) {
                                    $api_config[$get_key->config_key] =  $get_key->value;
                                }
                                $this->data["payment_settings"] = $api_config;
                                 if ($_POST) {
                                    $rules = $this->payment_rules($this->data["invoices"]);

                                $this->form_validation->set_rules($rules);
                                if ($this->form_validation->run() == false) {
                                    $this->data["subview"] = "invoice/payment";
                                    $this->load->view("_layout_main",$this->data);
                                    } else {
                                        if ($this->input->post("paymentmethodID") == "Cash" ||
                                            $this->input->post("paymentmethodID") == "Cheque" ||
                                            $this->input->post("paymentmethodID") == "Bank") {
                                            if (customCompute($this->data["invoices"])) {
                                                $invoicepaymentandweaver =    $this->data["invoicepaymentandweaver"];
                                                $globalpayment = ["classesID" =>$this->data["student"]->srclassesID,
                                                "sectionID" =>$this->data["student"]->srsectionID,
                                                "studentID" =>$maininvoice->maininvoicestudentID,
                                                "clearancetype" =>"partial",
                                                "invoicename" =>$this->data["student"]->srregisterNO . "-" . $this->data["student"]->srname,
                                                "invoicedescription" => "",
                                                "paymentyear" => date("Y",strtotime($this->input->post("date"))),
                                                "schoolyearID" => $schoolyearID,
                                                ];
                                                $this->globalpayment_m->insert_globalpayment($globalpayment);
                                                $globalLastID = $this->db->insert_id();

                                                $due = 0;
                                                $paidstatus = 0;
                                                $globalstatus = [];
                                                foreach ($this->data["invoices"]as $key => $invoice) {
                                                if ($invoice->paidstatus !=2) {
                                                    if (isset($invoicepaymentandweaver["totalamount"][$invoice->invoiceID])) {
                                                    $due =        (float) $invoicepaymentandweaver["totalamount"][$invoice->invoiceID];

                                                        if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                                                            $due =     (float) ($due - $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                                        }

                                                        if (isset($invoicepaymentandweaver["totalpayment"][$invoice->invoiceID])) {
                                                            $due = (float) ($due -  $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                                        }

                                                        if (isset($invoicepaymentandweaver["totalweaver"][$invoice->invoiceID])) {
                                                            $due =  (float) ($due -$invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                                        }
                                                    }

                                                    $totalPayment = 0;
                                                    if ($this->input->post("paidamount_" .$invoice->invoiceID) > 0) {
                                                        $totalPayment += (float) $this->input->post("paidamount_" .$invoice->invoiceID);
                                                    }

                                                    $totaldiscount = 0;
                                                    if ($this->input->post("weaver_" .
                                                $invoice->invoiceID) > 0
                                    ) {
                                        $totaldiscount = $this->input->post("weaver_" .
                                                $invoice->invoiceID);
                                                        // echo '<br>';
                                                        // $totalPayment += (float) $this->input->post('weaver_'.$invoice->invoiceID);
                                                        // exit();
                                                    }

                                                    // if (isset($_GET['dev'])){
                                                    // echo $totalPayment;
                                                    // echo '<br>';
                                                    // echo $due;
                                                    $p_amount = $totalPayment;
                                                    // exit();
                                                    if (($p_amount >
                                            $invoice->maininvoicenet_fee ||
                                            $p_amount ==            $invoice->maininvoicenet_fee) &&
                                        $invoice->maininvoicestatus !=        2
                                    ) {
                                        $mainnet_fee =        $invoice->maininvoicenet_fee;
                                                        if ($invoice->maininvoicestatus ==        1) {
                                            $main_net =            $invoice->maininvoicetotal_fee -
                                                $invoice->maininvoice_discount -
                                                $totaldiscount;
                                            $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2,
                                                    "maininvoicecreate_date" => date("Y-m-d",strtotime($this->input->post("date"))),
                                                    "maininvoicenet_fee" =>    $invoice->maininvoicenet_fee -
                                        $p_amount -
                                        $totaldiscount,
                                                    "maininvoice_discount" =>    $invoice->maininvoice_discount +
                                        $totaldiscount,],
                                                $invoice->maininvoiceID);
                                                        } else {
                                            $main_net =            $invoice->maininvoicenet_fee -
                                                $totaldiscount;
                                            $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2,
                                                    "maininvoicecreate_date" => date("Y-m-d",strtotime($this->input->post("date"))),
                                                    "maininvoicenet_fee" =>    $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                                    "maininvoice_discount" =>    $invoice->maininvoice_discount + $totaldiscount,],
                                                $invoice->maininvoiceID);
                                                        }

                                        $paymentArray = ["invoiceID"         => $invoice->invoiceID,
                                            "schoolyearID"      => $schoolyearID,
                                            "studentID"         => $invoice->studentID,
                                            "paymentamount"     => $invoice->maininvoicenet_fee,
                                            "paymenttype"       => ucfirst($this->input->post("paymentmethodID")),
                                            "paymentdate"       => date("Y-m-d",strtotime($this->input->post("date"))),
                                            "paymentday"        => date("d",strtotime($this->input->post("date"))),
                                            "paymentmonth"      => date("m",strtotime($this->input->post("date"))),
                                            "paymentyear"       => date("Y",strtotime($this->input->post("date"))),
                                            "userID"            => $this->session->userdata("loginuserID"),
                                            "usertypeID"        => $this->session->userdata("usertypeID"),
                                            "uname"             => $this->session->userdata("name"),
                                            "transactionID"     => "CASHANDCHEQUE" . random19(),
                                            "globalpaymentID"   => $globalLastID,
                                                        ];
                                        $this->payment_m->insert_payment($paymentArray);
                                        $paymentLastID = $this->db->insert_id();
                                        $paidstatus = 2;
                                        $this->invoice_m->update_invoice(
                                               ["paidstatus"        =>  $paidstatus,
                                                "pay_type"          =>  "cpv",
                                                "create_date"       =>  date("Y-m-d",strtotime( $this->input->post("date"))),
                                                "net_fee"           =>  $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                                "discount"          =>  $invoice->maininvoice_discount + $totaldiscount,
                                                ],
                                                $invoice->invoiceID);
                                        $this->globalpayment_m->update_globalpayment(["clearancetype" =>"paid",],$globalLastID);
                                        $p_amount   =   $p_amount - $mainnet_fee;
                                                    } elseif ($p_amount < $invoice->maininvoicenet_fee && $p_amount > 0 && $invoice->maininvoicestatus !=2) {
                                        $mainnet_fee =        $invoice->maininvoicenet_fee;
                                        $this->maininvoice_m->update_maininvoice(
                                            ["maininvoicenet_fee"   =>  $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                            "maininvoice_discount"  =>  $invoice->maininvoice_discount + $totaldiscount,
                                            ], 
                                            $invoice->maininvoiceID);
                                        $this->maininvoice_m->update_maininvoice(
                                            ["maininvoicestatus" => 1,
                                            "maininvoicecreate_date" => date("Y-m-d",strtotime($this->input->post("date"))),
                                            ],
                                            $invoice->maininvoiceID);
                                                        // $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                        $paymentArray = ["invoiceID"         => $invoice->invoiceID,
                                            "schoolyearID"      => 1,
                                            "studentID"         => $invoice->studentID,
                                            "paymentamount"     => $p_amount,
                                            "paymenttype"       => ucfirst($this->input->post("paymentmethodID")),
                                            "paymentdate"       => date("Y-m-d",strtotime($this->input->post("date"))),
                                            "paymentday"        => date("d",strtotime($this->input->post("date"))),
                                            "paymentmonth"      => date("m",strtotime($this->input->post("date"))),
                                            "paymentyear"       => date("Y",strtotime($this->input->post("date"))),
                                            "userID"            => $this->session->userdata("loginuserID"),
                                            "usertypeID"        => $this->session->userdata("usertypeID"),
                                            "uname"             => $this->session->userdata("name"),
                                            "transactionID"     => "CASHANDCHEQUE" . random19(),
                                            "globalpaymentID"   => $globalLastID,
                                                        ];
                                        $this->payment_m->insert_payment($paymentArray);
                                        $paymentLastID = $this->db->insert_id();
                                        $paidstatus = 1;
                                        $this->invoice_m->update_invoice(
                                            [   "paidstatus"    => $paidstatus,
                                                "pay_type"      =>"cpv",
                                                "create_date"   => date("Y-m-d",strtotime($this->input->post("date"))),
                                                "net_fee"       => $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                                "discount"      => $invoice->maininvoice_discount + $totaldiscount,
                                            ],
                                            $invoice->invoiceID);
                                            $p_amount = $p_amount - $mainnet_fee;
                                                    } elseif ($p_amount == 0 && $totaldiscount == $invoice->maininvoicenet_fee) {

                                        $mainnet_fee =        $invoice->maininvoicenet_fee;
                                        $this->maininvoice_m->update_maininvoice(
                                            [   "maininvoicenet_fee"    => 0,
                                                "maininvoice_discount"  => $invoice->maininvoicenet_fee,
                                            ],
                                            $invoice->maininvoiceID);
                                        $this->maininvoice_m->update_maininvoice(
                                            [   "maininvoicestatus"         => 2,
                                                "maininvoicecreate_date"    => date("Y-m-d",strtotime($this->input->post("date"))),
                                            ],
                                            $invoice->maininvoiceID);
                                                        // $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                        $paymentArray = ["invoiceID"         =>            $invoice->invoiceID,
                                            "schoolyearID"      => 1,
                                            "studentID"         =>            $invoice->studentID,
                                            "paymentamount"     => $p_amount,
                                            "paymenttype"       => ucfirst($this->input->post("paymentmethodID")),
                                            "paymentdate"       => date("Y-m-d",strtotime($this->input->post("date"))),
                                            "paymentday"        => date("d",strtotime($this->input->post("date"))),
                                            "paymentmonth"      => date("m",strtotime($this->input->post("date"))),
                                            "paymentyear"       => date("Y",strtotime($this->input->post("date"))),
                                            "userID"            => $this->session->userdata("loginuserID"),
                                            "usertypeID"        => $this->session->userdata("usertypeID"),
                                            "uname"             => $this->session->userdata("name"),
                                            "transactionID"     =>"CASHANDCHEQUE" . random19(),
                                            "globalpaymentID"   => $globalLastID,
                                                        ];
                                        $this->payment_m->insert_payment($paymentArray);
                                        $paymentLastID = $this->db->insert_id();
                                        $paidstatus = 2;
                                        $this->invoice_m->update_invoice(
                                            [   "paidstatus"    => $paidstatus,
                                                "pay_type"      =>"cpv",
                                                "create_date"   => date("Y-m-d",strtotime($this->input->post("date"))),
                                                "net_fee"       => 0,
                                                "discount"      => $invoice->maininvoicenet_fee,
                                            ],
                                            $invoice->invoiceID);
                                            $p_amount = 0;
                                                    } elseif ($p_amount == 0 && $totaldiscount < $invoice->maininvoicenet_fee) {
                                        $mainnet_fee =        $invoice->maininvoicenet_fee;
                                        $this->maininvoice_m->update_maininvoice(
                                            ["maininvoicenet_fee"    => $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                                "maininvoice_discount"  => $invoice->maininvoice_discount +$totaldiscount,
                                            ],
                                            $invoice->maininvoiceID);
                                        $this->maininvoice_m->update_maininvoice(
                                            [   "maininvoicestatus" => 1,
                                                "maininvoicecreate_date" => date("Y-m-d",strtotime($this->input->post("date"))),
                                            ],
                                            $invoice->maininvoiceID);
                                                        // $this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('maininvoiceID' => $invoice->maininvoiceID, 'deleted_at' => 1));
                                        $paymentArray = ["invoiceID"         => $invoice->invoiceID,
                                            "schoolyearID"      => 1,
                                            "studentID"         => $invoice->studentID,
                                            "paymentamount"     => $p_amount,
                                            "paymenttype"       => ucfirst($this->input->post("paymentmethodID")),
                                            "paymentdate"       => date("Y-m-d",strtotime($this->input->post("date"))),
                                            "paymentday"        => date("d",strtotime($this->input->post("date"))),
                                            "paymentmonth"      => date("m",strtotime($this->input->post("date"))),
                                            "paymentyear"       => date("Y",strtotime($this->input->post("date"))),
                                            "userID"            => $this->session->userdata("loginuserID"),
                                            "usertypeID"        => $this->session->userdata("usertypeID"),
                                            "uname"             => $this->session->userdata("name"),
                                            "transactionID"     =>"CASHANDCHEQUE" . random19(),
                                            "globalpaymentID"   => $globalLastID,
                                                        ];
                                        $this->payment_m->insert_payment($paymentArray);
                                        $paymentLastID = $this->db->insert_id();
                                        $paidstatus = 1;
                                        $this->invoice_m->update_invoice(
                                            [   "paidstatus"    =>  $paidstatus,
                                                "pay_type"      =>  "cpv",
                                                "create_date"   =>  date("Y-m-d",strtotime($this->input->post("date"))),
                                                "net_fee"       =>  $invoice->maininvoicenet_fee - $p_amount - $totaldiscount,
                                                "discount"      =>  $invoice->maininvoice_discount + $totaldiscount,
                                            ],
                                            $invoice->invoiceID);
                                            $p_amount = 0;
                                                    }

                                                    if ((float) $this->input->post("weaver_" .$invoice->invoiceID) > (float) 0 ||
                                                        (float) $this->input->post("fine_" .$invoice->invoiceID) > (float) 0) {
                                        $weaverandfineArray = ["globalpaymentID"   => $globalLastID,
                                            "invoiceID"         => $invoice->invoiceID,
                                            "paymentID"         => $paymentLastID,
                                            "studentID"         => $invoice->studentID,
                                            "schoolyearID"      => $schoolyearID,
                                            "weaver"            => $this->input->post("weaver_" . $invoice->invoiceID) == ""? 0 : $this->input->post("weaver_" . $invoice->invoiceID),
                                            "fine"              => $this->input->post("fine_" .$invoice->invoiceID) == ""? 0  : $this->input->post("fine_" . $invoice->invoiceID),
                                                        ];
                                        $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray);
                                                    }


                                                    $journal_entries_id   =     $this->invoice_m->journal_entries_id(array('studentID'=>$paymentArray['studentID']));

                                                    $journal_items_ar[]  = array(
                                                                'journal'       =>  $journal_entries_id, 
                                                                'referenceID'   =>  $paymentLastID,
                                                                'reference_type'=>  'payment',
                                                                'account'       =>  $this->data['siteinfos']->student_cr_account, 
                                                                'description'   =>  'Payment for Invoice '.$invoice->refrence_no, 
                                                                'feetypeID'     =>  $invoice->feetypeID, 
                                                                'debit'         =>  0, 
                                                                'credit'        =>  $paymentArray['paymentamount'], 
                                                                'entry_type'    =>  'CR', 
                                                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                                                    $journal_items_ar[]  = array(
                                                                'journal'       =>  $journal_entries_id, 
                                                                'referenceID'   =>  $paymentLastID,
                                                                'reference_type'=>  'payment',
                                                                'feetypeID'     =>  $invoice->feetypeID,
                                                                'account'       =>  $this->input->post("chart_account_id"), 
                                                                'description'   =>  'Payment for Invoice '.$invoice->refrence_no, 
                                                                'debit'         =>  $paymentArray['paymentamount'], 
                                                                'entry_type'    =>  'DR', 
                                                                'credit'        =>  0, 
                                                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                                                    $student_ledgers_ar[]  = array(
                                                                'journal_entries_id'       =>  $journal_entries_id, 
                                                                'maininvoice_id'           =>  $paymentLastID, 
                                                                'reference_type'           =>  'payment', 
                                                                'feetypeID'                =>  $invoice->feetypeID, 
                                                                'date'                     =>  date('Y-m-d'), 
                                                                'type'                     =>  'CR', 
                                                                'account'                  =>  $this->data['siteinfos']->student_cr_account, 
                                                                'vr_no'                    =>  $invoice->refrence_no, 
                                                                'narration'                =>  'Payment for Invoice '.$invoice->refrence_no, 
                                                                'debit'                    =>  0, 
                                                                'credit'                   =>  $paymentArray['paymentamount'], 
                                                                'balance'                  =>  $paymentArray['paymentamount'],  
                                                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                                                'updated_at'               =>  date('Y-m-d h:i:s'), );
                                                    $student_ledgers_ar[]  = array(
                                                                'journal_entries_id'       =>  $journal_entries_id, 
                                                                'maininvoice_id'           =>  $paymentLastID, 
                                                                'reference_type'           =>  'payment',
                                                                'feetypeID'                =>  $invoice->feetypeID,  
                                                                'date'                     =>  date('Y-m-d'), 
                                                                'type'                     =>  'DR', 
                                                                'account'                  =>  $this->input->post("chart_account_id"), 
                                                                'vr_no'                    =>  $invoice->refrence_no, 
                                                                'narration'                =>  'Payment for Invoice '.$invoice->refrence_no, 
                                                                'debit'                    =>  $paymentArray['paymentamount'], 
                                                                'credit'                   =>  0, 
                                                                'balance'                  =>  $paymentArray['paymentamount'],  
                                                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                                                'updated_at'               =>  date('Y-m-d h:i:s'), );
                                                     

                                                                   
                                                      $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                                                      $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );

                                       

                                        $this->session->set_flashdata("success", "Success" );
                                        redirect(base_url("invoice/view/" .$id));

                                                     
                                                }
                                                }

                                                
                                                $this->session->set_flashdata("success",
                                    "Success");
                                                redirect(base_url("invoice/view/" . $id));
                                            }
                                        } else {
                                            $get_configs = $this->payment_settings_m->get_order_by_config();
                                            $this->post_data = $this->input->post();
                                            $this->post_data["id"] = $this->uri->segment(3);
                                            $this->invoice_data = $this->maininvoice_m->get_maininvoice_with_studentrelation_by_maininvoiceID($this->post_data["id"]);

                                            $this->post_data["amount"] = 0;
                                            if (customCompute($this->data["invoices"])) {
                                                foreach ($this->data["invoices"]as $key => $invoice) {
                                                    if (isset($this->invoice_data->feetype)) {
                                        $this->invoice_data->feetype .=    $invoice->feetype . ", ";
                                                    } else {
                                        $this->invoice_data->feetype =    $invoice->feetype . ", ";
                                                    }

                                                    if (isset($this->post_data["amount"])) {
                                        $this->post_data["amount"] += (float) $this->input->post("paidamount_" .$invoice->invoiceID);
                                                    } else {
                                        $this->post_data["amount"] = (float) $this->input->post("paidamount_" .$invoice->invoiceID);
                                                    }

                                                    if (isset($this->post_data["fine"])) {
                                        $this->post_data["fine"] += (float) $this->input->post("fine_" .$invoice->invoiceID);
                                                    } else {
                                        $this->post_data["fine"] = (float) $this->input->post("fine_" .$invoice->invoiceID);
                                                    }

                                                    if (isset($this->post_data["weaver"])) {
                                        $this->post_data["weaver"] += (float) $this->input->post("weaver_" .$invoice->invoiceID);
                                                    } else {
                                        $this->post_data["weaver"] = (float) $this->input->post("weaver_" .$invoice->invoiceID);
                                                    }
                                                }
                                            }

                                            if ($this->input->post("paymentmethodID") == "Paypal") {
                                                $this->Paypal();
                                            } elseif ($this->input->post("paymentmethodID") == "Stripe") {
                                                $rulesStripe = $this->stripe_rules();
                                                $this->form_validation->set_rules($rulesStripe);
                                                if ($this->form_validation->run() ==    false) {
                                                    $this->data["subview"] ="invoice/payment";
                                                    $this->load->view("_layout_main",
                                        $this->data);
                                                } else {
                                                    $this->stripe();
                                                }
                                            } elseif ($this->input->post("paymentmethodID") == "Payumoney") {
                                                $rulesPayumoney = $this->payumoney_rules();
                                                $this->form_validation->set_rules($rulesPayumoney);
                                                if ($this->form_validation->run() ==    false) {
                                                    $this->data["subview"] ="invoice/payment";
                                                    $this->load->view("_layout_main",
                                        $this->data);
                                                } else {
                                                    $this->payumoney();
                                                }
                                            } elseif ($this->input->post("paymentmethodID") == "Voguepay") {
                                                $this->voguepay();
                                            } else {
                                                $this->session->set_flashdata("error",
                                    "You are not authorized");
                                                redirect(base_url("invoice/payment/$id"    ));
                                            }
                                        }
                                    }
                                                            } else {
    $this->data["subview"] = "invoice/payment";
    $this->load->view("_layout_main",    $this->data);
                                }
                            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
                            }
                        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
        $this->data["subview"] = "error";
        $this->load->view("_layout_main", $this->data);
                    }
                } else {
    $this->data["subview"] = "error";
    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function processolpayments()
    {
            $array = array('uploaded' => 0 );
            
            $payments   =   $this->payment_m->get_payment_join_invoice_by_array($array,2000);
            $student_ledgers_ar     =   [];
            $journal_items_ar       =   [];
            foreach ($payments as $p) {
               
           
                $journal_entries_id   =   $this->invoice_m->journal_entries_id(array('studentID'=>$p->studentID));

                $chart_account_id   =   194;

                if ($p->pay_type=='bpv') {
                   $chart_account_id   =   194;
                }

                if ($p->pay_type=='cpv') {
                   $chart_account_id   =   191;
                }



                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $p->paymentID,
                            'reference_type'=>  'payment',
                            'account'       =>  $this->data['siteinfos']->student_cr_account, 
                            'description'   =>  'Payment for Invoice '.$p->refrence_no, 
                            'feetypeID'     =>  $p->feetypeID, 
                            'debit'         =>  0, 
                            'credit'        =>  $p->paymentamount, 
                            'entry_type'    =>  'CR', 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                            'journal'       =>  $journal_entries_id, 
                            'referenceID'   =>  $p->paymentID,
                            'reference_type'=>  'payment',
                            'feetypeID'     =>  $p->feetypeID,
                            'account'       =>  $chart_account_id, 
                            'description'   =>  'Payment for Invoice '.$p->refrence_no, 
                            'debit'         =>  $p->paymentamount, 
                            'entry_type'    =>  'DR', 
                            'credit'        =>  0, 
                            'created_at'    =>  date('Y-m-d h:i:s'), 
                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $p->paymentID, 
                            'reference_type'           =>  'payment', 
                            'feetypeID'                =>  $p->feetypeID, 
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'CR', 
                            'account'                  =>  $this->data['siteinfos']->student_cr_account, 
                            'vr_no'                    =>  $p->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$p->refrence_no, 
                            'debit'                    =>  0, 
                            'credit'                   =>  $p->paymentamount, 
                            'balance'                  =>  $p->paymentamount,  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                            'journal_entries_id'       =>  $journal_entries_id, 
                            'maininvoice_id'           =>  $p->paymentID, 
                            'reference_type'           =>  'payment',
                            'feetypeID'                =>  $p->feetypeID,  
                            'date'                     =>  date('Y-m-d'), 
                            'type'                     =>  'DR', 
                            'account'                  =>  $chart_account_id, 
                            'vr_no'                    =>  $p->refrence_no, 
                            'narration'                =>  'Payment for Invoice '.$p->refrence_no, 
                            'debit'                    =>  $p->paymentamount, 
                            'credit'                   =>  0, 
                            'balance'                  =>  $p->paymentamount,  
                            'created_at'               =>  date('Y-m-d h:i:s'), 
                            'updated_at'               =>  date('Y-m-d h:i:s'), );

                $array = array('uploaded' => 1 );
                $this->payment_m->update_payment($array,$p->paymentID);
                 
                 }
                               
                  $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                  $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );
                  echo count($payments)."  entries done please reload this page";
    }

    public function viewpayment()
    {
        if (permissionChecker("invoice_view")) {
            $globalpaymentID = htmlentities(
                escapeString($this->uri->segment(3)));
            $maininvoiceID = htmlentities(escapeString($this->uri->segment(4)));
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            if ((int) $globalpaymentID && (int) $maininvoiceID) {
                $globalpayment = $this->globalpayment_m->get_single_globalpayment(
                    ["globalpaymentID" => $globalpaymentID,
        "schoolyearID" => $schoolyearID,
                    ]);
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,
    "maininvoiceschoolyearID" => $schoolyearID,]);
                if (
                    customCompute($maininvoice) &&
                    customCompute($globalpayment)) {
    $usertypeID = $this->session->userdata("usertypeID");
    $userID = $this->session->userdata("loginuserID");

    $f = false;
                    if ($usertypeID == 3) {
        $getstudent = $this->studentrelation_m->get_single_studentrelation(["srstudentID" => $globalpayment->studentID,
                "srschoolyearID" =>    $globalpayment->schoolyearID,]);
                        if (customCompute($getstudent)) {
                            if ($getstudent->srstudentID == $userID) {
                $f = true;
                            }
                        }
                    } elseif ($usertypeID == 4) {
        $parentID = $this->session->userdata("loginuserID");
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $getStudents = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,
                "srschoolyearID" => $schoolyearID,]);
        $fetchStudent = pluck($getStudents,
            "srstudentID",
            "srstudentID");
                        if (customCompute($fetchStudent)) {
                            if (in_array($globalpayment->studentID,$fetchStudent)) {
                $f = true;
                            }
                        }
                    } else {
        $f = true;
                    }

                    if ($f) {
        $studentrelation = $this->studentrelation_m->get_single_studentrelation(["srstudentID" => $globalpayment->studentID,
                "srschoolyearID" =>    $globalpayment->schoolyearID,]);
                        if (customCompute($studentrelation)) {
            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");
            $this->data["student"] = $this->student_m->get_single_student(["studentID" => $globalpayment->studentID,]);
            $this->data["invoices"] = pluck($this->invoice_m->get_order_by_invoice(["maininvoiceID" => $maininvoiceID,]),
                "obj",
                "invoiceID");

            $this->payment_m->order_payment("paymentID", "asc");
            $this->data["payments"] = $this->payment_m->get_order_by_payment(["globalpaymentID" => $globalpaymentID,]);
            $this->data["weaverandfines"] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(["globalpaymentID" => $globalpaymentID]),
                "obj",
                "paymentID");

            $this->data["paymenttype"] = "";
                            if (customCompute($this->data["payments"])) {
                                foreach ($this->data["payments"] as $payment) {
    $this->data["paymenttype"] =
        $payment->paymenttype;
                                    break;
                                }
                            }

            $this->data["studentrelation"] = $studentrelation;
            $this->data["globalpayment"] = $globalpayment;
            $this->data["maininvoice"] = $maininvoice;

            $this->data["subview"] = "invoice/viewpayment";
            $this->load->view("_layout_main", $this->data);
                        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
        $this->data["subview"] = "error";
        $this->load->view("_layout_main", $this->data);
                    }
                } else {
    $this->data["subview"] = "error";
    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    public function print_previewviewpayment()
    {
        if (permissionChecker("invoice_view")) {
            $globalpaymentID = htmlentities(
                escapeString($this->uri->segment(3)));
            $maininvoiceID = htmlentities(escapeString($this->uri->segment(4)));
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            if ((int) $globalpaymentID && (int) $maininvoiceID) {
                $globalpayment = $this->globalpayment_m->get_single_globalpayment(
                    ["globalpaymentID" => $globalpaymentID,
        "schoolyearID" => $schoolyearID,
                    ]);
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,
    "maininvoiceschoolyearID" => $schoolyearID,]);
                if (
                    customCompute($maininvoice) &&
                    customCompute($globalpayment)) {
    $usertypeID = $this->session->userdata("usertypeID");
    $userID = $this->session->userdata("loginuserID");

    $f = false;
                    if ($usertypeID == 3) {
        $getstudent = $this->studentrelation_m->get_single_studentrelation(["srstudentID" => $globalpayment->studentID,
                "srschoolyearID" =>    $globalpayment->schoolyearID,]);
                        if (customCompute($getstudent)) {
                            if ($getstudent->srstudentID == $userID) {
                $f = true;
                            }
                        }
                    } elseif ($usertypeID == 4) {
        $parentID = $this->session->userdata("loginuserID");
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $getStudents = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,
                "srschoolyearID" => $schoolyearID,]);
        $fetchStudent = pluck($getStudents,
            "srstudentID",
            "srstudentID");
                        if (customCompute($fetchStudent)) {
                            if (in_array($globalpayment->studentID,$fetchStudent)) {
                $f = true;
                            }
                        }
                    } else {
        $f = true;
                    }

                    if ($f) {
        $studentrelation = $this->studentrelation_m->get_single_studentrelation(["srstudentID" => $globalpayment->studentID,
                "srschoolyearID" =>    $globalpayment->schoolyearID,]);
                        if (customCompute($studentrelation)) {
            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");
            $this->data["student"] = $this->student_m->get_single_student(["studentID" => $globalpayment->studentID,]);
            $this->data["invoices"] = pluck($this->invoice_m->get_order_by_invoice(["maininvoiceID" => $maininvoiceID,]),
                "obj",
                "invoiceID");
            $this->payment_m->order_payment("paymentID", "asc");
            $this->data["payments"] = $this->payment_m->get_order_by_payment(["globalpaymentID" => $globalpaymentID,]);
            $this->data["weaverandfines"] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(["globalpaymentID" => $globalpaymentID]),
                "obj",
                "paymentID");

            $this->data["paymenttype"] = "";
                            if (customCompute($this->data["payments"])) {
                                foreach ($this->data["payments"] as $payment) {
    $this->data["paymenttype"] =
        $payment->paymenttype;
                                    break;
                                }
                            }

            $this->data["studentrelation"] = $studentrelation;
            $this->data["globalpayment"] = $globalpayment;
            $this->data["maininvoice"] = $maininvoice;

            $this->reportPDF("invoicemodulepayment.css",
                $this->data,
                "invoice/print_previewviewpayment");
                        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
        $this->data["subview"] = "error";
        $this->load->view("_layout_main", $this->data);
                    }
                } else {
    $this->data["subview"] = "error";
    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    protected function viewpayment_send_mail_rules()
    {
        $rules = [
            ["field" => "globalpaymentID",
"label" => $this->lang->line("invoice_globalpaymentID"),
"rules" =>"trim|required|xss_clean|numeric|callback_valid_data",
            ],
            ["field" => "maininvoiceID",
"label" => $this->lang->line("invoice_maininvoiceID"),
"rules" =>"trim|required|xss_clean|numeric|callback_valid_data",
            ],
            ["field" => "to",
"label" => $this->lang->line("to"),
"rules" => "trim|required|xss_clean|valid_email",
            ],
            ["field" => "subject",
"label" => $this->lang->line("subject"),
"rules" => "trim|required|xss_clean",
            ],
            ["field" => "message",
"label" => $this->lang->line("message"),
"rules" => "trim|xss_clean",
            ],
        ];
        return $rules;
    }

    public function viewpayment_send_mail()
    {
        $retArray["status"] = false;
        $retArray["message"] = "";
        if (permissionChecker("invoice_view")) {
            if ($_POST) {
                $rules = $this->viewpayment_send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
    $retArray = $this->form_validation->error_array();
    $retArray["status"] = false;
                    echo json_encode($retArray);
                    exit();
                } else {
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);
    $globalpaymentID = $this->input->post("globalpaymentID");
    $maininvoiceID = $this->input->post("maininvoiceID");
    $to = $this->input->post("to");
    $subject = $this->input->post("subject");
    $message = $this->input->post("message");

                    if ((int) $globalpaymentID && (int) $maininvoiceID) {
        $globalpayment = $this->globalpayment_m->get_single_globalpayment(["globalpaymentID" => $globalpaymentID,
                "schoolyearID" => $schoolyearID,]);
        $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,
                "maininvoiceschoolyearID" => $schoolyearID,]);

                        if (customCompute($maininvoice) &&
                            customCompute($globalpayment)) {
            $usertypeID = $this->session->userdata("usertypeID");
            $userID = $this->session->userdata("loginuserID");

            $f = false;
                            if ($usertypeID == 3) {
                $getstudent = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>        $globalpayment->studentID,
                        "srschoolyearID" =>        $globalpayment->schoolyearID,]);
                                if (customCompute($getstudent)) {
                                    if ($getstudent->srstudentID == $userID) {
        $f = true;
                                    }
                                }
                            } elseif ($usertypeID == 4) {
                $parentID = $this->session->userdata("loginuserID");
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $getStudents = $this->studentrelation_m->get_order_by_student(["parentID" => $parentID,
                        "srschoolyearID" => $schoolyearID,]);
                $fetchStudent = pluck($getStudents,
                    "srstudentID",
                    "srstudentID");
                                if (customCompute($fetchStudent)) {
                                    if (in_array($globalpayment->studentID,        $fetchStudent
                    )) {
        $f = true;
                                    }
                                }
                            } else {
                $f = true;
                            }

                            if ($f) {
                $studentrelation = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>        $globalpayment->studentID,
                        "srschoolyearID" =>        $globalpayment->schoolyearID,]);
                                if (customCompute($studentrelation)) {
    $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                        "feetypes",
                        "feetypesID");
    $this->data["student"] = $this->student_m->get_single_student(["studentID" =>        $globalpayment->studentID,]);
    $this->data["invoices"] = pluck($this->invoice_m->get_order_by_invoice([
                            "maininvoiceID" => $maininvoiceID,    ]),
                        "obj",
                        "invoiceID");
    $this->payment_m->order_payment("paymentID",
                        "asc");
    $this->data["payments"] = $this->payment_m->get_order_by_payment(["globalpaymentID" => $globalpaymentID,]);
    $this->data["weaverandfines"] = pluck($this->weaverandfine_m->get_order_by_weaverandfine([
                                "globalpaymentID" => $globalpaymentID,        ]
                    ),
                        "obj",
                        "paymentID");

    $this->data["paymenttype"] = "";
                                    if (customCompute($this->data["payments"])) {
                                        foreach ($this->data["payments"]
                                            as $payment
                    ) {
            $this->data["paymenttype"] =
                $payment->paymenttype;
                                            break;
                                        }
                                    }

    $this->data["studentrelation"] = $studentrelation;
    $this->data["globalpayment"] = $globalpayment;
    $this->data["maininvoice"] = $maininvoice;

    $this->reportSendToMail("invoicemodulepayment.css",    $this->data,
                        "invoice/print_previewviewpayment",    $to,    $subject,    $message);
    $retArray["message"] = "Success";
    $retArray["status"] = true;
                                    echo json_encode($retArray);
                                } else {
    $retArray["message"] = $this->lang->line("invoice_data_not_found");
                                    echo json_encode($retArray);
                                    exit();
                                }
                            } else {
                $retArray["message"] = $this->lang->line("invoice_data_not_found");
                                echo json_encode($retArray);
                                exit();
                            }
                        } else {
            $retArray["message"] = $this->lang->line("invoice_data_not_found");
                            echo json_encode($retArray);
                            exit();
                        }
                    } else {
        $retArray["message"] = $this->lang->line("invoice_data_not_found");
                        echo json_encode($retArray);
                        exit();
                    }
                }
            } else {
                $retArray["message"] = $this->lang->line("invoice_postmethod");
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["message"] = $this->lang->line("invoice_permission");
            echo json_encode($retArray);
            exit();
        }
    }

    public function valid_data($data)
    {
        if ($data == 0) {
            $this->form_validation->set_message(
"valid_data",
"The %s field is required.");
            return false;
        }
        return true;
    }

    public function unique_classID()
    {
        if ($this->input->post("classesID") == 0) {
            $this->form_validation->set_message(
"unique_classID",
"The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_studentID()
    {
        $id = $this->input->post("editID");
        if ((int) $id && $id > 0) {
            if ($this->input->post("studentID") == 0) {
                $this->form_validation->set_message(
    "unique_studentID",
    "%s field is required.");
                return false;
            }
        }
        return true;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message(
"date_valid",
"%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr = explode("-", $date);
            $dd = $arr[0];
            $mm = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                $this->form_validation->set_message(
    "date_valid",
    "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function unique_status()
    {
        if ($this->input->post("statusID") === "5") {
            $this->form_validation->set_message(
"unique_status",
"The %s field is required.");
            return false;
        } else {
            $array = [0, 1, 2];

            if (!in_array($this->input->post("statusID"), $array)) {
                $this->form_validation->set_message(
    "unique_status",
    "The %s field is required.");
                return false;
            }
        }
        return true;
    }

    public function unique_paymentmethodID()
    {
        if ($this->input->post("paymentmethodID") === "0") {
            $this->form_validation->set_message(
"unique_paymentmethodID",
"The %s field is required.");
            return false;
        }
        return true;
    }

    public function unique_feetypeitems()
    {
        $feetypeitems = json_decode($this->input->post("feetypeitems"));
        $status = [];
        if (customCompute($feetypeitems)) {
            foreach ($feetypeitems as $feetypeitem) {
                if ($feetypeitem->amount == "") {
    $status[] = false;
                }
            }
        } else {
            $this->form_validation->set_message(
"unique_feetypeitems",
"The fee type item is required.");
            return false;
        }

        if (in_array(false, $status)) {
            $this->form_validation->set_message(
"unique_feetypeitems",
"The fee type amount is required.");
            return false;
        }
        return true;
    }

    public function getstudent()
    {
        $classesID = $this->input->post("classesID");
        $schoolyearID = $this->session->userdata("defaultschoolyearID");

        if ($this->input->post("edittype")) {
            echo '<option value="0">' .
                $this->lang->line("invoice_select_student") .
"</option>";
        } else {
            echo '<option value="0">' .
                $this->lang->line("invoice_all_student") .
"</option>";
        }

        $students = $this->studentrelation_m->get_order_by_student(["srschoolyearID" => $schoolyearID,
"srclassesID" => $classesID,
        ]);
        if (customCompute($students)) {
            foreach ($students as $student) {
                echo "<option value=\"$student->srstudentID\">" .
    $student->srname .
    " - " .
    $this->lang->line("invoice_roll") .
    " - " .
    $student->srroll .
    "</option>";
            }
        }
    }

    public function invoice_account_import()
    {
        $invoiceMainArray = [];
        $globalPaymentArray = [];
        $invoiceArray = [];
        $paymentArray = [];
        $paymentHistoryArray = [];
        $studentArray = [];
        $globalPaymentIDArray = [];
        // $feetype = pluck($this->feetypes_m->get_feetypes(), 'feetypes', 'feetypesID');
        // $feetypeitems = json_decode($this->input->post('feetypeitems'));
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $feess_type = $this->input->post("feess_type");

        $to_date = $this->input->post("to_date");
        $studentID = $this->input->post("studentID");
        $classesID = $this->input->post("classesID");
        $sectionID = $this->input->post("sectionID");
        $ref_nos = $this->invoice_m->get_invoice_ref();

        $classes = $this->classes_m->general_get_single_classes(["classesID" => $classesID, ]);
        $classes_numeric = $classes->classes_numeric;
        if (((int) $studentID || $studentID == 0) && (int) $classesID) {
            if ($studentID == 0) {
                $studentArrays = ["srclassesID" => $classesID,
    "srschoolyearID" => $schoolyearID,];
                if ((int) $sectionID) {
    $studentArrays["srsectionID"] = $sectionID;
                }
                $getstudents = $this->studentrelation_m->get_order_by_student(
    $studentArrays);
                // $getstudents = $this->studentrelation_m->get_order_by_student(array("srclassesID" => $classesID, 'srschoolyearID' => $schoolyearID));
            } else {
                $studentArrays = ["srclassesID" => $classesID,
    "srstudentID" => $studentID,
    "srschoolyearID" => $schoolyearID,];
                if ((int) $sectionID) {
    $studentArrays["srsectionID"] = $sectionID;
                }
                $getstudents = $this->studentrelation_m->get_order_by_student(
    $studentArrays);
            }

            if (customCompute($getstudents)) {
                $paymentStatus = 0;
                $paymentStatus = $this->input->post("statusID");
                $clearancetype = "unpaid";
                if ($paymentStatus == 0) {
    $clearancetype = "unpaid";
                } elseif ($paymentStatus == 1) {
    $clearancetype = "partial";
                } elseif ($paymentStatus == 2) {
    $clearancetype = "paid";
                }
                // echo '<pre>';
                // print_r($getstudents);
                // echo '</pre>';
                // echo '<br>';
                foreach ($getstudents as $key => $getstudent) {
                    if ($feess_type == "Installments") {
        $instcounter = $getstudent->no_installment;
                    } else {
        $instcounter = 1;
                    }
    $data_counter = 0;
                    for ($y = 1; $y <= $instcounter; $y++) {
                        if ($data_counter == 0) {
            $from_date = date("Y-m-d",    strtotime($this->input->post("date")));
            $due_date = date("Y-m-d",    strtotime($this->input->post("to_date")));
                        } else {
            $from_date = strtotime($this->input->post("date"));
            $due_date = strtotime($this->input->post("to_date"));
            $month = "+" . $data_counter . " month";
            $from_date = date("Y-m-d",    strtotime($month, $from_date));
            $due_date = date("Y-m-d",    strtotime($month, $due_date));
                        }

                        // $maininvoice_discount = $getstudent->total_fee - $getstudent->net_fee;

        $invoiceMainArray[] = ["maininvoiceschoolyearID" => $schoolyearID,
            "maininvoiceclassesID" => $this->input->post("classesID"),
            "maininvoicestudentID" => $getstudent->srstudentID,
            "maininvoicesectionID" => $sectionID,
            "maininvoicestatus" =>$this->input->post("statusID") !== "0"        ? ((float) $this->input->post("totalsubtotal") == (float) 0
                                        ? 2
                                        : ((float) $this->input->post("totalpaidamount") > (float) 0
                                            ? ((float) $this->input->post("totalsubtotal") ==
                                            (float) $this->input->post("totalpaidamount")
                                                ? 2
                                                : 1)
                                            : 0))
                                    : 0,
            "maininvoiceuserID" => $this->session->userdata("loginuserID"),
            "maininvoiceusertypeID" => $this->session->userdata("usertypeID"),
            "maininvoiceuname" => $this->session->userdata("name"),
            "maininvoicedate" => $from_date,
            "maininvoicedue_date" => $due_date,
            "maininvoicecreate_date" => date("Y-m-d"),
            "maininvoicetotal_fee" =>$getstudent->total_fee / $instcounter,
            "maininvoice_discount" =>$getstudent->discount / $instcounter,
            "maininvoicenet_fee" =>$getstudent->net_fee / $instcounter,
            "maininvoicestd_srid" => $getstudent->srsection,
            "maininvoiceday" => date("d"),
            "maininvoicemonth" => date("m"),
            "maininvoiceyear" => date("Y"),
            "maininvoicedeleted_at" => 1,
                        ];
        $data_counter++;
                    }

    $globalPaymentArray[] = ["classesID" => $getstudent->srclassesID,
        "sectionID" => $getstudent->srsectionID,
        "studentID" => $getstudent->srstudentID,
        "clearancetype" => $clearancetype,
        "invoicename" =>$getstudent->srregisterNO . "-" .  $getstudent->srname,
        "invoicedescription" => "",
        "paymentyear" => date("Y"),
        "schoolyearID" => $schoolyearID,
                    ];

    $studentArray[] = $getstudent->srstudentID;
                }

                // echo '<pre>';
                // print_r($invoiceMainArray);
                // echo '</pre>';
                // echo '<br>';
                // exit();
                if (customCompute($invoiceMainArray)) {
                    //  for ($y = 1; $y <= $instcounter; $y++){
    $count = customCompute($invoiceMainArray);

    $firstID = $this->maininvoice_m->insert_batch_maininvoice(
        $invoiceMainArray
);

    $lastID = $firstID + ($count - 1);
                    if ($feess_type == "Installments") {
        $feetypeIDs = 17;
                    } else {
        $feetypeIDs = 18;
                    }

                    if ($lastID >= $firstID) {
        $j = 0;
                        for ($i = $firstID; $i <= $lastID; $i++) {
                            if ($i < 10) {
                $ref_i = "0" . $i;
                            } else {
                $ref_i = $i;
                            }
            $num = (int) $ref_nos + $j + 1;
                            // $num = sprintf("%02d", $num);
                            // var_dump($num);
                            // exit();
            $current_y = date("Y");
            $current_m = date("m");
                            if ($invoiceMainArray[$j]["maininvoicestd_srid"] <
                                10) {
                $maininvoicestd_srid = "0" . $invoiceMainArray[$j]["maininvoicestd_srid"];
                            } else {
                $maininvoicestd_srid =
    $invoiceMainArray[$j]["maininvoicestd_srid"];
                            }

                            // $ref_no = '01'.$classes_numeric.$maininvoicestd_srid.$current_y.$current_m.$ref_i;
            $ref_no = $num;
                            // $ref_no = 'Afro'.$current_m.$current_y.$ref_i;
                            // if(customCompute($feetypeitems)) {
                            // foreach ($feetypeitems as $feetypeitem) {
            $invoiceArray[] = ["schoolyearID" =>    $invoiceMainArray[$j]["maininvoiceschoolyearID"],
                "classesID" =>    $invoiceMainArray[$j]["maininvoiceclassesID"],
                "studentID" =>    $invoiceMainArray[$j]["maininvoicestudentID"],
                "sectionID" =>    $invoiceMainArray[$j]["maininvoicesectionID"],    // 'feetypeID' => isset($feetypeitem->feetypeID) ? $feetypeitem->feetypeID : 0,    // 'feetype' => isset($feetype[$feetypeitem->feetypeID]) ? $feetype[$feetypeitem->feetypeID] : '',
                "feetypeID" => $feetypeIDs,
                "feetype" => $feess_type,
                "refrence_no" => $ref_no,    // 'maininvoicetotal_fee' => $invoiceMainArray[$j]['maininvoicetotal_fee'],    // 'amount' => isset($feetypeitem->amount) ? $feetypeitem->amount : 0,    // 'discount' => (isset($feetypeitem->discount) ? (($feetypeitem->discount == '') ? 0 : $feetypeitem->discount) : 0),
                "amount" => isset($invoiceMainArray[$j]["maininvoicetotal_fee"])
                                    ? $invoiceMainArray[$j]["maininvoicetotal_fee"]
                                    : 0,
                "discount" => isset($invoiceMainArray[$j]["maininvoice_discount"])
                                    ? $invoiceMainArray[$j]["maininvoice_discount"]
                                    : 0,
                "net_fee" => isset($invoiceMainArray[$j]["maininvoicenet_fee"])
                                    ? $invoiceMainArray[$j]["maininvoicenet_fee"]
                                    : 0,    // 'paidstatus' => ($this->input->post('statusID') !== '0') ? (((float)$feetypeitem->paidamount > (float)0) ? (((float) $feetypeitem->subtotal == (float) $feetypeitem->paidamount) ? 2 : 1 ) : 0) : 0,
                "paidstatus" => "",
                "userID" =>    $invoiceMainArray[$j]["maininvoiceuserID"],
                "usertypeID" =>    $invoiceMainArray[$j]["maininvoiceusertypeID"],
                "uname" =>    $invoiceMainArray[$j]["maininvoiceuname"],
                "date" =>    $invoiceMainArray[$j]["maininvoicedate"],
                "create_date" =>    $invoiceMainArray[$j]["maininvoicecreate_date"],
                "due_date" =>    $invoiceMainArray[$j]["maininvoicedue_date"],
                "day" =>    $invoiceMainArray[$j]["maininvoiceday"],
                "month" =>    $invoiceMainArray[$j]["maininvoicemonth"],
                "year" =>    $invoiceMainArray[$j]["maininvoiceyear"],
                "deleted_at" =>    $invoiceMainArray[$j]["maininvoicedeleted_at"],
                "maininvoiceID" => $i,];
            $net_feess = isset($invoiceMainArray[$j]["maininvoicenet_fee"])
                                ? $invoiceMainArray[$j]["maininvoicenet_fee"]
                                : 0;

                            // $this->invoice_m->invoice_accounts($invoiceMainArray[$j]['maininvoicestudentID'],$invoiceMainArray[$j]['maininvoiceclassesID'],$net_feess,$ref_no);
            $paymentHistoryArray[] = ["paymenttype" => ucfirst($this->input->post("paymentmethodID")),    // 'paymentamount' =>  $feetypeitem->paidamount
                "paymentamount" =>    $invoiceMainArray[$j]["maininvoicenet_fee"],];
                            // }
                            // }
            $j++;
                        }
                    }
                    //   }
                    //   exit();
                }
                //   if($feess_type == 'Installments'){
                //         $instcounter = 4;
                //   }else{
                //       $instcounter = 1;
                //   }
                // for ($y = 1; $y <= $instcounter; $y++) {

                $paymentInserStatus = $this->input->post("statusID");
                // $paymentInserStatus = 0;
                // if($this->input->post('statusID') ==! '0') {
                //     if($this->input->post('totalpaidamount') > 0) {
                //         if((float) $this->input->post('totalsubtotal') == (float) $this->input->post('totalpaidamount')) {
                //             $paymentInserStatus = 2;
                //         } else {
                //             $paymentInserStatus = 1;
                //         }
                //     } else {
                //         $paymentInserStatus = 0;
                //     }
                // }
                // echo '<pre>';
                // print_r($invoiceArray);
                // echo '</pre>';
                $invoicefirstID = $this->invoice_m->insert_batch_invoice(
    $invoiceArray);

                $invoiceSubtotalStatus = 1;
                if ((float) $this->input->post("totalsubtotal") == (float) 0) {
    $invoiceSubtotalStatus = 0;
                }

                if ($paymentInserStatus && $invoiceSubtotalStatus) {
                    if (customCompute($invoiceArray)) {
        $invoicecount = customCompute($invoiceArray);
        $invoicefirstID = $invoicefirstID;
        $invoicelastID = $invoicefirstID + ($invoicecount - 1);

        $globalcount = customCompute($globalPaymentArray);
        $globalfirstID = $this->globalpayment_m->insert_batch_globalpayment($globalPaymentArray);
        $globallastID = $globalfirstID + ($globalcount - 1);

                        if (customCompute($studentArray)) {
            $studentcount = customCompute($getstudents);
                            for ($n = 0; $n <= $studentcount - 1; $n++) {
                $globalPaymentIDArray[
    $studentArray[$n]] = $globalfirstID;
                $globalfirstID++;
                            }
                        }

                        if ($invoicelastID >= $invoicefirstID) {
            $k = 0;
                            for ($i = $invoicefirstID;
                $i <= $invoicelastID;
                $i++) {
                $paymentArray[] = ["schoolyearID" =>    $invoiceArray[$k]["schoolyearID"],
                    "invoiceID" => $i,
                    "studentID" =>    $invoiceArray[$k]["studentID"],
                    "paymentamount" => isset($paymentHistoryArray[$k][
                            "paymentamount"            ])
                                        ? ($paymentHistoryArray[$k][
                            "paymentamount"            ] == ""                ? null
                                            : $paymentHistoryArray[$k][
                                "paymentamount"])
                                        : 0,
                    "paymenttype" => ucfirst($this->input->post("paymentmethodID")),
                    "paymentdate" => date("Y-m-d"),
                    "paymentday" => date("d"),
                    "paymentmonth" => date("m"),
                    "paymentyear" => date("Y"),
                    "userID" => $invoiceArray[$k]["userID"],
                    "usertypeID" =>    $invoiceArray[$k]["usertypeID"],
                    "uname" => $invoiceArray[$k]["uname"],
                    "transactionID" =>    "CASHANDCHEQUE" . random19(),
                    "globalpaymentID" => isset($globalPaymentIDArray[
            $invoiceArray[$k]["studentID"]
                                        ])
                                        ? $globalPaymentIDArray[
            $invoiceArray[$k]["studentID"]
                                        ]
                                        : 0,];
                $k++;
                            }
                        }

                        if (customCompute($paymentArray)) {
            $this->payment_m->insert_batch_payment($paymentArray);
                        }
                    }
                }

                // }

                $this->session->set_flashdata(
    "success",
    $this->lang->line("menu_success"));
                $retArray["status"] = true;
                $retArray["message"] = "Success";
                echo json_encode($retArray);
                exit();
            } else {
                $retArray["error"] = ["student" => "Student not found."];
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["error"] = ["classstudent" => "Class and Student not found.",
            ];
            echo json_encode($retArray);
            exit();
        }
    }

    public function saveinvoice()
    {
        error_reporting(E_ALL);
        $maininvoiceID = 0;
        $retArray["status"] = false;
        if ( $this->data["siteinfos"]->school_year ==  $this->session->userdata("defaultschoolyearID") || $this->session->userdata("usertypeID") == 1 || $this->session->userdata("defaultschoolyearID") == 5 ) {
            if ( permissionChecker("invoice_add") || permissionChecker("invoice_edit")) {
                if ($_POST) {
                $rules = $this->rules($this->input->post("statusID"));

                    if (  $this->input->post("maininvoice_type_v") == "hostel_fee" or $this->input->post("maininvoice_type_v") =="Transport_fee") {
                        unset($rules[0]);
                        unset($rules[6]);
                    }
                    if (  $this->input->post("maininvoice_type_v") == "other_charges" ) {
                        unset($rules[0]);
                    }
                    if ( $this->input->post("maininvoice_type_v") == "invoice" ) {
                        unset($rules[6]);
                    }
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray["error"] = $this->form_validation->error_array();
                    $retArray["status"] = false;
                    echo json_encode($retArray);
                    exit();
                    } else {
                        $invoiceMainArray   = [];
                        $invoiceArray       = [];
                        $paymentArray       = [];
                        $studentArray       = [];
                        $reg_no             = [];
                        $ref_array          = [];

                        $schoolyearID       = $this->session->userdata("defaultschoolyearID");
                        $feess_type         = $this->input->post("feess_type");
                        $get_feetype        = $this->feetypes_m->get_feetypes($feess_type);
                        $feetypeIDs         = $get_feetype->feetypesID;
                        $feetype            = $get_feetype->feetypes;
                        $to_date            = $this->input->post("to_date");
                        $studentID          = $this->input->post("studentID");
                        $classesID          = $this->input->post("classesID");
                        $sectionID          = $this->input->post("sectionID");
                        $invoice_type_v     = $this->input->post("maininvoice_type_v");
                        // $ref_no = $this->invoice_m->get_invoice_ref_by_date(
                        //     $this->input->post("date")
                        // );
                        // $ref_array[date('ym',strtotime($this->input->post("date")))] = $ref_no;

                        


                        if (((int) $studentID || $studentID == 0) &&
                            ((int) $classesID || $classesID == 0)) {
                            if ($studentID != 0) {
                                $studentArrays = [
                                                    "srstudentID"       => $studentID,
                                                    "srclassesID"       => $classesID,
                                                    "srschoolyearID"    => $schoolyearID,
                                                    "active"            => [0, 1],
                                                    ];
                        if ((int) $sectionID) { $studentArrays["srsectionID"] = $sectionID; }
                                $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                            } else {
                                $studentArrays = [
                                                    "srschoolyearID" => $schoolyearID,
                                                    "active" => [0, 1],
                                                  ];

                                if ((int) $classesID) { $studentArrays["srclassesID"] = $classesID; }
                                if ((int) $sectionID) { $studentArrays["srsectionID"] = $sectionID; }
                                if ($invoice_type_v == "hostel_fee") {  $studentArrays["hostel"] = 1;  }
                                if ($invoice_type_v == "Transport_fee") {  $studentArrays["transport"] = 1; }
                                    $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                                  }

                                $automationHMember = pluck($this->hmember_m->get_hmember(), "obj", "studentID");

                                $automationtmember = pluck($this->tmember_m->get_tmember(), "obj", "studentID");

                            if (customCompute($getstudents)) {
                                    $paymentStatus      = 0;
                                    $paymentStatus      = $this->input->post("statusID");
                                    $payment_type       = $this->input->post("payment_type");
                                    $clearancetype      = "unpaid";

                                    $mainlastID         = $this->maininvoice_m->get_maininvoice_last_id();



                                foreach ($getstudents as $key => $getstudent) {
                                    if ($payment_type == 1 and $feetypeIDs==17) {
                                        $instcounter = $getstudent->no_installment;
                                    } else {
                                        $instcounter = 1;
                                    }
                                    $data_counter = 0;
                                    for ($y = 1; $y <= $instcounter; $y++) {
                                        if ($data_counter == 0) {
                                            $from_date  = date("Y-m-d", strtotime($this->input->post("date")));
                                            $due_date   = date("Y-m-d", strtotime($this->input->post("to_date")));

                                        if (!isset($ref_array[date('ym',strtotime($from_date))])) {
                                           
                                            $ref_no    = $this->invoice_m->get_invoice_ref_by_date($from_date);
                                            $ref_array[date('ym',strtotime($from_date))] = $ref_no;
                                                                      
                                                    }
                                        } else {
                                            $from_date  = strtotime($this->input->post("date"));
                                            $due_date   = strtotime($this->input->post("to_date"));
                                            $month      = "+" . $data_counter . " month";
                                            $from_date  = date("Y-m-d",strtotime($month, $from_date));
                                            $due_date   = date("Y-m-d",strtotime($month, $due_date));
                                                if (!isset($ref_array[date('ym',strtotime($from_date))])) {
                                                    $ref_no = $this->invoice_m->get_invoice_ref_by_date($from_date);
                                                    $ref_array[date('ym',strtotime($from_date))] = $ref_no;
                                                                              
                                                  }
                                        }
                                           

                            if ($invoice_type_v == "invoice") {

                                
                                $student_discount   =   ceil($getstudent->discount /$instcounter);
                                $feebreakup = $this->courseoption_m->get_order_by_salaryoption_join_feetype(array('salary_templateID' => $getstudent->fee_structureID));
                                 $amount_breakup    =   [];
                                                 foreach ($feebreakup as $fb) {
                                                        $amount_breakup[$fb->feetypesID]    =  ceil($fb->label_amount /$instcounter) ;
                                                 }
                                   $discounttypeid =   $this->data['siteinfos']->discount_feetypesID;
                                   $breakup_info_ar    =   [];
                                    $fee_total          =   0;
                                    $net_discount       =   0;
                                    $net_total          =   0;
                                    $discount_left      =   $student_discount;
                                    foreach ($feebreakup as $ff) {
                                        $label_amount       =   ceil($ff->label_amount /$instcounter);
                                        if($student_discount > $amount_breakup[$discounttypeid]){
                                            $fee_discount       =   $student_discount;
                                        }else{       
                                            if ($ff->feetypesID==$discounttypeid) {
                                                $fee_discount = $student_discount;
                                                }else{

                                                    $fee_discount = 0;
                                                }
                                        }

                   
                                        if ($discount_left < $label_amount && $fee_discount==$discount_left) {
                                        $fee_discount   =   $fee_discount;
                                        $discount_left  =   0;
                                             
                                              
                                        }else if ($discount_left > $label_amount ) {



                                        $fee_discount      =   ($label_amount);
                                            

                                        $discount_left      =   ($discount_left-$label_amount);


                                        }else if ($discount_left <= $label_amount && $discount_left >0) {
                                        $fee_discount   =   $discount_left;
                                        $discount_left  =   0;


                                        }else{
                                        $fee_discount      =   0;
                                        $discount_left      =   0;
                                        }

                                      
                                        $fee_discount_a     = $fee_discount;
                                        $total_fee          = $label_amount;
                                        $fee_discount       = ceil($fee_discount);
                                        $net_fee_d          = ceil($total_fee-$fee_discount);

                 

                                            $breakup_info_ar[]    = array(
                                                        'feetypeID'         => $ff->feetypesID, 
                                                        'feetypes'          => $ff->feetypes, 
                                                        'fee_discount'      => $fee_discount, 
                                                        'fee_amount'        => $total_fee, 
                                                        'fee_discount_a'    => $fee_discount_a, 
                                                        'net_amount'        => $net_fee_d, 
                                                        'discount_left'     => $discount_left,  
                                                        'feetypedetail'     => $ff, 
                                                        'S_DIS'             => $student_discount, 
                                            );
                                            $fee_total      +=   $total_fee;
                                            $net_discount   +=   $fee_discount;
                                            $net_total      +=   $net_fee_d;
                                } //fee breacku loop
                                 

                            $fee_breakup    =   serialize($breakup_info_ar);
                            $total_fee      =   ceil($fee_total);
                            $discount       =   ceil($net_discount);
                            $net_fee        =   ceil($net_total);
                                
                            }
                            if ($invoice_type_v == "hostel_fee") {
                                if (isset($automationHMember[$getstudent->srstudentID])) {
                                    $total_hostel_fee       =    $automationHMember[$getstudent->srstudentID]->hbalance;
                                    $hostel_fee_discount    =    $automationHMember[$getstudent->srstudentID]->hostel_discount;
                                } else {
                                    $total_hostel_fee       = 0;
                                    $hostel_fee_discount    = 0;
                                }

                                $total_fee  = ceil($total_hostel_fee / $instcounter);
                                $discount   = ceil($hostel_fee_discount /$instcounter);
                                $net_fee    = ceil($total_hostel_fee - $hostel_fee_discount / $instcounter);
                                 $breakup_info_ar    =   [];
                                $breakup_info_ar[]    = array(
                                                                'feetypeID'         => $get_feetype->feetypesID, 
                                                                'feetypes'          => $get_feetype->feetypes, 
                                                                'fee_discount'      => $discount, 
                                                                'fee_amount'        => $total_fee, 
                                                                'fee_discount_a'    => $discount, 
                                                                'net_amount'        => $net_fee, 
                                                                'discount_left'     => 0,  
                                                                'feetypedetail'     => $get_feetype, 
                                                                'S_DIS'             => $discount, 
                                                    );
                             $fee_breakup    =   serialize($breakup_info_ar);
                            }
                            if ($invoice_type_v == "Transport_fee") {
                                if (isset($automationtmember[$getstudent->srstudentID])) {
                                    $total_hostel_fee       =    $automationtmember[$getstudent->srstudentID]->tbalance;
                                    $hostel_fee_discount    =    $automationtmember[$getstudent->srstudentID]->transport_discount;
                                } else {
                                    $total_hostel_fee       = 0;
                                    $hostel_fee_discount    = 0;
                                }

                                $total_fee  = ceil($total_hostel_fee / $instcounter);
                                $discount   = ceil($hostel_fee_discount /$instcounter);
                                $net_fee    = ceil($total_hostel_fee - $hostel_fee_discount / $instcounter);

                                  $breakup_info_ar     =   [];
                                 $breakup_info_ar[]    = array(
                                                                        'feetypeID'         => $get_feetype->feetypesID, 
                                                                        'feetypes'          => $get_feetype->feetypes, 
                                                                        'fee_discount'      => $discount, 
                                                                        'fee_amount'        => $total_fee, 
                                                                        'fee_discount_a'    => $discount, 
                                                                        'net_amount'        => $net_fee, 
                                                                        'discount_left'     => 0,  
                                                                        'feetypedetail'     => $get_feetype, 
                                                                        'S_DIS'             => $discount, 
                                                            );
                                        $fee_breakup    =   serialize($breakup_info_ar);
                            }

                            if ($invoice_type_v == "other_charges") {
                                $total_fee  = $this->input->post("amount");
                                $discount   = 0;
                                $net_fee    = $this->input->post("amount");
                                 $breakup_info_ar      =   [];
                                 $breakup_info_ar[]    = array(
                                                                'feetypeID'         => $get_feetype->feetypesID, 
                                                                'feetypes'          => $get_feetype->feetypes, 
                                                                'fee_discount'      => $discount, 
                                                                'fee_amount'        => $total_fee, 
                                                                'fee_discount_a'    => $discount, 
                                                                'net_amount'        => $net_fee, 
                                                                'discount_left'     => 0,  
                                                                'feetypedetail'     => $get_feetype, 
                                                                'S_DIS'             => $discount, 
                                                    );
                                    $fee_breakup    =   serialize($breakup_info_ar);
                            }
                            if ($net_fee) {
                                $mainlastID++;
                                $singleinvoiceMainArray = [
                                                            "maininvoiceID"         => $mainlastID,
                                                            "maininvoiceschoolyearID"   => $schoolyearID,
                                                            "maininvoiceclassesID"  => $getstudent->srclassesID,
                                                            "maininvoicestudentID"  => $getstudent->srstudentID,
                                                            "maininvoicesectionID"  => $getstudent->srsectionID,
                                                            "maininvoicestatus"     => 0,
                                                            "maininvoiceuserID"     => $this->session->userdata("loginuserID"),
                                                            "maininvoiceusertypeID" => $this->session->userdata("usertypeID"),
                                                            "maininvoiceuname"      => $this->session->userdata("name"),
                                                            "maininvoicedate"       => $from_date,
                                                            "maininvoicedue_date"   => $due_date,
                                                            "fee_breakup"           => $fee_breakup,
                                                            "maininvoicecreate_date"=> date("Y-m-d"),
                                                            "maininvoicestd_srid"   =>$getstudent->srsection,
                                                            "maininvoiceday"        => date("d"),
                                                            "maininvoicemonth"      => date("m"),
                                                            "maininvoiceyear"       => date("Y"),
                                                            "maininvoice_type_v"    => $invoice_type_v,
                                                            "maininvoicedeleted_at" => 1,
                                                ];

                                $singleinvoiceMainArray["maininvoicetotal_fee"] = ceil($total_fee);
                                $singleinvoiceMainArray["maininvoice_discount"] = ceil($discount);
                                $singleinvoiceMainArray["maininvoicenet_fee"]   = ceil($net_fee); 

                                $invoiceMainArray[] = $singleinvoiceMainArray;
                                $data_counter++;
                                        } // net fee
                                    } // for increment

                                $studentArray[] = $getstudent->srstudentID;
                                $reg_no[$getstudent->srstudentID] = $getstudent->accounts_reg;
                                }

                                if (customCompute($invoiceMainArray)) {
                                        $count = customCompute($invoiceMainArray);

                                        $firstID = $this->maininvoice_m->insert_batch_maininvoice($invoiceMainArray);

                                        $lastID = $firstID + ($count - 1);
                                                                         

                                        if ($lastID >= $firstID) {
                                            $j = 0;
                                            for ($i = $firstID;$i <= $lastID; $i++) {
                                                $current_y = date("Y");
                                                $current_m = date("m");
                                            if ($invoiceMainArray[$j]["maininvoicestd_srid"] < 10) {
                                                    $maininvoicestd_srid ="0" .
                                                    $invoiceMainArray[$j]["maininvoicestd_srid"];
                                            } else {
                                                    $maininvoicestd_srid =    $invoiceMainArray[$j]["maininvoicestd_srid"];
                                            }

                                                if (!isset($ref_array[date('ym',strtotime($ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))]))])) {
                                                # code...
                                           
                                             $ref_no = $this->invoice_m->get_invoice_ref_by_date($ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))]);
                                           
                                            $ref_array[date('ym',strtotime( $ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))]))] = $ref_no;
                                                                          
                                                        }

                                              if (!isset($ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))])) {
                                                    $ref_no = $this->invoice_m->get_invoice_ref_by_date($invoiceMainArray[$j]["maininvoicedate"]);
                                                    $ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))] = $ref_no;
                                                                              
                                                  }
                                             

                                        $invoiceArray[] = [
                                            "schoolyearID"  =>  $invoiceMainArray[$j]["maininvoiceschoolyearID"],
                                            "classesID"     =>  $invoiceMainArray[$j]["maininvoiceclassesID"],
                                            "type_v"        =>  $invoice_type_v,
                                            "studentID"     =>  $invoiceMainArray[$j]["maininvoicestudentID"],
                                            "sectionID"     =>  $invoiceMainArray[$j]["maininvoicesectionID"],
                                            "fee_breakup"   =>  $invoiceMainArray[$j]["fee_breakup"],
                                            "feetypeID"     =>  $feetypeIDs,
                                            "feetype"       =>  $feetype,
                                            "refrence_no"   =>  $ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))],
                                            "amount"        => isset($invoiceMainArray[$j]["maininvoicetotal_fee"])? $invoiceMainArray[$j]["maininvoicetotal_fee"]: 0,
                                            "discount"      =>  isset($invoiceMainArray[$j]["maininvoice_discount"])?$invoiceMainArray[$j]["maininvoice_discount"]: 0,
                                            "net_fee"       =>  isset($invoiceMainArray[$j]["maininvoicenet_fee"])? $invoiceMainArray[$j]["maininvoicenet_fee"]: 0,
                                            "paidstatus"    =>  NULL,
                                            "userID"        =>  $invoiceMainArray[$j]["maininvoiceuserID"],
                                            "usertypeID"    =>  $invoiceMainArray[$j]["maininvoiceusertypeID"],
                                            "uname"         =>  $invoiceMainArray[$j]["maininvoiceuname"],
                                            "date"          =>  $invoiceMainArray[$j]["maininvoicedate"],
                                            "create_date"   =>  $invoiceMainArray[$j]["maininvoicecreate_date"],
                                            "due_date"      =>  $invoiceMainArray[$j]["maininvoicedue_date"],
                                            "day"           =>  $invoiceMainArray[$j]["maininvoiceday"],
                                            "month"         =>  $invoiceMainArray[$j]["maininvoicemonth"],
                                            "year"          =>  $invoiceMainArray[$j]["maininvoiceyear"],
                                            "deleted_at"    =>  $invoiceMainArray[$j]["maininvoicedeleted_at"],
                                            "maininvoiceID" =>  $invoiceMainArray[$j]["maininvoiceID"],
                                                        ];

                                            $net_feess = isset($invoiceMainArray[$j][ "maininvoicenet_fee" ]) ? $invoiceMainArray[$j]["maininvoicenet_fee"  ] : 0;
                                            $ref_array[date('ym',strtotime($invoiceMainArray[$j]["maininvoicedate"]))]++;

                                            if ($invoice_type_v=='invoice') {

                                            $all_fee_breakups   =    unserialize($invoiceMainArray[$j][ "fee_breakup" ]);
                                                                
                                            foreach($all_fee_breakups  as $new_fb){
                                                    $getfeetype        =   $new_fb['feetypedetail'];
                                                                    
                                                    $ledger_array[] = [
                                                    "maininvoiceID" => $invoiceMainArray[$j][ "maininvoiceID" ],
                                                    "studentID"     => $invoiceMainArray[$j][ "maininvoicestudentID" ],
                                                    "classesID"     => $invoiceMainArray[$j][ "maininvoiceclassesID" ],
                                                    "net_fee"       => $new_fb['net_amount'],
                                                    "refrence_no"   => $ref_array[date('ym',strtotime($invoiceMainArray[$j][ "maininvoicedate" ]))],    
                                                    'description'   => $getfeetype->feetypes.' fee for semster '.$invoiceMainArray[$j]['maininvoicestd_srid'],
                                                    "accounts_reg"  => $reg_no[$invoiceMainArray[$j][ "maininvoicestudentID" ] ], 
                                                    "account_credit"=> $getfeetype->credit_id, 
                                                    "account_debit" => $getfeetype->debit_id, 
                                                    "feetypeID"     => $getfeetype->feetypesID,
                                                    "date"          => $invoiceMainArray[$j]["maininvoicedate"], 
                                                    "discount"      => $invoiceMainArray[$j]["maininvoice_discount"], ];

                                                                }
                                                            
                                                        }else{ 

                                                $ledger_array[] = [
                                                    "maininvoiceID" => $invoiceMainArray[$j][ "maininvoiceID" ],
                                                    "studentID"     => $invoiceMainArray[$j][ "maininvoicestudentID" ],
                                                    "classesID"     => $invoiceMainArray[$j][ "maininvoiceclassesID" ],
                                                    "net_fee"       => isset( $invoiceMainArray[$j][ "maininvoicenet_fee" ] ) ? $invoiceMainArray[$j][ "maininvoicenet_fee" ] : 0,
                                                    "refrence_no"   => $ref_array[date('ym',strtotime($invoiceMainArray[$j][ "maininvoicedate" ]))],    
                                                    'description'   => $feetype.' fee for semster '.$invoiceMainArray[$j]['maininvoicestd_srid'],
                                                    "accounts_reg"  => $reg_no[ $invoiceMainArray[$j][ "maininvoicestudentID" ] ], 
                                                    "account_credit"=> $get_feetype->credit_id, 
                                                    "account_debit" => $get_feetype->debit_id, 
                                                    "feetypeID"     => $get_feetype->feetypesID,  
                                                    "date"          => $invoiceMainArray[$j]["maininvoicedate"],
                                                    "discount"      => $invoiceMainArray[$j]["maininvoice_discount"], ];
                                                                }
                                                                                
                                                            //$ref_no = (int) $ref_no + 1;
                                            
                                                                                 
                                            $j++;
                                                                            }
                                                                        }
                            }

                $paymentInserStatus = $this->input->post("statusID");

                 
                $invoicefirstID = $this->invoice_m->insert_batch_invoice($invoiceArray);
                
                $student_ledgers_ar     =   [];
                $journal_items_ar       =   [];
                foreach ($ledger_array as $in_ar) {
                $journal_entries_id   =     $this->invoice_m->journal_entries_id($in_ar);

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
                                            'account'       =>  $in_ar['account_debit'], 
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
                if ($in_ar['discount']>0) {
                    

                $journal_items_ar[]  = array(
                                            'journal'       =>  $journal_entries_id, 
                                            'account'       =>  $this->data["siteinfos"]->discount_credit, 
                                            'description'   =>  $in_ar['description'], 
                                            'debit'         =>  0, 
                                            'credit'        =>  $in_ar['discount'], 
                                            'entry_type'    =>  'CR',  
                                            'referenceID'   =>  $in_ar['maininvoiceID'],
                                            'reference_type'=>  'invoice',
                                            'feetypeID'     =>  $in_ar['feetypeID'],
                                            'created_at'    =>  date('Y-m-d h:i:s'), 
                                            'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                                                'journal'       =>  $journal_entries_id, 
                                                'account'       =>  $this->data["siteinfos"]->discount_debit, 
                                                'description'   =>  $in_ar['description'], 
                                                'debit'         =>  $in_ar['discount'], 
                                                'entry_type'    =>  'DR', 
                                                'credit'        =>  0,  
                                                'referenceID'   =>  $in_ar['maininvoiceID'],
                                                'reference_type'=>  'invoice',
                                                'feetypeID'     =>  $in_ar['feetypeID'],
                                                'created_at'    =>  date('Y-m-d h:i:s'), 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                                'journal_entries_id'       =>  $journal_entries_id, 
                                'maininvoice_id'           =>  $in_ar['maininvoiceID'],   
                                'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                                'type'                     =>  'CR', 
                                'account'                  =>  $this->data["siteinfos"]->discount_credit, 
                                'vr_no'                    =>  $in_ar['refrence_no'], 
                                'narration'                =>  $in_ar['description'], 
                                'debit'                    =>  0, 
                                'reference_type'           =>  'invoice',
                                'feetypeID'                =>  $in_ar['feetypeID'],
                                'credit'                   =>  $in_ar['discount'], 
                                'balance'                  =>  $in_ar['discount'],  
                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                                'journal_entries_id'       =>  $journal_entries_id, 
                                'maininvoice_id'           =>  $in_ar['maininvoiceID'],  
                                'date'                     =>  date('Y-m-d',strtotime($in_ar['date'])), 
                                'type'                     =>  'DR', 
                                'account'                  =>  $this->data["siteinfos"]->discount_debit, 
                                'vr_no'                    =>  $in_ar['refrence_no'], 
                                'narration'                =>  $in_ar['description'], 
                                'debit'                    =>  $in_ar['discount'], 
                                'credit'                   =>  0, 
                                'reference_type'           =>  'invoice',
                                'feetypeID'                =>  $in_ar['feetypeID'],
                                'balance'                  =>  $in_ar['discount'],  
                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                'updated_at'               =>  date('Y-m-d h:i:s'), );


                }

                                }
                                $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                                $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );

                                  

                                $this->session->set_flashdata("success",$this->lang->line("menu_success"));
                                $retArray["status"] = true;
                                $retArray["message"] = "Success";

                                 
                                echo json_encode($retArray);
                                exit();
                            } else {
                                $retArray["error"] = ["student" => "Student not found.",];
                                echo json_encode($retArray);
                                exit();
                            }
                        } else {
                            $retArray["error"] = ["classstudent" =>"Class and Student not found.",];
                            echo json_encode($retArray);
                            exit();
                        }
                    }
                } else {
                    $retArray["error"] = ["posttype" => "Post type is required.",];
                    echo json_encode($retArray);
                    exit();
                }
            } else {
                $retArray["error"] = ["permission" => "Invoice permission is required.",];
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["error"] = ["permission" => "Permission Denied."];
            echo json_encode($retArray);
            exit();
        }
    }

    public function saveinvoice_hostel()
    {
        
        $maininvoiceID = 0;
        $retArray["status"] = false;
        if (
            $this->data["siteinfos"]->school_year ==
                $this->session->userdata("defaultschoolyearID") ||
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("defaultschoolyearID") == 5
) {
            if (
                permissionChecker("invoice_add") ||
                permissionChecker("invoice_edit")) {
                if ($_POST) {
    $rules = $this->rules($this->input->post("statusID"));
    $this->form_validation->set_rules($rules);
                    // if ($this->form_validation->run() == FALSE) {
                    //     $retArray['error'] = $this->form_validation->error_array();
                    //     $retArray['status'] = FALSE;
                    //     echo json_encode($retArray);
                    //     exit;
                    // } else {
    $invoiceMainArray = [];
    $globalPaymentArray = [];
    $invoiceArray = [];
    $paymentArray = [];
    $paymentHistoryArray = [];
    $studentArray = [];
    $globalPaymentIDArray = [];
    $feetype = pluck(
        $this->feetypes_m->get_feetypes(),
        "feetypes",
        "feetypesID"
);
    $feetypeitems = json_decode(
        $this->input->post("feetypeitems")
);
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);
    $feess_type = $this->input->post("feess_type");

    $to_date = $this->input->post("to_date");
    $studentID = $this->input->post("studentID");
    $classesID = $this->input->post("classesID");
    $sectionID = $this->input->post("sectionID");
                    // $ref_nos = $this->invoice_m->get_invoice_ref();
    $ref_nos = $this->invoice_m->get_invoice_ref_by_date(
        $this->input->post("date")
);
    $ref_no = $ref_nos;
                    // $ref_no = '01007077';
                    // $num = (int)$ref_nos + 1;
                    // $num = sprintf("%02d", $num);
    $classes = $this->classes_m->general_get_single_classes(["classesID" => $classesID,
                    ]);
    $classes_numeric = $classes->classes_numeric;
                    if ($studentID > 0) {
        $studentArrays = ["srstudentID" => $studentID,
            "srschoolyearID" => $schoolyearID,
            "hostel" => 1,
                        ];
                        if ((int) $sectionID) {
            $studentArrays["srsectionID"] = $sectionID;
                        }
        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);

                        // $getstudents = $this->studentrelation_m->get_order_by_student(array("srclassesID" => $classesID, 'srschoolyearID' => $schoolyearID));
                    } elseif ((int) $classesID && $classesID > 0) {
        $studentArrays = ["srclassesID" => $classesID,
            "srschoolyearID" => $schoolyearID,
            "hostel" => 1,
                        ];
                        if ((int) $sectionID) {
            $studentArrays["srsectionID"] = $sectionID;
                        }
        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                    } else {
        $studentArrays = ["srschoolyearID" => $schoolyearID,
            "hostel" => 1,
                        ];

        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                    }

    $automationHMember = pluck(
        $this->hmember_m->get_hmember(),
        "obj",
        "studentID"
);

                    if (customCompute($getstudents)) {
        $paymentStatus = 0;
                        // if($this->input->post('statusID') !== '0') {
                        //     if((float) $this->input->post('totalsubtotal') == (float)0) {
                        //         $paymentStatus = 2;
                        //     } else {
                        //         if((float) $this->input->post('totalpaidamount') > (float)0) {
                        //             if((float) $this->input->post('totalsubtotal') == (float) $this->input->post('totalpaidamount')) {
                        //                 $paymentStatus = 2;
                        //             } else {
                        //                 $paymentStatus = 1;
                        //             }
                        //         }
                        //     }
                        // }

        $paymentStatus = $this->input->post("statusID");
        $clearancetype = "unpaid";
                        if ($paymentStatus == 0) {
            $clearancetype = "unpaid";
                        } elseif ($paymentStatus == 1) {
            $clearancetype = "partial";
                        } elseif ($paymentStatus == 2) {
            $clearancetype = "paid";
                        }
                        // echo '<pre>';
                        // print_r($getstudents);
                        // echo '</pre>';
                        // echo '<br>';
                        foreach ($getstudents as $key => $getstudent) {
            $total_hostel_fee =
                $automationHMember[$getstudent->srstudentID]->hbalance;
            $hostel_fee_discount =
                $automationHMember[$getstudent->srstudentID]->hostel_discount;

                            if ($feess_type == "Installments") {
                $instcounter = $getstudent->no_installment;
                            } else {
                $instcounter = 1;
                            }
            $data_counter = 0;
                            for ($y = 1; $y <= $instcounter; $y++) {
                                if ($data_counter == 0) {
    $from_date = date("Y-m-d",    strtotime($this->input->post("date")));
    $due_date = date("Y-m-d",    strtotime($this->input->post("to_date")));
                                } else {
    $from_date = strtotime($this->input->post("date"));
    $due_date = strtotime($this->input->post("to_date"));
    $month = "+" . $data_counter . " month";
    $from_date = date("Y-m-d",    strtotime($month, $from_date));
    $due_date = date("Y-m-d",    strtotime($month, $due_date));
                                }

                                // $maininvoice_discount = $total_hostel_fee - $total_hostel_fee;
                                if ($total_hostel_fee - $hostel_fee_discount >
                                    0) {
                                    # code...

    $invoiceMainArray[] = ["maininvoiceschoolyearID" => $schoolyearID,
        "maininvoiceclassesID" =>$getstudent->srclassesID,
        "maininvoicestudentID" =>$getstudent->srstudentID,
        "maininvoicesectionID" =>$getstudent->srsectionID,
        "maininvoicestatus" =>$this->input->post("statusID") !==
            "0"    ? ((float) $this->input->post("totalsubtotal") == (float) 0
                                    ? 2
                                    : ((float) $this->input->post("totalpaidamount") > (float) 0
                                        ? ((float) $this->input->post("totalsubtotal") ==(float) $this->input->post("totalpaidamount")
                                            ? 2
                                            : 1)
                                        : 0))
                                : 0,
        "maininvoiceuserID" => $this->session->userdata("loginuserID"),
        "maininvoiceusertypeID" => $this->session->userdata("usertypeID"),
        "maininvoiceuname" => $this->session->userdata("name"),
        "maininvoicedate" => $from_date,
        "maininvoicedue_date" => $due_date,
        "maininvoicecreate_date" => date("Y-m-d"),
        "maininvoice_type_v" => "hostel_fee",
        "maininvoicetotal_fee" => ceil($total_hostel_fee / $instcounter),
        "maininvoice_discount" => ceil($hostel_fee_discount / $instcounter),
        "maininvoicenet_fee" => ceil(($total_hostel_fee -
                $hostel_fee_discount) /
                $instcounter),
        "maininvoicestd_srid" =>$getstudent->srsection,
        "maininvoiceday" => date("d"),
        "maininvoicemonth" => date("m"),
        "maininvoiceyear" => date("Y"),
        "maininvoicedeleted_at" => 1,
                    ];
    $data_counter++;
                                }
                            }

            $globalPaymentArray[] = ["classesID" => $getstudent->srclassesID,
                "sectionID" => $getstudent->srsectionID,
                "studentID" => $getstudent->srstudentID,
                "clearancetype" => $clearancetype,
                "invoicename" =>    $getstudent->srregisterNO .
                    "-" .
    $getstudent->srname,
                "invoicedescription" => "",
                "paymentyear" => date("Y"),
                "schoolyearID" => $schoolyearID,];

            $studentArray[] = $getstudent->srstudentID;
                        }

                        // echo '<pre>';
                        // print_r($invoiceMainArray);
                        // echo '</pre>';
                        // echo '<br>';
                        // exit();
                        if (customCompute($invoiceMainArray)) {
                            //  for ($y = 1; $y <= $instcounter; $y++){
            $count = customCompute($invoiceMainArray);

            $firstID = $this->maininvoice_m->insert_batch_maininvoice($invoiceMainArray);

            $lastID = $firstID + ($count - 1);
                            if ($feess_type == "Installments") {
                $feetypeIDs = 4;
                            } else {
                $feetypeIDs = 4;
                            }

                            if ($lastID >= $firstID) {
                $j = 0;
                                for ($i = $firstID; $i <= $lastID; $i++) {
                                    if ($i < 10) {
        $ref_i = "0" . $i;
                                    } else {
        $ref_i = $i;
                                    }
                                    // $num = (int)$ref_nos + $j + 1;
                                    // $num = sprintf("%02d", $num);
                                    // var_dump($num);
                                    // exit();
    $current_y = date("Y");
    $current_m = date("m");
                                    if ($invoiceMainArray[$j][
                            "maininvoicestd_srid"            ] < 10) {
        $maininvoicestd_srid =
                            "0" .
            $invoiceMainArray[$j][
                                "maininvoicestd_srid"];
                                    } else {
        $maininvoicestd_srid =
            $invoiceMainArray[$j][
                                "maininvoicestd_srid"];
                                    }

                                    // $ref_no = '01'.$classes_numeric.$maininvoicestd_srid.$current_y.$current_m.$ref_i;
                                    // $ref_no = $num;
                                    // $ref_no = 'Afro'.$current_m.$current_y.$ref_i;
                                    // if(customCompute($feetypeitems)) {
                                    // foreach ($feetypeitems as $feetypeitem) {
    $invoiceArray[] = ["schoolyearID" =>        $invoiceMainArray[$j][
                                "maininvoiceschoolyearID"],
                        "classesID" =>        $invoiceMainArray[$j][
                                "maininvoiceclassesID"],
                        "studentID" =>        $invoiceMainArray[$j][
                                "maininvoicestudentID"],
                        "sectionID" =>        $invoiceMainArray[$j][
                                "maininvoicesectionID"],    // 'feetypeID' => isset($feetypeitem->feetypeID) ? $feetypeitem->feetypeID : 0,    // 'feetype' => isset($feetype[$feetypeitem->feetypeID]) ? $feetype[$feetypeitem->feetypeID] : '',
                        "feetypeID" => $feetypeIDs,
                        "feetype" => $feess_type,
                        "refrence_no" => $ref_no,    // 'maininvoicetotal_fee' => $invoiceMainArray[$j]['maininvoicetotal_fee'],    // 'amount' => isset($feetypeitem->amount) ? $feetypeitem->amount : 0,    // 'discount' => (isset($feetypeitem->discount) ? (($feetypeitem->discount == '') ? 0 : $feetypeitem->discount) : 0),
                        "amount" => isset($invoiceMainArray[$j][
                                "maininvoicetotal_fee"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoicetotal_fee"]
                                            : 0,
                        "discount" => isset($invoiceMainArray[$j][
                                "maininvoice_discount"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoice_discount"]
                                            : 0,
                        "net_fee" => isset($invoiceMainArray[$j][
                                "maininvoicenet_fee"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoicenet_fee"]
                                            : 0,    // 'paidstatus' => ($this->input->post('statusID') !== '0') ? (((float)$feetypeitem->paidamount > (float)0) ? (((float) $feetypeitem->subtotal == (float) $feetypeitem->paidamount) ? 2 : 1 ) : 0) : 0,
                        "paidstatus" => "",
                        "userID" =>        $invoiceMainArray[$j][
                                "maininvoiceuserID"],
                        "usertypeID" =>        $invoiceMainArray[$j][
                                "maininvoiceusertypeID"],
                        "uname" =>        $invoiceMainArray[$j][
                                "maininvoiceuname"],
                        "date" =>        $invoiceMainArray[$j][
                                "maininvoicedate"],
                        "create_date" =>        $invoiceMainArray[$j][
                                "maininvoicecreate_date"],
                        "due_date" =>        $invoiceMainArray[$j][
                                "maininvoicedue_date"],
                        "day" =>        $invoiceMainArray[$j][
                                "maininvoiceday"],
                        "month" =>        $invoiceMainArray[$j][
                                "maininvoicemonth"],
                        "year" =>        $invoiceMainArray[$j][
                                "maininvoiceyear"],
                        "deleted_at" =>        $invoiceMainArray[$j][
                                "maininvoicedeleted_at"],
                        "maininvoiceID" => $i,];
    $net_feess = isset($invoiceMainArray[$j][
                            "maininvoicenet_fee"            ])
                                        ? $invoiceMainArray[$j][
                            "maininvoicenet_fee"            ]
                                        : 0;

                                    // $this->invoice_m->invoice_accounts($invoiceMainArray[$j]['maininvoicestudentID'],$invoiceMainArray[$j]['maininvoiceclassesID'],$net_feess,$ref_no,$i);
    $paymentHistoryArray[] = ["paymenttype" => ucfirst($this->input->post("paymentmethodID")
                    ),    // 'paymentamount' =>  $feetypeitem->paidamount
                        "paymentamount" =>        $invoiceMainArray[$j][
                                "maininvoicenet_fee"],];

    $ref_no = (int) $ref_nos + $j + 1;
                                    // }
                                    // }
    $j++;
                                }
                            }
                            //   }
                            //   exit();
                        }
                        //   if($feess_type == 'Installments'){
                        //         $instcounter = 4;
                        //   }else{
                        //       $instcounter = 1;
                        //   }
                        // for ($y = 1; $y <= $instcounter; $y++) {

        $paymentInserStatus = $this->input->post("statusID");
                        // $paymentInserStatus = 0;
                        // if($this->input->post('statusID') ==! '0') {
                        //     if($this->input->post('totalpaidamount') > 0) {
                        //         if((float) $this->input->post('totalsubtotal') == (float) $this->input->post('totalpaidamount')) {
                        //             $paymentInserStatus = 2;
                        //         } else {
                        //             $paymentInserStatus = 1;
                        //         }
                        //     } else {
                        //         $paymentInserStatus = 0;
                        //     }
                        // }
                        // echo '<pre>';
                        // print_r($invoiceArray);
                        // echo '</pre>';
        $invoicefirstID = $this->invoice_m->insert_batch_invoice($invoiceArray);

        $invoiceSubtotalStatus = 1;
                        if ((float) $this->input->post("totalsubtotal") ==
                            (float) 0) {
            $invoiceSubtotalStatus = 0;
                        }

                        if ($paymentInserStatus && $invoiceSubtotalStatus) {
                            if (customCompute($invoiceArray)) {
                $invoicecount = customCompute($invoiceArray);
                $invoicefirstID = $invoicefirstID;
                $invoicelastID =
    $invoicefirstID + ($invoicecount - 1);

                $globalcount = customCompute($globalPaymentArray);
                $globalfirstID = $this->globalpayment_m->insert_batch_globalpayment($globalPaymentArray);
                $globallastID =
    $globalfirstID + ($globalcount - 1);

                                if (customCompute($studentArray)) {
    $studentcount = customCompute($getstudents);
                                    for ($n = 0;
        $n <= $studentcount - 1;
        $n++) {
        $globalPaymentIDArray[
            $studentArray[$n]
                                        ] = $globalfirstID;
        $globalfirstID++;
                                    }
                                }

                                if ($invoicelastID >= $invoicefirstID) {
    $k = 0;
                                    for ($i = $invoicefirstID;
        $i <= $invoicelastID;
        $i++) {
        $paymentArray[] = [
                            "schoolyearID" =>            $invoiceArray[$k]["schoolyearID"                    ],"invoiceID" => $i,"studentID" =>            $invoiceArray[$k]["studentID"],"paymentamount" => isset($paymentHistoryArray[$k]["paymentamount"                    ])
                                                ? ($paymentHistoryArray[$k]["paymentamount"                    ] == ""                        ? null
                                                    : $paymentHistoryArray[$k]["paymentamount"])
                                                : 0,"paymenttype" => ucfirst($this->input->post("paymentmethodID")),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"type_v" => "hostel_fee","userID" =>            $invoiceArray[$k]["userID"],"usertypeID" =>            $invoiceArray[$k]["usertypeID"],"uname" =>            $invoiceArray[$k]["uname"],"transactionID" =>            "CASHANDCHEQUE" . random19(),"globalpaymentID" => isset($globalPaymentIDArray[
                    $invoiceArray[$k]["studentID"]
                                                ])
                                                ? $globalPaymentIDArray[
                    $invoiceArray[$k]["studentID"]
                                                ]
                                                : 0,    ];
        $k++;
                                    }
                                }

                                if (customCompute($paymentArray)) {
    $this->payment_m->insert_batch_payment($paymentArray);
                                }
                            }
                        }

                        // }

        $this->session->set_flashdata("success",
            $this->lang->line("menu_success"));
        $retArray["status"] = true;
        $retArray["message"] = "Success";
                        echo json_encode($retArray);
                        exit();
                    } else {
        $retArray["error"] = ["student" => "Student not found.",
                        ];
                        echo json_encode($retArray);
                        exit();
                    }

                    // }
                } else {
    $retArray["error"] = ["posttype" => "Post type is required.",
                    ];
                    echo json_encode($retArray);
                    exit();
                }
            } else {
                $retArray["error"] = ["permission" => "Invoice permission is required.",];
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["error"] = ["permission" => "Permission Denied."];
            echo json_encode($retArray);
            exit();
        }
    }

    public function saveinvoice_transport()
    {
        
        $maininvoiceID = 0;
        $retArray["status"] = false;
        if (
            $this->data["siteinfos"]->school_year ==
                $this->session->userdata("defaultschoolyearID") ||
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("defaultschoolyearID") == 5
) {
            if (
                permissionChecker("invoice_add") ||
                permissionChecker("invoice_edit")) {
                if ($_POST) {
    $rules = $this->rules($this->input->post("statusID"));
    $this->form_validation->set_rules($rules);
                    // if ($this->form_validation->run() == FALSE) {
                    //     $retArray['error'] = $this->form_validation->error_array();
                    //     $retArray['status'] = FALSE;
                    //     echo json_encode($retArray);
                    //     exit;
                    // } else {
    $invoiceMainArray = [];
    $globalPaymentArray = [];
    $invoiceArray = [];
    $paymentArray = [];
    $paymentHistoryArray = [];
    $studentArray = [];
    $globalPaymentIDArray = [];
    $feetype = pluck(
        $this->feetypes_m->get_feetypes(),
        "feetypes",
        "feetypesID"
);
    $feetypeitems = json_decode(
        $this->input->post("feetypeitems")
);
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);
    $feess_type = $this->input->post("feess_type");

    $to_date = $this->input->post("to_date");
    $studentID = $this->input->post("studentID");
    $classesID = $this->input->post("classesID");
    $sectionID = $this->input->post("sectionID");
                    // $ref_nos = $this->invoice_m->get_invoice_ref();
    $ref_nos = $this->invoice_m->get_invoice_ref_by_date(
        $this->input->post("date")
);
    $ref_no = $ref_nos;
                    // $ref_no = '01007077';
                    // $num = (int)$ref_nos + 1;
                    // $num = sprintf("%02d", $num);
    $classes = $this->classes_m->general_get_single_classes(["classesID" => $classesID,
                    ]);
    $classes_numeric = $classes->classes_numeric;
                    if ($studentID > 0) {
        $studentArrays = ["srstudentID" => $studentID,
            "srschoolyearID" => $schoolyearID,
            "transport" => 1,
                        ];
                        if ((int) $sectionID) {
            $studentArrays["srsectionID"] = $sectionID;
                        }
        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);

                        // $getstudents = $this->studentrelation_m->get_order_by_student(array("srclassesID" => $classesID, 'srschoolyearID' => $schoolyearID));
                    } elseif ((int) $classesID && $classesID > 0) {
        $studentArrays = ["srclassesID" => $classesID,
            "srschoolyearID" => $schoolyearID,
            "transport" => 1,
                        ];
                        if ((int) $sectionID) {
            $studentArrays["srsectionID"] = $sectionID;
                        }
        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                    } else {
        $studentArrays = ["srschoolyearID" => $schoolyearID,
            "transport" => 1,
                        ];

        $getstudents = $this->studentrelation_m->get_order_by_student($studentArrays);
                    }

    $automationtmember = pluck(
        $this->tmember_m->get_tmember(),
        "obj",
        "studentID"
);

                    if (customCompute($getstudents)) {
        $paymentStatus = 0;
                        // if($this->input->post('statusID') !== '0') {
                        //     if((float) $this->input->post('totalsubtotal') == (float)0) {
                        //         $paymentStatus = 2;
                        //     } else {
                        //         if((float) $this->input->post('totalpaidamount') > (float)0) {
                        //             if((float) $this->input->post('totalsubtotal') == (float) $this->input->post('totalpaidamount')) {
                        //                 $paymentStatus = 2;
                        //             } else {
                        //                 $paymentStatus = 1;
                        //             }
                        //         }
                        //     }
                        // }

        $paymentStatus = $this->input->post("statusID");
        $clearancetype = "unpaid";
                        if ($paymentStatus == 0) {
            $clearancetype = "unpaid";
                        } elseif ($paymentStatus == 1) {
            $clearancetype = "partial";
                        } elseif ($paymentStatus == 2) {
            $clearancetype = "paid";
                        }
                        // echo '<pre>';
                        // print_r($getstudents);
                        // echo '</pre>';
                        // echo '<br>';
                        foreach ($getstudents as $key => $getstudent) {
            $total_transport_fee =
                $automationtmember[$getstudent->srstudentID]->tbalance;
            $transport_fee_discount =
                $automationtmember[$getstudent->srstudentID]->transport_discount;

                            if ($feess_type == "Installments") {
                $instcounter = $getstudent->no_installment;
                            } else {
                $instcounter = 1;
                            }
            $data_counter = 0;
                            for ($y = 1; $y <= $instcounter; $y++) {
                                if ($data_counter == 0) {
    $from_date = date("Y-m-d",    strtotime($this->input->post("date")));
    $due_date = date("Y-m-d",    strtotime($this->input->post("to_date")));
                                } else {
    $from_date = strtotime($this->input->post("date"));
    $due_date = strtotime($this->input->post("to_date"));
    $month = "+" . $data_counter . " month";
    $from_date = date("Y-m-d",    strtotime($month, $from_date));
    $due_date = date("Y-m-d",    strtotime($month, $due_date));
                                }

                                // $maininvoice_discount = $total_transport_fee - $total_transport_fee;
                                if ($total_transport_fee -
        $transport_fee_discount >
                                    0) {
                                    # code...

    $invoiceMainArray[] = ["maininvoiceschoolyearID" => $schoolyearID,
                        "maininvoiceclassesID" =>        $getstudent->srclassesID,
                        "maininvoicestudentID" =>        $getstudent->srstudentID,
                        "maininvoicesectionID" =>        $getstudent->srsectionID,
                        "maininvoicestatus" =>        $this->input->post("statusID") !==
                            "0"                    ? ((float) $this->input->post("totalsubtotal") == (float) 0
                                                    ? 2
                                                    : ((float) $this->input->post("totalpaidamount"    ) > (float) 0
                                                        ? ((float) $this->input->post("totalsubtotal"        ) ==(float) $this->input->post("totalpaidamount"        )
                                                            ? 2
                                                            : 1)
                                                        : 0))
                                                : 0,
                        "maininvoiceuserID" => $this->session->userdata("loginuserID"),
                        "maininvoiceusertypeID" => $this->session->userdata("usertypeID"),
                        "maininvoiceuname" => $this->session->userdata("name"),
                        "maininvoicedate" => $from_date,
                        "maininvoicedue_date" => $due_date,
                        "maininvoicecreate_date" => date("Y-m-d"),
                        "maininvoice_type_v" => "transport_fee",
                        "maininvoicetotal_fee" => ceil($total_transport_fee / $instcounter
                    ),
                        "maininvoice_discount" => ceil($transport_fee_discount /
                $instcounter
                    ),
                        "maininvoicenet_fee" => ceil(($total_transport_fee -
                $transport_fee_discount) /
                $instcounter
                    ),
                        "maininvoicestd_srid" =>        $getstudent->srsection,
                        "maininvoiceday" => date("d"),
                        "maininvoicemonth" => date("m"),
                        "maininvoiceyear" => date("Y"),
                        "maininvoicedeleted_at" => 1,];
    $data_counter++;
                                }
                            }

            $globalPaymentArray[] = ["classesID" => $getstudent->srclassesID,
                "sectionID" => $getstudent->srsectionID,
                "studentID" => $getstudent->srstudentID,
                "clearancetype" => $clearancetype,
                "invoicename" =>    $getstudent->srregisterNO .
                    "-" .
    $getstudent->srname,
                "invoicedescription" => "",
                "paymentyear" => date("Y"),
                "schoolyearID" => $schoolyearID,];

            $studentArray[] = $getstudent->srstudentID;
                        }

                        // echo '<pre>';
                        // print_r($invoiceMainArray);
                        // echo '</pre>';
                        // echo '<br>';
                        // exit();
                        if (customCompute($invoiceMainArray)) {
                            //  for ($y = 1; $y <= $instcounter; $y++){
            $count = customCompute($invoiceMainArray);

            $firstID = $this->maininvoice_m->insert_batch_maininvoice($invoiceMainArray);

            $lastID = $firstID + ($count - 1);
                            if ($feess_type == "Installments") {
                $feetypeIDs = 3;
                            } else {
                $feetypeIDs = 3;
                            }

                            if ($lastID >= $firstID) {
                $j = 0;
                                for ($i = $firstID; $i <= $lastID; $i++) {
                                    if ($i < 10) {
        $ref_i = "0" . $i;
                                    } else {
        $ref_i = $i;
                                    }

                                    // $num = sprintf("%02d", $num);
                                    // var_dump($num);
                                    // exit();
    $current_y = date("Y");
    $current_m = date("m");
                                    if ($invoiceMainArray[$j][
                            "maininvoicestd_srid"            ] < 10) {
        $maininvoicestd_srid =
                            "0" .
            $invoiceMainArray[$j][
                                "maininvoicestd_srid"];
                                    } else {
        $maininvoicestd_srid =
            $invoiceMainArray[$j][
                                "maininvoicestd_srid"];
                                    }

                                    // $ref_no = '01'.$classes_numeric.$maininvoicestd_srid.$current_y.$current_m.$ref_i;
                                    // $ref_no = $num;
                                    // $ref_no = 'Afro'.$current_m.$current_y.$ref_i;
                                    // if(customCompute($feetypeitems)) {
                                    // foreach ($feetypeitems as $feetypeitem) {
    $invoiceArray[] = ["schoolyearID" =>        $invoiceMainArray[$j][
                                "maininvoiceschoolyearID"],
                        "classesID" =>        $invoiceMainArray[$j][
                                "maininvoiceclassesID"],
                        "studentID" =>        $invoiceMainArray[$j][
                                "maininvoicestudentID"],
                        "sectionID" =>        $invoiceMainArray[$j][
                                "maininvoicesectionID"],    // 'feetypeID' => isset($feetypeitem->feetypeID) ? $feetypeitem->feetypeID : 0,    // 'feetype' => isset($feetype[$feetypeitem->feetypeID]) ? $feetype[$feetypeitem->feetypeID] : '',
                        "feetypeID" => $feetypeIDs,
                        "feetype" => $feess_type,
                        "refrence_no" => $ref_no,    // 'maininvoicetotal_fee' => $invoiceMainArray[$j]['maininvoicetotal_fee'],    // 'amount' => isset($feetypeitem->amount) ? $feetypeitem->amount : 0,    // 'discount' => (isset($feetypeitem->discount) ? (($feetypeitem->discount == '') ? 0 : $feetypeitem->discount) : 0),
                        "amount" => isset($invoiceMainArray[$j][
                                "maininvoicetotal_fee"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoicetotal_fee"]
                                            : 0,
                        "discount" => isset($invoiceMainArray[$j][
                                "maininvoice_discount"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoice_discount"]
                                            : 0,
                        "net_fee" => isset($invoiceMainArray[$j][
                                "maininvoicenet_fee"]
                    )
                                            ? $invoiceMainArray[$j][
                                "maininvoicenet_fee"]
                                            : 0,    // 'paidstatus' => ($this->input->post('statusID') !== '0') ? (((float)$feetypeitem->paidamount > (float)0) ? (((float) $feetypeitem->subtotal == (float) $feetypeitem->paidamount) ? 2 : 1 ) : 0) : 0,
                        "paidstatus" => "",
                        "userID" =>        $invoiceMainArray[$j][
                                "maininvoiceuserID"],
                        "usertypeID" =>        $invoiceMainArray[$j][
                                "maininvoiceusertypeID"],
                        "uname" =>        $invoiceMainArray[$j][
                                "maininvoiceuname"],
                        "date" =>        $invoiceMainArray[$j][
                                "maininvoicedate"],
                        "create_date" =>        $invoiceMainArray[$j][
                                "maininvoicecreate_date"],
                        "due_date" =>        $invoiceMainArray[$j][
                                "maininvoicedue_date"],
                        "day" =>        $invoiceMainArray[$j][
                                "maininvoiceday"],
                        "month" =>        $invoiceMainArray[$j][
                                "maininvoicemonth"],
                        "year" =>        $invoiceMainArray[$j][
                                "maininvoiceyear"],
                        "deleted_at" =>        $invoiceMainArray[$j][
                                "maininvoicedeleted_at"],
                        "maininvoiceID" => $i,];
    $net_feess = isset($invoiceMainArray[$j][
                            "maininvoicenet_fee"            ])
                                        ? $invoiceMainArray[$j][
                            "maininvoicenet_fee"            ]
                                        : 0;

                                    // $this->invoice_m->invoice_accounts($invoiceMainArray[$j]['maininvoicestudentID'],$invoiceMainArray[$j]['maininvoiceclassesID'],$net_feess,$ref_no,$i);
    $paymentHistoryArray[] = ["paymenttype" => ucfirst($this->input->post("paymentmethodID")
                    ),    // 'paymentamount' =>  $feetypeitem->paidamount
                        "paymentamount" =>        $invoiceMainArray[$j][
                                "maininvoicenet_fee"],];

    $ref_no = (int) $ref_nos + $j + 1;
                                    // }
                                    // }
    $j++;
                                }
                            }
                            //   }
                            //   exit();
                        }
                        //   if($feess_type == 'Installments'){
                        //         $instcounter = 4;
                        //   }else{
                        //       $instcounter = 1;
                        //   }
                        // for ($y = 1; $y <= $instcounter; $y++) {

        $paymentInserStatus = $this->input->post("statusID");
                        // $paymentInserStatus = 0;
                        // if($this->input->post('statusID') ==! '0') {
                        //     if($this->input->post('totalpaidamount') > 0) {
                        //         if((float) $this->input->post('totalsubtotal') == (float) $this->input->post('totalpaidamount')) {
                        //             $paymentInserStatus = 2;
                        //         } else {
                        //             $paymentInserStatus = 1;
                        //         }
                        //     } else {
                        //         $paymentInserStatus = 0;
                        //     }
                        // }
                        // echo '<pre>';
                        // print_r($invoiceArray);
                        // echo '</pre>';
        $invoicefirstID = $this->invoice_m->insert_batch_invoice($invoiceArray);

        $invoiceSubtotalStatus = 1;
                        if ((float) $this->input->post("totalsubtotal") ==
                            (float) 0) {
            $invoiceSubtotalStatus = 0;
                        }

                        if ($paymentInserStatus && $invoiceSubtotalStatus) {
                            if (customCompute($invoiceArray)) {
                $invoicecount = customCompute($invoiceArray);
                $invoicefirstID = $invoicefirstID;
                $invoicelastID =
    $invoicefirstID + ($invoicecount - 1);

                $globalcount = customCompute($globalPaymentArray);
                $globalfirstID = $this->globalpayment_m->insert_batch_globalpayment($globalPaymentArray);
                $globallastID =
    $globalfirstID + ($globalcount - 1);

                                if (customCompute($studentArray)) {
    $studentcount = customCompute($getstudents);
                                    for ($n = 0;
        $n <= $studentcount - 1;
        $n++) {
        $globalPaymentIDArray[
            $studentArray[$n]
                                        ] = $globalfirstID;
        $globalfirstID++;
                                    }
                                }

                                if ($invoicelastID >= $invoicefirstID) {
    $k = 0;
                                    for ($i = $invoicefirstID;
        $i <= $invoicelastID;
        $i++) {
        $paymentArray[] = [
                            "schoolyearID" =>            $invoiceArray[$k]["schoolyearID"                    ],"invoiceID" => $i,"studentID" =>            $invoiceArray[$k]["studentID"],"paymentamount" => isset($paymentHistoryArray[$k]["paymentamount"                    ])
                                                ? ($paymentHistoryArray[$k]["paymentamount"                    ] == ""                        ? null
                                                    : $paymentHistoryArray[$k]["paymentamount"])
                                                : 0,"paymenttype" => ucfirst($this->input->post("paymentmethodID")),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"type_v" => "transport_fee","userID" =>            $invoiceArray[$k]["userID"],"usertypeID" =>            $invoiceArray[$k]["usertypeID"],"uname" =>            $invoiceArray[$k]["uname"],"transactionID" =>            "CASHANDCHEQUE" . random19(),"globalpaymentID" => isset($globalPaymentIDArray[
                    $invoiceArray[$k]["studentID"]
                                                ])
                                                ? $globalPaymentIDArray[
                    $invoiceArray[$k]["studentID"]
                                                ]
                                                : 0,    ];
        $k++;
                                    }
                                }

                                if (customCompute($paymentArray)) {
    $this->payment_m->insert_batch_payment($paymentArray);
                                }
                            }
                        }

                        // }

        $this->session->set_flashdata("success",
            $this->lang->line("menu_success"));
        $retArray["status"] = true;
        $retArray["message"] = "Success";
                        echo json_encode($retArray);
                        exit();
                    } else {
        $retArray["error"] = ["student" => "Student not found.",
                        ];
                        echo json_encode($retArray);
                        exit();
                    }

                    // }
                } else {
    $retArray["error"] = ["posttype" => "Post type is required.",
                    ];
                    echo json_encode($retArray);
                    exit();
                }
            } else {
                $retArray["error"] = ["permission" => "Invoice permission is required.",];
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["error"] = ["permission" => "Permission Denied."];
            echo json_encode($retArray);
            exit();
        }
    }

    public function saveinvoicefforedit()
    {
        if (
            $this->data["siteinfos"]->school_year ==
                $this->session->userdata("defaultschoolyearID") ||
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("defaultschoolyearID") == 5
) {
            $maininvoiceID = 0;
            $retArray["status"] = false;
            if (permissionChecker("invoice_edit")) {
                if ($_POST) {
    $rules = $this->rules($this->input->post("statusID"));
    $this->form_validation->set_rules($rules);
                    // if ($this->form_validation->run() == FALSE) {
                    //     $retArray['error'] = $this->form_validation->error_array();
                    //     $retArray['status'] = FALSE;
                    //     echo json_encode($retArray);
                    //     exit;
                    // } else {
    $globalPaymentArray = [];
    $mainInvoiceArray = [];
    $invoiceArray = [];
    $paymentArray = [];
    $paymentHistoryArray = [];

    $editID = $this->input->post("editID");
                    if ((int) $editID) {

      $old_invoice_data  = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $editID]);

      $get_old_dicount  =   unserialize($old_invoice_data->fee_breakup);
      $old_dis_ar   =[];
                      foreach ($get_old_dicount as $god) {
          $old_dis_ar[$god['feetypeID']]   =    $god['fee_discount'];
                      }

                      //var_dump($old_invoice_data);

       $breakup_info_ar         =       [];

        $sr   =   0;

        $feetypeitems  =    json_decode($_POST['feetypeitems']);
            $fee_total      =   0;
            $net_discount   =   0;
            $net_total      =   0;

                        foreach($feetypeitems as $fb){

                            

            $feetypeID          =   $fb->feetypeID;
            $get_feetype        =   $this->feetypes_m->get_feetypes($feetypeID);
            $fee_amount         =   $fb->amount;
            $fee_discount       =   $fb->discount;
            $net_amount         =   $fee_amount-$fee_discount;
                          

            $breakup_info_ar[]    = array(
                                                'feetypeID'         => $get_feetype->feetypesID, 
                                                'feetypes'          => $get_feetype->feetypes, 
                                                'fee_discount'      => $fee_discount, 
                                                'fee_amount'        => $fee_amount, 
                                                'fee_discount_a'    => $fee_discount, 
                                                'net_amount'        => $net_amount, 
                                                'discount_left'     => 0,  
                                                'feetypedetail'     => $get_feetype, 
                                                'S_DIS'             => $fee_discount, 
                                    );
                $journal_items_ar_cr  = array(  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'account'       =>  $get_feetype->credit_id,  
                                                'feetypeID'     =>  $get_feetype->feetypesID,  
                                                'entry_type'    =>  'CR',);
                $journal_items_ar_cr_up  = array(  
                                                'credit'        =>  $net_amount, 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $this->invoice_m->update_journal_items($journal_items_ar_cr_up,$journal_items_ar_cr);

                $journal_items_ar_dr  = array(  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'account'       =>  $get_feetype->debit_id,  
                                                'feetypeID'     =>  $get_feetype->feetypesID,  
                                                'entry_type'    =>  'DR',);
                $journal_items_ar_dr_up  = array(  
                                                'debit'         =>  $net_amount, 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $this->invoice_m->update_journal_items($journal_items_ar_dr_up,$journal_items_ar_dr);
                 
    $student_ledgers_ar_cr  = array( 
                                'maininvoice_id'           =>  $editID, 
                                'reference_type'           =>  'invoice', 
                                'feetypeID'                =>  $get_feetype->feetypesID,   
                                'type'                     =>  'CR', 
                                'account'                  =>  $get_feetype->credit_id );
    $student_ledgers_ar_up_cr  = array(   
                                'credit'                   =>  $net_amount, 
                                'balance'                  =>  $net_amount,    
                                'updated_at'               =>  date('Y-m-d h:i:s') );
    $this->invoice_m->update_studnet_ledgers($student_ledgers_ar_up_cr,$student_ledgers_ar_cr);
    $student_ledgers_ar_cr  = array( 
                                'maininvoice_id'           =>  $editID, 
                                'reference_type'           =>  'invoice', 
                                'feetypeID'                =>  $get_feetype->feetypesID,   
                                'type'                     =>  'DR', 
                                'account'                  =>  $get_feetype->debit_id );
    $student_ledgers_ar_up_cr  = array(   
                                'debit'                    =>  $net_amount, 
                                'balance'                  =>  $net_amount,    
                                'updated_at'               =>  date('Y-m-d h:i:s') );
    $this->invoice_m->update_studnet_ledgers($student_ledgers_ar_up_cr,$student_ledgers_ar_cr);
                
                if ($old_dis_ar[$get_feetype->feetypesID]>0) {
                    

                $journal_items_dis_ar  = array(
                                                'account'       =>  $this->data["siteinfos"]->discount_credit,   
                                                'entry_type'    =>  'CR',  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'feetypeID'     =>  $get_feetype->feetypesID  );
                $journal_items_dis_ar_up  = array(
                                                  
                                                'credit'        =>  $fee_discount,  
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $this->invoice_m->update_journal_items($journal_items_dis_ar_up,$journal_items_dis_ar);

                $journal_items_dis_dr  = array(
                                                'account'       =>  $this->data["siteinfos"]->discount_debit,   
                                                'entry_type'    =>  'DR',  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'feetypeID'     =>  $get_feetype->feetypesID  );
                $journal_items_dis_dr_up  = array(
                                                  
                                                'debit'         =>  $fee_discount,  
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $this->invoice_m->update_journal_items($journal_items_dis_dr_up,$journal_items_dis_dr);



    $student_ledgers_ds_cr  = array( 
                                'maininvoice_id'           =>  $editID, 
                                'reference_type'           =>  'invoice', 
                                'feetypeID'                =>  $get_feetype->feetypesID,   
                                'type'                     =>  'CR', 
                                'account'                  =>  $get_feetype->credit_id );
    $student_ledgers_ds_up_cr  = array(   
                                'credit'                   =>  $fee_discount, 
                                'balance'                  =>  $fee_discount,    
                                'updated_at'               =>  date('Y-m-d h:i:s') );
    $this->invoice_m->update_studnet_ledgers($student_ledgers_ds_up_cr,$student_ledgers_ds_cr);
    $student_ledgers_ds_cr  = array( 
                                'maininvoice_id'           =>  $editID, 
                                'reference_type'           =>  'invoice', 
                                'feetypeID'                =>  $get_feetype->feetypesID,   
                                'type'                     =>  'DR', 
                                'account'                  =>  $get_feetype->debit_id );
    $student_ledgers_ar_up_ds  = array(   
                                'debit'                    =>  $fee_discount, 
                                'balance'                  =>  $fee_discount,    
                                'updated_at'               =>  date('Y-m-d h:i:s') );
    $this->invoice_m->update_studnet_ledgers($student_ledgers_ar_up_ds,$student_ledgers_ds_cr);
                 
                 


                }else{


                

                
                if ($fee_discount>0) {
    $journal_entries_id   =     $this->invoice_m->journal_entries_id(array('studentID'=>$old_invoice_data->maininvoicestudentID));

                $journal_items_ar[]  = array(
                                                'journal'       =>  $journal_entries_id, 
                                                'account'       =>  $this->data["siteinfos"]->discount_credit, 
                                                'description'   =>  $get_feetype->feetypes.' fee for semster '.$old_invoice_data->maininvoicestd_srid, 
                                                'debit'         =>  0, 
                                                'credit'        =>  $fee_discount, 
                                                'entry_type'    =>  'CR',  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'feetypeID'     =>  $get_feetype->feetypesID,            'created_at'    =>  date('Y-m-d h:i:s'), 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $journal_items_ar[]  = array(
                                                'journal'       =>  $journal_entries_id, 
                                                'account'       =>  $this->data["siteinfos"]->discount_debit, 
                                                'description'   =>  $get_feetype->feetypes.' fee for semster '.$old_invoice_data->maininvoicestd_srid, 
                                                'debit'         =>  $fee_discount, 
                                                'entry_type'    =>  'DR', 
                                                'credit'        =>  0,  
                                                'referenceID'   =>  $editID,            'reference_type'=>  'invoice',            'feetypeID'     =>  $get_feetype->feetypesID,            'created_at'    =>  date('Y-m-d h:i:s'), 
                                                'updated_at'    =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                                'journal_entries_id'       =>  $journal_entries_id, 
                                'maininvoice_id'           =>  $editID,   
                                'date'                     =>  date('Y-m-d',strtotime($old_invoice_data->maininvoicedate)), 
                                'type'                     =>  'CR', 
                                'account'                  =>  $this->data["siteinfos"]->discount_credit, 
                                'vr_no'                    =>  $editID, 
                                'narration'                =>  $get_feetype->feetypes.' fee for semster '.$old_invoice_data->maininvoicestd_srid, 
                                'debit'                    =>  0, 
                                'reference_type'           =>  'invoice',    'feetypeID'                =>  $get_feetype->feetypesID,    'credit'                   =>  $fee_discount, 
                                'balance'                  =>  $fee_discount,  
                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                'updated_at'               =>  date('Y-m-d h:i:s'), );
                $student_ledgers_ar[]  = array(
                                'journal_entries_id'       =>  $journal_entries_id, 
                                'maininvoice_id'           =>   $editID,  
                                'date'                     =>  date('Y-m-d',strtotime($old_invoice_data->maininvoicedate)), 
                                'type'                     =>  'DR', 
                                'account'                  =>  $this->data["siteinfos"]->discount_debit, 
                                'vr_no'                    =>  $editID, 
                                'narration'                =>  $get_feetype->feetypes.' fee for semster '.$old_invoice_data->maininvoicestd_srid, 
                                'debit'                    =>  $fee_discount, 
                                'credit'                   =>  0, 
                                'reference_type'           =>  'invoice',    'feetypeID'                =>  $get_feetype->feetypesID,    'balance'                  =>  $fee_discount,  
                                'created_at'               =>  date('Y-m-d h:i:s'), 
                                'updated_at'               =>  date('Y-m-d h:i:s'), );

                $this->invoice_m->push_invoice_to_journal_items( $journal_items_ar );
                  $this->invoice_m->push_invoice_to_studnet_ledgers( $student_ledgers_ar );
                }


                }


            $fee_total      +=   $fee_amount;
            $net_discount   +=   $fee_discount;
            $net_total      +=   $net_amount;

            $sr++;  
                        }

        $feebreakup     =   serialize($breakup_info_ar);

        $inv            =   $this->invoice_m->get_invoice_order($editID)[0];

            $paidamount     =   $this->payment_m->get_payment_by_sum($inv->invoiceID)->paymentamount;

             
            $updatearray_invoice  =     array(
                            'fee_breakup'   => $feebreakup, 
                            'amount'        => $fee_total, 
                            'discount'      => $net_discount, 
                            'net_fee'       => $net_total-$paidamount, 
                            'date'          => date('Y-m-d',strtotime($this->input->post("date"))), 
                            'due_date'      => date('Y-m-d',strtotime($this->input->post("to_date"))), 
                        );
            $this->invoice_m->update_invoice_by_maininvoiceID($updatearray_invoice,$editID);


            $updatearray_main_invoice  =     array(
                        'fee_breakup'             => $feebreakup, 
                        'maininvoicetotal_fee'    => $fee_total, 
                        'maininvoice_discount'    => $net_discount, 
                        'maininvoicenet_fee'      => $net_total-$paidamount, 
                        'maininvoicedate'         => date('Y-m-d',strtotime($this->input->post("date"))), 
                        'maininvoicedue_date'     => date('Y-m-d',strtotime($this->input->post("to_date"))), 
                        );
            $this->maininvoice_m->update_maininvoice($updatearray_main_invoice,$editID);
                      

                                $this->session->set_flashdata("success", $this->lang->line("menu_success"));
                                $retArray["status"] = true;
                                $retArray["message"] = "Success";
                                echo json_encode($retArray);

                            } else {
                                $retArray["error"] = ["editid" => "Edit id is required.",
                        ];
                        echo json_encode($retArray);
                        exit();
                    }
                    // }
                } else {
                    $retArray["error"] = ["posttype" => "Post type is required.",
                    ];
                    echo json_encode($retArray);
                    exit();
                }
            } else {
                $retArray["error"] = ["permission" => "Invoice permission is required.",];
                echo json_encode($retArray);
                exit();
            }
        } else {
            $retArray["error"] = ["permission" => "Permission Denied."];
            echo json_encode($retArray);
            exit();
        }
    }

    private function grandtotalandpaid($maininvoices, $schoolyearID)
    {
        $retArray = [];
        $invoiceitems = pluck_multi_array_key(
            $this->invoice_m->get_order_by_invoice(["schoolyearID" => $schoolyearID,
            ]),
"obj",
"maininvoiceID",
"invoiceID"
);
        $paymentitems = pluck_multi_array(
            $this->payment_m->get_order_by_payment(["schoolyearID" => $schoolyearID,
"paymentamount !=" => null,
            ]),
"obj",
"invoiceID"
);
        $weaverandfineitems = pluck_multi_array(
            $this->weaverandfine_m->get_order_by_weaverandfine(["schoolyearID" => $schoolyearID,
            ]),
"obj",
"invoiceID"
);
        if (customCompute($maininvoices)) {
            foreach ($maininvoices as $maininvoice) {
                if (isset($invoiceitems[$maininvoice->maininvoiceID])) {
                    if (
                        customCompute($invoiceitems[$maininvoice->maininvoiceID])
) {
                        foreach ($invoiceitems[$maininvoice->maininvoiceID]
                            as $invoiceitem) {
            $amount = $invoiceitem->amount;
                            if ($invoiceitem->discount > 0) {
                $amount =
    $invoiceitem->amount -
                                    ($invoiceitem->amount / 100) *
        $invoiceitem->discount;
                            }

                            if (isset($retArray["grandtotal"][
        $maininvoice->maininvoiceID])) {
                $retArray["grandtotal"][
    $maininvoice->maininvoiceID] =
    $retArray["grandtotal"][
        $maininvoice->maininvoiceID] + $amount;
                            } else {
                $retArray["grandtotal"][
    $maininvoice->maininvoiceID] = $amount;
                            }

                            if (isset($retArray["totalamount"][
        $maininvoice->maininvoiceID])) {
                $retArray["totalamount"][
    $maininvoice->maininvoiceID] =
    $retArray["totalamount"][
        $maininvoice->maininvoiceID] + $invoiceitem->amount;
                            } else {
                $retArray["totalamount"][
    $maininvoice->maininvoiceID] = $invoiceitem->amount;
                            }

                            if (isset($retArray["totaldiscount"][
        $maininvoice->maininvoiceID])) {
                $retArray["totaldiscount"][
    $maininvoice->maininvoiceID] =
    $retArray["totaldiscount"][
        $maininvoice->maininvoiceID] +
                                    ($invoiceitem->amount / 100) *
        $invoiceitem->discount;
                            } else {
                $retArray["totaldiscount"][
    $maininvoice->maininvoiceID] =
                                    ($invoiceitem->amount / 100) *
    $invoiceitem->discount;
                            }

                            if (isset($paymentitems[$invoiceitem->invoiceID])) {
                                if (customCompute($paymentitems[$invoiceitem->invoiceID])) {
                                    foreach ($paymentitems[$invoiceitem->invoiceID]
                                        as $paymentitem) {
                                        if (isset($retArray["totalpayment"][
                    $maininvoice->maininvoiceID
                                                ])
                    ) {
            $retArray["totalpayment"][
                $maininvoice->maininvoiceID
                                            ] =
                $retArray["totalpayment"][
                    $maininvoice->maininvoiceID
                                                ] + $paymentitem->paymentamount;
                                        } else {
            $retArray["totalpayment"][
                $maininvoice->maininvoiceID
                                            ] = $paymentitem->paymentamount;
                                        }
                                    }
                                }
                            }

                            if (isset($weaverandfineitems[$invoiceitem->invoiceID])) {
                                if (customCompute($weaverandfineitems[
            $invoiceitem->invoiceID
                                        ])) {
                                    foreach ($weaverandfineitems[
            $invoiceitem->invoiceID
                                        ]
                                        as $weaverandfineitem) {
                                        if (isset($retArray["totalweaver"][
                    $maininvoice->maininvoiceID
                                                ])
                    ) {
            $retArray["totalweaver"][
                $maininvoice->maininvoiceID
                                            ] =
                $retArray["totalweaver"][
                    $maininvoice->maininvoiceID
                                                ] + $weaverandfineitem->weaver;
                                        } else {
            $retArray["totalweaver"][
                $maininvoice->maininvoiceID
                                            ] = $weaverandfineitem->weaver;
                                        }

                                        if (isset($retArray["totalfine"][
                    $maininvoice->maininvoiceID
                                                ])
                    ) {
            $retArray["totalfine"][
                $maininvoice->maininvoiceID
                                            ] =
                $retArray["totalfine"][
                    $maininvoice->maininvoiceID
                                                ] + $weaverandfineitem->fine;
                                        } else {
            $retArray["totalfine"][
                $maininvoice->maininvoiceID
                                            ] = $weaverandfineitem->fine;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $retArray;
    }

    private function grandtotalandpaidsingle(
        $maininvoice,
        $schoolyearID,
        $studentID = null
) {
        $retArray = ["grandtotal" => 0,
"totalamount" => 0,
"totaldiscount" => 0,
"totalpayment" => 0,
"totalfine" => 0,
"totalweaver" => 0,
        ];
        if (customCompute($maininvoice)) {
            if ((int) $studentID && $studentID != null) {
                $invoiceitems = pluck_multi_array_key(
    $this->invoice_m->get_order_by_invoice(["studentID" => $studentID,
        "maininvoiceID" => $maininvoice->maininvoiceID,
        "schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "maininvoiceID",
    "invoiceID");
                $paymentitems = pluck_multi_array(
    $this->payment_m->get_order_by_payment(["schoolyearID" => $schoolyearID,
        "paymentamount !=" => null,
                    ]),
    "obj",
    "invoiceID");
                $weaverandfineitems = pluck_multi_array(
    $this->weaverandfine_m->get_order_by_weaverandfine(["schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "invoiceID");
            } else {
                $invoiceitem = [];
                $paymentitems = [];
                $weaverandfineitems = [];
            }

            if (isset($invoiceitems[$maininvoice->maininvoiceID])) {
                if (customCompute($invoiceitems[$maininvoice->maininvoiceID])) {
                    foreach (
        $invoiceitems[$maininvoice->maininvoiceID]
                        as $invoiceitem
) {
        $amount = $invoiceitem->amount;
                        if ($invoiceitem->discount > 0) {
            $amount =
                $invoiceitem->amount -
                                ($invoiceitem->amount / 100) *
    $invoiceitem->discount;
                        }

                        if (isset($retArray["grandtotal"])) {
            $retArray["grandtotal"] =
                $retArray["grandtotal"] + $amount;
                        } else {
            $retArray["grandtotal"] = $amount;
                        }

                        if (isset($retArray["totalamount"])) {
            $retArray["totalamount"] =
                $retArray["totalamount"] + $invoiceitem->amount;
                        } else {
            $retArray["totalamount"] = $invoiceitem->amount;
                        }

                        if (isset($retArray["totaldiscount"])) {
            $retArray["totaldiscount"] =
                $retArray["totaldiscount"] +
                                ($invoiceitem->amount / 100) *
    $invoiceitem->discount;
                        } else {
            $retArray["totaldiscount"] =
                                ($invoiceitem->amount / 100) *
                $invoiceitem->discount;
                        }

                        if (isset($paymentitems[$invoiceitem->invoiceID])) {
                            if (customCompute($paymentitems[$invoiceitem->invoiceID])) {
                                foreach ($paymentitems[$invoiceitem->invoiceID]
                                    as $paymentitem) {
                                    if (isset($retArray["totalpayment"])) {
        $retArray["totalpayment"] =
            $retArray["totalpayment"] +
            $paymentitem->paymentamount;
                                    } else {
        $retArray["totalpayment"] =
            $paymentitem->paymentamount;
                                    }
                                }
                            }
                        }

                        if (isset($weaverandfineitems[$invoiceitem->invoiceID])) {
                            if (customCompute($weaverandfineitems[$invoiceitem->invoiceID])) {
                                foreach ($weaverandfineitems[$invoiceitem->invoiceID]
                                    as $weaverandfineitem) {
                                    if (isset($retArray["totalweaver"])) {
        $retArray["totalweaver"] =
            $retArray["totalweaver"] +
            $weaverandfineitem->weaver;
                                    } else {
        $retArray["totalweaver"] =
            $weaverandfineitem->weaver;
                                    }

                                    if (isset($retArray["totalfine"])) {
        $retArray["totalfine"] =
            $retArray["totalfine"] +
            $weaverandfineitem->fine;
                                    } else {
        $retArray["totalfine"] =
            $weaverandfineitem->fine;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $retArray;
    }

    private function paymentdue($maininvoice, $schoolyearID, $studentID = null)
    {
        $retArray = [];
        if (customCompute($maininvoice)) {
            if ((int) $studentID && $studentID != null) {
                $invoiceitems = pluck_multi_array_key(
    $this->invoice_m->get_order_by_invoice(["studentID" => $studentID,
        "maininvoiceID" => $maininvoice->maininvoiceID,
        "schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "maininvoiceID",
    "invoiceID");
                $paymentitems = pluck_multi_array(
    $this->payment_m->get_order_by_payment(["schoolyearID" => $schoolyearID,
        "paymentamount !=" => null,
                    ]),
    "obj",
    "invoiceID");
                $weaverandfineitems = pluck_multi_array(
    $this->weaverandfine_m->get_order_by_weaverandfine(["schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "invoiceID");
            } else {
                $invoiceitem = [];
                $paymentitems = [];
                $weaverandfineitems = [];
            }

            if (isset($invoiceitems[$maininvoice->maininvoiceID])) {
                if (customCompute($invoiceitems[$maininvoice->maininvoiceID])) {
                    foreach (
        $invoiceitems[$maininvoice->maininvoiceID]
                        as $invoiceitem
) {
        $amount = $invoiceitem->amount;
                        if ($invoiceitem->discount > 0) {
            $amount =
                $invoiceitem->amount -
                                ($invoiceitem->amount / 100) *
    $invoiceitem->discount;
                        }

                        if (isset($retArray["totalamount"][
    $invoiceitem->invoiceID])) {
            $retArray["totalamount"][$invoiceitem->invoiceID] =
                $retArray["totalamount"][
    $invoiceitem->invoiceID] + $invoiceitem->amount;
                        } else {
            $retArray["totalamount"][$invoiceitem->invoiceID] =
                $invoiceitem->amount;
                        }

                        if (isset($retArray["totaldiscount"][
    $invoiceitem->invoiceID])) {
            $retArray["totaldiscount"][
                $invoiceitem->invoiceID] =
                $retArray["totaldiscount"][
    $invoiceitem->invoiceID] +
                                ($invoiceitem->amount / 100) *
    $invoiceitem->discount;
                        } else {
            $retArray["totaldiscount"][
                $invoiceitem->invoiceID] =
                                ($invoiceitem->amount / 100) *
                $invoiceitem->discount;
                        }

                        if (isset($paymentitems[$invoiceitem->invoiceID])) {
                            if (customCompute($paymentitems[$invoiceitem->invoiceID])) {
                                foreach ($paymentitems[$invoiceitem->invoiceID]
                                    as $paymentitem) {
                                    if (isset($retArray["totalpayment"][
                $paymentitem->invoiceID
                                            ]
                    )) {
        $retArray["totalpayment"][
            $paymentitem->invoiceID
                                        ] =
            $retArray["totalpayment"][
                $paymentitem->invoiceID
                                            ] + $paymentitem->paymentamount;
                                    } else {
        $retArray["totalpayment"][
            $paymentitem->invoiceID
                                        ] = $paymentitem->paymentamount;
                                    }
                                }
                            }
                        }

                        if (isset($weaverandfineitems[$invoiceitem->invoiceID])) {
                            if (customCompute($weaverandfineitems[$invoiceitem->invoiceID])) {
                                foreach ($weaverandfineitems[$invoiceitem->invoiceID]
                                    as $weaverandfineitem) {
                                    if (isset($retArray["totalweaver"][
                $weaverandfineitem->invoiceID
                                            ]
                    )) {
        $retArray["totalweaver"][
            $weaverandfineitem->invoiceID
                                        ] =
            $retArray["totalweaver"][
                $weaverandfineitem->invoiceID
                                            ] + $weaverandfineitem->weaver;
                                    } else {
        $retArray["totalweaver"][
            $weaverandfineitem->invoiceID
                                        ] = $weaverandfineitem->weaver;
                                    }

                                    if (isset($retArray["totalfine"][
                $weaverandfineitem->invoiceID
                                            ]
                    )) {
        $retArray["totalfine"][
            $weaverandfineitem->invoiceID
                                        ] =
            $retArray["totalfine"][
                $weaverandfineitem->invoiceID
                                            ] + $weaverandfineitem->fine;
                                    } else {
        $retArray["totalfine"][
            $weaverandfineitem->invoiceID
                                        ] = $weaverandfineitem->fine;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // echo '<pre>';
        // print_r($retArray);
        // echo '</pre>';
        // exit();
        return $retArray;
    }

    private function globalpayment(
        $maininvoice,
        $schoolyearID,
        $studentID = null
) {
        if (customCompute($maininvoice)) {
            if ((int) $studentID && $studentID != null) {
                $invoiceitems = pluck_multi_array_key(
    $this->invoice_m->get_order_by_invoice(["studentID" => $studentID,
        "maininvoiceID" => $maininvoice->maininvoiceID,
        "schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "maininvoiceID",
    "invoiceID");
                $paymentitems = pluck_multi_array(
    $this->payment_m->get_order_by_payment(["schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "invoiceID");
                $weaverandfineitems = pluck_multi_array(
    $this->weaverandfine_m->get_order_by_weaverandfine(["schoolyearID" => $schoolyearID,
                    ]),
    "obj",
    "invoiceID");
            } else {
                $invoiceitem = [];
                $paymentitems = [];
                $weaverandfineitems = [];
            }

            if (isset($invoiceitems[$maininvoice->maininvoiceID])) {
                if (customCompute($invoiceitems[$maininvoice->maininvoiceID])) {
                    foreach (
        $invoiceitems[$maininvoice->maininvoiceID]
                        as $invoiceitem
) {
                        if (isset($paymentitems[$invoiceitem->invoiceID])) {
                            if (customCompute($paymentitems[$invoiceitem->invoiceID])) {
                                foreach ($paymentitems[$invoiceitem->invoiceID]
                                    as $paymentitem) {
    $retArray["globalpayments"][
        $paymentitem->globalpaymentID][$paymentitem->paymentID] = ["paymentID" => $paymentitem->paymentID,
                        "invoiceID" => $paymentitem->invoiceID,
                        "paymentamount" =>        $paymentitem->paymentamount,
                        "paymentdate" =>        $paymentitem->paymentdate,
                        "weaver" => "",
                        "fine" => "",];
                                }
                            }
                        }

                        if (isset($weaverandfineitems[$invoiceitem->invoiceID])) {
                            if (customCompute($weaverandfineitems[$invoiceitem->invoiceID])) {
                                foreach ($weaverandfineitems[$invoiceitem->invoiceID]
                                    as $weaverandfineitem) {
    $retArray["globalpayments"][
        $weaverandfineitem->globalpaymentID][$weaverandfineitem->paymentID]["weaver"] =
        $weaverandfineitem->weaver;

    $retArray["globalpayments"][
        $weaverandfineitem->globalpaymentID][$weaverandfineitem->paymentID]["fine"] =
        $weaverandfineitem->fine;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $retArray;
    }

    public function paymentlist()
    {
        if (permissionChecker("invoice_view")) {
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $maininvoiceID = $this->input->post("maininvoiceID");

            $globalPaymentArray = [];
            $globalpaymentobjects = [];
            $allpayments = [];
            $allweaverandfines = [];
            $paymentlists = [];

            if (
                !empty($maininvoiceID) &&
                (int) $maininvoiceID &&
                $maininvoiceID > 0) {
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $maininvoiceID,
    "maininvoiceschoolyearID" => $schoolyearID,]);
                if (customCompute($maininvoice)) {
    $invoices = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $maininvoiceID,
        "schoolyearID" => $schoolyearID,
                    ]);
    $globalpayments = pluck(
        $this->globalpayment_m->get_order_by_globalpayment(["studentID" => $maininvoice->maininvoicestudentID,
                        ]),
        "obj",
        "globalpaymentID"
);

                    if (customCompute($invoices)) {
                        foreach ($invoices as $invoice) {
            $payments = $this->payment_m->get_orderr_by_payment(["invoiceID" => $invoice->invoiceID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,]);

            $weaverandfines = $this->weaverandfine_m->get_order_by_weaverandfine(["invoiceID" => $invoice->invoiceID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,]);

                            if (customCompute($payments)) {
                                foreach ($payments as $payment) {
                                    if (isset($globalpayments[
                $payment->globalpaymentID
                                            ]
                    )) {
        $allpayments[
            $payment->globalpaymentID
                                        ][] = $payment;
                                        if (!in_array($payment->globalpaymentID,            $globalPaymentArray)
                    ) {
            $globalPaymentArray[] =
                $payment->globalpaymentID;
            $globalpaymentobjects[] =
                $globalpayments[
                    $payment->globalpaymentID
                                                ];
                                        }
                                    }
                                }
                            }

                            if (customCompute($weaverandfines)) {
                                foreach ($weaverandfines as $weaverandfine) {
    $allweaverandfines[
        $weaverandfine->globalpaymentID][] = $weaverandfine;
                                }
                            }
                        }
                    }

                    if (customCompute($globalpaymentobjects)) {
                        foreach ($globalpaymentobjects
                            as $globalpaymentobject) {
                            if (isset($allpayments[
        $globalpaymentobject->globalpaymentID])) {
                                if (customCompute($allpayments[
            $globalpaymentobject
                                                ->globalpaymentID
                                        ])) {
                                    foreach ($allpayments[
            $globalpaymentobject
                                                ->globalpaymentID
                                        ]
                                        as $payment) {
                                        if (isset($paymentlists[
                    $globalpaymentobject
                                                        ->globalpaymentID
                                                ])
                    ) {
            $paymentlists[
                $globalpaymentobject->globalpaymentID
                                            ]["paymentamount"] +=
                $payment->paymentamount;
                                        } else {
            $paymentlists[
                $globalpaymentobject->globalpaymentID
                                            ] = [
                                "globalpaymentID" =>$globalpaymentobject->globalpaymentID,    "paymentamount" =>$payment->paymentamount,    "date" => $payment->paymentdate,    "paymenttype" =>$payment->paymenttype,    "refrence_no" =>$payment->refrence_no,        ];
                                        }
                                    }

                                    if (isset($allweaverandfines[
                $globalpaymentobject
                                                    ->globalpaymentID
                                            ]
                    )) {
                                        foreach ($allweaverandfines[
                $globalpaymentobject
                                                    ->globalpaymentID
                                            ]
                                            as $allweaverandfine
                    ) {
                                            if (isset($paymentlists[$globalpaymentobject
                                                            ->globalpaymentID]["weaveramount"]) &&
                                                isset($paymentlists[$globalpaymentobject
                                                            ->globalpaymentID]["fineamount"])) {
                $paymentlists[
                    $globalpaymentobject->globalpaymentID
                                                ]["weaveramount"] +=    $allweaverandfine->weaver;
                $paymentlists[
                    $globalpaymentobject->globalpaymentID
                                                ]["fineamount"] +=    $allweaverandfine->fine;
                                            } else {
                                                if (isset($paymentlists[$globalpaymentobject
                                                                ->globalpaymentID
                                                        ]
                                )
                            ) {
                    $paymentlists[$globalpaymentobject->globalpaymentID]["weaveramount"] =$allweaverandfine->weaver;
                    $paymentlists[$globalpaymentobject->globalpaymentID]["fineamount"] =$allweaverandfine->fine;
                                                } else {
                    $paymentlists[$globalpaymentobject->globalpaymentID] = ["weaveramount" =>        $allweaverandfine->weaver,    "fineamount" =>        $allweaverandfine->fine,];
                                                }
                                            }
                                        }
                                    } else {
        $paymentlists[
            $globalpaymentobject->globalpaymentID
                                        ]["weaveramount"] = 0;
        $paymentlists[
            $globalpaymentobject->globalpaymentID
                                        ]["fineamount"] = 0;
                                    }
                                }
                            }
                        }
                    }
                }

                if (customCompute($paymentlists)) {
    $i = 1;
                    foreach ($paymentlists as $key => $paymentlist) {
                        echo "<tr>";
                        echo '<td data-title="' .
            $this->lang->line("slno") .
                            '">';
                        echo $i;
                        echo "</td>";

                        echo '<td data-title="Refrence_no">';
                        echo $paymentlist["refrence_no"];
                        echo "</td>";

                        echo '<td data-title="' .
            $this->lang->line("invoice_date") .
                            '">';
                        echo date("d M Y", strtotime($paymentlist["date"]));
                        echo "</td>";

                        echo '<td data-title="' .
            $this->lang->line("invoice_paymentmethod") .
                            '">';
                        echo $paymentlist["paymenttype"];
                        echo "</td>";

                        echo '<td data-title="' .
            $this->lang->line("invoice_paymentamount") .
                            '">';
                        echo ceil($paymentlist["paymentamount"]);
                        echo "</td>";

                        echo '<td data-title="' .
            $this->lang->line("invoice_weaver") .
                            '">';
                        echo ceil($paymentlist["weaveramount"]);
                        echo "</td>";

                        echo '<td data-title="' .
            $this->lang->line("invoice_fine") .
                            '">';
                        echo ceil($paymentlist["fineamount"]);
                        echo "</td>";
                        echo '<td data-title="' .
            $this->lang->line("action") .
                            '">';
                        if (permissionChecker("invoice_view")) {
                            echo '<a href="' .
                                base_url("invoice/viewpayment/" .
        $paymentlist["globalpaymentID"] .
                        "/" .
        $maininvoiceID) .
                                '" class="btn btn-success btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="' .
                $this->lang->line("view") .
                                '"><i class="fa fa-check-square-o"></i></a>';
                        }

                        if ($this->data["siteinfos"]->school_year ==
                $this->session->userdata("defaultschoolyearID") ||
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("usertypeID") == 5) {
                            if ($this->lang->line("Cash") ==
    $paymentlist["paymenttype"] ||
                $this->lang->line("Cheque") ==
    $paymentlist["paymenttype"] ||
                "Cash" == $paymentlist["paymenttype"] ||
                "Cheque" == $paymentlist["paymenttype"]) {
                                if (permissionChecker("invoice_delete")) {
                                    echo '<a href="' .
                                        base_url("invoice/deleteinvoicepaid/" .
                $paymentlist["globalpaymentID"                    ] .
                                "/" .
                $maininvoiceID
                    ) .
                                        '" onclick="return confirm(' .
                        "'" .
                        "you are about to delete a record. This cannot be undone. are you sure?" .
                        "'" .
                                        ')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="' .
        $this->lang->line("delete") .
                                        '"><i class="fa fa-trash-o"></i></a>';
                                }
                            }
                        }
                        echo "</td>";
                        echo "</tr>";
        $i++;
                    }
                }
            }
        }
    }

    public function deleteinvoicepaid()
    {
        if (
            $this->data["siteinfos"]->school_year ==
                $this->session->userdata("defaultschoolyearID") ||
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("usertypeID") == 5
) {
            $globalpaymentID = htmlentities(
                escapeString($this->uri->segment(3)));
            $maininvoiceID = htmlentities(escapeString($this->uri->segment(4)));
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            $paymentArray = [];
            $weaverandfineArray = [];
            if (permissionChecker("invoice_delete")) {
                if ((int) $globalpaymentID && (int) $maininvoiceID) {
    $globalpayment = $this->globalpayment_m->get_single_globalpayment(
                        ["globalpaymentID" => $globalpaymentID]);
                    if (customCompute($globalpayment)) {
        $payments = $this->payment_m->get_order_by_payment(["globalpaymentID" => $globalpaymentID,
                        ]);
        $weaverandfines = pluck($this->weaverandfine_m->get_order_by_weaverandfine(["globalpaymentID" => $globalpaymentID,]),
            "obj",
            "paymentID");

        $excType = true;
                        foreach ($payments as $payment) {
                            if ($this->lang->line("Cash") ==
    $payment->paymenttype ||
                $this->lang->line("Cheque") ==
    $payment->paymenttype ||
                "Cash" == $payment->paymenttype ||
                "Cheque" == $payment->paymenttype) {
                $paymentArray[] = $payment->paymentID;
                                if (isset($weaverandfines[$payment->paymentID])) {
    $weaverandfineArray[] =
        $weaverandfines[
            $payment->paymentID
                                        ]->weaverandfineID;
                                }
                            } else {
                $excType = false;
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
                                break;
                            }
                        }

                        if ($excType) {
            $this->payment_m->delete_batch_payment($paymentArray);
            $this->weaverandfine_m->delete_batch_weaverandfine($weaverandfineArray);
            $this->globalpayment_m->delete_globalpayment($globalpaymentID);

            $invoices = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $maininvoiceID,]);
            $invoicepluck = pluck($invoices, "invoiceID");

            $invoicesum = $this->invoice_m->get_invoice_sum(["maininvoiceID" => $maininvoiceID,]);
            $paymentsum = $this->payment_m->get_where_payment_sum("paymentamount",
                "invoiceID",
                $invoicepluck);
            $weaverandfinesum = $this->weaverandfine_m->get_where_weaverandfine_sum(["weaver", "fine"],
                "invoiceID",
                $invoicepluck);

            $maininvoiceArray = [];
                            if ($paymentsum->paymentamount +
    $weaverandfinesum->weaver ==
                                null) {
                $maininvoiceArray["maininvoicestatus"] = 0;
                            } elseif ((float) ($paymentsum->paymentamount +
    $weaverandfinesum->weaver) == (float) 0) {
                $maininvoiceArray["maininvoicestatus"] = 0;
                            } elseif ((float) $invoicesum->invoiceamount ==
                                (float) ($paymentsum->paymentamount +
    $weaverandfinesum->weaver)) {
                $maininvoiceArray["maininvoicestatus"] = 2;
                            } elseif ((float) ($paymentsum->paymentamount +
    $weaverandfinesum->weaver) > 0 &&
                                (float) $invoicesum->invoiceamount >
                                    (float) ($paymentsum->paymentamount +
        $weaverandfinesum->weaver)) {
                $maininvoiceArray["maininvoicestatus"] = 1;
                            } elseif ((float) ($paymentsum->paymentamount +
    $weaverandfinesum->weaver) > 0 &&
                                (float) $invoicesum->invoiceamount <
                                    (float) ($paymentsum->paymentamount +
        $weaverandfinesum->weaver)) {
                $maininvoiceArray["maininvoicestatus"] = 2;
                            }

            $payments = pluck($this->payment_m->get_where_payment_sum("paymentamount",
                    "invoiceID",$invoicepluck,
                    "invoiceID"),
                "obj",
                "invoiceID");
            $weaverandfines = pluck($this->weaverandfine_m->get_where_weaverandfine_sum(["weaver", "fine"],
                    "invoiceID",$invoicepluck,
                    "invoiceID"),
                "obj",
                "invoiceID");

            $invoiceArray = [];
                            if (customCompute($invoices)) {
                                foreach ($invoices as $invoice) {
    $paymentandweaver = 0;
    $paidstatus = 0;
                                    if (isset($payments[$invoice->invoiceID])) {
        $paymentandweaver +=
            $payments[$invoice->invoiceID]
                                                ->paymentamount;
                                    }

                                    if (isset($weaverandfines[$invoice->invoiceID]
                    )) {
        $paymentandweaver +=
            $weaverandfines[$invoice->invoiceID]
                                                ->weaver;
                                    }

                                    if ($paymentandweaver == null) {
        $paidstatus = 0;
                                    } elseif ((float) $paymentandweaver == (float) 0) {
        $paidstatus = 0;
                                    } elseif ((float) $invoice->amount ==
                                        (float) $paymentandweaver) {
        $paidstatus = 2;
                                    } elseif ((float) $paymentandweaver > 0 &&
                                        (float) $invoice->amount >
                                            (float) $paymentandweaver) {
        $paidstatus = 1;
                                    } elseif ((float) $paymentandweaver > 0 &&
                                        (float) $invoice->amount <
                                            (float) $paymentandweaver) {
        $paidstatus = 2;
                                    }

    $invoiceArray[] = ["paidstatus" => $paidstatus,
                        "invoiceID" => $invoice->invoiceID,];
                                }
                            }

                            if (customCompute($invoiceArray)) {
                $this->invoice_m->update_batch_invoice($invoiceArray,
                    "invoiceID");
                            }
            $this->maininvoice_m->update_maininvoice($maininvoiceArray,
                $maininvoiceID);

                            redirect(base_url("invoice/index"));
                        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
                        }
                    } else {
        $this->data["subview"] = "error";
        $this->load->view("_layout_main", $this->data);
                    }
                } else {
    $this->data["subview"] = "error";
    $this->load->view("_layout_main", $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view("_layout_main", $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view("_layout_main", $this->data);
        }
    }

    /* Paypal payment start*/
    private function Paypal()
    {
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $this->data["set_key"] = $api_config;
        if (
            $api_config["paypal_api_username"] == "" ||
            $api_config["paypal_api_password"] == "" ||
            $api_config["paypal_api_signature"] == ""
) {
            $this->session->set_flashdata(
"error",
"PayPal settings not available");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->item_data = $this->post_data;
            $this->invoice_info = (array) $this->invoice_data;

            (float) $this->item_data["amount"];
            $params = ["cancelUrl" => base_url("invoice/getCancelPayment"),
"returnUrl" => base_url("invoice/getSuccessPayment"),
"weaverUrl" => base_url("invoice/getSuccessWeaverPayment"),
"invoice_id" => $this->item_data["id"],
"name" => $this->invoice_info["srname"],
"description" => $this->invoice_info["feetype"],
"amount" => floatval(
    $this->item_data["amount"] + $this->item_data["fine"]),
"currency" => $this->data["siteinfos"]->currency_code,
"allpost" => $this->item_data,
            ];

            $this->session->set_userdata("params", $params);

            if (
                (float) ($this->item_data["amount"] +
    $this->item_data["fine"]) == 0) {
                redirect($params["weaverUrl"]);
            } else {
                $paypalMode =
    $api_config["paypal_demo"] === "TRUE"? (bool) true
                        : (bool) false;
                $gateway = Omnipay::create("PayPal_Express");
                $gateway->setUsername($api_config["paypal_api_username"]);
                $gateway->setPassword($api_config["paypal_api_password"]);
                $gateway->setSignature($api_config["paypal_api_signature"]);
                $gateway->setTestMode($paypalMode);
                $response = $gateway->purchase($params)->send();
                if ($response->isSuccessful()) {
                    // payment was successful: update database
                } elseif ($response->isRedirect()) {
    $response->redirect();
                } else {
                    // payment failed: display message to customer
                    echo $response->getMessage();
                }
            }
        }
    }

    public function getCancelPayment()
    {
        $params = $this->session->userdata("params");
        redirect(base_url("invoice/view/" . $params["invoice_id"]));
    }

    public function getSuccessPayment()
    {
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $this->data["set_key"] = $api_config;
        $gateway = Omnipay::create("PayPal_Express");
        $gateway->setUsername($api_config["paypal_api_username"]);
        $gateway->setPassword($api_config["paypal_api_password"]);
        $gateway->setSignature($api_config["paypal_api_signature"]);

        $gateway->setTestMode($api_config["paypal_demo"]);

        $params = $this->session->userdata("params");
        $response = $gateway->completePurchase($params)->send();
        $paypalResponse = $response->getData(); // this is the raw response object
        $purchaseId = $_GET["PayerID"];
        if (
            isset($paypalResponse["PAYMENTINFO_0_ACK"]) &&
            $paypalResponse["PAYMENTINFO_0_ACK"] === "Success"
) {
            if ($purchaseId) {
                $paypalTransactionID =
    $paypalResponse["PAYMENTINFO_0_TRANSACTIONID"];
                $dbTransactionID = $this->payment_m->get_single_payment(["transactionID" => $paypalTransactionID,]);
                if (!customCompute($dbTransactionID)) {
    $params = $this->session->userdata("params");
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);

    $maininvoice = $this->maininvoice_m->get_single_maininvoice(
                        ["maininvoiceID" => $params["invoice_id"]]);
                    if (customCompute($maininvoice)) {
        $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>    $maininvoice->maininvoicestudentID,
                "srschoolyearID" => $schoolyearID,]);
                        if (customCompute($this->data["student"])) {
            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $params["invoice_id"],
                "deleted_at" => 1,]);

            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");

            $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,
                $schoolyearID,
                $this->data["student"]->srstudentID);

                            if (customCompute($this->data["invoices"])) {
                $invoicepaymentandweaver =
    $this->data["invoicepaymentandweaver"];
                $globalpayment = ["classesID" =>    $this->data["student"]->srclassesID,
                    "sectionID" =>    $this->data["student"]->srsectionID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,
                    "clearancetype" => "partial",
                    "invoicename" =>    $this->data["student"]->srregisterNO .
                        "-" .
        $this->data["student"]->srname,
                    "invoicedescription" => "",
                    "paymentyear" => date("Y"),
                    "schoolyearID" => $schoolyearID,];

                $this->globalpayment_m->insert_globalpayment($globalpayment);
                $globalLastID = $this->db->insert_id();
                $due = 0;
                $paidstatus = 0;
                $globalstatus = [];
                                foreach ($this->data["invoices"]
                                    as $key => $invoice) {
                                    if ($invoice->paidstatus != 2) {
                                        if (isset($invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) $invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID];

                                            if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalpayment"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalweaver"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                            }
                                        }

        $totalPayment = 0;
                                        if (isset($params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ];
                                        }

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ];
                                        }

        $due = number_format($due, 2, ".", "");
        $totalPayment = number_format($totalPayment,        2,".","");

        $paidstatus = 0;
                                        if ($due <= $totalPayment) {
            $globalstatus[] = true;
            $paidstatus = 2;
                                        } else {
            $globalstatus[] = false;
            $paidstatus = 1;
                                        }

        $paymentArray = [
                            "invoiceID" => $invoice->invoiceID,"schoolyearID" => $schoolyearID,"studentID" => $invoice->studentID,"paymentamount" =>            $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ] == ""                        ? null
                                                    : $params["allpost"]["paidamount_" .
                            $invoice->invoiceID],"paymenttype" => ucfirst($params["allpost"]["paymentmethodID"                    ]),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"userID" => $this->session->userdata("loginuserID"),"usertypeID" => $this->session->userdata("usertypeID"),"uname" => $this->session->userdata("name"),"transactionID" => $paypalTransactionID,"globalpaymentID" => $globalLastID,    ];

        $this->payment_m->insert_payment($paymentArray
                    );
        $paymentLastID = $this->db->insert_id();
        $this->invoice_m->update_invoice(["paidstatus" => $paidstatus],        $invoice->invoiceID
                    );

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
                                            isset($params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ])
                    ) {
                                            if ((float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ] > (float) 0 ||
                                                (float) $params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ] > (float) 0) {
                $weaverandfineArray = ["globalpaymentID" => $globalLastID,"invoiceID" =>    $invoice->invoiceID,"paymentID" => $paymentLastID,"studentID" =>    $invoice->studentID,"schoolyearID" => $schoolyearID,"weaver" =>    $params["allpost"]["weaver_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["weaver_" .
                                    $invoice->invoiceID
                                                            ],"fine" =>    $params["allpost"]["fine_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["fine_" .
                                    $invoice->invoiceID
                                                            ],            ];
                $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                            );
                                            }
                                        }
                                    }
                                }

                                if (in_array(false, $globalstatus)) {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "partial"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],    $params["invoice_id"]);
                                } else {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],    $params["invoice_id"]);
                                }

                $this->session->set_flashdata("success",
                    "Payment successful!");
                            }
                        } else {
            $this->session->set_flashdata("error",
                "Student does not found.");
                            redirect(base_url("invoice/view/" . $params["invoice_id"]));
                        }
                    } else {
        $this->session->set_flashdata("error",
            "Invalid invoice");
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    }
                } else {
    $this->session->set_flashdata(
        "error",
        "Transaction ID already exist!"
);
                    redirect(base_url("invoice/view/" . $params["invoice_id"]));
                }
            } else {
                $this->session->set_flashdata("error", "Payer id not found!");
            }
            redirect(base_url("invoice/view/" . $params["invoice_id"]));
        } else {
            $this->session->set_flashdata("error", "Payment not success!");
            redirect(base_url("invoice/view/" . $params["invoice_id"]));
        }
    }

    public function getSuccessWeaverPayment()
    {
        if (
            $this->session->userdata("usertypeID") == 1 ||
            $this->session->userdata("usertypeID") == 5
) {
            $params = $this->session->userdata("params");
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            if (isset($params["invoice_id"])) {
                $maininvoice = $this->maininvoice_m->get_single_maininvoice(["maininvoiceID" => $params["invoice_id"],]);
                if (customCompute($maininvoice)) {
    $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" => $maininvoice->maininvoicestudentID,
        "srschoolyearID" => $schoolyearID,
                    ]);
                    if (customCompute($this->data["student"])) {
        $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $params["invoice_id"],
            "deleted_at" => 1,
                        ]);

        $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
            "feetypes",
            "feetypesID");
        $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,
            $schoolyearID,
            $this->data["student"]->srstudentID);

                        if (customCompute($this->data["invoices"])) {
            $invoicepaymentandweaver =
                $this->data["invoicepaymentandweaver"];
            $globalpayment = ["classesID" =>    $this->data["student"]->srclassesID,
                "sectionID" =>    $this->data["student"]->srsectionID,
                "studentID" =>    $maininvoice->maininvoicestudentID,
                "clearancetype" => "partial",
                "invoicename" =>    $this->data["student"]->srregisterNO .
                    "-" .
    $this->data["student"]->srname,
                "invoicedescription" => "",
                "paymentyear" => date("Y"),
                "schoolyearID" => $schoolyearID,];

            $this->globalpayment_m->insert_globalpayment($globalpayment);
            $globalLastID = $this->db->insert_id();
            $due = 0;
            $paidstatus = 0;
            $globalstatus = [];
                            foreach ($this->data["invoices"]
                                as $key => $invoice) {
                                if ($invoice->paidstatus != 2) {
                                    if (isset($invoicepaymentandweaver[
                                "totalamount"][$invoice->invoiceID]
                    )) {
        $due =
                                            (float) $invoicepaymentandweaver[
                                "totalamount"][$invoice->invoiceID];

                                        if (isset($invoicepaymentandweaver["totaldiscount"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) ($due -
                    $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                        }

                                        if (isset($invoicepaymentandweaver["totalpayment"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) ($due -
                    $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                        }

                                        if (isset($invoicepaymentandweaver["totalweaver"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) ($due -
                    $invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                        }
                                    }

    $totalPayment = 0;
                                    if (isset($params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ]
                    ) &&
        $params["allpost"][
                            "paidamount_" . $invoice->invoiceID
                                        ] > 0) {
        $totalPayment +=
                                            (float) $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ];
                                    }

                                    if (isset($params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ]
                    ) &&
        $params["allpost"][
                            "weaver_" . $invoice->invoiceID
                                        ] > 0) {
        $totalPayment +=
                                            (float) $params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ];
                                    }

    $due = number_format($due, 2, ".", "");
    $totalPayment = number_format($totalPayment,    2,
                        ".",
                        "");

    $paidstatus = 0;
                                    if ($due <= $totalPayment) {
        $globalstatus[] = true;
        $paidstatus = 2;
                                    } else {
        $globalstatus[] = false;
        $paidstatus = 1;
                                    }

    $paymentArray = ["invoiceID" => $invoice->invoiceID,
                        "schoolyearID" => $schoolyearID,
                        "studentID" => $invoice->studentID,
                        "paymentamount" =>        $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ] == ""                    ? null
                                                : $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ],
                        "paymenttype" => ucfirst($params["allpost"][
                                "paymentmethodID"]
                    ),
                        "paymentdate" => date("Y-m-d"),
                        "paymentday" => date("d"),
                        "paymentmonth" => date("m"),
                        "paymentyear" => date("Y"),
                        "userID" => $this->session->userdata("loginuserID"),
                        "usertypeID" => $this->session->userdata("usertypeID"),
                        "uname" => $this->session->userdata("name"),
                        "transactionID" =>        "PaypalWeaver" . random19(),
                        "globalpaymentID" => $globalLastID,];

    $this->payment_m->insert_payment($paymentArray);
    $paymentLastID = $this->db->insert_id();
    $this->invoice_m->update_invoice(["paidstatus" => $paidstatus],    $invoice->invoiceID);

                                    if ((float) $params["allpost"][
                            "weaver_" . $invoice->invoiceID
                                        ] > (float) 0 ||
                                        (float) $params["allpost"][
                            "fine_" . $invoice->invoiceID
                                        ] > (float) 0) {
        $weaverandfineArray = [
                            "globalpaymentID" => $globalLastID,"invoiceID" => $invoice->invoiceID,"paymentID" => $paymentLastID,"studentID" => $invoice->studentID,"schoolyearID" => $schoolyearID,"weaver" =>            $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ] == ""                        ? 0
                                                    : $params["allpost"]["weaver_" .
                            $invoice->invoiceID],"fine" =>            $params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ] == ""                        ? 0
                                                    : $params["allpost"]["fine_" .
                            $invoice->invoiceID],    ];
        $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                    );
                                    }
                                }
                            }

                            if (in_array(false, $globalstatus)) {
                $this->globalpayment_m->update_globalpayment(["clearancetype" => "partial"],$globalLastID);
                $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],$params["invoice_id"]);
                            } else {
                $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],$globalLastID);
                $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],$params["invoice_id"]);
                            }

            $this->session->set_flashdata("success",
                "Payment successful!");
                            redirect(base_url("invoice/view/" . $params["invoice_id"]));
                        }
                    } else {
        $this->session->set_flashdata("error",
            "Student does not found.");
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    }
                } else {
    $this->session->set_flashdata("error", "Invalid invoice");
                    redirect(base_url("invoice/view/" . $params["invoice_id"]));
                }
            } else {
                $this->session->set_flashdata("error", "Invalid invoice data");
                redirect(base_url("invoice/index"));
            }
        } else {
            $this->session->set_flashdata("error", "Invalid user data");
            redirect(base_url("invoice/index"));
        }
    }
    /* Paypal payment end*/

    /* Stripe Payment Start*/
    protected function stripe_rules()
    {
        $rules = [
            ["field" => "card_number",
"label" => $this->lang->line("card_number"),
"rules" =>"trim|required|xss_clean|numeric|min_length[16]|max_length[16]",
            ],
            ["field" => "cvv",
"label" => $this->lang->line("cvv"),
"rules" =>"trim|required|xss_clean|numeric|min_length[3]|max_length[3]",
            ],
            ["field" => "expire_month",
"label" => $this->lang->line("expire_month"),
"rules" =>"trim|required|xss_clean|numeric|min_length[2]|max_length[2]",
            ],
            ["field" => "expire_year",
"label" => $this->lang->line("expire_year"),
"rules" =>"trim|required|xss_clean|numeric|min_length[4]|max_length[4]",
            ],
        ];
        return $rules;
    }

    public function stripe()
    {
        $weaverURL = base_url("invoice/getSuccessWeaverPayment");
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $this->data["set_key"] = $api_config;
        if ($api_config["stripe_secret"] == "") {
            $this->session->set_flashdata(
"error",
"Stripe settings not available");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->item_data = $this->post_data;
            $this->invoice_info = $this->invoice_data;

            $params = ["weaverUrl" => base_url("invoice/getSuccessWeaverPayment"),
"invoice_id" => $this->item_data["id"],
"name" => $this->invoice_info->srname,
"description" => $this->invoice_info->feetype,
"amount" => floatval(
    $this->item_data["amount"] + $this->item_data["fine"]),
"currency" => $this->data["siteinfos"]->currency_code,
"allpost" => $this->item_data,
            ];
            $this->session->set_userdata("params", $params);

            if (
                (float) ($this->item_data["amount"] +
    $this->item_data["fine"]) == 0) {
                redirect($weaverURL);
            } else {
                try {
    $gateway = Omnipay::create("Stripe");
    $gateway->setApiKey($api_config["stripe_secret"]);
    $gateway->setTestMode($api_config["stripe_demo"]);

    $formData = ["number" => $this->item_data["card_number"],
        "expiryMonth" => $this->item_data["expire_month"],
        "expiryYear" => $this->item_data["expire_year"],
        "cvv" => $this->item_data["cvv"],
                    ];
    $paid_amount = number_format(
                        (float) ($this->item_data["amount"] +
            $this->item_data["fine"]),
                        2,
        ".",
        ""
);

    $response = $gateway->purchase(["amount" => $paid_amount,
            "invoice" => $this->item_data["id"],
            "currency" =>$this->data["siteinfos"]->currency_code,
            "card" => $formData,
                        ])->send();

                    if ($response->isSuccessful()) {
                        // payment was successful: updateabase
                        if ($response->getData()["status"] === "succeeded") {
            $dbTransactionID = $this->payment_m->get_single_payment(["transactionID" => $response->getData()["id"]]);
                            if (!customCompute($dbTransactionID)) {
                $schoolyearID = $this->session->userdata("defaultschoolyearID");

                                if (customCompute($this->invoice_info)) {
    $this->data["student"] = $this->studentrelation_m->get_single_studentrelation([
                            "srstudentID" =>            $this->invoice_info
                                                    ->maininvoicestudentID,"srschoolyearID" => $schoolyearID,    ]);
                                    if (customCompute($this->data["student"])) {
        $this->data[
                            "invoices"            ] = $this->invoice_m->get_order_by_invoice([
                                "maininvoiceID" =>$this->item_data["id"],    "deleted_at" => 1,        ]
                    );
        $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),"feetypes","feetypesID");

        $this->data[
                            "invoicepaymentandweaver"            ] = $this->paymentdue($this->invoice_info,        $schoolyearID,        $this->data["student"]->srstudentID
                    );

                                        if (customCompute($this->data["invoices"])
                    ) {
            $invoicepaymentandweaver =
                $this->data["invoicepaymentandweaver"                    ];
            $globalpayment = [
                                "classesID" =>$this->data["student"]
                                                        ->srclassesID,    "sectionID" =>$this->data["student"]
                                                        ->srsectionID,    "studentID" =>$this->invoice_info
                                                        ->maininvoicestudentID,    "clearancetype" => "partial",    "invoicename" =>$this->data["student"]
                                                        ->srregisterNO .
                                    "-" .
                    $this->data["student"]
                                                        ->srname,    "invoicedescription" => "",    "paymentyear" => date("Y"),    "schoolyearID" => $schoolyearID,        ];

            $this->globalpayment_m->insert_globalpayment($globalpayment);
            $globalLastID = $this->db->insert_id();
            $due = 0;
            $paidstatus = 0;
            $globalstatus = [];
                                            foreach ($this->data["invoices"]
                                                as $key => $invoice) {
                                                if ($invoice->paidstatus != 2) {
                                                    if (isset($invoicepaymentandweaver["totalamount"][$invoice
                                                                    ->invoiceID
                                                            ]
                                    )
                                ) {
                        $due =    (float) $invoicepaymentandweaver["totalamount"][$invoice
                                                                    ->invoiceID
                                                            ];

                                                        if (isset($invoicepaymentandweaver["totaldiscount"][$invoice
                                                                        ->invoiceID
                                                                ]
                                        )
                                    ) {
                            $due =        (float) ($due -
                                    $invoicepaymentandweaver["totaldiscount"][$invoice
                                                                            ->invoiceID
                                                                    ]);
                                                        }

                                                        if (isset($invoicepaymentandweaver["totalpayment"][$invoice
                                                                        ->invoiceID
                                                                ]
                                        )
                                    ) {
                            $due =        (float) ($due -
                                    $invoicepaymentandweaver["totalpayment"][$invoice
                                                                            ->invoiceID
                                                                    ]);
                                                        }

                                                        if (isset($invoicepaymentandweaver["totalweaver"][$invoice
                                                                        ->invoiceID
                                                                ]
                                        )
                                    ) {
                            $due =        (float) ($due -
                                    $invoicepaymentandweaver["totalweaver"][$invoice
                                                                            ->invoiceID
                                                                    ]);
                                                        }
                                                    }

                    $totalPayment = 0;
                                                    if (isset($this->item_data["paidamount_" .
                                    $invoice->invoiceID
                                                            ]
                                    ) &&
                        $this->item_data["paidamount_" .
                                $invoice->invoiceID
                                                        ] > 0
                                ) {
                        $totalPayment +=    (float) $this
                                                                ->item_data["paidamount_" .
                                    $invoice->invoiceID
                                                            ];
                                                    }

                                                    if (isset($this->item_data["weaver_" .
                                    $invoice->invoiceID
                                                            ]
                                    ) &&
                        $this->item_data["weaver_" .
                                $invoice->invoiceID
                                                        ] > 0
                                ) {
                        $totalPayment +=    (float) $this
                                                                ->item_data["weaver_" .
                                    $invoice->invoiceID
                                                            ];
                                                    }

                    $due = number_format($due,                    2,    ".",    ""    );
                    $totalPayment = number_format($totalPayment,                    2,    ".",    ""    );

                    $paidstatus = 0;
                                                    if ($due <= $totalPayment) {
                        $globalstatus[] = true;
                        $paidstatus = 2;
                                                    } else {
                        $globalstatus[] = false;
                        $paidstatus = 1;
                                                    }

                    $paymentArray = ["invoiceID" =>        $invoice->invoiceID,    "schoolyearID" => $schoolyearID,    "studentID" =>        $invoice->studentID,    "paymentamount" =>        $this->item_data["paidamount_" .
                                    $invoice->invoiceID
                                                            ] == ""? null
                                                                : $this
                                                                    ->item_data["paidamount_" .
                                        $invoice->invoiceID
                                                                ],    "paymenttype" => ucfirst($this->item_data["paymentmethodID"]
                                    ),    "paymentdate" => date("Y-m-d"        ),    "paymentday" => date("d"        ),    "paymentmonth" => date("m"        ),    "paymentyear" => date("Y"        ),    "userID" => $this->session->userdata("loginuserID"        ),    "usertypeID" => $this->session->userdata("usertypeID"        ),    "uname" => $this->session->userdata("name"        ),    "transactionID" => $response->getData()["id"],    "globalpaymentID" => $globalLastID,];

                    $this->payment_m->insert_payment($paymentArray
                                );
                    $paymentLastID = $this->db->insert_id();
                    $this->invoice_m->update_invoice(["paidstatus" => $paidstatus,                    ],    $invoice->invoiceID
                                );

                                                    if (isset($this->item_data["weaver_" .
                                    $invoice->invoiceID
                                                            ]
                                    ) &&
                                                        isset($this->item_data["fine_" .
                                    $invoice->invoiceID
                                                            ]
                                    )
                                ) {
                                                        if ((float) $this
                                                                ->item_data["weaver_" .
                                    $invoice->invoiceID
                                                            ] > (float) 0 ||
                                                            (float) $this
                                                                ->item_data["fine_" .
                                    $invoice->invoiceID
                                                            ] > (float) 0
                                    ) {
                            $weaverandfineArray = ["globalpaymentID" => $globalLastID,            "invoiceID" =>    $invoice->invoiceID,            "paymentID" => $paymentLastID,            "studentID" =>    $invoice->studentID,            "schoolyearID" => $schoolyearID,            "weaver" =>    $this
                                                                        ->item_data["weaver_" .
                                            $invoice->invoiceID
                                                                    ] == ""    ? 0
                                                                        : $this
                                                                            ->item_data["weaver_" .
                                                $invoice->invoiceID
                                                                        ],            "fine" =>    $this
                                                                        ->item_data["fine_" .
                                            $invoice->invoiceID
                                                                    ] == ""    ? 0
                                                                        : $this
                                                                            ->item_data["fine_" .
                                                $invoice->invoiceID
                                                                        ],                        ];
                            $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                                        );
                                                        }
                                                    }
                                                }
                                            }

                                            if (in_array(false, $globalstatus)) {
                $this->globalpayment_m->update_globalpayment(["clearancetype" =>                        "partial",],                $globalLastID
                            );
                $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],                $this->invoice_info
                                                        ->maininvoiceID
                            );
                                            } else {
                $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],                $globalLastID
                            );
                $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],                $this->invoice_info
                                                        ->maininvoiceID
                            );
                                            }

            $this->session->set_flashdata("success",    "Payment successful!");
                                        }
                                    } else {
        $this->session->set_flashdata("error","Student does not found.");
                                        redirect(base_url("invoice/view/" .
                    $this->item_data["id"])
                    );
                                    }
                                } else {
    $this->session->set_flashdata("error",
                        "Invalid invoice");
                                    redirect(base_url("invoice/view/" .
                $this->item_data["id"]
                    ));
                                }
                            } else {
                $this->session->set_flashdata("error",
                    "Transaction ID already exist!");
                                redirect(base_url("invoice/view/" . $this->item_data["id"]));
                            }
                        }
                        redirect(base_url("invoice/view/" . $this->item_data["id"]));
                    } elseif ($response->isRedirect()) {
                        // redirect to offsite payment gateway
        $response->redirect();
                    } else {
                        // payment failed: display message to customer
        $this->session->set_flashdata("error",
            "Something went wrong!");
                        redirect(base_url("invoice/payment/" . $this->item_data["id"]));
                    }
                } catch (\Exception $ex) {
    $this->session->set_flashdata("error", $ex->getMessage());
                    redirect(
                        base_url("invoice/payment/" . $this->item_data["id"])
);
                }
            }

            redirect(base_url("invoice/view/" . $this->item_data["id"]));
        }
    }
    /* stripe Payment End*/

    /* PayUmoney Payment*/
    protected function payumoney_rules()
    {
        $rules = [
            ["field" => "first_name",
"label" => $this->lang->line("first_name"),
"rules" => "trim|required|xss_clean",
            ],
            ["field" => "email",
"label" => $this->lang->line("email"),
"rules" => "trim|required|xss_clean",
            ],
            ["field" => "phone",
"label" => $this->lang->line("phone"),
"rules" => "trim|required|xss_clean",
            ],
        ];
        return $rules;
    }

    public function payumoney()
    {
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $this->data["set_key"] = $api_config;
        if (
            $api_config["payumoney_key"] == "" ||
            $api_config["payumoney_salt"] == ""
) {
            $this->session->set_flashdata(
"error",
"PayUMoney settings not available");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->item_data = $this->post_data;
            $this->invoice_info = (array) $this->invoice_data;
            $params = ["cancelUrl" => base_url("invoice/payumoney_canceled"),
"failedUrl" => base_url("invoice/payumoney_failed"),
"returnUrl" => base_url("invoice/payumoney_successful"),
"weaverUrl" => base_url("invoice/getSuccessWeaverPayment"),
"invoice_id" => $this->item_data["id"],
"name" => $this->invoice_info["srname"],
"description" => $this->invoice_info["feetype"],
"amount" => floatval(
    $this->item_data["amount"] + $this->item_data["fine"]),
"currency" => $this->data["siteinfos"]->currency_code,
"allpost" => $this->item_data,
            ];

            $this->session->set_userdata("params", $params);
            if (
                (float) ($this->item_data["amount"] +
    $this->item_data["fine"]) == 0) {
                redirect($params["weaverUrl"]);
            } else {
                if ($api_config["payumoney_demo"] == true) {
    $api_link = "https://sandboxsecure.payu.in/_payment";
                } else {
    $api_link = "https://secure.payu.in/_payment";
                }
                $this->array["invoice"] = $this->invoice_info;
                $this->array["key"] = $api_config["payumoney_key"];
                $this->array["salt"] = $api_config["payumoney_salt"];
                $this->array["payu_base_url"] = $api_link; // For Test environment
                $this->array["surl"] = base_url(
    "invoice/payumoney_success/" . $this->item_data["id"]);
                $this->array["furl"] = base_url(
    "invoice/payumoney_failed/" . $this->item_data["id"]);
                $this->array["txnid"] = substr(
                    hash("sha256", mt_rand() . microtime()),
                    0,
                    20);
                $this->array["action"] = $api_link;
                $this->array["amount"] = number_format(
    $this->item_data["amount"] + $this->item_data["fine"],
                    2,
    ".",
    "");
                $this->array["firstname"] = $this->item_data["first_name"];
                $this->array["email"] = $this->item_data["email"];
                $this->array["phone"] = $this->item_data["phone"];
                $this->array["productinfo"] = $this->invoice_info["feetype"];
                $this->array["curl"] = base_url(
    "invoice/payumoney_canceled/" . $this->item_data["id"]);
                $this->array["service_provider"] = "payu_paisa";
                $this->array["hash"] = $this->generateHash($this->array);

                $this->load->view("invoice/payumoney", $this->array);
            }
        }
    }

    public function generateHash($array)
    {
        $hashSequence =
"key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if (
            empty($array["key"]) ||
            empty($array["txnid"]) ||
            empty($array["amount"]) ||
            empty($array["firstname"]) ||
            empty($array["email"]) ||
            empty($array["phone"]) ||
            empty($array["productinfo"]) ||
            empty($array["surl"]) ||
            empty($array["furl"]) ||
            empty($array["service_provider"])
) {
            return false;
        } else {
            $hash = "";
            $salt = $array["salt"];
            $hashVarsSeq = explode("|", $hashSequence);
            $hash_string = "";
            foreach ($hashVarsSeq as $hash_var) {
                $hash_string .= isset($array[$hash_var])
                    ? $array[$hash_var]
                    : "";
                $hash_string .= "|";
            }
            $hash_string .= $salt;
            $hash = strtolower(hash("sha512", $hash_string));
            return $hash;
        }
    }

    public function payumoney_failed()
    {
        $invoice = $this->uri->segment(3);
        $this->session->set_flashdata("error", "Payment failed!");
        redirect(base_url("invoice/view/" . $invoice));
    }

    public function payumoney_success()
    {
        $invoice = $this->uri->segment(3);
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $status = $_POST["status"];
        $firstname = $_POST["firstname"];
        $amount = $_POST["amount"];
        $txnid = $_POST["txnid"];
        $posted_hash = $_POST["hash"];
        $key = $_POST["key"];
        $productinfo = $_POST["productinfo"];
        $email = $_POST["email"];
        $salt = $api_config["payumoney_salt"];

        if (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq =
                $additionalCharges .
"|" .
                $salt .
"|" .
                $status .
"|||||||||||" .
                $email .
"|" .
                $firstname .
"|" .
                $productinfo .
"|" .
                $amount .
"|" .
                $txnid .
"|" .
                $key;
        } else {
            $retHashSeq =
                $salt .
"|" .
                $status .
"|||||||||||" .
                $email .
"|" .
                $firstname .
"|" .
                $productinfo .
"|" .
                $amount .
"|" .
                $txnid .
"|" .
                $key;
        }

        $hash = strtolower(hash("sha512", $retHashSeq));
        if ($hash != $posted_hash) {
            $this->session->set_flashdata(
"error",
"Invalid Transaction. Please try again");
            redirect(base_url("invoice/view/" . $invoice));
        } else {
            if ($status === "success") {
                $params = $this->session->userdata("params");
                $dbTransactionID = $this->payment_m->get_single_payment(["transactionID" => $txnid,]);
                if (!customCompute($dbTransactionID)) {
    $params = $this->session->userdata("params");
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);

    $maininvoice = $this->maininvoice_m->get_single_maininvoice(
                        ["maininvoiceID" => $params["invoice_id"]]);
                    if (customCompute($maininvoice)) {
        $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>    $maininvoice->maininvoicestudentID,
                "srschoolyearID" => $schoolyearID,]);
                        if (customCompute($this->data["student"])) {
            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $params["invoice_id"],
                "deleted_at" => 1,]);

            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");

            $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,
                $schoolyearID,
                $this->data["student"]->srstudentID);

                            if (customCompute($this->data["invoices"])) {
                $invoicepaymentandweaver =
    $this->data["invoicepaymentandweaver"];
                $globalpayment = ["classesID" =>    $this->data["student"]->srclassesID,
                    "sectionID" =>    $this->data["student"]->srsectionID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,
                    "clearancetype" => "partial",
                    "invoicename" =>    $this->data["student"]->srregisterNO .
                        "-" .
        $this->data["student"]->srname,
                    "invoicedescription" => "",
                    "paymentyear" => date("Y"),
                    "schoolyearID" => $schoolyearID,];
                $this->globalpayment_m->insert_globalpayment($globalpayment);
                $globalLastID = $this->db->insert_id();
                $due = 0;
                $paidstatus = 0;
                $globalstatus = [];
                                foreach ($this->data["invoices"]
                                    as $key => $invoice) {
                                    if ($invoice->paidstatus != 2) {
                                        if (isset($invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) $invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID];

                                            if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalpayment"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalweaver"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                            }
                                        }

        $totalPayment = 0;
                                        if (isset($params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ];
                                        }

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ];
                                        }

        $due = number_format($due, 2, ".", "");
        $totalPayment = number_format($totalPayment,        2,".","");

        $paidstatus = 0;
                                        if ($due <= $totalPayment) {
            $globalstatus[] = true;
            $paidstatus = 2;
                                        } else {
            $globalstatus[] = false;
            $paidstatus = 1;
                                        }

        $paymentArray = [
                            "invoiceID" => $invoice->invoiceID,"schoolyearID" => $schoolyearID,"studentID" => $invoice->studentID,"paymentamount" =>            $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ] == ""                        ? null
                                                    : $params["allpost"]["paidamount_" .
                            $invoice->invoiceID],"paymenttype" => ucfirst($params["allpost"]["paymentmethodID"                    ]),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"userID" => $this->session->userdata("loginuserID"),"usertypeID" => $this->session->userdata("usertypeID"),"uname" => $this->session->userdata("name"),"transactionID" => $txnid,"globalpaymentID" => $globalLastID,    ];

        $this->payment_m->insert_payment($paymentArray
                    );
        $paymentLastID = $this->db->insert_id();
        $this->invoice_m->update_invoice(["paidstatus" => $paidstatus],        $invoice->invoiceID
                    );

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
                                            isset($params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ])
                    ) {
                                            if ((float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ] > (float) 0 ||
                                                (float) $params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ] > (float) 0) {
                $weaverandfineArray = ["globalpaymentID" => $globalLastID,"invoiceID" =>    $invoice->invoiceID,"paymentID" => $paymentLastID,"studentID" =>    $invoice->studentID,"schoolyearID" => $schoolyearID,"weaver" =>    $params["allpost"]["weaver_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["weaver_" .
                                    $invoice->invoiceID
                                                            ],"fine" =>    $params["allpost"]["fine_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["fine_" .
                                    $invoice->invoiceID
                                                            ],            ];
                $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                            );
                                            }
                                        }
                                    }
                                }

                                if (in_array(false, $globalstatus)) {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "partial"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],    $params["invoice_id"]);
                                } else {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],    $params["invoice_id"]);
                                }

                $this->session->set_flashdata("success",
                    "Payment successful!");
                            }
                        } else {
            $this->session->set_flashdata("error",
                "Student does not found.");
                            redirect(base_url("invoice/view/" . $params["invoice_id"]));
                        }
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    } else {
        $this->session->set_flashdata("error",
            "Invalid invoice");
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    }
                } else {
    $this->session->set_flashdata(
        "error",
        "Transaction ID already exist!"
);
                    redirect(base_url("invoice/view/" . $params["invoice_id"]));
                }
            } else {
                redirect(base_url("invoice/view/" . $invoice));
            }
        }
    }
    /* PayUMoney Payment End*/

    /* VoguePay Start*/
    public function voguepay()
    {
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        $this->data["set_key"] = $api_config;
        if (
            $api_config["voguepay_merchant_id"] == "" ||
            $api_config["voguepay_merchant_ref"] == "" ||
            $api_config["voguepay_developer_code"] == "" ||
            $api_config["voguepay_status"] == 0
) {
            $this->session->set_flashdata(
"error",
"VoguePay configuration is missing!");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->item_data = $this->post_data;
            $this->invoice_info = (array) $this->invoice_data;
            $params = ["fail_url" => base_url(
    "invoice/voguepay_failed/" . $this->item_data["id"]),
"notify_url" => base_url(
    "invoice/voguepay_notify/" . $this->item_data["id"]),
"success_url" => base_url(
    "invoice/voguepay_success/" . $this->item_data["id"]),
"weaverUrl" => base_url("invoice/getSuccessWeaverPayment"),
"invoice_id" => $this->item_data["id"],
"name" => $this->invoice_info["srname"],
"description" => $this->invoice_info["feetype"],
"amount" => floatval(
    $this->item_data["amount"] + $this->item_data["fine"]),
"currency" => $this->data["siteinfos"]->currency_code,
"allpost" => $this->item_data,
            ];

            $this->session->set_userdata("params", $params);

            if (
                (float) ($this->item_data["amount"] +
    $this->item_data["fine"]) == 0) {
                redirect($params["weaverUrl"]);
            } else {
                $api_link = "https://voguepay.com/pay/";

                $this->array["invoice"] = $this->invoice_info;
                $this->array["v_merchant_id"] =
    $api_config["voguepay_merchant_id"];
                $this->array["success_url"] = base_url(
    "invoice/voguepay_success/" . $this->item_data["id"]);
                $this->array["notify_url"] = base_url(
    "invoice/voguepay_notify/" . $this->item_data["id"]);
                $this->array["fail_url"] = base_url(
    "invoice/voguepay_failed/" . $this->item_data["id"]);
                $this->array["action"] = $api_link;
                $this->array["total"] = number_format(
    $this->item_data["amount"] + $this->item_data["fine"],
                    2,
    ".",
    "");
                $this->array["merchant_ref"] =
    $api_config["voguepay_merchant_ref"];
                $this->array["developer_code"] =
    $api_config["voguepay_developer_code"];
                $this->array["store_id"] = rand(1, 9999999999);
                $this->array["memo"] = $this->item_data["id"];
                $this->array["cur"] = $this->data["siteinfos"]->currency_code;
                $this->load->view("invoice/voguepay", $this->array);
            }
        }
    }

    public function voguepay_success()
    {
        $invoiceID = $this->uri->segment(3);
        $txnid = $_POST["transaction_id"];
        if (isset($_POST["transaction_id"])) {
            $result = json_decode(
                $this->verifyVoguePayPayment($_POST["transaction_id"]));

            if ($result->state == "success") {
                $dbTransactionID = $this->payment_m->get_single_payment(["transactionID" => $txnid,]);
                if (!customCompute($dbTransactionID)) {
    $params = $this->session->userdata("params");
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);

    $maininvoice = $this->maininvoice_m->get_single_maininvoice(
                        ["maininvoiceID" => $params["invoice_id"]]);
                    if (customCompute($maininvoice)) {
        $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>    $maininvoice->maininvoicestudentID,
                "srschoolyearID" => $schoolyearID,]);
                        if (customCompute($this->data["student"])) {
            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $params["invoice_id"],
                "deleted_at" => 1,]);

            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");
            $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,
                $schoolyearID,
                $this->data["student"]->srstudentID);

                            if (customCompute($this->data["invoices"])) {
                $invoicepaymentandweaver =
    $this->data["invoicepaymentandweaver"];
                $globalpayment = ["classesID" =>    $this->data["student"]->srclassesID,
                    "sectionID" =>    $this->data["student"]->srsectionID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,
                    "clearancetype" => "partial",
                    "invoicename" =>    $this->data["student"]->srregisterNO .
                        "-" .
        $this->data["student"]->srname,
                    "invoicedescription" => "",
                    "paymentyear" => date("Y"),
                    "schoolyearID" => $schoolyearID,];
                $this->globalpayment_m->insert_globalpayment($globalpayment);
                $globalLastID = $this->db->insert_id();
                $due = 0;
                $paidstatus = 0;
                $globalstatus = [];
                                foreach ($this->data["invoices"]
                                    as $key => $invoice) {
                                    if ($invoice->paidstatus != 2) {
                                        if (isset($invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) $invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID];

                                            if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalpayment"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalweaver"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                            }
                                        }

        $totalPayment = 0;
                                        if (isset($params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ];
                                        }

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ];
                                        }

        $due = number_format($due, 2, ".", "");
        $totalPayment = number_format($totalPayment,        2,".","");

        $paidstatus = 0;
                                        if ($due <= $totalPayment) {
            $globalstatus[] = true;
            $paidstatus = 2;
                                        } else {
            $globalstatus[] = false;
            $paidstatus = 1;
                                        }

        $paymentArray = [
                            "invoiceID" => $invoice->invoiceID,"schoolyearID" => $schoolyearID,"studentID" => $invoice->studentID,"paymentamount" =>            $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ] == ""                        ? null
                                                    : $params["allpost"]["paidamount_" .
                            $invoice->invoiceID],"paymenttype" => ucfirst($params["allpost"]["paymentmethodID"                    ]),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"userID" => $this->session->userdata("loginuserID"),"usertypeID" => $this->session->userdata("usertypeID"),"uname" => $this->session->userdata("name"),"transactionID" => $txnid,"globalpaymentID" => $globalLastID,    ];

        $this->payment_m->insert_payment($paymentArray
                    );
        $paymentLastID = $this->db->insert_id();
        $this->invoice_m->update_invoice(["paidstatus" => $paidstatus],        $invoice->invoiceID
                    );

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
                                            isset($params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ])
                    ) {
                                            if ((float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ] > (float) 0 ||
                                                (float) $params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ] > (float) 0) {
                $weaverandfineArray = ["globalpaymentID" => $globalLastID,"invoiceID" =>    $invoice->invoiceID,"paymentID" => $paymentLastID,"studentID" =>    $invoice->studentID,"schoolyearID" => $schoolyearID,"weaver" =>    $params["allpost"]["weaver_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["weaver_" .
                                    $invoice->invoiceID
                                                            ],"fine" =>    $params["allpost"]["fine_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["fine_" .
                                    $invoice->invoiceID
                                                            ],            ];
                $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                            );
                                            }
                                        }
                                    }
                                }

                                if (in_array(false, $globalstatus)) {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "partial"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],    $params["invoice_id"]);
                                } else {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],    $params["invoice_id"]);
                                }

                $this->session->set_flashdata("success",
                    "Payment successful!");
                            }
                        } else {
            $this->session->set_flashdata("error",
                "Student does not found.");
                            redirect(base_url("invoice/view/" . $params["invoice_id"]));
                        }
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    } else {
        $this->session->set_flashdata("error",
            "Invalid invoice");
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    }
                } else {
    $this->session->set_flashdata(
        "error",
        "Transaction ID already exist!"
);
                    redirect(base_url("invoice/view/" . $invoiceID));
                }
            } else {
                redirect(base_url("invoice/view/" . $invoiceID));
            }
        } else {
            $this->session->set_flashdata(
"error",
"Invalid Transaction. Please try again");
            redirect(base_url("invoice/view/" . $invoiceID));
        }
    }

    public function voguepay_notify()
    {
        $invoiceID = $this->uri->segment(3);
        $txnid = $_POST["transaction_id"];
        if (isset($_POST["transaction_id"])) {
            $result = json_decode(
                $this->verifyVoguePayPayment($_POST["transaction_id"]));

            if ($result->state == "success") {
                $dbTransactionID = $this->payment_m->get_single_payment(["transactionID" => $txnid,]);
                if (!customCompute($dbTransactionID)) {
    $params = $this->session->userdata("params");
    $schoolyearID = $this->session->userdata(
        "defaultschoolyearID"
);

    $maininvoice = $this->maininvoice_m->get_single_maininvoice(
                        ["maininvoiceID" => $params["invoice_id"]]);
                    if (customCompute($maininvoice)) {
        $this->data["student"] = $this->studentrelation_m->get_single_studentrelation(["srstudentID" =>    $maininvoice->maininvoicestudentID,
                "srschoolyearID" => $schoolyearID,]);
                        if (customCompute($this->data["student"])) {
            $this->data["invoices"] = $this->invoice_m->get_order_by_invoice(["maininvoiceID" => $params["invoice_id"],
                "deleted_at" => 1,]);

            $this->data["feetypes"] = pluck($this->feetypes_m->get_feetypes(),
                "feetypes",
                "feetypesID");

            $this->data["invoicepaymentandweaver"] = $this->paymentdue($maininvoice,
                $schoolyearID,
                $this->data["student"]->srstudentID);

                            if (customCompute($this->data["invoices"])) {
                $invoicepaymentandweaver =
    $this->data["invoicepaymentandweaver"];
                $globalpayment = ["classesID" =>    $this->data["student"]->srclassesID,
                    "sectionID" =>    $this->data["student"]->srsectionID,
                    "studentID" =>    $maininvoice->maininvoicestudentID,
                    "clearancetype" => "partial",
                    "invoicename" =>    $this->data["student"]->srregisterNO .
                        "-" .
        $this->data["student"]->srname,
                    "invoicedescription" => "",
                    "paymentyear" => date("Y"),
                    "schoolyearID" => $schoolyearID,];
                $this->globalpayment_m->insert_globalpayment($globalpayment);
                $globalLastID = $this->db->insert_id();
                $due = 0;
                $paidstatus = 0;
                $globalstatus = [];
                                foreach ($this->data["invoices"]
                                    as $key => $invoice) {
                                    if ($invoice->paidstatus != 2) {
                                        if (isset($invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID])
                    ) {
            $due =
                                                (float) $invoicepaymentandweaver["totalamount"                    ][$invoice->invoiceID];

                                            if (isset($invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totaldiscount"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalpayment"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalpayment"][$invoice->invoiceID]);
                                            }

                                            if (isset($invoicepaymentandweaver["totalweaver"][$invoice->invoiceID])) {
                $due =    (float) ($due -
                        $invoicepaymentandweaver["totalweaver"][$invoice->invoiceID]);
                                            }
                                        }

        $totalPayment = 0;
                                        if (isset($params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "paidamount_" .
                    $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ];
                                        }

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
            $params["allpost"][
                                "weaver_" . $invoice->invoiceID
                                            ] > 0
                    ) {
            $totalPayment +=
                                                (float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ];
                                        }

        $due = number_format($due, 2, ".", "");
        $totalPayment = number_format($totalPayment,        2,".","");

        $paidstatus = 0;
                                        if ($due <= $totalPayment) {
            $globalstatus[] = true;
            $paidstatus = 2;
                                        } else {
            $globalstatus[] = false;
            $paidstatus = 1;
                                        }

        $paymentArray = [
                            "invoiceID" => $invoice->invoiceID,"schoolyearID" => $schoolyearID,"studentID" => $invoice->studentID,"paymentamount" =>            $params["allpost"]["paidamount_" .
                        $invoice->invoiceID
                                                ] == ""                        ? null
                                                    : $params["allpost"]["paidamount_" .
                            $invoice->invoiceID],"paymenttype" => ucfirst($params["allpost"]["paymentmethodID"                    ]),"paymentdate" => date("Y-m-d"),"paymentday" => date("d"),"paymentmonth" => date("m"),"paymentyear" => date("Y"),"userID" => $this->session->userdata("loginuserID"),"usertypeID" => $this->session->userdata("usertypeID"),"uname" => $this->session->userdata("name"),"transactionID" => $txnid,"globalpaymentID" => $globalLastID,    ];

        $this->payment_m->insert_payment($paymentArray
                    );
        $paymentLastID = $this->db->insert_id();
        $this->invoice_m->update_invoice(["paidstatus" => $paidstatus],        $invoice->invoiceID
                    );

                                        if (isset($params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ]) &&
                                            isset($params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ])
                    ) {
                                            if ((float) $params["allpost"]["weaver_" .
                        $invoice->invoiceID
                                                ] > (float) 0 ||
                                                (float) $params["allpost"]["fine_" .
                        $invoice->invoiceID
                                                ] > (float) 0) {
                $weaverandfineArray = ["globalpaymentID" => $globalLastID,"invoiceID" =>    $invoice->invoiceID,"paymentID" => $paymentLastID,"studentID" =>    $invoice->studentID,"schoolyearID" => $schoolyearID,"weaver" =>    $params["allpost"]["weaver_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["weaver_" .
                                    $invoice->invoiceID
                                                            ],"fine" =>    $params["allpost"]["fine_" .
                                $invoice->invoiceID
                                                        ] == ""                                ? 0
                                                            : $params["allpost"]["fine_" .
                                    $invoice->invoiceID
                                                            ],            ];
                $this->weaverandfine_m->insert_weaverandfine($weaverandfineArray
                            );
                                            }
                                        }
                                    }
                                }

                                if (in_array(false, $globalstatus)) {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "partial"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 1],    $params["invoice_id"]);
                                } else {
    $this->globalpayment_m->update_globalpayment(["clearancetype" => "paid"],    $globalLastID);
    $this->maininvoice_m->update_maininvoice(["maininvoicestatus" => 2],    $params["invoice_id"]);
                                }

                $this->session->set_flashdata("success",
                    "Payment successful!");
                            }
                        } else {
            $this->session->set_flashdata("error",
                "Student does not found.");
                            redirect(base_url("invoice/view/" . $params["invoice_id"]));
                        }
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    } else {
        $this->session->set_flashdata("error",
            "Invalid invoice");
                        redirect(base_url("invoice/view/" . $params["invoice_id"]));
                    }
                } else {
    $this->session->set_flashdata(
        "error",
        "Transaction ID already exist!"
);
                    redirect(base_url("invoice/view/" . $invoiceID));
                }
            } else {
                redirect(base_url("invoice/view/" . $invoiceID));
            }
        } else {
            $this->session->set_flashdata(
"error",
"Invalid Transaction. Please try again");
            redirect(base_url("invoice/view/" . $invoiceID));
        }
    }

    public function voguepay_failed()
    {
        $invoice = $this->uri->segment(3);
        $this->session->set_flashdata("error", "Payment failed!");
        redirect(base_url("invoice/view/" . $invoice));
    }

    private $debug = true;
    private $debug_msg = [];

    public function verifyVoguePayPayment($transaction_id)
    {
        $details = json_decode(
            $this->getVoguePayPaymentDetails($transaction_id, "json")
);
        if (!$details && $this->debug == true) {
            $this->debug_msg[] =
"Failed Getting Transaction Details - [Called In verifyPayment()]";
        }
        if ($details->total < 1) {
            return json_encode(["state" => "error",
"msg" => "Invalid Transaction",
            ]);
        }
        if ($details->status != "Approved") {
            return json_encode(["state" => "error",
"msg" => "Transaction {$details->status}",
            ]);
        }
        return json_encode(["state" => "success",
"msg" => "Transaction Approved",
"details" => $details,
        ]);
    }

    public function getVoguePayPaymentDetails($transaction_id, $type = "json")
    {
        $api_config = [];
        $get_configs = $this->payment_settings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->config_key] = $get_key->value;
        }
        if ($api_config["voguepay_demo"] == true) {
            $url = "https://voguepay.com/?v_transaction_id={$transaction_id}&type={$type}&demo=true";
        } else {
            $url = "https://voguepay.com/?v_transaction_id={$transaction_id}&type={$type}";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
"Mozilla/5.0 (Windowos NT 5.1; en-NG; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 Vyren Media-VoguePay API Ver 1.0"
);
        if (curl_errno($ch) && $this->debug == true) {
            $this->debug_msg[] =
                curl_error($ch) . " - [Called In getPaymentDetails() CURL]";
        }
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
    /* VoguePay End*/
}
