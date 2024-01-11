<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <?php if (permissionChecker('quotation_add')) {
                echo btn_sm_add('product/addQuotation/' . $productId, 'Add Quotation');
            } ?>
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?= $this->lang->line('print') ?> </button>
            <?php
            echo btn_add_pdf('product/print_preview/' . $productId, $this->lang->line('pdf_preview'))
            ?>

            <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?= $this->lang->line('mail') ?></button>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?= base_url("product/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
                <li><a href="<?= base_url("product/index") ?>">Product</a></li>
                <li class="active">Quotation</li>
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

    .lowest-price {
        background-color: #8895a1 !important;
    }
    @media print {
        .action {
            display: none;
        }
    }
</style>

<div id="printablediv">
    <div class="row">
        <div class="col-sm-3" style='margin: auto;'>
            <div class="box box-primary" style="border: 1px solid #aba9a9;">
                <div class="box-body box-profile">
                    <?php //profileviewimage(Null)
                    ?>
                    <h3 class="product-name text-center"><?= $product->productname ?></h3>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="background-color: #FFF">
                            <b> Code/Ref.</b> <a class="pull-right"><?= $product->code_reference ?></a>
                        </li>
                        <li class="list-group-item" style="background-color: #FFF">
                            <b>Description</b> <a class="pull-right"><?= $product->productdesc ?></a>
                        </li>
                        <li class="list-group-item" style="background-color: #FFF">
                            <b>Category</b> <a class="pull-right"><?= $productcategorys[$product->productcategoryID] ?></a>
                        </li>
                        <li class="list-group-item" style="background-color: #FFF">
                            <b>Buying Price</b> <a class="pull-right"><?= $siteinfos->currency_code . ' ' . number_format((float)($product->productbuyingprice), 2, '.', '00') ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <table id="example1" class="table table-striped table-bordered table-hover no-footer">
                <thead style='background: #e5e5e5'>
                    <tr valign="middle">
                        <th rowspan="2">Sr. #</th>
                        <th rowspan="2">Description</th>
                        <th rowspan="2">Quantity</th>
                        <th rowspan="2">Unit Price</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2" class="action">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if (customCompute($allquotations)) {
                        $sr = 1;
                        $lowest_unit_price = 0;
                        foreach ($allquotations as $key => $quoation) {
                            $lowest_unit_price = $quoation->unit_price;
                    ?>
                            <tr class='text-center' >
                                <td><?php echo $sr; ?></td>
                                <td><?= $quoation->description ?></td>
                                <td><?= $quoation->quantity ?></td>
                                <td><?= number_format((float)$quoation->unit_price, 2, '.', ''); ?></td>
                                <td><?= $siteinfos->currency_code . ' ' . number_format((float)($quoation->unit_price * $quoation->quantity), 2, '.', '00')  ?></td>
                                <td class="action">
                                    <a href=<?= base_url("uploads/images/$quoation->quotation_img") ?> class="fa fa-eye btn btn-primary" target="_blank"></a>
                                    <a href=<?= base_url("product/download/$quoation->quotation_img") ?> class="fa fa-download btn btn-info"></a>
                                </td>
                            </tr>
                    <?php
                            $sr++;
                        }
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<form class="form-horizontal" role="form" action="<?= base_url('product/send_mail'); ?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?= $this->lang->line('mail') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="to" class="col-sm-2 control-label">
                            <?= $this->lang->line("to") ?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="to" name="to" value="<?= set_value('to') ?>">
                        </div>
                        <span class="col-sm-4 control-label" id="to_error">
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            <?= $this->lang->line("subject") ?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?= set_value('subject') ?>">
                        </div>
                        <span class="col-sm-4 control-label" id="subject_error">
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-2 control-label">
                            <?= $this->lang->line("message") ?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?= set_value('message') ?>"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
                    <input type="button" id="send_product_pdf" class="btn btn-success" value="<?= $this->lang->line("send") ?>" />
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#send_product_pdf').on('click', function(){
            var to = $('#to').val();
	        var subject = $('#subject').val();
	        var message = $('#message').val();
	        var productId = "<?=$productId;?>";
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
	                url: "<?= base_url('product/send_mail')?>",
	                //data: {to, subject, message},
                    data: 'to='+ to + '&subject=' + subject + "&message=" + message + "&productid=" + productId,
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
</script>