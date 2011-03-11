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
		$this->view->assign ( 'width', $this->getPlayerWidth() );
		$this->view->assign ( 'height', $this->getPlayerHeight() );
		$this->view->assign ( 'autostart', $this->settings['autostart'] );
		$this->view->assign ( 'controlbar', $this->settings['controlbar'] );
		$this->view->assign ( 'repeat', $this->settings['repeat'] );
		$this->view->assign ( 'bufferlength', $this->settings['bufferlength'] );
		$this->view->assign ( 'stretching', $this->settings['stretching'] );
		$this->view->assign ( 'volume', $this->settings['volume'] );
		$this->view->assign ( 'mute', $this->settings['mute'] );
		$this->view->assign ( 'facebookPlugin', $this->settings['facebookPlugin'] );
		$this->view->assign ( 'playlist_position', $this->getPlaylistPosition() );
		$this->view->assign ( 'playlist_size', $this->settings['playlistsize'] );
		
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
		
		if( count( $this->settings['moviesection'] ) > 1 ) {
			$flag = true;
		}
		
		return $flag;
	}
	
	/**
	 *	Calculate width of player
	 *	@return	integer	
	 */
	protected function getPlayerWidth() {
	
		$width = $this->settings['width'];
		
		if ( $this->hasPlaylist() && ( $this->settings['playlistposition'] == 'left' || $this->settings['playlistposition'] == 'right' ) ) {
			$width = $width + $this->settings['playlistsize'];
		}
		
		return $width;
	}
	
	/**
	 *	Calculate height of player
	 *	@return	integer	
	 */
	protected function getPlayerHeight() {
	
		$height = $this->settings['height'];
		
		if ( $this->hasPlaylist() && ( $this->settings['playlistposition'] == 'top' || $this->settings['playlistposition'] == 'bottom' ) ) {
			$height = $height + $this->settings['playlistsize'];
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

			foreach( $this->settings['moviesection'] as $item ) {
			
				$playlist[] = array(
					'title' => $item['movieitem']['title'],
					'description' => $item['movieitem']['description'],
					'duration' => ($item['movieitem']['duration']) ? $item['movieitem']['duration'] : 0,
					'file' => $this->solveVideoPath( $item ),
					'image' => $this->getImagePath( $item['movieitem']['image'] )
				);
			}
			
			$this->view->assign( 'playlistItems', $playlist );

		} else {
		
			$movieArray = array_shift( $this->settings['moviesection'] );
			
			if( $filePath = $this->solveVideoPath( $movieArray ) ) {
			
				$previewImagePath = $this->getImagePath( $movieArray['movieitem']['image'] );

				$this->view->assign ( 'file', $filePath );
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
		
	}
	
	protected function solveVideoPath( $itemArray ) {
	
		$videoPath = false;
	
		if( $itemArray['movieitem']['file'] ) {
		
			$videoPath = self::UPLOAD_PATH . $itemArray['movieitem']['file'];
		
		} elseif( $this->checkUrl( $itemArray['movieitem']['url']  ) ) {
			$videoPath = $itemArray['movieitem']['url'] = $itemArray['movieitem']['url']; 
		}
		
		return $videoPath;
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