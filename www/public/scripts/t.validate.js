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
        var defaultSettings = {
            activeColor: 'black',
            inactiveColor: 'gray',
            errorInputFontColor: 'red',
            errorInputBorderColor: 'red',
            validInputFontColor: 'green',
            validInputBorderColor: 'green',
            enableValidColors: false,
            placeholders: true,
            autoSubmit: true,
            onValidForm: function(e){}
        }

        var settings = this.settings = $.extend({}, defaultSettings, options);

        var form = $(element);
        var inputs = form.find("input,textarea,select").not("[type='submit']");
        var submitedBefore = false;

        inputs.bind('change', function(){
            if (submitedBefore) {
                validate(false);
            }
        });

        // placeholders
        inputs.each(function(){
            
            if( !settings.enableValidColors ){
                if ($(this).next('.custom-combobox').find('.cs-selected').length) {
                    var existingColor = $(this).next('.custom-combobox').find('.cs-selected').css('color');
                    var existingInputFontColor = $(this).next('.custom-combobox').find('.cs-selected').css('color');
                    var existingInputBorderColor = $(this).next('.custom-combobox').find('.cs-selected').css('border-color');
                }
                else{
                    var existingColor = $(this).css('color');
                    var existingInputFontColor = $(this).css('color');
                    var existingInputBorderColor = $(this).css('border-color');
                }

                $(this).data('activeColor', existingColor );
                $(this).data('validInputFontColor', existingInputFontColor );
                $(this).data('validInputBorderColor', existingInputBorderColor );
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

        // define event handler for future use like $('#contact-form').data('tValidate').submitEventHandler
        this.submitEventHandler = form.on('submit', function(e){
            submitedBefore = true;
            return validate(true);
        });

        var validate = function(showAlert){
            var valid = true;
            var form = $(this);

            inputs.each(function(){

                var emailPattern = /^[-\w\.]+@([-\w\.]+\.)[-\w]{2,4}$/;
                var postcodePattern = /^\d{5}$/;
                var phonePattern = /^\+?[\d-\/ ]+$/;
                var numberPattern = /^[\d]+$/;
                var textPattern = /^[-'\sa-zA-Z\u0400-\u04FF\u00C0-\u02AF]+$/;

                var inputValid = false;

                if( !settings.enableValidColors ){
                    settings.validInputFontColor = $(this).data('validInputFontColor');
                    settings.validInputBorderColor = $(this).data('validInputBorderColor');
                }

                $(this).css({
                    borderColor:settings.validInputBorderColor, 
                    color:settings.validInputFontColor
                });

                $(this).next('.custom-combobox').css({
                    borderColor:settings.validInputBorderColor, 
                    color:settings.validInputFontColor
                });

                $(this).next('.custom-combobox').find('.cs-selected').css({
                    borderColor:settings.validInputBorderColor, 
                    color:settings.validInputFontColor
                });

                if( 
                    $(this).hasClass("required") && 
                    ( 
                        $(this).val()=='' || $(this).val()==null ||
                        ( 
                            $(this).val()==$(this).data("placeholder") && 
                            settings.placeholders 
                            )
                        )    
                    ){
                    settings.errorMessage = "Required fields can not be empty";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("email") && !emailPattern.test($(this).val()) ){
                    settings.errorMessage = "Email is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("phone") && !phonePattern.test($(this).val()) ){
                    settings.errorMessage = "Phone is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("postcode") && !postcodePattern.test($(this).val()) ){
                    settings.errorMessage = "Postcode is not valid";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("number") && !numberPattern.test($(this).val()) ){
                    settings.errorMessage = "Only numbers allowed";
                    valid = false;
                }
                else if( $(this).val()!='' && $(this).hasClass("text") && !textPattern.test($(this).val()) ){
                    settings.errorMessage = "Only letters allowed";
                    valid = false;
                }
                else if( $(this).hasClass("terms") && !$(this).prop('checked') ){
                    settings.errorMessage = "You have to accept Terms and Conditions to continue";
                    valid = false;
                }
                else if( $(this).hasClass('repeat_password') && form.find("input[name='password']").val() != $(this).val() ){
                    $("input[name='password'], input[name='repeat_password']").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Passwords must match";
                    valid = false;
                }
                else if( $(this).hasClass('repeat_email') && form.find(".email").val().toLowerCase() != $(this).val().toLowerCase() ){
                    $("input[name='email'], input[name='repeat_email']").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Emails must match";
                    valid = false;
                }
                else{
                    inputValid = true;
                }

                if (!inputValid) {
                    $(this).css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });

                    $(this).next('.custom-combobox').css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });

                    $(this).next('.custom-combobox').find('.cs-selected').css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });
                }

            });
            
            if( valid ){

                inputs.each(function(){
                    if( $(this).val() == $(this).data('placeholder') && settings.placeholders ){
                        $(this).val("");
                    }
                });
               
                settings.onValidForm(e);
                return settings.autoSubmit ? true : false;
                
            }
            else{
                if (showAlert) {
                    alert(settings.errorMessage);   
                }

                return false;
            }
        }
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