/* 
*   Scale Images jQuery Plugin
*   Tirien.com
*   $Rev$
*/

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

        if (settings.center){
            el.css({
                position:'absolute',
                left: '50%',
                marginLeft: -el.width() / 2,
                top: '50%',
                marginTop: -el.height() / 2,
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
    $('.scale').scale();
    $('.scale-f').scale({
        type: 'fit'
    });
});
$(window).resize(function(){
    $('.scale').scale();
    $('.scale-f').scale({
        type: 'fit'
    });
});