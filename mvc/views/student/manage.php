

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <div class="box-body"> 
                <form role="form" method="post"> 

                     <fieldset  class="setting-fieldset" >
                        <legend class="setting-legend">Block Detail</legend>

                    <div class="col-sm-3 classesDiv form-group <?=form_error('classesID') ? 'has-error' : '' ?>" >
                        <label for="classesID">
                            <?=$this->lang->line("student_classes")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                // $classesArray = array('0' => $this->lang->line("invoice_select_classes"));
                                $classesArray = array('0' => 'All Degree');
                                if(customCompute($classes)) {
                                    foreach ($classes as $classa) {
                                        $classesArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID[]", $classesArray, set_value("classesID"), "id='classesID' multiple class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>



                    


                    <div class="col-sm-3 form-group <?=form_error('numric_code') ? 'has-error' : '' ?>" >
                        <label for="numric_code">
                            Semester Number <span class="text-red">*</span>
                        </label>
                            <?php
                                  $numricArray = array(
                                    '0'     => 'All',
                                    '1'     => '1',
                                    '2'     => '2',
                                    '3'     => '3',
                                    '4'     => '4',
                                    '5'     => '5',
                                    '6'     => '6',
                                    '7'     => '7',
                                    '8'     => '8',
                                    '9'     => '9',
                                    '10'    => '10',
                                    '11'    => '11'
                                    ,);
                                 
                                echo form_dropdown("numric_code[]", $numricArray, set_value("numric_code"), "id='numric_code' multiple class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('numric_code'); ?>
                        </span>
                    </div>

                   
                    <div class="col-sm-3 form-group" >
                        <label for="Fees type">
                            <?=$this->lang->line("invoice_all_student_block")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $statusArray = array(
                                0 => $this->lang->line("invoice_all_select_block"),
                                1 => $this->lang->line("invoice_block"),
                                2 => $this->lang->line("invoice_percent")
                            );

                            echo form_dropdown("feess_type", $statusArray, set_value("feess_type"), "id='feess_type' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('feess_type'); ?>
                        </span>
                    </div>
                   
                    <div class="col-sm-3 form-group" >
                        <label for="Fees type">
                            Invoice Type <span class="text-red">*</span>
                        </label>
                        <?php
                            $type_vArray = get_general_feetype();

                            echo form_dropdown("type_v[]", $type_vArray, set_value("type_v"), "id='type_v' multiple class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('type_v'); ?>
                        </span>
                    </div>

                    
                    <div class="col-sm-3 form-group feess_balanceDiv" >
                        <label for="Fees type">
                            <?='Previous Balance'?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $statusArray = array(
                                0 => $this->lang->line("invoice_all_select_block"),
                                1 => 'With Previous Balance',
                                2 => 'Without Previous Balance'
                            );

                            echo form_dropdown("feess_balance", $statusArray, set_value("feess_balance"), "id='feess_balance' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('feess_type'); ?>
                        </span>
                    </div>  





                    
                    <div class="col-sm-3 form-group" >
                        <label for="fine_include">
                            <?='Fine Include'?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $statusArray = array(
                                0 => 'Please Select',
                                1 => 'With Fine & Block',
                                2 => 'Without Fine & Block',
                                3 => 'Only Fine & Not Block',
                            );

                            echo form_dropdown("fine_include", $statusArray, set_value("fine_include"), "id='fine_include' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('fine_include'); ?>
                        </span>
                    </div>                   
                    <div class="col-sm-3 ammountDiv form-group feess_amountDiv" >
                        <label for="ammount">
                            <?='Percentage'?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control"  name="charges_ammount"  id="charges_ammount">
                        <span class="text-red">
                            <?php echo form_error('charges_ammount'); ?>
                        </span>
                    </div>

                     </fieldset>
                    <span class="clearfix"></span>

                    <fieldset id="finediv" class="setting-fieldset" >
                        <legend class="setting-legend">Invoice Detials</legend>
                        
                  
                    <span class="clearfix"></span>
                    <div class="col-sm-3 form-group" >
                        <label for="total_fine">
                            <?='Total Fine'?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control"  name="total_fine"  id="total_fine">
                        <span class="text-red">
                            <?php echo form_error('total_fine'); ?>
                        </span>
                    </div>

                    
                    <div class="col-sm-3 feetypesID form-group <?=form_error('feetypesID') ? 'has-error' : '' ?>">
                        <label for="feetypesID">
                            <?='Fee Type'?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $studentArray = array('0' => 'Please Select');
                                if(customCompute($feetypes)) {
                                    foreach ($feetypes as $f) {
                                        $studentArray[$f->feetypesID] = $f->feetypes;
                                    }
                                }
                                echo form_dropdown("feetypesID", $studentArray, set_value("feetypesID"), "id='feetypesID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('feetypesID'); ?>
                        </span>
                    </div>


                    <div class="col-sm-3 form-group" >
                        <label for="from_date">
                            Issue Date <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control date"  name="from_date"  id="from_date">
                        <span class="text-red">
                            <?php echo form_error('from_date'); ?>
                        </span>
                    </div>


                    <div class="col-sm-3 form-group" >
                        <label for="to_date">
                            Due Date <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control date"  name="to_date"  id="to_date">
                        <span class="text-red">
                            <?php echo form_error('to_date'); ?>
                        </span>
                    </div>
                    </fieldset>

                    <span class="clearfix"></span>


                    <input type="submit" class="btn btn-success" value="<?='Block'?>" >
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$( ".select2" ).select2();
$('.date').datepicker();
$(function(){
        $('#sectionDiv').hide('slow');
        $(".feess_balanceDiv").hide('slow');
        $(".feess_amountDiv").hide('slow');
         $('#finediv').hide('slow');
    });
    
    $(document).on('change', "#classesID", function() {
        $('#load_balancefeesreport').html("");
        var classesID = $(this).val();
        $("#feess_balance").val(0);
        $('#charges_ammount').val(0);
        // $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("balancefeesreport_please_select")?>" +"</option>");
        // $('#studentID').val(0);
        
        if(classesID == '0'){
            $("#sectionDiv").hide('slow');
            $("#studentDiv").hide('slow');
        } else {
            $("#sectionDiv").show('slow');
            $("#studentDiv").show('slow');
        }

        // if(classesID !=0) {
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?=base_url('balancefeesreport/getSection')?>",
        //         data: {"classesID" : classesID},
        //         dataType: "html",
        //         success: function(data) {
        //            $('#sectionID').html(data);
        //         }
        //     });
        // }
    });


    $(document).on('change', "#fine_include", function() {
         
        var fine_include = $(this).val();
        $("#feess_balance").val(0);
        $('#charges_ammount').val(0);
        // $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("balancefeesreport_please_select")?>" +"</option>");
        // $('#studentID').val(0);
        
        if(fine_include == '0' || fine_include == '2' ){
            $("#finediv").hide('slow');
            
        } else {
            $("#finediv").show('slow');
        }
 
    });

    

    $(document).on('change', "#feess_type", function() {
        var block = $(this).val();
        
        $('#feess_balance').val(0);
        // $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("balancefeesreport_please_select")?>" +"</option>");
        $('#charges_ammount').val(0);
        
        if(block == '0'){
            $(".feess_balanceDiv").hide('slow');
            $(".feess_amountDiv").hide('slow');
        } else if (block == '1') {
            $(".feess_balanceDiv").hide('slow');
            $(".feess_amountDiv").hide('slow');
        } else {
            $(".feess_balanceDiv").show('slow');
            $(".feess_amountDiv").show('slow');
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
                url: "<?=base_url('balancefeesreport/getStudent')?>",
                data: {"classesID":classesID, "sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });

$('input[type=radio][name=discount_type]').change(function() {
    if (this.value == 'percentage') {
        $("label[for='discount']").html('Discount( % ) <span class="text-red">*</span>');
    }
    else if (this.value == 'amount') {
        $("label[for='discount']").html('Discount( value ) <span class="text-red">*</span>');
    }
});

$('#discount').keyup(function() {
     var discount_type= $('input[type="radio"]:checked').val();
     var discount = $(this).val();
     var totalfee = $("#totalfee").val();
     if(discount != '' && totalfee != ''){
        if(discount_type == 'percentage'){
            if( discount > 100 ){
                alert('Percentage value can not more than 100');
                return false;
            }else{
                var total = totalfee - 15000;
                var totals = total * discount / 100;
                console.log(totals);
                //  var netfee = totals + 15000;
                var netfee = totalfee - totals;
                var totalshtml = `<label class="col-sm-2">Net Fee</label><div class="col-sm-6">`+netfee+`</div>`;
            }     
         }else{
            var total = totalfee - 15000;
            var totals = discount;
            console.log(totals);
            var netfee = totalfee - totals;
            var totalshtml = `<label class="col-sm-2">Net Fee</label><div class="col-sm-6">`+netfee+`</div>`;
         }     
     }else{
        var totalshtml = '';     
     }
     
    $(".net_fee").html('');
    $(".net_fee").append(totalshtml);
    $("#net_fee").val(netfee);
    
});



</script>
