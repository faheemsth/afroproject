<div class="row">
    <?php if(config_item('demo')) { ?>
        <div class="col-sm-12" id="resetDummyData">
            <div class="callout callout-danger">
                <h4>Reminder!</h4>
                <p>Dummy data will be reset in every <code>30</code> minutes</p>
            </div>
        </div>

        <script type="text/javascript"> 
            $(document).ready(function() {
                var count = 7;
                var countdown = setInterval(function(){
                    $("p.countdown").html(count + " seconds remaining!");
                    if (count == 0) {
                        clearInterval(countdown);
                        $('#resetDummyData').hide();
                    }
                    count--;
                }, 1000);
            });
        </script>
    <?php } ?>

    <?php if((config_item('demo') === FALSE) && ($siteinfos->auto_update_notification == 1) && ($versionChecking != 'none')) { ?>
        <?php if($this->session->userdata('updatestatus') === null) { ?>
            <div class="col-sm-12" id="updatenotify">
                <div class="callout callout-success">
                    <h4>Dear Admin</h4>
                    <p>INIlabs school management system has released a new update.</p>
                    <p>Do you want to update it now <?=config_item('ini_version')?> to <?=$versionChecking?> ?</p>
                    <a href="<?=base_url('dashboard/remind')?>" class="btn btn-danger">Remind me</a>
                    <a href="<?=base_url('dashboard/update')?>" class="btn btn-success">Update</a>
                </div>
            </div>
        <?php } ?> 
    <?php } ?>

    <?php
        $arrayColor = array(
            'bg-orange-dark',
            'bg-teal-light',
            'bg-pink-light',
            'bg-purple-light'
        );

        function allModuleArray($usertypeID='1', $dashboardWidget) {
          $userAllModuleArray = array(
            $usertypeID => array(
                'student'   => $dashboardWidget['students'],
                'classes'   => $dashboardWidget['classes'],
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects'],
                'book'      => $dashboardWidget['books'],
                'feetypes'  => $dashboardWidget['feetypes'],
                'lmember'   => $dashboardWidget['lmembers'],
                'event'     => $dashboardWidget['events'],
                'issue'     => $dashboardWidget['issues'],
                'holiday'   => $dashboardWidget['holidays'],
                'invoice'   => $dashboardWidget['invoices'],
            )
          );
          return $userAllModuleArray;
        }

        $userArray = array(
            '1' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects']
            ),
            '2' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'classes'   => $dashboardWidget['classes'],
                'subject'   => $dashboardWidget['subjects'],
            ),
            '3' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'subject'   => $dashboardWidget['subjects'],
                'issue'     => $dashboardWidget['issues'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '4' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'book'     => $dashboardWidget['books'],
                'event'     => $dashboardWidget['events'],
                'holiday'   => $dashboardWidget['holidays'],
            ),
            '5' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'feetypes'  => $dashboardWidget['feetypes'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '6' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'lmember'   => $dashboardWidget['lmembers'],
                'book'      => $dashboardWidget['books'],
                'issue'     => $dashboardWidget['issues'],
            ),
            '7' => array(
                'teacher'       => $dashboardWidget['teachers'],
                'event'         => $dashboardWidget['events'],
                'holiday'       => $dashboardWidget['holidays'],
                'visitorinfo'  => $dashboardWidget['visitors'],
            ),
        );

        $generateBoxArray = array();
        $counter = 0;
        $getActiveUserID = $this->session->userdata('usertypeID');
        $getAllSessionDatas = $this->session->userdata('master_permission_set');
        foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
            if($getAllSessionData == 'yes') {
                if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                    if($counter == 4) {
                      break;
                    }

                    $generateBoxArray[$getAllSessionDataKey] = array(
                        'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                        'color' => $arrayColor[$counter],
                        'link' => $getAllSessionDataKey,
                        'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                        'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey],
                    );
                    $counter++;
                }

            }
        }

        $icon = '';
        $menu = '';
        if($counter < 4) {
            $userArray = allModuleArray($getActiveUserID, $dashboardWidget);
            foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
                if($getAllSessionData == 'yes') {
                    if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                        if($counter == 4) {
                            break;
                        }

                        if(!isset($generateBoxArray[$getAllSessionDataKey])) {
                            $generateBoxArray[$getAllSessionDataKey] = array(
                                'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                                'color' => $arrayColor[$counter],
                                'link' => $getAllSessionDataKey,
                                'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                                'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey]
                            );
                            $counter++;
                        }
                    }
                }
            }
        }

        if(customCompute($generateBoxArray)) { foreach ($generateBoxArray as $generateBoxArrayKey => $generateBoxValue) { ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box ">
                    <a class="small-box-footer <?=$generateBoxValue['color']?>" href="<?=base_url($generateBoxValue['link'])?>">
                        <div class="icon <?=$generateBoxValue['color']?>" style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa <?=$generateBoxValue['icon']?>"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                <?=$generateBoxValue['count']?>
                            </h3 class="text-white">
                            <p class="text-white">
                                <?=$this->lang->line('menu_'.$generateBoxValue['menu'])?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
    <?php } } ?>
    <?php 
    if(permissionChecker('book')) {   ?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-teal-light" href="javascript:;">
                        <div class="icon bg-teal-light" style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa icon-subject"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">Books </h3>
                            <p class="text-white">Titles: <?php echo $dashboardWidget['total_titles'];?></p>
                            <p class="text-white">Total: <?php echo $dashboardWidget['books'];?></p>
                            <p class="text-white">Issued: <?php echo $dashboardWidget['issues'];?></p>
                        </div>
                    </a>
                </div>
            </div>

            <?php   
                } ?>
    <?php 
    if(permissionChecker('transactionreport')) {   ?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-pink-light" href="javascript:;">
                        <div class="icon bg-pink-light" style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa fa-money"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">Fee Collection </h3>
                            <p class="text-white">Today: <?php echo number_format($dashboardWidget['today_tra_amount']);?></p>
                            <p class="text-white">30 Days: <?php echo number_format($dashboardWidget['today_tra_amount_30']);?></p>
                            <p class="text-white">Year: <?php echo number_format($dashboardWidget['today_tra_amount_365']);?></p>
                        </div>
                    </a>
                </div>
            </div>

    <?php    
        } ?>
    <?php 
    if(permissionChecker('expense')) {  ?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-purple-light" href="javascript:;">
                        <div class="icon bg-purple-light" style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa fa-usd"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">Expense </h3>
                            <p class="text-white">Today: <?php echo $dashboardWidget['total_balance'];?></p>
                            <p class="text-white">30 Days: <?php echo $dashboardWidget['total_balance_30'];?></p>
                            <p class="text-white">Year: <?php echo $dashboardWidget['total_balance_365'];?></p>
                        </div>
                    </a>
                </div>
            </div>

    <?php   
        } ?>
            <?php 
    if(permissionChecker('hostel')) {     
    foreach ($hostels as $h) {?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-teal-light" href="javascript:;">
                        <div class="icon bg-teal-light " style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa fa-building-o"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white"><?php echo $h->name;?> </h3>
                            <?php foreach ($hostelrooms as $hr) {
                                if ($hr->hostelID==$h->hostelID) {
                                    
                                ?>
                            <p class="text-white">Room. <?php echo $hr->hostelroom;?> Cap. <?php echo $hr->roomcapcity;?> O. Cap. <?php echo $hr->capcityoccopied;?> </p>
                            <?php }
                                 }?>
                        </div>
                    </a>
                </div>
            </div>

            <?php   }
                } ?> 
    <?php 
    if(permissionChecker('hostel')) {     
    foreach ($hostels as $h) {?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-pink-light" href="javascript:;">
                        <div class="icon bg-teal-light " style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa icon-account"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">Hostel Dues </h3>
                            <?php $sum=0; 
                            foreach ($hostel_invoice as $hinvoice) {
                                
                                $sum += $hinvoice->net_fee;
                            ?>
                            
                            <?php 
                                 }?>
                                 <p class="text-white">Hostel Pending Fee :<?php echo ($sum)?> </p>
                        </div>
                    </a>
                </div>
            </div>

            <?php   }
                } ?>
    <?php 
    if(permissionChecker('admission')) {  ?>
    <div class="col-lg-4 col-xs-12">
                <div class="small-box ">
                    <a class="small-box-footer bg-purple-light" href="javascript:;">
                        <div class="icon bg-purple-light " style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">Admission </h3>
                            <p class="text-white">Pending: <?php echo $dashboardWidget['ad_pending'];?></p>
                            <p class="text-white">In Process: <?php echo $dashboardWidget['ad_in_process'];?></p>
                            <p class="text-white">Confirmed: <?php echo $dashboardWidget['ad_in_Confirmed'];?></p>
                            <p class="text-white">Rejected: <?php echo $dashboardWidget['ad_in_Rejected'];?></p>
                        </div>
                    </a>
                </div>
            </div>

            

    <?php   
        } ?>


    <?php 
    if(permissionChecker('admission')) { 
        if (count($allclasses)) {
            
     ?>
   

            
                
  <!--<div class="col-md-12 col-sm-12">-->
  <!--  <h3>Degree Wise Admission Details</h3>-->
  <!--  <div class="panel_ac-group wrap" id="accordion" role="tablist" aria-multiselectable="true">-->
  <!--      <?php foreach($allclasses as $c){?>-->
  <!--    <div class="panel_ac">-->
  <!--      <div class="panel_ac-heading" role="tab" id="headingOne<?php echo $c->classesID?>">-->
  <!--        <h4 class="panel_ac-title">-->
  <!--      <a  class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapseOne<?php echo $c->classesID?>" aria-expanded="false"    aria-controls="collapseOne<?php echo $c->classesID?>">-->
  <!--       <?php echo $c->classes?>-->
  <!--      </a>-->
  <!--    </h4>-->
  <!--      </div>-->
  <!--      <div id="collapseOne<?php echo $c->classesID?>" class="panel_ac-collapse collapse" role="tabpanel" aria-labelledby="headingOne<?php echo $c->classesID?>">-->
  <!--        <div class="panel-body">-->
           
  <!--         <div class="col-lg-3 col-xs-6">-->
  <!--              <div class="small-box ">-->
  <!--                  <a class="small-box-footer bg-orange-dark" href="javascript:;">-->
  <!--                      <div class="icon bg-orange-dark" style="padding: 9.5px 18px 6px 18px;">-->
  <!--                          <i class="fa icon-student"></i>-->
  <!--                      </div>-->
  <!--                      <div class="inner ">-->
  <!--                          <h3 class="text-white"> <?php echo $dashboardWidget[$c->classesID]['ad_pending']  ;?> </h3>-->
  <!--                          <p class="text-white">Pending </p>-->
  <!--                      </div>-->
  <!--                  </a>-->
  <!--              </div>-->
  <!--          </div>-->
           
  <!--         <div class="col-lg-3 col-xs-6">-->
  <!--              <div class="small-box ">-->
  <!--                  <a class="small-box-footer bg-pink-light" href="javascript:;">-->
  <!--                      <div class="icon bg-pink-light" style="padding: 9.5px 18px 6px 18px;">-->
  <!--                          <i class="fa icon-student"></i>-->
  <!--                      </div>-->
  <!--                      <div class="inner ">-->
  <!--                          <h3 class="text-white"> <?php echo $dashboardWidget[$c->classesID]['ad_in_process'] ;?> </h3>-->
  <!--                          <p class="text-white">In Process </p>-->
  <!--                      </div>-->
  <!--                  </a>-->
  <!--              </div>-->
  <!--          </div>-->
           
  <!--         <div class="col-lg-3 col-xs-6">-->
  <!--              <div class="small-box ">-->
  <!--                  <a class="small-box-footer bg-teal-light" href="javascript:;">-->
  <!--                      <div class="icon bg-teal-light" style="padding: 9.5px 18px 6px 18px;">-->
  <!--                          <i class="fa icon-student"></i>-->
  <!--                      </div>-->
  <!--                      <div class="inner ">-->
  <!--                          <h3 class="text-white"> <?php echo $dashboardWidget[$c->classesID]['ad_in_Confirmed'];?> </h3>-->
  <!--                          <p class="text-white">Comfirmed </p>-->
  <!--                      </div>-->
  <!--                  </a>-->
  <!--              </div>-->
  <!--          </div>-->

  <!--         <div class="col-lg-3 col-xs-12">-->
  <!--              <div class="small-box ">-->
  <!--                  <a class="small-box-footer bg-purple-light" href="javascript:;">-->
  <!--                      <div class="icon bg-purple-light" style="padding: 9.5px 18px 6px 18px;">-->
  <!--                          <i class="fa icon-student"></i>-->
  <!--                      </div>-->
  <!--                      <div class="inner ">-->
  <!--                          <h3 class="text-white"> <?php echo $dashboardWidget[$c->classesID]['ad_in_Rejected'];?> </h3>-->
  <!--                          <p class="text-white">Rejected </p>-->
  <!--                      </div>-->
  <!--                  </a>-->
  <!--              </div>-->
  <!--          </div>-->

 


  <!--        </div>-->
  <!--      </div>-->
  <!--    </div>-->
      <!-- end of panel -->
  <!--<?php }?>-->
       

  <!--  </div>-->
    <!-- end of #accordion -->

  <!--</div>-->
             <br>
<!-- end of container -->

    <?php   
        } 
        } 
        ?>

        <?php if($getActiveUserID == 1 || $getActiveUserID == 5 || $getActiveUserID == 18) { ?>
     
        
        <div class="col-sm-12">
            <br>
            <div class="box"> 
                <div class="box-body" style="padding: 0px;">

                    <div id="attendanceGraph"></div>
                </div>
            </div>
        </div>
     
        <?php }?>



    

</div>

<br>
 

<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <div class="col-sm-8">
           <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-5">
            <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 15, 'maxlength' => 45)); ?>
        </div>
        <?php } ?>
        <div class="col-sm-7">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="visitor"></div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
        
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;height: 320px">
                    <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 20, 'maxlength' => 70)); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } ?>

<div class="row">
  <div class="col-sm-12">
      <div class="box">
          <div class="box-body">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
          </div>
      </div>
  </div>
</div><!-- /.row -->

<?php
    if($attendanceSystem != 'subject') {
        $this->load->view("dashboard/AttendanceHighChartJavascript");
    } else {
        $this->load->view("dashboard/SubjectWiseAttendanceHighChartJavascript");
    }
?> 
<?php $this->load->view("dashboard/CalenderJavascript"); ?>
<?php $this->load->view("dashboard/VisitorHighChartJavascript"); ?>
<style type="text/css">
 .panel_ac {
  border-width: 0 0 1px 0;
  border-style: solid;
  border-color: #fff;
  background: none;
  box-shadow: none;
}

.panel_ac:last-child {
  border-bottom: none;
}

.panel_ac-group > .panel_ac:first-child .panel_ac-heading {
  border-radius: 4px 4px 0 0;
}

.panel_ac-group .panel_ac {
  border-radius: 0;
}

.panel_ac-group .panel_ac + .panel_ac {
  margin-top: 0;
}

.panel_ac-heading {
  background-color: #009688;
  border-radius: 0;
  border: none;
  color: #fff;
  padding: 0;
}

.panel_ac-title a {
  display: block;
  color: #fff;
  padding: 15px;
  position: relative;
  font-size: 16px;
  font-weight: 400;
}

.panel_ac-body {
  background: #fff;
}

.panel_ac:last-child .panel_ac-body {
  border-radius: 0 0 4px 4px;
}

.panel_ac:last-child .panel_ac-heading {
  border-radius: 0 0 4px 4px;
  transition: border-radius 0.3s linear 0.2s;
}

.panel_ac:last-child .panel_ac-heading.active {
  border-radius: 0;
  transition: border-radius linear 0s;
}
/* #bs-collapse icon scale option */

.panel_ac-heading a:before {
  content: '\e146';
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  font-size: 24px;
  transition: all 0.5s;
  transform: scale(1);
}

.panel_ac-heading.active a:before {
  content: ' ';
  transition: all 0.5s;
  transform: scale(0);
}

#bs-collapse .panel_ac-heading a:after {
  content: ' ';
  font-size: 24px;
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  transform: scale(0);
  transition: all 0.5s;
}

#bs-collapse .panel_ac-heading.active a:after {
  content: '\e909';
  transform: scale(1);
  transition: all 0.5s;
}
/* #accordion rotate icon option */

#accordion .panel_ac-heading a:before {
  content: '\f107';
  font-size: 24px;
  position: absolute;
  font-family: 'FontAwesome';
  right: 5px;
  top: 10px;
  transform: rotate(180deg);
  transition: all 0.5s;
}

#accordion .panel_ac-heading.active a:before {
  transform: rotate(0deg);
  transition: all 0.5s;
}
h4.panel_ac-title {
    margin: 0px;
}
.small-box h3 {
    font-size: 240%;}
    .small-box .icon {
    background: transparent !important;
}
@media screen and (max-width: 480px) {
    .small-box h3 {
    font-size: 150%;}
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
  $('.collapse.in').prev('.panel_ac-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('show.bs.collapse', function(a) {
      $(a.target).prev('.panel_ac-heading').addClass('active');
    })
    .on('hide.bs.collapse', function(a) {
      $(a.target).prev('.panel_ac-heading').removeClass('active');
    });
});
</script>