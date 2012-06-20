<?php
/**
 * Value object for the jwplayer config
 * @package jwplayer
 */
class Tx_Jwplayer_Config {

	/**
	 * @var array
	 */
	private $jsConfig = array();

	/**
	 * Constructor
	 */
	public function __construct(){
		$extConfig = t3lib_div::makeInstance ( 'Tx_Jwplayer_Configuration_ExtensionManager' );

		$this->jsConfig['flashplayer'] = $extConfig->getPlayerPath();

		$skin = $extConfig->getSkin();
		if(!empty($skin)) {
			$this->jsConfig['skin'] = $skin;
		}
		$backcolor = $extConfig->getBackcolor();
		if(!empty($backcolor)) {
			$this->jsConfig['backcolor'] = $backcolor;
		}
		$fontcolor = $extConfig->getFontcolor();
		if(!empty($fontcolor)) {
			$this->jsConfig['fontcolor'] = $fontcolor;
		}
		$lightcolor = $extConfig->getLightcolor();
		if(!empty($lightcolor)) {
			$this->jsConfig['lightcolor'] = $lightcolor;
		}
		$screenscolor = $extConfig->getScreenscolor();
		if(!empty($screenscolor)) {
			$this->jsConfig['screenscolor'] = $screenscolor;
		}
	}

	/**
	 * @param array $settings
	 */
	public function setSettings(array $settings = array() ){
		$config_keys = array();
		$config_keys[] = 'player_id';
		$config_keys[] = 'backcolor';
		$config_keys[] = 'fontcolor';
		$config_keys[] = 'lightcolor';
		$config_keys[] = 'screencolor';
		$config_keys[] = 'controlbar';
		$config_keys[] = 'width';
		$config_keys[] = 'height';
		$config_keys[] = 'bufferlength';
		$config_keys[] = 'volume';
		$config_keys[] = 'stretching';
		$config_keys[] = 'repeat';
		$config_keys[] = 'image';
		$config_keys[] = 'skin';
		$config_keys[] = 'file';

		foreach($settings as $key =>$value){
			if(in_array($key,$config_keys) && $value!==''){
				$this->jsConfig[$key] = $value;
			}
		}
	}

	/**
	 * @return string $jsConfig
	 */
	public function getJsConfig() {
		return $this->jsConfig;
	}

}

?>