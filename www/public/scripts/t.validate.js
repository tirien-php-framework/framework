/*  
    Validate jQuery Plugin
    Tirien.com
    $Rev$
    
    Use `data-tvalidate-required` or just native 'required' attribute on inputs that are mandatory and class `data-tvalidate-email` to validate email.
    
    This is optional:
    options = {
        activeColor: 'white',
        inactiveColor: 'white'
    };
    
    Use `data-empty` attribute in select's option in order mark that one as empty on validation. This allows empty values for other options in select.
    
    To initiate with JS use:
    $("#contact-form").tValidate(options);
    
    To initiate within markup use:
    <form data-tvalidate data-tvalidate-options='{"activeColor":"yellow"}'>

    Options are optional.
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

        // define event handler for future use like $('#contact-form').data('tValidate').submitEventHandler
        this.submitEventHandler = form.on('submit', function(e){
            submitedBefore = true;
            return validate(true);
        });

        var validate = function(showAlert){
            var validForm = true;
            var form = $(this);

            inputs.each(function(){
                var emailPattern = /^[-\w\.]+@([-\w\.]+\.)[-\w]{2,4}$/;
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

                if( 
                    (
                        this.hasAttribute("required") || 
                        this.hasAttribute("data-tvalidate-required")
                    )
                    && 
                    ( 
                        $(this).val()=='' || 
                        $(this).val()==null ||
                        !!$(this).find('option[data-empty]:selected').length ||
                        ( 
                            $(this).val()==$(this).data("placeholder") && 
                            settings.placeholders 
                            )
                        )    
                    ){
                        if (!$(this).find('option[data-empty]').length || $(this).val()!='') {
                    settings.errorMessage = "Required fields can not be empty";
                            validInput = false;
                        }
                }
                else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-email") && !emailPattern.test($(this).val()) ){
                    settings.errorMessage = "Email is not valid";
                    validInput = false;
                }
                else if( this.hasAttribute('data-repeat-email') && form.find(".email").val().toLowerCase() != $(this).val().toLowerCase() ){
                    $("input[name='email'], input[name='repeat_email']").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Emails must match";
                    validInput = false;
                }
                else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-phone") && !phonePattern.test($(this).val()) ){
                    settings.errorMessage = "Phone is not valid";
                    validInput = false;
                }
                else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-postcode") && !postcodePattern.test($(this).val()) ){
                    settings.errorMessage = "Postcode is not valid";
                    validInput = false;
                }
                else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-number") && !numberPattern.test($(this).val()) ){
                    settings.errorMessage = "Only numbers allowed";
                    validInput = false;
                }
                else if( $(this).val()!='' && this.hasAttribute("data-tvalidate-text") && !textPattern.test($(this).val()) ){
                    settings.errorMessage = "Only letters allowed";
                    validInput = false;
                }
                else if( this.hasAttribute("data-tvalidate-terms") && !$(this).prop('checked') ){
                    settings.errorMessage = "You have to accept Terms and Conditions to continue";
                    validInput = false;
                }
                else if( this.hasAttribute('data-repeat-password') && form.find("input[name='password']").val() != $(this).val() ){
                    $("input[name='password'], input[name='repeat_password']").css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    settings.errorMessage = "Passwords must match";
                    validInput = false;
                }

                if (!validInput) {
                    validForm = false;
                    
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

    $(function(){
        $('form[data-tvalidate]').tValidate();
    })

})(jQuery);