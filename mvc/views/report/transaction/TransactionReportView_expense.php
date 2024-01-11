<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-transactionreport"></i> Expense Report</h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_transactionreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <?php //var_dump($chartofaccounts);?>
                <div class="form-group col-sm-3" id="chartofaccountDiv">
                    <label>Expense Head </label>
                    <?php
                                 
                                $chofaccArray = array('0' => 'Please Select');
                                if(customCompute($chartofaccounts)) {
                                    foreach ($chartofaccounts as $ch) {
                                        $chofaccArray[$ch->id] = $ch->name .'-'.$ch->code;
                                    }
                                }
                                echo form_dropdown("chartofaccountID", $chofaccArray, set_value("chartofaccountID"), "id='chartofaccountID' class='form-control select2'");
                            ?>
                </div>
                <div class="form-group col-sm-3" id="cbtypeDiv">
                    <label>Voucher  Type </label>
                    <?php
                                 
                                $chofaccArray = array('0' => 'Please Select','JUR' => 'Journal Voucher', 'BPV' => 'Bank payment Voucher', 'CPV' => 'Cash payment Voucher', 'BR' => 'Bank Receipt' , 'CR' => 'Cash Receipt');
                                
                                echo form_dropdown("vouchertype", $chofaccArray, set_value("vouchertype"), "id='vouchertype' class='form-control select2'");
                            ?>
                </div>
                <div class="form-group col-sm-2" id="fromdateDiv">
                    <label><?=$this->lang->line("transactionreport_fromdate")?><span class="text-red"> * </span></label>
                   <input class="form-control" type="text" name="fromdate" id="fromdate">
                </div>

                <div class="form-group col-sm-2" id="todateDiv">
                    <label><?=$this->lang->line("transactionreport_todate")?><span class="text-red"> * </span></label>
                    <input class="form-control" type="text" name="todate" id="todate">
                </div>

                <div class="col-sm-2">
                    <button id="get_classreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("transactionreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_transactionreport"></div>


<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('transactionreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

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

    $(document).bind('click', '#fromdate, #todate', function() {
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
    });

    $('#get_classreport').click(function() {

        var fromdate            = $('#fromdate').val();
        var todate              = $('#todate').val();
        var chartofaccountID    = $('#chartofaccountID').val();
        var vouchertype         = $('#vouchertype').val();
        var error               = 0;

        if(fromdate == '') {
            error++;
            $('#fromdateDiv').addClass('has-error');
        } else{
            $('#fromdateDiv').removeClass('has-error');
        }

        if(todate == '') {
            error++;
            $('#todateDiv').addClass('has-error');
        } else{
            $('#todateDiv').removeClass('has-error');
        } 

        var field = {
            'fromdate': fromdate,
            'todate': todate,
            'chartofaccountID': chartofaccountID,
            'vouchertype': vouchertype,
        }

        if(error == 0 ) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('transactionreport/getTransactionReport_expense')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_transactionreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }

</script>
