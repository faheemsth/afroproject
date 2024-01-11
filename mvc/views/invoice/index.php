<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-invoice"></i> <?='Invoices'?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_invoice')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php if(permissionChecker('invoice_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('invoice/add') ?>">
                                <i class="fa fa-plus"></i> 
                                <?=$this->lang->line('add_title')?>
                            </a>  &nbsp; &nbsp;&nbsp;
                            <a href="<?php echo base_url('invoice/add_hostel_fee') ?>" style="display: none;">
                                <i class="fa fa-plus"></i> 
                                Process Hostel Fee
                            </a> &nbsp; &nbsp;&nbsp;
                            <a href="<?php echo base_url('invoice/add_transport_fee') ?>" style="display: none;">
                                <i class="fa fa-plus"></i> 
                                Process Transport Fee
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                </div>
                <!-- <div class="col-sm-2"> -->
                     <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                        <?php if(permissionChecker('invoice_add')) { 
                                // echo session_id(); 
                                // if ($_GET['sessions'] == 'devv') {
                                    ?>
                                     <!-- <h5 class="page-header">
                                        <a href="<?php echo base_url('invoice/add') ?>">
                                            <i class="fa fa-plus"></i> 
                                            <?=$this->lang->line('add_charges')?>
                                        </a>
                                    </h5> -->
                                    <?php
                                // }
                            ?>
                        <?php } ?>
                     <?php } ?>
                <!-- </div> -->
                <?php if(permissionChecker('invoice_add')) { 
                                // echo session_id(); 
                                // if ($_GET['sessions'] == 'devv') {
                                    ?>
                <form type="get" action="">
                <div class="col-sm-12">
                  

                <div class="form-group col-sm-3" id="classesDiv">
                    <label>Invoice Type </span></label>
                    <?php
                        $array = [lang("All")];
                        $array2 = get_general_feetype();

                        $array  =   array_merge($array, $array2);
                         
                        echo form_dropdown("maininvoice_type_v", $array, set_value("maininvoice_type_v",$maininvoice_type_v), "id='maininvoice_type_v' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-3" id="classesDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_class")?></label>
                    <?php
                        $array = array("0" => $this->lang->line("attendanceoverviewreport_please_select"));
                        if(customCompute($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID",$classesID), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-3" id="sectionDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                        <?php if(count($allsection)){
                            foreach ($allsection as $sc) {
                                ?>
                                <option value="<?php echo $sc->sectionID?>"
                                    <?php if ($sectionID==$sc->sectionID) {
                                        echo "selected";
                                    }?>
                                    >
                                    <?php echo $sc->section;?>
                                </option>
                                <?php
                            }
                        }?>
                    </select>
                </div>
 

                <div class="form-group col-sm-3" id="userDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_student")?></label>
                    <select id="studentID" name="studentID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                        <?php if(count($allstudents)){
                            foreach ($allstudents as $st) {
                                ?>
                                <option value="<?php echo $st->studentID?>"
                                    <?php if ($studentID==$st->studentID) {
                                        echo "selected";
                                    }?>
                                    >
                                    <?php echo $st->name;?>
                                </option>
                                <?php
                            }
                        }?>
                    </select>
                </div>

                 

                <div class="form-group col-sm-3"  >
                    <label>Name/Reg. No. </label>
                    <input type="text" id="nameSearch" name="nameSearch" value="<?php echo set_value('nameSearch',$nameSearch);?>" class="form-control"/>
                </div>
                <div class="form-group col-sm-3"  >
                    <label>Refrence No. </label>
                    <input type="text" id="refrence_no" name="refrence_no" value="<?php echo set_value('refrence_no',$refrence_no);?>" class="form-control"/>
                </div>
                <div class="form-group col-sm-3"  >
                    <label>Payment Status </label>

                    

                    <?php
                        $maininvoicestatus_ar = array(
                                        
                                        ""  => 'All Status',
                                        "0" => $this->lang->line('invoice_notpaid'),
                                        "1" => $this->lang->line('invoice_partially_paid'),
                                        "2" => $this->lang->line('invoice_fully_paid') 
                                        );
                         
                        echo form_dropdown("maininvoicestatus", $maininvoicestatus_ar, set_value("maininvoicestatus",$maininvoicestatus), "id='maininvoicestatus_filter' class='form-control select2'");
                     ?>
                    
                </div>
                <div class="form-group col-sm-3"  >
                    <label>Invoice Status</label>

                    

                    <?php
                        $invoice_status_ar = array(
                                        
                                        "99"  => 'All',
                                        "0" => 'In Active',
                                        "1" => 'Active',  
                                        );
                         
                        echo form_dropdown("invoice_status", $invoice_status_ar, set_value("invoice_status",$invoice_status), "id='invoice_status' class='form-control select2'");
                     ?>
                    
                </div> 
                <div class="form-group col-sm-3"  >
                    <label>Date Type</label>

                    

                    <?php
                        $date_type_ar = array(
                                        
                                        ""  => 'All',
                                        "maininvoicedate" => 'Invoice Create Date',
                                        "maininvoicedue_date " => 'Invoice Due Date',  
                                        );
                         
                        echo form_dropdown("date_type", $date_type_ar, set_value("date_type",$date_type), "id='date_type' class='form-control select2'");
                     ?>
                    
                </div>
                <div class="form-group col-sm-3"  >
                    <label>Start Date</label>

                    

                    <input type="text" id="start_date" name="start_date" value="<?php echo set_value('start_date',$start_date);?>" class="form-control datepicker"/>
                    
                </div>
                <div class="form-group col-sm-3"  >
                    <label>End Date</label>

                    

                    <input type="text" id="end_date" name="end_date" value="<?php echo set_value('end_date',$end_date);?>" class="form-control datepicker"/>
                    
                </div>

                <div class="form-group col-sm-3"  >
                    <label>View/Download</label>

                    

                    <?php 
                        $rendertype_ar = array(
                                        "view" => lang('View'),  
                                        "download" => lang('Download'),  
                                        );
                         
                        echo form_dropdown("rendertype", $rendertype_ar, set_value("rendertype",$rendertype), "id='rendertype_filter' class='form-control select2'");
                     ?>
                    
                </div>

                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                    <a href="<?php echo base_url('invoice/');?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                </div>

            
                </div>
            </form> 
             <?php } ?>
             <span class="clearfix"></span>
             <br>
                <div id="hide-table">
                      <!--id="example1"-->
                    <table id="invoice_table" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('invoice_student')?></th>
                                <th><?=$this->lang->line('invoice_classesID')?></th> 
                                <th><?=$this->lang->line('invoice_total')?></th>
                                <th><?=$this->lang->line('invoice_discount')?></th>
                                <th><?=$this->lang->line('invoice_fine')?></th>
                                <th><?=$this->lang->line('invoice_subtotal');?></th>
                                <th><?=$this->lang->line('invoice_paid')?></th>
                                <!--<th><?=$this->lang->line('invoice_weaver')?></th>-->
                                <!--<th><?=$this->lang->line('invoice_balance')?></th>-->
                                <th><?=$this->lang->line('invoice_onlystatus')?></th>
                                <th><?=$this->lang->line('invoice_date_from')?></th>
                                <th><?=$this->lang->line('invoice_date_to')?></th>
                                <?php if(permissionChecker('invoice_view') || permissionChecker('invoice_edit') || permissionChecker('invoice_delete')) { ?>
                                    <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 

                                if(customCompute($maininvoices)) {$i = 1; foreach($maininvoices as $maininvoice) { 
                                        // if ($this->session->userdata('dev_test') == 'Shuja') {
                                        //         echo '---Development Environment';
                                        //         echo '<br>';
                                        //     if ($maininvoice->maininvoicesectionID == $maininvoice->srsectionID) {
                                        //         $balance = $maininvoice->balance / $maininvoice->no_installment;
                                        //     }else{
                                        //         $balance = 0;
                                        //     }
                                        //     echo $balance + $maininvoice->maininvoicetotal_fee;

                                        // }
                                    ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $maininvoice->maininvoiceID; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_student')?>">
                                        <?php echo $maininvoice->srname; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_classesID')?>">
                                            <?php
                                              
                                         echo ($classes_pluck[$maininvoice->maininvoiceclassesID]->classes) ; ?>
                                    </td>

                                    

                                    <td data-title="<?=$this->lang->line('invoice_total')?>">
                                        <?php 
                                            // $maininvoicetotal_fee = $balance + $maininvoice->maininvoicetotal_fee;
                                            echo ceil($maininvoice->maininvoicetotal_fee-$maininvoice->totalfine);
                                        // if(isset($grandtotalandpayment['totalamount'][$maininvoice->maininvoiceID])) { echo number_format($grandtotalandpayment['totalamount'][$maininvoice->maininvoiceID], 2); } else { echo '0.00'; } 
                                        ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('invoice_discount')?>">
                                        <?php 
                                             echo ceil($maininvoice->maininvoice_discount);
                                        //   if(isset($grandtotalandpayment['totaldiscount'][$maininvoice->maininvoiceID])) { echo number_format($grandtotalandpayment['totaldiscount'][$maininvoice->maininvoiceID], 2); } else { echo '0.00'; } 
                                           ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('invoice_fine')?>">
                                        <?php
                                            // $today = date("Y-m-d");
                                            // $today_time = strtotime($today);
                                            // $check_dates = strtotime($maininvoice->maininvoicedue_date);
                                            // if($check_dates < $today_time){
                                            // 	$datediff =   ceil(($today_time - $check_dates)/86400);
                                            // 	if($datediff < 31){
                                            // 	    $final = $datediff * 100;    
                                            // 	}else{
                                            // 	    $final = 0;    
                                            // 	}
                                            // 	echo $final;
                                            // }else{
                                                $final = 0;
                                            // 	echo '0.0';
                                            // }
                                            echo $maininvoice->totalfine;
                                        ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('invoice_subtotal')?>">
                                        <?php
                                            $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                            echo ceil($maininvoicenet_fee);
                                            // if(isset($grandtotalandpayment['grandtotal'][$maininvoice->maininvoiceID])) { 
                                            //     if(isset($grandtotalandpayment['totalpayment'][$maininvoice->maininvoiceID])) { 
                                            //         if(isset($grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID])) {
                                            //             $paymentandweaver = ($grandtotalandpayment['totalpayment'][$maininvoice->maininvoiceID] + $grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID]);
                                            //             echo number_format(((float)$grandtotalandpayment['grandtotal'][$maininvoice->maininvoiceID] - (float)$paymentandweaver), 2);
                                            //         } else {
                                            //             echo number_format(((float)$grandtotalandpayment['grandtotal'][$maininvoice->maininvoiceID] - (float)$grandtotalandpayment['totalpayment'][$maininvoice->maininvoiceID]), 2);
                                            //         }
                                            //     } else {
                                            //         if(isset($grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID])) {
                                            //             echo number_format(((float)$grandtotalandpayment['grandtotal'][$maininvoice->maininvoiceID] - (float)$grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID]), 2);
                                            //         } else {
                                            //             echo number_format((float)$grandtotalandpayment['grandtotal'][$maininvoice->maininvoiceID], 2);
                                            //         }
                                            //     }
                                            // } else { 
                                            //     echo '0.00'; 
                                            // } 
                                        ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('invoice_paid')?>">
                                        <?php if(isset($grandtotalandpayment['totalpayment'][$maininvoice->maininvoiceID])) { echo number_format($grandtotalandpayment['totalpayment'][$maininvoice->maininvoiceID], 2); } else { echo '0'; } ?>
                                    </td>

                                    <!--<td data-title="<?=$this->lang->line('invoice_weaver')?>">-->
                                        <?php 
                                            // if(isset($grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID])) { echo number_format($grandtotalandpayment['totalweaver'][$maininvoice->maininvoiceID], 2); } else { echo '0.00'; } 
                                            ?>
                                    <!--</td>-->

                                    

                                    <td data-title="<?=$this->lang->line('invoice_onlystatus')?>">
                                        <?php 
                                            $status = $maininvoice->maininvoicestatus;
                                            $setButton = '';
                                            if($status == 0) {
                                                $status = $this->lang->line('invoice_notpaid');
                                                $setButton = 'btn-danger';
                                            } elseif($status == 1) {
                                                $status = $this->lang->line('invoice_partially_paid');
                                                $setButton = 'btn-warning';
                                            } elseif($status == 2) {
                                                $status = $this->lang->line('invoice_fully_paid');
                                                $setButton = 'btn-success';
                                            }

                                            echo "<button class='btn ".$setButton." btn-xs'>".$status."</button>";
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_date')?>">
                                        <?php echo date("d M Y", strtotime($maininvoice->maininvoicedate)) ; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('invoice_date')?>">
                                        <?php echo date("d M Y", strtotime($maininvoice->maininvoicedue_date)) ; ?>
                                    </td>

                                    <?php if((permissionChecker('invoice_view') || permissionChecker('invoice_edit') || permissionChecker('invoice_delete'))) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            if ($maininvoice->maininvoicestatus == 0) {
                                                echo btn_view('invoice/view/'.$maininvoice->maininvoiceID, $this->lang->line('view'));
                                            }
                                            ?>
                                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                            <?php if( $maininvoice->maininvoicestatus != 2 && $maininvoice->maininvoice_status == 1) { echo btn_edit('invoice/edit/'.$maininvoice->maininvoiceID, $this->lang->line('edit')); } ?>
                                            <?php if($maininvoice->maininvoicestatus != 1 && $maininvoice->maininvoicestatus != 2 && $maininvoice->maininvoice_status == 1) { echo btn_delete('invoice/delete/'.$maininvoice->maininvoiceID, $this->lang->line('delete')); } ?>
                                        <?php } ?>
                                        <?php if(permissionChecker('invoice_view') && permissionChecker('invoice_edit')) { if( ($maininvoice->maininvoicestatus != 2 && $maininvoice->maininvoice_status == 1)  || $maininvoice->maininvoicestatus == 0   ) { echo btn_invoice('invoice/payment/'.$maininvoice->maininvoiceID, $this->lang->line('payment')); }} ?>
                                        <?php 
                                            if(permissionChecker('invoice_view')  && permissionChecker('invoice_edit')) { 
                                                echo '<a href="#paymentlist" id="'.$maininvoice->maininvoiceID.'" class="btn btn-info btn-xs mrg getpaymentinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-list-ul" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('invoice_view_payments').'"></i></a>';
                                                 echo '<a href="'.base_url().'student/view/'.$maininvoice->maininvoicestudentID.'/'.$maininvoice->srclassesID.'#payment" class="btn btn-warning btn-xs mrg"><i class="fa fa-briefcase" data-toggle="tooltip"  data-placement="top" target="_blank" data-original-title="'.$this->lang->line('invoice_view_student').'"></i></a>';
                                                 if ($maininvoice->maininvoice_status == 0) {
                                                        echo '<a href="#" class="btn btn-danger btn-xs mrg"><i class="fa fa-eye-slash" data-toggle="tooltip"  data-placement="top" data-original-title="Inactive"></i></a>';
                                                 }else{
                                                         echo '<a href="#" class="btn btn-primary btn-xs mrg"><i class="fa fa-eye" data-toggle="tooltip"  data-placement="top" data-original-title="Active"></i></a>';
                                                 }
                                                 
                                                // echo $maininvoice->maininvoice_status;
                                            }
                                        ?>

                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                    
                </div>
                    <div class="row">
                    <div class="col-sm-6 p0">
                        <?php if($count>0){?>
                    <h5 style="margin: 20px 0px;">Total Record Found <?php echo $count;?></h5>
                    <?php }?>
                    </div>
                    <div class="col-sm-6 text-right p0">
                        <?php 
                        if($this->session->userdata("usertypeID") != 3){
                            echo $this->pagination->create_links(); 
                        }
                        
                    ?>
                    </div>

                    </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentlist">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('invoice_view_payments')?></h4>
            </div>
            <div class="modal-body">
                <div id="hide-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?='Refrence No'?></th>
                                <th><?=$this->lang->line('invoice_date')?></th>
                                <th><?=$this->lang->line('invoice_paidby')?></th>
                                <th><?=$this->lang->line('invoice_paymentamount')?></th>
                                <th><?=$this->lang->line('invoice_weaver')?></th>
                                <th><?=$this->lang->line('invoice_fine')?></th>
                                <th><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="payment-list-body">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
    $('#invoice_table').DataTable({
        paging: false,
        ordering: false,
        info: false,
        dom : 'Bfrtip',
          buttons : [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
          ],
    });
});
    $('#statusID').change(function(){
        var year = $(this).val();
        console.log(year);
        if (year != '0') {
            $("#form_year").submit();
        } 
    });

    $('.getpaymentinfobtn').click(function() {
        var maininvoiceID =  $(this).attr('id');
        if(maininvoiceID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/paymentlist')?>",
                data: {'maininvoiceID' : maininvoiceID},
                dataType: "html",
                success: function(data) {
                    $('#payment-list-body').children().remove();
                    $('#payment-list-body').append(data);
                }
            });
        }   
    });
</script>


<script type="text/javascript">

     

    $('.datepicker').datepicker();
    $('.select2').select2();
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('attendanceoverviewreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    

    $(document).on('change', '#classesID', function() {
        $('#load_attendanceoverview_report').html('');
        var classesID = $(this).val();

        $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
        $('#userID').val('0');
        if(classesID == 0) {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#sectionID').val('0');
            
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#userID').val('0');
            $('#monthID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
 
        }
    });

    $(document).on('change', '#sectionID', function() {
        $('#load_attendanceoverview_report').html('');
        var usertype    = 1;
        var classesID   = $('#classesID').val();
        var sectionID   = $('#sectionID').val();
        if(sectionID == 0 ) {
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#userID').val('0');
        } else if(sectionID > 0 ) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getStudent')?>",
                data: {"usertype" : usertype,'classesID':classesID,'sectionID':sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });
</script>