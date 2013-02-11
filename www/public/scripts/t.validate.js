/*  
    Validate jQuery Plugin
    Tirien.com
    $Rev: 78 $
    
    $("#contact-form").tValidate();
*/

(function($) {
    $.tValidate = function(element, options) {
        var settings = {
            activeColor: 'black',
            inactiveColor: 'lightgray',
            requiredColor: 'red'
        }

        settings = $.extend({}, settings, options);
        form = $(element);
        inputs = form.find("input,textarea");

        // placeholders
        inputs.each(function(){
            if( typeof( $(this).data('placeholder') ) == "undefined" ){
                $(this).data('placeholder', $(this).val());
            }
        });

        inputs.css('color', settings.inactiveColor).focus(function(){
            if( $(this).val() == $(this).data('placeholder') ){
                $(this).val('');
                $(this).css('color', settings.activeColor);
            }
        }).blur(function(){
            if( $(this).val()=='' ){
                $(this).css('color', settings.inactiveColor);
                $(this).val( $(this).data('placeholder') );
            }
        });

        // validation
        form.submit(function(){
            var valid = true;

            inputs.filter(".required").each(function(){
                if( $(this).val()=='' || $(this).val()==$(this).data("placeholder") ){
                    $(this).css({borderColor:settings.requiredColor, color:settings.requiredColor});
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