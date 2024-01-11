<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
        if($fromdate != '' || $todate != '' ) {
            $generatepdfurl = base_url("asset_assignmentreport/pdf/".$asset_assignmentcustomertypeID."/".$asset_assignmentcustomerID."/".strtotime($fromdate)."/".strtotime($todate));
            $generatexmlurl = base_url("asset_assignmentreport/xlsx/".$asset_assignmentcustomertypeID."/".$asset_assignmentcustomerID."/".strtotime($fromdate)."/".strtotime($todate));
        } else {
            $generatepdfurl = base_url("asset_assignmentreport/pdf/".$asset_assignmentcustomertypeID."/".$asset_assignmentcustomerID."/");
            $generatexmlurl = base_url("asset_assignmentreport/xlsx/".$asset_assignmentcustomertypeID."/".$asset_assignmentcustomerID."/");
        }
        echo btn_printReport('asset_assignmentreport', $this->lang->line('report_print'), 'printablediv');
        echo btn_pdfPreviewReport('asset_assignmentreport',$generatepdfurl, $this->lang->line('report_pdf_preview'));
        echo btn_xmlReport('asset_assignmentreport',$generatexmlurl, $this->lang->line('report_xlsx'));
        echo btn_sentToMailReport('asset_assignmentreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('asset_assignmentreport_report_for')?> - <?=$this->lang->line('asset_assignmentreport_asset_assignment')?>  </h3>
    </div><!-- /.box-header -->

    <div id="printablediv">
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>

                <?php if($fromdate != '' && $todate != '' ) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?=$this->lang->line('asset_assignmentreport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
                        </h5>
                        <h5 class="pull-right">
                            <?=$this->lang->line('asset_assignmentreport_todate')?> : <?=date('d M Y',strtotime($todate))?></p>
                        </h5>
                    </div>
                <?php }  elseif($asset_assignmentcustomertypeID != 0 && $asset_assignmentcustomerID != 0 ) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('asset_assignmentreport_role')." : ";
                                echo isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : '';
                            ?>
                        </h5>
                        <h5 class="pull-right">
                            <?php
                                echo $this->lang->line('asset_assignmentreport_user')." : ";
                                if(isset($users[3][$asset_assignmentcustomerID])) {
                                    $userName = isset($users[3][$asset_assignmentcustomerID]->name) ? $users[3][$asset_assignmentcustomerID]->name : $users[3][$asset_assignmentcustomerID]->srname;
                                    echo $userName;
                                }
                            ?>
                        </h5>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('asset_assignmentreport_role')." : ";
                                 echo isset($usertypes[$asset_assignmentcustomertypeID]) ? $usertypes[$asset_assignmentcustomertypeID] : $this->lang->line('asset_assignmentreport_all');
                            ?>
                        </h5>
                    </div>
                <?php } ?>

                <div class="col-sm-12" style="margin-top:5px">
                    <?php if (customCompute($asset_assignments)) { ?>
                        <div id="fees_collection_details" class="tab-pane active">
                            <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assetID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assigned_quantity')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_usertypeID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_to')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_due_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_in_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_status')?></th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($asset_assignments)) {$i = 1; foreach($asset_assignments as $asset_assignment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assetID')?>">
                                        <?php echo $asset_assignment->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assigned_quantity')?>">
                                        <?php echo $asset_assignment->assigned_quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_usertypeID')?>">
                                        <?php echo $asset_assignment->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_to')?>">
                                        <?php echo $asset_assignment->assigned_to; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_due_date')?>">
                                        <?php echo isset($asset_assignment->due_date) ? date('d M Y', strtotime($asset_assignment->due_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_date')?>">
                                        <?php echo isset($asset_assignment->check_out_date) ? date('d M Y', strtotime($asset_assignment->check_out_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_in_date')?>">
                                        <?php echo isset($asset_assignment->check_in_date) ? date('d M Y', strtotime($asset_assignment->check_in_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_status')?>">
                                        <?php
                                            if($asset_assignment->status == 1) {
                                                echo $this->lang->line('asset_assignment_checked_out');
                                            } elseif($asset_assignment->status == 2) {
                                                echo $this->lang->line('asset_assignment_in_storage');
                                            }
                                        ?>
                                    </td>
                                  
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
                        </div>
                    <?php } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('asset_assignmentreport_data_not_found')?></b></p>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('asset_assignmentreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('asset_assignmentreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('asset_assignmentreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("asset_assignmentreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("asset_assignmentreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("asset_assignmentreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("asset_assignmentreport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('asset_assignmentreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var field = {
            'to'      : $('#to').val(),
            'subject' : $('#subject').val(),
            'message' : $('#message').val(),
            'asset_assignmentcustomertypeID' : "<?=$asset_assignmentcustomertypeID?>", 
            'asset_assignmentcustomerID' : "<?=$asset_assignmentcustomerID?>",
            'fromdate'     : "<?=$fromdate?>",
            'todate'       : "<?=$todate?>"
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('asset_assignmentreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('asset_assignmentreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('asset_assignmentreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == false) {
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
