<?php
/**
 * Player
 * @package jwplayer
 */
abstract class Tx_Jwplayer_Controller_AbstractPlayerController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * define separators for params and their values
	 */
	const UPLOAD_PATH = 'uploads/tx_jwplayer/';

	/**
	 * @var array
	 */
	var $allowedSkinExtension = array(
		'zip',
		'swf',
		'xml'
	);

	/**
	 * @var array
	 */
	protected $conf;

	/**
	 * @var Tx_Jwplayer_FlashConfigGenerator
	 */
	protected $flashConfigGenerator;

	/**
	 * View to render inline JS
	 */
	protected $jsView;

	/**
	 * @var array
	 */
	protected $playlist = array();

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->flashConfigGenerator = t3lib_div::makeInstance ( 'Tx_Jwplayer_FlashConfigGenerator' );
		$this->conf = t3lib_div::makeInstance ( 'Tx_Jwplayer_Configuration_ExtensionManager' );
	}


	/**
	 * @return string
	 */
	public function indexAction() {

		if( $this->getSetting('disableJsAutoInclude') != 1 ) {
			$this->addJavaScript();
			$this->view->assign ( 'usePageRenderer', true);
		} else {
			$this->view->assign ( 'usePageRenderer', false);
		}
		$this->view->assign ( 'usePageRenderer', false);
		$playerId = uniqid('player');

		$this->view->assign ( 'player_id', $playerId);
		$this->view->assign ( 'flashplayer', $this->conf->getPlayerPath());
		$this->view->assign ( 'backcolor', $this->conf->getBackcolor() );
		$this->view->assign ( 'fontcolor', $this->conf->getFontcolor() );
		$this->view->assign ( 'lightcolor', $this->conf->getLightcolor() );
		$this->view->assign ( 'screencolor', $this->conf->getScreenscolor() );
		$this->view->assign ( 'width', $this->getPlayerWidth() );
		$this->view->assign ( 'height', $this->getPlayerHeight() );
		$this->view->assign ( 'autostart', $this->getSetting( 'autostart' ) );
		$this->view->assign ( 'controlbar', $this->getSetting( 'controlbar' ) );
		$this->view->assign ( 'repeat', $this->getSetting( 'repeat' ) );
		$this->view->assign ( 'bufferlength', $this->getSetting( 'bufferlength' ) );
		$this->view->assign ( 'stretching', $this->getSetting( 'stretching' ) );
		$this->view->assign ( 'volume', $this->getSetting( 'volume' ) );
		$this->view->assign ( 'mute', $this->getSetting( 'mute' ) );
		$this->view->assign ( 'facebookPlugin', $this->getSetting( 'facebookPlugin' ) );
		$this->view->assign ( 'playlist_position', $this->getPlaylistPosition() );
		$this->view->assign ( 'playlist_size', $this->getSetting( 'playlistsize' ) );
		$this->view->assign ( 'skin', $this->getSkin() );

		$this->setPlaylist();
	}

	/**
	 *	Return setting, selected by name
	 *
	 *	@param string $name
	 *	@return string
	 */
	protected function getSetting( $name ) {
		$value = ( $this->settings['overrideGlobal'][ $name ] ) ? $this->settings['overrideGlobal'][ $name ] : $this->settings[ $name ];
		return $value;
	}

	/**
	 *	Return path to skin file when is set
	 *
	 *	@return string
	 */
	protected function getSkin() {
		$skinFile = '';

		if( file_exists( PATH_site . $this->conf->getSkin() ) && $this->checkSkinFileExtension( PATH_site . $this->conf->getSkin() ) ) {
			$skinFile = $this->conf->getSkin();
		} elseif( file_exists( PATH_site . $this->getSetting( 'skin' ) ) && $this->checkSkinFileExtension( PATH_site . $this->getSetting( 'skin' ) ) ) {
			$skinFile = $this->getSetting( 'skin' );
		} else {
			$skinFile = $this->getUploadPath( $this->getSetting( 'skin' ) );
		}

		// add slash
		if( !empty($skinFile) && substr($skinFile, 0, 1) != '/') {
			$skinFile = '/' . $skinFile;
		}

		return $skinFile;
	}

	/**
	 *	Check if file extension is allow
	 *
	 *	@param string $file
	 *	@return bool
	 */
	protected function checkSkinFileExtension( $file) {
		return in_array( pathinfo( PATH_site . $file, PATHINFO_EXTENSION ) ,$this->allowedSkinExtension );
	}

	/**
	 *	Return setting for playlist position
	 *	When less than 2 items are available don't show playlist
	 *
	 *	@return string
	 */
	protected function getPlaylistPosition() {
		$position = $this->settings['playlistposition'];

		if( !$this->hasPlaylist() ) {
			$position = 'none';
		}

		return $position;
	}

	/**
	 *	Check if playlist should be shown
	 *
	 *	@return bool
	 */
	protected function hasPlaylist() {
		$flag = false;

		if( count( $this->playlist ) > 1 ) {
			$flag = true;
		}

		return $flag;
	}

	/**
	 *	Calculate width of player
	 *
	 *	@return integer
	 */
	protected function getPlayerWidth() {
		$width = $this->getSetting( 'width' );

		if ( $this->hasPlaylist() && ( $this->getSetting( 'playlistposition' ) == 'left' || $this->getSetting( 'playlistposition' ) == 'right' ) ) {
			$width = $width + $this->getSetting( 'playlistsize' );
		}

		return $width;
	}

	/**
	 *	Calculate height of player
	 *
	 *	@return integer
	 */
	protected function getPlayerHeight() {
		$height = $this->getSetting( 'height' );

		if ( $this->hasPlaylist() && ( $this->getSetting( 'playlistposition' ) == 'top' || $this->getSetting( 'playlistposition' ) == 'bottom' ) ) {
			$height = $height + $this->getSetting( 'playlistsize' );
		}

		return $height;
	}

	/*
	 *	Check count of files and create a playlist
	 */
	abstract protected function setPlayList();

	/**
	 *	Check Url
	 *
	 *	@param string $url
	 *	@return bool
	 */
	protected function checkUrl( $url ) {
		$flag = false;

		if( preg_match("/[h][t]{2}[p][\:][\/]{2}[w.0-9]{0,4}[a-zA-Z0-9.-]{2,40}[.][a-zA-Z]{2,7}/", $url ) ) {
			$flag = true;
		}

		return $flag;
	}

	/**
	 * add javascript to page
	 */
	protected function addJavaScript() {
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFooterLibrary('jwplayer', $this->conf->getJsPath());

		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Js/tx_jw_player.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFooterFile( $file );
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	protected function getUploadPath( $filename  = NULL) {
		if ($this->settings['disableSolveUploadPath'] == TRUE) return $filename;

		$path = '';
		if( $filename ){
			$path = '/' . self::UPLOAD_PATH . $filename;
		}
		return $path;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function removeLastChar($string) {
		return substr($string,0,-1);
	}

	/**
	 *	Create file path, by URL checks the syntax
	 *
	 *	@param string $filename
	 *	@param string $type
	 *	@return string
	 */
	protected function solveFilePath( $filename, $type='file' ) {
		if ($this->settings['disableSolveMoviePath'] == TRUE) return $filename;
		if ($this->settings['disableSolveAudioPath'] == TRUE) return $filename;

		$filePath = '';

		if( !empty( $filename ) ) {
			switch( $type ) {
				case 'file':
					$filePath = '/'. $filename;
					break;
				case 'url':
					$filePath = ( $this->checkUrl( $filename ) ) ? $filename : '';
					break;
			}
		}

		return $filePath;
	}

	/**
	 * create URL to action 'showVideo'
	 *
	 * old name: createVideoUrl
	 *
	 * @param string $action
	 * @return string
	 */
	protected function createUrlToSinglePlayer() {
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showSingleFile';
		$arguments['tx_jwplayer_pi1']['controller'] = $this->request->getControllerName();

		$settings = $this->settings;
		$settings['autostart']= TRUE;

		// TODO - there is no movie. Cant work!
		// TODO - multiple videos (playlist) Cant work!
		$settings['movie']= self::UPLOAD_PATH . $settings['movie'];

		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $this->flashConfigGenerator->encode($settings, $this->getUploadPath() );
		$url = $this->uriBuilder->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();

		return $url;
	}

	/**
	 * show video (we need this action to be able to share the video in facebook)
	 *
	 * @param string $flash_player_config
	 */
	public function showSingleFileAction($flash_player_config) {

		$typo3SiteUrl = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		if( substr($typo3SiteUrl, -1, 1) != '/') { // add slash
			$typo3SiteUrl .= '/';
		}

		$flashPlayerUrl = $typo3SiteUrl . $this->conf->getPlayerPath() . '?';
		$flashPlayerUrl .= $this->flashConfigGenerator->decode($flash_player_config);

		// add flashPlayerConfig to URL, which depends on this plugin
		$flashPlayerConfig = array();
		$flashPlayerConfig['netstreambasepath'] = $typo3SiteUrl;
		$flashPlayerConfig['id'] = uniqid('player');
		$flashPlayerConfig['backcolor'] = $this->conf->getBackcolor();
		$flashPlayerConfig['fontcolor'] = $this->conf->getFontcolor();
		$flashPlayerConfig['lightcolor'] = $this->conf->getLightcolor();
		$flashPlayerConfig['screencolor'] = $this->conf->getScreenscolor();

		$skinFile = $this->getSkin();
		if($skinFile != '' ) {
			if(substr(0,1,$skinFile) != '/') {
				$baseUrl = $typo3SiteUrl;
			}else {
				$baseUrl = $this->removeLastChar($typo3SiteUrl);
			}

			$skinUrl 					= $baseUrl.$skinFile;
			$flashPlayerConfig['skin'] 	= $skinUrl;
		}

		foreach($flashPlayerConfig as $key => $val) {
			$flashPlayerUrl .= $key.'='.$val.'&';
		}
		$flashPlayerUrl = $this->removeLastChar($flashPlayerUrl);

		$this->redirectToURI($flashPlayerUrl);
	}

}

?>