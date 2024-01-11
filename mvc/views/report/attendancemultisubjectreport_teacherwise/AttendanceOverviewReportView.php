<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-attendanceoverviewreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_attendanceoverviewreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="usertypeDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_reportfor")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array(
                            "0" => $this->lang->line("attendanceoverviewreport_please_select"),
                            "1" => $this->lang->line("attendanceoverviewreport_student"),
                        );
                        echo form_dropdown("usertype", $array, set_value("usertype"), "id='usertype' class='form-control select2'");
                     ?>
                </div>


                <div class="form-group col-sm-4" id="teacherDiv">
                    <label>Teachers <span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => 'Select teacher');
                        if(customCompute($teachers)) {
                            foreach ($teachers as $key => $teacher) {
                                 $array[$key] = $teacher;
                            }
                        }
                        echo form_dropdown("teacherID", $array, set_value("teacherID"), "id='teacherID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label>Semesters <span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => 'Select Section');
                        if(customCompute($sections)) {
                            foreach ($sections as $key => $section) {
                                 $array[$section] = $section;
                            }
                        }
                        echo form_dropdown("sectionID", $array, set_value("sectionID"), "id='sectionID' multiple='true' class='form-control select2'");
                     ?>
                </div>

                

               <div class="form-group col-sm-4" id="subjectDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_subject")?> </label>
                    <select multiple="" id="subjectID" name="subjectID[]" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-2" id="startdateDiv">
                    <label>Start Date <span class="text-red"> * </span></label>
                    <input type="text" id="startdate" name="startdate" class="form-control datepicker"/>
                </div>

                <div class="form-group col-sm-2" id="enddateDiv">
                    <label>End Date <span class="text-red"> * </span></label>
                    <input type="text" id="enddate" name="enddate" class="form-control datepicker"/>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-sm-4">
                    <button id="get_attendanceoverviewreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("attendanceoverviewreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_attendanceoverview_report"></div>

<?php
    $startDate = $schoolyearsessionobj->startingmonth.'-'.$schoolyearsessionobj->startingyear;
    $endDate   = $schoolyearsessionobj->endingmonth.'-'.$schoolyearsessionobj->endingyear;
?>

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

    $(document).ready(function() {
        $("#usertype").val('0');
        $("#teacherDiv").val('0');
        $("#subjectID").val('0');
        $("#sectionID").val('0');
        
       

        $("#teacherDiv").hide("slow");
        $("#startdateDiv").hide("slow");
        $("#enddateDiv").hide("slow");
        $("#subjectDiv").hide("slow");
        $("#sectionDiv").hide('slow');
    });

    $(document).on('change','#usertype', function() {
        $('#load_attendanceoverview_report').html('');
        var usertype = $(this).val();

        $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
        $('#userID').val('0');
        if(usertype == 0) {
            $("#teacherDiv").hide("slow"); 
            $("#sectionDiv").hide('slow');
        $("#startdateDiv").hide("slow");
        $("#enddateDiv").hide("slow");
           
            $("#subjectDiv").hide("slow");
        } else if(usertype == 1) {
            $("#teacherDiv").show("slow");
            $("#sectionDiv").show('slow');
            
            $("#startdateDiv").show("slow");
            $("#enddateDiv").show("slow");
           
            <?php if($siteinfos->attendance == 'subject') { ?>
                $('#subjectDiv').show()
            <?php } else { ?>
                $('#subjectDiv').hide()
            <?php } ?>
        } else if(usertype == 2) {
            $("#teacherDiv").hide("slow");
            $("#sectionDiv").hide('slow');
            $('#subjectDiv').hide('slow');
            $("#monthDiv").show("slow");
           
        } else if(usertype == 3) {
            $("#teacherDiv").hide("slow");
            $("#sectionDiv").hide('slow');
            $('#subjectDiv').hide('slow');
            $("#monthDiv").show("slow");
        }

    });

    $(document).on('change', '#sectionID', function() {
        $('#load_attendanceoverview_report').html('');
        var teacherID = $('#teacherID').val();
        var sectionIDs = $(this).val();
       if(teacherID == ''){
        alert('Please select teacher');
        $(this).val('');
        return false;
       }

        $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
        $('#userID').val('0');
        if(teacherID == 0) {
            $('#subjectID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#subjectID').val('0');
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#userID').val('0');
            $('#monthID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancemultisubjectreport_teacherwise/getSubjects')?>",
                data: {"teacherID" : teacherID, sectionIDs},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });

        }
    });


    $(document).on('change', '#subjectID', function() {
        $('#load_attendanceoverview_report').html('');
    });
    
    $(document).on('change', '#enddate', function() {
        $('#load_attendanceoverview_report').html('');
    });

    $(document).on('click', '#get_attendanceoverviewreport', function() {
        $('#load_attendanceoverview_report').html('');
        var error = 0;
        var field = {
            'usertype'  : $('#usertype').val(),
            'teacherID' : $('#teacherID').val(),
            'startdate' : $('#startdate').val(),
            'enddate'   : $('#enddate').val(),
            'subjectID' : $('#subjectID').val(),
        };

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function validation_checker(field, error){
        if (field['usertype'] == '0') {
            $('#usertypeDiv').addClass('has-error');
            error++;
        } else {
            $('#usertypeDiv').removeClass('has-error');
        }

        if (field['startdate'] == '') {
            $('#startdateDiv').addClass('has-error');
            error++;
        } else {
            $('#startdateDiv').removeClass('has-error');
        }

        if (field['enddate'] == '') {
            $('#enddateDiv').addClass('has-error');
            error++;
        } else {
            $('#enddateDiv').removeClass('has-error');
        }
         
    
        if(field['usertype'] == '1') {
            if (field['classesID'] == '0') {
                $('#classesDiv').addClass('has-error');
                error++;
            } else {
                $('#classesDiv').removeClass('has-error');
            }

            <?php if($siteinfos->attendance == 'subject') { ?>
                if (field['subjectID'] == '0') {
                    $('#subjectDiv').addClass('has-error');
                    error++;
                } else {
                    $('#subjectDiv').removeClass('has-error');
                }

            <?php } ?>
            }

        return error;
    }

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        console.log(passData);
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendancemultisubjectreport_teacherwise/getAttendacneOverviewReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_attendanceoverview_report').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }
    

</script>
