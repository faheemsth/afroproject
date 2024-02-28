<?php 

// echo "<pre>";
// print_r($student_semester_wise);
// die();
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-balancefeesreport"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"> Receivable Report </li>
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
                        <form role="form" method="get" action="<?php echo base_url('receivable/getReceivableReport'); ?>">
                            <div class="col-sm-12">
                                <div class="col-sm-4 about-imgform-group" id="classesDiv">
                                    <label>Degree</label>
                                    <?php
                                    echo form_dropdown("classesID[]", $classes_arr, set_value("classesID", $classesID), "id='classesID' multiple class='form-control select2'");
                                    ?>
                                </div>

                                <div class="col-sm-4 form-group <?= form_error('numric_code') ? 'has-error' : '' ?>">
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

                                <div class="form-group col-sm-4">
                                    <label>Start Date</label>
                                    <input type="text" id="start_date" name="start_date" value="<?php echo set_value('start_date', $start_date); ?>" class="form-control datepicker" />
                                </div>
                            </div>

                            <div class="col-sm-12">
                               
                                <div class="form-group col-sm-4">
                                    <label>End Date</label>
                                    <input type="text" id="end_date" name="end_date" value="<?php echo set_value('end_date', $end_date); ?>" class="form-control datepicker" />
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
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <button id="get_duefeesreport" type="submit" class="btn btn-success" style="margin-top:23px;"> <?= $this->lang->line("balancefeesreport_submit") ?></button>
                                </div>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </div>

</div><!-- row -->
</div><!-- Body -->
</div><!-- /.box -->


<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            Receivable Report
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

                if (customCompute($data)) {
                    // echo "<pre>";
                    // print_r($invoices);
                    // die();
                ?>
                    <div class="col-sm-12">
                        <div id="hide-table">
                            <table class="table table-bordered">
                                <thead>

                                    <tr>
                                        <th><?= $this->lang->line('slno') ?></th>
                                        <th>Degree</th>
                                        <th>Semester</th>
                                        <th>No. of Students</th>
                                        <th>Opening Balance</th>
                                        <th>Total Fee</th>
                                        <th>Discount</th>
                                        <th>Net Fee</th>
                                        <?php  foreach ($invoices as $invtype)  { 
                                                if($invtype == 'invoice')
                                                    continue;    
                                            ?>
                                            <th><?= ucfirst($invtype) ?></th>
                                        <?php } ?>
                                        <th>Discounts on others</th>
                                        <th>Net Receivable</th>
                                        <th>Received</th>
                                        <th>Receivable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                            $count = 1; 
                                            $total_students = 0;
                                            $total_opening_bal = 0;
                                            $total_fee = 0;
                                            $total_discount = 0;
                                            $total_net_fee = 0;
                                            $total_total_other_charges = 0;
                                            $total_library_fine = 0;
                                            $total_hostel_fee = 0;
                                            $total_transport_fee = 0;
                                            $total_discount_others = 0;
                                            $total_net_receivable = 0;
                                            $total_received = 0;
                                            $total_receivable = 0;


                                            foreach($data as $sectionID => $detail){ 
                                                $other_discounts = 0;    
                                                $total_amounts = 0;
                                                $total_paid = 0;

                                                $total_students += $detail['no_of_students'];
                                                $total_opening_bal += $detail['opening_balance'];
                                                $total_fee += $detail['student_total_fees'];
                                                $total_discount += $detail['student_discounts'];
                                                $total_net_fee += $detail['student_net_fees'];
                                                $total_total_other_charges += 0;
                                                $total_library_fine += 0;
                                                $total_hostel_fee += 0;
                                                $total_transport_fee += 0;
                                                $total_discount_others += 0;
                                            ?>
                                            <tr>
                                                <td><?= $count++ ?></td>
                                                <td><?= $classes_arr[$detail["classesID"]] ?></td>
                                                <td><?= $sections[$detail["sectionID"]] ?></td>
                                                <td><?= $detail['no_of_students'] ?></td>
                                                <td><?= $detail['opening_balance'] ?></td>
                                                <td><?= $detail['student_total_fees'] ?></td>
                                                <td><?= $detail['student_discounts'] ?></td>
                                                <td><?= $detail['student_net_fees'] ?></td>
                                                <?php  foreach ($detail['invoices'] as $key => $inv_detail)  { 
                                                        $total_paid += $inv_detail['total_paid'];

                                                        if($key == 'invoice')
                                                        continue; 
                                                        
                                                        $other_discounts += $inv_detail['discount'];
                                                        $total_amounts += $inv_detail['amount'];        
                                                    ?>
                                                    <td><?= $inv_detail['amount'] ?></td>
                                                <?php } ?>
                                                <td><?= $other_discounts ?> <?php $total_discount_others += $other_discounts ?> </td>
                                                <td> <?php 
                                                        $net_receivable =   $detail['opening_balance'] + $detail['student_net_fees'] + $total_amounts - $other_discounts;  
                                                        echo $net_receivable;
                                                        $total_net_receivable += $net_receivable;
                                                    ?> </td>
                                                <td><?= $total_paid ?> <?php $total_received += $total_paid ?></td>
                                                <td><?= $net_receivable - $total_paid ?> <?php $total_receivable += ($net_receivable - $total_paid ); ?></td>
                                            </tr>
                                     <?php } ?>

                                     <tr>
                                        <th colspan="3">Total</th>
                                        <th><?= $total_students ?></th>
                                        <th><?= $total_opening_bal ?></th>
                                        <th><?= $total_fee ?></th>
                                        <th><?= $total_discount ?></th>
                                        <th><?= $total_net_fee ?></th>
                                        <?php foreach ($invoices as $invtype)  { 
                                                if($invtype == 'invoice')
                                                    continue;  ?>
                                            <th></th>
                                        <?php } ?>
                                        <th><?= $total_discount_others ?></th>
                                        <th><?= $total_net_receivable ?></th>
                                        <th><?= $total_received ?></th>
                                        <th><?= $total_receivable ?></th>
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