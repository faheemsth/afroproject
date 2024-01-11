<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
    <?php 
        if((int)$asset_requisition->usertypeID && (int)$asset_requisition->check_out_to && isset($user[$asset_requisition->check_out_to])) { $users = $user[$asset_requisition->check_out_to]; ?>

      <div class="areaTop">
        <div class="studentImage">
          <img class="studentImg" src="<?=pdfimagelink($users->photo)?>" alt="">
        </div>
        <div class="studentProfile">
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_requisition_name')?></div>
            <div class="single_value">: <?=$users->name?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_requisition_usertypeID')?></div>
            <div class="single_value">: <?=isset($usertypes[$users->usertypeID]) ? $usertypes[$users->usertypeID] : ''?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_requisition_gender')?></div>
            <div class="single_value">: <?=$users->sex?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_requisition_dob')?></div>
            <div class="single_value">: <?php if($users->dob) { echo date("d M Y", strtotime($users->dob)); } ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_requisition_phone')?></div>
            <div class="single_value">: <?=$users->phone?></div>
          </div>
        </div>
      </div>
    <?php } ?>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col" style="font-size: 16px; width: 33%; float: left;">
                    <?php  echo $this->lang->line("asset_requisition_from"); ?>
                     
                        <address>
                            <strong><?=$fromuser->name?>  (<?=$usertypes[$fromuser->usertypeID]?>)</strong><br> 
                            <?=$this->lang->line("asset_requisition_phone"). " : ". $fromuser->phone?><br>
                            <?=$this->lang->line("asset_requisition_email"). " : ". $fromuser->email?><br>
                        </address>
                     
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;width: 33%; float: left;">
                    Approved By
                        <?php if($asset_requisition->aprove_status_first === '1'){?>
                        <address>
                            <strong><?=$approve_one->name?>  (<?=$usertypes[$approve_one->usertypeID]?>)</strong><br> 
                            <?=$this->lang->line("asset_requisition_phone"). " : ". $approve_one->phone?><br>
                            <?=$this->lang->line("asset_requisition_email"). " : ". $approve_one->email?><br>
                        </address>
                        <?php }?>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;width: 33%; float: left;">
                    Approved By
                        <?php if($asset_requisition->aprove_status_second === '1'){?>
                        <address>
                            <strong><?=$approve_two->name?>  (<?=$usertypes[$approve_two->usertypeID]?>)</strong><br> 
                            <?=$this->lang->line("asset_requisition_phone"). " : ". $approve_two->phone?><br>
                            <?=$this->lang->line("asset_requisition_email"). " : ". $approve_two->email?><br>
                        </address>
                        <?php }?>
                </div>
                 
                 
            </div>
      <div class="areaBottom">
        <h3><?=$this->lang->line('asset_requisition_information')?></h3>
        <table class="table table-bordered">
            <?php if((int)$asset_requisition->usertypeID && $asset_requisition->check_out_to == '0') { ?>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_usertypeID")?></td>
                <td> <?=isset($usertypes[$asset_requisition->usertypeID]) ? $usertypes[$asset_requisition->usertypeID] : ''?></td>
            </tr>
            <?php } ?>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_assetID")?></td>
                <td> <?=$asset_requisition->description; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_assigned_quantity")?></td>
                <td> <?=$asset_requisition->assigned_quantity; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_due_date")?></td>
                <td> <?=isset($asset_requisition->due_date) ?  date('d M Y', strtotime($asset_requisition->due_date)) : ''; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_check_out_date")?></td>
                <td> <?=isset($asset_requisition->check_out_date) ? date('d M Y', strtotime($asset_requisition->check_out_date)) : ''?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_check_in_date")?></td>
                <td> <?=isset($asset_requisition->check_in_date) ? date('d M Y', strtotime($asset_requisition->check_in_date)) : '';?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_status")?></td>
                <td> 
                    <?php
                        if($asset_requisition->status == 1) {
                            echo $this->lang->line('asset_requisition_checked_out');
                        } elseif($asset_requisition->status == 2) {
                            echo $this->lang->line('asset_requisition_in_storage');
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_note")?></td>
                <td> <?=$asset_requisition->note?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_requisition_location")?></td>
                <td> <?=$asset_requisition->location?></td>
            </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>