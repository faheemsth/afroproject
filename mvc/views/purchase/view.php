<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?php
             echo btn_add_pdf('purchase/print_preview/'.$purchase->purchaseID, $this->lang->line('pdf_preview'))
            ?>
            <?php if(permissionChecker('purchase_edit')) { echo btn_sm_edit('purchase/edit/'.$purchase->purchaseID, $this->lang->line('edit')); }
            ?>
            <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
        </div>


        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("purchase/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                <li class="active"><?=$this->lang->line('menu_view')?></li>
            </ol>
        </div>

    </div>

</div>


<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">
        <section class="content invoice" >
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <?php
                            if($siteinfos->photo) {
                                $array = array(
                                    "src" => base_url('uploads/images/'.$siteinfos->photo),
                                    'width' => '25px',
                                    'height' => '25px',
                                    'class' => 'img-circle',
                                    'style' => 'margin-top:-10px' 
                                );
                                echo img($array);
                            } 
                        ?>
                        <?php  echo $siteinfos->sname; ?>
                        <small class="pull-right"><?=$this->lang->line('purchase_create_date').' : '.date('d M Y')?></small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-12 invoice-col" style="font-size: 16px;">
                    <div class="refrens-header-wrapper">
                      <div class="invoice-heading">
                        <div class="invoice-title">
                          <span>Purchase Order</span>
                           
                        </div>
                        <div class="invoice-status"></div>
                      </div>
                      <div class="invoice-header">
                        <div class="invoice-detail-section">
                          <table border="0" class="invoice-table invoice-head-table" width="500">
                            <tbody>
                              <tr>
                                <th class="hash-header">Purchase Order No  # 
                                </th>
                                <td>P.O-<?php echo $purchase->purchaseID;?></td>
                              </tr>
                              <tr>
                                <th>Purchase Order Date</th>
                                <td>
                                  <div>
                                    <span><?php echo date('D, d-m-Y',strtotime($purchase->purchase_date));?></span>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                </div>
                <span class="clearfix"></span>
                 <hr>
                 <span class="clearfix"></span>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    Vendor
                     
                        <address>
                            <strong><?=$vendor->name?> </strong><br> 
                            <?=$this->lang->line("purchase_phone"). " : ". $vendor->phone?><br>
                            <?="Contact Person : ". $vendor->contact_name?><br>
                        </address>
                     
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    Approved By
                        <?php if($purchase->aprove_status_first === '1'){?>
                        <address>
                            <strong><?=$approve_one->name?>  (<?=$usertypes[$approve_one->usertypeID]?>)</strong><br> 
                            <?=$this->lang->line("purchase_phone"). " : ". $approve_one->phone?><br>
                            <?=$this->lang->line("purchase_email"). " : ". $approve_one->email?><br>
                        </address>
                        <?php }?>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    Approved By
                        <?php if($purchase->aprove_status_second === '1'){?>
                        <address>
                            <strong><?=$approve_two->name?>  (<?=$usertypes[$approve_two->usertypeID]?>)</strong><br> 
                            <?=$this->lang->line("purchase_phone"). " : ". $approve_two->phone?><br>
                            <?=$this->lang->line("purchase_email"). " : ". $approve_two->email?><br>
                        </address>
                        <?php }
                         
                        ?>
                </div>
                 
                 
            </div>

            <br />
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered product-style">
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
                    </div>
                </div>

                <div class="col-sm-9 col-xs-12 pull-left">
                    
                </div>
                <div class="col-sm-3 col-xs-12 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?=$this->lang->line('purchase_create_by')?> : <?=$createuser->name?> (<?=$usertypes[$createuser->usertypeID]?>)
                            <br>
                            <?=$this->lang->line('purchase_date')?> : <?=date('d M Y', strtotime($purchase->create_date))?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>
</section>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('purchase/send_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("to")?>
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
                        <?=$this->lang->line("subject")?>
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
                        <?=$this->lang->line("message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->
<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }
    function closeWindow() {
        location.reload();
    }

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $("#send_pdf").click(function(){
        var to = $('#to').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var id = "<?=$purchase->purchaseID;?>";
        var error = 0;

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("");
            $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("");
            $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('purchase/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message,
                dataType: "html",
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
</script>

