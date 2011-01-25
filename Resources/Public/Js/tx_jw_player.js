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
	}
}

if(typeof jQuery != 'function'){
	$(function(){
		tx_jwplayer.init();
	});
}else{
	tx_jwplayer.init();
}
