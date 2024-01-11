function printDiv(divID) {
	        var divElements = document.getElementById(divID).innerHTML;
	        var oldPage = document.body.innerHTML;
	        document.body.innerHTML =
	          "<html><head><title></title></head><body>" +
	          divElements + "</body>";
	        window.print();
	        document.body.innerHTML = oldPage;
	        window.location.reload();
	    }

	    function closeWindow() {
	        location.reload();
	    }

	    function check_email(email) {
	        var status = false;
	        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	        if (email.search(emailRegEx) == -1) {
	            $("#to_error").html('');
	            $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
	        } else {
	            status = true;
	        }
	        return status;
	    }

	    $('#totaldiscount').keyup(function() {
		     // var discount_type= $('input[type="radio"]:checked').val();
		     var discount_type = 'amount';
		     var discount = $(this).val();
		     var std_ol_discount = $("#std_ol_discount").val();
		     var totalfee = $("#totalfee").val();
            var net_fee = $("#net_fee").val();
		     if(discount != '' && totalfee != ''){
		        if(discount_type == 'percentage'){
		            if( discount > 100 ){
		                alert('Percentage value can not more than 100');
		                return false;
		            }else{
		                var total = totalfee;
		                var totals = total * discount / 100;
		                console.log(totals);
		                //  var netfee = totals + 15000;
		                var netfee = totalfee - totals;
		                var totalshtml = `<label class="col-sm-2">Net Fee</label><div class="col-sm-6">`+netfee+`</div>`;
		            }     
		         }else{
		            var total = totalfee;
		            var totals = eval(discount) + eval(std_ol_discount);
		            console.log(totals);
		            console.log(net_fee);
		          //  if(totals > net_fee){
	           //         alert('Total discount should not be more than net Fee');
	           //         $(this).val(0);
	           //         $(this).trigger('keyup');
		          //      return false;
		          //  }else{
		                 var netfee = totalfee - totals;
		                var totalshtml = `<label class="col-sm-2">Net Fee</label><div class="col-sm-6">`+netfee+`</div>`;
		          //  }
		         }     
		     }else{
		        var totalshtml = '';     
		     }
		     
		    $(".net_fee").html('');
		    $(".net_fee").append(totalshtml);
		    $("#net_fee").val(netfee);
		    
		});

	    $('#send_pdf').click(function() {
	        var to = $('#to').val();
	        var subject = $('#subject').val();
	        var message = $('#message').val();
	        var id = "<?=$profile->srstudentID;?>";
	        var set = "<?=$set;?>";
	        var error = 0;

	        $("#to_error").html("");
	        if(to == "" || to == null) {
	            error++;
	            $("#to_error").html("");
	            $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
	        } else {
	            if(check_email(to) == false) {
	                error++
	            }
	        }

	        if(subject == "" || subject == null) {
	            error++;
	            $("#subject_error").html("");
	            $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
	        } else {
	            $("#subject_error").html("");
	        }

	        if(error == 0) {
	        	$('#send_pdf').attr('disabled','disabled');
	            $.ajax({
	                type: 'POST',
	                url: "<?=base_url('student/send_mail')?>",
	                data: 'to='+ to + '&subject=' + subject + "&studentID=" + id+ "&message=" + message+ "&classesID=" + set,
	                dataType: "html",
	                success: function(data) {
	                    var response = JSON.parse(data);
	                    if (response.status == false) {
	        				$('#send_pdf').removeAttr('disabled');
	                        $.each(response, function(index, value) {
	                            if(index != 'status') {
	                                toastr["error"](value)
	                                toastr.options = {
	                                  "closeButton": true,
	                                  "debug": false,
	                                  "newestOnTop": false,
	                                  "progressBar": false,
	                                  "positionClass": "toast-top-right",
	                                  "preventDuplicates": false,
	                                  "onclick": null,
	                                  "showDuration": "500",
	                                  "hideDuration": "500",
	                                  "timeOut": "5000",
	                                  "extendedTimeOut": "1000",
	                                  "showEasing": "swing",
	                                  "hideEasing": "linear",
	                                  "showMethod": "fadeIn",
	                                  "hideMethod": "fadeOut"
	                                }
	                            }
	                        });
	                    } else {
	                        location.reload();
	                    }
	                }
	            });
	        }
	    });

	    $('.mark-bodyID').mCustomScrollbar({
            axis:"x"
        });

        $('.studentDIV').each(function() {
        	$(this).mCustomScrollbar({
	            axis:"x"
	        });
        });