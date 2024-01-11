// Class definition

var KTInputmask = function () {
    
    // Private functions
    var demos = function () {
        // date format
        $("#kt_inputmask_1").inputmask("99/99/9999", {
            "placeholder": "mm/dd/yyyy",
            autoUnmask: true
        });

        // custom placeholder        
        $("#kt_inputmask_2").inputmask("99/99/9999", {
            "placeholder": "mm/dd/yyyy",
        });
		
		
		
        
        // phone number format
        $("#kt_inputmask_3").inputmask("mask", {
            "mask": "(999) 999-9999"
        }); 

        // empty placeholder
        $("#kt_inputmask_4").inputmask({
            "mask": "99-9999999",
            placeholder: "" // remove underscores from the input mask
        });

        // repeating mask
        $("#kt_inputmask_5").inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }); // ~ mask "9" or mask "99" or ... mask "9999999999"
        
        // decimal format
        $("#kt_inputmask_6").inputmask('decimal', {
            rightAlignNumerics: false
        }); 
        
        // currency format
        $("#kt_inputmask_7").inputmask('€ 999.999.999,99', {
            numericInput: true
        }); //123456  =>  € ___.__1.234,56

        //ip address
        $("#kt_inputmask_8").inputmask({
            "mask": "999.999.999.999"
        });  

        //email address
        $(".EMAIL").inputmask({
            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
            greedy: false,
            onBeforePaste: function (pastedValue, opts) {
                pastedValue = pastedValue.toLowerCase();
                return pastedValue.replace("mailto:", "");
            },
            definitions: {
                '*': {
                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
                    cardinality: 1,
                    casing: "lower"
                }
            }
        });   
		
		
		 // phone number format
        $(".CELL_NUMBER_PAK").inputmask("mask", {
            "mask": "0399-9999999"
        });
		
		$(".CELL_NUMBER_INT").inputmask("mask", {
            "mask": "*{1,4}-*{1,4}-*{1,9}"
        });
		
		 $(".TEL_NUMBER_PAK").inputmask("mask", {
            "mask": "\\92-*{1,4}-*{1,9}"
        });
		
		$(".ACT_CODE").inputmask("mask", {
            "mask": "9-9-9-9-9-9"
        });
		
		$(".CNIC").inputmask("mask", {
            "mask": "99999-9999999-9"
        });
		
		$(".IBAN").inputmask("mask", {
            "mask": "AA99AAAA9999999999999999"
        });
		
		$(".NTN").inputmask("mask", {
            "mask": "[A|9]999999-9"
        }); 
		
		$(".ROLL_NUMBER").inputmask({
            "mask": "9999-99-9999",
           
        });     
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {
    KTInputmask.init();
});