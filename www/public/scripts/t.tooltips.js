/* 
	Tooltips Script
	Tirien.com
	
	tooltipsData = [ 
		{ x:10, y:50, content:"text" }, 
		{ x:20, y:50, content:"<b>html bold</b>" }, 
		{ x:20, y:50, content:"<i>html italic</i>" } 
	]
	
	drawTooltips(".tooltipsWrap", tooltipsData);
*/

function drawTooltips( wrapSelector, data ){

	$(wrapSelector).css("position", "relative");
	
	for (i = 0; i < data.length; i++) {
		$(wrapSelector).append('<div class="tooltipPoint" style="left:' + data[i].x + '%; top:' + data[i].y + '%;" ><div style="position:relative;z-index:100;"><div class="tooltipBox">' + data[i].content + '</div></div></div>')
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