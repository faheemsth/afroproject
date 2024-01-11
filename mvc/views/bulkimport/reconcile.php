

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> Reconcile</h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Reconcile</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            
                <div id="hide-table">
                    <!--id="example1"-->
                    <table  class="table table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1">#</th>
                                <th class="col-sm-1">Student Id</th>
                                <th class="col-sm-1">Payment Date</th>
                                <th class="col-sm-2">Student Roll</th>
                                <th class="col-sm-2">Challan no</th>
                                <th class="col-sm-2">Amount</th>
                                <th class="col-sm-1">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                             if(customCompute($reconcile)) {$i = 1; 
                                $active_reocord     =   0;
                                $disable_reocord    =   0;

                               $total_amount_csv    =  0;
                               $total_amount_sys    =  0;
                                foreach($reconcile as $data) { ?>
                                <tr >
                                    <td>
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-toggle="tooltip" data-placement="top" title="<?php echo ($data->student_id_status == 2)? 'Student Not Found': ''; ?>" <?php echo ($data->student_id_status == 2)? 'class="ini-bg-danger"': ''; ?>>
                                        <?php echo $data->student_id; ?>

                                    </td>
                                    <td data-toggle="tooltip" data-placement="top" title="<?php echo ($data->payment_date_status == 2)? 'Date Format Not Correct': ''; ?>" <?php echo ($data->payment_date_status == 2)? 'class="ini-bg-danger"': ''; ?>>
                                        <?php echo $data->payment_date; ?>
                                    </td>
                                    <td data-toggle="tooltip" data-placement="top" title="<?php echo ($data->student_roll_status == 2)? 'Student Not Found': ''; ?>" <?php echo ($data->student_roll_status == 2)? 'class="ini-bg-danger"': ''; ?>>
                                        <?php echo $data->student_roll; ?>
                                    </td>

                                    <td data-toggle="tooltip" data-placement="top" title="<?php echo ($data->challan_no_status == 2)? 'Invoice Not Found': ''; ?><?php echo ($data->challan_no_status == 3)? 'Duplicate Challan Number': ''; ?><?php echo ($data->challan_no_status == 4)? 'Challan status is deleted': ''; ?>" <?php echo ($data->challan_no_status == 2)? 'class="ini-bg-danger"': ''; ?> <?php echo ($data->challan_no_status == 3)? 'class="ini-bg-warning"': ''; ?> <?php echo ($data->challan_no_status == 4)? 'class="ini-bg-warning"': ''; ?>>
                                        <?php echo $data->challan_no; ?>
                                    </td>

                                    <td data-toggle="tooltip" data-placement="top" title="<?php echo ($data->amount_status == 2)? 'Payment Not Match': ''; ?><?php echo ($data->amount_status == 3)? 'Invoice Already Paid': ''; ?>" <?php echo ($data->amount_status == 2 or $data->amount_status == 3)? 'class="ini-bg-danger"': ''; ?>>
                                        <?php echo $data->amount; ?> <?php if($data->amount_status==2){
                                            echo ' ('.$data->invoice_amount.')';
                                        }?>
                                    </td>
                                    <td 

                                     <?php

                                      echo ($data->status == 0)? 'class="ini-bg-danger"': ''; ?>>
                                       <?php echo btn_view('invoice/view/'.$data->maininvoiceID, 
                                    'View Invoice',"_blank");?> -
                                        <?php 
                                            echo ($data->status == 1)? 'active':'disable'; 
                                             

                                           $cpv_bpv_recordID    =  $data->cpv_bpv_recordID;
                                           $total_amount_csv    +=  $data->amount;
                                           $total_amount_sys    +=  $data->invoice_amount;
                                           $all_record          =  $i; 

                                            $data->status? $active_reocord++:$disable_reocord++; 
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                    
                </div>


            </div>
            <div class="col-sm-12">
                <?php
               // var_dump($uploadrecord);
                if (customCompute($reconcile)) {
                    # code...
               
                 if($disable_reocord > 0 or $uploadrecord->total_record!=count($reconcile) or $total_amount_csv-$total_amount_sys!=0){ ?>
                   
                    <div class="alert alert-danger">
                        Records Not Matched <br>
                        Total record in CSV <?php echo $uploadrecord->total_record;?><br>
                        Total record <?php echo $all_record;?><br>
                        Total Active Record <?php echo $active_reocord;?><br>
                        Total Disabled Record <?php echo $disable_reocord;?><br>
                        Total Amount in CSV <?php echo $total_amount_csv;?><br>
                        Total Active Amount  <?php echo $total_amount_sys;?><br>
                        Total Difference  <?php echo $total_amount_csv-$total_amount_sys;?><br>

                        <form action="<?=base_url('bulkimport/reconcile_rollback');?>" class="form-horizontal" role="form" method="post">
                <input type="hidden" name="cpv_bpv_recordID" value="<?php echo $cpv_bpv_recordID;?>">   
                <input type="submit" onclick="return confirm('You are about to delete all records. This cannot be undone. Are you sure?')" class="btn btn-danger pull-right" value="Roll Back">
                </form> 
                <span class="clearfix"></span>
                    </div>
                    <?php }else{ ?>
                        <div class="alert alert-success">
                        Records   Matched <br>
                        Total record in CSV <?php echo $uploadrecord->total_record;?><br>
                        Total record <?php echo $all_record;?><br>
                        Total Active Record <?php echo $active_reocord;?><br>
                        Total Disabled Record <?php echo $disable_reocord;?><br>
                        Total Amount in CSV <?php echo $total_amount_csv;?><br>
                        Total Active Amount  <?php echo $total_amount_sys;?><br>
                        Total Difference  <?php echo $total_amount_csv-$total_amount_sys;?><br>
                <form action="<?=base_url('bulkimport/reconcile_bulkimport');?>" class="form-horizontal" role="form" method="post">
                


                    <?php
                        if(form_error('chart_account_id'))
                            echo "<div class='form-group col-sm-6 has-error' >";
                        else
                            echo "<div class='form-group  col-sm-6 ' >";
                    ?>
                        <label for="chart_account_id" class="col-sm-3 control-label">
                            Payment Method <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array();
                                $array[0] = 'Please Select';
                                foreach ($bank_accounts as $b) {
                                    $array[$b->chart_account_id] = $b->bank_name;
                                }
                                echo form_dropdown("chart_account_id", $array, set_value("chart_account_id"), "id='chart_account_id' class='form-control select2' required");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('chart_account_id'); ?>
                        </span>
                    </div>
                <input type="hidden" name="post_data" value="1">   
                <input type="submit" class="btn btn-success pull-right" value="Post Data">
                </form>  
                <span class="clearfix"></span> 
                </div> 
                <?php } 
                 }
                ?>      
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
    $("td").tooltip({container:'body'});
});
</script>