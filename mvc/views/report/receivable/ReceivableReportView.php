<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-balancefeesreport"></i>Receivable Report</h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"> Receivable Report</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Degree Wise Report</a></li>

                    <!-- <li><a   href="<?php echo base_url('opentbalance'); ?>">Opening Balance</a></li> -->
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <form role="form" method="get" action="<?php echo base_url('receivable/getReceivableReport'); ?>">
                            <div class="col-sm-12">
                                <div class="col-sm-4 about-imgform-group" id="classesDiv">
                                    <label>Degree</label>
                                    <?php
                                    $array = [];
                                    if (customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID[]", $array, set_value("classesID", $classesID), "id='classesID' multiple class='form-control select2'");
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
                                    <button id="get_duefeesreports" type="submit" class="btn btn-success" style="margin-top:23px;"> <?= $this->lang->line("balancefeesreport_submit") ?></button>
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

<!-- <div id="load_balancefeesreport"></div> -->


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
            // makingPostDataPreviousofAjaxCall(field);
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
            url: "<?= base_url('receivable/getReceivableReport') ?>",
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