<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?php
             echo btn_add_pdf('subjectenrollment/print_preview/'.$subjectenrollment->subjectenrollmentID, $this->lang->line('pdf_preview'))
            ?> 
           <!--  <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button> -->
        </div>


        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("subjectenrollment/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                <li class="active"><?=$this->lang->line('menu_view')?></li>
            </ol>
        </div>

    </div>

</div>


<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    
            <table width="20%" style="float: left;">
                <tbody>
                    <tr>
                        <td style="">
                           <!--  <img style="width: 25%;height:auto;" src="<?php echo base_url('uploads/images/'.$siteinfos->photo)?>"> -->
                             <?php
                            if($siteinfos->photo) {
                                $array = array(
                                    "src" => base_url('uploads/images/'.$siteinfos->photo),
                                    'width' => '100px',
                                    'height' => '100px',
                                    'class' => 'img-circle',
                                    //'style' => 'width: 25%;height:auto;',
                                );
                                echo img($array);
                            } 
                        ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="60%" style="float: left;">
                <tbody>
                    <tr>
                        <td style="text-align: center;">
                            <h3><u><?php echo $siteinfos->sname;?></u></h3>
                            <p><u>COURSE REGISTRATION FORM</u></p>
                            <p>Enrollment to Fall, 2022-2023 </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="20%" style="float: right;">
                <tbody>
                    <tr>
                        <td style="text-align: right;">
                            <img style="width: 130px;height:130px;float: right; border: 1px solid #0B3C5C; object-fit: contain;" src="<?=imagelink($enroll_student->photo)?>">
                             
                        </td>
                    </tr>
                </tbody>
            </table>

            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            <b>Student Name:</b> <u>
                                <?php echo $enroll_student->name;?>
                            </u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Father Name:</b> <u><?php echo $parents->name;?></u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CNIC #:</b> <u><?php echo $enroll_student->cnic;?></u>&nbsp;&nbsp;&nbsp;<b>Registration #:</b> <u><?php echo $enroll_student->registerNO;?></u>&nbsp;&nbsp;&nbsp;<b>Faculty:</b> <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Programmes: </b> <u><?php   echo $student->srclasses;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Department: </b> <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Date of Admission: </b> <u>1-Nov-<?php echo $enroll_student->admission_year;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Roll #:   </b> <u><?php echo $enroll_student->roll;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <!--  <b>Session:  </b> <u><?php echo $enroll_student->session_year;?></u> -->
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="1" style="margin-top: 25px;">
                <tbody>
                    <tr>
                        <td style="text-align: center; padding-bottom: 15px;" colspan="6">
                            <p><b>Course to be taken during the semester</b></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <p><b>SR#</b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Course Code</b></p>
                        </td>
                        <td>
                            <p><b>Course Title</b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Credit Hours </b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Total Marks </b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Enroll Type </b></p>
                        </td>
                    </tr>

                    <?php 
                    if (count($enrolledsubjects)) {
                        $sr                 =   1;
                        $total_credit_hours =   0;
                        foreach ($enrolledsubjects as $es) {
                            $total_credit_hours     +=  $es->credit_hour;
                            ?>
                    <tr>
                        <td  style="text-align: center;">
                            <p><b><?php echo $sr; $sr++;?></b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><?php echo $es->subject_code;?></p>
                        </td>
                        <td>
                            <p><?php echo $es->subject;?></p>
                        </td>
                        <td  style="text-align: center;">
                            <p>
                                <?php echo $es->credit_hour;?>
                                <?php echo $es->credit_hour_text;?>
                                
                            </p>
                        </td>
                        <td  style="text-align: center;">
                            <p><?php echo $es->finalmark;?></p>
                        </td>
                        <td  style="text-align: center;">
                            <p>Regular</p>
                        </td>
                    </tr>    
                    <?php    } // foreach
                    } //if
                    ?>
                    
                     
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <b>Total</b>
                        </td>
                        <td style="text-align: center;">
                            <b><?php echo $total_credit_hours;?></b>
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="margin-top: 25px;">
                <tbody>
                    <tr>
                        <td>
                            <b>NOTE:</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>1. 75% attendance is compulsory, otherwise students will not be allowed for final exams</p>
                            <p>2. It’s compulsory to pay dues as per due date notified on 28.09.2022.</p>
                            <p>3. It’s compulsory to pay minimum one Installment with enrollment.</p>
                            <p>4. It’s compulsory to pay 50% fee before mid-term exams.</p>
                            <p>5. If you will not pay your dues with in time, your GCUF Enrollment/Registration for Fall-2022 will not be sent.</p>
                            <p>6. Late Payment fine/short attendance fine will be charged as per rules.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            Under Taking
                        </td>
                    </tr>
                    <tr>
                        <td>
                            I_______________________ solemnly affirm that I have read all the information, provided is correct as per my
                            knowledge and I will follow the rules and regulation of the Institute.
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="margin-top: 30px;">
                <tbody>
                    <tr>
                        <td style="text-align: right;">
                            <p>________________________________</p><br>
                            <b>(Student Signature)</b>  
                        </td>
                    </tr>
                </tbody>
            </table>
            <p style="text-align: center; margin-top: 30px;">This is System Generated document. Signature is not required.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('subjectenrollment/send_mail');?>" method="post">
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
        var id = "<?=$subjectenrollment->subjectenrollmentID;?>";
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
                url: "<?=base_url('subjectenrollment/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message,
                dataType: "html",
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
</script>

