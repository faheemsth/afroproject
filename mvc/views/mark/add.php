<?php if ($siteinfos->note == 1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("mark/index") ?>"><?= $this->lang->line('menu_mark') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> <?= $this->lang->line('menu_mark') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="classesID" class="control-label">
                                            <?= $this->lang->line('mark_classes') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        $array = array("0" => $this->lang->line("mark_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="examID" class="control-label">
                                            <?= $this->lang->line('mark_exam') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        $array = array("0" => $this->lang->line("mark_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control select2 examID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label class="control-label"><?= $this->lang->line('mark_section') ?> <span class="text-red">*</span></label>
                                        <?php
                                        $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                        if (customCompute($sections)) {
                                            foreach ($sections as $section) {
                                                $arraysection[$section->sectionID] = $section->section;
                                            }
                                        }
                                        echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="subjectID" class="control-label">
                                            <?= $this->lang->line('mark_subject') ?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                        $subjectArray = array("0" => $this->lang->line("mark_select_subject"));
                                        if (customCompute($subjects)) {
                                            foreach ($subjects as $subject) {
                                                $subjectArray[$subject->subjectID] = $subject->subject;
                                            }
                                        }
                                        echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12 add-marks-btn" style="margin-top: 20px;"><?= $this->lang->line('add_mark') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>


                <?php if (customCompute($sendExam) && customCompute($sendClasses) && customCompute($sendSection) && customCompute($sendSubject)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                        echo '<h5><center>' . $this->lang->line('mark_details') . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_exam') . ' : ' . $sendExam->exam . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_classes') . ' : ' . $sendClasses->classes . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_section') . ' : ' . $sendSection->section . '</center></h5>';
                        echo '<h5><center>' . $this->lang->line('mark_subject') . ' : ' . $sendSubject->subject . '</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>

            <div class="row" style="margin-left: 5px;">
                <div class="col-md-10">
                    <div class="row"><?php $check_total_marks = 'disabled';
                                         if (isset($markpercentages) && !empty($markpercentages)) {

                                            foreach ($markpercentages as $mark_dis) {
                                            if(empty($total_marks[$mark_dis->markpercentageID]))
                                            $check_total_marks = 'disabled';
                                            else 
                                            $check_total_marks = '';  

                                            $t_marks = isset($total_marks[$mark_dis->markpercentageID]->total_marks) ? $total_marks[$mark_dis->markpercentageID]->total_marks : 0;
                                        ?>
                                <div class="col-md-2">
                                    <label for=""><?= $mark_dis->markpercentagetype ?> (total marks)</label>
                                    <input type="text" min='0' max='100' class="form-control total_marks" value="<?= $t_marks ?>" data-id="<?= $mark_dis->markpercentageID ?>">
                                </div>

                            <?php
                                            } ?>

                            <div class="col-md-2 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success col-md-12 col-xs-12 submit-total-marks" style="margin-top: 20px;">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                                        } ?>
                    </div>
                </div>
            </div>



            <div class="col-sm-12">
                <?php if (customCompute($students)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('slno') ?></th>
                                    <th><?= $this->lang->line('mark_photo') ?></th>
                                    <th><?= $this->lang->line('mark_name') ?></th>
                                    <th>Registration No.</th>
                                    <th><?= $this->lang->line('mark_roll') ?></th>
                                    <?php
                                    foreach ($markpercentages as $data) {
                                        echo "<th>$data->markpercentagetype (Obtained marks)</th>";
                                    }
                                    ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (customCompute($students)) {
                                    $i = 1;
                                    foreach ($students as $student) {
                                        foreach ($marks as $mark) {
                                            if ($student->studentID == $mark->studentID) {   ?>
                                                <tr>
                                                    <td data-title="<?= $this->lang->line('slno') ?>">
                                                        <?php echo $i; ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('mark_photo') ?>">
                                                        <?= profileproimage($student->photo) ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('mark_name') ?>">
                                                        <?php echo $student->name; ?>
                                                    </td>
                                                    <td data-title="Registration No.">
                                                        <?php echo $student->registerNO; ?>
                                                    </td>
                                                    <td data-title="<?= $this->lang->line('mark_roll') ?>">
                                                        <?php echo $student->roll; ?>
                                                    </td>
                                                    <?php
                                                    foreach ($markpercentages as $data) {
                                                        $t_marks = isset($total_marks[$data->markpercentageID]->total_marks) ? $total_marks[$data->markpercentageID]->total_marks : 0;

                                                        echo "<td data-title='$data->markpercentagetype'>";
                                                        echo "<input class='form-control student_mark mark' ". $check_total_marks ." type='number' name='mark-" . $markwr[$student->studentID][$data->markpercentageID] . "' id='" . $data->markpercentageID . "' value='" . $markRelations[$student->studentID][$data->markpercentageID] . "' min='0' max='" . $t_marks . "' />";
                                                        echo "</td>";
                                                    }
                                                    ?>

                                                </tr>
                                <?php $i++;
                                            }
                                        }
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success col-sm-2 col-xs-12" id="add_mark" name="add_mark" value="<?= $this->lang->line("add_sub_mark") ?>" />

                    <script type="text/javascript">
                        window.addEventListener('load', function() {
                            setTimeout(lazyLoad, 1000);
                        });

                        function lazyLoad() {
                            var card_images = document.querySelectorAll('.card-image');
                            card_images.forEach(function(card_image) {
                                var image_url = card_image.getAttribute('data-image-full');
                                var content_image = card_image.querySelector('img');
                                content_image.src = image_url;
                                content_image.addEventListener('load', function() {
                                    card_image.style.backgroundImage = 'url(' + image_url + ')';
                                    card_image.className = card_image.className + ' is-loaded';
                                });
                            });
                        }

                        $(document).on("keyup", ".mark", function() {
                            if (parseInt($(this).val())) {
                                var val = parseInt($(this).val());
                                var minMark = parseInt($(this).attr('min'));
                                var maxMark = parseInt($(this).attr('max'));
                                if (minMark > val || val > maxMark) {
                                    $(this).val('');
                                }
                            } else {
                                if ($(this).val() == '0') {} else {
                                    $(this).val('');
                                }
                            }
                        });

                        $("#add_mark").click(function() {
                            var inputs = "";
                            var inputs_value = "";
                            var mark = $('input[name^=mark]').map(function() {
                                return {
                                    markpercentageid: this.id,
                                    mark: this.name,
                                    value: this.value
                                };
                            }).get();

                            $.ajax({
                                type: 'POST',
                                url: "<?= base_url('mark/mark_send') ?>",
                                data: {
                                    "examID": "<?= $set_exam ?>",
                                    "classesID": "<?= $set_classes ?>",
                                    "subjectID": "<?= $set_subject ?>",
                                    "inputs": mark
                                },
                                dataType: "html",
                                success: function(data) {
                                    var response = jQuery.parseJSON(data);
                                    if (response.status) {
                                        toastr["success"](response.message)
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
                                        if (response.inputs) {
                                            toastr["error"](response.inputs)
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
                                }
                            });
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if (parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/examcall') ?>",
                data: {
                    "classesID": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#examID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/sectioncall') ?>",
                data: {
                    "id": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });
        }
    });

    $("#sectionID").change(function(){
        var classesID = $("#classesID").val();
        var sectionID = $(this).val();
        
        $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/subjectcall') ?>",
                data: {
                    "id": classesID,
                    "sectionID" : sectionID
                },
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                }
            });
    })

    $('.submit-total-marks').click(function() {

        var values = [];
        var classID = $("#classesID").val();
        var examID = $("#examID").val(); 
        var sectionID = $("#sectionID").val();
        var subjectID = $("#subjectID").val();


        $('.total_marks').each(function() {
            var dataId = $(this).attr('data-id');
            var value = $(this).val();

            var entry = {};
            values[dataId] = value;
        });

        $.ajax({
            type: 'POST',
            url: "<?= base_url('mark/addTotalMarks') ?>",
            data: {
                "totalmarks": values,
                classID,
                examID,
                sectionID,
                subjectID
            },
            success: function(data) {
                $('.mark').removeAttr('disabled');
                $(".add-marks-btn").trigger('click');
            }
        });



    });
</script>