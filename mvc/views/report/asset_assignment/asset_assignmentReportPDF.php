<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
<h3 style="margin-bottom: 0px;"><?=$this->lang->line('asset_assignmentreport_report_for')?> - <?=$this->lang->line('asset_assignmentreport_asset_assignment')?> </h3>
    <?php if($fromdate != '' && $todate != '' ) { ?>
        <div>
            <h5 class="pull-left">
                <?=$this->lang->line('asset_assignmentreport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
            </h5>
            <h5 class="pull-right">
                <?=$this->lang->line('asset_assignmentreport_todate')?> : <?=date('d M Y',strtotime($todate))?></p>
            </h5>
        </div>
    <?php } elseif($asset_assignmentcustomertypeID != 0 && $asset_assignmentcustomerID != 0 ) { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('asset_assignmentreport_role')." : ";
                    echo isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : '';
                ?>
            </h5>
            <h5 class="pull-right">
                <?php
                    echo $this->lang->line('asset_assignmentreport_user')." : ";
                    if(isset($users[3][$asset_assignmentcustomerID])) {
                        $userName = isset($users[3][$asset_assignmentcustomerID]->name) ? $users[3][$asset_assignmentcustomerID]->name : $users[3][$asset_assignmentcustomerID]->srname;
                        echo $userName;
                    }
                ?>
            </h5>
        </div>
    <?php } else { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('asset_assignmentreport_role')." : ";
                     echo isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : $this->lang->line('asset_assignmentreport_all');
                ?>
            </h5>
        </div>
    <?php } ?>
    <div style="margin-top:0px">
        <?php if (customCompute($asset_assignments)) { ?>
                        <div id="fees_collection_details" class="tab-pane active">
                            <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assetID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assigned_quantity')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_usertypeID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_to')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_due_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_in_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_status')?></th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($asset_assignments)) {$i = 1; foreach($asset_assignments as $asset_assignment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assetID')?>">
                                        <?php echo $asset_assignment->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assigned_quantity')?>">
                                        <?php echo $asset_assignment->assigned_quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_usertypeID')?>">
                                        <?php echo $asset_assignment->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_to')?>">
                                        <?php echo $asset_assignment->assigned_to; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_due_date')?>">
                                        <?php echo isset($asset_assignment->due_date) ? date('d M Y', strtotime($asset_assignment->due_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_date')?>">
                                        <?php echo isset($asset_assignment->check_out_date) ? date('d M Y', strtotime($asset_assignment->check_out_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_in_date')?>">
                                        <?php echo isset($asset_assignment->check_in_date) ? date('d M Y', strtotime($asset_assignment->check_in_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_status')?>">
                                        <?php
                                            if($asset_assignment->status == 1) {
                                                echo $this->lang->line('asset_assignment_checked_out');
                                            } elseif($asset_assignment->status == 2) {
                                                echo $this->lang->line('asset_assignment_in_storage');
                                            }
                                        ?>
                                    </td>
                                  
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
                        </div>
                    <?php } else { ?>
        <div class="notfound">
            <?=$this->lang->line('asset_assignmentreport_data_not_found')?>
        </div>
        <?php } ?>
    </div>
<?=reportfooter($siteinfos, $schoolyearsessionobj)?>
</body>
</html>