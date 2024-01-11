<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineadmission"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_onlineadmission')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <br/>
            </div>
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('applicant_id')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_class')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_reg')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_fee')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_payment_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // echo '<pre>';
                            // print_r($onlineadmissions);
                            // echo '</pre>';
                            ?>
                            <?php if(customCompute($onlineadmissions)) {$i = 1; foreach($onlineadmissions as $onlineadmission) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('applicant_id')?>">
                                        <?=$onlineadmission->form_no; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_name')?>">
                                        <?=$onlineadmission->f_name?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_name')?>">
                                        <?=$onlineadmission->classes?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_reg')?>">
                                        <?=$onlineadmission->accounts_reg?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('onlineadmission_name')?>">
                                        <?=$onlineadmission->admission_fee?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('onlineadmission_status')?>">
                                        <?php
                                            if($onlineadmission->status == 'Confirmed') {
                                                echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('onlineadmissions_confirm') . "</button>";
                                            } elseif ($onlineadmission->status == 'In process') {
                                                echo "<button class='btn btn-warning btn-xs'>" . $this->lang->line('onlineadmissions_inprocess') . "</button>";
                                            } elseif($onlineadmission->status == 'Rejected') {
                                                echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('onlineadmission_decline') . "</button>";
                                            } else {
                                                echo "<button class='btn btn-info btn-xs'>" . $this->lang->line('onlineadmission_new') . "</button>";
                                            }
                                        ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('onlineadmission_payment_status')?>">
                                        <?=$onlineadmission->payment_status?>
                                    </td>
                                    
                                    <?php if(permissionChecker('onlineadmissions')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            echo btn_view_show(base_url('onlineadmissions/view/'.$onlineadmission->id), $this->lang->line('onlineadmission_view'));
                                            if($onlineadmission->payment_status == 'paid'){
                                                //   echo btn_add_show(base_url('#'), $this->lang->line('onlineadmission_bank'));
                                                echo 'Paid';
                                            }else{
                                                echo btn_add_show(base_url('onlineadmissions/bankadd/'.$onlineadmission->id), $this->lang->line('onlineadmission_bank'));
                                            }
                                            // echo btn_add_show(base_url('onlineadmissions/bankadd/'.$onlineadmission->id), $this->lang->line('onlineadmission_bank'));
                                            // echo '<a onclick="return confirm(\'This cannot be undone. are you sure?\')" href="'.base_url('onlineadmission/waiting/'.$onlineadmission->id).'" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="'.$this->lang->line('onlineadmission_waiting').'"><i class="fa fa-circle-o"></i></a>';
                                            echo btn_cancel('onlineadmissions/decline/'.$onlineadmission->id, $this->lang->line('onlineadmission_decline'));
                                        ?>
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

<script>
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = parseInt($('#classesID').val());
        if(classesID) {
            var url = "<?=base_url('onlineadmission/index/')?>"+classesID;
            window.location.href = url; 
        }   
    });
</script>
