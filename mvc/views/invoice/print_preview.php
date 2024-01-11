

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
                                            <p class="c12"><span class="c19"><b><?php echo $siteinfos->sname;?></b></span></p>
                                            <p class="c12"><span class="c18"><?php echo $siteinfos->address;?></span></p>
                                            <p class="c12"><span  class="c18">Phone: <?php echo $siteinfos->phone;?></span>, 
                                            <br>
                                            <span  class="c18">Email: <?php echo $siteinfos->voucher_email;?></span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike><?php echo $siteinfos->transaction_title;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: <?php echo $siteinfos->company_code;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b><?php echo $siteinfos->bank_account;?></b></span></p>
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
                                                    <?=date('d-m-Y',strtotime($maininvoice->maininvoicedate))?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                       <!--  <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?> -->
                                                        
                                                        Due Date: <?php 
                            $set_date   = date('Y-m-d',strtotime($siteinfos->invoice_date));
                            $voc_date   = date('Y-m-d',strtotime($maininvoice->maininvoicedue_date));

                            $cur_date   =  date('Y-m-d');
                            if($voc_date >= $set_date){
                                echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                            }else{
                                  if ($set_date >= $cur_date) {
                                       echo date('d-m-Y',strtotime($set_date));
                                    }else{

                                            echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                                    }
                            }
                          
                            ?>
                                                        

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
                                    
                                    <?php


                                        if ($maininvoice->maininvoicesectionID == $maininvoice->srsectionID) {
                                            $balance = $maininvoice->balance / $maininvoice->no_installment;
                                            ?>
                                            <!-- <tr class="c36">
                                                <td valign="middle" colspan="5" class="c40" align="left">
                                                    <p class="c41">
                                                        <span class="c18">
                                                            <b>
                                                                <?=$this->lang->line('invoice_prev_balance')?>
                                                            </b>
                                                        </span>
                                                    </p>
                                                </td>
                                                <td valign="middle"  colspan="2" class="c42">
                                                    <p class="c35"><span class="c18"><?=ceil($balance)?></span></p>
                                                </td>
                                            </tr> -->
                                            <?php
                                        }
                                    ?>
                                   
                                    <?php 

                                    error_reporting(0);
                                    $fee_breakup    =   unserialize( $maininvoice->fee_breakup);

                                    
                                     
                                    for ($sr=0;$sr<count($fee_breakup);$sr++) {

                                        
                                       
                                        
                                        ?>
<tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
 
                                                        <?php 
                                                       
                                                        //var_dump($fee_breakup);
                                                        echo $fee_breakup[$sr]['feetypes'];?></b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                    
                                                <?php  echo $fee_breakup[$sr]['fee_amount']; ?>  &nbsp;</span></p>
                                        </td>
                                    </tr>

                                        <?php
                                       
                                    }

                                    
                                    ?>

                                   <!--  <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Fee Type
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                <?php 
                                                $arrayfeeType = array(
                                        
                                        "invoice" => 'Tution Fee',
                                        "other_charges" => 'Other Charges',
                                        "library_fine" => 'Library Fine',
                                        "hostel_fee" => 'Hostel Fee',
                                        "Transport_fee" => 'Transport Fee',
                                        );

                                                echo $arrayfeeType[$maininvoice->maininvoice_type_v]
                                                ?>

                                                
                                              &nbsp;</span></p>
                                        </td>
                                    </tr> -->
                                    
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoice_discount)?> &nbsp;</span></p>
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
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
                                                 ?>  &nbsp;
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?=$maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                            ceil($maininvoicenet_fee)?> &nbsp;
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
                                    <tr class="c36">
                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Others <?= $maininvoice->maininvoice_type_v == 'other_charges' ? '( '.$maininvoice->remarks.' )' : '' ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"></span></p>
                                        </td>
                                    </tr>

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Fee Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                                 <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                            ?> &nbsp;
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <?php echo $siteinfos->voucher_notes;?>


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
                                            <p class="c12"><span class="c19"><b><?php echo $siteinfos->sname;?></b></span></p>
                                            <p class="c12"><span class="c18"><?php echo $siteinfos->address;?></span></p>
                                            <p class="c12"><span  class="c18">Phone: <?php echo $siteinfos->phone;?></span>,

                                            <br>
                                             <span  class="c18">Email: <?php echo $siteinfos->voucher_email;?></span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike><?php echo $siteinfos->transaction_title;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: <?php echo $siteinfos->company_code;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b><?php echo $siteinfos->bank_account;?></b></span></p>
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
                                                    <?=date('d-m-Y',strtotime($maininvoice->maininvoicedate))?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                       <!--  <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?> -->
                                                        
                                                        Due Date: <?php 
                            $set_date   = date('Y-m-d',strtotime($siteinfos->invoice_date));
                            $voc_date   = date('Y-m-d',strtotime($maininvoice->maininvoicedue_date));

                            $cur_date   =  date('Y-m-d');
                            if($voc_date >= $set_date){
                                echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                            }else{
                                  if ($set_date >= $cur_date) {
                                       echo date('d-m-Y',strtotime($set_date));
                                    }else{

                                            echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                                    }
                            }
                          
                            ?>
                                                        

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
                                    
                                     <?php
                                        if ($maininvoice->maininvoicesectionID == $maininvoice->srsectionID) {
                                            $balance = $maininvoice->balance / $maininvoice->no_installment;
                                            ?>
                                            <!-- <tr class="c36">
                                                <td valign="middle" colspan="5" class="c40" align="left">
                                                    <p class="c41">
                                                        <span class="c18">
                                                            <b>
                                                                <?=$this->lang->line('invoice_prev_balance')?>
                                                            </b>
                                                        </span>
                                                    </p>
                                                </td>
                                                <td valign="middle"  colspan="2" class="c42">
                                                    <p class="c35"><span class="c18"><?=ceil($balance)?></span></p>
                                                </td>
                                            </tr> -->
                                            <?php
                                        }
                                    ?>


                                  <!--   <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Fee Type
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                <?php 
                                                $arrayfeeType = array(
                                        
                                        "invoice" => 'Tution Fee',
                                        "other_charges" => 'Other Charges',
                                        "library_fine" => 'Library Fine',
                                        "hostel_fee" => 'Hostel Fee',
                                        "Transport_fee" => 'Transport Fee',
                                        );

                                                echo $arrayfeeType[$maininvoice->maininvoice_type_v]
                                                ?>
                                              &nbsp;</span></p>
                                        </td>
                                    </tr> -->
                                    <!-- <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        <?=$this->lang->line('invoice_amount')?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoicetotal_fee)?>  &nbsp;</span></p>
                                        </td>
                                    </tr> -->

                                     <?php 

                                    error_reporting(0);
                                    $fee_breakup    =   unserialize( $maininvoice->fee_breakup);

                                    
                                    // var_dump($fee_breakup);
                                    for ($sr=0;$sr<count($fee_breakup);$sr++) {

                                        
                                       
                                        
                                        ?>
<tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
 
                                                        <?php echo $fee_breakup[$sr]['feetypes'];?></b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                    
                                                <?php  echo $fee_breakup[$sr]['fee_amount']; ?>  &nbsp;</span></p>
                                        </td>
                                    </tr>

                                        <?php
                                       
                                    }

                                    
                                    ?>
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
                                        <td valign="middle" align="right"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoice_discount)?> &nbsp;</span></p>
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
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
                                                 ?>  &nbsp;
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                 <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                                ?>  &nbsp;
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
                                    <tr class="c36">
                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Others <?= $maininvoice->maininvoice_type_v == 'other_charges' ? '( '.$maininvoice->remarks.' )' : '' ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"></span></p>
                                        </td>
                                    </tr>

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Fee Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                             <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                    echo ceil($maininvoicenet_fee);
                                            ?>  &nbsp;
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <?php echo $siteinfos->voucher_notes;?>


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
                                            <p class="c12"><span class="c19"><b><?php echo $siteinfos->sname;?></b></span></p>
                                            <p class="c12"><span class="c18"><?php echo $siteinfos->address;?></span></p>
                                            <p class="c12"><span  class="c18">Phone: <?php echo $siteinfos->phone;?></span>,
                                            <br> <span  class="c18">Email: <?php echo $siteinfos->voucher_email;?></span></p>
                                            <!--<p class="c12"><span class="c18"><b>Title:<?php  echo $siteinfos->sname; ?></b></span></p>-->
                                            <p class="c12"></p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p>
                                                <span class="c18" style="text-decoration:underline;font-size:9px;"><b><strike><?php echo $siteinfos->transaction_title;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">
                                            <p class="c12">
                                                
                                                <span class="c18" style="text-decoration:underline;text-align:justify;font-size:9px;"><b><strike>Company Code: <?php echo $siteinfos->company_code;?></strike></b></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td valign="top" colspan="7" class="c10">                                          
                                            <p class="c12"><span class="c18" style="text-decoration:underline;font-size:9px;"><b>
                                                <?php echo $siteinfos->bank_account;?>
                                            </b></span></p>
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
                                                    <?=date('d-m-Y',strtotime($maininvoice->maininvoicedate))?></span><span class="c19"><b> </b></span></p>
                                        </td>
                                        <td valign="top" colspan="3" class="c22" align="right">
                                            <p class="c23">
                                                <span class="c18">

                                                        <!-- <?=$this->lang->line('invoice_date_to')?>: <?=$maininvoice->maininvoicedue_date?> -->
                                                        
                                                        Due Date:  <?php 
                            $set_date   = date('Y-m-d',strtotime($siteinfos->invoice_date));
                            $voc_date   = date('Y-m-d',strtotime($maininvoice->maininvoicedue_date));

                            $cur_date   =  date('Y-m-d');
                            if($voc_date >= $set_date){
                                echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                            }else{
                                  if ($set_date >= $cur_date) {
                                       echo date('d-m-Y',strtotime($set_date));
                                    }else{

                                            echo date('d-m-Y',strtotime($maininvoice->maininvoicedue_date));
                                    }
                            }
                          
                            ?>
                                                        

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
                                     <?php
                                        if ($maininvoice->maininvoicesectionID == $maininvoice->srsectionID) {
                                            $balance = $maininvoice->balance / $maininvoice->no_installment;
                                            ?>
                                            <!-- <tr class="c36">
                                                <td valign="middle" colspan="5" class="c40" align="left">
                                                    <p class="c41">
                                                        <span class="c18">
                                                            <b>
                                                                <?=$this->lang->line('invoice_prev_balance')?>
                                                            </b>
                                                        </span>
                                                    </p>
                                                </td>
                                                <td valign="middle"  colspan="2" class="c42">
                                                    <p class="c35"><span class="c18"><?=ceil($balance)?></span></p>
                                                </td>
                                            </tr> -->
                                            <?php
                                        }
                                    ?>


                                    <!-- <tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Fee Type
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                <?php 
                                                $arrayfeeType = array(
                                        
                                        "invoice" => 'Tution Fee',
                                        "other_charges" => 'Other Charges',
                                        "library_fine" => 'Library Fine',
                                        "hostel_fee" => 'Hostel Fee',
                                        "Transport_fee" => 'Transport Fee',
                                        );

                                                echo $arrayfeeType[$maininvoice->maininvoice_type_v]
                                                ?>
                                              &nbsp;</span></p>
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
                                        <td valign="middle" align="right"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><?=ceil($maininvoice->maininvoicetotal_fee)?>  &nbsp;</span></p>
                                        </td>
                                    </tr> -->
                                     <?php 

                                    error_reporting(0);
                                    $fee_breakup    =   unserialize( $maininvoice->fee_breakup);

                                    
                                    // var_dump($fee_breakup);
                                    for ($sr=0;$sr<count($fee_breakup);$sr++) {

                                        
                                       
                                        
                                        ?>
<tr class="c36">


                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
 
                                                        <?php echo $fee_breakup[$sr]['feetypes'];?></b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                                    
                                                <?php 
                                                
                                                 echo $fee_breakup[$sr]['fee_amount']; ?>  &nbsp;</span></p>
                                        </td>
                                    </tr>

                                        <?php
                                       
                                    }

                                    
                                    ?>
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18" ><?=ceil($maininvoice->maininvoice_discount)?> &nbsp;</span></p>
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
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
                                                 ?>  &nbsp;
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
                                        <td valign="middle" align="right" colspan="2" class="c42">
                                            <p class="c35"><span class="c18">
                                              <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                                ?>  &nbsp;
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
                                    <tr class="c36">
                                        <td valign="middle" colspan="5" class="c40" align="left">
                                            <p class="c41">
                                                <span class="c18">
                                                    <b>
                                                        Others <?= $maininvoice->maininvoice_type_v == 'other_charges' ? '( '.$maininvoice->remarks.' )' : '' ?>
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"></span></p>
                                        </td>
                                    </tr>

                                    <tr class="c43">
                                        <td valign="middle" colspan="5" class="c44" align="left">
                                            <p class="c45">
                                                <span class="c18">
                                                    <b>
                                                        Fee Payable:
                                                    </b>
                                                </span>
                                            </p>
                                        </td>
                                        <td valign="middle" align="right"  colspan="2" class="c42">
                                            <p class="c35"><span class="c18"><b>
                                            <?php $maininvoicenet_fee = $maininvoice->maininvoicenet_fee + $final;
                                                        echo ceil($maininvoicenet_fee);
                                            ?> &nbsp;
                                            </b> </span></p>
                                        </td>
                                    </tr>
                                    <tr class="c47">
                                        <td valign="top" colspan="7" class="c10">
                                        


                                            <?php echo $siteinfos->voucher_notes;?>


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
