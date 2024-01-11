<div class="box">
   <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineadmission"></i> <?=$this->lang->line('bank_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_onlineadmission')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('bank_accounts') ? ' has-error' : ''  ?>">
                        <label for="bank_accounts" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_bank")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php 
                                $bank_accountsArray['0'] = $this->lang->line('onlineadmission_select_bank');
                                if(customCompute($bank_accounts)) {
                                    foreach ($bank_accounts as $bank_account) {
                                        $bank_accountsArray[$bank_account->id] = $bank_account->bank_name;
                                    }
                                }
                                echo form_dropdown("bank_accounts", $bank_accountsArray, set_value("bank_accounts", ''), "id='bank_accounts' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('bank_accounts'); ?>
                        </span>

                    </div>
                    <div class="form-group">
                        <label for="fee" class="col-sm-2 control-label">Admission Fee</label>
                        <div class="col-sm-6">
                            <?php
                                if(customCompute($admission_fee)){
                                   echo 'PKR '.$admission_fee['admission_fee'];
                                   ?>
                                   <input type="hidden" name="admission_fee" value="<?php echo $admission_fee['admission_fee']; ?>">
                                   <?php
                                }
                            ?>
                            <input type="hidden" name="admission_id" value="<?php echo $this->uri->segment(3); ?>">
                        </div>
                    </div>


                   

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_bank")?>" >
                        </div>
                    </div>
                </form>
            </div><!-- col-sm-8 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $( ".select2" ).select2();
    $('#dob').datepicker({ startView: 2 });

    $('#username').keyup(function() {
        $(this).val($(this).val().replace(/\s/g, ''));
    });

    $('#classesID').change(function(event) {
        var classesID = $(this).val();
        if(classesID === '0') {
            $('#sectionID').val(0);
        } else {
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?=base_url('student/sectioncall')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/optionalsubjectcall')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data2) {
                    $('#optionalSubjectID').html(data2);
                }
            });
        }
    });

    $(document).on('click', '#close-preview', function(){ 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
               $('.content').css('padding-bottom', '130px');
            }, 
             function () {
               $('.image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    
    });

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
            $(".image-preview-input-title").text("<?=$this->lang->line('onlineadmission_file_browse')?>");
        }); 
        // Create the preview image
        $(".image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('onlineadmission_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '130px');
            }        
            reader.readAsDataURL(file);
        });  
    });
</script>
