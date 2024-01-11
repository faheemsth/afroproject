<?php
    error_reporting(E_ALL ^ E_NOTICE);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
 <meta charset="utf-8">
</head>
<body>
<div align="center">
    
        <table  width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="top">
                    <table class="c69"  >
                        <tr class="c2" >
                            <td class="c3" align="center">
                                <table class="c62">
                                    <tr class="c11">
                                        <td>
                                            <img style="width: 30px;height: auto;" src="mvc/views/invoice/logo.png">
                                        </td>
                                        <td valign="top" colspan="6" class="c10" >
                                            <p class="c12" style="text-align: left;" ><span class="c19" style="font-size:14px;text-align: left;"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12" style="text-align: left;"><span class="c18"  style="text-align: left;">19 KM - Ferozpur Road, Lahore.</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            
                                            <p class="c12"><span  class="c18">Ph: 042-35959530</span>, <span  class="c18">+92 334 4326090</span></p>

                                            <p class="c12"><span  class="c18">Email: fee@afroasian.edu.pk</span></p>

                                            <p class="c12"><span class="c18"><b>Bank Alfalah Ltd A/C No. 0455 1004829310</b></span></p>
                                            <p class="c12"><span class="c18"><b>Allied Bank Ltd A/C No. 0100 75757790017</b></span></p>

                                        </td>
                                    </tr>
                                   <br>

                                    <tr class="c11">
                                        <td valign="top" align="left" colspan="3" class="c10">
                                            <p class="c16">
                                                <span class="c17">

                                                    Date : 
                                                     <?=$admissioninfo->time?>
                                                    <?php
                                                        $time = date('Y-m-d', strtotime($admissioninfo->time));
                                                        $date = $admissioninfo->time;
                                                        $date = strtotime($date);
                                                        $date = strtotime("+7 day", $date);
                                                        $due_date = date('Y-m-d', $date);
                                                    ?>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" align="right"  colspan="4" class="c10">
                                            <p class="c16" style="background-color: #dfdfdf">
                                                <span class="c17">

                                                   Due Date :  <?=$due_date?>
                                                </span>
                                                <span class="c19"><b> </b></span><span class="c18"><b style="color:red;">
                                                
                                                </b></span><span class="c17"><b> </b></span>
                                            </p>
                                        </td>
                                    </tr>



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>

                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Applicant ID: </span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;"><b>
                                                    
                                                        <?=$admissioninfo->form_no?>
                                                 </b>       
                                                </span>
                                            </p>
                                        </td>
                                    </tr>


                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;">
                                                    
                                                       <?=$admissioninfo->f_name?>
                                                        
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student CNIC:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                        <?=$admissioninfo->cnic?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Father Name:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                       <?=$admissioninfo->father_name?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Degree:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                        <?=$admissioninfo->program1?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Session:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                       <?=$admissioninfo->name?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Remarks:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                       <?=$admissioninfo->remark?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                    </tr>

                                    <tr class="c26">

                                        <td valign="middle" colspan="4" class="c32"  style="text-align: left; border-top: 1px solid; border-bottom: 1px solid;">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c34" colspan="3" style="text-align: right;border-top: 1px solid; border-bottom: 1px solid">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="4" class="c40" style="text-align: left;">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Admission Fee
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><?=$admissioninfo->admission_fee?></span></p>
                                        </td>
                                    </tr>                               

                                    <tr class="c43">
                                        <td valign="middle" colspan="4" class="c44" style="text-align: left;">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Total Amount Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><b>
                                                 <?=$admissioninfo->admission_fee?>
                                            </b> </span></p>
                                        </td>
                                    </tr>

                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10" align="left">
                                            <p class="c16"><span class="c48"><b>Rupees in words:</b> Twenty Thousand Only</span></p>

                                        </td>
                                    </tr>
                                    <hr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left" colspan="1" style="text-align: left;">
                                                        <p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                    <td colspan="5">
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">1.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">After due date, late payment charges of Rs. 100/day will be applicable. </span></p>
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td valign="top" colspan="1"><span class="c38">2.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable & non-transferable to any person. </span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                         <td valign="top" colspan="1"><span class="c38">3.</span></td>
                                        <td align="justify" colspan="5">
                                        <p class="c50"><span class="c38">The fee voucher issued is valid only for admission in the given degree programme.</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">4.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">In case of online payment, please send the details of payment on email fee@afroasian.edu.pk or Whatsapp on +923344326090</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">5.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">For any query contact us on email at admissions@afroasian.edu.pk or Phone number: 042-35959530. </span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; text-decoration: underline;font-size: 8pt" colspan="3">Bank Stamp</td>
                                                    <td style="font-size: 8pt; text-align: right;text-decoration: underline;" colspan="3">
                                                        Fee Section
                                                    </td>
                                                </tr>
                                            </table>

                                            <br>
                                            <br>
                                            <br>

                                        </td>
                                    </tr>
                                    <br>
                                    <br>

                                    <tr class="c59">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c60"><span class="c61"><b>Bank Copy</b></span></p>
                                        </td>
                                    </tr>
                                </table>
                                <p class="c63"><br /></p>
                            </td>
                            <!-- 2nd voucher -->
                            <td class="c3" align="center">
                                <table class="c62">

                                    <tr class="c11">
                                        <td>
                                            <img style="width: 30px;height: auto;" src="mvc/views/invoice/logo.png">
                                        </td>
                                        <td valign="top" colspan="6" class="c10" >
                                            <p class="c12" style="text-align: left;" ><span class="c19" style="font-size:14px;text-align: left;"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12" style="text-align: left;"><span class="c18"  style="text-align: left;">19 KM - Ferozpur Road, Lahore.</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    
                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                         <p class="c12"><span  class="c18">Ph: 042-35959530</span>, <span  class="c18">+92 334 4326090</span></p>

                                        <p class="c12"><span  class="c18">Email: fee@afroasian.edu.pk</span></p>

                                        <p class="c12"><span class="c18"><b>Bank Alfalah Ltd A/C No. 0455 1004829310</b></span></p>
                                        <p class="c12"><span class="c18"><b>Allied Bank Ltd A/C No. 0100 75757790017</b></span></p>

                                        </td>
                                    </tr>

                                    <br>
                                   
                                    <tr class="c11">
                                        <td valign="top" align="left" colspan="3" class="c10">
                                            <p class="c16">
                                                <span class="c17">

                                                    Date : 
                                                   <?=$admissioninfo->time?>
                                                    <?php
                                                        $time = date('Y-m-d', strtotime($admissioninfo->time));
                                                        $date = $admissioninfo->time;
                                                        $date = strtotime($date);
                                                        $date = strtotime("+7 day", $date);
                                                        $due_date = date('Y-m-d', $date);
                                                    ?>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" align="right"  colspan="4" class="c10">
                                            <p class="c16" style="background-color: #dfdfdf">
                                                <span class="c17">

                                                   Due Date : <?=$due_date?>
                                                </span>
                                                <span class="c19"><b> </b></span><span class="c18"><b style="color:red;">
                                                
                                                </b></span><span class="c17"><b> </b></span>
                                            </p>
                                        </td>
                                    </tr>



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>

                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Applicant ID: </span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;"><b>
                                                    
                                                        <?=$admissioninfo->form_no?>
                                                 </b>       
                                                </span>
                                            </p>
                                        </td>
                                    </tr>

                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;">
                                                    
                                                        <?=$admissioninfo->f_name?>
                                                        
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student CNIC:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                        <?=$admissioninfo->cnic?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Father Name:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                        <?=$admissioninfo->father_name?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Degree:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                       <?=$admissioninfo->program1?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Session:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                      <?=$admissioninfo->name?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Remarks:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                      <?=$admissioninfo->remark?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                   
                                    <tr class="c26">

                                        <td valign="middle" colspan="4" class="c32" style="text-align: left; border-top: 1px solid; border-bottom: 1px solid;">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c34" colspan="3" style="text-align: right;border-top: 1px solid; border-bottom: 1px solid">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="4" class="c40"  style="text-align: left;">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Admission Fee
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><?=$admissioninfo->admission_fee?></span></p>
                                        </td>
                                    </tr>

                                    <tr class="c43">
                                        <td valign="middle" colspan="4" class="c44"  style="text-align: left;">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Total Amount Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><b>
                                                 <?=$admissioninfo->admission_fee?>
                                            </b> </span></p>
                                        </td>
                                    </tr>

                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10" align="left">
                                            <p class="c16"><span class="c48"><b>Rupees in words:</b> Twenty Thousand Only</span></p>

                                        </td>
                                    </tr>
                                    <hr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left" colspan="1" style="text-align: left;">
                                                        <p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                    <td colspan="5">
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td valign="top" colspan="1"><span class="c38">1.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">After due date, late payment charges of Rs. 100/day will be applicable. </span></p>
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td valign="top" colspan="1"><span class="c38">2.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable & non-transferable to any person. </span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                         <td valign="top" colspan="1"><span class="c38">3.</span></td>
                                        <td align="justify" colspan="5">
                                        <p class="c50"><span class="c38">The fee voucher issued is valid only for admission in the given degree programme.</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">4.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">In case of online payment, please send the details of payment on email fee@afroasian.edu.pk or Whatsapp on +923344326090</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">5.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">For any query contact us on email at admissions@afroasian.edu.pk or Phone number: 042-35959530. </span></p>
                                                    </td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; text-decoration: underline;font-size: 8pt" colspan="3">Bank Stamp</td>
                                                    <td style="font-size: 8pt; text-align: right;text-decoration: underline;" colspan="3">
                                                        Fee Section
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                    

                                    <table border="0" width="100%" cellspacing="1">    

                                    
                                    </table>
                                    <br>
                                    <br>
                                    <tr class="c59">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c60"><span class="c61"><b>Office Copy</b></span></p>
                                        </td>
                                    </tr>
                                </table>
                                <p class="c63"><br /></p>
                            </td>

                            <!-- 3rd voucher -->
                            <td class="c303" align="center">
                                <table class="c62">

                                     <tr class="c11">
                                        <td>
                                            <img style="width: 30px;height: auto;" src="mvc/views/invoice/logo.png">
                                        </td>
                                        <td valign="top" colspan="6" class="c10" >
                                            <p class="c12" style="text-align: left;" ><span class="c19" style="font-size:14px;text-align: left;"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12" style="text-align: left;"><span class="c18"  style="text-align: left;">19 KM - Ferozpur Road, Lahore.</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            
                                        </td>
                                    </tr>
                                    
                                    

                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                         <p class="c12"><span  class="c18">Ph: 042-35959530</span>, <span  class="c18">+92 334 4326090</span></p>

                                        <p class="c12"><span  class="c18">Email: fee@afroasian.edu.pk</span></p>
                                            
                                            <p class="c12"><span class="c18"><b>Bank Alfalah Ltd A/C No. 0455 1004829310</b></span></p>
                                            <p class="c12"><span class="c18"><b>Allied Bank Ltd A/C No. 0100 75757790017</b></span></p>
                                            
                                        </td>
                                    </tr>

                                    <br>
                                   
                                    <tr class="c11">
                                        <td valign="top" align="left" colspan="3" class="c10">
                                            <p class="c16">
                                                <span class="c17">

                                                    Date : 
                                                    
                                                    <?php
                                                        echo $time = date('Y-m-d', strtotime($admissioninfo->time));
                                                        $date = $admissioninfo->time;
                                                        $date = strtotime($date);
                                                        $date = strtotime("+7 day", $date);
                                                        $due_date = date('Y-m-d', $date);
                                                    ?>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" align="right"  colspan="4" class="c10">
                                            <p class="c16" style="background-color: #dfdfdf">
                                                <span class="c17">

                                                   Due Date : <?php echo $due_date; ?>
                                                </span>
                                                <span class="c19"><b> </b></span><span class="c18"><b style="color:red;">
                                                
                                                </b></span><span class="c17"><b> </b></span>
                                            </p>
                                        </td>
                                    </tr>



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>
                               
                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Applicant ID: </span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;"><b>
                                                    <?php echo $admissioninfo->form_no; ?>
                                                 </b>       
                                                </span>
                                            </p>
                                        </td>
                                    </tr>

                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration: underline;font-size: 8pt;">
                                                    <?php echo $admissioninfo->f_name; ?>
                                                        
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student CNIC:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    <?php echo $admissioninfo->cnic; ?>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Father Name:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                     <?php echo $admissioninfo->father_name; ?>
                                                     
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Degree:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                        <?php echo $admissioninfo->program1; ?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Session:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                      <?php echo $admissioninfo->name; ?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                                                        <tr class="c15">
                                        <td valign="top" colspan="2" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Remarks:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="5" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18" style="text-decoration:underline">
                                                    
                                                       <?php echo $admissioninfo->remark; ?>
                                                    
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                                                 
                                    <tr class="c26">
                                    
                                        <td valign="middle" colspan="4" class="c32" style="text-align: left; border-top: 1px solid; border-bottom: 1px solid;">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c34" colspan="3" style="text-align: right;border-top: 1px solid; border-bottom: 1px solid">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="4" class="c40"  style="text-align: left;">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Admission Fee
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><?=$admissioninfo->admission_fee?></span></p>
                                        </td>
                                    </tr>
                                   

                                    <tr class="c43">
                                        <td valign="middle" colspan="4" class="c44" style="text-align: left;">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Total Amount Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" class="c42" colspan="3" style="text-align: right;">
                                            <p class="c35"><span class="c18"><b>
                                                 <?=$admissioninfo->admission_fee?>
                                            </b> </span></p>
                                        </td>
                                    </tr>

                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10" align="left">
                                            <p class="c16"><span class="c48"><b>Rupees in words:</b> Twenty Thousand Only</span></p>

                                        </td>
                                    </tr>
                                    <hr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left" colspan="1" style="text-align: left;">
                                                        <p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                    <td colspan="5">
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td valign="top" colspan="1"><span class="c38">1.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">After due date, late payment charges of Rs. 100/day will be applicable. </span></p>
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td valign="top" colspan="1"><span class="c38">2.</span></td>
                                                    <td align="justify" colspan="5">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable & non-transferable to any person. </span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                         <td valign="top" colspan="1"><span class="c38">3.</span></td>
                                        <td align="justify" colspan="5">
                                        <p class="c50"><span class="c38">The fee voucher issued is valid only for admission in the given degree programme.</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">4.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">In case of online payment, please send the details of payment on email fee@afroasian.edu.pk or Whatsapp on +923344326090</span></p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" colspan="1"><span class="c38">5.</span></td>
                                                    <td align="justify" colspan="5">
                                                    <p class="c50"><span class="c38">For any query contact us on email at admissions@afroasian.edu.pk or Phone number: 042-35959530. </span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; text-decoration: underline;font-size: 8pt" colspan="3">Bank Stamp</td>
                                                    <td style="font-size: 8pt; text-align: right;text-decoration: underline;" colspan="3">
                                                        Fee Section
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                            <br>
                                            <br>


                                        </td>
                                    </tr>
                                    <br>
                                    <br>
                                    <tr class="c59">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c60"><span class="c61"><b>Student Copy</b></span></p>
                                        </td>
                                    </tr>
                                </table>
                                <p class="c63"><br /></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
</div>
</body>
</html>
