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


                 
                <form type="get" action="">
                <div class="col-sm-12">
                     
                
                     <?php
                        if(form_error('cat_type'))
                            echo "<div class='form-group col-sm-4 has-error' >";
                        else
                            echo "<div class='form-group col-sm-4' >";
                    ?>
                        <label for="cat_type" class=" control-label">
                            Type <span class="text-red">*</span>
                        </label>
                         
                            <?php
                                $array22[0]  = 'Please Select';
                                $array2     = array_merge($array22,get_asset_cat_type());

                                
                              
                                echo form_dropdown("cat_type", $array2, set_value("cat_type",$cat_type), "id='cat_type' class='form-control select2'");
                            ?>
                         
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('cat_type'); ?>
                        </span>
                    </div>


                    <?php
                        if(form_error('asset_categoryID'))
                            echo "<div class='form-group col-sm-4 col-sm-4 has-error' >";
                        else
                            echo "<div class='form-group col-sm-4' >";
                    ?>
                        <label for="asset_categoryID" class=" control-label">
                            <?=$this->lang->line("asset_categoryID")?> <span class="text-red">*</span>
                        </label>
                         
                            <?php
                                $array[0] = $this->lang->line('asset_select_category');
                                if(customCompute($categories)) {
                                    foreach ($categories as $category) {
                                        $array[$category->asset_categoryID] = $category->category;
                                    }
                                }
                                echo form_dropdown("asset_categoryID", $array, set_value("asset_categoryID",$asset_categoryID), "id='asset_categoryID' class='form-control select2'");
                            ?>
                         
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('asset_categoryID'); ?>
                        </span>
                    </div>
 
                   <?php
                        if(form_error('status'))
                            echo "<div class='form-group col-sm-4 has-error' >";
                        else
                            echo "<div class='form-group col-sm-4' >";
                    ?>
                        <label for="status" class=" control-label">
                            <?=$this->lang->line("asset_status")?> 
                        </label>
                         
                            <?php
                                echo form_dropdown("status", array(0 => $this->lang->line('asset_select_status'), 1 => $this->lang->line('asset_status_checked_out'), 2 => $this->lang->line('asset_status_checked_in')), set_value("status",$status), "id='status' class='form-control select2'");
                            ?>
                         
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('status'); ?>
                        </span>
                    </div>

                 

                 

                <div class="form-group col-sm-4"  >
                    <label>Code </label>
                    <input type="text" id="asset_number" name="asset_number" value="<?php echo set_value('asset_number',$asset_number);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-4"  >
                    <label>Title </label>
                    <input type="text" id="description" name="description" value="<?php echo set_value('description',$description);?>" class="form-control"/>
                </div> 
 

                <div class="col-sm-4">
                    <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                    <a href="<?php echo base_url('asset/index/');?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                </div>

            
                </div>
            </form> 
              
                <div class="clearfix"></div>
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
                                <?php if(permissionChecker('requisitionapprove1')) {?>
                                <th>Approve 1</th>
                                <?php }?>
                                <?php if(permissionChecker('requisitionapprove2')) {?>
                                <th>Approve 2</th>
                                <?php }?> 
                                 
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
                                    
                                     <?php if(permissionChecker('requisitionapprove1')) {?>
                                    <td data-title="<?=$this->lang->line('requisition_grandtotal')?>">
                                        <?php if($asset_requisition->requisitionstatus == 0){?>
                                         <div class="onoffswitch-small" id="<?=$asset_requisition->asset_requisitionID?>">
                                          <input type="checkbox" id="myonoffswitch<?=$asset_requisition->asset_requisitionID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_first" name="aprove_status_first" <?php if($asset_requisition->aprove_status_first === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch<?=$asset_requisition->asset_requisitionID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>
                                      <?php  } if($asset_requisition->aprove_status_first === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($asset_requisition->firstAprove_usertypeID,$asset_requisition->firstAprove_userID)?>
                                            (<?=$usertypes[$asset_requisition->firstAprove_usertypeID]?>)
                                      <?php }?>
                                      
                                    </td>
                                     <?php } if(permissionChecker('requisitionapprove2')) {?>
                                    <td data-title="<?=$this->lang->line('requisition_grandtotal')?>">
                                        <?php if($asset_requisition->requisitionstatus == 0){?>
                                         <div class="onoffswitch-small" id="<?=$asset_requisition->asset_requisitionID?>">
                                          <input type="checkbox" id="myonoffswitch_second<?=$asset_requisition->asset_requisitionID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_second" name="aprove_status_second" <?php if($asset_requisition->aprove_status_second === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch_second<?=$asset_requisition->asset_requisitionID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>

                                      <?php } if($asset_requisition->aprove_status_second === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($asset_requisition->secondAprove_usertypeID,$asset_requisition->secondAprove_userID)?>
                                            (<?=$usertypes[$asset_requisition->secondAprove_usertypeID]?>)
                                      <?php }?>
                                    </td>



                                    <?php }?> 
                                  <?php if(permissionChecker('asset_requisition_edit') || permissionChecker('asset_requisition_delete') || permissionChecker('asset_requisition_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('asset_requisition/view/'.$asset_requisition->asset_requisitionID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('asset_requisition/edit/'.$asset_requisition->asset_requisitionID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset_requisition/delete/'.$asset_requisition->asset_requisitionID, $this->lang->line('delete')) ?>
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