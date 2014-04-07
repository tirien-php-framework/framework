function empty(v){
	return !v || typeof v == 'undefined' || ( typeof v == 'string' && v.replace(/[0| ]+/gm,'')=='' )	|| ( typeof v == 'object' && !Object.keys(v).length );
}