<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("result/index")?>"><?=$this->lang->line('menu_result')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_result')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

<style type="text/css">
    
          
            #Page-1 path{
                fill: green !important;
            }

              #Check-\+-Oval-2 path{
                fill: red !important;
                z-index: 999999999999;
                position: relative;
            }
</style>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>
                <h3>Upload <small> Result Cards</small></h3>
<SECTION>
  <DIV id="dropzone">
    <FORM class="dropzone needsclick" id="demo-upload" action="<?php echo base_url("result/doupload/$result->ResultID");?>">
      <DIV class="dz-message needsclick">
      <b>    
        Drop files here or click to upload.</b><BR>
            
             <svg style="
    width: 100%;
" version="1.0" xmlns="http://www.w3.org/2000/svg" width="100pt" height="100pt" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none" style="
    width: 100%;
">
<path d="M1324 4494 c-32 -31 -27 -65 16 -109 l30 -29 -29 -31 c-44 -44 -49
-78 -17 -109 35 -35 65 -33 106 9 l33 34 39 -34 c43 -40 78 -45 108 -15 29 29
26 71 -10 105 -16 16 -30 34 -30 40 0 6 14 24 30 40 36 34 39 76 10 105 -30
30 -65 25 -108 -15 l-39 -34 -33 34 c-41 42 -71 44 -106 9z"></path>
<path d="M2497 4446 c-20 -7 -43 -18 -50 -23 -107 -83 -376 -302 -389 -318
-10 -11 -23 -37 -29 -57 l-12 -38 -576 -2 -576 -3 -67 -33 c-82 -40 -157 -119
-190 -200 l-23 -57 -3 -1144 c-3 -1269 -5 -1215 63 -1317 40 -61 103 -115 171
-146 49 -23 50 -23 680 -28 l631 -5 -47 -165 -47 -165 -707 -5 -708 -5 -19
-24 c-25 -31 -24 -63 4 -89 l23 -22 1935 0 1936 0 21 23 c27 29 28 57 3 88
l-19 24 -673 5 -673 5 -48 165 c-26 91 -48 166 -48 167 0 1 269 4 598 5 l597
3 56 26 c107 50 185 144 214 255 13 54 15 198 13 1205 l-3 1144 -23 57 c-33
81 -108 160 -190 200 l-67 33 -576 3 -576 2 -11 38 c-6 20 -24 50 -38 66 -37
39 -367 302 -404 321 -43 22 -109 27 -153 11z m275 -276 c199 -159 214 -178
184 -225 -29 -44 -73 -37 -151 25 -89 71 -109 78 -143 53 l-27 -20 -5 -402 -5
-403 -24 -19 c-29 -24 -53 -24 -82 0 l-24 19 -5 403 -5 402 -27 20 c-34 25
-54 18 -143 -53 -79 -63 -106 -70 -139 -36 -32 31 -33 57 -3 90 12 14 99 86
192 161 116 92 178 135 195 135 17 0 83 -46 212 -150z m-710 -320 c49 -67 168
-91 248 -50 22 11 41 20 44 20 3 0 7 -150 8 -333 l3 -334 28 -40 c39 -56 97
-86 166 -86 62 0 101 16 145 60 54 53 56 65 56 413 0 176 2 320 4 320 2 0 23
-9 47 -20 51 -23 126 -26 173 -6 18 7 49 30 68 50 l35 36 545 0 c600 0 608 -1
682 -62 21 -17 49 -54 62 -82 l24 -51 0 -997 0 -998 -1840 0 -1840 0 0 998 0
997 24 51 c13 28 41 65 62 83 72 59 75 60 681 60 l552 1 23 -30z m2336 -2378
c-3 -78 -7 -95 -33 -138 -39 -62 -113 -109 -187 -117 -69 -9 -3167 -9 -3236 0
-74 8 -148 55 -187 117 -26 43 -30 60 -33 138 l-4 88 1842 0 1842 0 -4 -88z
m-1434 -555 c25 -89 46 -166 46 -170 0 -4 -187 -7 -415 -7 -290 0 -415 3 -415
11 0 8 46 174 86 312 5 16 29 17 328 17 l323 0 47 -163z"></path>
<path d="M3732 3627 c-123 -47 -175 -177 -117 -294 36 -71 89 -106 169 -111
74 -5 125 16 174 72 113 129 29 325 -146 341 -26 3 -62 -1 -80 -8z m116 -147
c45 -42 15 -123 -46 -123 -43 0 -72 28 -72 70 0 65 71 96 118 53z"></path>
<path d="M1067 2910 c-72 -57 8 -167 81 -113 51 38 34 114 -28 128 -18 4 -34
0 -53 -15z"></path>
<path d="M4080 2917 c-55 -28 -48 -110 13 -131 77 -27 124 80 55 128 -27 19
-35 20 -68 3z"></path>
<path d="M1804 2899 c-74 -28 -128 -79 -166 -157 l-33 -67 0 -300 0 -300 26
-55 c36 -77 80 -124 147 -157 l57 -28 725 0 725 0 57 28 c67 33 111 80 147
157 l26 55 0 300 0 300 -33 67 c-39 79 -93 129 -169 158 -52 19 -77 20 -756
19 -672 0 -704 -1 -753 -20z m1483 -136 c19 -9 47 -34 61 -56 l27 -40 3 -280
c3 -268 2 -281 -19 -323 -14 -30 -35 -52 -63 -69 l-43 -25 -693 0 -693 0 -43
25 c-28 17 -49 39 -63 69 -21 42 -22 55 -19 323 l3 280 26 40 c51 76 10 73
787 73 626 0 697 -2 729 -17z"></path>
<path d="M1912 2638 c-12 -6 -25 -21 -28 -32 -2 -12 -3 -122 -2 -245 l3 -224
27 -20 c24 -18 30 -18 57 -7 35 14 40 27 43 125 l3 70 87 5 c101 6 130 22 166
91 44 83 10 188 -74 229 -33 16 -60 20 -150 19 -60 0 -119 -5 -132 -11z m236
-160 c4 -32 -18 -41 -89 -36 -42 3 -44 5 -47 36 l-3 33 68 -3 c66 -3 68 -4 71
-30z"></path>
<path d="M2380 2630 c-19 -19 -20 -33 -20 -253 0 -185 3 -237 14 -253 12 -16
29 -20 117 -22 115 -4 163 6 209 44 54 46 60 69 60 229 0 155 -6 179 -52 222
-43 41 -90 53 -203 53 -92 0 -108 -3 -125 -20z m238 -132 c15 -15 16 -211 2
-239 -9 -15 -22 -19 -70 -19 l-60 0 0 135 0 135 58 0 c32 0 63 -5 70 -12z"></path>
<path d="M2854 2625 l-25 -25 3 -231 c3 -229 3 -231 27 -250 31 -26 57 -24 86
6 22 22 25 32 25 105 l0 80 83 0 c95 0 117 13 117 67 0 50 -25 63 -118 63
l-82 0 0 35 0 35 105 0 c58 0 115 5 130 12 28 13 40 45 31 81 -9 39 -45 47
-207 47 -147 0 -150 -1 -175 -25z"></path>
<path d="M2534 1471 c-63 -39 -66 -124 -6 -171 45 -36 121 -20 148 31 49 95
-53 196 -142 140z"></path>
</g>
</svg>
      </DIV>
    </FORM>
  </DIV>
</SECTION>
<script type="text/javascript">
    Dropzone.options.demoUpload = {
  maxFiles: 200,
  accept: function(file, done) {
    console.log("uploaded");
    done();
  },
  init: function() {
    this.on("maxfilesexceeded", function(file){
           toastr[ "error" ]("Maximum File Upload Limit Exceeded (Max limit is 200)");
              toastr.options = {
                "closeButton" : true,
                "debug" : false,
                "newestOnTop" : false,
                "progressBar" : false,
                "positionClass" : "toast-top-right",
                "preventDuplicates" : false,
                "onclick" : null,
                "showDuration" : "500",
                "hideDuration" : "500",
                "timeOut" : "5000",
                "extendedTimeOut" : "1000",
                "showEasing" : "swing",
                "hideEasing" : "linear",
                "showMethod" : "fadeIn",
                "hideMethod" : "fadeOut"
              }
    });
  }

}
</script>
<br/>
<hr size="3" noshade color="#F00000">
  
<DIV id="preview-template" style="display: none;">
<DIV class="dz-preview dz-file-preview">
<DIV class="dz-image"><IMG data-dz-thumbnail=""></DIV>
<DIV class="dz-details">
<DIV class="dz-size"><SPAN data-dz-size=""></SPAN></DIV>
<DIV class="dz-filename"><SPAN data-dz-name=""></SPAN></DIV></DIV>
<DIV class="dz-progress"><SPAN class="dz-upload" 
data-dz-uploadprogress=""></SPAN></DIV>
<DIV class="dz-error-message"><SPAN data-dz-errormessage=""></SPAN></DIV>
<div class="dz-success-mark">
  <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
    <title>Check</title>
    <desc>Created with Sketch.</desc>
    <defs></defs>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
    </g>
  </svg>
</div>
<div class="dz-error-mark">
  <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
      <title>error</title>
      <desc>Created with Sketch.</desc>
      <defs></defs>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
          <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
              <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
          </g>
      </g>
  </svg>
</div>

  

            </div>    
        </div>
    </div>
</div>

 