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
          <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("productsale_create_date")." : ". date("d M Y"); ?></h5>
        </td>
      </tr>
    </table>
    <br>
    <table width="100%">
      <tr>
        
        <td width="33%">
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float"><?php  echo $this->lang->line("productsale_to"); ?></th>
                  </tr>
                  <?php if(customCompute($user)) { ?>
                      <?php if(isset($user->name)) { ?>
                          <tr>
                              <td><?=$user->name?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></td>
                          </tr>
                          <tr>
                              <td><?=$user->address?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_phone"). " : ". $user->phone?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_email"). " : ". $user->email?></td>
                          </tr>
                      <?php } else { ?>
                          <tr>
                              <td><?=$user->srname?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[3]) ? $usertypes[3] : ''?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_phone"). " : "?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_email"). " : "?></td>
                          </tr>
                      <?php } ?>
                  <?php } ?>
              </tbody>
            </table>
        </td>
        <td width="33%"> </td>
        <td width="34%" style="vertical-align: text-top;">
          <table>
            <tbody>
              <tr>
                <td><?php echo $this->lang->line("productsale_referenceno"). " : " . $productsale->productsalereferenceno; ?></td>
              </tr> 
            </tbody>
          </table>
        </td>
      </tr>
    </table>
    <br>
    <table class="table table-bordered">
      <thead>
        <tr>
            <th><?=$this->lang->line('slno')?></th>
            <th><?=$this->lang->line('productsale_description')?></th> 
            <th><?=$this->lang->line('productsale_quantity')?></th> 
        </tr>
      </thead>
      <tbody>
          <?php $subtotal = 0; $totalsubtotal = 0; if(customCompute($productsaleitems)) { $i=1; foreach ($productsaleitems as $productsaleitem) { $subtotal = ($productsaleitem->productsaleunitprice * $productsaleitem->productsalequantity); $totalsubtotal += $subtotal; ?>
            <tr>
                <td data-title="<?=$this->lang->line('slno')?>">
                    <?php echo $i; ?>
                </td>
                
                <td data-title="<?=$this->lang->line('productsale_description')?>">
                    <?=isset($products[$productsaleitem->productID]) ? $products[$productsaleitem->productID] : ''?>
                </td>
                 

                <td data-title="<?=$this->lang->line('productsale_quantity')?>">
                    <?=number_format($productsaleitem->productsalequantity, 2)?>
                </td> 
            </tr>
          <?php $i++; } } ?>
      </tbody>
       
    </table>
   
    <table width="100%">
        <tr>
            <td width="65%" >
                <p><?=$productsale->productsaledescription?></p>
            </td>
            <td width="35%">
                <table>
                    <tr>
                        <td><?=$this->lang->line('productsale_create_by')?> : <?=$createuser?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('productsale_date')?> : <?=date('d M Y', strtotime($productsale->create_date))?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>