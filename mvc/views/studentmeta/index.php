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
                <?php if(permissionChecker('studentmeta_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('studentmeta/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('studentmeta_title')?></th>
                                <?php if(permissionChecker('studentmeta_edit') || permissionChecker('studentmeta_delete') || permissionChecker('studentmeta_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($studentmetas)) {$i = 1; foreach($studentmetas as $studentmeta) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('studentmeta_title')?>">
                                        <?=$studentmeta->title;?>
                                    </td>
                                    <?php if(permissionChecker('studentmeta_edit') || permissionChecker('studentmeta_delete') || permissionChecker('studentmeta_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('studentmeta/view/'.$studentmeta->, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('studentmeta/edit/'.$studentmeta->, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('studentmeta/delete/'.$studentmeta->, $this->lang->line('delete')) ?>
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