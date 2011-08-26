<?php
/**
 * Player 
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_AjaxPlayerController extends Tx_Extbase_MVC_Controller_ActionController {
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
	
		if( intval( t3lib_div::_GP('news') ) )  {
                	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_jwplayerttnews_movie, tx_jwplayerttnews_previewimage', 'tt_news', 'uid ='. t3lib_div::_GP('news') );
                	$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

                	if( $data ) {
                        	$this->view->assign ( 'file_flash', '/uploads/jwplayerttnews/' . $data['tx_jwplayerttnews_movie'] );

                	}
		}
		
		
		$this->view->assign ( 'flashplayer', $this->conf->getPlayerPath());
		$this->view->assign ( 'autostart', $this->getSetting( 'autostart' ) );
		$this->view->assign ( 'skin', $this->getSkin() );
		
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

	/*	
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
