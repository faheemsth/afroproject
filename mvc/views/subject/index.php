<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?= $this->lang->line('panel_title') ?></h3>

        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_subject') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <?php if (permissionChecker('subject_add')) { ?>
                        <a href="<?php echo base_url('subject/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?= $this->lang->line('add_title') ?>
                        </a>
                    <?php } ?>

                    <?php if ($this->session->userdata('usertypeID') != 3) { ?>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                            <?php
                            $array = array("0" => $this->lang->line("subject_select_class"));
                            if (customCompute($classes)) {
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                            }
                            echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                    <?php } ?>
                </h5>

                <div id="hide-table"></div>
                <?php if (count($subjects)) { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true">All Subjects</a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#tab' . $section->classesID . $section->sectionID . '" aria-expanded="false">' . $this->lang->line("student_section") . " " . $section->section . " ( " . $section->category . " )" . '</a></li>';
                            } ?>
                        </ul>



                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th><?= $this->lang->line('slno') ?></th>
                                                <th><?= $this->lang->line('subject_name') ?></th>
                                                <th><?= $this->lang->line('subject_author') ?></th>
                                                <th><?= $this->lang->line('subject_code') ?></th>
                                                <th><?= $this->lang->line('subject_teacher') ?></th>
                                                <th><?= $this->lang->line('subject_passmark') ?></th>
                                                <th><?= $this->lang->line('subject_finalmark') ?></th>
                                                <th><?= $this->lang->line('subject_type') ?></th>
                                                <th>Status</th>
                                                <?php if (permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                                    <th><?= $this->lang->line('action') ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php if (customCompute($subjects)) {
                                                $i = 1;
                                                foreach ($subjects as $subject) { ?>
                                                    <tr>
                                                        <td data-title="<?= $this->lang->line('slno') ?>">
                                                            <?php echo $i; ?>
                                                        </td>
                                                        <td data-title="<?= $this->lang->line('subject_name') ?>">
                                                            <?php echo $subject->subject; ?>
                                                        </td>
                                                        <td data-title="<?= $this->lang->line('subject_author') ?>">
                                                            <?php echo $subject->subject_author; ?>
                                                        </td>
                                                        <td data-title="<?= $this->lang->line('subject_code') ?>">
                                                            <?php echo $subject->subject_code; ?>
                                                        </td>
                                                        <td data-title="<?= $this->lang->line('subject_teacher') ?>">
                                                            <?php
                                                            if (isset($subjectteachers[$subject->subjectID])) {
                                                                foreach ($subjectteachers[$subject->subjectID] as $teacherID) {
                                                                    if (isset($teachers[$teacherID])) {
                                                                        echo $teachers[$teacherID] . '<br>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </td>

                                                        <td data-title="<?= $this->lang->line('subject_passmark') ?>">
                                                            <?php echo $subject->passmark; ?>
                                                        </td>

                                                        <td data-title="<?= $this->lang->line('subject_finalmark') ?>">
                                                            <?php echo $subject->finalmark; ?>
                                                        </td>

                                                        <td data-title="<?= $this->lang->line('subject_type') ?>">
                                                            <?php if ($subject->type == 1) { ?>
                                                                <button type="button" class="btn btn-primary btn-xs"><?php echo $this->lang->line('subject_mandatory'); ?></button>
                                                            <?php } elseif ($subject->type == 0) { ?>
                                                                <button type="button" class="btn btn-warning btn-xs"><?php echo $this->lang->line('subject_optional'); ?></button>
                                                            <?php } ?>
                                                        </td>

                                                        <td>
                                                            <input type="checkbox" class="form form-check subject-status" <?= $subject->status == 1 ? 'checked' : '' ?> data-subject-id=<?= $subject->subjectID ?> name="checkbox-<?= $subject->subjectID ?>">
                                                        </td>

                                                        <?php if (permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                                            <td data-title="<?= $this->lang->line('action') ?>">
                                                                <?php echo btn_edit('subject/edit/' . $subject->subjectID . "/" . $set, $this->lang->line('edit')) ?>
                                                                <?php echo btn_delete('subject/delete/' . $subject->subjectID . "/" . $set, $this->lang->line('delete')) ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                            <?php $i++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                <div id="tab<?= $section->classesID . $section->sectionID ?>" class="tab-pane">
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr>
                                                    <th><?= $this->lang->line('slno') ?></th>
                                                    <th><?= $this->lang->line('subject_name') ?></th>
                                                    <th><?= $this->lang->line('subject_author') ?></th>
                                                    <th><?= $this->lang->line('subject_code') ?></th>
                                                    <th><?= $this->lang->line('subject_teacher') ?></th>
                                                    <th><?= $this->lang->line('subject_passmark') ?></th>
                                                    <th><?= $this->lang->line('subject_finalmark') ?></th>
                                                    <th><?= $this->lang->line('subject_type') ?></th>
                                                    <th>Status</th>
                                                    <?php if (permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                                        <th><?= $this->lang->line('action') ?></th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (customCompute($allsection[$section->sectionID])) {
                                                    $i = 1;
                                                    foreach ($allsection[$section->sectionID] as $subject) { ?>
                                                        <tr>
                                                            <td data-title="<?= $this->lang->line('slno') ?>">
                                                                <?php echo $i; ?>
                                                            </td>
                                                            <td data-title="<?= $this->lang->line('subject_name') ?>">
                                                                <?php echo $subject->subject; ?>
                                                            </td>
                                                            <td data-title="<?= $this->lang->line('subject_author') ?>">
                                                                <?php echo $subject->subject_author; ?>
                                                            </td>
                                                            <td data-title="<?= $this->lang->line('subject_code') ?>">
                                                                <?php echo $subject->subject_code; ?>
                                                            </td>
                                                            <td data-title="<?= $this->lang->line('subject_teacher') ?>">
                                                                <?php
                                                                if (isset($subjectteachers[$subject->subjectID])) {
                                                                    foreach ($subjectteachers[$subject->subjectID] as $teacherID) {
                                                                        if (isset($teachers[$teacherID])) {
                                                                            echo $teachers[$teacherID] . '<br>';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </td>

                                                            <td data-title="<?= $this->lang->line('subject_passmark') ?>">
                                                                <?php echo $subject->passmark; ?>
                                                            </td>

                                                            <td data-title="<?= $this->lang->line('subject_finalmark') ?>">
                                                                <?php echo $subject->finalmark; ?>
                                                            </td>

                                                            <td data-title="<?= $this->lang->line('subject_type') ?>">
                                                                <?php if ($subject->type == 1) { ?>
                                                                    <button type="button" class="btn btn-primary btn-xs"><?php echo $this->lang->line('subject_mandatory'); ?></button>
                                                                <?php } elseif ($subject->type == 0) { ?>
                                                                    <button type="button" class="btn btn-warning btn-xs"><?php echo $this->lang->line('subject_optional'); ?></button>
                                                                <?php } ?>
                                                            </td>

                                                            <td>
                                                            <input type="checkbox" class="form form-check <?= permissionChecker('subject_status') ? 'subject-status' : '' ?> " <?= $subject->status == 1 ? 'checked' : '' ?> data-subject-id=<?= $subject->subjectID ?> name="checkbox-<?= $subject->subjectID ?>">
                                                            </td>
                                                            <?php if (permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                                                <td data-title="<?= $this->lang->line('action') ?>">
                                                                    <?php echo btn_edit('subject/edit/' . $subject->subjectID . "/" . $set, $this->lang->line('edit')) ?>
                                                                    <?php echo btn_delete('subject/delete/' . $subject->subjectID . "/" . $set, $this->lang->line('delete')) ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                <?php $i++;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div> <!-- nav-tabs-custom -->

                <?php } ?>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if (classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('subject/subject_list') ?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });


    $(".subject-status").on("change", function() {
        var subjectID = $(this).data('subject-id');
        if ($(this).is(":checked")) {
            var subjectStatus = 1;
        } else {
            var subjectStatus = 0;
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('subject/subjectStatus') ?>",
            data: {
                subjectID,
                subjectStatus
            },
            success: function(data) {

                toastr["success"]('Subject status changed successfully.')
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

    })
</script>