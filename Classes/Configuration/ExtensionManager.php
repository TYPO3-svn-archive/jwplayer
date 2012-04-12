<?php
/**
 * Object for extension configuration
 * @package jwplayer
 */
class Tx_Jwplayer_Configuration_ExtensionManager implements t3lib_Singleton {
	
	/**
	 * @var array
	 */
	private $configuration = array();

	/**
	 * loading current configuration of extension
	 */
	public function __construct() {
		$this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jwplayer']);
	}

	/**
	 * returns configurationvalue for the given key
	 *
	 * @param string $key
	 * @return string
	 */
	private function get($key) {
		return $this->configuration[$key];
	}
	
	/**
	 * @return string
	 */
	public function getPath() {
		$pathSetting = $this->get('path_licensed_player');
		$path = 'EXT:jwplayer/Resources/Public/Player/';

			// check if is file or directory
		if(!empty($pathSetting) && file_exists(t3lib_div::getFileAbsFileName($pathSetting))){
			$path = t3lib_div::dirname($pathSetting, PATHINFO_DIRNAME);
		} elseif(!empty($pathSetting) && is_dir(t3lib_div::getFileAbsFileName($pathSetting))) {
			$path = $pathSetting;
		}

			// add slash
		if( substr($path, -1, 1) != '/') {
			$path .= '/';
		}

		return $path;
	}

	/**
	 * @return string
	 */
	public function getPathLicensedPlayer() {
		$pathSetting = $this->get ( 'path_licensed_player' );
		$path = '';

		if(!empty($pathSetting) && file_exists(t3lib_div::getFileAbsFileName($pathSetting))){
			$path = t3lib_div::getFileAbsFileName($pathSetting);
		} elseif(!empty($pathSetting) && is_dir(t3lib_div::getFileAbsFileName($pathSetting)) && file_exists(t3lib_div::getFileAbsFileName($pathSetting . 'player.swf'))) {
			$path = t3lib_div::getFileAbsFileName($pathSetting . 'player.swf');
		}

		return $path;
	}

	/**
	 * @return string
	 */
	public function getSkin() {
		return $this->get ( 'skin' );
	}
	
	/**
	 * @return string
	 */
	public function getBackcolor() {
		return $this->get ( 'backcolor' );
	}
	
	/**
	 * @return string
	 */
	public function getFontcolor() {
		return $this->get ( 'fontcolor' );
	}
	
	/**
	 * @return string
	 */
	public function getLightcolor() {
		return $this->get ( 'lightcolor' );
	}
	
	/**
	 * @return string
	 */
	public function getScreenscolor() {
		return $this->get ( 'screencolor' );
	}
	
	/**
	 * @return string
	 */
	public function getPlayerPath() {

		$path = $this->getPath();
		$path_player = t3lib_div::getFileAbsFileName( $path . 'player.swf');

		if(empty($path_player)){
			throw new Exception('Invalid swf-file path', 201203151804);
		}

		$path_player = substr($path_player, strlen(PATH_site));
		return $path_player;
	}

	/**
	 * @return string
	 */
	public function getJsPath() {

		$path = $this->getPath();
		$path_js = t3lib_div::getFileAbsFileName( $path . 'jwplayer.js');

		if(empty($path_js)){
			throw new Exception('Invalid js-file path', 201203151804);
		}

		$path_js = substr($path_js, strlen(PATH_site));
		return $path_js;
	}

}
