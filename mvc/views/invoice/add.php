
<div class="row">
    <div class="col-sm-7">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?='Add Invoices'?></h3>
            </div><!-- /.box-header -->
            <div class="box-body"> 
                <form role="form" method="post" enctype="multipart/form-data" id="invoiceDataForm"> 



                    <div class="remarksDiv form-group <?=form_error('remarks') ? 'has-error' : '' ?>" >
                        <label for="date">
                            Remarks <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="remarks" name="remarks" value="<?=set_value('remarks')?>" required >
                        <span class="text-red">
                            <?php echo form_error('remarks'); ?>
                        </span>
                    </div>

                    <div class=" form-group <?=form_error('Decision') ? 'has-error' : '' ?>" >
                         <label for="photo" class=" ">
                            Decision
                            &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Download the teacher sample csv file first then see the format and make a copy of that file and add your data with exact format which is used in our csv file then upload the file."></i>
                        </label>
                         <div class="clearfix"></div>
                        <div class="col-sm-8 col-xs-8 col-md-6">
                            <input class="form-control"  id="uploadFile" placeholder="Choose File" disabled />
                        </div>

                        <div class="col-sm-4 col-xs-4 col-md-6">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span>Upload Decsion</span>
                                <input id="uploadBtn" type="file" class="upload" name="csvFile" data-show-upload="false"
                                       data-show-preview="false" required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="classesDiv form-group <?=form_error('maininvoice_type_v') ? 'has-error' : '' ?>" >
                        <label for="maininvoice_type_v">
                            Invoice Type <span class="text-red">*</span>
                        </label>
                            <?php
                    $array_1['all']     =   'Please Select';
                    $array              =   get_general_feetype();
                    $array              =   array_merge($array_1, $array);
                     if(!permissionChecker('tution_invoice')){
                        unset($array['invoice']);
                    }
                    //var_dump(permissionChecker('other_charges'));
                    if(!permissionChecker('other_charges')){
                        unset($array['other_charges']);
                    }
                    if(!permissionChecker('Transport_fee')){
                        unset($array['Transport_fee']);
                    }
                    if(!permissionChecker('hostel_fee')){
                        unset($array['hostel_fee']);
                    }
                    unset($array['library_fine']);      
                        echo form_dropdown("maininvoice_type_v", $array, set_value("maininvoice_type_v"), "id='maininvoice_type_v' class='form-control select2'");
                     ?>
                        <span class="text-red">
                            <?php echo form_error('maininvoice_type_v'); ?>
                        </span>
                    </div>



                    <div class="dateDiv form-group <?=form_error('date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            <?=$this->lang->line("invoice_date_from")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        <span class="text-red">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>
                    
                    <div class="dateDiv form-group <?=form_error('to_date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            <?=$this->lang->line("invoice_date_to")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="to_date" name="to_date" value="<?=set_value('to_date')?>" >
                        <span class="text-red">
                            <?php echo form_error('to_date'); ?>
                        </span>
                    </div>
                    

                    <input type="hidden" name="statusID" value="0">
                    <div id="show_invoice" class="invoicetype hide">

                    


                    <div class="form-group payment_type">
                        <label for="Fees type" class="control-label">
                            Payment Type <span class="text-red">*</span>
                        </label>
                        

                            <?php
                            $payment_typeArray = array(
                                0 => 'Please Select', 
                                1 => 'Installment', 
                                2 => 'Lump Sum', 
                            );

                            echo form_dropdown("payment_type", $payment_typeArray, set_value("payment_type"), "id='payment_type' class='form-control select2'");
                        ?>
                             
                             
                         
                    </div>

                    <div class="classesDiv form-group <?=form_error('classesID') ? 'has-error' : '' ?>" >
                        <label for="classesID">
                            <?=$this->lang->line("invoice_classesID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                // $classesArray = array('0' => $this->lang->line("invoice_select_classes"));
                                $classesArray = array('0' => 'All Degree');
                                if(customCompute($classes)) {
                                    foreach ($classes as $classa) {
                                        $classesArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <div class="form-group" id="sectionDiv">
                        <label><?=$this->lang->line("balancefeesreport_section")?></label>
                        <?php
                            $sectionArray = array(
                                "0" => $this->lang->line("balancefeesreport_please_select"),
                            );
                            echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                         ?>
                    </div>

                    <div class="studentDiv form-group <?=form_error('studentID') ? 'has-error' : '' ?>" id="studentDiv">
                        <label for="studentID">
                            <?=$this->lang->line("invoice_studentID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $studentArray = array('0' => $this->lang->line("invoice_all_student"));
                                if(customCompute($students)) {
                                    foreach ($students as $student) {
                                        $studentArray[$student->studentID] = $student->name.' - '.$student->accounts_reg;
                                    }
                                }
                                echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('studentID'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="Fees type" class="control-label">
                            <?=  'Fees Type';?> <span class="text-red">*</span>
                        </label>
                        

                            <?php
                            $statusArray = array(
                                17 => 'Tuition Fee', 
                            );

                            echo form_dropdown("feess_type", $statusArray, set_value("feess_type"), "id='feess_type' class='form-control select2'");
                        ?>
                             
                             
                         
                    </div>
                    


                    <div class="form-group" id="other_amount" style="display: none;">
                        <label for="Fees type" class="control-label">
                           Amount <span class="text-red">*</span>
                        </label>
                        

                             <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" >
                             
                             
                         
                    </div>
                    


                    </div>

                   
            </div>
        </div>
    

    </div>


    <div class="col-sm-5" id="enrolment_fine" style="display: none;">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?='Enrollment Fine'?></h3>
            </div><!-- /.box-header -->
            <div class="box-body"> 
                 
 
 
                    
                    <div class="last_date_enrollmentDiv form-group <?=form_error('last_date_enrollment') ? 'has-error' : '' ?>" >
                        <label for="date">
                            Last Date of Enrollment  <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control date" id="last_date_enrollment" name="last_date_enrollment" value="<?=set_value('last_date_enrollment')?>" >
                        <span class="text-red">
                            <?php echo form_error('last_date_enrollment'); ?>
                        </span>
                    </div>
                    

                      <div class="form-group fine_typeDiv">
                        <label for="Fees type" class="control-label">
                            Fine Type <span class="text-red">*</span>
                        </label>
                        

                            <?php
                            $fine_typeArray = array(
                                0 => 'Please Select', 
                                1 => 'Day Wise', 
                                2 => 'Lump Sum', 
                            );

                            echo form_dropdown("fine_type", $fine_typeArray, set_value("fine_type"), "id='fine_type' class='form-control select2'");
                        ?>
                             
                             
                         
                    </div>
 
            </div>
        </div>
    

    </div>


    <div class="col-sm-5" id="attendance_t_fine" style="display: none;">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?='Attendance Fine'?></h3>
            </div><!-- /.box-header -->
            <div class="box-body"> 
                 
 
 
                    
                    <div class="attendance_start_dateDiv form-group <?=form_error('attendance_start_date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            Attendance check Start Date  <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control date" id="attendance_start_date" name="attendance_start_date" value="<?=set_value('attendance_start_date')?>" >
                        <span class="text-red">
                            <?php echo form_error('attendance_start_date'); ?>
                        </span>
                    </div>
                    
                    <div class="attendance_end_dateDiv form-group <?=form_error('attendance_end_date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            Attendance check end Date  <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control date" id="attendance_end_date" name="attendance_end_date" value="<?=set_value('attendance_end_date')?>" >
                        <span class="text-red">
                            <?php echo form_error('attendance_end_date'); ?>
                        </span>
                    </div>
                    

                     
 
            </div>
        </div>
    

    </div>

    <div class="col-sm-12">
         <input id="addInvoiceButton" type="button" class="btn btn-success" value="<?=$this->lang->line("add_invoice")?>" >
                </form>
    </div>


    
</div>

<script type="text/javascript">
    document.getElementById("uploadBtn").onchange = function() {
        document.getElementById("uploadFile").value = this.value;
    };
    $(document).on('change', "#maininvoice_type_v", function() {

        
         $("#other_amount").hide();

         $('.invoicetype').each(function(index, value) { 
           // $(this).addClass('hide');
         });

         $('#show_invoice').removeClass('hide');
         var feetypedata    =   '';
         if (this.value=='invoice') {
            feetypedata    =   '<option value="17">Tuition Fee</option>';
         }else if (this.value=='hostel_fee') {
            feetypedata    =   '<option value="4">Hostel Fee</option>';
         }else if (this.value=='Transport_fee') {
            feetypedata    =   '<option value="3">Transport Fee</option>';
         }else if (this.value=='other_charges') {
            feetypedata    +=   '<option value="">Please Select</option>';
            <?php foreach ($feetypes as $f){
                if(permissionChecker('fee_type_'.$f->feetypesID)){ ?>
                
                feetypedata    +=   '<option value="<?php echo $f->feetypesID;?>"><?php echo $f->feetypes;?></option>';
            <?php } } ?>

            $("#other_amount").show();
            
         }
         
         $('#feess_type').html(feetypedata);
         $("#feess_type").select2("destroy");
         $("#feess_type").select2();
    });

    function dd(data) {
        console.log(data);
    }

    $('.select2').select2();
    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
         endDate:'<?=$schoolyearsessionobj->endingdate?>',
        //endDate:'10-04-2022',
    });
    
    $('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
         endDate:'<?=$schoolyearsessionobj->endingdate?>',
         
    });   
    $('.date').datepicker();
    
     

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }

     

    $('#feetypeID').change(function(e) {
        var feetypeID   = $(this).val();
        if(feetypeID != 0) {
            var feetypeText = $(this).find(":selected").text();
            // var appendData  = productItemDesign(feetypeID, feetypeText);
            // $('#feetypeList').append(appendData);
        }
    });

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

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
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

    function floatChecker(value) {
        var val = value;
        if(isNumeric(val)) {
            return true;
        } else {
            return false;
        }
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
    
    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    }

    function currencyConvert(data) {
        return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    var globaltotalamount = 0;
    var globaltotaldiscount = 0;
    var globaltotalsubtotal = 0;
    var globaltotalpaidamount = 0;
    function totalInfo() {
        var i = 1;
        var j = 1;

        var totalAmount = 0;
        var totalDiscount = 0;
        var totalSubtotal = 0;
        var totalPaidAmount = 0;

        var discount = 0; 

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(2).children().val() != '' && $(this).children().eq(2).children().val() != null && $(this).children().eq(2).children().val() != '.') {
                var amount = parseFloat($(this).children().eq(2).children().val());
                totalAmount += amount;
            } 
        });
        globaltotalamount = totalAmount;
        $('#totalAmount').text(currencyConvert(totalAmount));

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(3).children().val() != '' && $(this).children().eq(3).children().val() != null && $(this).children().eq(3).children().val() != '.') {
                var discount = parseFloat($(this).children().eq(3).children().val());
                totalDiscount += discount;
            } 
        });
        globaltotaldiscount = totalDiscount;
        $('#totalDiscount').text(currencyConvert(totalDiscount));


        $('#feetypeList tr').each(function(index, value) {
            var amount = parseFloat($(this).children().eq(2).children().val());
            var discount = parseFloat($(this).children().eq(3).children().val());
            var subtotal = 0;
            if(amount > 0) {
                if(discount > 0) {
                    if(discount == 100) {
                        subtotal = 0;
                    } else {
                        subtotal = (amount - ((amount/100) * discount));
                    }
                } else {
                    subtotal = amount;
                }
            }

            $(this).children().eq(4).text(subtotal);
            totalSubtotal += subtotal;
        });
        globaltotalsubtotal = totalSubtotal;
        $('#totalSubtotal').text(currencyConvert(totalSubtotal));

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(5).children().val() != '' && $(this).children().eq(5).children().val() != null && $(this).children().eq(5).children().val() != '.') {
                var paidamount = parseFloat($(this).children().eq(5).children().val());
                totalPaidAmount += paidamount;
            } 
        });
        globaltotalpaidamount = totalPaidAmount;
        $('#totalPaidAmount').text(currencyConvert(totalPaidAmount));

    }

    $(function(){
        $('#sectionDiv').hide('slow');
        // $('#studentDiv').hide('slow');
    });
    
    $(document).on('change', "#classesID", function() {
        $('#load_balancefeesreport').html("");
        var classesID = $(this).val();
        
        $('#sectionID').val(0);
        $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("balancefeesreport_please_select")?>" +"</option>");
        $('#studentID').val(0);
        
        if(classesID == '0'){
            $("#sectionDiv").hide('slow');
            $("#studentDiv").hide('slow');
        } else {
            $("#sectionDiv").show('slow');
            $("#studentDiv").show('slow');
        }

        if(classesID !=0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('balancefeesreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#sectionID", function() {
        $('#load_balancefeesreport').html("");
        var sectionID = $(this).val();
        
        $('#studentID').html("<option value='0'>" + "All Student" +"</option>");
        $('#studentID').val(0);

        var classesID = $('#classesID').val();
        if(sectionID != 0 && classesID != 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('balancefeesreport/getStudent_active_inactive')?>",
                data: {"classesID":classesID, "sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });

    $(document).on('keyup', '.change-amount', function() {
        var amount =  toFixedVal($(this).val());
        var amountID = $(this).attr('data-amount-id'); 

        if(dotAndNumber(amount)) {
            if(amount.length > 15) {
                amount = lenChecker(amount, 15);
                $(this).val(amount);
            }
            
            if(amount != '' && amount != null) {
                $(this).val(amount);
                totalInfo();
            } else {
                totalInfo();
            }
        } else {
            var amount = parseSentenceForNumber(toFixedVal($(this).val()));
            $(this).val(amount);
        }

        removePaidAmount(amountID);
    });

    $(document).on('keyup', '.change-paidamount', function() {
        var trID = $(this).parent().parent().attr('id').replace('tr_','');
        var amount = $('#'+'td_amount_id_'+trID).val();
        var discount = $('#'+'td_discount_id_'+trID).val();

        if(discount != '' && discount != null) {
            amount = (amount - ((amount/100) * discount));
        }

        if(amount != '' && amount != null) {
            var paidamount =  toFixedVal($(this).val());
            var paidamountID = $(this).attr('data-paidamount-id'); 
            
            if(dotAndNumber(paidamount)) {
                if(paidamount.length > 15) {
                    paidamount = lenChecker(paidamount, 15);
                    if(parseFloat(paidamount) > parseFloat(amount)) {
                        $(this).val(amount);
                    } else {
                        $(this).val(paidamount);
                    }
                }
                
                if(paidamount != '' && paidamount != null) {
                    if(parseFloat(paidamount) > parseFloat(amount)) {
                        $(this).val(amount);
                    } else {
                        $(this).val(paidamount);
                    }
                    totalInfo();
                } else {
                    totalInfo();
                }
            } else {
                var paidamount = parseSentenceForNumber(toFixedVal($(this).val()));
                if(parseFloat(paidamount) > parseFloat(amount)) {
                    $(this).val(amount);
                } else {
                    $(this).val(paidamount);
                }
            }
        } else {
            $(this).val('');
        }
    });

    $(document).on('keyup', '.change-discount', function() {
        var trID = $(this).parent().parent().attr('id').replace('tr_','');
        var randID = $(this).attr('data-discount-id'); 
        var amount = $('#'+'td_amount_id_'+trID).val();

        if(amount != '' && amount != null) {
            var discount =  toFixedVal($(this).val());
            var discountID = $(this).attr('data-discount-id'); 
            
            if(dotAndNumber(discount)) {
                if(discount > 100) {
                    discount = 100;
                }
                $(this).val(discount);
                totalInfo();
            } else {
                var discount = parseSentenceForNumber(toFixedVal($(this).val()));
                $(this).val(discount);
            }
        } else {
            $(this).val('');
        }

        removePaidAmount(randID);
    });

    $(document).on('click', '.deleteBtn', function(er) {
        er.preventDefault();
        var feetypeID = $(this).attr('data-feetype-id');
        $('#tr_'+feetypeID).remove();
        
        var i = 1;
        $('#feetypeList tr').each(function(index, value) {
            $(this).children().eq(0).text(i);
            i++;
        });
        totalInfo();
    });

    function removePaidAmount(randID) {
        var ramount = $('#td_amount_id_'+randID).val();
        var rdiscount = $('#td_discount_id_'+randID).val();
        var rpaidamount = ($('#td_paidamount_id_'+randID).val());
        
        if(ramount == '' && ramount == null) {
            ramount = 0;
        }

        if(rdiscount == '' && rdiscount == null) {
            rdiscount = 0;
        }

        if(rpaidamount != '' && rpaidamount != null) {
            ramount = parseFloat((ramount - (ramount/100) * rdiscount)); 
            rpaidamount = parseFloat(rpaidamount);  
            if(rpaidamount > ramount) {
                $('#td_paidamount_id_'+randID).val('');
            }
        }
    }

</script>

<script type="text/javascript">
    $('#statusID').change(function() {
        if(($(this).val() != 0) && ($(this).val() != 5)) {
            $('.paymentmethodDiv').removeClass('hide');

            $('#feetypeList tr').each(function(index, value) {
                $(this).children().eq(5).children().removeAttr('readonly');
            });
        } else {
            $('.paymentmethodDiv').addClass('hide');

            $('#feetypeList tr').each(function(index, value) {
                $(this).children().eq(5).children().attr('readonly', 'readonly');
            });
        }
    });

    $(document).on('click', '#addInvoiceButton', function() {
        var error=0;;
        var fileInput = document.getElementById("uploadBtn");
        if (fileInput.files.length > 0) {
           var ttttt= fileInput.files[0];
        }else{
            var ttttt = '';
        }

         var file_data = $('#uploadBtn').prop('files')[0];   
    var field = new FormData();                  
    field.append('file', file_data);
    //alert(file_data);
        var fields = {
            'classesID'             : $('#classesID').val(), 
            'sectionID'             : $('#sectionID').val(), 
            'studentID'             : $('#studentID').val(), 
            'date'                  : $('#date').val(),
            'statusID'              : $('#statusID').val(), 
            'paymentmethodID'       : $('#paymentmethodID').val(), 
            'payment_type'          : $('#payment_type').val(), 
            'last_date_enrollment'  : $('#last_date_enrollment').val(), 
            'attendance_start_date' : $('#attendance_start_date').val(), 
            'attendance_end_date'   : $('#attendance_end_date').val(), 
            'fine_type'             : $('#fine_type').val(), 
            'feess_type'            : $('#feess_type').val(), 
            'remarks'               : $('#remarks').val(),  
        };
        field.append('classesID', $('#classesID').val());

    // field.push(fields); 
     //alert(field);  
        // if(field['classesID'] === '0') {
        //     $('.classesDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('.classesDiv').removeClass('has-error');
        // }

        if(field['remarks'] === '') {
            $('.remarksDiv').addClass('has-error');
             toastr["error"]('Remarks is Required')
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
            error++;
        } else {
            $('.remarksDiv').removeClass('has-error');
        }

        if(field['date'] === '') {
            $('.dateDiv').addClass('has-error');
             toastr["error"]('Date is Required')
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
            error++;
        } else {
            $('.dateDiv').removeClass('has-error');
        }

        if(field['feess_type'] == '<?php echo $siteinfos->enrolment_feetype?>') {
             

            if(field['fine_type'] == '0') {
            $('.fine_typeDiv').addClass('has-error');
             toastr["error"]('Fine Type is Required')
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
            error++;
        } else {
            $('.fine_typeDiv').removeClass('has-error');
        }  

            if(field['last_date_enrollment'] === '') {
            $('.last_date_enrollmentDiv').addClass('has-error');
             toastr["error"]('Last Date  is Required')
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
            error++;
        } else {
            $('.last_date_enrollmentDiv').removeClass('has-error');
        }



            if(field['classesID'] == '0') {
            $('.classesDiv').addClass('has-error');
             toastr["error"]('Degree is Required')
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
            error++;
        } else {
            $('.classesDiv').removeClass('has-error');
        }

            if(field['sectionID'] == '0') {
            $('.sectionDiv').addClass('has-error');
             toastr["error"]('Semester is Required')
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
            error++;
        } else {
            $('.sectionDiv').removeClass('has-error');
        }

        }  


        if(field['feess_type'] == '<?php echo $siteinfos->attendance_fine_feetypeID?>') {
             
            debugger;
            if(field['attendance_start_date'] == '') {
            $('.attendance_start_dateDiv').addClass('has-error');
             toastr["error"]('Attendance check start date is Required')
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
            error++;
        } else {
            $('.attendance_start_dateDiv').removeClass('has-error');
        }  

            if(field['attendance_end_date'] === '') {
            $('.attendance_end_dateDiv').addClass('has-error');
             toastr["error"]('Attendance check end  date  is Required')
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
            error++;
        } else {
            $('.attendance_end_dateDiv').removeClass('has-error');
        }



            if(field['classesID'] == '0') {
            $('.classesDiv').addClass('has-error');
             toastr["error"]('Degree is Required')
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
            error++;
        } else {
            $('.classesDiv').removeClass('has-error');
        }

            if(field['sectionID'] == '0') {
            $('.sectionDiv').addClass('has-error');
             toastr["error"]('Semester is Required')
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
            error++;
        } else {
            $('.sectionDiv').removeClass('has-error');
        }

        }  //attendance_fine_feetypeID  if close

        

        if(field['statusID'] === '5') {
            $('.statusDiv').addClass('has-error');
            error++;
        } else {
            $('.statusDiv').removeClass('has-error');
        }


        if(field['payment_type'] == '0') {
            $('.payment_type').addClass('has-error');
             toastr["error"]('Payment Type is Required')
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
            error++;
        } else {
            $('.payment_type').removeClass('has-error');
        }

        if(field['statusID'] != 0 && field['statusID'] != 5) {
            if(field['paymentmethodID'] === '0') {
                $('.paymentmethodDiv').addClass('has-error');
                error++;
            } else {
                $('.paymentmethodDiv').removeClass('has-error');
            }
        }

        var totalsubtotal = 0;
        var totalpaidamount = 0;
          

        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#invoiceDataForm')[0]);
            formData.append("feetypeitems", '');
            formData.append("totalsubtotal", totalsubtotal);
            formData.append("totalpaidamount", totalpaidamount);
            formData.append("editID", 0);

        if(field['feess_type'] == '<?php echo $siteinfos->attendance_fine_feetypeID?>') { 
            formData.append("amount", 0);
        }  //attendance_fine_feetypeID  if close
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
            url: "<?=base_url('invoice/saveinvoice')?>",
            data: passData,
            //async: true,
            dataType: "text",

              //cache: false,
        contentType: false,
        processData: false,
       // data: form_data,                         
       // type: 'post',


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
            window.location = "<?=base_url("invoice/index")?>";
        } else {
            $('#addInvoiceButton').removeAttr('disabled');
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

$(document).on('change', "#feess_type", function() {

        
         $("#enrolment_fine").hide('slow');
 
         if (this.value=='<?php echo $siteinfos->enrolment_feetype?>') {
            $("#enrolment_fine").show('slow');
         }
         $("#attendance_t_fine").hide('slow');
 
         if (this.value=='<?php echo $siteinfos->attendance_fine_feetypeID?>') {
            $("#attendance_t_fine").show('slow');
         } 
          
    });
</script>

