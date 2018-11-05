Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function empty(v){
	return !v || typeof v == 'undefined' || ( typeof v == 'string' && v.replace(/[0| ]+/gm,'')==='' )	|| ( typeof v == 'object' && !Object.size(v) );
}

var _isMacLike = navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i)?true:false;
var _isIOS = navigator.platform.match(/(iPhone|iPod|iPad)/i)?true:false;