<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-cart-plus"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('purchase_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('purchase/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
              <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('purchase_assetID')?></th>
                                <th class=""><?=$this->lang->line('purchase_vendorID')?></th>
                                <th class=""><?=$this->lang->line('purchase_quantity')?></th>
                                <th class=""><?=$this->lang->line('purchase_unit')?></th>
                                <th class=""><?=$this->lang->line('purchase_price')?></th>
                                <th class=""><?=$this->lang->line('purchase_date')?></th> 
                                <th class=""><?=$this->lang->line('purchased_by')?></th>
                                <?php if(permissionChecker('purchaseapprove1')) {?>
                                <th>Approve 1</th>
                                <?php }?>
                                <?php if(permissionChecker('purchaseapprove2')) {?>
                                <th>Approve 2</th>
                                <?php }?> 
                                <th class="">Delivery Status</th>
                               <!--  <th class="">Convert Status</th> -->
                                <?php if(permissionChecker('purchase_edit') || permissionChecker('purchase_delete')) { ?>
                                <th class=""><?=$this->lang->line('action')?></th>
                                <?php } ?>


                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($purchases)) {$i = 1; foreach($purchases as $purchase) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_assetID')?>">
                                        <?php echo $purchase->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_vendorID')?>">
                                        <?php echo $purchase->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_quantity')?>">
                                        <?php echo $purchase->quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_unit')?>">
                                        <?php echo isset($unit[$purchase->unit]) ? $unit[$purchase->unit] : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_price')?>">
                                        <?php echo $purchase->purchase_price; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_date')?>">
                                        <?php echo isset($purchase->purchase_date) ? date("d M Y", strtotime($purchase->purchase_date)) : ''; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('purchased_by')?>">
                                        <?php echo $purchase->purchaser_name;
                                        
                                         ?>
                                    </td>

                                     <?php if(permissionChecker('purchaseapprove1')) {?>
                                    <td data-title="<?=$this->lang->line('purchase_grandtotal')?>">
                                        <?php if($purchase->status == 0){?>
                                         <div class="onoffswitch-small" id="<?=$purchase->purchaseID?>">
                                          <input type="checkbox" id="myonoffswitch<?=$purchase->purchaseID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_first" name="aprove_status_first" <?php if($purchase->aprove_status_first === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch<?=$purchase->purchaseID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>
                                      <?php  } if($purchase->aprove_status_first === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($purchase->firstAprove_usertypeID,$purchase->firstAprove_userID)?>
                                            (<?=$usertypes[$purchase->firstAprove_usertypeID]?>)
                                      <?php }?>
                                      
                                    </td>
                                     <?php } if(permissionChecker('purchaseapprove2')) {?>
                                    <td data-title="<?=$this->lang->line('purchase_grandtotal')?>">
                                        <?php if($purchase->status == 0){?>
                                         <div class="onoffswitch-small" id="<?=$purchase->purchaseID?>">
                                          <input type="checkbox" id="myonoffswitch_second<?=$purchase->purchaseID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_second" name="aprove_status_second" <?php if($purchase->aprove_status_second === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch_second<?=$purchase->purchaseID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                          </div>

                                      <?php } if($purchase->aprove_status_second === '1'){?>
                                        <?=getNameByUsertypeIDAndUserID($purchase->secondAprove_usertypeID,$purchase->secondAprove_userID)?>
                                            (<?=$usertypes[$purchase->secondAprove_usertypeID]?>)
                                      <?php }?>
                                    </td>
                                    <?php }?> 
                                     <?php if(permissionChecker('purchasedelivery')) {?>
                                      <td>
                                       <?php
                                       if($purchase->aprove_status_first == '1' && $purchase->aprove_status_second == '1'){
                                        if($purchase->aprove_status_delivery == 0){?>
                                        <a href="javascript:;" class="btn-cs btn-sm-cs" onclick="updatedilerystatus(<?php echo $purchase->purchaseID;?>)" ><span class="fa fa-print"></span> Delivery  </a>
                                      <?php }else{
                                              echo "Delivery processed  by ";?>
                                               <?=getNameByUsertypeIDAndUserID($purchase->deliveryAprove_usertypeID,$purchase->deliveryAprove_userID)?>
                                            (<?=$usertypes[$purchase->secondAprove_usertypeID]?>)
                                              <?php
                                            } 
                                          }else{
                                            echo "Need Approval first";
                                          }
                                            ?> 
                                  
                                      </td>
                                    <?php }?> 
                                   <!--   <?php if(permissionChecker('purchaseconvettoexpense')) {?>
                                      <td>
                                        <?php if($purchase->aprove_status_first == '1' && $purchase->aprove_status_second == '1'){?>
                                       <a href="javascript:;" class="btn-cs btn-sm-cs" ><span class="fa fa-print"></span> Convert To Expense <?php if($purchase->aprove_status_convettoexpense == 0){?>
                                           <div class="onoffswitch-small" id="<?=$purchase->purchaseID?>">
                                          <input type="checkbox" id="myonoffswitch_convettoexpense<?=$purchase->purchaseID?>" class="onoffswitch-small-checkbox onoffswitch-small-checkbox_convettoexpense" name="aprove_status_convettoexpense" <?php if($purchase->aprove_status_convettoexpense === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch_convettoexpense<?=$purchase->purchaseID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                          </div>

                                      <?php }else{
                                              echo "Converted to Expense";
                                            } ?> </a>
                                      <?php 
                                             }else{
                                            echo "Need Approval first";
                                          }
                                      ?>
                                    </td>     
                                    <?php }?>  -->

                                  <?php if(permissionChecker('purchase_edit') || permissionChecker('purchase_delete')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('purchase/view/'.$purchase->purchaseID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('purchase/edit/'.$purchase->purchaseID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('purchase/delete/'.$purchase->purchaseID, $this->lang->line('delete')) ?>
                                            <?php
                                                /*if(permissionChecker('purchase_edit')) {
                                                    if($purchase->status == 1) {
                                                        echo btn_status_show('purchase/status/'.$purchase->purchaseID, $this->lang->line('purchase_status_approved'));
                                                    } else {
                                                        echo btn_not_status_show('purchase/status/'.$purchase->purchaseID, $this->lang->line('purchase_status_not_approved'));
                                                    }
                                                }*/
                                            ?>
                                        </td>
                                  <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

  function updatedilerystatus(purchaseID) {
        
               Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to add this product to the store",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                  }).then((result) => {
                    if (result.isConfirmed) { 


          $.ajax({
              type: 'POST',
              url: "<?=base_url('purchase/active_delivery')?>",
              data: "id=" + purchaseID + "&status=chacked",
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                    Swal.fire(
                        'Added!',
                        'Product added to store successfuly',
                        'success'
                      );
                    setTimeout(() => { 
                            location.reload();
                        }, 2000);
                  }  
              }
          });
      

                      
                    }
                  });
  }
    
  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox_first').click(function() {
     
      if($(this).prop('checked')) {
          status = 'chacked';
          id = $(this).parent().attr("id");
      } else {
          status = 'unchacked';
          id = $(this).parent().attr("id");
      }

      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?=base_url('purchase/active')?>",
              data: "id=" + id + "&status=" + status,
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                      toastr["success"]("Success")
                      toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "500",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                      }
                        setTimeout(() => { 
                            location.reload();
                        }, 1000);
                  } else {
                      toastr["error"]("Error")
                      toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "500",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                      }
                  }
              }
          });
      }
  }); 
  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox_second').click(function() {
        
      if($(this).prop('checked')) {
          status = 'chacked';
          id = $(this).parent().attr("id");
      } else {
          status = 'unchacked';
          id = $(this).parent().attr("id");
      }

      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?=base_url('purchase/active_second')?>",
              data: "id=" + id + "&status=" + status,
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                      toastr["success"]("Success")
                      toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "500",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                      }
                       setTimeout(() => { 
                            location.reload();
                        }, 1000);
                  } else {
                      toastr["error"]("Error")
                      toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "500",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                      }
                  }
              }
          });
      }
  });
</script>