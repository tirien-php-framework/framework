/*  
    Validate jQuery Plugin
    Tirien.com
    $Rev$

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
            activeColor: '#222',
            inactiveColor: '#777',
            errorInputFontColor: 'red',
            errorInputBorderColor: 'red',
            validInputFontColor: 'green',
            validInputBorderColor: 'green',
            placeholders: false
        }

        settings = $.extend({}, settings, options);
        form = $(element);
        inputs = form.find("input,textarea");
        errorMsg = "Required fields can not be empty";

        // placeholders
        inputs.each(function(){

            $(this).css('color', settings.activeColor);

            if( typeof( $(this).data('placeholder') ) == "undefined" && settings.placeholders ){
                $(this).data('placeholder', $(this).attr("name"));
            }

            if( $(this).val() == '' && settings.placeholders ){
                $(this).val( $(this).data('placeholder') ).css('color', settings.inactiveColor);;
            }

        });

        inputs.focus(function(){
            $(this).css('color', settings.activeColor);
            if( $(this).val() == $(this).data('placeholder') && settings.placeholders ){
                $(this).val('');
            }
        }).blur(function(){
            if( $(this).val()=='' && settings.placeholders ){
                $(this).css('color', settings.inactiveColor);
                $(this).val( $(this).data('placeholder') );
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