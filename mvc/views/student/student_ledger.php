<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("student/index/$set")?>"><?=$this->lang->line('menu_student')?></a></li>
            <li class="active">Student Ledger</li>
        </ol>
    </div><!-- /.box-header -->
     
</div><!-- /.box -->
<div class="box">
    <div class="box-body">
        <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
        <div class="row" id="printablediv">
            <h2 align="center"><?php echo $siteinfos->sname;?></h2>
            <h3 align="center">Student Ledger</h3>
            <div class="col-sm-4" style="width: 33%; float: left;">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($profile->photo)?>
                        <h3 class="profile-username text-center"><?=$profile->srname?></h3>

                        <p class="text-muted text-center"><?=$usertype->usertype?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('student_registerNO')?></b> <a class="pull-right"><?=$profile->srregisterNO?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('student_roll')?></b> <a class="pull-right"><?=$profile->srroll?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('student_classes')?></b> <a class="pull-right"><?=customCompute($class) ? $class->classes : ''?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('student_section')?></b> <a class="pull-right"><?=customCompute($section) ? $section->section : ''?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b>CNIC</b> <a class="pull-right"><?=$profile->cnic?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b>GCUF Roll</b> <a class="pull-right"><?=$profile->gcuf_roll?></a>
                            </li>
                             
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-8" style="width: 66%; float: right;">
                <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th> 
                                        <th>Invoice #</th>
                                        <th>Particulars</th> 
                                        <th>Debit</th> 
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; 
                                    $total_balance      =   0;
                                    $total_amount       =   0;
                                    $total_fine       =   0;
                                    $total_discount     =   0;
                                    $total_paid         =   0;
                                    $total_netamount    =   0;
                                    foreach($invoices as $invoice) {  ?>
                                           
                                            <tr>
                                                <td ><?php echo $i;?></td>
                                                    
                                               <td>
                                                    <?php echo $invoice->refrence_no; ?>
                                                </td> 
                                                <td data-title="<?=$this->lang->line('student_feetype')?>">
                                                        <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                                                    </td> 
                                                    
                                            <td data-title="<?=$this->lang->line('student_fees_amount')?>">
                                                        <?php echo $invoice->net_fee;?>
                                                    </td>

                                                    
                                                    <td>
                                                        
                                            <?php  $inv_paid     =   0;
                                               echo $inv_paid ; ?>

                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                        <?php $blance   =    $invoice->net_fee-$inv_paid;
                                                            
                                                            


                                    $total_paid      +=   $inv_paid;
                                    $total_balance      +=   $blance;
                                    $total_amount       +=   $invoice->net_fee;
                                    $total_fine       +=   $invoice->totalfine;
                                    $total_discount     +=   $invoice->discount; 
                                    $total_netamount    +=   ($invoice->amount-$invoice->discount);
                                    echo $total_balance;
                                                         ?>
                                                    </td>

                                            </tr>

                                             
                                            <?php $i++; }  


                                            foreach($payments as $invoice) {  ?>
                                           
                                            <tr>
                                                <td ><?php echo $i;?></td>
                                                    
                                               <td>
                                                    <?php echo $invoice->refrence_no; ?>
                                                </td> 
                                                <td data-title="<?=$this->lang->line('student_feetype')?>">
                                                        <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                                                    </td> 
                                                    
                                            <td data-title="<?=$this->lang->line('student_fees_amount')?>">
                                                       0
                                                    </td>

                                                    
                                                    <td>
                                                        
                                            <?php  $inv_paid     =   $invoice->paymentamount;
                                               echo $inv_paid ; ?>

                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_paid')?>">
                                                        <?php $blance   =    0;
                                                            
                                                            


                                    $total_paid      +=   $inv_paid;
                                    $total_balance      -=   $inv_paid; 
                                    echo $total_balance;
                                                         ?>
                                                    </td>

                                            </tr>

                                             
                                            <?php $i++; }  ?>         
                                   
                                                         
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" align="right"> Total</th>
                                        <th><?php echo $total_amount;?></th> 
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

    function printDiv(divID) {
            var divElements = document.getElementById(divID).innerHTML;
            var oldPage = document.body.innerHTML;
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
            window.location.reload();
        }

        function closeWindow() {
            location.reload();
        }
    
</script>

