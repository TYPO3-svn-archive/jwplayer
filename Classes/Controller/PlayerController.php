<?php
/**
 * Player 
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_PlayerController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * define separators for params and their values
	 */
	const SEPARATOR_PARAM = '|';
	const SEPARATOR_VALUE = ':';
	const UPLOAD_PATH = '/uploads/tx_jwplayer/';

	/**
	 * @var array
	 */
	private $conf;

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
		$this->view->assign ( 'file', self::UPLOAD_PATH.$this->settings['movie'] );
		$this->view->assign ( 'image', $this->getImagePath() );
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

		if((boolean) $this->settings['add_metatags'] === TRUE) {
			// create metaTags
			$title = empty($this->settings['metatag_title']) ? $this->settings['movie'] : $this->settings['metatag_title'];
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:title" content="'.$title.'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:description" content="'.$this->settings['metatag_description'].'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video" content="'.$this->createUrlToShowVideoAction().'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:height" content="'.$this->settings['height'].'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:width" content="'.$this->settings['width'].'"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:type" content="application/x-shockwave-flash"/>' );
			//$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:image" content="[img_src]"/>' );
			//$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="website"/>' );
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

		// add flashPlayerConfig to URL, which depends on the video (these data were build in method 'getFlashPlayerConfig')
		$flashPlayerConfig = explode(self::SEPARATOR_PARAM, $flash_player_config);
		foreach($flashPlayerConfig as $config) {
			list($key, $val) = explode(self::SEPARATOR_VALUE, $config);
			if(!empty($key) && !empty($val)) {
				$flashPlayerUrl .= $key.'='.$val.'&';
			}
		}

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

		header( 'Location: '.$flashPlayerUrl );
		exit();
	}

	/**
	 * add javascript to page
	 */
	protected function addJavaScript() {
		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsLibrary ( 'jwplayer', $file, 'text/javascript',TRUE ,TRUE);
		$file = $extPath . 'Resources/Public/Js/tx_jw_player.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsLibrary ( 'tx_jw_player',$file,'text/javascript',  TRUE );
	}
	/**
	 * create URL to action 'showVideo'
	 * @return string
	 */
	protected function createUrlToShowVideoAction() {
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showVideo';
		$arguments['tx_jwplayer_pi1']['controller'] = 'Player';
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $this->getFlashPlayerConfig();
		$url = $this->uriBuilder->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		return $url;
	}
	/**
	 * get string which contains all flash-player-data, which comes from plugin-settings and is needed by action 'showVideo'
	 * 
	 * @return string
	 */
	protected function getFlashPlayerConfig() {
		$flashPlayerData = array();
		$flashPlayerData['autostart'] = ($this->settings['autostart'] == '1') ? 'true' : 'false';
		$flashPlayerData['bufferlength'] = $this->settings['bufferlength'];
		$flashPlayerData['controlbar.position'] = $this->settings['controlbar'];
		$flashPlayerData['file'] = self::UPLOAD_PATH . $this->settings['movie'];
		$flashPlayerData['image'] = ($this->getImagePath() == '') ? 'undefined' : $this->getImagePath();
		$flashPlayerData['mute'] = ($this->settings['mute'] == '1') ? 'true' : 'false';
		$flashPlayerData['volume'] = $this->settings['volume'];

		$flashPlayerDataString = '';
		foreach($flashPlayerData as $key => $val) {
			$flashPlayerDataString .= $key . self::SEPARATOR_VALUE . $val . self::SEPARATOR_PARAM;
		}
		$flashPlayerDataString = $this->removeLastChar($flashPlayerDataString);

		return $flashPlayerDataString;
	}
	
	/**
	 * Initializes the controller before invoking an action method.
	 */
	protected function initializeAction() {
		$this->conf = unserialize ( $GLOBALS ['TYPO3_CONF_VARS'] ['EXT'] ['extConf'] ['jwplayer'] );
	}

	/**
	 * @return string
	 */
	private function getImagePath() {
		$image = '';
		if($this->settings['image']){
			$image = self::UPLOAD_PATH . $this->settings['image'];
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