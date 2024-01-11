 
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
             
            <?=isset($usertypes[$usertypeID]) ? $usertypes[$usertypeID]: ' ';?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style>
            .idcardreport {
                font-family: arial;    
                max-width:794px;
                max-height: 1123px;
                margin-left: auto;
                margin-right: auto;
                -webkit-print-color-adjust: exact;
            }
            /*IDcard Front Part Css Code*/
            .idcardreport-frontend{
                margin: 3px;
                float: left;
                border: 1px solid #000;
                padding: 10px;
                width: 257px;
                text-align: center;
                height:290px;
                <?php if($background == 1) { ?>
                background:url("<?=base_url('uploads/default/idcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }
            
            .idcardreport-frontend h3{
                font-size: 20px;
                color: #1A2229;
            }
            
            .idcardreport-frontend img{
                width: 50px;
                height: 50px;
                border: 1px solid #ddd;
                margin-bottom: 5px;
            }

            .idcardreport-frontend p{
                text-align: left;
                font-size: 12px;
                margin-bottom: 0px;
                color: #1A2229;
            }

            /*ID Card Back Part Css Code*/
            .idcardreport-backend{
                margin: 3px;
                /*float: left;*/
                float: right;
                border: 1px solid #1A2229;
                padding: 10px;
                width: 257px;
                text-align: center;
                height:290px;
                <?php if($background == 1) { ?>
                background:url("<?=base_url('uploads/default/idcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }

            .idcardreport-backend h3{
                background-color: #1A2229;
                color: #fff;
                font-size: 13px;
                padding: 5px 0px;
                margin:5px;
                margin-top: 13px;
            }

            .idcardreport-backend h4{
                font-size: 11px;
                color: #1A2229;
                font-weight: bold;
                padding: 5px 0px;
            }

            .idcardreport-backend p{
                font-size: 17px;
                color: #1A2229;
                font-weight: 500;
                line-height: 17px;
            }

            .idcardreport-schooladdress {
                color: #1A2229 !important;
                font-weight: 500;
            }

            .idcardreport-bottom {
                text-align: center;
                padding-top: 5px
            }

            .idcardreport-qrcode{
                float: left;
                width: 50%;
            }

            .idcardreport-qrcode img{
                width: 80px;
                height: 80px;
            }

            .idcardreport-session{
                float: right;
                width: 50%;
            }
            
            .idcardreport-session span{
                color: #1A2229;
                font-weight: bold;
                margin-top: 35px;
                overflow: hidden;
                float: left;
            }

            @media print {
                .idcardreport {
                    max-width:794px;
                    max-height: 1123px;
                    margin-left: auto;
                    margin-right: auto;
                    -webkit-print-color-adjust: exact;
                    margin:0px auto;    
                }

                /*ID Card Front Part Css Code*/
                .idcardreport-frontend{
                    margin: 1px;
                    float: left;
                    border: 1px solid #000;
                    padding: 10px;
                    width: 250px;
                }

                h3{
                    color: #1A2229 !important;
                }

                .idcardreport-frontend .profile-view-dis .profile-view-tab {
                    width: 100%;
                    float: left;
                    margin-bottom: 0px;
                    padding: 0 15px;
                    font-size: 14px;
                    margin-top: 5px;
                }

                /*ID Card Back Part Css Code*/
                .idcardreport-backend {
                    margin: 1px;
                    float: right;
                    border: 1px solid #1A2229;
                    padding: 10px;
                    width: 250px;
                }

                .idcardreport-backend h3{
                    background-color: #1A2229 !important;
                    font-size: 12px;
                    color: #fff !important;
                    overflow: hidden;
                    display: block;
                }
            }

            .idcardreport-frontend .profile-view-dis .profile-view-tab {
                width: 100%;
                float: left;
                margin-bottom: 0px;
                padding: 0 15px;
                font-size: 14px;
                margin-top: 5px;
            }
        </style>

        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?php if (customCompute($idcards)) { ?>
                    <table class="idcardreport">
                        <tr>
                            <?php $j= 0; $i=0; $c = customCompute($idcards); foreach($idcards as $idcard) {
                            //TYPE 1 == Front Part
                            //TYPE 2 == Back Part
                            if($type == 1) { ?>
                                <td class="idcardreport-frontend">
                                    <h3><?=$siteinfos->sname?></h3> 
                                    <img src="<?=imagelink($idcard->photo)?>" alt="">
                                    <div class="profile-view-dis">
                                        <?php if($usertypeID == 1) { ?>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_name')?></b></span>: <?=$idcard->name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_dob')?></b> </span>: <?=date('d M Y',strtotime($idcard->dob))?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_jod')?></b> </span>: <?=date('d M Y',strtotime($idcard->jod))?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_phone')?></b> </span>: <?=$idcard->phone?> </p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_email')?></b> </span>: <?=$idcard->email?></p>
                                            </div>
                                        <?php } elseif($usertypeID == 2) { ?>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_name')?></b> </span>: <?=$idcard->name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_designation')?></b> </span>: <?=$idcard->designation?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_jod')?></b> </span>: <?=date('d M Y',strtotime($idcard->jod))?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_phone')?></b> </span>: <?=$idcard->phone?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_email')?></b> </span>: <?=$idcard->email?></p>
                                            </div>
                                        <?php } elseif($usertypeID == 3) { ?>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_name')?></b> </span>: <?=$idcard->srname?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_registerNO')?></b> </span>: <?=$idcard->srregisterNO?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_class')?></b> </span>: <?=isset($classes[$idcard->srclassesID]) ? $classes[$idcard->srclassesID] : ''?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_section')?></b> </span>: <?=isset($sections[$idcard->srsectionID]) ? $sections[$idcard->srsectionID] : ''?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_roll')?></b> </span>: <?=$idcard->srroll?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_blood_group')?></b> </span>: <?=$idcard->bloodgroup?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_name')?></b> </span>: <?=$idcard->name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_dob')?></b> </span>: <?=date('d M Y',strtotime($idcard->dob))?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_jod')?></b> </span>: <?=date('d M Y',strtotime($idcard->jod))?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_phone')?></b> </span>: <?=$idcard->phone?> </p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><b><?=$this->lang->line('idcardreport_email')?></b> </span>: <?=$idcard->email?></p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <?php 
                                $i++; 
                                if($i==3) {
                                    $j++;
                                    $k = $c/3;
                                    $k = ceil($k);
                                    if($k == $j) {
                                        echo "";
                                    } else {
                                        echo "</tr><tr>";
                                    }
                                    $i=0;
                                }
                            } else { $i++;?>
                                <?php 
                                    if($usertypeID == 1) {
                                        $filename = $idcard->usertypeID.'-'.$idcard->systemadminID;
                                        $text = $this->lang->line('idcardreport_type')." : ".'1'.',';
                                        $text.= $this->lang->line('idcardreport_id')." : ".$idcard->systemadminID;
                                    } elseif($usertypeID == 2) {
                                        $filename = $idcard->usertypeID.'-'.$idcard->teacherID;
                                        $text = $this->lang->line('idcardreport_type')." : ".'2'.',';
                                        $text.= $this->lang->line('idcardreport_id')." : ".$idcard->teacherID;
                                    } elseif($usertypeID == 3) {
                                        $filename = $idcard->usertypeID.'-'.$idcard->studentID;
                                        $text = $this->lang->line('idcardreport_type')." : ".'3'.',';
                                        $text.= $this->lang->line('idcardreport_id')." : ".$idcard->srstudentID;
                                    } elseif($usertypeID == 4) {
                                        $filename = $idcard->usertypeID.'-'.$idcard->parentsID;
                                        $text = "invalid";
                                    } else {
                                        $filename = $idcard->usertypeID.'-'.$idcard->userID;
                                        $text = $this->lang->line('idcardreport_type')." : ".$idcard->usertypeID.',';
                                        $text.= $this->lang->line('idcardreport_id')." : ".$idcard->userID;
                                    }

                                    $filepath = FCPATH.'uploads/idQRcode/'.$filename.'.png';
                                    if(!file_exists($filepath)) {
                                        generate_qrcode($text,$filename);
                                    }
                                ?>
                                <td class="idcardreport-backend">
                                    <h3><?=$this->lang->line('idcardreport_valid_up')?> <?=date('F Y',strtotime($schoolyear->endingdate))?></h3>
                                    <h4><?=$this->lang->line('idcardreport_please_return')?> : </h4>
                                    <p><?=$siteinfos->sname?></p>
                                    <div class="idcardreport-schooladdress">
                                        <?=$siteinfos->address?>
                                    </div>
                                    <div class="idcardreport-bottom">
                                        <div class="idcardreport-qrcode">
                                            <img src="<?=base_url('uploads/idQRcode/'.$filename.'.png')?>" alt="">
                                        </div>
                                        <div class="idcardreport-session">
                                            <span><?=$this->lang->line('idcardreport_session')?> : <?=$schoolyear->schoolyear?></span>
                                        </div>
                                    </div>
                                </td>
                            <?php } } ?>
                        </tr>
                    </table>
                    <?php } else { ?>   
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('idcardreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>

 