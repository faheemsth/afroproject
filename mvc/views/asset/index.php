<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-fax"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php

                   if(permissionChecker('asset_add')) {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('asset/add') ?>">
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
                            Type 
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
                            <?=$this->lang->line("asset_categoryID")?> 
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
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('asset_serial')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_description')?></th>
                                <th class="col-sm-1">Code</th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_categoryID')?></th>
                                <!--New Code-->
                                <th class="col-sm-1"><?=$this->lang->line('asset_quantity')?></th>
                                <!--New Code-->
                                <?php if(permissionChecker('asset_edit') || permissionChecker('asset_delete') || permissionChecker('asset_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($assets)) {$i = 1; foreach($assets as $asset) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_serial')?>">
                                        <?php echo $asset->serial; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_description')?>">
                                        <?php echo $asset->description; ?>
                                    </td>
                                    <td data-title="Code">
                                        <?php echo $asset->asset_number; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_status')?>">
                                        <?php
                                            if($asset->status==1) {
                                                echo $this->lang->line('asset_status_checked_out');
                                            } else {
                                                echo $this->lang->line('asset_status_checked_in');
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_categoryID')?>">
                                        <?php echo $asset->category; ?>
                                    </td>
                                    <!--New Code-->
                                    <td data-title="<?=$this->lang->line('quantity')?>">
                                        <?php echo $asset->quantity; ?>
                                    </td>
                                    <!--New Code-->
                                    <?php if(permissionChecker('asset_edit') || permissionChecker('asset_delete') || permissionChecker('asset_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_flat_pdfPreviewReport('asset_view','asset/ledger/'.$asset->assetID, 'Ledger') ?>
                                            <?php echo btn_view('asset/view/'.$asset->assetID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('asset/edit/'.$asset->assetID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset/delete/'.$asset->assetID, $this->lang->line('delete')) ?>
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