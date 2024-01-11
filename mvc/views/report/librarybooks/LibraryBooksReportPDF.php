<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <h3><?=$this->lang->line('librarybooksreport_report_for')?> - <?=$this->lang->line('librarybooksreport_librarybooks');?></h3>
     <div class="col-sm-12">
        <h5 class="pull-left">
            <?php if($status == 1) {
                echo $this->lang->line('librarybooksreport_status')." : ";
                echo $this->lang->line('librarybooksreport_available');
            } elseif($status == 2) {
                echo $this->lang->line('librarybooksreport_status')." : ";
                echo $this->lang->line('librarybooksreport_unavailable');
            } elseif($bookname != '0') {
                echo $this->lang->line('librarybooksreport_bookname')." : ".$bookfullname;
            } elseif ($subjectcode != '0') {
                echo $this->lang->line('librarybooksreport_subjectcode')." : ".$subjectcode;
            } elseif($rackNo != '0') {
                echo $this->lang->line('librarybooksreport_rackNo')." : ".$rackNo;
            } ?>
        </h5>
    </div>
    <div class="col-sm-12">
        <?php if(customCompute($books)) { ?>
        <div id="hide-table">
            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_bookname')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_author')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_subjectcode')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_rackNo')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_status')?></th>
                                    <th>Quantity</th>
                                    <th>Issued</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=0; 
                                    foreach($books as $book) { $i++?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_bookname')?>"><?=$book['bookname']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_author')?>"><?=$book['author']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_subjectcode')?>"><?=$book['subjectcode']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_rackNo')?>"><?=$book['rackNo']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_status')?>"><?=$book['status']?></td>
                                            <td data-title="Quantity"><?=$book['quantity']?></td>
                                            <td data-title="Issued"><?=$book['due_quantity']?></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        </div>
        <?php } else { ?>
            <br/>
            <div class="notfound">
                <?=$this->lang->line('librarybooksreport_data_not_found')?>
            </div>
        <?php } ?>
    </div>
    <div class="text-center footerAll">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
</body>
</html>

