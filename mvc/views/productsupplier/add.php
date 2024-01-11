
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productsupplier"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("productsupplier/index")?>"><?=$this->lang->line('menu_productsupplier')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_productsupplier')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('productsuppliercompanyname') ? 'has-error' : '' ?>" >
                        <label for="productsuppliercompanyname" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_companyname")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliercompanyname" name="productsuppliercompanyname" value="<?=set_value('productsuppliercompanyname')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliercompanyname'); ?>
                        </span>
                    </div>
                    
                    <div class="form-group <?=form_error('productsuppliername') ? 'has-error' : '' ?>" >
                        <label for="productsuppliername" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_suppliername")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliername" name="productsuppliername" value="<?=set_value('productsuppliername')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliername'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplieremail') ? 'has-error' : '' ?>" >
                        <label for="productsupplieremail" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplieremail" name="productsupplieremail" value="<?=set_value('productsupplieremail')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplieremail'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplierphone') ? 'has-error' : '' ?>" >
                        <label for="productsupplierphone" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplierphone" name="productsupplierphone" value="<?=set_value('productsupplierphone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplierphone'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplieraddress') ? 'has-error' : '' ?>" >
                        <label for="productsupplieraddress" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_address")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="productsupplieraddress" name="productsupplieraddress"><?=set_value('productsupplieraddress')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplieraddress'); ?>
                        </span>
                    </div>



                    <div class="form-group <?=form_error('productsupplier_strn') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_strn" class="col-sm-2 control-label">
                           STRN
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_strn" name="productsupplier_strn" value="<?=set_value('productsupplier_strn')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_strn'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_ntn') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_ntn" class="col-sm-2 control-label">
                           NTN
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_ntn" name="productsupplier_ntn" value="<?=set_value('productsupplier_ntn')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_ntn'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_bank') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_bank" class="col-sm-2 control-label">
                           Bank Name
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_bank" name="productsupplier_bank" value="<?=set_value('productsupplier_bank')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_bank'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_branch_code') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_branch_code" class="col-sm-2 control-label">
                           Branch Code
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_branch_code" name="productsupplier_branch_code" value="<?=set_value('productsupplier_branch_code')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_branch_code'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_account_title') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_account_title" class="col-sm-2 control-label">
                           Account Title
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_account_title" name="productsupplier_account_title" value="<?=set_value('productsupplier_account_title')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_account_title'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_account_number') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_account_number" class="col-sm-2 control-label">
                           Account Number
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_account_number" name="productsupplier_account_number" value="<?=set_value('productsupplier_account_number')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_account_number'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplier_quotation') ? 'has-error' : '' ?>" >
                        <label for="productsupplier_quotation" class="col-sm-2 control-label">
                            Quotation
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplier_quotation" name="productsupplier_quotation" value="<?=set_value('productsupplier_quotation')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplier_quotation'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_supplier")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>