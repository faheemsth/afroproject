        <table width="100%"  >
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                           <!--  <img style="width: 25%;height:auto;" src="<?php echo base_url('uploads/images/'.$siteinfos->photo)?>"> -->
                             <?php
                            if($siteinfos->photo) {
                                $array = array(
                                    "src" => base_url('uploads/images/'.$siteinfos->photo),
                                    'width' => '100px',
                                    'height' => '100px',
                                    'class' => 'img-circle',
                                    //'style' => 'width: 25%;height:auto;',
                                );
                                echo img($array);
                            } 
                        ?>
                        </td>
                     
                        <td  style="width: 60%;" align="center">
                            <h3><u><?php echo $siteinfos->sname;?></u></h3>
                            <p><u>COURSE REGISTRATION FORM</u></p>
                            <p>Enrollment to Fall, 2022-2023 </p>
                        </td>
                     
                        <td  style="width: 20%;">
                            <img style="width: 130px;height:130px;float: right; border: 1px solid #0B3C5C; object-fit: contain;" src="<?=imagelink($enroll_student->photo)?>">
                             
                        </td>
                    </tr>
                </tbody>
            </table>

            

            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            <b>Student Name:</b> <u>
                                <?php echo $enroll_student->name;?>
                            </u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Father Name:</b> <u><?php echo $parents->name;?></u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CNIC #:</b> <u><?php echo $enroll_student->cnic;?></u>&nbsp;&nbsp;&nbsp;<b>Registration #:</b> <u><?php echo $enroll_student->registerNO;?></u>&nbsp;&nbsp;&nbsp;<b>Faculty:</b> <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Programmes: </b> <u><?php   echo $student->srclasses;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Department: </b> <u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Date of Admission: </b> <u><?php echo date('d-m-Y',strtotime($enroll_student->create_date));?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Roll #:   </b> <u><?php echo $enroll_student->roll;?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Session: 
                            </b> <u><?php echo $enroll_student->session_year;?></u>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="1" cellspacing="0" cellpadding="5" style="margin-top: 25px;">
                <tbody>
                    <tr>
                        <td style="text-align: center; padding-bottom: 15px;" colspan="6">
                            <p><b>Course to be taken during the semester</b></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <p><b>SR#</b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Course Code</b></p>
                        </td>
                        <td>
                            <p><b>Course Title</b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Credit Hours </b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Total Marks </b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><b>Enroll Type </b></p>
                        </td>
                    </tr>

                    <?php 
                    if (count($enrolledsubjects)) {
                        $sr                 =   1;
                        $total_credit_hours =   0;
                        foreach ($enrolledsubjects as $es) {
                            $total_credit_hours     +=  $es->credit_hour;
                            ?>
                    <tr>
                        <td  style="text-align: center;">
                            <p><b><?php echo $sr; $sr++;?></b></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><?php echo $es->subject_code;?></p>
                        </td>
                        <td>
                            <p><?php echo $es->subject;?></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><?php echo $es->credit_hour;?>
                            <?php echo $es->credit_hour_text;?></p>
                        </td>
                        <td  style="text-align: center;">
                            <p><?php echo $es->finalmark;?></p>
                        </td>
                        <td  style="text-align: center;">
                            <p>Regular</p>
                        </td>
                    </tr>    
                    <?php    } // foreach
                    } //if
                    ?>
                    
                     
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <b>Total</b>
                        </td>
                        <td style="text-align: center;">
                            <b><?php echo $total_credit_hours;?></b>
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="margin-top: 25px;">
                <tbody>
                    <tr>
                        <td>
                            <b>NOTE:</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>1. 75% attendance is compulsory, otherwise students will not be allowed for final exams</p>
                            <p>2. It’s compulsory to pay dues as per due date notified on 28.09.2022.</p>
                            <p>3. It’s compulsory to pay minimum one Installment with enrollment.</p>
                            <p>4. It’s compulsory to pay 50% fee before mid-term exams.</p>
                            <p>5. If you will not pay your dues with in time, your GCUF Enrollment/Registration for Fall-2022 will not be sent.</p>
                            <p>6. Late Payment fine/short attendance fine will be charged as per rules.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            Under Taking
                        </td>
                    </tr>
                    <tr>
                        <td>
                            I_______________________ solemnly affirm that I have read all the information, provided is correct as per my
                            knowledge and I will follow the rules and regulation of the Institute.
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="margin-top: 30px;">
                <tbody>
                    <tr>
                        <td style="text-align: right;">
                            <p>________________________________</p><br>
                            <b>(Student Signature)</b>  
                        </td>
                    </tr>
                </tbody>
            </table>
            <p style="text-align: center; margin-top: 30px;">This is System Generated document. Signature is not required.</p>
                