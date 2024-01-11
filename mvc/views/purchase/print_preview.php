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
          <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("purchase_create_date")." : ". date("d M Y"); ?></h5>
        </td>
      </tr>
    </table>
    <br>
      <table border="0" class="invoice-table invoice-head-table" width="100%">
                            <tbody>
                              <tr>
                                <th  align="left">Purchase Order No  # 
                                </th>
                                <td align="left">P.O-<?php echo $purchase->purchaseID;?></td>
                              </tr>
                              <tr>
                                <th  align="left">Purchase Order Date</th>
                                <td   align="left">
                                  <div>
                                    <span><?php echo date('D, d-m-Y',strtotime($purchase->purchase_date));?></span>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
    <table width="100%">
      <tr>
        <td width="32%">
          <table>
            <tbody>
                <tr>
                    <th class="site-header-title-float" align="left">Vendor</th>
                </tr>
                 
                    <tr>
                        <td>  <strong><?="Name : ". $vendor->name?> </strong></td>
                    </tr>
                    <tr>
                        <td><?="Contact Person : ". $vendor->contact_name?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("purchase_phone"). " : ". $vendor->phone?></td>
                    </tr>
                     
                
            </tbody>
          </table>
        </td>
        <td width="34%">
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float" align="left">Approved By</th>
                  </tr>

                  <?php if($purchase->aprove_status_first === '1'){?>
                  <?php if(customCompute($approve_one)) { ?>
                      <tr>
                          <td><strong><?="Name : ". $approve_one->name?></strong></td>
                      </tr>
                      <tr>
                          <td><?=$approve_one->address?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("purchase_phone"). " : ". $approve_one->phone?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("purchase_email"). " : ". $approve_one->email?></td>
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

                  
                   <?php if($purchase->aprove_status_second === '1'){?>
                  <?php if(customCompute($approve_two)) { ?>
                      <tr>
                          <td><strong><?="Name : ". $approve_two->name?></strong></td>
                      </tr>
                      <tr>
                          <td><?=$approve_two->address?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("purchase_phone"). " : ". $approve_two->phone?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("purchase_email"). " : ". $approve_two->email?></td>
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
                                    <th class="col-lg-2" ><?=$this->lang->line('slno')?></th>
                                    <th class="col-lg-4">Asset</th> 
                                    <th class="col-lg-4">Price</th> 
                                    <th class="col-lg-2"><?=$this->lang->line('purchase_quantity')?></th> 
                                    <th class="col-lg-2">Total</th> 
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            1
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('purchase_description')?>">
                                            <?=$assets->description?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('purchase_description')?>">
                                            <?=$purchase->purchase_price?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('purchase_description')?>">
                                            <?=$purchase->quantity?>
                                        </td>
                                        
                                        

                                        <td data-title="<?=$this->lang->line('purchase_quantity')?>">
                                            <?=number_format($purchase->quantity*$purchase->purchase_price, 2)?>
                                        </td>
                                        
                                    </tr>
                                 
                            </tbody>
                            <tfoot>
                                
                                
                            </tfoot>
                        </table>
   
    <table width="100%">
        <tr>
            <td width="65%" >
                
            </td>
            <td width="35%">
                <table>
                    <tr>
                        <td><?=$this->lang->line('purchase_create_by')?> : <?=$createuser->name?> (<?=$usertypes[$createuser->usertypeID]?>)</td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('purchase_date')?> : <?=date('d M Y', strtotime($purchase->create_date))?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>