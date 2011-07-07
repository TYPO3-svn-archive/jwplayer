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
	
	var $allowedSkinExtension = array(
		'zip',
		'swf',
		'xml'
	);
	
	/**
	 * @var array
	 */
	private $conf;
	
	/**
	 * @var Tx_Jwplayer_FlashConfigGenerator
	 */
	private $flashConfigGenerator;
	
	/**
	 * View to render inline JS
	 */
	private $jsView;
	
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
		}
		
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
		$this->view->assign ( 'dontMoveJs', $this->getSetting( 'dontMoveJs' ) );
		
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
	 *	Return setting, selected by name
	 *	@parameter	string	$name
	 *	@return 	string
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
		
		return $skinFile;
	}
	
	/**
	 *	Check if file extension is allow
	 *	@param	string	$file
	 *	@return	bool
	 */
	protected function checkSkinFileExtension( $file) {
		
		return in_array( pathinfo( PATH_site . $file, PATHINFO_EXTENSION ) ,$this->allowedSkinExtension );
	}
	
	/**
	 *	Return setting for playlist position
	 *	When less than 2 items are available don't show playlist
	 *	@return	string
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
	 *	@return	bool	
	 */
	protected function hasPlaylist() {
	
		$flag = false;
		
		if( count( $this->getSetting( 'moviesection' ) ) > 1 ) {
			$flag = true;
		}
		
		return $flag;
	}
	
	/**
	 *	Calculate width of player
	 *	@return	integer	
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
	 *	@return	integer	
	 */
	protected function getPlayerHeight() {
	
		$height = $this->getSetting( 'height' );
		
		if ( $this->hasPlaylist() && ( $this->getSetting( 'playlistposition' ) == 'top' || $this->getSetting( 'playlistposition' ) == 'bottom' ) ) {
			$height = $height + $this->getSetting( 'playlistsize' );
		}
		
		return $height;
	}
	
	/**
	 * show video (we need this action to be able to share the video in facebook)
	 * 
	 * @param string $flash_player_config
	 */
	public function showVideoAction($flash_player_config) {
		$typo3SiteUrl = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$flashPlayerUrl = $this->removeLastChar($typo3SiteUrl) . $this->conf->getPlayerPath() . '?';
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

			foreach( $this->settings['moviesection'] as $item ) {
			
				$movie = $this->getMovieList( $item );
				$playlist[] = array(
					'title' => $item['movieitem']['title'],
					'description' => $item['movieitem']['description'],
					'duration' => ($item['movieitem']['duration']) ? $item['movieitem']['duration'] : 0,
					'file' => ( $movie['flash'] ) ? $movie['flash'] : $movie['url'],
					'image' => $this->getUploadPath( $item['movieitem']['image'] )
				);
			}
			
			$this->view->assign( 'playlistItems', $playlist );

		} else {
		
			$itemArray = array_shift( $this->settings['moviesection'] );
		
			if( $movieList = $this->getMovieList( $itemArray ) ) {
			
				$previewImagePath = $this->getUploadPath( $itemArray['movieitem']['image'] );

				$this->view->assign ( 'file_flash', ( !empty( $movieList['flash'] ) ) ? $movieList['flash'] : $movieList['url'] );
				$this->view->assign ( 'files_html5', $movieList['html5'] );
				
				$this->view->assign ( 'image', $previewImagePath );

					// add movie specific meta tags for facebook	
				if((boolean) $this->settings['add_metatags'] === TRUE) {

					$title = empty($this->settings['metatag_title']) ? $itemArray['movieitem']['file'] : $this->settings['metatag_title'];
					$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:title" content="'.$title.'"/>' );

					# TODO: Render image to 50x50 PX
					$imgPath = $this->removeLastChar( t3lib_div::getIndpEnv('TYPO3_SITE_URL') ) . $previewImagePath ;
					$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:image" content="'.$imgPath.'">' );
				}
			}
		}	
		
	}
	
	/**
	 *	Generate a list with given movie formats
	 *	@param	
	 */	
	protected function getMovieList( $itemArray ) {
	
		$movieArray = array(
			'flash' => '',
			'html5' => array(),
			'url' => ''
		);
		
			
			// flashhtml5
		$flashhtml5 = $this->solveMoviePath( $itemArray['movieitem']['file_flashhtml5'] );
		$movieArray['flash'] = $flashhtml5;
		$movieArray['html5'][ pathinfo( $flashhtml5, PATHINFO_EXTENSION ) ] = $flashhtml5;
	
			// flash
		if( $itemArray['movieitem']['file_flash'] ) {
			$movieArray['flash'] = $this->solveMoviePath( $itemArray['movieitem']['file_flash'] );
		}

			// html5
		$ogv = $this->solveMoviePath( $itemArray['movieitem']['file_ogv'] );
		$movieArray['html5'][ pathinfo( $ogv, PATHINFO_EXTENSION ) ] = $ogv;
		
		$webm = $this->solveMoviePath( $itemArray['movieitem']['file_webm'] );
		$movieArray['html5'][ pathinfo( $webm, PATHINFO_EXTENSION ) ] = $webm;
		
			// url
		$movieArray['url'] = $this->solveMoviePath( $itemArray['movieitem']['url'], 'url' );
	
		if( empty( $movieArray['flash'] ) && empty( $movieArray['url'] ) ) {
			return false;	
		}
		
		return $movieArray;
	}
	
	/**
	 *	Create file path, by URL checks the syntax
	 *	@param	string	$filename
	 *	@param	string	$type
	 *	@return	string
	 */
	protected function solveMoviePath( $filename, $type='file' ) {
	
		$filePath = '';
	
		if( !empty( $filename ) ) {
			switch( $type ) {
				case 'file':
					$filePath = self::UPLOAD_PATH . $filename;
					break;
				case 'url':
					$filePath = ( $this->checkUrl( $filename ) ) ? $filename : '';
					break;
			}
		}
			
		return $filePath;
	}
	
	/**
	 *	Check Url 
	 *
	 *	@param	string	$url
	 *	@return	bool
	 */
	private function checkUrl( $url ) {
	
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
	
		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFooterFile( $file);
		$file = $extPath . 'Resources/Public/Js/tx_jw_player.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFooterFile( $file );		
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
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $this->flashConfigGenerator->encode($settings, $this->getUploadPath() );
		$url = $this->uriBuilder->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		return $url;
	}
	/**
	 * @param	string	$filename
	 * @return 	string
	 */
	private function getUploadPath( $filename ) {
		$path = '';
		if( $filename ){
			$path = self::UPLOAD_PATH . $filename;
		}
		return $path;
	}
	/**
	 * @param string $string
	 * @return string
	 */
	private function removeLastChar($string) {
		return substr($string,0,-1);
	}
}
