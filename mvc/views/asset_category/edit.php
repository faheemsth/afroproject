
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-life-ring"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("asset_category/index")?>"><?=$this->lang->line('menu_asset_category')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_asset_category')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                    if(form_error('category'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="category" class="col-sm-3 control-label">
                            <?=$this->lang->line("asset_category")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="category" name="category" value="<?=set_value('category', $asset_category->category)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('category'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('category_abbr'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="category_abbr" class="col-sm-3 control-label">
                            <?=$this->lang->line("asset_category_abbr")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="category_abbr" name="category_abbr" value="<?=set_value('category_abbr', $asset_category->category_abbr)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('category_abbr'); ?>
                        </span>
                    </div>

                      
                     <?php
                        if(form_error('cat_type'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="cat_type" class="col-sm-3 control-label">
                            Type <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = get_asset_cat_type();
                              
                                echo form_dropdown("cat_type", $array, set_value("cat_type", $asset_category->cat_type), "id='cat_type' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('cat_type'); ?>
                        </span>
                    </div>



                    <?php 
                        if(form_error('credit_id')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="credit_id" class="col-sm-3 control-label">
                            Credit Account <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                             <?php
                                 
                                $chofaccArray = array('' => 'Please Select');
                                if(customCompute($chartofaccounts)) {
                                    foreach ($chartofaccounts as $ch) {
                                        $chofaccArray[$ch->id] = $ch->name .'-'.$ch->code;
                                    }
                                }
                                echo form_dropdown("credit_id", $chofaccArray, set_value("credit_id", $asset_category->credit_id), "id='credit_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('credit_id'); ?>
                        </span>
                    </div>

                   
                    <?php 
                        if(form_error('debit_id')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="debit_id" class="col-sm-3 control-label">
                            Debit Account <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                             <?php
                                 
                                $chofaccArray = array('' => 'Please Select');
                                if(customCompute($chartofaccounts)) {
                                    foreach ($chartofaccounts as $ch) {
                                        $chofaccArray[$ch->id] = $ch->name .'-'.$ch->code;
                                    }
                                }
                                echo form_dropdown("debit_id", $chofaccArray, set_value("debit_id", $asset_category->debit_id), "id='debit_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('debit_id'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_asset_category")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.select2').select2();
</script>