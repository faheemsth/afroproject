

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">



                <?php
                    if(permissionChecker('book_add')) {
                ?>
                    <div class="col-sm-2">
                        <h5 class="page-header">
                            <a href="<?php echo base_url('book/add') ?>">
                                <i class="fa fa-plus"></i> 
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    </div>
                    
                <?php } ?>


                <form type="get" action="">
                <div class="col-sm-12">
                
                <div class="form-group col-sm-3" id="classesDiv">
                    <label>Degree</label>
                    <?php
                                    $array['0'] = 'Select Degree';
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID",$classesID), "id='classesID' class='form-control select2'");
                                ?>
                </div>
 
  

                 

                <div class="form-group col-sm-3"  >
                    <label>Book </label>
                    <input type="text" id="book" name="book" value="<?php echo set_value('book',$book);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Accession Number </label>
                    <input type="text" id="accession_number" name="accession_number" value="<?php echo set_value('accession_number',$accession_number);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Subject Code </label>
                    <input type="text" id="subject_code" name="subject_code" value="<?php echo set_value('subject_code',$subject_code);?>" class="form-control"/>
                </div>

                <div class="form-group col-sm-3"  >
                    <label>Publisher</label>
                    <input type="text" id="book_publisher" name="book_publisher" value="<?php echo set_value('book_publisher',$book_publisher);?>" class="form-control"/>
                </div> 

                <div class="form-group col-sm-3"  >
                    <label>Author</label>
                    <input type="text" id="author" name="author" value="<?php echo set_value('author',$author);?>" class="form-control"/>
                </div> 

                

                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success" style="margin-top:23px;">Search</button>
                    <a href="<?php echo base_url('book/');?>" class="btn btn-danger" style="margin-top:23px;">Reset</a>
                </div>

            
                </div>
            </form> 
              
                <div class="clearfix"></div>


                <div id="hide-table">
                    <!--id="example1"-->
                    <table  class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('accession_number')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('book_subject_code')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('book_name')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_author')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_category')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_quantity')?></th>
                                <th class="col-sm-1"><?='Class'?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_status')?></th>
                                <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($books)) {$i = $sr; foreach($books as $book) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('accession_number')?>">
                                        <?php echo $book->accession_number; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('book_subject_code')?>">
                                        <?php echo $book->subject_code; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('book_name')?>">
                                        <?php echo $book->book; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('book_author')?>">
                                        <?php echo $book->author; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('book_category')?>">
                                        <?php echo $book->book_category; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('book_quantity')?>">
                                        <?php echo $book->quantity - $book->due_quantity; ?>
                                    </td>

                                     <td data-title="<?=$this->lang->line('book_quantity')?>">
                                        <?php echo $book->classes; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('book_status')?>">
                                        <?php 
                                            if($book->quantity == $book->due_quantity) {
                                                echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('book_unavailable') . "</button>";
                                            } else {
                                                echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('book_available') . "</button>";
                                            }
                                        ?>
                                    </td>

                                    <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_edit('book/edit/'.$book->bookID, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('book/delete/'.$book->bookID, $this->lang->line('delete')) ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                     
                      <?php 
                        if($this->session->userdata("usertypeID") != 3){
                            echo $this->pagination->create_links(); 
                        }
                        
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
  $( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
  $(document).ready(function() {
     $('#classesID').change(function(){
        var year = $(this).val();
        console.log(year);
        // if (year != '0') {
            $("#form_year").submit();
        // } 
    });
    // $('#cover_photo').imageuploadify();
    // $('#file').imageuploadify();
     // $('input[type="file"]').imageuploadify();
  })

</script>