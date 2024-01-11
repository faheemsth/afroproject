
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-member"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("hmember/index")?>"><?=$this->lang->line('panel_title')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                     <?php 
                        if(form_error('bookDate')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="bookDate" class="col-sm-2 control-label">
                             Date <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                 
                                echo form_input("bookDate", set_value("bookDate"), "id='bookDate' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('bookDate'); ?>
                        </span>
                    </div>
                    <?php 
                        if(form_error('hostelID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="hostelID" class="col-sm-2 control-label">
                            <?=$this->lang->line("hmember_hname")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array[0] = $this->lang->line("hmember_select_hostel_name");
                                foreach ($hostels as $hostel) {
                                    $array[$hostel->hostelID] = $hostel->name;
                                }
                                echo form_dropdown("hostelID", $array, set_value("hostelID"), "id='hostelID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hostelID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('categoryID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="categoryID" class="col-sm-2 control-label">
                            Category <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(0 => 'Select Category');
                                if(customCompute($categorys)) {
                                    foreach ($categorys as $key => $category) {
                                        $array[$category->categoryID] = $category->class_type;
                                    }
                                }
                                echo form_dropdown("categoryID", $array, set_value("categoryID"), "id='categoryID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('categoryID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('hostelroomID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="categoryID" class="col-sm-2 control-label">
                            Room  <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(0 => $this->lang->line("hmember_select_class_type"));
                                if(customCompute($hostelrooms)) {
                                    foreach ($hostelrooms as $key => $hr) {
                                        $array[$hr->hostelroomID] = $hr->hostelroom;
                                    }
                                }
                                echo form_dropdown("hostelroomID", $array, set_value("hostelroomID"), "id='hostelroomID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hostelroomID'); ?>
                        </span>
                    </div>

                    <div class="roomdiv">

                        <div class="form-group <?=form_error('roomcapcity') ? 'has-error' : '' ?>" >
                        <label for="roomcapcity" class="col-sm-2 control-label">
                            Room Capcity<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="roomcapcity" name="roomcapcity" readonly value="<?=set_value('roomcapcity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('roomcapcity'); ?>
                        </span>
                    </div>


                <div class="form-group <?=form_error('capcityoccopied') ? 'has-error' : '' ?>" >
                        <label for="capcityoccopied" class="col-sm-2 control-label">
                            Capcity Occopied<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="capcityoccopied" name="capcityoccopied" readonly value="<?=set_value('capcityoccopied')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('capcityoccopied'); ?>
                        </span>
                    </div>


                        
                        <div class="col-sm-4">
                            <input type="text" name="other_charges[]" id="hostelfee" value="Monthly Fee" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="other_charges_amount[]" id="hostelfee_amount" value="" class="form-control totalsum">
                        </div>
                        <span class="clearfix"></span>
                        <hr>
                        <span class="clearfix"></span>
                        <div class="col-sm-4">
                            <input type="text" name="other_charges[]" id="messfee" value="Mess Charges" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="other_charges_amount[]" id="messfee_amount" value="" class="form-control totalsum">
                        </div>
                        <span class="clearfix"></span>
                        <hr>
                        <span class="clearfix"></span>
                        <div class="col-sm-4">
                            <input type="text"   name="other_charges[]" value="Other Charges" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="other_charges_amount[]" id="other_charges_amount" value="" class="form-control totalsum">
                        </div>
                        <span class="clearfix"></span>
                        <hr>
                        <span class="clearfix"></span>
                        <div id="appendme"></div>
                        <button class="appendbtn btn btn-success"  type="button"> Add</button>
                        <div class="col-sm-4">
                            <input type="text" name="hostel_discount" id="hostel_discount" value="Discount" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="hostel_discount_amount" id="hostel_discount_amount" value="0" class="form-control">
                        </div>
                        <span class="clearfix"></span>
                        <hr>
                        <span class="clearfix"></span>
                        <div class="col-sm-4">
                            <input type="text" name="total" id="hostel_total" value="Total" class="form-control" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="total_amount" id="total_amount" value="" class="form-control">
                        </div>
                        <span class="clearfix"></span>
                        <hr>
                        <span class="clearfix"></span>
                    </div>

                    
                    <?php
                        if(form_error('photo'))
                            echo "<div class='form-group photo_upload_sec has-error' >";
                        else
                            echo "<div class='form-group photo_upload_sec' >";
                    ?>
                        <label for="photo" class="col-sm-2 control-label">
                            Decision
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                       Remove
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-upload"></span>
                                        <span class="image-preview-input-title">
                                        Decision Upload</span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('photo'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_hmember")?>" >
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
$('.select2').select2();
$('#bookDate').datepicker();
$('#hostelID').click(function(event) {
    var hostelID = $(this).val();
    if(hostelID == 0 || hostelID == "" || hostelID == null) {
        $('#categoryID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('hmember/categorycall')?>",
            data: "id=" + hostelID,
            dataType: "html",
            success: function(data) {
               $('#categoryID').html(data)
            }
        });
        $.ajax({
            type: 'POST',
            url: "<?=base_url('hmember/roomcall')?>",
            data: "id=" + hostelID,
            dataType: "html",
            success: function(data) {
               $('#hostelroomID').html(data)
            }
        });
    }
});
$('#hostelroomID').click(function(event) {
    var hostelroomID = $(this).val();
    if(hostelroomID == 0 || hostelroomID == "" || hostelroomID == null) {
        $('#categoryID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('hmember/roomsinglecall')?>",
            data: "id=" + hostelroomID,
            dataType: "html",
            success: function(data) {
                var res = JSON.parse(data);
                $('#security_amount').val(res.security);
                $('#hostelfee_amount').val(res.monthlyfee);
                $('#roomcapcity').val(res.roomcapcity);
                $('#capcityoccopied').val(res.capcityoccopied);
                $('#hostel_discount_amount').trigger('change'); 
            }
        }); 
    }
});
$(".appendbtn").click(function () {
   $("#appendme").append('<div class="addmian"><div class="col-sm-4"> <input type="text" name="other_charges[]" id="other_charges" value="Other Charges" class="form-control">                        </div>                        <div class="col-sm-4">                            <input type="text" name="other_charges_amount[]" id="other_charges_amount" value="" class="form-control totalsum">                        </div>    <button type="button" class="btn btn-danger col-sm-2" onClick="removeDiv(this)">Remove</button>                      <span class="clearfix"></span>                        <hr>                        <span class="clearfix"></span></div>');
});
function removeDiv(elem){
    $(elem).parent('div.addmian').remove();
    $('#hostel_discount_amount').trigger('change');
}
$(document).on("change", ".totalsum", function() {
    var sum = 0;
    $(".totalsum").each(function(){
        sum += +$(this).val();
    });

    var discount =  $("#hostel_discount_amount").val();
    $("#total_amount").val(sum-discount);
});
$(document).on("change", "#hostel_discount_amount", function() {
    var sum = 0;
    $(".totalsum").each(function(){
        sum += +$(this).val();
    });

    var discount =  $("#hostel_discount_amount").val();
    $("#total_amount").val(sum-discount);
});
</script>
