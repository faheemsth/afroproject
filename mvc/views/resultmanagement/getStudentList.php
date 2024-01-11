<div id="printablediv">
    <div class="box">
         
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="pull-left">
                                <?php 
                                    echo "Degree : ";
                                    echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('balancefeesreport_all_class');
                                ?>
                            </h5>                         
                            <h5 class="pull-right">
                                <?php
                                   echo $this->lang->line('certificatereport_section')." : ";
                                   echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('certificatereport_all_section');
                                ?>
                            </h5>                        
                        </div>
                    </div>
                    <?php if(customCompute($students)) { ?>
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1">#</th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_photo')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('certificatereport_name')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('certificatereport_class')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_section')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_roll')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_action')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($students as $student) { ?>
                                        <tr>
                                            <td data-title="#">
                                                <?=$i?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('certificatereport_photo')?>">
                                                <?=profileimage($student->photo)?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('certificatereport_name')?>">
                                                <?=$student->srname; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('certificatereport_class')?>"><?=$classes[$student->srclassesID]; ?></td>
                                            <td data-title="<?=$this->lang->line('certificatereport_section')?>"><?=$sections[$student->srsectionID]; ?></td>
                                           
                                            <td data-title="<?=$this->lang->line('certificatereport_roll')?>">
                                                <?=$student->srroll; ?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('certificatereport_action')?>">
                                                <?php  if ( permissionChecker('resultmanagement_edit')) {?>
                                                <a class="btn btn-warning btn-xs mrg" target="_blank" href="<?=base_url('resultmanagement/update_result/'.$student->srstudentID)?>" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                 
                                                <?php  } echo btn_pdfPreviewReport('resultmanagement_view','resultmanagement/print_preview/'.$student->srstudentID, $this->lang->line('view')) ?>
                                            </td>
                                       </tr>
                                    <?php $i++; } ?> 
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('certificatereport_student_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>
