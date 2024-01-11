<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> Result</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('result_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('result/add') ?>">
                            <i class="fa fa-plus"></i> 
                            Add a Result
                        </a>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-2">Result Name</th>
                                <th class="col-lg-1"> Result Year</th>
                                <th class="col-lg-2">Date</th>
                                <th class="col-lg-2">Note</th>
                                <th class="col-lg-1">Result Card</th>
                                <th class="col-lg-2">PDF Download</th>
                                <?php if(permissionChecker('result_edit') || permissionChecker('result_delete')) { ?>
                                <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($results)) {$i = 1; foreach($results as $result) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="Result Name">
                                        <?php echo $result->result; ?>
                                    </td>
                                    <td data-title="Exam  Year">
                                        <?php echo $result->result_year; ?>
                                    </td>
                                    <td data-title="Result Date">
                                        <?php echo date("d M Y", strtotime($result->date)); ?>
                                    </td>
                                    <td data-title="Result Note">
                                        <?php echo $result->note; ?>
                                    </td>
                                    <td data-title="Result Card">
                                        
                                <div class="onoffswitch-small" id="<?=$result->ResultID?>">
                                    <input type="checkbox" id="myonoffswitch<?=$result->ResultID?>" class="onoffswitch-small-checkbox is_active" name="paypal_demo" <?php if($result->active === '1') echo "checked='checked'"; ?>>
                                    <label for="myonoffswitch<?=$result->ResultID?>" class="onoffswitch-small-label">
                                        <span class="onoffswitch-small-inner"></span>
                                        <span class="onoffswitch-small-switch"></span>
                                    </label>
                                </div>
                                                    
                                    </td>
                                    <td data-title="PDF Download">
                                        
                                <div class="onoffswitch-small" id="<?=$result->ResultID?>is_download">
                                    <input type="checkbox" id="myonoffswitch<?=$result->ResultID?>is_download" class="onoffswitch-small-checkbox is_download" name="paypal_demo" <?php if($result->is_download === '1') echo "checked='checked'"; ?>>
                                    <label for="myonoffswitch<?=$result->ResultID?>is_download" class="onoffswitch-small-label">
                                        <span class="onoffswitch-small-inner"></span>
                                        <span class="onoffswitch-small-switch"></span>
                                    </label>
                                </div>
                                                    
                                    </td>
                                    <?php if(permissionChecker('result_edit') || permissionChecker('result_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?=btn_upload('result/uploadresultcard/'.$result->ResultID, 'Upload Result Cards') ?>
                                        <?=btn_edit('result/edit/'.$result->ResultID, $this->lang->line('edit')) ?>
                                        <?php if((int)$result->ResultID && !in_array($result->ResultID, $this->notdeleteArray)) {
                                            echo btn_delete('result/delete/'.$result->ResultID, $this->lang->line('delete'));
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
                url: "<?=base_url('result/active')?>",
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
                url: "<?=base_url('result/is_download')?>",
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