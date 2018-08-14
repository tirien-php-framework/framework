/*  
    ScrollTo jQuery Plugin
    Tirien.com
    
    To initiate within markup use:
    <a href="#contact" data-scrollto>...</a>
    or
    <button data-scrollto="#contact"/> (use only IDs)

    There are also optional:
    data-scrollto-offset="100" - scroll to element position + offset value
    data-scrollto-duration="1000" - animation duration
    data-scrollto-activestate-offset="100" - links will get class="active" when element is reached + offset value
    data-scrollto-wrapper=".scrolling-wrap" - define what will be scrolled. Default is "html,body"

    You can use anchors in href, and IDs and Classes in data-scrollto attribute.

    #TODO - if last section is hitting the bottom of page and it's smaller than window height, it should be set as active
*/

(function($) {

    $.tScrollToElement = function(selector, offset, duration, wrapper){
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

        $(wrapper).animate({
            scrollTop: $(selector).offset().top + offset
        }, duration, function(){
            $('body').removeClass('scrolling');
            history.pushState({}, '', selector);
        });
    }

    $(document).ready(function() {
        $.fn.reverse = [].reverse;

        $("body").delegate('[data-scrollto]', 'click', function(e) {
            e.preventDefault();

            var where = $(this).data('scrollto') || $(this).attr('href');
            var offset = $(this).data('scrollto-offset') || 0;
            var duration = $(this).data('scrollto-duration') || 1000;
            var wrapper = $(this).data('scrollto-wrapper') || 'html, body';

            if (!$(where).length) {
                console.log(where + " element doesn't exist");
                return false;
            }

            $.tScrollToElement(where, offset, duration, wrapper);

            $(this).addClass('active');
            $("[data-scrollto]").not($(this)).removeClass('active');
        });
    });

    $(window).scroll(function(){
        if ($('body').hasClass('scrolling')) {
            return false;
        }

        var sections = $("[data-scrollto]").map(function(){
            return $(this).attr("href");
        }).toArray();

        $(sections.join(',')).reverse().each(function(){
            var href = "#" + $(this).attr('id');
            var offset = $(this).data('scrollto-activestate-offset') || 0;
            var links = $("[data-scrollto][href='"+href+"']");

            if ($(window).scrollTop() > ($(this).offset().top + offset)) {
                if (!links.filter(".active").length) {
                    history.pushState({}, '', href);
                }

                links.addClass('active');
                $("[data-scrollto]").not(links).removeClass('active');

                return false;
            }
            else{
                if (window.location.hash == href) {
                    history.pushState({}, '', '');
                }

                links.removeClass('active');
            }
        })
    });

})(jQuery);
