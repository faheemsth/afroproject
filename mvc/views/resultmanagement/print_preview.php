
            
 

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
              <tr>
                <td><?php echo $sr;  $sr++; ?></td>
                <td><?php echo $subjects[$res->subjectID]->subject_code;?></td>
                <td class="subject-column"><?php echo $subjects[$res->subjectID]->subject;?></td>
                <td><?php echo $subjects[$res->subjectID]->credit_hour;?></td>
                <td><?php echo $res->total_mark;?></td>
                <td><?php echo $res->obtain_mark;?></td>
                <td><?php $perc     =    round(($res->obtain_mark/$res->total_mark*100),2);
                          echo $perc;
                    ?></td>

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

       

             