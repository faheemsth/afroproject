<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-library"></i>Exam Slip</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Enroll</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <br/>
            </div>
            <div class="col-sm-12">
                <a href="<?php echo 'https://enrollment.slmsafroasian.com/exam-slip.php?email_login='.$this->session->userdata('username'); ?>" class="btn btn-primary"><i class="fa fa-laptop"></i>Exam Slip</a>
                <?php
                    // echo '<pre>';
                    // print_r($this->session->userdata('username'));
                    // echo '</pre>';
                ?>

            </div>
        </div>
    </div>
</div>


