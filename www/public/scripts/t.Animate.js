/** 
*   Scroll Animation jQuery Plugin
*   Tirien.com
*   $Rev: 33 $
*   
*   Initialize
*   tAnimate.init(animation_wrap_selector, screen_height_multiplier);
*   
*   Add tweens
*   tAnimate.tween(selector, startPercent, endPercent, startCss, endCss);
*   
*   NOTE: for desktop use CSS transitions to simulate inertia
*/

tAnimate = new function (){

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
			'selector':selector, 
			'startPercent':startPercent, 
			'endPercent':endPercent, 
			'startCss':startCss, 
			'endCss': typeof endCss == "undefined" ? null : endCss
		});

		this.refresh();

	}

	this.refresh = function() {
	
		tAnimate.progress = _mobile ? $(".animation-wrap").scrollTop() / Math.abs( $(".animation").height() - $(".animation-wrap").height() ) : $(window).scrollTop() / Math.abs( $(document).height() - window.innerHeight );

		// console.dir(tAnimate.progress);

		for (var i = 0; i < tAnimate.tweens.length; i++) {

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

								case "top" || "bottom" || "height":
								startCss = window.innerHeight * startCss.replace(/%$/,'') / 100;
									break;
								
								case "left" || "right" || "width":
								startCss = window.innerWidth * startCss.replace(/%$/,'') / 100;
									break;
								
							}
						}

						if( /px$/.test(startCss) ){
							startCss = startCss.replace(/px$/,'')
						}

						if( /%$/.test(endCss) ){

							switch(rule){

								case "top" || "bottom" || "height":
								endCss = window.innerHeight * endCss.replace(/%$/,'') / 100;
									break;
								
								case "left" || "right" || "width":
								endCss = window.innerWidth * endCss.replace(/%$/,'') / 100;
									break;
								
							}

						}

						if( /px$/.test(endCss) ){
							endCss = endCss.replace(/px$/,'')
						}

						newValue = startCss + (endCss - startCss) * tweenProgress;

						$(tAnimate.tweens[i].selector).css(rule,newValue);

					}
					
				}

			}

		}
	}

}


if(_mobile){
	$(window).on('touchmove', function(event) {
		tAnimate.refresh();
	})
}
else{
	$(window).on("scroll", tAnimate.refresh);
}