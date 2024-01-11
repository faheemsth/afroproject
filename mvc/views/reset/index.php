<!--begin::Form group-->

<form name="suForm" autocomplete="off" class="form" id="kt_login_forgot_form" method="post" action="#" role="form" >
            <?php 
            if($form_validation == "No"){
            } else {
            if(customCompute($form_validation)) {
            echo "<div class=\"alert alert-custom alert-outline-danger alert-dismissable fade show mb-5\">
                     <div class=\"alert-icon\">
                        <i class=\"flaticon-warning icon-xl\"></i>
                        </div>
                       
                      <div class=\"alert-text\">$form_validation</div>
                    </div>";
            }
            }

            if($this->session->flashdata('reset_send')) {
            $message = $this->session->flashdata('reset_send');
            echo "<div class=\"alert alert-custom alert-outline-success fade show mb-5 alert-dismissable\">
                 <div class=\"alert-icon\">
                    <i class=\"flaticon-warning icon-xl\"></i>
                    </div>
                   
                  <div class=\"alert-text\">$message</div>
                </div>";
            } else {
            if($this->session->flashdata('reset_error')) {
            $message = $this->session->flashdata('reset_error');
            echo "<div class=\"alert alert-custom alert-outline-danger fade show mb-5 alert-dismissable\">
                 <div class=\"alert-icon\">
                    <i class=\"flaticon-warning icon-xl\"></i>
                    </div>
                   
                  <div class=\"alert-text\">$message</div>
                </div>";
            }
            }
            ?>
            <input type="hidden" name="suaction" value="1" />
            <input type="hidden" name="CSRF" value="3jrf865cbhune20013msjn9k84" />
            <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark">Username</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6" type="email" placeholder="Enter your email address" name="email" id="email"  value="" autocomplete="off" />
            </div>
            <!--end::Form group-->
            <!--begin::Form group-->


            <div class="form-group d-flex flex-wrap">
            <button type="submit" id="kt_login_forgot_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4" value="Send">Retrieve Password</button>
            <a href="<?php echo base_url()?>signin/index" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</a>
            </div>
            </form>
<!--end::Form group-->