/*  
	Tooltips jQuery Plugin
	Tirien.com
	$Rev$
	
	tooltipsData = [ 
		{ x:10, y:50, content:'text'" }, 
		{ x:20, y:50, content:'<b>html bold</b>', className:"red" }, 
		{ x:20, y:50, content:'<i>html italic</i>', class:"yellow under" } 
	]
	
	drawTooltips(".tooltipsWrap", tooltipsData);
*/

function drawTooltips( wrapSelector, data ){

	$(wrapSelector).css("position", "relative");
	
	for (i = 0; i < data.length; i++) {
		className = typeof(data[i].className)=="undefined" ? "" : data[i].className;
		$(wrapSelector).append('<div class="tooltipPoint ' + className + '" style="left:' + data[i].x + '%; top:' + data[i].y + '%;" ><div style="position:relative;z-index:100;"><div class="tooltipBox">' + data[i].content + '</div></div></div>')
	}
	
	if( $(wrapSelector).find("img").length > 0 ){
		$(wrapSelector).find("img").load(function(){
			$(".tooltipPoint").fadeIn("slow");
		});		
	}
	else{
		$(".tooltipPoint").fadeIn("slow");
	}
	
	$(".tooltipPoint").hover( 
		function(){
			$(this).find(".tooltipBox").stop(true, true).fadeToggle();
		}
	);
}