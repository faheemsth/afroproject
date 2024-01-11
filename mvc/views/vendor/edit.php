
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-rss"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("vendor/index")?>"><?=$this->lang->line('menu_vendor')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_vendor')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="name" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $vendor->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                    </div>
                     <?php
                    if(form_error('tax_rate'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="tax_rate" class="col-sm-2 control-label">
                            Tax Rate
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="tax_rate" name="tax_rate" value="<?=set_value('tax_rate', $vendor->tax_rate)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('tax_rate'); ?>
                        </span>
                    </div>
                     <?php
                    if(form_error('address'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="address" class="col-sm-2 control-label">
                            Address
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address', $vendor->address)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('address'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('email'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $vendor->email)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('email'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('phone'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $vendor->phone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('phone'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('contact_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="contact_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_contact_name")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?=set_value('contact_name', $vendor->contact_name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('contact_name'); ?>
                        </span>
                    </div>

 

                    <?php
                    if(form_error('vendor_strn'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_strn" class="col-sm-2 control-label">
                            STRN
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_strn" name="vendor_strn" value="<?=set_value('vendor_strn', $vendor->vendor_strn)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_strn'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('vendor_ntn'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_ntn" class="col-sm-2 control-label">
                            NTN
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_ntn" name="vendor_ntn" value="<?=set_value('vendor_ntn', $vendor->vendor_ntn)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_ntn'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('vendor_bank'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_bank" class="col-sm-2 control-label">
                            Bank Name 
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_bank" name="vendor_bank" value="<?=set_value('vendor_bank', $vendor->vendor_bank)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_bank'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('vendor_branch_code'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_branch_code" class="col-sm-2 control-label">
                            Branck Code 
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_branch_code" name="vendor_branch_code" value="<?=set_value('vendor_branch_code', $vendor->vendor_branch_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_branch_code'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('vendor_account_title'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_account_title" class="col-sm-2 control-label">
                            Account Title
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_account_title" name="vendor_account_title" value="<?=set_value('vendor_account_title', $vendor->vendor_account_title)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_account_title'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('vendor_account_number'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="vendor_account_number" class="col-sm-2 control-label">
                            Account Number
                        </label>    
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vendor_account_number" name="vendor_account_number" value="<?=set_value('vendor_account_number', $vendor->vendor_account_number)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendor_account_number'); ?>
                        </span>
                    </div>



                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_vendor")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
