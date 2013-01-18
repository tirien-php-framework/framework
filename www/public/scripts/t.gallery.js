/* 
	Gallery jQuery Plugin
	Tirien.com
	$Rev$
*/

(function($) {
    $.tGallery = function(element, options) {
        var defaults = {
            speed : 900,
            duration : 5000,
            goToImage : '.goto-image',
            nextImage : '.next-image',
            prevImage : '.prev-image',
            caption : '.image-caption',
            startImage: 0,
            imageSelector: 'img',
            imageWrapper: null,
            autoPlay : true,
            lockSize: true,
            beforeChange: function(){},
            afterChange: function(){},
            beforeAnimation: function(){},
            onInit: function(){}
        }

        var plugin = this;

        plugin.settings = {}

        var $element = $(element),
             element = element;

        var imageWrapper,
            images,
            firstImage;
        var activeImage;
        var i;
        var timer;
        var captionField;

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

            findElements();      //vraca sve potrebne elemente
            applyInitSettings(); //postavlja pocetne stilove
            bindEvents();        //binduje eventove za goto next i prev image
            activeImage.show();
            if (plugin.settings.autoPlay)
            {
                run();
            }
            plugin.settings.onInit(images, i);
        }



        plugin.startPlaying = function() {
            clearTimeout(timer);
            run();
        }
        plugin.stopPlaying = function() {
            clearTimeout(timer);
        }
        plugin.refresh = function(){
            clearTimeout(timer);
            plugin.init();
        }
        var findElements = function() {
            images = $element.find(plugin.settings.imageSelector);
            i = plugin.settings.startImage;
            activeImage = firstImage = $(images[i]);

            if (plugin.settings.imageWrapper === null)
            {
                imageWrapper = firstImage.parent();
            }
            else{
                imageWrapper = $element.find(plugin.settings.imageWrapper);
            }
            captionField = $element.find(plugin.settings.caption);
        }
        var applyInitSettings = function() {
            images.filter(':not(:eq(0))').css({
                position : 'absolute',
                left : 0,
                top : 0,
                display : 'none'
            });

            $element.find(plugin.settings.goToImage).filter('[data-n=0]').addClass('active');
            var pos = imageWrapper.css('position');
            if (pos=='static'){
                if (plugin.settings.lockSize){
                    imageWrapper.css({width: imageWrapper.width(), height: imageWrapper.height()});
                    images.first().css({position: 'absolute'});
                }
                imageWrapper.css({position : 'relative'});
            }
            if (captionField){
                captionField.html(activeImage.data('caption'));
            }
        }
        var run = function() {
            timer = setTimeout(nextImage, plugin.settings.duration);
        }
        var bindEvents = function() {
            $element.find(plugin.settings.goToImage).off('click.tgallery').on('click.tgallery', function(e){
                e.preventDefault();
                goTo($(this).data('n'));
            });
            $element.find(plugin.settings.nextImage).off('click.tgallery').on('click.tgallery', function(e) {
                e.preventDefault();
                nextImage();
            });
            $element.find(plugin.settings.prevImage).off('click.tgallery').on('click.tgallery', function(e) {
                e.preventDefault();
                prevImage();
            });

        }
        var goTo = function(imageNumber) {
            var next = $(images[imageNumber]);
            if(next.length) {
                clearTimeout(timer);
                timer = setTimeout(nextImage, plugin.settings.duration);
                i = imageNumber;
                showImage(activeImage, next);
            }
        }
        var nextImage = function() {
            clearTimeout(timer);
            timer = setTimeout(nextImage, plugin.settings.duration);
            var next = $(images[++i]);
            if(!next.length) {
                i = 0
                next = $(images[i]);
            }
            showImage(activeImage, next, 'next');
        }
        var prevImage = function() {
            clearTimeout(timer);
            timer = setTimeout(prevImage, plugin.settings.duration);
            var prev = $(images[--i]);
            if(!prev.length) {
                i = images.length - 1;
                prev = $(images[i]);
            }
            showImage(activeImage, prev, 'prev');
        }
        var showImage = function(prev, next, direction) {
            if($(prev)[0] !== $(next)[0]) {
                if (plugin.settings.beforeChange(images, i)!==false){
                    if (captionField){
                        captionField.html(next.data('caption'));
                    }
                    $element.find(plugin.settings.goToImage).removeClass('active').filter('[data-n=' + next.index() + ']').addClass('active');
                    plugin.settings.beforeAnimation(images, i);
                    transitionFade(prev,next,direction, plugin.settings.afterChange(images, i));
                    activeImage = next;
                }
            }
        }
        var transitionFade = function(prev,next,direction,callback){
                    prev.fadeOut(plugin.settings.speed);
                    next.fadeIn(plugin.settings.speed, callback );
        }
        var transitionSlide = function(prev,next,direction,callback){
            prev.css({zIndex: 5});
            var left = direction === 'next';
            next.css({zIndex: 10, left: left ? 100 : -100}).show().animate({left: 0},250,callback);
        }
        plugin.nextImage = function() {
            nextImage();
        }
        plugin.prevImage = function() {
            prevImage();
        }
        plugin.showImage = function(next) {
            if (typeof next !== 'undefined')
            {
                showImage(activeImage, next);

            }
        }
        plugin.init();

    }

    $.fn.tGallery = function(options) {

        return this.each(function() {
            if (undefined == $(this).data('tGallery')) {
                var plugin = new $.tGallery(this, options);
                $(this).data('tGallery', plugin);
            }
        });

    }

})(jQuery);