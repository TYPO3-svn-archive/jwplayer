var tx_jwplayer = {
	init:function(){
		if(typeof(tx_jwplayer_list)!="undefined"){
			for (var playerId in tx_jwplayer_list){
				var config = tx_jwplayer_list[playerId];
				jwplayer(playerId).setup(config);
			}
		}
	},
	clickTracking:function(player){
		// webtrekk click tracking if it exists
		if (typeof wt_sendinfo == 'function') {
			var filename = player.config.file;
			filename = filename.substring(filename.lastIndexOf('/')+1);
			var clickname = window.webtrekk.contentId + '.movie.'+filename;
			wt_sendinfo(clickname,'click');
		}
	},
	checkAvailableType:function() {
		var v = document.createElement('video');
		var vtype = new Array();
   
   		if(!!(v.canPlayType && v.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/no/, '')))
	    	vtype.push('webm');
		if(!!(v.canPlayType && v.canPlayType('video/ogg; codecs="theora, vorbis"').replace(/no/, '')))
			vtype.push('ogv');
	   	if(!!(v.canPlayType && v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"').replace(/no/, '')))
	    	vtype.push('mp4');	   	
	    	
	    if(vtype.length == 0) return false;
	 	   
	   	return vtype;
	}
}

if(typeof jQuery != 'function'){
	$(function(){
		tx_jwplayer.init();
	});
}else{
	tx_jwplayer.init();
}
