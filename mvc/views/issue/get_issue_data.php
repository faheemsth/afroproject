<?php 

        $now = time(); // or your date as well
        $your_date = strtotime($issue->due_date);
        $datediff = $now - $your_date;

        $days =  (round($datediff / (60 * 60 * 24)))-1;
        if ($days>0) {
            $fine   =   $days*$siteinfos->book_fine;
        }else{
            $fine   =   0;
        }
        
?>
                    <input type="hidden" class="form-control" id="libraryID" name="libraryID" value="<?=$issue->lID?>"  >
                    <input type="hidden" class="form-control" id="issueID" name="issueID" value="<?=$issue->issueID?>"  >
                    <span class="clearfix"></span>
                    <?php 
                        if(form_error('include_fine')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-2 control-label">
                          Return    <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <label class="col-sm-6">
                            <input type="radio"  required onclick="display_fine_detail()"  id="include_fine" name="include_fine" value="1" > With fine </label>
                            <label class="col-sm-6">
                            <input type="radio"  required  onclick="display_fine_detail()" id="include_fine_no" name="include_fine" value="0" > Without fine </label>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('due_date'); ?>
                        </span>
                    </div>
                    <div id="fine_detail" style="display: none;">
                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_amount")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount',$fine )?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="amount_error">
                        </span>

                    </div>



                    <?php 
                        if(form_error('due_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-2 control-label">
                          Fine  <?=$this->lang->line("issue_due_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="due_date" name="due_date" value="<?=set_value('due_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('due_date'); ?>
                        </span>
                    </div>
                    </div>
                    <span class="clearfix"></span>
                    