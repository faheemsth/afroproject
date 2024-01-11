<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
        // $pdf_preview_uri = base_url('resultcardreport/pdf/' . $resultID . '/' . $classesID . '/' . $sectionID . '/' . $studentID . '/' . $typeID . '/' . $backgroundID);
        // echo btn_printReport('resultcardreport', $this->lang->line('admitcardreport_print'), 'printablediv');
        // echo btn_pdfPreviewReport('resultcardreport', $pdf_preview_uri, $this->lang->line('admitcardreport_pdf_preview'));
        // echo btn_sentToMailReport('resultcardreport', $this->lang->line('admitcardreport_send_pdf_to_mail'));
        ?>

       
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray row">
        <div class="col-xs-6">
        <h3 class="box-title text-navy" style="text-align: left;"><i class="fa fa-clipboard"></i>
            Report For - Result Card
        </h3>
        </div>

        <div class="box-title text-navy col-xs-6" style="text-align: right; right: 1rem;">
          <?php if(permissionChecker('all_result_cards_download')){  ?>
            <a href="resultcardreport/downloadzip/<?= $resultID . '/' . $classesID . '/' . $sectionID . '/' . $studentID ?>" class="fa fa-download btn btn-success mr-0" data-toggle="tooltip" title="Download All Reports"> Download All Reports</a>
            <?php } ?>
        </div>


    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">
            .mainadmincardreport {
                max-width: 794px;
                margin-left: auto;
                margin-right: auto;
                -webkit-print-color-adjust: exact;
                overflow: hidden;
            }

            .admincardreport {
                border: 1px solid #ddd;
                overflow: hidden;
                padding: 20px 50px;
                margin-bottom: 10px;
                min-height: 443px;
                <?php if ($backgroundID == 1) { ?>background: url("<?= base_url('uploads/default/admitcard-border.png') ?>") !important;
                background-size: 100% 100% !important;
                <?php } ?>position: relative;
            }

            .studentinfo {
                width: 100%;
            }

            .studentinfo p {
                width: 50%;
                float: left;
                margin-bottom: 1px;
                padding: 0 0px;
                font-size: 12px;
            }

            .studentinfo p span {
                font-weight: bold;
            }

            .admitcardbody {
                float: left;
                width: 100%;
                color: #000;
                padding: 0px 0px;
            }

            .admitcardbody h3 {
                text-align: center;
                border-bottom: 1px solid #ddd;
                padding-bottom: 6px;
                color: #000;
                font-weight: 500;
                margin: 0px;
                font-size: 14px;
            }

            .subjectlist {
                width: 100%;
                float: left;
                font-family: monospace;
            }

            .subjectlist table {
                text-align: center;
                font-size: 10px;
                width: 100%;
            }

            .subjectlist table td {
                padding: 2px;
                border: 1px solid #ddd;
            }

            .admitcardfooter {
                float: left;
                width: 100%;
                font-weight: normal;
                margin-top: 15px;
                color: #000;
            }

            .account_signature {
                float: left;
            }

            .headmaster_signature {
                float: right;
            }


            .mainadmincardreport .admincardreport h2 {
                color: #000;
                margin-bottom: 0px;
            }

            .mainadmincardreport .admincardreport h5 {
                color: #000;
            }

            .mainadmincardreport img {
                margin-top: 11px;
            }

            .admitcardreportbackend {
                border: 1px solid #ddd;
                overflow: hidden;
                padding: 30px 50px;
                margin-bottom: 10px;
                height: 443px;
                color: #000;
                <?php if ($backgroundID == 1) { ?>background: url("<?= base_url('uploads/default/admitcard-border.png') ?>") !important;
                background-size: 100% 100% !important;
                <?php } ?>
            }

            .admitcardreportbackend ol {
                padding-left: 10px;
            }

            .admitcardreportbackend ol li {
                line-height: 20px;
            }

            .admitcardreportbackend ol li span {
                font-weight: 600
            }
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <?php if (customCompute($students)) {
                            foreach ($students as $student) {
                                $filename   =  'basicinfo-' . $student->usertypeID . '-' . $student->srstudentID;

                        ?>
                                <div class="col-sm-12">

                                    <div class="col-sm-1">
                                        <img src="<?= imagelink($student->photo) ?>" alt="">
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><?= $resultTitle ?> Result Card - ( <?= $resultYear ?> )</h4>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="admitcardstudentinfo">
                                            <div class="studentinfo">
                                                <p><span>Name </span> : <?= $student->srname ?> </p>
                                                <p><span>Regiter NO </span> : <?= $student->srregisterNO ?> </p>
                                                <p><span>Degree </span> : <?= isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : '' ?> </p>
                                                <p><span>Semester </span> : <?= isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : '' ?> </p>
                                                <p><span>Roll </span> : <?= $student->srroll ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php
                                        if ($is_download) {
                                            $result_file     =   "uploads/result/$resultID/" . $student->srregisterNO . '.pdf';


                                            if (is_file($result_file)) {
                                        ?>
                                                <a href="<?php echo base_url('/') . $result_file; ?>" target="_blank" class="btn btn-success">Download</a>
                                            <?php
                                            } else {
                                            ?>
                                                <a href="javascript:;" class="btn btn-success">N/A</a>
                                            <?php

                                            }
                                        } else {
                                            ?>
                                            <div class="">No Permission</div>

                                        <?php } ?>

                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                </div>

                            <?php
                            }
                        } else { ?>
                            <div class="callout callout-danger">
                                <p><b class="text-info"><?= $this->lang->line('admitcardreport_data_not_found') ?></b></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?= base_url('admitcardreport/send_pdf_to_mail'); ?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?= $this->lang->line('admitcardreport_close') ?></span></button>
                    <h4 class="modal-title"><?= $this->lang->line('admitcardreport_send_pdf_to_mail') ?></h4>
                </div>
                <div class="modal-body">

                    <?php
                    if (form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?= $this->lang->line("admitcardreport_to") ?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?= set_value('to') ?>">
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                if (form_error('subject'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="subject" class="col-sm-2 control-label">
                    <?= $this->lang->line("admitcardreport_subject") ?> <span class="text-red">*</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="subject" name="subject" value="<?= set_value('subject') ?>">
                </div>
                <span class="col-sm-4 control-label" id="subject_error">
                </span>

            </div>

            <?php
            if (form_error('message'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="message" class="col-sm-2 control-label">
                <?= $this->lang->line("admitcardreport_message") ?>
            </label>
            <div class="col-sm-6">
                <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?= set_value('message') ?>"></textarea>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
        <input type="button" id="send_pdf" class="btn btn-success" value="<?= $this->lang->line("admitcardreport_send") ?>" />
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
            $("#to_error").html("<?= $this->lang->line('admitcardreport_mail_valid') ?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to': $('#to').val(),
            'subject': $('#subject').val(),
            'message': $('#message').val(),
            'resultID': '<?= $resultID ?>',
            'classesID': '<?= $classesID ?>',
            'sectionID': '<?= $sectionID ?>',
            'studentID': '<?= $studentID ?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if (to == "" || to == null) {
            error++;
            $("#to_error").html("<?= $this->lang->line('admitcardreport_mail_to') ?>").css("text-align", "left").css("color", 'red');
        } else {
            if (check_email(to) == false) {
                error++
            }
        }

        if (subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?= $this->lang->line('admitcardreport_mail_subject') ?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if (error == 0) {
            $('#send_pdf').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('admitcardreport/send_pdf_to_mail') ?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if (response.to) {
                            $("#to_error").html("<?= $this->lang->line('admitcardreport_mail_to') ?>").css("text-align", "left").css("color", 'red');
                        }
                        if (response.subject) {
                            $("#subject_error").html("<?= $this->lang->line('admitcardreport_mail_subject') ?>").css("text-align", "left").css("color", 'red');
                        }
                        if (response.message) {
                            toastr["error"](response.message)
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>