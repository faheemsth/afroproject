<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-users"></i> Add Quotation</h3>


        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("user/index") ?>"><?= $this->lang->line('menu_user') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> Quotation</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" id="quotation-form" role="form" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            Supplier <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                            $supplierArray[0] = 'Select Supplier';
                            foreach ($productsuppliers as $key => $productsupplier) {
                                $supplierArray[$key] = $productsupplier;
                            }
                            echo form_dropdown("supplierID", $supplierArray, set_value("supplierID"), "id='supplierID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label" id="supplier-error">
                        </span>
                    </div>


                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            Description <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="description" name="description" value="<?= set_value('description') ?>">
                        </div>
                        <span class="col-sm-4 control-label" id="description-error">
                        </span>
                    </div>


                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            Quantity <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= set_value('quantity') ?>">
                        </div>
                        <span class="col-sm-4 control-label" id="quantity-error">
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            Unit Price <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="unit_price" name="unit_price" value="<?= set_value('unit_price') ?>">
                        </div>
                        <span class="col-sm-4 control-label" id="unit-price-error">
                        </span>
                    </div>


                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            File <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?= $this->lang->line('user_clear') ?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                           Browse </span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif application/pdf" name="photo" id="photo" />
                                    </div>
                                </span>
                            </div>
                        </div>
                        <span class="col-sm-4 control-label" id="photo-error">
                        </span>
                    </div>



                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" id='add-quotation' value="Add Quotation">
                        </div>
                    </div>


                </form>
            </div>


            <script type="text/javascript">
                $('.select2').select2();

                $(document).ready(function() {
                    $('#add-quotation').on('click', function() {
                        
                        var error = 0;
                        supplierid = $('#supplierID').val();
                        description = $('#description').val();
                        quantity = $('#quantity').val();
                        unit_price = $('#unit_price').val();
                        photo = $('#photo').val();

                    

                        if (supplierid == 0 || supplierid == null) {
                            error++;
                            $("#supplier-error").html("");
                            $("#supplier-error").html("The supplier field is required.").css("text-align", "left").css("color", 'red');
                        }else{
                            $("#supplier-error").html("");
                        } 

                        if (description == "" || supplierid == null) {
                            error++;
                            $("#description-error").html("");
                            $("#description-error").html("The description field is required.").css("text-align", "left").css("color", 'red');
                        } else {
                            $("#description-error").html("");
                        }

                        if (quantity == 0 || quantity == null) {
                            error++;
                            $("#quantity-error").html("");
                            $("#quantity-error").html("The quantity field is required.").css("text-align", "left").css("color", 'red');
                        } else {
                            $("#quantity-error").html("");
                        }

                        if (unit_price == 0 || unit_price == null) {
                            error++;
                            $("#unit-price-error").html("");
                            $("#unit-price-error").html("The unit price field is required.").css("text-align", "left").css("color", 'red');
                        } else {
                            $("#unit-price-error").html("");
                        }

                        if (photo == "" || photo == null) {
                            error++;
                            $("#photo-error").html("");
                            $("#photo-error").html("The quotation photo is required.").css("text-align", "left").css("color", 'red');
                        } else {
                            $("#photo-error").html("");
                        }


                       if(error > 0)
                            return false;
                        else 
                            return true;
                    })
                })



                $(document).on('click', '#close-preview', function() {
                    $('.image-preview').popover('hide');
                    // Hover befor close the preview
                    $('.image-preview').hover(
                        function() {
                            $('.image-preview').popover('show');
                            $('.content').css('padding-bottom', '100px');
                        },
                        function() {
                            $('.image-preview').popover('hide');
                            $('.content').css('padding-bottom', '20px');
                        }
                    );
                });

                $(function() {
                    // Create the close button
                    var closebtn = $('<button/>', {
                        type: "button",
                        text: 'x',
                        id: 'close-preview',
                        style: 'font-size: initial;',
                    });
                    closebtn.attr("class", "close pull-right");
                    // Set the popover default content
                    $('.image-preview').popover({
                        trigger: 'manual',
                        html: true,
                        title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
                        content: "There's no image",
                        placement: 'bottom'
                    });
                    // Clear event
                    $('.image-preview-clear').click(function() {
                        $('.image-preview').attr("data-content", "").popover('hide');
                        $('.image-preview-filename').val("");
                        $('.image-preview-clear').hide();
                        $('.image-preview-input input:file').val("");
                        $(".image-preview-input-title").text("<?= $this->lang->line('user_file_browse') ?>");
                    });
                    // Create the preview image
                    $(".image-preview-input input:file").change(function() {
                        var img = $('<img/>', {
                            id: 'dynamic',
                            width: 250,
                            height: 200,
                            overflow: 'hidden'
                        });
                        var file = this.files[0];
                        var reader = new FileReader();
                        // Set preview image into the popover data-content
                        reader.onload = function(e) {
                            $(".image-preview-input-title").text("<?= $this->lang->line('user_file_browse') ?>");
                            $(".image-preview-clear").show();
                            $(".image-preview-filename").val(file.name);
                            img.attr('src', e.target.result);
                            $(".image-preview").attr("data-content", $(img)[0].outerHTML).popover("show");
                            $('.content').css('padding-bottom', '100px');
                        }
                        reader.readAsDataURL(file);
                    });
                });
            </script>