
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-feetypes"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("feetypes/index")?>"><?=$this->lang->line('menu_feetypes')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_feetypes')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('feetypes')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="feetypes" class="col-sm-2 control-label">
                            <?=$this->lang->line("feetypes_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="feetypes" name="feetypes" value="<?=set_value('feetypes')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('feetypes'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('credit_id')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="credit_id" class="col-sm-2 control-label">
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
                                echo form_dropdown("credit_id", $chofaccArray, set_value("credit_id"), "id='credit_id' class='form-control select2'");
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
                        <label for="debit_id" class="col-sm-2 control-label">
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
                                echo form_dropdown("debit_id", $chofaccArray, set_value("debit_id"), "id='debit_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('debit_id'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("feetypes_note")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="note" name="note"><?=set_value('note')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('monthly')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="monthly" class="col-sm-2 control-label">
                            <?=$this->lang->line("feetypes_monthly")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="checkbox" name="monthly" value="1" <?=set_radio("monthly", 1)?> >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('monthly'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_feetype")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#debit_id').select2();
    $('#credit_id').select2();
</script>