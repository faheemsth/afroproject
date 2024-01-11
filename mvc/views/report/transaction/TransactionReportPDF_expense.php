<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>   
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
            </div>
            <div class="col-sm-12">
                <h3> <?=$this->lang->line('transactionreport_report_for')?> - <?=$this->lang->line("transactionreport_option_".$pdfoption)?> </h3>
            </div>
            <div class="col-sm-12">
                <h5 class="pull-left"><?=$this->lang->line('transactionreport_fromdate')?> : <?=date('d M Y',$fromdate)?></h5>                         
                <h5 class="pull-right"><?=$this->lang->line('transactionreport_todate')?> : <?=date('d M Y',$todate)?></h5>
            </div>
            <div class="col-sm-12">
            <?php if($pdfoption == 3) { ?>
                <div id="fees_collection_details" class="tab-pane active">
                    <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                           <tr>
                                                <th>Sr</th>
                                                <th> #</th>
                                                <th> Transaction Date</th>
                                                <th> Create At</th>
                                                <th> Description</th>
                                                <th> Debit</th>
                                                <th> Credit</th>
                                                <th> Balance</th>
                                                <th> Voucher Type</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                                 
                                                $balance=0;
                                                $debit=0;
                                                $credit=0;
                                                if(customCompute($expenses)) { 
                                                    $i=1; 
                                                    foreach($expenses as $e) {
                                                    
                                                   
                                                     ?>
                                                     <tr>
                            <td>
                                
                                    <?php echo $i;?>
                                 
                            </td>
                            <td class="Id">
                                 <?php   echo $e->journal_id;?> 
                            </td>

                            <td>  <?=date('d M Y',strtotime($e->transaction_date))?></td>
                            <td><?php echo $e->created_at;?></td>
                            <td><?php echo !empty($e->description)?$e->description:'-';?></td>
                            <td><?php echo  $e->debit;?></td>
                            <td><?php echo $e->credit;?></td>
                            <td>
                            <?php 
                                if($e->debit>0){
                                      $debit+=$e->debit;
                                }else{
                                      $credit+=$e->credit ;
                                }

                                 $balance= $credit-$debit;
                                if($balance>0){
                                    echo 'Cr' .'. ' .$balance ;
                                } elseif($balance<0){
                                    echo 'Dr' .'. ' .$balance ;
                                }
                                else{
                                    echo '0';
                                }
                                
                                ?>
                            </td>
                            <td><?php echo $e->vouchertype;?></td> 
                        </tr>
                                            <?php $i++;   } } ?>
                        <tr>
                           
                            <th colspan="5">Total</th>
                             
                            <th> <?php echo $debit;?></th>
                            <th> <?php echo $credit;?></th>
                            <th> <?php if($balance>0){
                                    echo 'Cr' .'. ' .$balance ;
                                } elseif($balance<0){
                                    echo 'Dr' .'. ' .$balance ;
                                }
                                else{
                                    echo '0';
                                };?></th>
                            <th> Voucher Type</th> 
                                             
                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                </div>
            <?php } else { ?>
                <div class="notfound">
                    <?=$this->lang->line('transactionreport_data_not_found')?>
                </div>
            <?php } ?>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>