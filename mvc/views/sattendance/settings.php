<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-sattendance"></i> <?= $this->lang->line('panel_title') ?></h3>


        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("sattendance/index") ?>"><?= $this->lang->line('menu_sattendance') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> <?= $this->lang->line('menu_sattendance') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <fieldset class="setting-fieldset">
                        <legend class="setting-legend">Attendance Setting</legend>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="permission">Old Attendance Permission</label>
                                        <select name="old_attendance_permission" id="" class="form form-control select2">
                                            <option value="">Select permission</option>
                                            <option value="yes" <?= isset($siteinfos->old_attendance_permission) && $siteinfos->old_attendance_permission == 'yes' ? 'selected' : '' ?> >Yes</option>
                                            <option value="no" <?= isset($siteinfos->old_attendance_permission) && $siteinfos->old_attendance_permission == 'no' ? 'selected' : '' ?>>No</option>
                                        </select>


                                        <span class="control-label">
                                            <?= form_error('attendance_permission'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label for="permission">Admin Email</label>
                                        <input type="email" value="<?= isset($siteinfos->admin_email) ? $siteinfos->admin_email : '' ?>" class="form form-control" name="admin_email">
                                        <span class="control-label">
                                            <?= form_error('admin_email'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <input type="submit" value="Submit" class="btn btn-primary">
                            </div>
                        </div>


                    </fieldset>
                </form>

            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script>
    $(".select2").select2();
</script>