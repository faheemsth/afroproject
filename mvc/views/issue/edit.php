
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("issue/index/$set")?>"><?=$this->lang->line('menu_issue')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    
                    
                 

                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?='Class'?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array['0'] = 'Select Class';
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("bookclassesID", $array, set_value("bookclassesID", $issue->bookclassesID), "id='bookclassesID' class='form-control select2' disabled ");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('book')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group'  id='bookDiv'>";
                    ?>
                        <label for="book" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_book")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array('0' => $this->lang->line('issue_select_book'));
                                foreach ($books as $book) {
                                    $array[$book->bookID] = $book->book;
                                }
                                echo form_dropdown("book", $array, set_value("book", $issue->bookID), "id='book' class='form-control select2' disabled ");
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
                        <label for="author" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_author")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="author" name="author" readonly="readonly" value="<?=set_value('author', $issue->author)?>" >
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
                        <label for="subject_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject_code" name="subject_code" readonly="readonly" value="<?=set_value('subject_code', $issue->subject_code)?>" >
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
                        <label for="accession_number" class="col-sm-2 control-label">
                            <?='Accession Number'?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="accession_number" name="accession_number" readonly="readonly" value="<?=set_value('accession_number', $issue->accession_number)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('accession_number'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="usertypeID" class="col-sm-2 control-label">
                            <?='Role'?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $userArray[0] = $this->lang->line('asset_assignment_select_usertype');
                                if(customCompute($usertypes)) {
                                    foreach ($usertypes as $key => $usertype) {
                                        $userArray[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("usertypeID", $userArray, set_value("usertypeID", $issue->usertypeID), "id='usertypeID' class='form-control select2'  disabled ");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classesID'))
                            echo "<div id='classesDiv' class='form-group has-error' >";
                        else
                            echo "<div id='classesDiv' class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?='Degree'?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_class')
                                );

                                if(customCompute($classes)) {
                                    foreach ($classes as $key => $class) {
                                        $classArray[$class->classesID] = $class->classes;
                                    }
                                }

                                echo form_dropdown("classesID", $classArray, set_value("classesID", $issue->classesID), "id='classesID' class='form-control select2'  disabled ");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                     <div class="form-group" id="sectionDiv">
                        <label for="sectionID" class="col-sm-2 control-label"><?='Semester'?></label>
                        <div class="col-sm-6">
                        <?php
                            $sectionArray = array(
                                "0" => $this->lang->line("balancefeesreport_please_select"),
                            );
                               if(customCompute($sections)) {
                                    foreach ($sections as  $section) {
                                        $sectionArray[$section->sectionID] = $section->section;
                                    }
                                }
                            echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $issue->sectionID), "id='sectionID' class='form-control select2' disabled ");
                         ?>
                     </div>
                    </div>

                    <?php
                        if(form_error('check_out_to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="check_out_to" class="col-sm-2 control-label">
                            <?='Check Out To'?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $userArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_user')
                                );  

                                if(customCompute($checkOutToUesrs)) {
                                    foreach ($checkOutToUesrs as $checkOutToUesrKey => $checkOutToUesr) {
                                        $userArray[$checkOutToUesrKey] = $checkOutToUesr;
                                    }
                                }

                                echo form_dropdown("check_out_to", $userArray, set_value("check_out_to", $issue->check_out_to), "id='check_out_to' class='form-control select2' disabled ");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('check_out_to'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('serial_no')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="serial_no" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_serial_no")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="serial_no" name="serial_no" value="<?=set_value('serial_no', $issue->serial_no)?>" >
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
                        <label for="issue_date" class="col-sm-2 control-label">
                            Issue Date <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="issue_date" readonly name="issue_date" value="<?=set_value('issue_date', $issue->issue_date)?>" >
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
                        <label for="due_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_due_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="due_date" name="due_date" value="<?=set_value('due_date', $issue->due_date)?>" >
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
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_note")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="note" name="note" value="<?=set_value('note', $issue->note)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    
                

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_issue")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('#due_date').datepicker();
</script>



<script type="text/javascript">
$('.select2').select2();
$("#due_date").datepicker();
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

 

        $('#usertypeID').change(function() {
            var usertypeID = $(this).val();
            if(usertypeID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allusers')?>",
                    data: {'usertypeID' : usertypeID },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html('');
                        $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                        if(usertypeID == 3) {
                            $('#classesDiv').show();
                            $('#sectionDiv').show();
                            $('#classesID').html(data);
                        } else {
                            $('#classesDiv').hide();
                            $('#sectionDiv').hide();
                            $('#check_out_to').html(data);
                        }
                        
                        $('#check_out_to').trigger('change');
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

$(document).ready(function () {
             <?php if ($issue->usertypeID==3) {?>


                 $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allstudent_get')?>",
                    data: {'classesID' : <?php echo $issue->classesID; ?>,'studentID' : <?php echo $issue->check_out_to; ?> },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                        if(usertypeID == 3) {
                            $('#classesDiv').show();
                            $('#classesID').html(data);
                        } else {
                            $('#classesDiv').show();
                            $('#check_out_to').html(data);
                        }

                        $('#check_out_to').trigger('change');
                    }
                });
                <?php }else{
                ?>


                 $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allusers_get')?>",
                    data: {'usertypeID' : <?php echo $issue->usertypeID; ?>,'selectedid' : <?php echo $issue->check_out_to; ?> },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                        if(usertypeID == 3) {
                            $('#classesDiv').show();
                            $('#classesID').html(data);
                        } else {
                            $('#classesDiv').hide();
                            $('#sectionDiv').hide();
                            $('#check_out_to').html(data);
                        }

                        $('#check_out_to').trigger('change');
                    }
                });
                <?php 
             }?>
           

          });
</script>
