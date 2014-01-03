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
            activeColor: 'black',
            inactiveColor: 'gray',
            errorInputFontColor: 'red',
            errorInputBorderColor: 'red',
            validInputFontColor: 'green',
            validInputBorderColor: 'green',
            enableValidColors: false,
            placeholders: true
        }

        var settings = $.extend({}, settings, options);
        var form = $(element);
        var inputs = form.find("input,textarea").not("[type='submit']");


        // placeholders
        inputs.each(function(){
            
            if( !settings.enableValidColors ){
                $(this).data('activeColor', $(this).css('color') );
                $(this).data('validInputFontColor', $(this).css('color') );
                $(this).data('validInputBorderColor', $(this).css('border-color') );
            }

            if( $(this).val() == '' && settings.placeholders ){
                $(this).val( $(this).data('placeholder') ).css('color', settings.inactiveColor);
            }

        });

        inputs.focus(function(){

            if( !settings.enableValidColors ){
                $(this).css('color', $(this).data('activeColor') );
            }
            else{
                $(this).css('color', settings.activeColor);
            }

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
            var form = $(this);

            inputs.each(function(){

                var emailPattern = /^[-\w\.]+@([-\w\.]+\.)[-\w]{2,4}$/;
                var postcodePattern = /^\d{5}$/;
                var phonePattern = /^\+?[\d-\/ ]+$/;
                var numberPattern = /^[\d]+$/;


                if( !settings.enableValidColors ){
                    settings.validInputFontColor = $(this).data('validInputFontColor');
                    settings.validInputBorderColor = $(this).data('validInputBorderColor');
                }

                $(this).css({
                    borderColor:settings.validInputBorderColor, 
                    color:settings.validInputFontColor
                });

                if( 
                    $(this).hasClass("required") && 
                    ( 
                        $(this).val()=='' || 
                        ( 
                            $(this).val()==$(this).data("placeholder") && 
                            settings.placeholders 
                            )
                        )    
                    ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Required fields can not be empty";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("email") && !emailPattern.test($(this).val()) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Email is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("phone") && !phonePattern.test($(this).val()) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Phone is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("postcode") && !postcodePattern.test($(this).val()) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Postcode is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("number") && !numberPattern.test($(this).val()) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Only numbers allowed";
                    valid = false;
                }
                else if( $(this).hasClass("terms") && !$(this).prop('checked') ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "You have to accept Terms and Conditions to continue";
                    valid = false;
                }
                else if( form.find("input[name='password']").val() != form.find("input[name='repeat_password']").val() ){
                    $("input[name='password'], input[name='repeat_password']").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Passwords must match";
                    valid = false;
                }

            });
            
            if( valid ){

                inputs.each(function(){
                    if( $(this).val() == $(this).data('placeholder') && settings.placeholders ){
                        $(this).val("");
                    }
                });
               
                return true;
                
            }
            else{
                alert(settings.errorMessage);
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