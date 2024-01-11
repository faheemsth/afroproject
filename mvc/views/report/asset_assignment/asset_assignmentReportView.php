<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-asset_assignmentreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset_assignmentreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="asset_assignmentcustomertypeDiv">
                    <label><?=$this->lang->line("asset_assignmentreport_role")?></label>
                    <?php
                    $usertypeArray = array(0 => $this->lang->line("asset_assignmentreport_please_select"));
                    if(customCompute($usertypes)) {
                        foreach ($usertypes as $usertype) {
                            $usertypeArray[$usertype->usertypeID] = $usertype->usertype;
                        }
                    }
                    echo form_dropdown("asset_assignmentcustomertypeID", $usertypeArray, set_value("asset_assignmentcustomertypeID"), "id='asset_assignmentcustomertypeID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4 hide" id="asset_assignmentclassesDiv">
                    <label for="asset_assignmentclassesID"><?=$this->lang->line("asset_assignmentreport_classes")?></label>
                    <?php
                    $classesArray = array(0 => $this->lang->line("asset_assignmentreport_please_select"));
                    if(customCompute($classes)) {
                        foreach ($classes as $classa) {
                            $classesArray[$classa->classesID] = $classa->classes;
                        }
                    }
                    echo form_dropdown("asset_assignmentclassesID", $classesArray, set_value("asset_assignmentclassesID"), "id='asset_assignmentclassesID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="asset_assignmentcustomerDiv">
                    <label for="asset_assignmentcustomerID"><?=$this->lang->line("asset_assignmentreport_user")?></label>
                    <?php
                    $asset_assignmentcustomerArray = array(0 => $this->lang->line("asset_assignmentreport_please_select"));
                    echo form_dropdown("asset_assignmentcustomerID", $asset_assignmentcustomerArray, set_value("asset_assignmentcustomerID"), "id='asset_assignmentcustomerID' class='form-control select2'");
                    ?>
                </div>
 
 

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label><?=$this->lang->line("asset_assignmentreport_fromdate")?></label>
                   <input class="form-control" type="text" name="fromdate" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("asset_assignmentreport_todate")?></label>
                    <input class="form-control" type="text" name="todate" id="todate">
                </div>

                <div class="col-sm-4">
                    <button id="get_asset_assignmentreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("asset_assignmentreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_asset_assignmentreport"></div>

<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('asset_assignmentreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();

    $('#fromdate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $('#todate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $(document).bind('click', '#fromdate, #todate', function() {
        $('#fromdate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

        $('#todate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });
    });

    $('#asset_assignmentcustomertypeID, #asset_assignmentclassesID').change(function(event) {
        var asset_assignmentcustomertypeID = $('#asset_assignmentcustomertypeID').val();
        var asset_assignmentclassesID = $('#asset_assignmentclassesID').val();

        if(asset_assignmentcustomertypeID === '3') {
            $('#asset_assignmentclassesDiv').removeClass('hide');
        } else {
            $('#asset_assignmentclassesDiv').addClass('hide');
        }

        if(asset_assignmentcustomertypeID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('asset_assignmentreport_please_select')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('asset_assignmentreport/getuser')?>",
                data: {'asset_assignmentcustomertypeID' : asset_assignmentcustomertypeID, 'asset_assignmentclassesID' : asset_assignmentclassesID},
                dataType: "html",
                success: function(data) {
                    $('#asset_assignmentcustomerID').html(data);
                }
            });
        }
    });


    $('#get_asset_assignmentreport').click(function() {
        var asset_assignmentcustomertypeID = $('#asset_assignmentcustomertypeID').val();
        var asset_assignmentclassesID = $('#asset_assignmentclassesID').val();
        var asset_assignmentcustomerID = $('#asset_assignmentcustomerID').val();
        var fromdate = $('#fromdate').val();
        var todate   = $('#todate').val();
        var error = 0;

        var field = {
            'asset_assignmentcustomertypeID': asset_assignmentcustomertypeID,
            'asset_assignmentclassesID': asset_assignmentclassesID,
            'asset_assignmentcustomerID': asset_assignmentcustomerID,
            'fromdate': fromdate,
            'todate': todate
        };

        if(fromdate != '' && todate == '') {
            error++;
            $('#todateDiv').addClass('has-error');
        } else{
            $('#todateDiv').removeClass('has-error');
        }

        if(fromdate == '' && todate != '') {
            error++;
            $('#fromdateDiv').addClass('has-error');
        } else {
            $('#fromdateDiv').removeClass('has-error');
        }

        if(fromdate != '' && todate != '') {
            var fromdate = fromdate.split('-');
            var todate = todate.split('-');
            var newfromdate = new Date(fromdate[2], fromdate[1]-1, fromdate[0]);
            var newtodate   = new Date(todate[2], todate[1]-1, todate[0]);

            if(newfromdate.getTime() > newtodate.getTime()) {
                error++;
                $('#todateDiv').addClass('has-error');
            } else {
                $('#todateDiv').removeClass('has-error');
            }
        }

        if(error == 0 ) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('asset_assignmentreport/getasset_assignmentReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_asset_assignmentreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }

</script>
