<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('subjectenrollment_add')) { ?>
                    <h5 class="page-header">
                        <?php if(!count($subjectenrollments)){ // CHECK ENROLLMENT
                            
                            if ($section->enrollment_date>date('Y-m-d')) {  // CHECK LAST DATE
                                if (count($invoice)>0) {  // CHECK PAID INVOICE
                            ?>
                                    <a href="<?php echo base_url('subjectenrollment/add') ?>">
                                        <i class="fa fa-plus"></i>
                                        <?=$this->lang->line('add_title')?>
                                    </a>
                            <?php 

                                }else{
                                    echo "Please Pay Your Invoice First";
                                }
                            }else{
                                echo "Last Date of Enrollment is ".date('d-m-Y',strtotime($section->enrollment_date));
                            }

                        }else{
                            echo "Already Enrolled";
                        }?>
                    </h5>
                <?php } ?>



                <?php if(($this->session->userdata('usertypeID') != 3)){?>


                <form type="get" action="">

                <div class="col-sm-12">
                     

                
                <div class="form-group col-sm-3" id="classesDiv">
                    <label>Degree</label>
                    <?php
                        $array = array("0" => 'Select Degree');
                        if(customCompute($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID",$classesID), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-3" id="sectionDiv">
                    <label>Semester</label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0">Please Select</option>
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
                    <label>Student</label>
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
                    <label>Name </label>
                    <input type="text" id="name" name="name" value="<?php echo set_value('name',$name);?>" class="form-control"/>
                </div> 
  

                <div class="form-group col-sm-3"  >
                    <label>Register NO </label>
                    <input type="text" id="registerNO" name="registerNO" value="<?php echo set_value('registerNO',$registerNO);?>" class="form-control"/>
                </div>

                <div class="form-group col-sm-3"  >
                    <label>Roll </label>
                    <input type="text" id="roll" name="roll" value="<?php echo set_value('roll',$roll);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Enrollement </label>
                    <Select id="enrollment" name="enrollment" class="select2 form-control">
                        <option value="enrollment" <?= isset($_GET['enrollment']) && $_GET['enrollment'] == 'enrollment' ? 'selected' : '' ?>>Enrolled</option>
                        <option value="not_enrollment" <?= isset($_GET['enrollment']) && $_GET['enrollment'] == 'not_enrollment' ? 'selected' : '' ?>>Not Enrolled</option>
                    </Select>
              
                </div> 


                

                

                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                    <a href="<?php echo base_url('subjectenrollment/index/');?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                </div>

            
                </div>
            </form> 
              
                <div class="clearfix"></div>
                <?php }?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2">Student</th>
                                <th class="col-sm-2">Parent Name</th>
                                <th class="col-sm-2">Class</th>
                                <th class="col-sm-1">Semester</th>
                                <th class="col-sm-2">Accounts No.</th>
                                <th class="col-sm-2">Register No.</th>
                                <th class="col-sm-2">Roll No.</th>
                                <th class="col-sm-4">Enrollment Date</th>
                                <?php if(permissionChecker('subjectenrollment_edit') || permissionChecker('subjectenrollment_delete') || permissionChecker('subjectenrollment_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($subjectenrollments)) {$i = 1; foreach($subjectenrollments as $subjectenrollment) {
                                 
                             ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subjectenrollment_title')?>">
                                        <?=$subjectenrollment->name;?>
                                    </td>
                                    <td data-title="Student parent name">
                                        <?=$parents[$subjectenrollment->parentID];?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subjectenrollment_title')?>">
                                        <?=$classes_pluck[$subjectenrollment->classesID]->classes;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subjectenrollment_title')?>">
                                        <?= isset($section_pluck[$subjectenrollment->subjectenrollment_sectionID]->section) ? $section_pluck[$subjectenrollment->subjectenrollment_sectionID]->section : (isset($section_pluck[$subjectenrollment->sectionID]->section) ? $section_pluck[$subjectenrollment->sectionID]->section : '') ?>
                                    </td>
                                    <td data-title="Accounts No">
                                        <?=$subjectenrollment->accounts_reg;?>
                                    </td>
                                    <td data-title="Register No">
                                        <?=$subjectenrollment->registerNO;?>
                                    </td>
                                    <td data-title="Register No">
                                        <?=$subjectenrollment->gcuf_roll;?>
                                    </td>
                                    <td data-title="Enrollment Date">
                                        <?=$subjectenrollment->subjectenrollment_created;?>
                                    </td>

                                    <?php if(permissionChecker('subjectenrollment_edit') || permissionChecker('subjectenrollment_delete') || permissionChecker('subjectenrollment_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('subjectenrollment/view/'.$subjectenrollment->subjectenrollmentID, $this->lang->line('view')) ?>
                                            <?php //echo btn_edit('subjectenrollment/edit/'.$subjectenrollment->subjectenrollmentID, $this->lang->line('edit')) ?>
                                            <?php //echo btn_delete('subjectenrollment/delete/'.$subjectenrollment->subjectenrollmentID, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(".select2").select2();



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

     

    // $('#classesID').change(function() {
    //     var classesID = $(this).val();
    //     if(classesID == 0) {
    //         $('#hide-table').hide();
    //         $('.nav-tabs-custom').hide();
    //     } else {
    //         $.ajax({
    //             type: 'POST',
    //             url: "<?=base_url('student/student_list')?>",
    //             data: "id=" + classesID,
    //             dataType: "html",
    //             success: function(data) {
    //                 window.location.href = data;
    //             }
    //         });
    //     }
    // });


    var status = '';
    var id = 0;
    $('.onoffswitch-small-checkbox').click(function() {
        if($(this).prop('checked')) {
            status = 'chacked';
            id = $(this).parent().attr("id");
        } else {
            status = 'unchacked';
            id = $(this).parent().attr("id");
        }

        if((status != '' || status != null) && (id !='')) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/active')?>",
                data: "id=" + id + "&status=" + status,
                dataType: "html",
                success: function(data) {
                    if(data == 'Success') {
                        toastr["success"]("Success")
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
                    } else {
                        toastr["error"]("Error")
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
                }
            });
        }
    });
</script>


