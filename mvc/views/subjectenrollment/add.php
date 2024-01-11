
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("subjectenrollment/index")?>"><?=$this->lang->line('menu_subjectenrollment')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_subjectenrollment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('title') ? 'has-error' : '' ?>" >
                        <label for="title" class="col-sm-2 control-label">
                            Subjects <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                <tr>
                                    <th>
                                        SR# 
                                    </th>
                                    <th>
                                        Course Code 
                                    </th>
                                    <th>
                                        Course Title 
                                    </th>
                                    <th>
                                        Credit Hours
                                    </th>
                                    <th>
                                         Total Marks 
                                    </th>
                                    <th>
                                         Enroll Type
                                    </th>
                                    <th>
                                         Select
                                    </th>
                                </tr>
                                <?php if(count($allsubjects)){
                                    $sr     =   1;
                                    foreach ($allsubjects as $sub) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $sr;
                                                  $sr++;
                                            ?> 
                                        </td>
                                        <td>
                                            <?php echo $sub->subject_code?>
                                        </td>
                                        <td>
                                           <?php echo $sub->subject?> Course Title 
                                        </td>
                                        <td>
                                            <?php echo $sub->credit_hour?>
                                        </td>
                                        <td>
                                             <?php echo $sub->finalmark?> 
                                        </td>
                                        <td>
                                             Regular
                                        </td>
                                        <td>
                                             <input type="radio" name="subjectID<?php echo $sub->subjectID?>" required value="<?php echo $sub->subjectID?>">
                                        </td>
                                    </tr> 
                                    <?php    }
                                        }
                                ?>
                            </table>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-10 col-sm-2">
                            <input type="submit" class="btn btn-success pull-right" value="Submit" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
