<form name="suForm" class="form" id="kt_login_singin_form" method="post" action="#">

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
if($this->session->flashdata('reset_success')) {
$message = $this->session->flashdata('reset_success');
echo "<div class=\"alert alert-custom alert-outline-danger fade show mb-5 alert-dismissable\">
<div class=\"alert-icon\">
<i class=\"flaticon-warning icon-xl\"></i>
</div>

<div class=\"alert-text\">$message</div>
</div>";
}
?>

<div class="form-group fv-plugins-icon-container">
<label class="font-size-h6 font-weight-bolder text-dark">Username</label>
<input class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6 username" type="text" placeholder="Enter your email address" name="username" id="email"  value="<?=set_value('username')?>" autocomplete="off" />
<div class="fv-plugins-message-container"><div data-field="username" data-validator="notEmpty" class="fv-help-block" id="usernametext"></div></div>
</div>

<div class="form-group  fv-plugins-icon-container ">
<label class="font-size-h6 font-weight-bolder text-dark">Password</label>
<input class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6  password" type="password" placeholder="Enter Password" name="password" autocomplete="off" />

<div class="fv-plugins-message-container"><div data-field="username" data-validator="notEmpty" class="fv-help-block" id="passwordtext"></div></div>

<!--begin::Form-->

</div>
<div class="d-flex justify-content-between mt-n5">
<label class="font-size-h6 font-weight-bolder text-dark pt-5"></label>
<a href="<?php echo base_url() ?>reset/index" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot Password ?</a>
</div>
<!--end::Form group-->
<!--begin::Form group-->

<!--end::Form group-->
<!--begin::Action-->
<div class="pb-lg-0 pb-5">
<button type="submit" id="kt_login_singin_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>

</div>
<!--end::Action-->
</form>
<!--end::Form-->
