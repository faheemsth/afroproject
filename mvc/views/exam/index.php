<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_exam')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('exam_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('exam/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('exam_name')?></th>
                                <th class="col-lg-1"> Exam Year</th>
                                <th class="col-lg-2"><?=$this->lang->line('exam_date')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('exam_note')?></th>
                                <th class="col-lg-1">Exam Slip</th>
                                <th class="col-lg-2">PDF Download</th>
                                <?php if(permissionChecker('exam_edit') || permissionChecker('exam_delete')) { ?>
                                <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($exams)) {$i = 1; foreach($exams as $exam) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_name')?>">
                                        <?php echo $exam->exam; ?>
                                    </td>
                                    <td data-title="Exam  Year">
                                        <?php echo $exam->exam_year; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_date')?>">
                                        <?php echo date("d M Y", strtotime($exam->date)); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_note')?>">
                                        <?php echo $exam->note; ?>
                                    </td>
                                    <td data-title="Exam Slip">
                                        
                                <div class="onoffswitch-small" id="<?=$exam->examID?>">
                                    <input type="checkbox" id="myonoffswitch<?=$exam->examID?>" class="onoffswitch-small-checkbox is_active" name="paypal_demo" <?php if($exam->active === '1') echo "checked='checked'"; ?>>
                                    <label for="myonoffswitch<?=$exam->examID?>" class="onoffswitch-small-label">
                                        <span class="onoffswitch-small-inner"></span>
                                        <span class="onoffswitch-small-switch"></span>
                                    </label>
                                </div>
                                                    
                                    </td>
                                    <td data-title="PDF Download">
                                        
                                <div class="onoffswitch-small" id="<?=$exam->examID?>is_download">
                                    <input type="checkbox" id="myonoffswitch<?=$exam->examID?>is_download" class="onoffswitch-small-checkbox is_download" name="paypal_demo" <?php if($exam->is_download === '1') echo "checked='checked'"; ?>>
                                    <label for="myonoffswitch<?=$exam->examID?>is_download" class="onoffswitch-small-label">
                                        <span class="onoffswitch-small-inner"></span>
                                        <span class="onoffswitch-small-switch"></span>
                                    </label>
                                </div>
                                                    
                                    </td>
                                    <?php if(permissionChecker('exam_edit') || permissionChecker('exam_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?=btn_upload('exam/uploadadmitcard/'.$exam->examID, 'Upload Admit Cards') ?>
                                        <?=btn_edit('exam/edit/'.$exam->examID, $this->lang->line('edit')) ?>
                                        <?php if((int)$exam->examID && !in_array($exam->examID, $this->notdeleteArray)) {
                                            echo btn_delete('exam/delete/'.$exam->examID, $this->lang->line('delete'));
                                        } ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>


            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    
    var status = '';
    var id = 0;
    $('.is_active').click(function() {
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
                url: "<?=base_url('exam/active')?>",
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
    
    var status = '';
    var id = 0;
    $('.is_download').click(function() {
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
                url: "<?=base_url('exam/is_download')?>",
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