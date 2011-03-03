<?php
/**
 * Player 
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_PlayerController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * define separators for params and their values
	 */
	const UPLOAD_PATH = '/uploads/tx_jwplayer/';
	/**
	 * @var array
	 */
	private $conf;
	/**
	 * @var Tx_Jwplayer_FlashConfigGenerator
	 */
	private $flashConfigGenerator;
	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->flashConfigGenerator = t3lib_div::makeInstance ( 'Tx_Jwplayer_FlashConfigGenerator' );
		$this->conf = unserialize ( $GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['jwplayer'] );
	}

	/**
	 * @return string
	 */
	public function indexAction() {
		$this->addJavaScript();

		$playerId = uniqid('player');
		$this->view->assign ( 'player_id', $playerId);
		$this->view->assign ( 'flashplayer', $this->getPlayerPath());
		$this->view->assign ( 'backcolor', $this->conf['backcolor'] );
		$this->view->assign ( 'fontcolor', $this->conf['fontcolor'] );
		$this->view->assign ( 'lightcolor', $this->conf['lightcolor'] );
		$this->view->assign ( 'screencolor', $this->conf['screencolor'] );
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
		
		$this->setPlaylist();

		if((boolean) $this->settings['add_metatags'] === TRUE) {
			// create metaTags
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:description" content="'.$this->settings['metatag_description'].'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="video"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta name="medium" content="video"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video" content="'.$this->createVideoUrl().'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:type" content="application/x-shockwave-flash"/>' );
		}
	}
	/**
	 * show video (we need this action to be able to share the video in facebook)
	 * 
	 * @param string $flash_player_config
	 */
	public function showVideoAction($flash_player_config) {
		$typo3SiteUrl = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$flashPlayerUrl = $this->removeLastChar($typo3SiteUrl) . $this->getPlayerPath() . '?';
		$flashPlayerUrl .= $this->flashConfigGenerator->decode($flash_player_config);
		// add flashPlayerConfig to URL, which depends on this plugin
		$flashPlayerConfig = array();
		$flashPlayerConfig['netstreambasepath'] = $typo3SiteUrl;
		$flashPlayerConfig['id'] = uniqid('player');
		$flashPlayerConfig['backcolor'] = $this->conf['backcolor'];
		$flashPlayerConfig['fontcolor'] = $this->conf['fontcolor'];
		$flashPlayerConfig['lightcolor'] = $this->conf['lightcolor'];
		$flashPlayerConfig['screencolor'] = $this->conf['screencolor'];
		foreach($flashPlayerConfig as $key => $val) {
			$flashPlayerUrl .= $key.'='.$val.'&';
		}
		$flashPlayerUrl = $this->removeLastChar($flashPlayerUrl);
		$this->redirectToURI($flashPlayerUrl);
	}

	/*
	 *	Check count of files and create a playlist
	 */
	private function setPlayList() {
	
			/*
			 * 	check count of movies. generate playliste when more than
			 * 	one movie was set
			 */
		if ( count( $this->settings['moviesection'] ) > 1 ) {  
		
			# TODO create Playlist
			# now only the first movie will use

			$movieArray = array_shift( $this->settings['moviesection'] );

			$this->view->assign ( 'file', self::UPLOAD_PATH.$movieArray['movieitem']['file'] );			                        
			$this->view->assign ( 'image', $this->getImagePath( $movieArray['movieitem']['image'] ) );
		} else {
		
			$movieArray = array_shift( $this->settings['moviesection'] );
			$previewImagePath = $this->getImagePath( $movieArray['movieitem']['image'] );

			$this->view->assign ( 'file', self::UPLOAD_PATH.$movieArray['movieitem']['file'] );
			$this->view->assign ( 'image', $previewImagePath );

				// add movie specific meta tags for facebook	
			if((boolean) $this->settings['add_metatags'] === TRUE) {

				$title = empty($this->settings['metatag_title']) ? $movieArray['movieitem']['file'] : $this->settings['metatag_title'];
				$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:title" content="'.$title.'"/>' );

				# TODO: Render image to 50x50 PX
				$imgPath = $this->removeLastChar( t3lib_div::getIndpEnv('TYPO3_SITE_URL') ) . $previewImagePath ;
				$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:image" content="'.$imgPath.'">' );
			}
		}	
	}

	/**
	 * add javascript to page
	 */
	protected function addJavaScript() {
		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFile ( $file, 'text/javascript');
		$file = $extPath . 'Resources/Public/Js/tx_jw_player.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFile ( $file,'text/javascript',  TRUE );
	}
	/**
	 * create URL to action 'showVideo'
	 * @return string
	 */
	private function createVideoUrl() {
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showVideo';
		$arguments['tx_jwplayer_pi1']['controller'] = 'Player';
		$settings = $this->settings;
		$settings['autostart']= TRUE;
		$settings['movie']= self::UPLOAD_PATH.$settings['movie'];
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $this->flashConfigGenerator->encode($settings, $this->getImagePath());
		$url = $this->uriBuilder->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		return $url;
	}
	/**
	 * @param	string	$filename
	 * @return 	string
	 */
	private function getImagePath( $filename ) {
		$image = '';
		if( $filename ){
			$image = self::UPLOAD_PATH . $filename;
		}
		return $image;
	}
	/**
	 * @return string
	 */
	private function getPlayerPath() {
		if(!empty($this->conf['path_licensed_player'])){
			$path_player = $this->conf['path_licensed_player'];
		} else {
			$uri = 'EXT:jwplayer/Resources/Public/Player/player.swf';
			$uri = t3lib_div::getFileAbsFileName($uri);
			$uri = substr($uri, strlen(PATH_site));
			$path_player = '/'.$uri;
		}
		return $path_player;
	}
	/**
	 * @param string $string
	 * @return string
	 */
	private function removeLastChar($string) {
		return substr($string,0,-1);
	}
}