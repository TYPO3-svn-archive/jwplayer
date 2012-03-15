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
	 * @return integer
	 */
	public function getPathLicensedPlayer() {
		return $this->get ( 'path_licensed_player' );
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
		$pathLicensedPlayer = $this->getPathLicensedPlayer();
		if(!empty($pathLicensedPlayer)){
			$path_player = t3lib_div::getFileAbsFileName($this->getPathLicensedPlayer());
		} else {
			$uri = 'EXT:jwplayer/Resources/Public/Player/player.swf';
			$path_player = t3lib_div::getFileAbsFileName($uri);
		}
		if(is_null($path_player)){
			throw new Exception('Invalid jwplayer path', 201203151804);
		}
		$path_player = substr($path_player, strlen(PATH_site));
		return $path_player;
	}

}