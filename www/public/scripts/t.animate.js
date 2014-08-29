/** 
*   Scroll Animation jQuery Plugin
*   Tirien.com
*   $Rev: 33 $
*   
*   NOTE: for desktop use CSS transitions to simulate inertia (disable this for mobile and MacOS), i.e.:
*   .tween, body
*   {
*   	-webkit-transition: all 600ms;
*   	-moz-transition: all 600ms;
*   	-o-transition: all 600ms;
*   	transition: all 600ms;
*   }
*   	
*/

var tAnimate = new function (){

	this.progress = 0;
	this.tweens = [];

	this.init = function(animation, m){

		m = typeof m == "undefined" ? m = 10 : m;
		$(animation).height( window.innerHeight * m );

		if(_mobile){
		    
			$(animation).wrap( '<div class="animation-wrap">' );

			$(".animation-wrap").css({
				position: "fixed",
				width: "100%",
				height: "100%",
				overflow: "scroll"
			});

		}

	}

	this.tween = function(selector, startPercent, endPercent, startCss, endCss){

		startPercent = startPercent/100;
		endPercent = endPercent/100;

		this.tweens.push({
			'selector': selector, 
			'startPercent': startPercent, 
			'endPercent': endPercent, 
			'startCss': startCss, 
			'endCss': typeof endCss == "undefined" ? null : endCss
		});

		this.refresh();

	}

	this.refresh = function() {
	
		tAnimate.progress = _mobile ? $(".animation-wrap").scrollTop() / Math.abs( $(".animation").height() - $(".animation-wrap").height() ) : $(window).scrollTop() / Math.abs( $(document).height() - window.innerHeight );

		// console.dir(tAnimate.progress);

		for (var i = 0; i < tAnimate.tweens.length; i++) {

			$(tAnimate.tweens[i].selector).css("position","fixed");

			if( 
				tAnimate.tweens[i].startPercent <= tAnimate.progress && 
				tAnimate.tweens[i].endPercent >= tAnimate.progress 
				){

				if(tAnimate.tweens[i].endCss == null){

				    for (var rule in tAnimate.tweens[i].startCss) {
				    	if (tAnimate.tweens[i].startCss.hasOwnProperty(rule)) {
				    		$(tAnimate.tweens[i].selector).css(rule, tAnimate.tweens[i].startCss[rule]);
				    	}
				    }

				    continue;

				}

			    var tweenProgress = (tAnimate.progress - tAnimate.tweens[i].startPercent) / (tAnimate.tweens[i].endPercent - tAnimate.tweens[i].startPercent);

			    for (var rule in tAnimate.tweens[i].startCss) {

					if (tAnimate.tweens[i].startCss.hasOwnProperty(rule)) {

						hasPercent = /%$/.test(tAnimate.tweens[i].startCss[rule]);

						startCss = tAnimate.tweens[i].startCss[rule];
						endCss = tAnimate.tweens[i].endCss[rule];

						if( /%$/.test(startCss) ){

							switch(rule){

								case "top":
								case "bottom":
								case "height":
									startCss = window.innerHeight * startCss.replace(/%$/,'') / 100;
									break;


								case "left":
								case "right":
								case "width":
									startCss = window.innerWidth * startCss.replace(/%$/,'') / 100;
									break;
								
							}
						}

						if( /px$/.test(startCss) ){
							startCss = parseFloat(startCss.replace(/px$/,''));
						}

						if( /%$/.test(endCss) ){

							switch(rule){

								case "top":
								case "bottom":
								case "height":
								endCss = window.innerHeight * endCss.replace(/%$/,'') / 100;
									break;
								
								case "left":
								case "right":
								case "width":
								endCss = window.innerWidth * endCss.replace(/%$/,'') / 100;
									break;
								
							}

						}

						if( /px$/.test(endCss) ){
							endCss = parseFloat(endCss.replace(/px$/,''));
						}

						newValue = startCss + (endCss - startCss) * tweenProgress;

						$(tAnimate.tweens[i].selector).css(rule,newValue);

					}
					
				}

			}

		}
	}

	this.inertia = new function(){

		this.touches = [];
		this.redrawInterval;
		var speed, lastTouch, oldScrolltop, distance;
		var duration = 800; //ms
		var inertiaRatio = 1/20; // speed dependence of distance

		this.redraw = function(){

			var timePassed = typeof lastTouch == "undefined" ? new Date().getTime() : new Date().getTime() - lastTouch.timeStamp;

			newScrolltop = easeOutExpo(timePassed, oldScrolltop, distance, duration).toFixed();

			if(newScrolltop != oldScrolltop + distance){
				$(".animation-wrap").scrollTop(newScrolltop);
				tAnimate.refresh();
				return true;
			}
			else{
				clearInterval(tAnimate.inertia.redrawInterval);
				speed = lastTouch = oldScrolltop = distance = undefined;
				return false;
			}
			
		}		

		this.start = function(){

			if(tAnimate.inertia.redrawInterval){
				clearInterval(tAnimate.inertia.redrawInterval);
			}

			// calculate speed in px/s
			lastTouch = tAnimate.inertia.touches[tAnimate.inertia.touches.length-1];
			var beforeLastTouch = tAnimate.inertia.touches[tAnimate.inertia.touches.length-2];
			
			if(typeof beforeLastTouch == "undefined"){
			    return false;
			}

			speed = (lastTouch.pageY - beforeLastTouch.pageY)*1000/(lastTouch.timeStamp - beforeLastTouch.timeStamp);

			oldScrolltop = $(".animation-wrap").scrollTop();
			distance = -(speed * inertiaRatio).toFixed();

			// reset touches
			tAnimate.inertia.touches = [];
			tAnimate.inertia.redraw();

			tAnimate.inertia.redrawInterval = setInterval(tAnimate.inertia.redraw,20);
				
		}	

	}

	this.goTo = function(progressValue){
	
		if(_mobile){
		    $(".animation-wrap").scrollTop( progressValue * Math.abs( $(".animation").height() - $(".animation-wrap").height() ) );
		}
		else{
			$(window).scrollTop( progressValue * Math.abs( $(document).height() - window.innerHeight ) );
		}

		tAnimate.inertia.redraw();

	}



}

$(function(){

	if(_mobile){

		$(window).on('touchmove', function(event) {

			tAnimate.refresh();

			tAnimate.inertia.touches.push({
				pageY: event.originalEvent.changedTouches[0].pageY, 
				timeStamp: event.originalEvent.timeStamp 
			});
				
		})

		$(window).on('touchend touchcancel', function(event) {
			tAnimate.inertia.start();
		})

	}
	else{

		$(window).on("scroll", tAnimate.refresh);

	}
	
})

function easeOutExpo(t, b, c, d) {
	// t: current time, b: begining value, c: change value, d: duration
	return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
}