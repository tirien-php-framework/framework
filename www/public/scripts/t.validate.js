/*  
    Validate jQuery Plugin
    Tirien.com
    $Rev: 78 $
    
    Use class 'required' on inputs that is mandatory and class 'email' to validate email.
    
    This is optional:
    options = {
        activeColor: 'white',
        inactiveColor: 'white'
    };
    
    To initiate use:
    $("#contact-form").tValidate(options);
*/

(function($) {
    $.tValidate = function(element, options) {
        var settings = {
            activeColor: 'black',
            inactiveColor: 'gray',
            errorInputFontColor: 'red',
            errorInputBorderColor: 'red',
            validInputFontColor: 'green',
            validInputBorderColor: 'green',
            placeholders: true
        }

        settings = $.extend({}, settings, options);
        form = $(element);
        inputs = form.find("input,textarea");
        errorMsg = "Required fields can not be empty";

        // placeholders
        inputs.each(function(){
            if( typeof( $(this).data('placeholder') ) == "undefined" ){
                $(this).data('placeholder', $(this).val());
            }
            else if( $(this).val() == '' && settings.placeholders ){
                $(this).val( $(this).data('placeholder') );
            }
        });

        inputs.not('[type="submit"]').css('color', settings.inactiveColor).focus(function(){
            if( $(this).val() == $(this).data('placeholder') && settings.placeholders ){
                $(this).val('');
                $(this).css('color', settings.activeColor);
            }
        }).blur(function(){
            if( $(this).val()=='' ){
                $(this).css('color', settings.inactiveColor);
                if( settings.placeholders ){
                    $(this).val( $(this).data('placeholder') );
                }
            }
        });

        // validation
        form.submit(function(){
            var valid = true;
            inputs.filter(".required").css({borderColor:settings.validInputBorderColor, color:settings.validInputFontColor});

            inputs.filter(".required").each(function(){

                var emailPattern = /^[-\w\.]+@([-\w\.]+\.)[-\w]{2,4}$/;
                
                if( $(this).val()=='' || ( $(this).val()==$(this).data("placeholder") && settings.placeholders ) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("email") && !emailPattern.test($(this).val()) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    errorMsg = "Email is not valid";
                    valid = false;
                }

            });
            
            if( valid ){
                return true;
            }
            else{
                alert(errorMsg);
                return false;
            }
        });
    }

    $.fn.tValidate = function(options) {
        return this.each(function() {
            if ($(this).data('tValidate') == undefined) {
                var tValidateObject = new $.tValidate(this, options);
                $(this).data('tValidate', tValidateObject);
            }
        });
    }

})(jQuery);