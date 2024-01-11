
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("resultmanagement/index")?>"><?=$this->lang->line('menu_resultmanagement')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_resultmanagement')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post"  enctype="multipart/form-data">
                    <div class="form-group file_upload_sec <?=form_error('file') ? 'has-error' : '' ?>" >
                        <label for="title" class="col-sm-2 control-label">
                            CSV File <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        Clear
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        Select File</span>
                                        <input type="file"  accept="csv"  name="file"/>
                                    </div>
                                </span>
                            </div>
                             <input type="hidden" value="test"   id="inputname" name="inputname"/>
                        </div>
                         
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('file'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_resultmanagement")?>" >
                        </div>
                    </div>
                </form>
                <?php if (isset($msg)): ?>
                    <div class="callout callout-danger">
                      <h4>These data not inserted</h4>
                      <p><?=$msg; ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="callout callout-danger">
                        <p><?=$this->session->flashdata('error'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


$(document).on('click', '.decision_upload_sec #close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.decision_upload_sec .image-preview').hover(
        function () {
           $('.decision_upload_sec .image-preview').popover('show');
           $('.content').css('padding-bottom', '100px');
        },
         function () {
           $('.decision_upload_sec .image-preview').popover('hide');
           $('.decision_upload_sec .content').css('padding-bottom', '20px');
        }
    );
});


$(document).on('click', '.file_upload_sec #close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.file_upload_sec .image-preview').hover(
        function () {
           $('.file_upload_sec .image-preview').popover('show');
           $('.content').css('padding-bottom', '100px');
        },
         function () {
           $('.file_upload_sec .image-preview').popover('hide');
           $('.file_upload_sec .content').css('padding-bottom', '20px');
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
    $('.decision_upload_secd .image-previewd').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
        content: "There's no image",
        placement:'bottom'
    });
    // Clear event
    $('.decision_upload_sec .image-preview-clear').click(function(){
        $('.decision_upload_sec .image-preview').attr("data-content","").popover('hide');
        $('.decision_upload_sec .image-preview-filename').val("");
        $('.decision_upload_sec .image-preview-clear').hide();
        $('.decision_upload_sec .image-preview-input input:file').val("");
        $(".decision_upload_sec .image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
    });
    // Create the preview image
    $(".decision_upload_sec .image-preview-input input:file").change(function (){
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
            $(".decision_upload_sec .image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
            $(".decision_upload_sec .image-preview-clear").show();
            $(".decision_upload_sec .image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(".decision_upload_sec .image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $('.content').css('padding-bottom', '100px');
        }
        reader.readAsDataURL(file);
    });
    
    // file Upload
    
    
    // Set the popover default content
    $('.file_upload_sec .image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
        content: "There's no image",
        placement:'bottom'
    });
    // Clear event
    $('.file_upload_sec .image-preview-clear').click(function(){
        $('.file_upload_sec .image-preview').attr("data-content","").popover('hide');
        $('.file_upload_sec .image-preview-filename').val("");
        $('.file_upload_sec .image-preview-clear').hide();
        $('.file_upload_sec .image-preview-input input:file').val("");
        $(".file_upload_sec .image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
    });
    // Create the preview image
    $(".file_upload_sec .image-preview-input input:file").change(function (){
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
            $(".file_upload_sec .image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
            $(".file_upload_sec .image-preview-clear").show();
            $(".file_upload_sec .image-preview-filename").val(file.name);
            $("#inputname").val(file.name);
          //  img.attr('src', e.target.result);
            //$(".file_upload_sec .image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            //$('.content').css('padding-bottom', '100px');
        }
        reader.readAsDataURL(file);
    });
});
</script>
