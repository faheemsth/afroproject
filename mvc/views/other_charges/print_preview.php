

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
                    <table class="c69">
                        <tr class="c2">
                            <td class="c3" align="center">
                                <table class="c62">
                                    
                                    <?php  
                                    // echo $siteinfos->sname; 
                                    ?>
                                    <tr class="c11">
                                        <td valign="top" colspan="2" class="c10">
                                            <span>
                                                <img src="mvc/views/invoice/logo.png" width="55px">
                                            </span>
                                        </td>
                                        <td valign="top" colspan="5" class="c10" align="center">
                                            <p class="c12"><span class="c19"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12"><span class="c18">19 Km- FEROZEPUR Road LAHORE</span></p>
                                            <p class="c12"><span  class="c18">Ph: 042 35959530</span>, <span  class="c18">Email: accounts@afroasian.edu.pk</span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike>Transaction to be posted through Alfalah Transact only. </strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: AFASI</strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b>Bank Al-Habib A/C No. 0029 0981012372011</b></span></p>
                                            <!--<p class="c12"><span class="c18"> Branch Code:<b>0073</b></span></p>-->
                                        </td>
                                    </tr>
                                    
                                    <br>
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="4" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?php
                                                    // $this->lang->line('invoice_date_from')
                                                    echo 'Date'    
                                                    ?>: 
                                                    <?=$maininvoice->maininvoicedate?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                        <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?>
                                                        
                                                        <!-- Due Date: 2022-01-15 -->
                                                        

                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c11">-->
                                    <!--    <td valign="top" colspan="7" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Student Name: </span>  </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?=$this->lang->line("invoice_invoice")?>:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->refrence_no; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php  echo $maininvoice->srname; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Roll #:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->srroll; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Registration #:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srregisterNO; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Degree:</span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srclasses; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c15">-->
                                    <!--    <td valign="top" colspan="3" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Semester:</span></p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                   
                                    <tr class="c26">


                                        <td valign="middle" colspan="5" class="c32" align="left">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c34">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_feetype')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=$maininvoice->feetype?></span></p>
                                        </td>
                                    </tr>

                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_amount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoicetotal_fee)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_discount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoice_discount)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_fine')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?php
                                                    // $today = date("Y-m-d");
                                                    // $today_time = strtotime($today);
                                                    // $check_dates = strtotime($maininvoice->maininvoicedue_date);
                                                    // if($check_dates < $today_time){
                                                    // 	$datediff = ceil(($today_time - $check_dates)/86400);
                                                    // 	if($datediff < 31){
                                                    // 	    $final = $datediff * 100;    
                                                    // 	}else{
                                                    // 	    $final = 0;    
                                                    // 	}
                                                	   // echo $final;
                                                    // }else{
                                                    	$final = 0;
                                                	   // echo '0.0';
                                                    // }
                                                    echo '0';
                                                 ?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_subtotal')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?=$maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                            ceil($maininvoicenet_fee)?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>6</b></span><span class="c18"><b>-->
                                                
                                    <!--             </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Theory Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>7</b></span><span class="c18"><b> </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Practical Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                   

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Amount Payable
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                                 <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                            ?>
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left">
                                                        <br><p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top">
                                                        <p class="c50"><span class="c38">1. </span></p>
                                                    </td>
                                                    <td align="left"><span class="c38">The fee voucher will not be accepted after due date.</span></td>
                                                </tr>

                                                <tr>
                                                    <td valign="top"><span class="c38">2.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">The late payment charges will be levied after due date and can not be waived.</span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td valign="top"><span class="c38">3.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable and can be changed without prior notice.</span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top"><span class="c38">4.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">Withholding tax @5% leviable effective July 01, 2013 under section 236I of the ITO, 2001 where annual fee exceeds Rs. 200,000..</span></p>
                                                    </td>
                                                </tr>
                                                </table>


                                        </td>
                                    </tr>
                                    <tr class="c51">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                           <!-- @*<p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>*@-->
                                        </td>
                                    </tr>
                                       <tr class="c53">
                                        <td valign="top" colspan="4" class="c54" style="text-align:left;">
                                            <p class="c55">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's Signature
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="3" class="c57" style="text-align:right;">
                                            <p class="c58">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's CNIC
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                    <tr class="c53">
                                        <td valign="top" colspan="7" class="c54">
                                            <p class="c55">
                                                <span class="c56">
                                                    <b><u>
                                                        Bank Stamp
                                                    </u></b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="2" align="left">
                                        <br />
                                            <span style="font-size:8px;"><?php echo ($maininvoice->user_c == '')? 0 : $maininvoice->user_c; ?></span>
                                        </td>
                                        
                                        <td valign="bottom" colspan="4">
                                            <span class="c61"><b>Bank Copy &nbsp;&nbsp;&nbsp;</b></span>
                                        </td>
                                    </tr>
                                </table>
                            <!--second voucher-->
                            <td class="c64" align="center">
                                <table class="c62">
                                    
                                    <?php  
                                    // echo $siteinfos->sname; 
                                    ?>
                                    <tr class="c11">
                                        <td valign="top" colspan="2" class="c10">
                                            <span>
                                                <img src="mvc/views/invoice/logo.png" width="55px">
                                            </span>
                                        </td>
                                        <td valign="top" colspan="5" class="c10" align="center">
                                            <p class="c12"><span class="c19"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12"><span class="c18">19 Km- FEROZEPUR Road LAHORE</span></p>
                                            <p class="c12"><span  class="c18">Ph: 042 35959530</span>, <span  class="c18">Email: accounts@afroasian.edu.pk</span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike>Transaction to be posted through Alfalah Transact only. </strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: AFASI</strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b>Bank Al-Habib A/C No. 0029 0981012372011</b></span></p>
                                            <!--<p class="c12"><span class="c18"> Branch Code:<b>0073</b></span></p>-->
                                        </td>
                                    </tr>
                                    
                                    <br>
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="4" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?php
                                                    // $this->lang->line('invoice_date_from')
                                                    echo 'Date'    
                                                    ?>: 
                                                    <?=$maininvoice->maininvoicedate?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                        <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?>
                                                        
                                                        <!-- Due Date: 2022-01-15 -->
                                                        

                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c11">-->
                                    <!--    <td valign="top" colspan="7" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Student Name: </span>  </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?=$this->lang->line("invoice_invoice")?>:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->refrence_no; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php  echo $maininvoice->srname; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Roll #:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->srroll; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Registration #:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srregisterNO; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Degree:</span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srclasses; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c15">-->
                                    <!--    <td valign="top" colspan="3" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Semester:</span></p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                   <tr class="c26">


                                        <td valign="middle" colspan="5" class="c32" align="left">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c34">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_feetype')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=$maininvoice->feetype?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_amount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoicetotal_fee)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_discount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoice_discount)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_fine')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?php
                                                    // $today = date("Y-m-d");
                                                    // $today_time = strtotime($today);
                                                    // $check_dates = strtotime($maininvoice->maininvoicedue_date);
                                                    // if($check_dates < $today_time){
                                                    // 	$datediff = ceil(($today_time - $check_dates)/86400);
                                                    // 	if($datediff < 31){
                                                    // 	    $final = $datediff * 100;    
                                                    // 	}else{
                                                    // 	    $final = 0;    
                                                    // 	}
                                                	   // echo $final;
                                                    // }else{
                                                    	$final = 0;
                                                	   // echo '0.0';
                                                    // }
                                                    echo '0.0';
                                                 ?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_subtotal')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                                ?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>6</b></span><span class="c18"><b>-->
                                                
                                    <!--             </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Theory Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>7</b></span><span class="c18"><b> </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Practical Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Amount Payable
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                             <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                    echo ceil($maininvoicenet_fee);
                                            ?>
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left">
                                                        <br><p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top">
                                                        <p class="c50"><span class="c38">1. </span></p>
                                                    </td>
                                                    <td align="left"><span class="c38">The fee voucher will not be accepted after due date.</span></td>
                                                </tr>

                                                <tr>
                                                    <td valign="top"><span class="c38">2.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">The late payment charges will be levied after due date and can not be waived.</span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td valign="top"><span class="c38">3.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable and can be changed without prior notice.</span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top"><span class="c38">4.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">Withholding tax @5% leviable effective July 01, 2013 under section 236I of the ITO, 2001 where annual fee exceeds Rs. 200,000..</span></p>
                                                    </td>
                                                </tr>
                                                </table>


                                        </td>
                                    </tr>
                                    <tr class="c51">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                           <!-- @*<p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>*@-->
                                        </td>
                                    </tr>
                                       <tr class="c53">
                                        <td valign="top" colspan="4" class="c54" style="text-align:left;">
                                            <p class="c55">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's Signature
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="3" class="c57" style="text-align:right;">
                                            <p class="c58">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's CNIC
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                    <tr class="c53">
                                        <td valign="top" colspan="7" class="c54">
                                            <p class="c55">
                                                <span class="c56">
                                                    <b><u>
                                                        Bank Stamp
                                                    </u></b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="2" align="left">
                                        <br />
                                            <span style="font-size:8px;"><?php echo ($maininvoice->user_c == '')? 0 : $maininvoice->user_c; ?></span>
                                        </td>
                                        
                                        <td valign="bottom" colspan="4">
                                            <span class="c61"><b>Office Copy &nbsp;&nbsp;&nbsp;</b></span>
                                        </td>
                                    </tr>
                                </table>
                                <p class="c63"><br /></p>
                            </td>
                            <!--third voucher-->
                            <td class="c64" align="center">
                                <table class="c62">
                                    
                                    <?php  
                                    // echo $siteinfos->sname; 
                                    ?>
                                    <tr class="c11">
                                        <td valign="top" colspan="2" class="c10">
                                            <span>
                                                <img src="mvc/views/invoice/logo.png" width="55px">
                                            </span>
                                        </td>
                                        <td valign="top" colspan="5" class="c10" align="center">
                                            <p class="c12"><span class="c19"><b>AFRO-ASIAN INSTITUE</b></span></p>
                                            <p class="c12"><span class="c18">19 Km- FEROZEPUR Road LAHORE</span></p>
                                            <p class="c12"><span  class="c18">Ph: 042 35959530</span>, <span  class="c18">Email: accounts@afroasian.edu.pk</span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike>Transaction to be posted through Alfalah Transact only. </strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: AFASI</strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b>Bank Al-Habib A/C No. 0029 0981012372011</b></span></p>
                                            <!--<p class="c12"><span class="c18"> Branch Code:<b>0073</b></span></p>-->
                                        </td>
                                    </tr>
                                    
                                    <br>
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="4" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?php
                                                    // $this->lang->line('invoice_date_from')
                                                    echo 'Date'    
                                                    ?>: 
                                                    <?=$maininvoice->maininvoicedate?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                        <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?>
                                                        
                                                        <!-- Due Date: 2022-01-15 -->
                                                        

                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    



                                    <tr class="c11">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c14"><br /></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c11">-->
                                    <!--    <td valign="top" colspan="7" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Student Name: </span>  </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17"><?=$this->lang->line("invoice_invoice")?>:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->refrence_no; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    
                                    
                                    
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Student Name:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php  echo $maininvoice->srname; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Roll #:</span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                        <?php echo $maininvoice->srroll; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21">
                                                <span class="c17">
                                                    Registration #:<b>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srregisterNO; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="c15">
                                        <td valign="top" colspan="3" class="c20" align="left">
                                            <p class="c21"><span class="c17">Degree:</span></p>
                                        </td>
                                        <td valign="top" colspan="4" class="c22" align="left">
                                            <p class="c23">
                                                <span class="c18">
                                                    <b>
                                                       <?php echo $maininvoice->srclasses; ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c15">-->
                                    <!--    <td valign="top" colspan="3" class="c20">-->
                                    <!--        <p class="c21"><span class="c17">Semester:</span></p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="top" colspan="4" class="c22" align="left">-->
                                    <!--        <p class="c23">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                                        
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                   
                                    <tr class="c26">


                                        <td valign="middle" colspan="5" class="c32" align="left">
                                            <p class="c33">
                                                <span class="c29">
                                                    <b>
                                                        Description
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c34">
                                            <p class="c35"><span class="c29"><b>Amount (PKR)</b></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_feetype')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=$maininvoice->feetype?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_amount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoicetotal_fee)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_discount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoice_discount)?></span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_fine')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?php
                                                    // $today = date("Y-m-d");
                                                    // $today_time = strtotime($today);
                                                    // $check_dates = strtotime($maininvoice->maininvoicedue_date);
                                                    // if($check_dates < $today_time){
                                                    // 	$datediff = ceil(($today_time - $check_dates)/86400);
                                                    // 	if($datediff < 31){
                                                    // 	    $final = $datediff * 100;    
                                                    // 	}else{
                                                    // 	    $final = 0;    
                                                    // 	}
                                                	   // echo $final;
                                                    // }else{
                                                    	$final = 0;
                                                	   // echo '0.0';
                                                    // }
                                                    echo '0.0';
                                                 ?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c36">

                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_subtotal')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                              <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                                ?>
                                            </span></p>
                                        </td>
                                    </tr>
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>6</b></span><span class="c18"><b>-->
                                                
                                    <!--             </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Theory Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    <!--<tr class="c36">-->
                                    <!--    <td valign="middle" class="c37">-->
                                    <!--        <p class="c28"><span class="c38"><b>7</b></span><span class="c18"><b> </b></span></p>-->
                                    <!--    </td>-->


                                    <!--    <td valign="middle" colspan="4" class="c40">-->
                                    <!--        <p class="c41">-->
                                    <!--            <span class="c18">-->
                                    <!--                <b>-->
                                    <!--                    Repeat Course fee (Practical Only)-->
                                    <!--                </b>-->
                                    <!--            </span>-->
                                    <!--        </p>-->
                                    <!--    </td>-->
                                    <!--    <td valign="middle" class="c42">-->
                                    <!--        <p class="c35"><span class="c18"></span></p>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                   

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Amount Payable
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                            <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                            ?>
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <table border="0" width="100%" cellspacing="1">
                                                <tr>
                                                    <td valign="top"><span class="c38"></span></td>

                                                    <td align="left">
                                                        <br><p class="c50"><span class="c38"><b>Note: </b></span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top">
                                                        <p class="c50"><span class="c38">1. </span></p>
                                                    </td>
                                                    <td align="left"><span class="c38">The fee voucher will not be accepted after due date.</span></td>
                                                </tr>

                                                <tr>
                                                    <td valign="top"><span class="c38">2.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">The late payment charges will be levied after due date and can not be waived.</span></p>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td valign="top"><span class="c38">3.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">All fees paid are non-refundable and can be changed without prior notice.</span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top"><span class="c38">4.</span></td>
                                                    <td align="left">
                                                        <p class="c50"><span class="c38">Withholding tax @5% leviable effective July 01, 2013 under section 236I of the ITO, 2001 where annual fee exceeds Rs. 200,000..</span></p>
                                                    </td>
                                                </tr>
                                                </table>


                                        </td>
                                    </tr>
                                    <tr class="c51">
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                           <!-- @*<p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>*@-->
                                        </td>
                                    </tr>
                                       <tr class="c53">
                                        <td valign="top" colspan="4" class="c54" style="text-align:left;">
                                            <p class="c55">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's Signature
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="top" colspan="3" class="c57" style="text-align:right;">
                                            <p class="c58">
                                                <span class="c56" style="font-size:11px;border-top:1px solid;">
                                                    <b>
                                                        Depositor's CNIC
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                            <p class="c52"><br /></p>
                                            <p class="c52"><br /></p>
                                    <tr class="c53">
                                        <td valign="top" colspan="7" class="c54">
                                            <p class="c55">
                                                <span class="c56">
                                                    <b><u>
                                                        Bank Stamp
                                                    </u></b>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="2" align="left">
                                        <br />
                                            <span style="font-size:8px;"><?php echo ($maininvoice->user_c == '')? 0 : $maininvoice->user_c; ?></span>
                                        </td>
                                        
                                        <td valign="bottom" colspan="4">
                                            <span class="c61"><b>Student Copy &nbsp;&nbsp;&nbsp;</b></span>
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
