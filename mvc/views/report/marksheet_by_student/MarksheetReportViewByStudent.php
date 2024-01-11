<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-marksheetreport"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"> <?= $this->lang->line('menu_marksheetreport') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="examDiv">
                    <label><?= $this->lang->line("marksheetreport_exam") ?><span class="text-red"> * </span></label>
                    <?php
                    $examsArray['0'] = $this->lang->line("marksheetreport_please_select");
                    if (customCompute($exams)) {
                        foreach ($exams as $examKey => $exam) {
                            $examsArray[$examKey] = $exam;
                        }
                    }
                    echo form_dropdown("examID", $examsArray, set_value("examID"), "id='examID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="studentDiv">
                    <label>Student <span class="text-red"> * </span></label>
                    <?php
                    $studentArray[0] = 'Select Registration No';
                    if (customCompute($students)) {
                        foreach ($students as $studentID => $registionNo) {
                            $studentArray[$studentID] = $registionNo;
                        }
                    }
                    echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                    ?>
                </div>
                <div class="col-sm-4">
                    <button id="get_terminalreport" class="btn btn-success" style="margin-top:23px;"> <?= $this->lang->line("marksheetreport_submit") ?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_terminalreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:30px;' /></center>";
        var copyright = "<center><?= $siteinfos->footer ?> | <?= $this->lang->line('marksheetreport_hotline') ?> : <?= $siteinfos->phone ?></center>";
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            "<center><img src='<?= base_url('uploads/images/' . $siteinfos->photo) ?>' style='width:50px;' /></center>" +
            divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();

    $(function() {
        $("#examID").val(0);
    });

    $(document).on('change', "#examID", function() {
        $('#load_terminalreport').html("");
    });

    $(document).on('change', "#sectionID", function() {
        $('#load_terminalreport').html("");
    });

    $(document).on('click', '#get_terminalreport', function() {
        $('#load_terminalreport').html("");
        var error = 0;
        var field = {
            'examID': $('#examID').val(),
            'studentID': $('#studentID').val()
        };

        if (field['examID'] == 0) {
            $('#examDiv').addClass('has-error');
            error++;
        } else {
            $('#examDiv').removeClass('has-error');
        }

        if (field['studentID'] == 0) {
            $('#studentDiv').addClass('has-error');
            error++;
        } else {
            $('#studentDiv').removeClass('has-error');
        }

        if (error == 0) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('marksheetreport_by_student/getMarksheetreport') ?>",
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
            $('#load_terminalreport').html(response.render);
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