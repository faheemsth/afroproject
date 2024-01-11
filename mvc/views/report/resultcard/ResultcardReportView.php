<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-admitcardreport"></i> Result Card Report</h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"> Result Card Report</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">

                <div class="form-group col-sm-4" id="classesDiv">
                    <label>Degree <span class="text-red"> * </span></label>
                    <?php
                    if (count($classes) > 1) {
                        # code...

                        $classesArray = array(
                            "0" => 'Please Select',
                        );
                    }
                    if (customCompute($classes)) {
                        foreach ($classes as $classaKey => $classa) {
                            $classesArray[$classa->classesID] = $classa->classes;
                        }
                    }

                    echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="resultDiv">
                    <label>Result <span class="text-red"> * </span></label>
                    <?php

                    $resultArray = array(
                        "0" => 'Please Select',
                    );
                    foreach ($results as $e) {
                        $resultArray[$e->resultID]    =   $e->result;
                    }
                    echo form_dropdown("resultID", $resultArray, set_value("resultID"), "id='resultID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label>Section <span class="text-red"> * </span></label>
                    <?php
                    if (isset($sections) and count($sections)) {
                        $sectionArray  = array(
                            $sections[0]->sectionID => $sections[0]->section
                        );
                    } else {
                        $sectionArray = array(
                            "0" => 'Please Select',
                        );
                    }
                    echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                    ?>
                </div>

                
                <div class="form-group col-sm-4" id="studentDiv">
                    <label>Student</label>
                    <?php
                    $studentArray = array(
                        "0" => 'Please Select',
                    );
                    if (isset($students) and count($students)) {
                        $studentArray  = array(
                            $students[0]->studentID => $students[0]->name
                        );
                    } else {
                        $studentArray = array(
                            "0" => 'Please Select',
                        );
                    }
                    echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                    ?>
                </div>



                <div class="col-sm-4">
                    <button id="get_resultcardreport" class="btn btn-success" style="margin-top:23px;"> Get Report</button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_resultcardreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();
   
    $(document).on('change', "#resultID", function() {
        $('#load_resultcardreport').html("");
        var resultID = $(this).val();
        if (resultID == '0') {
            $("#sectionDiv").hide('slow');
            $('#studentDiv').hide('slow');
        } else {
            $("#sectionDiv").show('slow');
            $('#studentDiv').show('slow');
        }
    });

    $(document).on('change', "#classesID", function() {
        $('#load_resultcardreport').html("");
        var classesID = $(this).val();
        if (classesID == '0') {
            $('#sectionID').html('<option value="0">' + "<?= 'Please Select' ?>" + '</option>');
            $('#sectionID').val('0');
            $('#studentID').html('<option value="0">' + "<?= 'Please Select' ?>" + '</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('resultcardreport/getSection') ?>",
                data: {
                    "classesID": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('resultcardreport/getReport') ?>",
                data: {
                    "classesID": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#resultID').html(data);
                }
            });
        }
    });



    $(document).on('change', "#sectionID", function() {
        $('#load_resultcardreport').html("");
        var sectionID = $(this).val();
        var classesID = $("#classesID").val();
        if (sectionID == '0') {
            $('#studentID').html('<option value="0">' + "<?= 'Please Select' ?>" + '</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('admitcardreport/getStudent') ?>",
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


    $(document).on('change', "#studentID", function() {
        $('#load_resultcardreport').html('');
    });

    $(document).on('click', '#get_resultcardreport', function() {
        $('#load_resultcardreport').html('');
        var passData;
        var error = 0;
        var field = {
            'resultID': $("#resultID").val(),
            'classesID': $('#classesID').val(),
            'sectionID': $('#sectionID').val(),
            'studentID': $('#studentID').val(),
        };

        if (field['resultID'] == 0) {
            $('#resultDiv').addClass('has-error');
            error++;
        } else {
            $('#resultDiv').removeClass('has-error');
        }

        if (field['classesID'] == 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }
        if (field['sectionID'] == 0) {
            $('#sectionDiv').addClass('has-error');
            error++;
        } else {
            $('#sectionDiv').removeClass('has-error');
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
            url: "<?= base_url('resultcardreport/getResultcardReport') ?>",
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
            $('#load_resultcardreport').html(response.render);
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