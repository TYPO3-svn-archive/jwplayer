<?php
/**
 * Player 
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_PlayerController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * @return string
	 */
	public function indexAction(){
		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsLibrary ( 'jwplayer', $file, 'text/javascript',TRUE ,TRUE);
		$file = $extPath . 'Resources/Public/Js/tx_jw_player.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsLibrary ( 'tx_jw_player',$file,'text/javascript',  TRUE );
		$uploadPath = '/uploads/tx_jwplayer/';
		$conf = unserialize ( $GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['jwplayer'] );
		if(!empty($conf['path_licensed_player'])){
			$path_player = $conf['path_licensed_player'];
		}else{
			$uri = 'EXT:jwplayer/Resources/Public/Player/player.swf';
			$uri = t3lib_div::getFileAbsFileName($uri);
			$uri = substr($uri, strlen(PATH_site));
			$path_player = '/'.$uri;
		}
		if($this->settings['image']){
			$this->view->assign ( 'image', $uploadPath.$this->settings['image'] );
		}else{
			$this->view->assign ( 'image', '' );
		}
		$this->view->assign ( 'player_id', uniqid('player'));
		$this->view->assign ( 'flashplayer', $path_player);
		$this->view->assign ( 'backcolor', $conf['backcolor'] );
		$this->view->assign ( 'fontcolor', $conf['fontcolor'] );
		$this->view->assign ( 'lightcolor', $conf['lightcolor'] );
		$this->view->assign ( 'screencolor', $conf['screencolor'] );
		
		$this->view->assign ( 'file', $uploadPath.$this->settings['movie'] );
		$this->view->assign ( 'width', $this->settings['width'] );
		$this->view->assign ( 'height', $this->settings['height'] );
		$this->view->assign ( 'autostart', $this->settings['autostart'] );
		$this->view->assign ( 'controlbar', $this->settings['controlbar'] );
		$this->view->assign ( 'repeat', $this->settings['repeat'] );
		$this->view->assign ( 'bufferlength', $this->settings['bufferlength'] );
		$this->view->assign ( 'stretching', $this->settings['stretching'] );
		$this->view->assign ( 'volume', $this->settings['volume'] );
		$this->view->assign ( 'mute', $this->settings['mute'] );
		$this->view->assign ( 'facebookPlugin', $this->settings['facebookPlugin'] );
	}
}