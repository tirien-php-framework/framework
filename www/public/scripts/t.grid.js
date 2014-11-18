/* 
*   Grid jQuery Plugin
*   Tirien.com
*   $Rev$
*/

function tGrid(ops){
    var defaults = {
        grid: '.grid',
        colSpacing: 0,
        rowSpacing: 0,
        startTop: 0,
        startLeft: 0
    }
    var options = {};
    var grid, elements;
    var elementWidth, gridWidth;
    this.refresh = function(){
        arange();
    }
    var setVars = function(){
        elementWidth = elements.first().outerWidth(true);
        gridWidth = grid.width();
    }
    var findElements = function(){
        grid = $(options.grid);
        elements = grid.children();
    }
    var setStyle = function(){
        grid.css({
            position: 'relative'
        });
        elements.css({
            position:'absolute', 
            visibility: 'hidden',
            display: 'block'
        });
    }
    var arange = function(){
        setVars();
        var colsPerRow = Math.floor(gridWidth / elementWidth);
        var lasts = [];
        elements.each(function(ind){
            var t = $(this).css({
                visibility: 'visible'
            });

            if (ind < colsPerRow)
            {
                //prvi red redjaj redom
                if (ind){
                    //ako je prvi element u drugom, trecem... redu
                    var prev = t.prev();
                    lasts.push(t.css({
                        top: 0,
                        left: prev.position().left + prev.outerWidth(true) + options.colSpacing
                    }));
                }
                else{
                    //ako je prvi element u prvom redu
                    lasts.push(t.css({
                        top: options.startTop, 
                        left: options.startLeft
                    }));
                }
            }
            else{
                var minPos, minInd;
                $(lasts).each(function(ind,el){
                    var l = $(el);
                    if (ind){
                        var thisMin = l.position().top + l.outerHeight(true) + options.rowSpacing;
                        if (thisMin < minPos){
                            minPos = thisMin;
                            minInd = ind;
                        }
                    }
                    else{
                        //prvi je najmanji
                        minPos = l.position().top + l.outerHeight(true) + options.rowSpacing;
                        minInd = ind;
                    }
                });
                var minEl = $(lasts.splice(minInd, 1, t)[0]); //izbaci stari element i stavi novi;
                t.css({
                    top: minEl.position().top + minEl.outerHeight(true), 
                    left: minEl.position().left
                });
            }

            t.addClass("gridded")
        });
        var maxPos = 0;
        $(lasts).each(function(ind,el){
            var l = $(el);
            var thisMax = l.position().top + l.outerHeight(true);
            if (thisMax > maxPos){
                maxPos = thisMax;
            }
        });
        grid.css({
            height: maxPos
        });
    }
    var init = function(){
        findElements();
        
        setStyle();
        arange();
    }
    options = $.extend({}, defaults, ops);
    init();
}