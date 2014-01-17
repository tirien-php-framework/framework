/* 
*   Scale Images jQuery Plugin
*   Tirien.com
*   $Rev$
*/

(function($){

$.fn.scale = function(ops){

    function Scale(el){

        var parent, elementWidth, elementHeight, elementRatio;

        if (typeof settings.parent !== 'undefined'){
            parent = settings.parent;
        }
        else{
            parent = el.parent();
        }

        var parentWidth = parent.width();
        var parentHeight = parent.height();

        hiddenParents = el.parents(':hidden');
        hiddenParents.show();

        if( el.data("original-width")!=undefined && el.data("original-height")!=undefined ){
            elementWidth = el.data("original-width");
            elementHeight = el.data("original-height");
        }
        else{
            elementWidth = el.width();
            elementHeight = el.height();   
            el.data("original-width", elementWidth);
            el.data("original-height", elementHeight);
        }

        if (elementWidth==0 || elementHeight==0 || parentWidth==0 || parentHeight==0) {
            return false;
        }
        
        hiddenParents.hide();

        elementRatio = elementWidth/elementHeight;
        
        var b = (parentWidth / parentHeight) > (elementWidth / elementHeight);
        var f = settings.type == 'fit';

        if ( (b && !f) || (!b && f) )
        {
            el.css({
                width : '100%',
                height : Math.round( parentWidth / elementRatio )
            });
        }
        else
        {
            el.css({
                width : Math.round( parentHeight * elementRatio ),
                height : '100%'
            });

        }

        hiddenParents.show();
        elementWidth = el.width();
        elementHeight = el.height();
        hiddenParents.hide();


        if (settings.center){
            el.css({
                position:'absolute',
                left: '50%',
                marginLeft: - elementWidth / 2,
                top: '50%',
                marginTop: - elementHeight / 2,
            });
        }       

        if(f){
            el.removeClass("scale").addClass('scale-f scale-done');
        }
        else{
            el.removeClass("scale-f").addClass('scale scale-done');
        }

        return true;
    }

    var settings = $.extend( {
        type: 'fill',
        attachEvents: 'true',
        center: true
    }, ops);

    if (!this.length){
        return this;
    }

    this.each(function(ind,el){
        Scale($(el));
    });

    return this;

};

$(window).load(function(){
    $('img.scale,img.scale-f').load(function(){
        $(this).filter('.scale').scale();
        $(this).filter('.scale-f').scale({
            type: 'fit'
        });
        
    }).each(function(index, el) {
       if ( el.complete ) $(el).load(); 
    });

    $('.scale').not('img').scale();
    $('.scale-f').not('img').scale({
        type: 'fit'
    });
});
$(window).resize(function(){
    $('.scale').scale();
    $('.scale-f').scale({
        type: 'fit'
    });
});

})(jQuery);