/*  
    Paralax jQuery Plugin
    Tirien.com
    $Rev: 1 $
    
    To initiate within markup use:
    <div data-parallax-distance='20'>...</div>

    Distance can be 0.5 or above. The greater distance, the objects move slower.
*/

(function($) {

    $.tParallax = function(index, element) {
        var offset = $(element).offset().top - $(window).height()/2 - $(window).scrollTop();
        var distance = $(element).data('parallax-distance');

        $(element).css({
            'transform' : "translateY(" + offset/distance + "px)"
        });
    }

    $(document).ready(function() {
        $('[data-parallax-distance]').each(function(){
            if ($(this).css('transform') != 'none') {
                console.dir("Parallax element can't have transform defined. Please, add wrapper and use tParallax on that wrapper.")
                return false;
            }

            if ($(this).css('transition-duration') != '0s') {
                console.dir("Parallax element can't have transition duration defined. Please, add wrapper and use tParallax on that wrapper.")
                return false;
            }

            if ($(this).data('transition-duration') < 1) {
                console.dir("Parallax element must have distance at least 1.")
                return false;
            }

            $(this).attr('data-tparallax-allowed', '');
        });
    });

    $(window).scroll(function(){
        $('[data-tparallax-allowed]').each($.tParallax);
    });

})(jQuery);