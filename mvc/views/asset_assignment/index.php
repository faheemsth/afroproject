<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-plug"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset_assignment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('asset_assignment_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('asset_assignment/add') ?>">
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
                                <th class=""><?=$this->lang->line('asset_assignment_assetID')?></th>
                                <th class="">Code</th>
                                <th class=""><?=$this->lang->line('asset_assignment_assigned_quantity')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_usertypeID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_to')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_due_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_in_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_status')?></th>
                              <?php if(permissionChecker('asset_assignment_edit') || permissionChecker('asset_assignment_delete') || permissionChecker('asset_assignment_view')) { ?>
                                    <th class=""><?=$this->lang->line('action')?></th>
                              <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($asset_assignments)) {$i = 1; foreach($asset_assignments as $asset_assignment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assetID')?>">
                                        <?php echo $asset_assignment->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assetID')?>">
                                        <?php   echo $asset_assignment->asset_number; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assigned_quantity')?>">
                                        <?php echo $asset_assignment->assigned_quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_usertypeID')?>">
                                        <?php echo $asset_assignment->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_to')?>">
                                        <?php echo $asset_assignment->assigned_to; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_due_date')?>">
                                        <?php echo isset($asset_assignment->due_date) ? date('d M Y', strtotime($asset_assignment->due_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_date')?>">
                                        <?php echo isset($asset_assignment->check_out_date) ? date('d M Y', strtotime($asset_assignment->check_out_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_in_date')?>">
                                        <?php echo isset($asset_assignment->check_in_date) ? date('d M Y', strtotime($asset_assignment->check_in_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_status')?>">
                                        <?php
                                            if($asset_assignment->status == 1) {
                                                echo $this->lang->line('asset_assignment_checked_out');
                                            } elseif($asset_assignment->status == 2) {
                                                echo $this->lang->line('asset_assignment_in_storage');
                                            }
                                        ?>
                                    </td>
                                  <?php if(permissionChecker('asset_assignment_edit') || permissionChecker('asset_assignment_delete') || permissionChecker('asset_assignment_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('asset_assignment/view/'.$asset_assignment->asset_assignmentID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('asset_assignment/edit/'.$asset_assignment->asset_assignmentID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset_assignment/delete/'.$asset_assignment->asset_assignmentID, $this->lang->line('delete')) ?>
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
</script>