<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('hostelroom_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('hostelroom/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2">Hostel Name</th>
                                <th class="col-sm-2">Hostel Room</th>
                                <th class="col-sm-2">Monthly Fee</th>
                                <th class="col-sm-2">Room Capcity</th>
                                <th class="col-sm-2">Capcity Occopied</th>
                                <th class="col-sm-2">Security</th> 
                                <?php if(permissionChecker('hostelroom_edit') || permissionChecker('hostelroom_delete') || permissionChecker('hostelroom_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($hostelrooms)) {$i = 1; foreach($hostelrooms as $hostelroom) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?php  
                                    echo $hostel[$hostelroom->hostelID]->name ;
                                  ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?=$hostelroom->hostelroom;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?=$hostelroom->monthlyfee;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?=$hostelroom->roomcapcity;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?=$hostelroom->capcityoccopied;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hostelroom_title')?>">
                                        <?=$hostelroom->security;?>
                                    </td>
                                    <?php if(permissionChecker('hostelroom_edit') || permissionChecker('hostelroom_delete') || permissionChecker('hostelroom_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('hostelroom/view/'.$hostelroom->hostelroomID, $this->lang->line('view')) ?>
                                            <?php echo btn_delete('hostelroom/delete/'.$hostelroom->hostelroomID, $this->lang->line('delete')) ?>
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