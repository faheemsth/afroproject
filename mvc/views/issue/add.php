
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("issue/index")?>"><?=$this->lang->line('menu_issue')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
             
                <form class="form-horizontal" role="form" method="post">
                 
                 <div class="col-sm-6">
                     
                    <fieldset class="setting-fieldset">
                        <legend class="setting-legend">Book Detail</legend>
                    

                    

                    <?php 
                        if(form_error('book')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group'   >";
                    ?>
                        <label for="book" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_book")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <?php
                                $array = array('0' => $this->lang->line('issue_select_book'));
                                foreach ($books as $book) {
                                    $array[$book->bookID] = $book->book ." (". $book->accession_number .") (". $book->subject_code .") (".$book->quantity.") "  ;
                                }
                                echo form_dropdown("book", $array, set_value("book"), "id='book' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('author')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="author" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_author")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="author" name="author" readonly="readonly" value="<?=set_value('author')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('author'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject_code')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_code" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="subject_code" name="subject_code" readonly="readonly" value="<?=set_value('subject_code')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_code'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('accession_number')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="accession_number" class="col-sm-12 control-label">
                            <?='Accession Number'?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="accession_number" name="accession_number" readonly="readonly" value="<?=set_value('accession_number')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('accession_number'); ?>
                        </span>
                    </div>


                    </fieldset>


                    <fieldset class="setting-fieldset">
                        <legend class="setting-legend">Issue Detail</legend>

                    <?php 
                        if(form_error('serial_no')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="serial_no" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_serial_no")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="serial_no" name="serial_no" value="<?=set_value('serial_no',$serial_no)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('serial_no'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('issue_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-12 control-label">
                           Issue Date <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="issue_date" name="issue_date" value="<?=set_value('issue_date',date('d-m-Y'))?>" readonly >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('issue_date'); ?>
                        </span>
                    </div>
                    

                    <?php 
                        if(form_error('due_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_due_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="due_date" name="due_date" value="<?=set_value('due_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('due_date'); ?>
                        </span>
                    </div>
                    

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-12 control-label">
                            <?=$this->lang->line("issue_note")?>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="note" name="note" value="<?=set_value('note')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>
                    </fieldset>

                 </div> 
                 <div class="col-sm-6">
                     
                    <fieldset class="setting-fieldset">
                        <legend class="setting-legend">User Detail</legend>

                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="usertypeID" class="col-sm-12 control-label">
                            <?='Role'?>
                        </label>
                        <div class="col-sm-12">
                            <?php
                                $userArray[0] = $this->lang->line('asset_assignment_select_usertype');
                                if(customCompute($usertypes)) {
                                    foreach ($usertypes as $key => $usertype) {
                                        $userArray[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("usertypeID", $userArray, set_value("usertypeID"), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    

                     <div class="form-group" id="sectionDiv">
                        <label for="sectionID" class="col-sm-12 control-label"><?='Semester'?></label>
                        <div class="col-sm-12">
                        <?php
                            $sectionArray = array(
                                "0" => $this->lang->line("balancefeesreport_please_select"),
                            );
                            echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                         ?>
                     </div>
                    </div>

                    <?php
                        if(form_error('check_out_to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="check_out_to" class="col-sm-12 control-label">
                            <?='Check Out To'?>
                        </label>
                        <div class="col-sm-12">
                            <?php
                                $userArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_user')
                                );  

                                if(customCompute($checkOutToUesrs)) {
                                    foreach ($checkOutToUesrs as $checkOutToUesrKey => $checkOutToUesr) {
                                        $userArray[$checkOutToUesrKey] = $checkOutToUesr;
                                    }
                                }

                                echo form_dropdown("check_out_to", $userArray, set_value("check_out_to"), "id='check_out_to' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('check_out_to'); ?>
                        </span>
                    </div>
                    <div id="load_idcardreport"></div>
                    <div id="load_history"></div>
                    </fieldset>

                 </div>
                 <div class="clearfix"></div>
                 

                    

                    <div class="form-group col-sm-12">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-success" value="Issue Book" >
                        </div>
                    </div>
                </form>

             
        </div>
    </div>
</div>


<script type="text/javascript">
$('.select2').select2();
$("#due_date").datepicker();
$("#issue_date").datepicker();
$('#book').change(function() {
    var bookID = $(this).val();
    if(bookID === '0') {
        $(this).val(0);
        $('#author').val(' ');
        $("#subject_code").val('');
    } else {
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?=base_url('issue/bookIDcall')?>",
            data: "bookID=" + bookID,
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);
                console.log(response);
                if(response != "") {
                    $('#author').val(response.author);
                    $('#subject_code').val(response.subject_code);
                    $('#accession_number').val(response.accession_number);
                } else {
                    $('#author').val(' ');
                    $("#subject_code").val('');
                }
            }
        });
    }
});

$(function(){
    $('#sectionDiv').hide('slow');
    $('#bookDiv').hide('slow');
    // $('#studentDiv').hide('slow');
});

        $('#usertypeID').change(function() {
            var usertypeID = $(this).val();
            if(usertypeID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allusers_by_usertypeID')?>",
                    data: {'usertypeID' : usertypeID },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                         
                            $('#check_out_to').html(data);
                         
                    }
                });
            } else {
                $('#classesDiv').hide();
            }
        });

        $("#bookclassesID").change(function(){
            var classesID = $(this).val();
            $('#book').html("<option value='0'>Select Book</option>");
            if(classesID == '0'){
                $("#bookDiv").hide('slow');
                $("#bookDiv").hide('slow');
            } else {
                $("#bookDiv").show('slow');
                $("#bookDiv").show('slow');
            }

            if(classesID != 0){
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('book/getBook')?>",
                    data: {"bookclassesID" : classesID},
                    dataType: "html",
                    success: function(data) {
                       $('#book').html(data);
                    }
                });
            }

        });

        $('#classesID').change(function() {
            var classesID = $(this).val();
            $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");

            if(classesID == '0'){
                $("#sectionDiv").hide('slow');
                $("#studentDiv").hide('slow');
            } else {
                $("#sectionDiv").show('slow');
                $("#studentDiv").show('slow');
            }

            if(classesID != 0) {

                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('balancefeesreport/getSection')?>",
                    data: {"classesID" : classesID},
                    dataType: "html",
                    success: function(data) {
                       $('#sectionID').html(data);
                    }
                });
                // $.ajax({
                //     type: 'POST',
                //     url: "<?=base_url('asset_assignment/allstudent')?>",
                //     data: {'classesID' : classesID },
                //     dataType: "html",
                //     success: function(data) {
                //         $('#check_out_to').html(data);
                //     }
                // });
            }
        });

        $(document).on('change', "#sectionID", function() {
            // $('#load_balancefeesreport').html("");
            var sectionID = $(this).val();
            
            $('#check_out_to').html("<option value='0'>" + "All Student" +"</option>");
            $('#check_out_to').val(0);

            var classesID = $('#classesID').val();
            if(sectionID != 0 && classesID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('balancefeesreport/getStudent')?>",
                    data: {"classesID":classesID, "sectionID" : sectionID},
                    dataType: "html",
                    success: function(data) {
                       $('#check_out_to').html(data);
                    }
                });
            }
        });


</script>


<script type="text/javascript">
    
 

 
 
 
    $(document).on('change','#check_out_to', function() {
        var usertypeID = $('#usertypeID').val();
        var classesID = 0;
        var sectionID =0;
        var userID    = $('#check_out_to').val();
        var type      = 1;
        var background= 2;
        var error = 0;
        var field = {
            'usertypeID': usertypeID,
            'classesID' : classesID,
            'sectionID' : sectionID,
            'userID'    : userID,
            'type'      : type,
            'background': background,
        }

        if(usertypeID == 0 ) {
            $('#usertypeIDDiv').addClass('has-error');
            error++;
        } else {
            $('#usertypeIDDiv').removeClass('has-error');
        }

        

        if(type == 0 ) {
            $('#typeDiv').addClass('has-error');
            error++;
        } else {
            $('#typeDiv').removeClass('has-error');
        }

        if(background == 0 ) {
            $('#backgroundDiv').addClass('has-error');
            error++;
        } else {
            $('#backgroundDiv').removeClass('has-error');
        } 

        if(error == 0 ) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        var passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type:'POST',
            url:'<?=base_url('idcardreport/getIdcardReport_library')?>',
            data:passData,
            dataType:'html',
            success:function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
        $.ajax({
            type:'POST',
            url:'<?=base_url('issue/get_issue_history')?>',
            data:passData,
            dataType:'html',
            success:function(data) {
                 
                 $('#load_history').html(data);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_idcardreport').html(response.render);
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



