/*  
    ScrollTo jQuery Plugin
    Tirien.com
    $Rev: 1 $
    
    To initiate within markup use:
    <a href="#contact" data-scrollto>...</a>
    or
    <button data-scrollto="#contact"/>

    There are also optional:
    data-scrollto-offset="100" - scroll to element position + offset value
    data-scrollto-duration="1000" - animation duration
    data-scrollto-activestate-offset="100" - links will get class="active" when element is reached + offset value

    You can use anchors in href, and IDs and Classes in data-scrollto attribute.
*/

(function($) {

    $.tScrollToElement = function(element, offset, duration){
        if ($('body').hasClass('scrolling')) {
            return false;
        }
        
        if (offset == undefined) {
            offset = 0;
        }

        if (duration == undefined) {
            duration = 1000;
        }

        $('body').addClass('scrolling');

        $('html, body').animate({
            scrollTop: $(element).offset().top + offset
        }, duration, function(){
            $('body').removeClass('scrolling');
        });
    }

    $(document).ready(function() {
        $.fn.reverse = [].reverse;

        $("body").delegate("[data-scrollto]", "click", function(e) {
            e.preventDefault();

            var where = $(this).data('scrollto') || $(this).attr('href');
            var offset = $(this).data('scrollto-offset') || 0;
            var duration = $(this).data('scrollto-duration') || 1000;

            if (!$(where).length) {
                console.log(where + " element doesn't exist");
                return false;
            }

            $.tScrollToElement(where, offset, duration);

            $(this).addClass('active');
            $("[data-scrollto]").not($(this)).removeClass('active');
        });
    });

    $(window).scroll(function(){
        if ($('body').hasClass('scrolling')) return false;

        $("[data-scrollto]").reverse().each(function(){
            var where = $(this).data('scrollto') || $(this).attr('href');
            var offset = $(this).data('scrollto-activestate-offset') || 0;

            if (!$(where).length) {
                console.log(where + " element doesn't exist");
                return false;
            }

            if ($(window).scrollTop() > ($(where).offset().top + offset)) {
                $(this).addClass('active');
                $("[data-scrollto]").not($(this)).removeClass('active');
                return false;
            }
            else{
                $(this).removeClass('active');
            }
        })
    });

})(jQuery);
