<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Sign In To Afro-Asian Portal</title>
   
   <link href="<?php echo base_url('assets/css/style.bundlec7e5.css?v=7.1.1') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/pages/login/login-4.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/media/logos/icon.png" />


</head>

<!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading" >
        <!--begin::Main-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Login-->
            <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
                <!--begin::Content-->
                <div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
                    <!--begin::Wrapper-->
                    <div class="login-content d-flex flex-column pt-lg-0 pt-12">
                        <!--begin::Logo-->
                        <a href="javascript:;" class="login-logo pb-xl-20 pb-15">
                            <img src="<?php echo base_url() ?>assets/media/logos/afro-logo.png" class="max-h-70px" alt="Afro-Asian" />
                        </a>
                        <!--end::Logo-->
                        <!--begin::Signin-->
                        
                                                        
                        <div class="login-form">
                                <!--begin::Title-->
                                <div class="pb-5 pb-lg-5">
                                    <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h3>
                                     
                                </div>
                                <!--begin::Title-->

                        <?php $this->load->view($subview); ?>

                        </div>
                        <!--Start of Tawk.to Script-->



                    <!--End of Tawk.to Script-->
                    <div class="footer py-4 d-flex flex-lg-column">
                        <div class="container-fluid d-flex flex-column flex-md-row align-items-left justify-content-between">
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted font-weight-bold mr-2">Copyright &copy; <?php date('Y');?>. AFRO-ASIAN INSTITUTE, LAHORE Pakistan - All Rights Reserved
                                            </span>
                            </div>
                        </div>
                    </div>                      <!--end::Signin-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--begin::Content-->
                <!--begin::Aside-->
                
                    <div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
                        <div  class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" id="background" style="background-position: center center; background-size: cover;" >
                            
                        </div>
                    </div>
                <!--end::Aside-->
            </div>
            <!--end::Login-->
        </div>
        <!--end::Main-->
    <script src="<?php echo base_url('assets/inilabs/jquery.min.js') ?>"></script> 
    <script type="text/javascript">
    var images = ['home1.JPG', 'home2.JPG', 'home3.JPG', 'home4.JPG', 'home5.JPG'];
    $('#background').css({'background-image': 'url(<?php echo  base_url() ?>assets/media/svg/illustrations/' + images[Math.floor(Math.random() * images.length)] + ')'});

    $('.username').keyup(function(e){
    var value   =   this.value;
        
    if (value.length>0) {
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('is-invalid');

                $(this).parent().addClass('has-success');
                $(this).addClass('is-valid');
                $("#usernametext").empty();
          }else{
                 $(this).parent().removeClass('has-success');
                $(this).removeClass('is-valid');

                $(this).parent().addClass('has-danger');
                $(this).addClass('is-invalid'); 
                $("#usernametext").text('Username is required');
          }
    });
    $('.password').keyup(function(e){
    var value   =   this.value;
        
    if (value.length>0) {
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('is-invalid');

                $(this).parent().addClass('has-success');
                $(this).addClass('is-valid');
                $("#passwordtext").empty();
          }else{
                 $(this).parent().removeClass('has-success');
                $(this).removeClass('is-valid');

                $(this).parent().addClass('has-danger');
                $(this).addClass('is-invalid'); 
                $("#passwordtext").text('Password is required');
          }
    });
    </script>

    </script>

</body>
</html>
