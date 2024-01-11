 <style type="text/css">
        .setting-fieldset {
            border: 1px solid #DBDEE0 !important;
            padding: 15px !important;
            margin: 0 0 25px 0 !important;
            box-shadow: 0px 0px 0px 0px #000;
        }

        .setting-legend {
            font-size: 1.1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            color: #428BCA;
            padding: 5px 15px;
            border: 1px solid #DBDEE0 !important;
            margin: 0px;
        }
    </style>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-upload"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_import')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12"> 
                <?php if ($this->session->flashdata('msg')): ?>
                    <div class="callout callout-danger">
                      <h4>These data not inserted</h4>
                      <p><?=$this->session->flashdata('msg'); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="callout callout-danger">
                        <p><?=$this->session->flashdata('error'); ?></p>
                    </div>
                <?php endif; ?>
                <?php if(permissionChecker('teacher_add')) { ?>
                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Teacher</legend>
                
                <form action="<?=base_url('bulkimport/teacher_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_teacher")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the teacher sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload" name="csvFile" data-show-upload="false"
                                       data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_teacher.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
                </fieldset>
            <?php }?>
                <?php if(permissionChecker('asset_add')) { ?>
                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Asset</legend>
                
                <form action="<?=base_url('bulkimport/asset_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                           Add Asset
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the teacher sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control asset"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload assetUpload" name="csvFile" data-show-upload="false"
                                       data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_asset.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
                </fieldset>
            <?php }?>


                <?php if(permissionChecker('product_add')) { ?>
                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Product</legend>
                
                <form action="<?=base_url('bulkimport/product_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                           Add Product
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the teacher sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control product"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload productUpload" name="csvFile" data-show-upload="false"
                                       data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_product.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
                </fieldset>
            <?php }?>
                <?php if(permissionChecker('parents_add')) { ?>
                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Parent</legend>
                
                <form enctype="multipart/form-data" style="" action="<?=base_url('bulkimport/parent_bulkimport');?>" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_parent")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the parent sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control parent" id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload parentUpload" name="csvParent" />
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>

                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_parent.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
                </fieldset>
            <?php }?>
                <?php if(permissionChecker('user_add')) { ?>
                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add User</legend>
                
                <form enctype="multipart/form-data" style="" action="<?=base_url('bulkimport/user_bulkimport');?>" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label for="csvUser" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_user")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the user sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control user" id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload userUpload" name="csvUser" />
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>

                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_user.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>

                </fieldset>
                <?php }?>
                <?php if(permissionChecker('book_add')) { ?>

                <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Book</legend>
                <form enctype="multipart/form-data" style="" action="<?=base_url('bulkimport/book_bulkimport');?>" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label for="csvBook" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_book")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the book sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control bookImport" id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload bookUpload" name="csvBook" />
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>

                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_book.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
                </fieldset>
            <?php }?>
                <?php if(permissionChecker('student_add')) { ?>
                    <fieldset class="setting-fieldset">
                <legend class="setting-legend">Add Student</legend>
                
                <form action="<?=base_url('bulkimport/student_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_student")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the student sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control student"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload studentUpload" name="csvStudent" data-show-upload="false" data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_student.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>

                </fieldset>
                <?php }?>
                <?php if(permissionChecker('user_add')) { ?>
                    <fieldset class="setting-fieldset">
                <legend class="setting-legend">Change Student Status</legend>
                
                   <form action="<?=base_url('bulkimport/user_status_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_status_user")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the student sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control stdstatus"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload stdstatusUpload" name="csvstdstatus" data-show-upload="false" data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_student.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form> 
                </fieldset>
            <?php }?>
                <?php
                
                 if(permissionChecker('make_payment')) { ?>
                    <fieldset class="setting-fieldset">
                <legend class="setting-legend">Upload Cash Payment Voucher</legend>
            
                <form action="<?=base_url('bulkimport/cpv_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            Upload CPV
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the student sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control cpvstudent"  id="uploadCPV" placeholder="Choose File" disabled />
                        </div>
                        <input type="hidden" name="paymenttype" value="Cash">
                        <input type="hidden" name="pay_type" value="cpv">
                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload cpvUpload" name="cpvStudent" data-show-upload="false" data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_cpv.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form> </fieldset>

            <?php } ?>
                <?php if(permissionChecker('make_payment')) { ?>
                <?php if(count($reconcile)==0){?>
                    <fieldset class="setting-fieldset">
                <legend class="setting-legend">Upload Bank Payment Voucher</legend>
                <form action="<?=base_url('bulkimport/bpv_new_bulkimport_rollback');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            Upload BPV
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the student sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control bpvstudent"  id="uploadBPV" placeholder="Choose File" disabled />
                        </div>
                        <input type="hidden" name="paymenttype" value="Bank">
                        <input type="hidden" name="pay_type" value="bpv">
                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload bpvUpload" name="bpvStudent" data-show-upload="false" data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_bpv.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form></fieldset>
            <?php }else{?>
                    <a class="btn btn-danger" href="<?php echo base_url("bulkimport/get_reconcile_data")?>">Roll back</a>
            <?php
             }
             }
            ?>
             
                <?php if(permissionChecker('subject_add')) { ?>
                    <fieldset class="setting-fieldset">
                <legend class="setting-legend">Upload Subject</legend>
                <form action="<?=base_url('bulkimport/subject_bulkimport');?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group col-sm-6 has-error' >";
                        else
                            echo "<div class='form-group  col-sm-6 ' >";
                    ?>
                        <label for="classesID" class="col-sm-3 control-label">
                            Degree <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array();
                                $array[0] = 'Please Select';
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2' required");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>



                    <?php
                        if(form_error('sectionID'))
                            echo "<div class='form-group  col-sm-6  has-error' >";
                        else
                            echo "<div class='form-group  col-sm-6 ' >";
                    ?>
                        <label for="sectionID" class="col-sm-3 control-label">
                            Semester <span class="text-red">*</span>
                        </label>

                        <div class="col-sm-6">
                            <?php
                                $sectionArray = array(0 => 'Please Select');
                                

                                $sID = 0;
                                 

                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $sID), "id='sectionID' class='form-control select2' required");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sectionID'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("bulkimport_add_student")?>
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the student sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                        <div class="col-sm-3 col-xs-4 col-md-3">
                            <input class="form-control subject"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("bulkimport_upload")?></span>
                                <input id="uploadBtn" type="file" class="upload subjectUpload" name="csvSubject" data-show-upload="false" data-show-preview="false" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("bulkimport_import")?>" >
                        </div>
                        <div class="col-md-1 rep-mar">
                            <a class="btn btn-info" href="<?=base_url('assets/csv/sample_subject.csv')?>"><i class="fa fa-download"></i> <?=$this->lang->line("bulkimport_download_sample")?></a>
                        </div>
                    </div>
                </form>
            </fieldset>
                <?php }?>
                
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    document.getElementById("uploadBtn").onchange = function() {
        document.getElementById("uploadFile").value = this.value;
    };

    $( ".select2" ).select2();

    $('.assetUpload').on('change', function() {
        $('.asset').val($(this).val());
    });  
    $('.productUpload').on('change', function() {
        $('.product').val($(this).val());
    });  
    $('.parentUpload').on('change', function() {
        $('.parent').val($(this).val());
    });
    
    $('.userUpload').on('change', function() {
        $('.user').val($(this).val());
    });
    
    $('.bookUpload').on('change', function() {
        $('.bookImport').val($(this).val());
    });

    $('.studentUpload').on('change', function() {
        $('.student').val($(this).val());
    });

    $('.cpvUpload').on('change', function() {
        $('.cpvstudent').val($(this).val());
    });

    $('.bpvUpload').on('change', function() {
        $('.bpvstudent').val($(this).val());
    });
    
    $('.stdstatusUpload').on('change', function() {
        $('.stdstatus').val($(this).val());
    });

    

    $('.subjectUpload').on('change', function() {
        $('.subject').val($(this).val());
    });

$('#classesID').change(function(event) {
    var classesID = $(this).val();
    if(classesID === '0') {
        $('#sectionID').val(0);
    } else {
        $.ajax({
            async: false,
            type: 'POST',
            url: "<?=base_url('student/sectioncall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data);
            }
        });

        
    }
}); 
</script>
