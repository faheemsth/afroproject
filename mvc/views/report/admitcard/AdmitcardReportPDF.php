<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <div class="mainadmincardreport">
        <?php if(customCompute($students)) { foreach($students as $student) { 

                        $filename   =  'basicinfo-'.$student->usertypeID.'-'.$student->srstudentID;
                        if ($student->active) {
                            $st_status  =   'Active';
                        }else{
                            $st_status  =   'In Active';
                        }

                    $text    = ' ID: '.$student->srstudentID."\n";
                    $text    .= ' Name: '.$student->srname."\n";
                    $text    .= ' Class: '.$classes[$student->srclassesID] ."\n";
                    $text    .= ' Semester: '. $sections[$student->srsectionID]."\n";
                    $text    .= ' Roll Number: '.$student->roll."\n";
                    $text    .= ' GCUF Reg. No.: ' .$student->registerNO."\n";
                    $text    .= 'Status: ' .$st_status."\n";
                    

                            $filepath = FCPATH.'uploads/idQRcode/'.$filename.'.png';
                if(!file_exists($filepath)) {
                    generate_qrcode($text,$filename);
                }?>
        <?php if($typeID == 1) { ?>
            <div class="admincardreport" style="height: 480px; <?=($backgroundID == 1) ? "background-image:url(".base_url('uploads/default/admitcard-border.png').") !important" : '' ?>">

                <table width="100%" style="text-align:center">
                    <tr>
                        <td style="width:8%">
                            <?php
                                if($siteinfos->photo) {
                                    $array = array(
                                        "src" => base_url('uploads/images/'.$siteinfos->photo),
                                        'width' => '50px',
                                        'height' => '50px',
                                        "style" => "margin-right:0px;margin-top:10px;"
                                    );
                                    echo img($array)."<br>";
                                }
                            ?>
                        </td>
                        <td style="width:84%"> 
                            <h2 class="title"><?=$siteinfos->sname?></h2>
                            <h5 class="address"><?=$siteinfos->address?></h5> 
                        </td>
                        <td style="width:8%">
                            <img src="<?=imagelink($student->photo)?>" alt="" style="height :50px; width:50px;margin-top:10px;">
                        </td>
                    </tr>
                </table>
                <div class="admitcardbody">
                    <h3><?=$examTitle?> <?=$this->lang->line('admitcardreport_exam_admit_card')?> - ( <?=$examYear?> )</h3>
                    <div class="admitcardstudentinfo">
                        <div class="studentinfo">
                            <p><span><?=$this->lang->line('admitcardreport_name')?></span> : <?=$student->srname?> </p>
                            <p><span><?=$this->lang->line('admitcardreport_registerNO')?></span> : <?=$student->srregisterNO?> </p>
                            <p><span><?=$this->lang->line('admitcardreport_class')?></span> : <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?> </p>
                            <p><span><?=$this->lang->line('admitcardreport_section')?></span> : <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?> </p>
                            <p><span><?=$this->lang->line('admitcardreport_roll')?></span> : <?=$student->srroll?></p>
                        </div>
                    </div>
                    <div class="subjectlist">
                        <h3><?=$this->lang->line('admitcardreport_subject_appear')?></h3>
                        <table>
                            <tr>
                                <td><?=$this->lang->line('admitcardreport_slno')?></td>
                                <td><?=$this->lang->line('admitcardreport_subject_code')?></td>
                                <td><?=$this->lang->line('admitcardreport_subject_name')?></td>
                                
                            </tr>
                            <?php $i= 0; if(customCompute($subjects)) { foreach($subjects as $subject) { if($subject->type == 1) { $i++; ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=$subject->subject_code?></td>
                                    <td><?=$subject->subject?></td>
                                     
                                </tr>
                            <?php } } }
                            if(isset($subjects[$student->sroptionalsubjectID])) { $opsubject = $subjects[$student->sroptionalsubjectID]; ?>
                                <tr>
                                    <td><?=$i+1?></td>
                                    <td><?=$opsubject->subject_code?></td>
                                    <td><?=$opsubject->subject?></td>
                                     
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="admitcardfooter">
                    <div class="account_signature"></div>
                    <div class="headmaster_signature"><img style="width:90px;height: 80px; padding-top: 12px" src="<?=base_url('uploads/idQRcode/'.$filename.'.png')?>" alt=""></div>
                </div>

                 <p align="center" style="
    position: absolute;
    bottom: 30px;
    width: 87%;
"><b> This is a system generated slip and does not need any signatures.</b></p>
            </div>
        <?php } elseif($typeID == 2) { ?>
            <div class="admitcardreportbackend" style="height: 480px; <?=($backgroundID == 1) ? "background-image:url(".base_url('uploads/default/admitcard-border.png').") !important" : '' ?>">
                <ol>
                    <li><span>Do not Carry these Electronic Gadgets : </span> electronic gadgets (Bluetooth devices, head phones, pen/buttonhole cameras, scanner, calculator, storage devices etc) in the examination lab. These items are strictly prohibited from the examination lab.</li>
                    <li><span>Do not Carry these Ornaments : </span> Candidates should also not wear charms, veil, items containing metals such as rings, bracelet, earrings, nose-pin, chains, necklace, pendants, badge, broach, hair pin, hair band.</li>
                    <li><span>What Candidates should Wear to the SSC Exam hall : </span> Candidates should not wear clothes with full sleeves or big buttons, etc. Candidates are advised to wear open footwear like slippers, sandals instead of shoes as the candidates could be asked to remove shoes by the frisking staff.</li>
                    <li><span>Do not Carry Stationary : </span> Pen/pencil and paper for rough work would be provided in the examination lab. Electronic watch (timer) will be available on the computer screen allotted to the candidates.</li>
                    <li><span>Do not carry Bags : </span> Do not carry back pack, College bag or any other bag like hand bag. If candidates bring any bag, they must make arrangement for safe custody of these items. The Commission shall not make any arrangement nor be responsible for the safe custody of such items.</li>
                    <li><span>What will Happen if you carry Prohibited items to SSC CGL Exam Hall : </span> If any such prohibited item is found in the possession of a candidate in the examination lab, his/her candidature is liable to be canceled and legal/criminal proceedings could be initiated against him/her. He/she would also liable to be debarred from appearing in future examinations of the Commission for a period of 3 years.</li>
                    <li><span>Candidate should not create Disturbance in the Exam Hall : </span> If any candidate is found obstructing the conduct of the examination or creating disturbances at the examination venue, his/her candidature shall be summarily canceled.</li>
                </ol>
            </div>
        <?php } } } else { ?>
            <div class="notfound">
                <?=$this->lang->line('admitcardreport_data_not_found')?>
            </div>
        <?php } ?>
    </div> 
</body>
</html>