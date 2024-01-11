
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("resultmanagement/index")?>"><?=$this->lang->line('menu_resultmanagement')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_resultmanagement')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            

      <header>
        <div class="header-top text-center px-5 py-5">
          <img src="Media/gcu-logo.png" alt="" class="logo" />
          <h3><?php echo $siteinfos->sname;?></h3>
          <h4><?php echo $siteinfos->address;?></h4>
          <h5 class="text-underline">TRANSCRIPT</h5>
        </div>
        <table class="table table-borderless">
          <tr>
            <th
              colspan="4"
              class="text-center text-undesrline"
              style="font-size: 18px"
            >
              <?php echo  $student->srclasses;?>,   <?php echo  $student->session_year;?>
            </th>
          </tr>
          <tr>
            <th class="w-12">NAME :</th>
            <td class="text-underline">  <?php echo  $student->srname;?></td>
            <th class="text-end">REG. NO :</th>
            <td class="text-underline"><?php echo  $student->srregisterNO;?></td>
          </tr>

          <tr>
            <th>FATHER NAME :</th>
            <td class="text-underline"><?php echo  $student->srname;?></td>
            <th class="text-end">ROLL. NO :</th>
            <td class="text-underline"><?php echo  $student->roll;?></td>
          </tr>
        </table>
       
      </header>

      <section class="table-2" style="padding-bottom: 300px">
        <table class="table table-bordered table-borders-darken my-0">
          <thead>
            <tr>
              <th scope="col">Sr. No.</th>
              <th scope="col">Course Code</th>
              <th scope="col" class="subject-column">Subject Title</th>
              <th scope="col">Credit Hours</th>
              <th scope="col">Max. Marks</th>
              <th scope="col">Obt. Marks</th>
              <th scope="col">Marks Per (%)</th>
              <th scope="col">Letter Grade</th>
              <th scope="col">Q.P.</th>
              <th scope="col">Action</th>
            </tr>
          </thead>

          <tbody>
            <!-- semester 1 -->
             

                <?php

                $grand_credit     =   0;
                $grand_obtain     =   0;
                $grand_mark       =   0;
                 foreach ($sections as $section) {
                    if (count($result[$section->sectionID])) {
                    ?>
              <!-- row: semester # -->
              <tr>
                <td colspan="10" class="text-bold text-start"><?php echo  $section->section;   ?> Semester</td>
              </tr>

              <?php  
              $sr               =   1;
              $total_credit     =   0;
              $total_obtain     =   0;
              $total_mark       =   0;
              
              
                  
             
              foreach ($result[$section->sectionID] as $res) {

                if (isset($subjects[$res->subjectID])) {
                   
                $total_credit     +=   $subjects[$res->subjectID]->credit_hour;
                $total_obtain     +=   $res->obtain_mark;
                $total_mark       +=   $res->total_mark;

                ?>
                
            

              <!-- course 1 -->
              <tr class="carousel">
                <td><?php echo $sr;  $sr++; ?></td>
                <td><?php echo $subjects[$res->subjectID]->subject_code;?></td>
                <td class="subject-column"><?php echo $subjects[$res->subjectID]->subject;?></td>
                <td><?php echo $subjects[$res->subjectID]->credit_hour;?></td>
                <td><?php echo $res->total_mark;?></td>
                <td><?php echo $res->obtain_mark;?></td>
                <td><?php $perc     =    round(($res->obtain_mark/$res->total_mark*100),2); 
                          echo $perc;  ?></td>

                <?php 
                $text  = "";
                 if(customCompute($grades)) {
                                                            foreach ($grades as $grade) {
                                                                if(($grade->gradefrom <= $perc) && ($grade->gradeupto >= $perc)) {
                                                                    $text.= "<td>";
                                                                        $text.= $grade->grade;
                                                                       
                                                                    $text.= "</td>";
                                                                     
                                                                }
                                                            }
                                                        } else {
                                                            $text.= "<td>";
                                                                $text.= 'N/A';
                                                            $text.= '</td>';
                                                             
                                                        }

                echo  $text;
                ?>
                 
                <td>12.33</td>
                <td>
                    <div class="editform" id="edit_result_<?php echo $res->resultmanagementID; ?>"   >                


                  <form class="form-horizontal" role="form" method="post">
                    <span class="close_edit">X</span>  
                    <input type="hidden" name="resultmanagementID" value="<?php echo $res->resultmanagementID; ?>">    
                    <div class="col-sm-2">      
                    <?php 
                        if(form_error('total_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="total_mark" class="col-sm-12 control-label">
                            Total Mark <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="total_mark" name="total_mark" value="<?=set_value('total_mark', $res->total_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('total_mark'); ?>
                        </span>
                    </div>

                  </div>
                          
                    <div class="col-sm-2">      
                    <?php 
                        if(form_error('obtain_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="obtain_mark" class="col-sm-12 control-label">
                            obtain mark <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="obtain_mark" name="obtain_mark" value="<?=set_value('obtain_mark', $res->obtain_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('obtain_mark'); ?>
                        </span>
                    </div>

                  </div>
                          
                    <div class="col-sm-2">      
                    <?php 
                        if(form_error('internal_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="internal_mark" class="col-sm-12 control-label">
                            internal mark <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="internal_mark" name="internal_mark" value="<?=set_value('internal_mark', $res->internal_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('internal_mark'); ?>
                        </span>
                    </div>

                  </div>
                          
                    <div class="col-sm-2">      
                    <?php 
                        if(form_error('midterm_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="midterm_mark" class="col-sm-12 control-label">
                            midterm mark <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="midterm_mark" name="midterm_mark" value="<?=set_value('midterm_mark', $res->midterm_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('midterm_mark'); ?>
                        </span>
                    </div>

                  </div>
                          
                    <div class="col-sm-1">      
                    <?php 
                        if(form_error('finalterm_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="finalterm_mark" class="col-sm-12 control-label">
                            finalterm<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="finalterm_mark" name="finalterm_mark" value="<?=set_value('finalterm_mark', $res->finalterm_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('finalterm_mark'); ?>
                        </span>
                    </div>

                  </div>
                          
                    <div class="col-sm-1">      
                    <?php 
                        if(form_error('practical_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="practical_mark" class="col-sm-12 control-label">
                            practical<span class="text-red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="practical_mark" name="practical_mark" value="<?=set_value('practical_mark', $res->practical_mark)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('practical_mark'); ?>
                        </span>
                    </div>

                  </div>

                    <div class="col-sm-1">
                         <label for="practical_mark" class="col-sm-12 control-label">
                           &nbsp;
                        </label>
                            <input type="submit" class="btn btn-success" value="Update" >
                         
                    </div>


                        </form>
                    </div>
                    <?php  if(permissionChecker('resultmanagement_edit')){ ?>
                      <a href="javascript:;" onclick="show_edit_form('<?php echo $res->resultmanagementID;?>')" class="btn-cs btn-sm-cs" style="text-decoration: none;" role="button"><i class="fa fa-edit"></i> Edit</a>
                    <?php } ?>
                    <?php echo btn_sm_delete('resultmanagement/delete/'.$res->resultmanagementID, 'Delete') ?>    
                </td>
              </tr>


               <?php 
                   }
                    }
                ?>

             

              <!-- row: total -->
              <tr class="text-bold">
                <td colspan="3">Semester Total :</td>
                <td><?php echo  $total_credit ;?></td>
                <td><?php echo   $total_mark ;?></td>
                <td><?php echo   $total_obtain ; ?></td>
                <td><?php $perct     =    round(($total_obtain/$total_mark*100),2);
                          echo $perct;
                    ?></td>
                <td></td>
                <td>58.33</td>
                <td>GPA, 3.65</td>
              </tr>

                <?php 

                $grand_credit     +=   $total_credit;
                $grand_obtain     +=   $total_obtain;
                $grand_mark       +=   $total_mark;
                        }
                    }
                    ?>
             
              <tr class="text-bold">
                <td colspan="3">Grand Total :</td>
                <td><?php echo  $grand_credit ;?></td>
                <td><?php echo  $grand_mark ;?></td>
                <td><?php echo  $grand_obtain ; ?></td>
                <td><?php $perc_grand     =    round(($grand_obtain/$grand_mark*100),2);
                          echo $perc_grand;
                    ?></td>
                <td></td>
                <td>58.33</td>
                <td>GPA, 3.65</td>
              </tr>

           
 
            <tr class="text-bold">
              <td colspan="10">
                Cumulative Grade Point Average (CGPA) required 2.50, Earned >> 3.30
              </td>
            </tr>
          </tbody>
        </table>

         
      </section>

       

             </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).on('click','.close_edit', function() {
       
      $(this).closest('.editform').hide('slow');
    });

    function show_edit_form(resultmanagementID) {
        $('#edit_result_'+resultmanagementID).show('slow');
    }
</script>
