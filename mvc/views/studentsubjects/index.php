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
                <?php if(permissionChecker('studentsubjects_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('studentsubjects/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('studentsubjects_title')?></th>
                                <?php if(permissionChecker('studentsubjects_edit') || permissionChecker('studentsubjects_delete') || permissionChecker('studentsubjects_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($studentsubjectss)) {$i = 1; foreach($studentsubjectss as $studentsubjects) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('studentsubjects_title')?>">
                                        <?=$studentsubjects->title;?>
                                    </td>
                                    <?php if(permissionChecker('studentsubjects_edit') || permissionChecker('studentsubjects_delete') || permissionChecker('studentsubjects_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('studentsubjects/view/'.$studentsubjects->studentsubjectsID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('studentsubjects/edit/'.$studentsubjects->studentsubjectsID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('studentsubjects/delete/'.$studentsubjects->studentsubjectsID, $this->lang->line('delete')) ?>
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