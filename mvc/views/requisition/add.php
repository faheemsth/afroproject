<div class="row">
    <div class="col-sm-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-requisition"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data" id="requisitionDataForm"> 
                   
                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='usertypeIDDiv form-group has-error' >";
                        else
                            echo "<div class='usertypeIDDiv form-group' >";
                    ?>
                        <label for="usertypeID" class="control-label">
                            <?='Role'?>
                        </label>
                        <div >
                            <?php
                                $userArray['0'] = $this->lang->line('asset_assignment_select_usertype');
                                if(customCompute($usertypes)) {
                                    foreach ($usertypes as $key => $usertype) {
                                        $userArray[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("usertypeID", $userArray, set_value("usertypeID"), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    

                    <?php
                        if(form_error('userID'))
                            echo "<div class='userIDDiv form-group has-error' >";
                        else
                            echo "<div class='userIDDiv form-group' >";
                    ?>
                        <label for="userID" class="control-label">
                            <?='Check Out To'?>
                        </label>
                        <div class="">
                            <?php
                                $userArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_user')
                                );  

                                if(customCompute($checkOutToUesrs)) {
                                    foreach ($checkOutToUesrs as $checkOutToUesrKey => $checkOutToUesr) {
                                        $userArray[$checkOutToUesrKey] = $checkOutToUesr;
                                    }
                                }

                                echo form_dropdown("userID", $userArray, set_value("userID"), "id='userID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('userID'); ?>
                        </span>
                    </div>


                    <div class="requisitiondateDiv form-group <?=form_error('requisitiondate') ? 'has-error' : '' ?>" >
                        <label for="requisitiondate">
                            <?=$this->lang->line("requisition_date")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="requisitiondate" name="requisitiondate" value="<?=set_value('requisitiondate')?>" >
                        <span class="text-red">
                            <?php echo form_error('requisitiondate'); ?>
                        </span>
                    </div>  

                    <div class="form-group <?=form_error('requisitionfile') ? 'has-error' : '' ?>" >
                        <label for="requisitionfile">
                            <?=$this->lang->line("requisition_file")?>
                        </label>
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('requisition_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('requisition_browse')?></span>
                                    <input type="file" name="requisitionfile"/>
                                </div>
                            </span>
                        </div>
                        <span class="text-red">
                            <?php echo form_error('requisition_file'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('requisitiondescription') ? 'has-error' : '' ?>" >
                        <label for="requisitiondescription">
                            <?=$this->lang->line("requisition_description")?>
                        </label>
                        <textarea class="form-control" style="resize:none;" id="requisitiondescription" name="requisitiondescription"><?=set_value('requisitiondescription')?></textarea>
                        <span class="text-red">
                            <?php echo form_error('requisitiondescription'); ?>
                        </span>
                    </div>

                    <input id="addPurchaseButton" type="button" class="btn btn-success" value="<?=$this->lang->line("add_requisition")?>" >
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-requisitionitem"></i> <?=$this->lang->line('requisition_purchaseitem')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("requisition/index")?>"><?=$this->lang->line('menu_requisition')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_requisition')?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="" role="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('productcategoryID') ? 'has-error' : '' ?>" >
                                <label for="productcategoryID" class="control-label">
                                    <?=$this->lang->line("requisition_category")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productcategoryArray = array(0 => $this->lang->line("requisition_select_category"));
                                    if(customCompute($productcategorys)) {
                                        foreach ($productcategorys as $productcategory) {
                                            $productcategoryArray[$productcategory->productcategoryID] = $productcategory->productcategoryname;
                                        }
                                    }
                                    echo form_dropdown("productcategoryID", $productcategoryArray, set_value("productcategoryID"), "id='productcategoryID' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('productcategoryID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('productID') ? 'has-error' : '' ?>" >
                                <label for="productID" class="control-label">
                                    <?=$this->lang->line("requisition_product")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productArray = array(0 => $this->lang->line("requisition_select_product"));
                                    echo form_dropdown("productID", $productArray, set_value("productID"), "id='productID' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('productID'); ?>
                                </span>
                            </div>  
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered product-style" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-4"><?=$this->lang->line('requisition_product')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('requisition_quantity')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                        </tbody>

                        <tfoot id="productListFooter">
                            <tr>
                                <td colspan="2" style="font-weight: bold"><?=$this->lang->line('requisition_total')?></td>
                                <td id="totalQuantity" style="font-weight: bold">0.00</td> 
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('requisition_browse')?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('requisition_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function floatChecker(value) {
        var val = value;
        if(isNumeric(val)) {
            return true;
        } else {
            return false;
        }
    }

    function dotAndNumber(data) {
        var retArray = [];
        var fltFlag = true;
        if(data.length > 0) {
            for(var i = 0; i <= (data.length-1); i++) {
                if(i == 0 && data.charAt(i) == '.') {
                    fltFlag = false;
                    retArray.push(true);
                } else {
                    if(data.charAt(i) == '.' && fltFlag == true) {
                        retArray.push(true);
                        fltFlag = false;
                    } else {
                        if(isNumeric(data.charAt(i))) {
                            retArray.push(true);
                        } else {
                            retArray.push(false);
                        }
                    }

                }
            }
        }

        if(jQuery.inArray(false, retArray) ==  -1) {
            return true;
        }
        return false;
    }

    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    }   

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }

    function productItemDesign(productID, productText) {
        var productobj = <?=$productobj?>;
        var randID = getRandomInt();
        if($('#productList tr:last').text() == '') {
            var lastTdNumber = 0;
        } else {
            var lastTdNumber = $("#productList tr:last td:eq(0)").text();
        }

        if(typeof(productobj) == 'object') {
            if(typeof(productobj[productID]) == 'object') {
                var productobjinfo = productobj[productID];
            } else {
                productobjinfo = {'productID' : productID, 'productbuyingprice' : '0', 'productsellingprice' : '0'};            
            }
        }
        console.log(productobjinfo);
        console.log(productobjinfo.productbuyingprice);
        lastTdNumber = parseInt(lastTdNumber);
        lastTdNumber++;

        var text = '<tr id="tr_'+randID+'" purchaseproductid="'+productID+'">';
            text += '<td>';
            text += lastTdNumber;
            text += '</td>';

            text += '<td>';
            text += productText;
            text += '</td>';
            text += '<td>';
            text += ('<input type="text" class="form-control change-productquantity" id="productquantity_'+randID+'" data-productquantity-id="'+randID+'">' );
            text += '</td>';
            text += '<td>';
            text += ('<input type="hidden" class="form-control change-productbuyingprice" id="productbuyingprice_'+randID+'" data-productbuyingprice-id="'+randID+'" value="'+productobjinfo.product_average_price+'">' );
            text += ('<a style="margin-top:3px" href="#" class="btn btn-danger btn-sm deleteBtn" id="productaction_'+randID+'" data-productaction-id="'+randID+'"><i class="fa fa-trash-o"></i></a>');
            text += '</td>';
            text += '</tr>';

            return text; 
    }

    function currencyConvert(data) {
        return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    function lenCheckerWithoutParseFloat(data, len) {
        var retdata = 0;
        var lencount = 0;
        if(data.length > len) {
            lencount = (data.length - len);
            data = data.toString();
            data = data.slice(0, -lencount);
            retdata = data;
        } else {
            retdata = data;
        }

        return retdata;
    }

    function toFixedVal(x) {
      if (Math.abs(x) < 1.0) {
        var e = parseFloat(x.toString().split('e-')[1]);
        if (e) {
            x *= Math.pow(10,e-1);
            x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
        }
      } else {
        var e = parseFloat(x.toString().split('+')[1]);
        if (e > 20) {
            e -= 20;
            x /= Math.pow(10,e);
            x += (new Array(e+1)).join('0');
        }
      }
      return x;
    }

    function lenChecker(data, len) {
        var retdata = 0;
        var lencount = 0;
        data = toFixedVal(data);
        if(data.length > len) {
            lencount = (data.length - len);
            data = data.toString();
            data = data.slice(0, -lencount);
            retdata = parseFloat(data);
        } else {
            retdata = parseFloat(data);
        }

        return toFixedVal(retdata);
    }

    $(document).on('keyup', '#requisitionreferenceno', function() {
        var requisitionreferenceno =  $(this).val();
        if(requisitionreferenceno.length > 99) {
            requisitionreferenceno = lenCheckerWithoutParseFloat(requisitionreferenceno, 99);
            $(this).val(requisitionreferenceno);                    
        }
    });

    function totalInfo() {
        debugger;
        var i = 1;
        var totalQuantity = 0;
        var totalSubtotal = 0;
        $('#productList tr').each(function(index, value) {
            if($(this).children().eq(2).children().val() != '' && $(this).children().eq(2).children().val() != null) {
                var quantity = parseFloat($(this).children().eq(2).children().val()); 
                totalQuantity += quantity; 
            } 
        });

        $('#totalQuantity').text(currencyConvert(totalQuantity)); 
    }

    $('.select2').select2();
    $('#requisitiondate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $('#productcategoryID').change(function(event) {
        var productcategoryID = $(this).val();
        if(productcategoryID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('requisition_select_product')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('requisition/getrequisition')?>",
                data: "productcategoryID=" + productcategoryID,
                dataType: "html",
                success: function(data) {
                    $('#productID').html(data);
                }
            });
        }
    });

    $('#productID').change(function(e) {
        var productID   = $(this).val();
        if(productID != 0) {
            var productText = $(this).find(":selected").text();
            var appendData  = productItemDesign(productID, productText);
            $('#productList').append(appendData);
        }
    });

    $(document).on('keyup', '.change-productprice', function() {
        var productPrice =  toFixedVal($(this).val());
        var productPriceID = $(this).attr('data-productprice-id'); 
        var productQuantity = toFixedVal($('#productquantity_'+productPriceID).val());
        
        if(dotAndNumber(productPrice)) {
            if(productPrice.length > 15) {
                productPrice = lenChecker(productPrice, 15);
                $(this).val(productPrice);
            }

            if((productPrice != '' && productPrice != null) && (productQuantity != '' && productQuantity != null)) {
                if(floatChecker(productPrice)) {
                    if(productPrice.length > 15) {
                        productPrice = lenChecker(productPrice, 15);
                        $(this).val(productPrice);
                        $('#producttotal_'+productPriceID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    } else {
                        $('#producttotal_'+productPriceID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    }
                }
            } else {
                $('#producttotal_'+productPriceID).text('0.00');
                totalInfo();
            }
        } else {
            var productPrice = parseSentenceForNumber($(this).val());
            $(this).val(productPrice);
        }
    });

    $(document).on('keyup', '.change-productquantity', function() {
        var productQuantity =  toFixedVal($(this).val());
        var productQuantityID = $(this).attr('data-productquantity-id');  
        
        if(dotAndNumber(productQuantity)) {
            if(productQuantity.length > 15) {
                productQuantity = lenChecker(productQuantity, 15);
                $(this).val(productQuantity);
            }
            
            if((productQuantity != '' && productQuantity != null) ) {
                if(floatChecker(productQuantity)) {
                    if(productQuantity.length > 15) {
                        productQuantity = lenChecker(productQuantity, 15);
                        $(this).val(productQuantity);
                         
                        totalInfo();
                    } else {
                         
                        totalInfo();
                    }
                }
            } else {
                 
                totalInfo();
            }
        } else {
            var productQuantity = parseSentenceForNumber($(this).val());
            $(this).val(productQuantity);
        }
    });

    $(document).on('click', '.deleteBtn', function(e) {
        e.preventDefault();
        var productItemID = $(this).attr('data-productaction-id');
        $('#tr_'+productItemID).remove();
        
        var i = 1;
        $('#productList tr').each(function(index, value) {
            $(this).children().eq(0).text(i);
            i++;
        });
        totalInfo();
    });

    $(document).on('click', '#addPurchaseButton', function() {
        var error=0;;
        var field = {
            'usertypeID'                : $('#usertypeID').val(), 
            'userID'                    : $('#userID').val(),   
            'requisitiondate'           : $('#requisitiondate').val(), 
            'requisitiondescription'    : $('#requisitiondescription').val(), 
        };

        if (field['usertypeID'] === '0') {
            $('.usertypeIDDiv').addClass('has-error');
            error++;
        } else {
            $('.usertypeIDDiv').removeClass('has-error');
        }

        if (field['userID'] === '0') {
            $('.userIDDiv').addClass('has-error');
            error++;
        } else {
            $('.userIDDiv').removeClass('has-error');
        }

       

        if (field['requisitiondate'] == '') {
            $('.requisitiondateDiv').addClass('has-error');
            error++;
        } else {
            $('.requisitiondateDiv').removeClass('has-error');
        }

        var productitem = $('tr[id^=tr_]').map(function(){
            return { productID : $(this).attr('purchaseproductid'), unitprice: $(this).children().eq(3).children().val(), quantity : $(this).children().eq(2).children().val() };
        }).get();

        if (typeof productitem == 'undefined' || productitem.length <= 0) {
            error++;
            toastr["error"]('The product item is required.')
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

        productitem = JSON.stringify(productitem);

        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#requisitionDataForm')[0]);
            formData.append("productitem", productitem);
            formData.append("editID", 0);
            makingPostDataPreviousofAjaxCall(formData);
        }

    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('requisition/saverequisition')?>",
            data: passData,
            async: true,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                errrorLoader(response);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function errrorLoader(response) {
        if(response.status) {
            window.location = "<?=base_url("requisition/index")?>";
        } else {
            $('#addPurchaseButton').removeAttr('disabled');
            $.each(response.error, function(index, val) {
                toastr["error"](val)
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
            });
        }
    }
      $('#usertypeID').change(function() {
            var usertypeID = $(this).val();
            if(usertypeID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allusers_by_usertypeID')?>",
                    data: {'usertypeID' : usertypeID },
                    dataType: "html",
                    success: function(data) {
                        $('#userID').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                         
                            $('#userID').html(data);
                         
                    }
                });
            } else {
                //$('#classesDiv').hide();
            }
        });
</script>

