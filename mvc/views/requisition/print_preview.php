<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
  <div>
    <table width="100%">
      <tr>
        <td widht="5%">
          <h2>
            <?php
              if($siteinfos->photo) {
                  $array = array(
                      "src" => base_url('uploads/images/'.$siteinfos->photo),
                      'width' => '25px',
                      'height' => '25px',
                      'style' => 'margin-top:-8px'
                  );
                  echo img($array);
              }
              ?>
          </h2>
        </td>
        <td widht="75%">
          <h3 class="top-site-header-title"><?php  echo $siteinfos->sname; ?></h3>
        </td>
        <td class="20%">
          <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("requisition_create_date")." : ". date("d M Y"); ?></h5>
        </td>
      </tr>
    </table>
    <br>
    <table width="100%">
      <tr>
        <td width="33%">
          <table>
            <tbody>
                <tr>
                    <th class="site-header-title-float" align="left"><?php  echo $this->lang->line("requisition_from"); ?></th>
                </tr>
                <?php if(customCompute($fromuser)) { ?>
                    <tr>
                        <td>  <?=$fromuser->name?></td>
                    </tr>
                    <tr>
                        <td><?=$fromuser->address?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("requisition_phone"). " : ". $fromuser->phone?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("requisition_email"). " : ". $fromuser->email?></td>
                    </tr>
                <?php } ?>
            </tbody>
          </table>
        </td>
        <td width="33%">
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float" align="left">Approved By</th>
                  </tr>

                  <?php if($requisition->aprove_status_first === '1'){?>
                  <?php if(customCompute($approve_one)) { ?>
                      <tr>
                          <td><?="Name : ". $approve_one->name?></td>
                      </tr>
                      <tr>
                          <td><?=$approve_one->address?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("requisition_phone"). " : ". $approve_one->phone?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("requisition_email"). " : ". $approve_one->email?></td>
                      </tr>
                  <?php } 
                  } ?> 
                   
              </tbody>
            </table>
        </td>
        <td width="34%" style="vertical-align: text-top;">
         
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float" align="left">Approved By</th>
                  </tr>

                  
                   <?php if($requisition->aprove_status_second === '1'){?>
                  <?php if(customCompute($approve_two)) { ?>
                      <tr>
                          <td><?="Name : ". $approve_two->name?></td>
                      </tr>
                      <tr>
                          <td><?=$approve_two->address?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("requisition_phone"). " : ". $approve_two->phone?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("requisition_email"). " : ". $approve_two->email?></td>
                      </tr>
                  <?php } 
                          }?>
              </tbody>
            </table>
        
        </td>
      </tr>
    </table>
    <br>
    <table class="table table-bordered" width="100%" border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
            <th><?=$this->lang->line('slno')?></th>
            <th><?=$this->lang->line('requisition_description')?></th> 
            <th><?=$this->lang->line('requisition_quantity')?></th> 
        </tr>
      </thead>
      <tbody>
          <?php $subtotal = 0; $totalsubtotal = 0; if(customCompute($requisitionitems)) { $i=1; foreach ($requisitionitems as $requisitionitem) { $subtotal = ($requisitionitem->requisitionunitprice * $requisitionitem->requisitionquantity); $totalsubtotal += $subtotal; ?>
            <tr>
                <td data-title="<?=$this->lang->line('slno')?>">
                    <?php echo $i; ?>
                </td>
                
                <td data-title="<?=$this->lang->line('requisition_description')?>">
                    <?=isset($products[$requisitionitem->productID]) ? $products[$requisitionitem->productID] : ''?>
                </td> 

                <td data-title="<?=$this->lang->line('requisition_quantity')?>">
                    <?=number_format($requisitionitem->requisitionquantity, 2)?>
                </td> 
            </tr>
          <?php $i++; } } ?>
      </tbody>
      
    </table>
   
    <table width="100%">
        <tr>
            <td width="65%" >
                <p><?=$requisition->requisitiondescription?></p>
            </td>
            <td width="35%">
                <table>
                    <tr>
                        <td><?=$this->lang->line('requisition_create_by')?> : <?=$createuser->name?> (<?=$usertypes[$createuser->usertypeID]?>)</td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('requisition_date')?> : <?=date('d M Y', strtotime($requisition->create_date))?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>