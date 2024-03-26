<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-balancefeesreport"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"> <?= $this->lang->line('menu_balancefeesreport') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Degree Wise Report</a></li>
                    <!-- <li><a data-toggle="tab" href="#menu1">Date Wise Report</a></li> -->
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <form role="form" method="get" action="<?php echo base_url('balancefeesreport/getBalanceFeesReport'); ?>">
                            <div class="col-sm-12">

                                <div class="col-sm-3">
                                    <div class="form-group" id="maininvoice_type_vDiv">
                                        <label>Invoice Type </span></label>
                                        <?php
                                        $array = get_general_feetype();

                                        echo form_dropdown("maininvoice_type_v[]", $array, set_value("maininvoice_type_v", $maininvoice_type_v), "id='maininvoice_type_v' required multiple class='form-control select2'");
                                        ?>
                                    </div>
                                    <div class="form-group" id="classesDiv">
                                        <label>Degree</label>
                                        <?php

                                        $arrayc = [];
                                        if (customCompute($classes)) {
                                            foreach ($classes as $key => $val) {

                                                $arrayc[$key] = $val;
                                            }
                                        }

                                        echo form_dropdown("classesID[]", $arrayc, set_value("classesID", $classesID), "id='classesID' multiple class='form-control select2'");
                                        ?>
                                    </div>


                                    <div class="form-group <?= form_error('numric_code') ? 'has-error' : '' ?>">
                                        <label for="numric_code">
                                            Semester Number
                                        </label>
                                        <?php
                                        $numricArray = array(
                                            '1'     => '1',
                                            '2'     => '2',
                                            '3'     => '3',
                                            '4'     => '4',
                                            '5'     => '5',
                                            '6'     => '6',
                                            '7'     => '7',
                                            '8'     => '8',
                                            '9'     => '9',
                                            '10'    => '10',
                                            '11'    => '11',
                                        );

                                        echo form_dropdown("numric_code[]", $numricArray, set_value("numric_code"), "id='numric_code' multiple class='form-control select2'");
                                        ?>
                                        <span class="text-red">
                                            <?php echo form_error('numric_code'); ?>
                                        </span>
                                    </div>

                                </div>

                                <div class="col-sm-9">


                                    <div class="form-group col-sm-4">
                                        <label>Payment Status </label>



                                        <?php
                                        $maininvoicestatus_ar = array(

                                            ""  => 'All Status',
                                            "0" => 'Not Paid',
                                            "1" => 'Partially Paid',
                                            "2" => 'Fully Paid',
                                        );

                                        echo form_dropdown("maininvoicestatus", $maininvoicestatus_ar, set_value("maininvoicestatus", $maininvoicestatus), "id='maininvoicestatus_filter' class='form-control select2'");
                                        ?>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Invoice Status</label>
                                        <?php
                                        $invoice_status_ar = array(

                                            "99"    => 'All',
                                            "0"     => 'In Active',
                                            "1"     => 'Active',
                                        );

                                        echo form_dropdown("invoice_status", $invoice_status_ar, set_value("invoice_status", $invoice_status), "id='invoice_status' required class='form-control select2'");
                                        ?>

                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>Student Status</span></label>
                                        <?php
                                        $array = get_student_status_type();

                                        echo form_dropdown("student_status", $array, set_value("active", $student_status), "id='active' class='form-control select2'");
                                        ?>
                                    </div>



                                    <div class="form-group col-sm-4">
                                        <label>Date Type</label>
                                        <?php
                                        $date_type_ar = array(

                                            "0"                      => 'All',
                                            "maininvoicedate"       => 'Invoice Create Date',
                                            "maininvoicedue_date"   => 'Invoice Due Date',
                                            "paymentdate"           => 'Payment Date',
                                        );

                                        echo form_dropdown("date_type", $date_type_ar, set_value("date_type", $date_type), "id='date_type' required class='form-control select2'");
                                        ?>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>Start Date</label>
                                        <input type="text" id="start_date" name="start_date" required value="<?php echo set_value('start_date', $start_date); ?>" class="form-control datepicker" />
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>End Date</label>
                                        <input type="text" id="end_date" name="end_date" required value="<?php echo set_value('end_date', $end_date); ?>" class="form-control datepicker" />
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>View/Download</label>



                                        <?php
                                        $veiw_down = array(
                                            "view"       => 'View',
                                            "download"   => 'Download'
                                        );

                                        echo form_dropdown("veiw_down", $veiw_down, set_value("veiw_down"), "id='veiw_down' class='form-control select2'");
                                        ?>

                                    </div>
                                    <div class="col-sm-4">
                                        <button id="get_duefeesreport" type="submit" class="btn btn-success" style="margin-top:23px;"> <?= $this->lang->line("balancefeesreport_submit") ?></button>
                                    </div>
                        </form>
                    </div>



                </div>
            </div>
            <div id="menu1" class="tab-pane fade">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4" id="fromdateDiv">
                                <label><?= 'From Date' ?><span class="text-red"> * </span></label>
                                <input class="form-control" type="text" name="fromdate" id="fromdate">
                            </div>

                            <div class="form-group col-sm-4" id="todateDiv">
                                <label><?= 'To Date' ?><span class="text-red"> * </span></label>
                                <input class="form-control" type="text" name="todate" id="todate">
                            </div>

                            <div class="col-sm-4">
                                <button id="get_classreport" class="btn btn-success" style="margin-top:23px;"> <?= 'Get Report' ?></button>
                            </div>
                        </div>
                    </div><!-- row -->
                </div><!-- Body -->
            </div>
        </div>
    </div>

</div><!-- row -->
</div><!-- Body -->
</div><!-- /.box -->
<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php

        // $pdf_preview_uri = base_url('balancefeesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID);
        // $xml_preview_uri = base_url('balancefeesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID);

        // echo btn_printReport('balancefeesreport', $this->lang->line('report_print'), 'printablediv');
        // echo btn_pdfPreviewReport('balancefeesreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
        // echo btn_xmlReport('balancefeesreport',$xml_preview_uri, $this->lang->line('report_xlsx'));
        // echo btn_sentToMailReport('balancefeesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?= $this->lang->line('balancefeesreport_report_for') ?> -
            <?= $this->lang->line('balancefeesreport_balancefees'); ?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?= reportheader($siteinfos, $schoolyearsessionobj) ?>
                </div>
                <?php if ($classesID >= 0 || $sectionID >= 0) { ?>
                    <div class="col-sm-12" style="margin-top: 15px;"></div>
                <?php } else { ?>
                    <div class="col-sm-12" style="margin-top: 15px;"></div>
                <?php }

                if (customCompute($students)) {
                    $ivoicetypes    =   get_general_feetype();
                ?>
                    <div class="col-sm-12">
                        <div id="hide-table" style="overflow: scroll;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= $this->lang->line('slno') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_name') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_registerNO') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_class') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_section') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_roll') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_invoice_type') ?></th>
                                        <th>Opening Balance</th>
                                        <?php
                                        $typetotal  =   [];
                                        foreach ($maininvoice_type_v as $invtype) {
                                            $typetotal[$invtype]['total']           = 0;
                                            $typetotal[$invtype]['totaliscount']    = 0;

                                        ?>
                                            <th><?php echo $ivoicetypes[$invtype]; ?></th>
                                            <th>Discount</th>
                                        <?php } ?>
                                        <th>Total</th>
                                        <th>Fine</th>
                                        <th>Total Discount</th>
                                        <th>Net Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalAmount    = 0;
                                    $totalDiscount  = 0;
                                    $totalPayments  = 0;
                                    $totalWeaver    = 0;
                                    $totalBalance   = 0;
                                    $i = 0;
                                    foreach ($students as $student) {
                                        $i++; ?>

                                        <tr>
                                            <td data-title="<?= $this->lang->line('slno') ?>"><?= $i ?></td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_name') ?>">
                                                <?= $student->name ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_registerNO') ?>">
                                                <?= $student->registerNO ?>
                                            </td>

                                            <td data-title="<?= $this->lang->line('balancefeesreport_class') ?>">
                                                <?= isset($classes[$student->classesID]) ? $classes[$student->classesID] : '' ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_section') ?>">
                                                <?= isset($sections[$student->sectionID]) ? $sections[$student->sectionID] : '' ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_roll') ?>">
                                                <?= $student->roll ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_invoice_type') ?>">
                                                <?= isset($totalAmountAndDiscount[$student->studentID]['feetype']) ? $totalAmountAndDiscount[$student->studentID]['feetype'] : 'None' ?>
                                            </td>



                                            <?php
                                            $st_amount1      =   0;
                                            $st_discount1    =   0;
                                            if (isset($totalAmountAndDiscount1[$student->studentID]['total_paid'])) {
                                                $total_paid1     =   $totalAmountAndDiscount1[$student->studentID]['total_paid'];
                                            } else {
                                                $total_paid1     =   0;
                                            }
                                            foreach ($maininvoice_type_v as $invtype) {



                                                if (isset($totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype])) {
                                                    $st_amount1    += $totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype];
                                                    $typetotal[$invtype]['total']   += $totalAmountAndDiscount1[$student->studentID]['type_amount'][$invtype];
                                                }
                                                if (isset($totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype])) {
                                                    $st_discount1    += $totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype];
                                                    $typetotal[$invtype]['totaliscount']    += $totalAmountAndDiscount1[$student->studentID]['type_discount'][$invtype];
                                                }



                                            ?>


                                            <?php } ?>

                                            <?php
                                            $net_amount1 = $st_amount1 - $st_discount1;

                                            ?>

                                            <td>
                                                <?php


                                                $totalAmount1    += $st_amount1;
                                                $totalDiscount1  += $st_discount1;
                                                $totalPayments1  += $total_paid1;
                                                $balance1        =  $net_amount1 - $total_paid1;

                                                $totalBalance1   += $balance1;
                                                echo floor($balance1) < 0 ? 0 : floor($balance1);
                                                ?>
                                            </td>

                                            <?php
                                            $st_amount      =   0;
                                            $st_discount    =   0;
                                            if (isset($totalAmountAndDiscount[$student->studentID]['total_paid'])) {
                                                $total_paid     =   $totalAmountAndDiscount[$student->studentID]['total_paid'];
                                            } else {
                                                $total_paid     =   0;
                                            }

                                            if (isset($totalAmountAndDiscount[$student->studentID]['totalfine'])) {
                                                $totalfine     =   $totalAmountAndDiscount[$student->studentID]['totalfine'];
                                            } else {
                                                $totalfine     =   0;
                                            }
                                            foreach ($maininvoice_type_v as $invtype) {



                                                if (isset($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype])) {
                                                    $st_amount    += $totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype];
                                                    $typetotal[$invtype]['total']   += $totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype];
                                                }
                                                if (isset($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype])) {
                                                    $st_discount    += $totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype];
                                                    $typetotal[$invtype]['totaliscount']    += $totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype];
                                                }



                                            ?>
                                                <td data-title="<?= $this->lang->line('balancefeesreport_other_charges') ?>">

                                                    <?= isset($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype]) ? ceil($totalAmountAndDiscount[$student->studentID]['type_amount'][$invtype]) : 0 ?>
                                                </td>
                                                <td data-title="<?= $this->lang->line('balancefeesreport_other_charges') ?>">
                                                    <?= isset($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype]) ? ceil($totalAmountAndDiscount[$student->studentID]['type_discount'][$invtype]) : 0 ?>
                                                </td>
                                            <?php } ?>

                                            <td><?php echo $st_amount - $totalfine; ?></td>
                                            <td><?php echo $totalfine; ?></td>
                                            <td><?php echo $st_discount; ?></td>
                                            <td><?php
                                                $net_amount = $st_amount - $st_discount;
                                                echo $net_amount;
                                                ?></td>
                                            <td data-title="<?= $this->lang->line('balancefeesreport_invoice_type') ?>">
                                                <?= $total_paid ?>
                                            </td>
                                            <td>
                                                <?php

                                                $totalAmount    += $st_amount;
                                                $totalDiscount  += $st_discount;
                                                $totalPayments  += $total_paid;
                                                $balance        =  $net_amount - $total_paid;

                                                $totalBalance   += $balance;
                                                echo $balance;
                                                ?>
                                            </td>

                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th><?= $this->lang->line('slno') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_name') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_registerNO') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_class') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_section') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_roll') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_invoice_type') ?></th>
                                        <th><?php echo floor($totalBalance1) ?></th>
                                        <?php foreach ($maininvoice_type_v as $invtype) { ?>
                                            <th><?php echo $typetotal[$invtype]['total'];  ?></th>
                                            <th><?php echo $typetotal[$invtype]['totaliscount']; ?></th>
                                        <?php } ?>
                                        <th><?php echo $totalAmount; ?></th>
                                        <th><?php echo $totalDiscount; ?></th>
                                        <th><?php echo $totalAmount - $totalDiscount; ?></th>
                                        <th><?php echo $totalPayments; ?></th>
                                        <th><?php echo $totalAmount - $totalDiscount - $totalPayments; ?></th>
                                        <th><?= $totalBalance ?></th>

                                    </tr>

                                    <tr>
                                        <th><?= $this->lang->line('slno') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_name') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_registerNO') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_class') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_section') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_roll') ?></th>
                                        <th><?= $this->lang->line('balancefeesreport_invoice_type') ?></th>
                                        <th>Opening Balance</th>
                                        <?php
                                        $typetotal  =   [];
                                        foreach ($maininvoice_type_v as $invtype) {
                                            $typetotal[$invtype]['total']           = 0;
                                            $typetotal[$invtype]['totaliscount']    = 0;

                                        ?>
                                            <th><?php echo $ivoicetypes[$invtype]; ?></th>
                                            <th>Discount</th>
                                        <?php } ?>
                                        <th>Total</th>
                                        <th>Fine</th>
                                        <th>Total Discount</th>
                                        <th>Net Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <br />
                    <div class="col-sm-12">
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?= $this->lang->line('report_data_not_found') ?></b></p>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12 text-center footerAll">
                    <?= reportfooter($siteinfos, $schoolyearsessionobj) ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?= base_url('balancefeesreport/send_pdf_to_mail'); ?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?= $this->lang->line('balancefeesreport_close') ?></span></button>
                    <h4 class="modal-title"><?= $this->lang->line('balancefeesreport_mail') ?></h4>
                </div>
                <div class="modal-body">

                    <?php
                    if (form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?= $this->lang->line("balancefeesreport_to") ?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?= set_value('to') ?>">
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                if (form_error('subject'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="subject" class="col-sm-2 control-label">
                    <?= $this->lang->line("balancefeesreport_subject") ?> <span class="text-red">*</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="subject" name="subject" value="<?= set_value('subject') ?>">
                </div>
                <span class="col-sm-4 control-label" id="subject_error">
                </span>

            </div>

            <?php
            if (form_error('message'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="message" class="col-sm-2 control-label">
                <?= $this->lang->line("balancefeesreport_message") ?>
            </label>
            <div class="col-sm-6">
                <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?= set_value('message') ?>"></textarea>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
        <input type="button" id="send_pdf" class="btn btn-success" value="<?= $this->lang->line("balancefeesreport_send") ?>" />
    </div>
    </div>
    </div>
    </div>
</form>

<script type="text/javascript">
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?= $this->lang->line('balancefeesreport_mail_valid') ?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $("#send_pdf").click(function() {
        var field = {
            'to': $('#to').val(),
            'subject': $('#subject').val(),
            'message': $('#message').val(),
            'classesID': '<?= $classesID ?>',
            'sectionID': '<?= $sectionID ?>',
            'studentID': '<?= $studentID ?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if (to == "" || to == null) {
            error++;
            $("#to_error").html("<?= $this->lang->line('balancefeesreport_mail_to') ?>").css("text-align", "left").css("color", 'red');
        } else {
            if (check_email(to) == false) {
                error++
            }
        }

        if (subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?= $this->lang->line('balancefeesreport_mail_subject') ?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if (error == 0) {
            $('#send_pdf').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('balancefeesreport/send_pdf_to_mail') ?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        $.each(response, function(index, value) {
                            if (index != 'status') {
                                toastr["error"](value)
                                toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": false,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "500",
                                    "hideDuration": "500",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                }
                            }
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>

<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:30px;' /></center>";
        var copyright = "<center><?= $siteinfos->footer ?> | <?= $this->lang->line('balancefeesreport_hotline') ?> : <?= $siteinfos->phone ?></center>";
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:50px;' /></center>" +
            divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();

    $('#fromdate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
    });

    $('#todate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
    });

    $(function() {
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');
    });


    $(document).on('change', "#sectionID", function() {
        $('#load_balancefeesreport').html("");
        var sectionID = $(this).val();

        $('#studentID').html("<option value='0'>" + "<?= $this->lang->line("balancefeesreport_please_select") ?>" + "</option>");
        $('#studentID').val(0);

        var classesID = $('#classesID').val();
        if (sectionID != 0 && classesID != 0) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('balancefeesreport/getStudent') ?>",
                data: {
                    "classesID": classesID,
                    "sectionID": sectionID
                },
                dataType: "html",
                success: function(data) {
                    $('#studentID').html(data);
                }
            });
        }
    });

    $(document).on('click', '#get_duefeesreport,#get_classreport', function() {

        $('#load_balancefeesreport').html("");
        var maininvoice_type_v = $('#maininvoice_type_v').val();
        var classesID = $('#classesID').val();
        var numric_code = $('#numric_code').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var maininvoicestatus = $('#maininvoicestatus').val();
        var invoice_status = $('#invoice_status').val();
        var date_type = $('#date_type').val();
        var veiw_down = $('#veiw_down').val();
        var error = 0;

        var field = {
            "maininvoice_type_v": maininvoice_type_v,
            "classesID": classesID,
            "numric_code": numric_code,
            "start_date": start_date,
            "end_date": end_date,
            "maininvoicestatus": maininvoicestatus,
            "invoice_status": invoice_status,
            "date_type": date_type,
            "veiw_down": veiw_down,
        };

        if (field['maininvoice_type_v'] == null) {
            $('#maininvoice_type_vDiv').addClass('has-error');
            error++;
        } else {
            $('#maininvoice_type_vDiv').removeClass('has-error');
        }

        if (error == 0) {
            //makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        // debugger;
        $.ajax({
            type: 'GET',
            url: "<?= base_url('balancefeesreport/getBalanceFeesReport') ?>",
            data: passData,
            dataType: "html",
            success: function(data) {

                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if (response.status) {
            $("#loading").hide();
            $('#load_balancefeesreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#' + key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#' + key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#' + key).parent().addClass('has-error');
                }
            }
        }
    }
</script>


<script type="text/javascript">
    $('.datepicker').datepicker();
    $('.select2').select2();

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:30px;' /></center>";
        var copyright = "<center><?= $siteinfos->footer ?> | <?= $this->lang->line('attendanceoverviewreport_hotline') ?> : <?= $siteinfos->phone ?></center>";
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:50px;' /></center>" +
            divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }





    $(document).on('change', '#sectionID', function() {
        $('#load_attendanceoverview_report').html('');
        var usertype = 1;
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        if (sectionID == 0) {
            $('#userID').html('<option value="0">' + "<?= $this->lang->line("attendanceoverviewreport_please_select") ?>" + '</option>');
            $('#userID').val('0');
        } else if (sectionID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('attendanceoverviewreport/getStudent') ?>",
                data: {
                    "usertype": usertype,
                    'classesID': classesID,
                    'sectionID': sectionID
                },
                dataType: "html",
                success: function(data) {
                    $('#studentID').html(data);
                }
            });
        }
    });
</script>