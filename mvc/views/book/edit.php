

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("book/index")?>"><?=$this->lang->line('menu_books')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">

                <form class="form-horizontal" role="form" method="post">
 <?php 
                        if(form_error('accession_number')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="accession_number" class="col-sm-2 control-label">
                            <?=$this->lang->line("accession_number")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="accession_number" name="accession_number" value="<?=set_value('accession_number', $book->accession_number)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('accession_number'); ?>
                        </span>
                    </div>
 <?php 
                        if(form_error('ddc_number')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="ddc_number" class="col-sm-2 control-label">
                            <?=$this->lang->line("ddc_number")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="ddc_number" name="ddc_number" value="<?=set_value('ddc_number',$book->ddc_number)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('ddc_number'); ?>
                        </span>
                    </div>
                    <?php 
                        if(form_error('subject_code')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?=set_value('subject_code',$book->subject_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_code'); ?>
                        </span>
                    </div>
                    <?php 
                        if(form_error('author')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="author" class="col-sm-2 control-label">
                        <?=$this->lang->line("book_author")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="author" name="author" value="<?=set_value('author',$book->author)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('author'); ?>
                        </span>
                    </div>
                   <?php 
                        if(form_error('book')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book" name="book" value="<?=set_value('book',$book->book)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book'); ?>
                        </span>
                    </div>
                      <?php 
                       if(form_error('book_category')) 
                       echo "<div class='form-group has-error' >";
                     else     
                      echo "<div class='form-group' >";
                   ?>
                    <label for="book_category" class="col-sm-2 control-label">
                   <?=$this->lang->line("book_category")?> <span class="text-red">*</span>
                       </label>
                     <div class="col-sm-6">
            <input type="text" class="form-control" id="book_category" name="book_category" value="<?=set_value('book_category',$book->book_category)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_categor'); ?>
                        </span>
                    </div>
                   <?php 
                        if(form_error('book_publisher')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_publisher" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_publisher")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_publisher" name="book_publisher" value="<?=set_value('book_publisher',$book->book_publisher)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_publisher'); ?>
                        </span>
                    </div> 
                   <?php 
                        if(form_error('book_year')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_year" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_year")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_year" name="book_year" value="<?=set_value('book_year',$book->book_year)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_year'); ?>
                        </span>
                    </div> 
                   <?php 
                        if(form_error('book_pages')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_pages" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_pages")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_pages" name="book_pages" value="<?=set_value('book_pages',$book->book_pages)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_pages'); ?>
                        </span>
                    </div> 
             <?php 
                        if(form_error('book_binding')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_binding" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_binding")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_binding" name="book_binding" value="<?=set_value('book_binding',$book->book_binding)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_binding'); ?>
                        </span>
                    </div> 
                                 <?php 
                        if(form_error('book_source')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_source" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_source")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_source" name="book_source" value="<?=set_value('book_source',$book->book_source)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_source'); ?>
                        </span>
                    </div> 
         <?php 
                        if(form_error('price')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="price" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_price")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="price" name="price" value="<?=set_value('price',$book->price)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('price'); ?>
                        </span>
                    </div>
                 <?php 
                        if(form_error('rack')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="rack" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_rack_no")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="rack" name="rack" value="<?=set_value('rack',$book->rack)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('rack'); ?>
                        </span>
                    </div>
            <?php 
                        if(form_error('book_volume')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_volume" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_volume")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_volume" name="book_volume" value="<?=set_value('book_volume',$book->book_volume)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_volume'); ?>
                        </span>
                    </div>
            <?php 
                        if(form_error('book_edtion')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_edtion" class="col-sm-2 control-label">
                            Book Edition
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_edtion" name="book_edtion" value="<?=set_value('book_edtion',$book->book_edtion)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_edtion'); ?>
                        </span>
                    </div>
            <?php 
                        if(form_error('book_remarks')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book_remarks" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_remarks")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="book_remarks" name="book_remarks" value="<?=set_value('book_remarks',$book->book_remarks)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book_remarks'); ?>
                        </span>
                    </div>
                    <!-- New Code -->

                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            Degree <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array['0'] = 'Select Degree';
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array,  set_value("classesID",$book->classesID), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <!-- END  -->
                    <?php 
                        if(form_error('quantity')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="quantity" class="col-sm-2 control-label">
                            <?=$this->lang->line("book_quantity")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?=set_value('quantity',$book->quantity)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('quantity'); ?>
                        </span>
                    </div>
<!-- New Code -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_book")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
  $( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
  $(document).ready(function() {
    // $('#cover_photo').imageuploadify();
    // $('#file').imageuploadify();
     // $('input[type="file"]').imageuploadify();
  })
</script>