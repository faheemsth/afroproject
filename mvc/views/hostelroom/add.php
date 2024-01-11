
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("hostelroom/index")?>"><?=$this->lang->line('menu_hostelroom')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_hostelroom')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                     <?php 
                        if(form_error('hostelID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="hostelID" class="col-sm-2 control-label">
                            <?=$this->lang->line("hmember_hname")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array[0] = $this->lang->line("hmember_select_hostel_name");
                                foreach ($hostels as $hostel) {
                                    $array[$hostel->hostelID] = $hostel->name;
                                }
                                echo form_dropdown("hostelID", $array, set_value("hostelID"), "id='hostelID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hostelID'); ?>
                        </span>
                    </div>


                    <div class="form-group <?=form_error('hostelroom') ? 'has-error' : '' ?>" >
                        <label for="hostelroom" class="col-sm-2 control-label">
                            Room Number<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="hostelroom" name="hostelroom" value="<?=set_value('hostelroom')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hostelroom'); ?>
                        </span>
                    </div>


                    <div class="form-group <?=form_error('monthlyfee') ? 'has-error' : '' ?>" >
                        <label for="monthlyfee" class="col-sm-2 control-label">
                           Monthly Fee<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="monthlyfee" name="monthlyfee" value="<?=set_value('monthlyfee')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('monthlyfee'); ?>
                        </span>
                    </div>


                    <div class="form-group <?=form_error('roomcapcity') ? 'has-error' : '' ?>" >
                        <label for="roomcapcity" class="col-sm-2 control-label">
                            Room Capcity<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="roomcapcity" name="roomcapcity" value="<?=set_value('roomcapcity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('roomcapcity'); ?>
                        </span>
                    </div>


                    <div class="form-group <?=form_error('security') ? 'has-error' : '' ?>" >
                        <label for="security" class="col-sm-2 control-label">
                            Security<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="security" name="security" value="<?=set_value('security')?>" >
                        </div> 
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('security'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_hostelroom")?>" >
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
