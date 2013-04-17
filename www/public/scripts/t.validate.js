/*  
    Validate jQuery Plugin
    Tirien.com
    $Rev: 78 $
    
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
                if( $(this).val()=='' || ( $(this).val()==$(this).data("placeholder") && settings.placeholders ) ){
                    $(this).css({borderColor:settings.errorInputBorderColor, color:settings.errorInputFontColor});
                    valid = false;
                }
            });
            
            if( valid ){
                return true;
            }
            else{
                alert("Required fields can not be empty");
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