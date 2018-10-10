/*  
    Validate jQuery Plugin
    Tirien.com
    
    Use `data-tvalidate-required` or just native 'required' attribute on inputs that are mandatory and class `data-tvalidate-email` to validate email. 

    `data-tvalidate-email` is set by default for `type=email"` that have `required` attribute
    'data-tvalidate-email' validate email 
    'data-tvalidate-repeat-email' validate confirmed email 
    'data-tvalidate-phone' validate phone 
    'data-tvalidate-postcode' validate postcode 
    'data-tvalidate-number' validate number 
    'data-tvalidate-text' validate text 
    'data-tvalidate-terms' validate terms 
    'data-tvalidate-password' validate password 
    'data-tvalidate-repeat-password' validate confirmed password 
    
    This is optional:
    options = {
        activeColor: 'white',
        inactiveColor: 'white'
    };
    
    Use `data-empty` attribute in select's option in order to mark that option as empty on validation. This allows empty values for other options in select.
    
    To initiate with JS use:
    $("#contact-form").tValidate(options);
    
    To initiate within markup use:
    <form data-tvalidate data-tvalidate-options='{"activeColor":"yellow"}'>

    Options are not mandatory. 
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
            showAlert: true,
            onValidForm: function(){}
        }

        var form = $(element);
        var inputs = form.find("input,textarea,select").not("[type='submit']");
        var submitedBefore = false;
        var inlineOptions = form.data('tvalidate-options');

        var settings = this.settings = $.extend({}, defaultSettings, inlineOptions, options);

        element.setAttribute("novalidate", true);

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

        // Define event handler for future use. Get it like $('#contact-form').data('tValidate').submitEventHandler
        // or use namespace to remove the event - $form.off('submit.tValidate')
        this.submitEventHandler = form.on('submit.tValidate', function(e){
            submitedBefore = true;
            return validate(settings.showAlert);
        });

        var validate = function(showAlert){
            var validForm = true;
            var errorMessages = [];

            inputs.each(function(){
                var emailPattern = /^[-\w\.\+]+@([-\w\.]+\.)[-\w]{2,4}$/;
                var postcodePattern = /^\d{5}$/;
                var phonePattern = /^\+?[\d-\/ ]+$/;
                var numberPattern = /^[\d]+$/;
                var textPattern = /^[-'\sa-zA-Z\u0400-\u04FF\u00C0-\u02AF]+$/;

                var validInput = true;

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
                
                $("[type='email'][required]").attr("data-tvalidate-email", "");

                if(this.hasAttribute("required") || $(this).is("[data-tvalidate-required], [data-tvalidate-email], [data-tvalidate-email], [data-tvalidate-repeat-email], [data-tvalidate-phone], [data-tvalidate-postcode], [data-tvalidate-number], [data-tvalidate-text], [data-tvalidate-terms], [data-tvalidate-password], [data-tvalidate-repeat-password]")) {
                    if($(this).val()=='' || $(this).val()==null || ($(this).val()==$(this).data("placeholder") && settings.placeholders)){
                        errorMessages.push("Required fields can not be empty");
                        validInput = false;
                    }
                    else if (!!$(this).find('option[data-empty]:selected').length) {
                        errorMessages.push("You have to select one option");
                        validInput = false;
                    }                    
                    else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-email") && !emailPattern.test($(this).val()) ){
                        errorMessages.push("Email is not valid");
                        validInput = false;
                    }
                    else if( this.hasAttribute('data-tvalidate-repeat-email') && form.find("[data-tvalidate-repeat-email]").val().toLowerCase() != form.find("[data-tvalidate-email]").val().toLowerCase() ){
                        form.find("[data-tvalidate-repeat-email], [data-tvalidate-email]").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                        errorMessages.push("Emails must match");
                        validInput = false;
                    }
                    else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-phone") && !phonePattern.test($(this).val()) ){
                        errorMessages.push("Phone is not valid");
                        validInput = false;
                    }
                    else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-postcode") && !postcodePattern.test($(this).val()) ){
                        errorMessages.push("Postcode is not valid");
                        validInput = false;
                    }
                    else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-number") && !numberPattern.test($(this).val()) ){
                        errorMessages.push("Only numbers allowed");
                        validInput = false;
                    }
                    else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-text") && !textPattern.test($(this).val()) ){
                        errorMessages.push("Only letters allowed");
                        validInput = false;
                    }
                    else if( this.hasAttribute('data-tvalidate-repeat-password') && form.find("[data-tvalidate-repeat-password]").val() != form.find("[data-tvalidate-password]").val() ){
                        form.find("[data-tvalidate-repeat-password], [data-tvalidate-password]").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                        errorMessages.push("Passwords must match");
                        validInput = false;
                    }
                    else if( this.hasAttribute("data-tvalidate-terms") && !$(this).prop('checked') ){
                        errorMessages.push("You have to accept Terms and Conditions to continue");
                        validInput = false;
                    }
                }

                if (!validInput) {
                    validForm = false;
                    
                    $(this).css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });

                    $(this).parents('.custom-combobox').css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });

                    $(this).parents('.custom-combobox').find('.cs-selected').css({
                        borderColor:settings.errorInputBorderColor, 
                        color:settings.errorInputFontColor
                    });
                }

            });

            if( validForm ){
                inputs.each(function(){
                    if( $(this).val() == $(this).data('placeholder') && settings.placeholders ){
                        $(this).val("");
                    }
                });
               
                settings.onValidForm();

                return settings.autoSubmit ? true : false;
            }
            else{
                if (showAlert) {
                    alert(errorMessages[0]);   
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

    $(function(){
        $('form[data-tvalidate]').tValidate();
    })

})(jQuery);
