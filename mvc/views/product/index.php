<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-product"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('product_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('product/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>


                 <!-- FILTERS START -->
                 <form type="get" action="">
                    <div class="col-sm-12">

                        <div class="form-group col-sm-3 <?= form_error('productcategoryID') ? 'has-error' : '' ?>">
                            <label>Category</label>
                            <div>
                                <?php
                                $productcategoryArray[0] = $this->lang->line("product_select_category");
                                foreach ($productcategorys as $key => $productcategory) {
                                    $productcategoryArray[$key] = $productcategory;
                                }
                                echo form_dropdown("productcategoryID", $productcategoryArray, set_value("productcategoryID"), "id='productcategoryID' class='form-control select2'");
                                ?>
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('productcategoryID'); ?>
                            </span>
                        </div>


                        <div class="form-group col-sm-3" id="codeDiv">
                            <label>Code</label>
                            <input type="text" id="productCode" name="productCode" value="<?php echo set_value('productCode', $productCode); ?>" class="form-control" />
                        </div>

                        <div class="form-group col-sm-3" id="titleDiv">
                            <label>Title</label>
                            <input type="text" id="productTitle" name="productTitle" value="<?php echo set_value('productTitle', $productTitle); ?>" class="form-control" />
                        </div>


                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                            <a href="<?php echo base_url('product/'); ?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                        </div>
                    </div>
                </form>
                <!-- FILTERS END -->


                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_product')?></th>
                                <th class="col-sm-2">Code/Ref.</th>
                                <th class="col-sm-2"><?=$this->lang->line('product_category')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_buyingprice')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_sellingprice')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_desc')?></th>
                                <?php if(permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                    <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($products)) {$i = 1; foreach($products as $product) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_product')?>">
                                        <?=$product->productname;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_product')?>">
                                        <?=$product->code_reference;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_category')?>">
                                        <?=isset($productcategorys[$product->productcategoryID]) ? $productcategorys[$product->productcategoryID] : ''?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_buyingprice')?>">
                                        <?=$product->productbuyingprice;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_sellingprice')?>">
                                        <?=$product->productsellingprice;?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_desc')?>">
                                        <?=$product->productdesc?>
                                    </td>
                                    
                                    <?php if(permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_flat_pdfPreviewReport('product_edit','/product/ledger/'.$product->productID, 'Ledger') ?>

                                            <?php if(permissionChecker('quotation_add')) ?>
                                            <a href="<?='/product/quotation/'.$product->productID?>" class="fa fa-file-archive-o btn btn-default" style="margin: 3px 3px;"> Quotation</a>
                                            
                                            <?php echo btn_edit('product/edit/'.$product->productID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('product/delete/'.$product->productID, $this->lang->line('delete')) ?>
                                           
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