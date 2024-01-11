<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-plug"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset_requisition')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('asset_requisition_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('asset_requisition/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
              <?php } ?>
              <span class="clearfix"> </span>

 
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('asset_requisition_assetID')?></th>
                                <th class="">Code</th>
                                <th class=""><?=$this->lang->line('asset_requisition_assigned_quantity')?></th>
                                <th class=""><?=$this->lang->line('asset_requisition_usertypeID')?></th>
                                <th class=""><?=$this->lang->line('asset_requisition_check_out_to')?></th>
                                 
                                 
                              <?php if(permissionChecker('asset_requisition_edit') || permissionChecker('asset_requisition_delete') || permissionChecker('asset_requisition_view')) { ?>
                                    <th class=""><?=$this->lang->line('action')?></th>
                              <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($asset_requisitions)) {$i = 1; foreach($asset_requisitions as $asset_requisition) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_requisition_assetID')?>">
                                        <?php echo $asset_requisition->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_requisition_assetID')?>">
                                        <?php   echo $asset_requisition->asset_number; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_requisition_assigned_quantity')?>">
                                        <?php echo $asset_requisition->assigned_quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_requisition_usertypeID')?>">
                                        <?php echo $asset_requisition->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_requisition_check_out_to')?>">
                                        <?php echo $asset_requisition->assigned_to; ?>
                                    </td>
                                     
                                  <?php if(permissionChecker('asset_requisition_edit') || permissionChecker('asset_requisition_delete') || permissionChecker('asset_requisition_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            
                                        <?php echo btn_add('asset_assignment/add/'.$asset_requisition->asset_requisitionID, 'Issue Asset');?>
 
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
    $('.select2').select2();
      $('#cat_type').change(function() {
            var cat_type = $(this).val();
            $('#asset_categoryID').html("<option value='0'>Please Select</option>");
            if(cat_type != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_category/getcategorylist')?>",
                    data: {'cat_type' : cat_type },
                    dataType: "html",
                    success: function(data) {
                        $('#asset_categoryID').html(data);
                    }
                });
            }
        });




  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox_first').click(function() {
     
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
              url: "<?=base_url('asset_requisition/active')?>",
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
  $('.onoffswitch-small-checkbox_second').click(function() {
        
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
              url: "<?=base_url('asset_requisition/active_second')?>",
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