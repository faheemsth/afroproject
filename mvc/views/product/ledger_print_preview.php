<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
      <div class="areaBottom" style="padding: 3px;">
        <h3><?=$this->lang->line('asset_asset_information')?></h3>
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('asset_serial')?></td>
            <td width="70%"><?=$asset->productID;?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_description')?></td>
            <td width="70%"><?=$asset->productname;?></td>
          </tr>
           
         
          
        </table>

            <table class="table table-bordered">
                <thead>
                    <tr valign="middle">
                        <th rowspan="2">Sr. #</th>
                        <th rowspan="2">Date</th>
                        <th rowspan="2">Description</th>
                        <th rowspan="2">Type</th>
                        <th colspan="2">Quantity Received</th>
                        <th colspan="2">Quantity Issued</th>
                        <th rowspan="2">Balance</th>
                    </tr>
                    <tr>
                          
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if (customCompute($allinouts)) {
                        $sr         =   1;
                        $balance    =   0;
                        foreach ($allinouts as $inout) { 
                      ?>
                    <tr>
                        <td><?php echo $sr;?></td>
                        <td><?php echo date('d-m-Y',strtotime($inout->date));?></td>
                        <td><?php echo $asset->productname;?></td>
                        <td><?php echo $inout->type;?></td>
                        <?php if($inout->type=='purchase'){
                                $balance += $inout->quantity;
                        ?>
                        <td><?php echo $inout->quantity?></td>
                        <td><?php echo $inout->quantity*$inout->price?></td>
                        <td></td>
                        <td></td>
                        <?php }?>
                        <?php if($inout->type=='stockin'){
                                $balance += $inout->quantity;
                        ?>
                        <td><?php echo $inout->quantity?></td>
                        <td><?php echo $inout->quantity*$inout->price?></td>
                        <td></td>
                        <td></td>
                        <?php }?>
                        <?php if($inout->type=='stockout'){
                                $balance -= $inout->quantity;
                        ?>
                        <td></td>
                        <td></td>
                        <td><?php echo $inout->quantity?></td>
                        <td><?php echo $inout->quantity*$inout->price?></td>
                        <?php }?>
                        <td><?php echo $balance;?></td>
                        
                    </tr>
                    <?php 
                          $sr++;  }
                        }
                        ?>
                </tbody>
                
            </table>
        
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>