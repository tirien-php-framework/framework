/* 
	Scale Images Script
	Tirien.com
	$Rev$
*/

$.fn.scale = function(ops){
    function Scale(el){
        var parent;
        if (typeof settings.parent !== 'undefined'){
            parent = settings.parent;
        }
        else{
            parent = el.parent();
        }
        var parentWidth = parent.width();
        var parentHeight = parent.height();
        var tmpImg = new Image();
        tmpImg.onload = function(){
            var elementWidth = tmpImg.width;
            var elementHeight = tmpImg.height;

            if (settings.type == 'center'){
                el.css({
                    position: 'absolute',
                    width: 'auto',
                    height: 'auto',
                    left: (parentWidth - elementWidth) /2,
                    top: (parentHeight - elementHeight)/2
                });
            }
            else{
                var b = (parentWidth / parentHeight) > (elementWidth / elementHeight);
                var f = settings.type == 'fill';
                if ( (b || !f) && (!b || f) )
                {
                    el.css({
                        width : '100%',
                        height : 'auto'
                    });
                    if (settings.center){
                        el.css({
                            position:'absolute',
                            left: 0,
                            top: (parentHeight - el.height()) / 2
                        });
                    }
                }
                else
                {
                    el.css({
                        width : 'auto',
                        height : '100%'
                    });
                    if (settings.center){
                        el.css({
                            position:'absolute',
                            left: (parentWidth - el.width()) / 2,
                            top: 0
                        });
                    }
                }
            }
        };
        tmpImg.src = el.prop('src');
        return;
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