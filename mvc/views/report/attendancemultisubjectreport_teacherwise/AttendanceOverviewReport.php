<?php
    $monthArray = array(
      "01" => $this->lang->line('attendanceoverviewreport_january'),
      "02" => $this->lang->line('attendanceoverviewreport_february'),
      "03" => $this->lang->line('attendanceoverviewreport_march'),
      "04" => $this->lang->line('attendanceoverviewreport_april'),
      "05" => $this->lang->line('attendanceoverviewreport_may'),
      "06" => $this->lang->line('attendanceoverviewreport_june'),
      "07" => $this->lang->line('attendanceoverviewreport_july'),
      "08" => $this->lang->line('attendanceoverviewreport_august'),
      "09" => $this->lang->line('attendanceoverviewreport_september'),
      "10" => $this->lang->line('attendanceoverviewreport_october'),
      "11" => $this->lang->line('attendanceoverviewreport_november'),
      "12" => $this->lang->line('attendanceoverviewreport_december'),
    );
?>



<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $subjectID = implode(' ', $subjectID);
        

            $pdfurl = 'attendancemultisubjectreport_teacherwise/pdf/'.$usertype.'/'.$teacherID.'/'.'/'.$subjectID.'/'.strtotime($startdate).'/'.strtotime($enddate);
            $xmlurl = 'attendancemultisubjectreport_teacherwise/xlsx/'.$usertype.'/'.$teacherID.'/'.$subjectID.'/'.'/'.strtotime($startdate).'/'.strtotime($enddate);

            $pdf_preview_uri = base_url($pdfurl);
            $xml_preview_uri = base_url($xmlurl);

            echo btn_printReport('attendanceoverviewreport', $this->lang->line('report_print'), 'printablediv');
           // echo btn_pdfPreviewReport('attendanceoverviewreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('attendanceoverviewreport', $xml_preview_uri, $this->lang->line('report_xlsx'));
            //echo btn_sentToMailReport('attendanceoverviewreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>



<div class="box">
    <!-- form start -->
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('attendanceoverviewreport_reportfor')?> <?=$this->lang->line('attendanceoverviewreport_type')?> - <?=$attendanceoverviewreport_reportfor?> ( <?php if($usertype == 1 && $siteinfos->attendance == 'subject') {
            //var_dump($subjects);
            } ?> <?=$startdate.' '.$enddate?> ) </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($usertype == '1') { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left">
                                    <?php 
                                        echo "Teacherwise Report : ";
                                    ?>
                                </h5>                          
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12"></div>
                <?php }?>
                <div class="col-sm-12">
                    <?php if(customCompute($studentlist)) { ?>
                        <div id="hide-table" style="overflow-x: auto;">
                            <table class="attendance_table">
                                <thead>
                                     <tr height=21  >
                                      <td colspan=5  align="right">Subjects Name</td>
                                      <?php foreach($subjects as $sub){?>
                                      <td colspan=4 align="center" ><?php echo $sub->subject;?></td>
                                      <?php }?>
                                      <td rowspan=2  >Total Lectures Held</td>
                                      <td rowspan=2  >Total Absents</td>
                                      <td rowspan=2  >%age</td>
                                      <td rowspan=2  >Fine</td>
                                   </tr>
                                   <tr  >
                                      <td  >SN</td>
                                      <td >Roll  No </td>
                                      <td >Student Name </td>
                                      <td  > F/Name</td>
                                      <td >Registration  No. </td>
                                      <?php foreach($subjects as $sub){?>
                                      <td  >LECTURES  HELD </td>
                                      <td >ABSENTS</td>
                                      <td  >%age</td>
                                      <td >Fine</td>
                                      <?php }?>
                                      
                                   </tr>
                                </thead>
                                <tbody>
                                <?php 
                                error_reporting(0);
                                 $i = 0; foreach($studentlist  as $user) { 
                                    $student_percantage         = 0;
                                    $student_fine               = 0; 
                                    $student_total_attendance   = 0; 
                                    $student_total_absent       = 0; 
                                    $i++;?>
                                        <tr>
                                          <td><?php echo $i;?></td>
                                          <td><?php echo $user->roll?></td>
                                          <td><?php echo $user->name?></td>
                                          <td> <?php echo $parents[$user->parentID]?></td>
                                          <td><?php echo $user->registerNO?></td>
                                          
                                          <?php foreach($subjects as $sub){?>
                                              <td><?php 

                                              $total_sub_at_held =  $presentCount[$user->studentID][$sub->subjectID]+$lateexcuseCount[$user->studentID][$sub->subjectID]+$lateCount[$user->studentID][$sub->subjectID]+$absentCount[$user->studentID][$sub->subjectID];
                                              $student_total_attendance   += $total_sub_at_held; 
                                              $student_total_absent       += $absentCount[$user->studentID][$sub->subjectID];
                                              echo $total_sub_at_held;
                                              ?></td>
                                              <td><?php echo $absentCount[$user->studentID][$sub->subjectID];?></td>
                                              <td><?php $sub_per    =   100-round(((($absentCount[$user->studentID][$sub->subjectID])/$total_sub_at_held)*100),2);
                                                    echo $sub_per;
                                                     $student_percantage += $sub_per;
                                    
                                                    ?></td>
                                              <td><?php 
                                             // var_dump($sub_per);
                                               if($sub_per<$siteinfos->attendance_fine_percentage){
                                                $per_fine_number    =   $siteinfos->attendance_fine_percentage-$sub_per;
                                                $sub_fine           =   $siteinfos->attendance_per_percantage_fine*$per_fine_number;
                                                echo $sub_fine;
                                                 $student_fine       += $sub_fine;
                                              }else{
                                                echo "0";
                                              }?></td>
                                          <?php }?>

                                          <td><?php echo $student_total_attendance;?></td>
                                          <td><?php echo $student_total_absent;?></td>
                                          <td><?php echo ($student_percantage/count($subjects));?></td>
                                          <td><?php echo $student_fine;?></td>
                                           
                                       </tr>
                                <?php  $i++;} ?>         
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('attendanceoverviewreport_data_not_found')?></b></p>
                    </div>
                    <?php } ?>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>

            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('attendanceoverviewreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('attendanceoverviewreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('attendanceoverviewreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("attendanceoverviewreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendanceoverviewreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendanceoverviewreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("attendanceoverviewreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'usertype'   : '<?=isset($usertype) ? $usertype : "" ?>',
            'subjectID'  : '<?=isset($subjectID) ? $subjectID : "" ?>',
            'monthID'    : '<?=isset($monthdays) ? $monthdays : "" ?>'
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/send_pdf_to_mail')?>",
                data: field,
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