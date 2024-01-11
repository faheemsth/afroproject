<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("student/index/$set")?>"><?=$this->lang->line('menu_student')?></a></li>
            <li class="active">Student Ledger</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
        <form class="form-horizontal"  role="form" method="get" enctype="multipart/form-data">
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-4" style="margin-left: 0px; margin-right:0 ;" id="fromdateDiv">
                                        <label><?='From Date'?><span class="text-red"> * </span></label>
                                       <input class="form-control" type="text" name="start_date" id="fromdate">
                                    </div>

                                    <div class="form-group col-sm-4" style="margin-left: 0px; margin-right:0 ;" id="todateDiv">
                                        <label><?='To Date'?><span class="text-red"> * </span></label>
                                        <input class="form-control" type="text" name="end_date" id="todate">
                                    </div>

                                    <div class="col-sm-4">
                                        <button id="" class="btn btn-success" style="margin-top:23px;"> <?='Get Ledger'?></button>
                                    </div>
                                </div>
                            </div><!-- row -->
                            </form>
       
    </div><!-- Body -->
</div><!-- /.box -->
<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th>
                                        <td>Date</td>
                                        <th>Degree</th>
                                        <th>Semester</th>
                                        <th>voucher number</th>
                                        <th>Fee Type</th>
                                        <th>Payment ID</th>
                                        <th>Amount</th>
                                        <th>Dsicount</th>
                                        <th>Net Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; 
                                    $total_balance      =   0;
                                    $total_amount       =   0;
                                    $total_discount     =   0;
                                    $total_paid         =   0;
                                    $total_netamount    =   0;
                                    foreach($invoices as $invoice) {  ?>
                                           
                                            <tr>
                                                <td ><?php echo $i;?></td>
                                                <td>
                                                    <?php echo date('d-m-Y',strtotime($invoice->date));?>
                                                </td>
                                                <td>
                                                    <?php echo $classesp[$student->classesID];?>
                                                </td>
                                                <td >
                                                    <?php echo $sectionp[$student->sectionID];?>
                                                </td>    
                                               <td>
                                                    <?php echo $invoice->refrence_no; ?>
                                                </td> 
                                                <td data-title="<?=$this->lang->line('student_feetype')?>">
                                                        <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                                                    </td> 
                                                    <td></td> 
                                            <td data-title="<?=$this->lang->line('student_fees_amount')?>">
                                                        <?php echo $invoice->net_fee+$invoice->discount;?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_discount')?>">
                                                       
                                                        <?php  echo $invoice->discount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                        <?php echo $invoice->net_fee ?>
                                                    </td>
                                                    <td>-</td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                        <?php $blance   =    $invoice->net_fee;
                                                            
                                                            


                                    $total_balance      +=   $blance;
                                    $total_amount       +=   $invoice->amount;
                                    $total_discount     +=   $invoice->discount; 
                                    $total_netamount    +=   ($invoice->amount-$invoice->discount);
                                    echo $total_balance;
                                                         ?>
                                                    </td>

                                            </tr>

                                             <?php 
                                             if(count($invoicepaments[$invoice->invoiceID])){
                                                $pp     =   1;
                                             foreach ($invoicepaments[$invoice->invoiceID] as $p){ ?>

                                                <tr>
                                                <td ><?php echo numbertoroman($pp); $pp++;?></td>

                                                <td>
                                                    <?php echo date('d-m-Y',strtotime($p->paymentdate));?>
                                                </td>
                                                <td>
                                                   -
                                                </td>
                                                <td >
                                                    -
                                                </td>    
                                               <td>
                                                   -
                                                </td> 
                                                <td data-title="<?=$this->lang->line('student_feetype')?>">
                                                        <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                                                    </td> 
                                                    <td><?php echo $p->paymentID;?></td> 
                                            <td data-title="<?=$this->lang->line('student_fees_amount')?>">
                                                        -
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_discount')?>">
                                                       
                                                       -
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                       -
                                                    </td>
                                                    <td>
                                                        <?php echo $p->paymentamount;?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                        <?php $paid   =    $p->paymentamount;
                                                            
                                                            


                                    $total_balance      -=   $paid; 
                                    $total_paid      +=   $paid; 
                                    echo $total_balance;
                                                         ?>
                                                    </td>

                                            </tr>
                                                
                                            <?php   }
                                                    } ?>
                                            <?php $i++; }  ?>       
                                   
                                                         
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" align="right"> Total</th>
                                        <th><?php echo $total_amount;?></th>
                                        <th><?php echo $total_discount;?></th>
                                        <th><?php echo $total_netamount;?></th>
                                        <th><?php echo $total_paid;?></th>
                                        <th><?php echo $total_balance;?></th> 
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

      $('.select2').select2();

    $('#fromdate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $('#todate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });
    
</script>

