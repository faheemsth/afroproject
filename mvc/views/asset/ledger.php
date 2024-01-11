<div class="well">
    <div class="row">
        <div class="col-sm-6">

            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?=btn_add_pdf('asset/ledger_print_preview/'.$asset->assetID, $this->lang->line('pdf_preview'))?>

             
            
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("asset/index")?>"><?=$this->lang->line('menu_asset')?></a></li>
                <li class="active"><?=$this->lang->line('view')?></li>
            </ol>
        </div>
    </div>
</div>

<style type="text/css">
    .table>thead>tr>th {
        vertical-align: middle; 
        font-weight: bold;
        text-align: center;
    }
</style>
<div id="printablediv">
    <div class="row">
        <div class="col-sm-12">
            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
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
                        <td><?php echo $asset->adescription;?></td>
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

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('asset/send_mail');?>" method="post">
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
                        <?=$this->lang->line("to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("subject")?> <span class="text-red">*</span>
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
        window.location.reload();

    }
    function closeWindow() {
        location.reload(); 
    }

    function check_email(email) {
        var status = false;     
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('mail_valid');?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var to = $('#to').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var assetID = "<?=$asset->assetID?>";
        var error = 0;

        $("#to_error").html("");
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
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('asset/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&assetID=" + assetID+ "&message=" + message,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
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