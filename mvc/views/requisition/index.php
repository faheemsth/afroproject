<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-requisition"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php if(permissionChecker('requisition_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('requisition/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th>Ref. Number</th> 
                                <th>Name</th> 
                                <th><?=$this->lang->line('requisition_date')?></th>
                                <th><?=$this->lang->line('requisition_file')?></th>
                                <?php if(permissionChecker('requisitionapprove1')) {?>
                                <th>Approve 1</th>
                                <?php }?>
                                <?php if(permissionChecker('requisitionapprove2')) {?>
                                <th>Approve 2</th>
                                <?php }?> 
                                <?php if(permissionChecker('requisition_edit') || permissionChecker('requisition_delete') || permissionChecker('requisition_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($requisitions)) {$i = 1; foreach($requisitions as $requisition) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="Refrence Number">
                                        <?=$requisition->refrence_no;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('requisition_referenceno')?>">
                                        

                                        <?=getNameByUsertypeIDAndUserID($requisition->usertypeID,$requisition->userID)?>
                                            (<?=$usertypes[$requisition->usertypeID]?>)</td>
                                    </td> 
                                     

                                    <td data-title="<?=$this->lang->line('requisition_date')?>">
                                        <?=date('d M Y', strtotime($requisition->requisitiondate));?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('requisition_file')?>">
                                        <?php 
                                            if($requisition->requisitionfileorginalname) { echo btn_download_file('requisition/download/'.$requisition->requisitionID, namesorting($requisition->requisitionfileorginalname, 12), $this->lang->line('download')); 
                                            }
                                        ?>
                                    </td>
                                     <?php if(permissionChecker('requisitionapprove1')) {?>
                                    <td data-title="<?=$this->lang->line('requisition_grandtotal')?>">
                                        <?php if($requisition->requisitionstatus == 0){?>
                                         <div class="onoffswitch-small" id="<?=$requisition->requisitionID?>">
                                          <input type="checkbox" id="myonoffswitch<?=$requisition->requisitionID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_first" name="aprove_status_first" <?php if($requisition->aprove_status_first === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch<?=$requisition->requisitionID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>
                                      <?php  } if($requisition->aprove_status_first === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($requisition->firstAprove_usertypeID,$requisition->firstAprove_userID)?>
                                            (<?=$usertypes[$requisition->firstAprove_usertypeID]?>)
                                      <?php }?>
                                      
                                    </td>
                                     <?php } if(permissionChecker('requisitionapprove2')) {?>
                                    <td data-title="<?=$this->lang->line('requisition_grandtotal')?>">
                                        <?php if($requisition->requisitionstatus == 0){?>
                                         <div class="onoffswitch-small" id="<?=$requisition->requisitionID?>">
                                          <input type="checkbox" id="myonoffswitch_second<?=$requisition->requisitionID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_second" name="aprove_status_second" <?php if($requisition->aprove_status_second === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch_second<?=$requisition->requisitionID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>

                                      <?php } if($requisition->aprove_status_second === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($requisition->secondAprove_usertypeID,$requisition->secondAprove_userID)?>
                                            (<?=$usertypes[$requisition->secondAprove_usertypeID]?>)
                                      <?php }?>
                                    </td>



                                    <?php }?> 


                                    <?php if(permissionChecker('requisition_edit') || permissionChecker('requisition_delete') || permissionChecker('requisition_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php
                                                echo btn_view('requisition/view/'.$requisition->requisitionID, $this->lang->line('view'));
                                                if($requisition->requisitionstatus == 0){
                                                echo btn_edit('requisition/edit/'.$requisition->requisitionID, $this->lang->line('edit'));
                                                echo btn_delete('requisition/delete/'.$requisition->requisitionID, $this->lang->line('delete'));
                                                }
                                                
                                                if($requisition->aprove_status_first == 1 || $requisition->aprove_status_second == 1){
                                                    echo btn_invoice('productsale/add/'.$requisition->requisitionID, 'Issue Inventory');
                                                }     
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<form class="form-horizontal" role="form" method="post" id="requisitionPaymentAddDataForm" enctype="multipart/form-data">
    <div class="modal fade" id="addpayment">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('requisition_add_payment')?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-sm-12">
                            <div class="form-group" id="requisitionpaiddateerrorDiv">
                                <label for="requisitionpaiddate"><?=$this->lang->line('requisition_date')?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="requisitionpaiddate" name="requisitionpaiddate">
                                <span id="requisitionpaiddateerror"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="col-sm-12">
                            <div class="form-group" id="requisitionpaidreferencenoerrorDiv">
                                <label for="requisitionpaidreferenceno"><?=$this->lang->line('requisition_referenceno')?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="requisitionpaidreferenceno" name="requisitionpaidreferenceno">
                                <span id="requisitionpaidreferencenoerror"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="col-sm-12">
                            <div class="form-group" id="requisitionpaidamounterrorDiv">
                                <label for="requisitionpaidamount"><?=$this->lang->line('requisition_amount')?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="requisitionpaidamount" name="requisitionpaidamount">
                                <span id="requisitionpaidamounterror"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="col-sm-12">
                            <div class="form-group" id="requisitionpaidpaymentmethoderrorDiv">
                                <label for="requisitionpaidpaymentmethod"><?=$this->lang->line('requisition_paymentmethod')?> <span class="text-red">*</span></label>
                                <?php
                                    $paymentmethodArray = array(
                                        0 => $this->lang->line('requisition_select_paymentmethod'),
                                        1 => $this->lang->line('requisition_cash'),
                                        2 => $this->lang->line('requisition_cheque'),
                                        3 => $this->lang->line('requisition_credit_card'),
                                        4 => $this->lang->line('requisition_other'),
                                    );
                                    echo form_dropdown("requisitionpaidpaymentmethod", $paymentmethodArray, set_value("requisitionpaidpaymentmethod"), "id='requisitionpaidpaymentmethod' class='form-control select2'");
                                ?>

                                <span id="requisitionpaidpaymentmethoderror"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="col-sm-12">
                            <div class="form-group" id="requisitionpaidfileerrorDiv">
                                <label for="requisitionpaidfile"><?=$this->lang->line('requisition_file')?></label>
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('requisition_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('requisition_browse')?></span>
                                            <input type="file" name="requisitionpaidfile"/>
                                        </div>
                                    </span>
                                </div>
                                <span id="requisitionpaidfileerror"></span>
                            </div>
                        </div>
                    </div>

                    <?php if ($siteinfos->note==1) { ?>
                        <div class="col-sm-12">
                            <div class="callout callout-danger">
                                <p><b>Note:</b> This payment add in current academic year.</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="add_payment_button" class="btn btn-success" value="<?=$this->lang->line("requisition_add_payment")?>" />
            </div>
        </div>
      </div>
    </div>
</form>

<form class="form-horizontal" role="form" method="post">
    <div class="modal fade" id="paymentlist">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('requisition_view_payments')?></h4>
            </div>
            <div class="modal-body">
                <div id="hide-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('requisition_date')?></th>
                                <th><?=$this->lang->line('requisition_referenceno')?></th>
                                <th><?=$this->lang->line('requisition_amount')?></th>
                                <th><?=$this->lang->line('requisition_paid_by')?></th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                    <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="payment-list-body">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
        </div>
      </div>
    </div>
</form>

<script type="text/javascript">
    $(function() {
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });

        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('requisition_browse')?>");
        });

        $(".image-preview-input input:file").change(function (){
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('requisition_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    $('#requisitionpaiddate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearobj->startingdate?>',
        endDate:'<?=$schoolyearobj->endingdate?>',
    });

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function floatChecker(value) {
        var val = value;
        if(isNumeric(val)) {
            return true;
        } else {
            return false;
        }
    }

    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    } 

    function sentanceLengthRemove(sentence) {
        sentence = sentence.toString();
        sentence = sentence.slice(0, -1);
        sentence = parseFloat(sentence);
        return sentence;
    } 

    function dotAndNumber(data) {
        var retArray = [];
        var fltFlag = true;
        if(data.length > 0) {
            for(var i = 0; i <= (data.length-1); i++) {
                if(i == 0 && data.charAt(i) == '.') {
                    fltFlag = false;
                    retArray.push(true);
                } else {
                    if(data.charAt(i) == '.' && fltFlag == true) {
                        retArray.push(true);
                        fltFlag = false;
                    } else {
                        if(isNumeric(data.charAt(i))) {
                            retArray.push(true);
                        } else {
                            retArray.push(false);
                        }
                    }

                }
            }
        }

        if(jQuery.inArray(false, retArray) ==  -1) {
            return true;
        }
        return false;
    }

    function toFixedVal(x) {
      if (Math.abs(x) < 1.0) {
        var e = parseFloat(x.toString().split('e-')[1]);
        if (e) {
            x *= Math.pow(10,e-1);
            x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
        }
      } else {
        var e = parseFloat(x.toString().split('+')[1]);
        if (e > 20) {
            e -= 20;
            x /= Math.pow(10,e);
            x += (new Array(e+1)).join('0');
        }
      }
      return x;
    }

    function lenChecker(data, len) {
        var retdata = 0;
        var lencount = 0;
        data = toFixedVal(data);
        if(data.length > len) {
            lencount = (data.length - len);
            data = data.toString();
            data = data.slice(0, -lencount);
            retdata = parseFloat(data);
        } else {
            retdata = parseFloat(data);
        }

        return toFixedVal(retdata);
    }

    function lenCheckerWithoutParseFloat(data, len) {
        var retdata = 0;
        var lencount = 0;
        if(data.length > len) {
            lencount = (data.length - len);
            data = data.toString();
            data = data.slice(0, -lencount);
            retdata = data;
        } else {
            retdata = data;
        }

        return retdata;
    }

    $(document).on('keyup', '#requisitionpaidreferenceno', function() {
        var requisitionpaidreferenceno =  $(this).val();
        if(requisitionpaidreferenceno.length > 99) {
            requisitionpaidreferenceno = lenCheckerWithoutParseFloat(requisitionpaidreferenceno, 99);
            $(this).val(requisitionpaidreferenceno);                    
        }
    });

    var globalrequisitionpaidamount = 0;
    var globalrequisitionID = 0;
    $(document).on('keyup', '#requisitionpaidamount', function() {
        var requisitionpaidamount =  $(this).val();
        if(dotAndNumber(requisitionpaidamount)) {
            if(requisitionpaidamount != '' && requisitionpaidamount != null) {
                if(floatChecker(requisitionpaidamount)) {
                    if(requisitionpaidamount.length > 15) {
                        requisitionpaidamount = lenChecker(requisitionpaidamount);
                        $(this).val(requisitionpaidamount);

                        if(requisitionpaidamount > globalrequisitionpaidamount) {
                            $(this).val(globalrequisitionpaidamount);
                        }                 
                    } else {
                        if(requisitionpaidamount > globalrequisitionpaidamount) {
                            $(this).val(globalrequisitionpaidamount);
                        }
                    }
                }
            }
        } else {
            var requisitionpaidamount = parseSentenceForNumber($(this).val());
            $(this).val(requisitionpaidamount);
        }
    });

    $('.getpurchaseinfobtn').click(function() {
        var requisitionID =  $(this).attr('id');
        globalrequisitionID = requisitionID;
        if(requisitionID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('requisition/getpurchaseinfo')?>",
                data: {'requisitionID' : requisitionID},
                dataType: "html",
                success: function(data) {
                    $('#requisitionpaidamount').val('');
                    var response = JSON.parse(data);
                    if(response.status == true) {
                        $('#requisitionpaidamount').val(response.dueamount);
                        globalrequisitionpaidamount = parseFloat(response.dueamount);
                    }
                }
            });
        }   
    });

    $('.getpaymentinfobtn').click(function() {
        var requisitionID =  $(this).attr('id');
        if(requisitionID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('requisition/paymentlist')?>",
                data: {'requisitionID' : requisitionID},
                dataType: "html",
                success: function(data) {
                    $('#payment-list-body').children().remove();
                    $('#payment-list-body').append(data);
                }
            });
        }   
    });


    $(document).on('click', '#add_payment_button', function() {
        var error=0;;
        var field = {
            'requisitionpaiddate'           : $('#requisitionpaiddate').val(), 
            'requisitionpaidreferenceno'    : $('#requisitionpaidreferenceno').val(), 
            'requisitionpaidamount'         : $('#requisitionpaidamount').val(), 
            'requisitionpaidpaymentmethod'  : $('#requisitionpaidpaymentmethod').val(), 
        };

        if (field['requisitionpaiddate'] == '') {
            $('#requisitionpaiddateerrorDiv').addClass('has-error');
            error++;
        } else {
            $('#requisitionpaiddateerrorDiv').removeClass('has-error');
        }

        if (field['requisitionpaidreferenceno'] == '') {
            $('#requisitionpaidreferencenoerrorDiv').addClass('has-error');
            error++;
        } else {
            $('#requisitionpaidreferencenoerrorDiv').removeClass('has-error');
        }

        if (field['requisitionpaidamount'] == '') {
            $('#requisitionpaidamounterrorDiv').addClass('has-error');
            error++;
        } else {
            $('#requisitionpaidamounterrorDiv').removeClass('has-error');
        }

        if (field['requisitionpaidpaymentmethod'] === '0') {
            $('#requisitionpaidpaymentmethoderrorDiv').addClass('has-error');
            error++;
        } else {
            $('#requisitionpaidpaymentmethoderrorDiv').removeClass('has-error');
        }

        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#requisitionPaymentAddDataForm')[0]);
            formData.append("requisitionID", globalrequisitionID);
            makingPostDataPreviousofAjaxCall(formData);
        }

    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('requisition/saverequisitionpayment')?>",
            data: passData,
            async: true,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                errrorLoader(response);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function errrorLoader(response) {
        if(response.status) {
            window.location = "<?=base_url("requisition/index")?>";
        } else {
            $('#add_payment_button').removeAttr('disabled');
            $.each(response.error, function(index, val) {
                toastr["error"](val)
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
            });
        }
    }


  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox_first').click(function() {
     
      if($(this).prop('checked')) {
          status = 'chacked';
          id = $(this).parent().attr("id");
      } else {
          status = 'unchacked';
          id = $(this).parent().attr("id");
      }

      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?=base_url('requisition/active')?>",
              data: "id=" + id + "&status=" + status,
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                      toastr["success"]("Success")
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
                  } else {
                      toastr["error"]("Error")
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
              }
          });
      }
  }); 
  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox_second').click(function() {
        
      if($(this).prop('checked')) {
          status = 'chacked';
          id = $(this).parent().attr("id");
      } else {
          status = 'unchacked';
          id = $(this).parent().attr("id");
      }

      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?=base_url('requisition/active_second')?>",
              data: "id=" + id + "&status=" + status,
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                      toastr["success"]("Success")
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
                  } else {
                      toastr["error"]("Error")
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
              }
          });
      }
  });
</script>

