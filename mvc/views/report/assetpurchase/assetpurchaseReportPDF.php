<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?=reportheader($siteinfos, $schoolyearsessionobj, true); ?>
        <h3 style="margin-bottom: 0px;"><?=$this->lang->line('productpurchasereport_report_for')?> - <?=$this->lang->line('productpurchasereport_product_purchase')?> </h3>
        <div>
            <?php if($fromdate != '' && $todate != '' ) { ?>
                <h5 class="pull-left">
                    <?=$this->lang->line('productpurchasereport_fromdate')?> : <?=date('d M Y',$fromdate)?></p>
                </h5>
                <h5 class="pull-right">
                    <?=$this->lang->line('productpurchasereport_todate')?> : <?=date('d M Y',$todate)?></p>
                </h5>
            <?php }   elseif($vendorID != 0) { ?>
                <h5 class="pull-left">
                    <?php
                        echo " Vender : ";
                        echo isset($venders[$vendorID]) ? $venders[$vendorID] : '';
                    ?>
                </h5>
            <?php }  ?>
        </div>
        <?php if (customCompute($assetpurchases)) { ?>
            <table>
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th>
                                        <th>Asset</th>
                                        <th>Vendor</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th>Date</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i=1;

                                    $unitar     = array( 0 => $this->lang->line('purchase_select_unit'), 1 => $this->lang->line('purchase_unit_kg'), 2 => $this->lang->line('purchase_unit_piece'), 3 => $this->lang->line('purchase_unit_other'));
                                    foreach($assetpurchases as $productpurchase) {

                                     ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="Asset">
                                                <?=$productpurchase->description?>
                                            </td>

                                            <td data-title="Vendor">
                                                <?=$vendors[$productpurchase->vendorID];?>
                                            </td>

                                            <td data-title="Quantity">
                                                <?=$productpurchase->p_quantity;?>
                                            </td>

                                            <td data-title="Unit">
                                                <?=$unitar[$productpurchase->unit];?>
                                            </td>

                                            <td data-title="Price">
                                                <?=$productpurchase->purchase_price;?>
                                            </td>

                                            <td data-title="Date">
                                                <?=date('d-m-Y',strtotime($productpurchase->purchase_date));?>
                                            </td>
                                             
                                        </tr>
                                    <?php $i++; } ?>
                                    <tr>
                                       <th><?=$this->lang->line('slno')?></th>
                                        <th>Asset</th>
                                        <th>Vendor</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th>Date</th> </tr>
                                </tbody>
                            </table>
        <?php } else { ?>
            <div class="notfound">
                <?=$this->lang->line('productpurchasereport_data_not_found')?>
            </div>
        <?php } ?>
    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
</body>
</html>