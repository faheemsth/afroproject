<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php

            $generatepdfurl = base_url("transactionreport/pdf_expense/".strtotime($fromdate)."/".strtotime($todate)."/3/$chartofaccountID/$vouchertype");
            $generatexmlurl = base_url("transactionreport/xlsx_expense/".strtotime($fromdate)."/".strtotime($todate)."/3/$chartofaccountID/$vouchertype");
            echo btn_printReport('transactionreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('transactionreport',$generatepdfurl, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('transactionreport',$generatexmlurl, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('transactionreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('transactionreport_report_for')?> - <?=$this->lang->line('transactionreport_transaction')?>  </h3>
    </div><!-- /.box-header -->

    <div id="printablediv">
            <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($fromdate >= 0 || $todate >= 0 ) { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left" style="margin-top:0px">
                                    <?=$this->lang->line('transactionreport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
                                </h5>                         
                                <h5 class="pull-right" style="margin-top:0px">
                                    <?=$this->lang->line('transactionreport_todate')?> : <?=date('d M Y', strtotime($todate))?></p>
                                </h5>                        
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-sm-12" style="margin-top:0px">
                   <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a class="transction-tab" data-toggle="tab" href="#fees_collection_details" aria-expanded="true"><?=$this->lang->line("transactionreport_income_details")?></a></li>
                            <!-- <li><a  class="transction-tab" data-toggle="tab" href="#income_details" aria-expanded="true"><?=$this->lang->line("transactionreport_income_details")?></a></li>
                            <li><a  class="transction-tab" data-toggle="tab" href="#expense_details" aria-expanded="true"><?=$this->lang->line("transactionreport_expense_details")?></a></li>
                            <li><a  class="transction-tab" data-toggle="tab" href="#salary_details" aria-expanded="true"><?=$this->lang->line("transactionreport_salary_details")?></a></li> -->
                        </ul>



                        <div class="tab-content">
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

                            <td><?=date('d M Y',strtotime($e->transaction_date))?></td>
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
                            <div id="income_details" class="tab-pane">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('transactionreport_name')?></th>
                                                <th><?=$this->lang->line('transactionreport_date')?></th>
                                                <th><?=$this->lang->line('transactionreport_amount')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalincome = 0;
                                            if(customCompute($incomes)) {
                                                $i = 1;
                                                foreach($incomes as $income) { ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                        <td data-title="<?=$this->lang->line('transactionreport_name')?>"><?=$income->name?></td>
                                                        <td data-title="<?=$this->lang->line('transactionreport_date')?>"><?=date('d M Y',strtotime($income->date))?></td>
                                                        <td data-title="<?=$this->lang->line('transactionreport_amount')?>">
                                                            <?php 
                                                                $amount = $income->amount;
                                                                echo number_format($amount,2);
                                                                $totalincome += $amount;
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php $i++; } } ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('transactionreport_grand_total')?>" colspan="3" class="text-bold text-right"><?=$this->lang->line('transactionreport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                                <td data-title="<?=$this->lang->line('transactionreport_total_amount')?>" class="text-bold"> <?=number_format($totalincome,2)?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="expense_details" class="tab-pane">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('transactionreport_name')?></th>
                                                <th><?=$this->lang->line('transactionreport_date')?></th>
                                                <th><?=$this->lang->line('transactionreport_amount')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalexpense = 0; $i=1; if(customCompute($expenses)) { foreach($expenses as $expense) {?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_name')?>"><?=$expense->expense?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_date')?>"><?=date('d M Y',strtotime($expense->date))?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_amount')?>">
                                                    <?php
                                                        $amount = $expense->amount;
                                                        echo number_format($amount,2);
                                                        $totalexpense += $amount; 
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                            <tr>
                                                 <td data-title="<?=$this->lang->line('transactionreport_grand_total')?>" colspan="3" class="text-bold text-right"><?=$this->lang->line('transactionreport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                                <td data-title="<?=$this->lang->line('transactionreport_total_amount')?>" class="text-bold"><?=number_format($totalexpense,2)?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="salary_details" class="tab-pane">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('transactionreport_date')?></th>
                                                <th><?=$this->lang->line('transactionreport_name')?></th>
                                                <th><?=$this->lang->line('transactionreport_type')?></th>
                                                <th><?=$this->lang->line('transactionreport_month')?></th>
                                                <th><?=$this->lang->line('transactionreport_amount')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalSalary = 0; $i=1; if(customCompute($salarys)) { foreach($salarys as $salary) {?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_date')?>"><?=date('d M Y',strtotime($salary->create_date))?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_name')?>"><?=isset($allUserName[$salary->usertypeID][$salary->userID]) ? $allUserName[$salary->usertypeID][$salary->userID]->name : ''?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_type')?>"><?=isset($usertypes[$salary->usertypeID]) ? $usertypes[$salary->usertypeID] : ''?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_month')?>"><?=date('F Y',strtotime('01-'.$salary->month))?></td>
                                                    <td data-title="<?=$this->lang->line('transactionreport_amount')?>">
                                                        <?php 
                                                            echo number_format($salary->payment_amount,2);
                                                            $totalSalary += $salary->payment_amount;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                            <tr>
                                                 <td data-title="<?=$this->lang->line('transactionreport_grand_total')?>" colspan="5" class="text-bold text-right"><?=$this->lang->line('transactionreport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                                <td data-title="<?=$this->lang->line('transactionreport_total_amount')?>" class="text-bold"><?=number_format($totalSalary,2)?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div>
                    </div> <!-- nav-tabs-custom -->
                </div>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('transactionreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('transactionreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('transactionreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("transactionreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("transactionreport_subject")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("transactionreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("transactionreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('transactionreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    gherf = '1';
    $(document).on('click','.transction-tab', function() {
        var href = $(this).attr('href');
        var fromdate = <?=strtotime($fromdate)?>;
        var todate = <?=strtotime($todate)?>;

        if(href == "#fees_collection_details") {
            var querydata = 1;
        } else if(href == "#income_details") {
            var querydata = 2;
        } else if(href == "#expense_details") {
            var querydata = 3;
        } else if(href == "#salary_details") {
            var querydata = 4;
        }
        gherf = querydata;

        var pdfUrlGenarete = "<?=base_url('/transactionreport/pdf')?>"+"/"+fromdate+"/"+todate+"/"+querydata;
        var xmlUrlGenarete = "<?=base_url('/transactionreport/xlsx')?>"+"/"+fromdate+"/"+todate+"/"+querydata;
        
        $('.pdfurl').attr('href', pdfUrlGenarete);
        $('.xmlurl').attr('href', xmlUrlGenarete);

    });

    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'fromdate'   : "<?=strtotime($fromdate)?>",
            'todate'     : "<?=strtotime($todate)?>",
            'querydata'  : gherf,
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('transactionreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('transactionreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('transactionreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        $.each(response, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value)
                                toastr.options = {
                                  "closeButton": true,
                                  "debug": false,
                                  "newestOnTop": false,
                                  "progressBar": false,
                                  "positionClass": "toast-top-right",
                                  "preventDuplicates": false,
                                  "onclick": null,
                                  "showDuration": "500",
                                  "hideDuration": "500",
                                  "timeOut": "5000",
                                  "extendedTimeOut": "1000",
                                  "showEasing": "swing",
                                  "hideEasing": "linear",
                                  "showMethod": "fadeIn",
                                  "hideMethod": "fadeOut"
                                }
                            }
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>
