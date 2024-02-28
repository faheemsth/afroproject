<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_student')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                 
                <form type="get" action="">
                <div class="col-sm-12">
                     

                <div class="form-group col-sm-3" id="classesDiv">
                    <label>Status</span></label>
                    <?php


                        $array = get_student_status_type();
                         
                        echo form_dropdown("active", $array, set_value("active",$active), "id='active' class='form-control select2'");
                     ?>
                </div>
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
                    <label><?=$this->lang->line("attendanceoverviewreport_section")?></label>
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
                    <label>Name </label>
                    <input type="text" id="name" name="name" value="<?php echo set_value('name',$name);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Email </label>
                    <input type="text" id="email" name="email" value="<?php echo set_value('email',$email);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Phone </label>
                    <input type="text" id="phone" name="phone" value="<?php echo set_value('phone',$phone);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Address </label>
                    <input type="text" id="address" name="address" value="<?php echo set_value('address',$address);?>" class="form-control"/>
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
                    <label>Username </label>
                    <input type="text" id="username" name="username" value="<?php echo set_value('username',$username);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>No. of Installments </label>
                    <input type="text" id="InstallmentsID" name="InstallmentsID" value="<?php echo set_value('InstallmentsID',$InstallmentsID);?>" class="form-control"/>
                </div> 
                
                <div class="form-group col-sm-3">
                    <label>View/Download</label>

                    <?php
                    $veiw_down = array(
                        "view"       => 'View',
                        "download"   => 'Download'
                    );

                    echo form_dropdown("veiw_down", $veiw_down, set_value("veiw_down"), "id='veiw_down' class='form-control select2'");
                    ?>
                </div>


                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                    <a href="<?php echo base_url('student/search/');?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                </div>

            
                </div>
            </form> 
              
                <div class="clearfix"></div>

                
                <?php if(customCompute($students) > 0 ) { ?>
                      <hr>

                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_name')?></th>
                                                <th class="col-sm-2">
                                                    Degree
                                                </th>
                                                <th class="col-sm-2">
                                                    Semester
                                                </th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_roll')?></th>
                                                <th class="col-sm-2">Reg. No.</th>
                                                <?php if(permissionChecker('student_edit')) { ?>
                                                    <th class="col-sm-1"><?=$this->lang->line('student_status')?></th>
                                                <?php } ?>
                                                <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_photo')?>">
                                                        <?=profileimage($student->photo); ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('student_name')?>">
                                                        <?php echo $student->srname; ?> <br>
                                                        <?php echo $student->address; ?><br>
                                                        <?php echo $student->phone; ?>
                                                    </td>
                                                    <td data-title="Degree">
                                                        <?php echo $pluck_classes[$student->srclassesID]->classes; ?>
                                                    </td>
                                                    <td data-title="Semester">
                                                        <?php 
                                                    if ($student->srsectionID) {
                                                       echo $pluck_section[$student->srsectionID]->section;
                                                    }else{
                                                        echo "0";
                                                    }
                                                     ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('student_roll')?>">
                                                        <?php echo $student->srroll; ?>
                                                    </td>

                                                    <td data-title="Reg. No.">
                                                        <?php echo  $student->srregisterNO; ?>
                                                    </td>
                                                    
                                                    <?php if(permissionChecker('student_edit')) { ?>
                                                    <td data-title="<?=$this->lang->line('student_status')?>">
                                                        <div class="onoffswitch-small" id="<?=$student->srstudentID?>">
                                                            <input type="checkbox" id="myonoffswitch<?=$student->srstudentID?>" class="onoffswitch-small-checkbox" name="paypal_demo" <?php if($student->active === '1') echo "checked='checked'"; ?>>
                                                            <label for="myonoffswitch<?=$student->srstudentID?>" class="onoffswitch-small-label">
                                                                <span class="onoffswitch-small-inner"></span>
                                                                <span class="onoffswitch-small-switch"></span>
                                                            </label>
                                                        </div>
                                                        <input type="hidden" class="test">
                                                    </td>
                                                    <?php } ?>
                                                    <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                        <td data-title="<?=$this->lang->line('action')?>">
                                                            <?php
                                                                echo btn_view('student/view/'.$student->srstudentID."/".$student->classesID, $this->lang->line('view'));
                                                                if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                                    echo btn_edit('student/edit/'.$student->srstudentID."/".$student->classesID, $this->lang->line('edit'));
                                                                    echo btn_delete('student/delete/'.$student->srstudentID."/".$student->classesID, $this->lang->line('delete'));
                                                                     echo btn_status_show('student/update_status/'.$student->srstudentID."/".$student->classesID, 'Update Student Status');
                                                                }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            


                <?php } else { ?>
                     
                     <hr>
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_email')?></th>
                                                <?php if(permissionChecker('student_edit')) { ?>
                                                    <th class="col-sm-1"><?=$this->lang->line('student_status')?></th>
                                                <?php } ?>
                                                <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            
                <?php } ?>
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

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


