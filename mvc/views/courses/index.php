<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calculator "></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('salary_template_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('courses/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
                <?php } ?>


                 <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true">All</a></li>
                            <?php foreach ($allyears as  $year) {
                                echo '<li class=""><a data-toggle="tab" href="#tab'.$year->basic_salary.$year->basic_salary .'" aria-expanded="false">'. $this->lang->line("student_section")." ".$year->basic_salary.'</a></li>';
                            } ?>
                        </ul>



                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_salary_grades')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_basic_salary')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_overtime_rate_not_hour')?></th>
                                <?php if(permissionChecker('salary_template_edit') || permissionChecker('salary_template_delete') || permissionChecker('salary_template_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($salary_templatesall)) {$i = 1; foreach($salary_templatesall as $salary_template) { 
                            // echo $salary_template->salary_templateID;
                            $salary_templateID = $salary_template->salary_templateID;
                            $sql ="SELECT * FROM course_option WHERE salary_templateID = '$salary_templateID'";
                            $query =  $this->db->query($sql);
                            // var_dump($query->result());
                            $salaryoptions = $query->result();
                            $netsalary = 0;
                            foreach ($salaryoptions as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    // echo $netsalary;
                                }
                            }
                            // var_dump($netsalary);
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_salary_grades')?>">
                                        <?=$salary_template->classes?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_basic_salary')?>">
                                        <?=$salary_template->basic_salary?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_overtime_rate_not_hour')?>">
                                        <?=$netsalary?>
                                        <!--$salary_template->overtime_rate-->
                                    </td>
                                    <?php if(permissionChecker('salary_template_edit') || permissionChecker('salary_template_delete') || permissionChecker('salary_template_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <a href="<?php echo base_url();?>/courses/view/<?php echo $salary_template->salary_templateID; ?>" class="btn btn-success btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-check-square-o"></i></a>
                                            <?php 
                                            // echo btn_view('salary_template/view/'.$salary_template->salary_templateID, $this->lang->line('view')) 
                                            ?>
                                            <?php 
                                            // echo btn_edit('salary_template/edit/'.$salary_template->salary_templateID, $this->lang->line('edit'))
                                            ?>
                                            <a href="<?php echo base_url();?>/courses/delete/<?php echo $salary_template->salary_templateID; ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                                            <?php 
                                            // echo btn_delete('salary_template/delete/'.$salary_template->salary_templateID, $this->lang->line('delete')) 
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                
                                </div>
                            </div>


                             <?php foreach ($allyears as   $year) { ?>
                                    <div id="tab<?=$year->basic_salary.$year->basic_salary?>" class="tab-pane">
                                        <div id="hide-table">
                                    
                    <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_salary_grades')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_basic_salary')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('salary_template_overtime_rate_not_hour')?></th>
                                <?php if(permissionChecker('salary_template_edit') || permissionChecker('salary_template_delete') || permissionChecker('salary_template_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($salary_templates[$year->basic_salary])) {$i = 1; foreach($salary_templates[$year->basic_salary] as $salary_template) { 
                            // echo $salary_template->salary_templateID;
                            $salary_templateID = $salary_template->salary_templateID;
                            $sql ="SELECT * FROM course_option WHERE salary_templateID = '$salary_templateID'";
                            $query =  $this->db->query($sql);
                            // var_dump($query->result());
                            $salaryoptions = $query->result();
                            $netsalary = 0;
                            foreach ($salaryoptions as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    // echo $netsalary;
                                }
                            }
                            // var_dump($netsalary);
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_salary_grades')?>">
                                        <?=$salary_template->classes?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_basic_salary')?>">
                                        <?=$salary_template->basic_salary?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('salary_template_overtime_rate_not_hour')?>">
                                        <?=$netsalary?>
                                        <!--$salary_template->overtime_rate-->
                                    </td>
                                    <?php if(permissionChecker('salary_template_edit') || permissionChecker('salary_template_delete') || permissionChecker('salary_template_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <a href="<?php echo base_url();?>/courses/view/<?php echo $salary_template->salary_templateID; ?>" class="btn btn-success btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-check-square-o"></i></a>
                                            <?php 
                                            // echo btn_view('salary_template/view/'.$salary_template->salary_templateID, $this->lang->line('view')) 
                                            ?>
                                            <?php 
                                            // echo btn_edit('salary_template/edit/'.$salary_template->salary_templateID, $this->lang->line('edit'))
                                            ?>
                                            <a href="<?php echo base_url();?>/courses/delete/<?php echo $salary_template->salary_templateID; ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                                            <?php 
                                            // echo btn_delete('salary_template/delete/'.$salary_template->salary_templateID, $this->lang->line('delete')) 
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                
                                </div>
                                    </div>
                            <?php } ?>
                        </div>
                </div>
                
            </div>
        </div>
    </div>
</div>