function tx_jwplayer_init(){
	if(typeof(tx_jwplayer_list)!="undefined"){
		for (var playerId in tx_jwplayer_list){
			var config = tx_jwplayer_list[playerId];
			jwplayer(playerId).setup(config);
		}
	}
}
if(typeof jQuery != 'function'){
	$(function(){
		tx_jwplayer_init();
	});
}else{
	tx_jwplayer_init();
}
