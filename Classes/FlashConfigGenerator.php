<?php
/**
 * Generate the Flash config
 * @package jwplayer
 */
class Tx_Jwplayer_FlashConfigGenerator {

	const SEPARATOR_PARAM = '|';
	const SEPARATOR_VALUE = ':';

	const ESACPED_SEPERATOR_PARAM = '@@PIPE@@';
	const ESACPED_SEPERATOR_VALUE = '@@COLON@@';

	/**
	 * get string which contains all flash-player-data, which comes from plugin-settings and is needed by action 'showVideo'
	 *
	 * @param array $settings
	 * @param string $imagePath
	 * @return string
	 */
	public function encode(array $settings, $imagePath = '') {
		$flashPlayerData = array ();
		$flashPlayerData ['autostart'] = ($settings ['autostart'] == '1') ? 'true' : 'false';
		$flashPlayerData ['bufferlength'] = $settings ['bufferlength'];
		$flashPlayerData ['controlbar.position'] = $settings ['controlbar'];

		if($imagePath != '') {
			$flashPlayerData ['image'] = $imagePath;
		}

		if(array_key_exists('movie',$settings)) {
			$flashPlayerData ['file'] = $settings ['movie'];
		}elseif(array_key_exists('audio',$settings)) {
			$flashPlayerData ['file'] = $settings ['audio'];
		}

		$flashPlayerData ['mute'] = ($settings ['mute'] == '1') ? 'true' : 'false';
		$flashPlayerData ['volume'] = $settings ['volume'];

		$flashPlayerDataString = '';
		foreach ( $flashPlayerData as $key => $val ) {
			$key = $this->subsituteSeperatorValue( $this->subsituteSeperatorParam( $key ) ) ;
			$val = $this->subsituteSeperatorValue( $this->subsituteSeperatorParam( $val) );
			$flashPlayerDataString .= $key . self::SEPARATOR_VALUE . $val . self::SEPARATOR_PARAM;
		}

		$flashPlayerDataString = substr ( $flashPlayerDataString, 0, - 1 );
		return base64_encode ( $flashPlayerDataString );
	}

	/**
	 * add flashPlayerConfig to URL, which depends on the video
	 *
	 * @param string $flash_player_config
	 * @return string
	 */
	public function decode($flash_player_config) {
		$flashPlayerConfig = base64_decode ( $flash_player_config );
		$flashPlayerConfig = explode ( self::SEPARATOR_PARAM, $flashPlayerConfig );
		foreach ( $flashPlayerConfig as $config ) {
			list ( $key, $val ) = explode ( self::SEPARATOR_VALUE, $config );
			if (! empty ( $key ) && ! empty ( $val )) {
				$key = $this->resubsituteSeperatorParam( $this->resubsituteSeperatorValue($key) );
				$val = $this->resubsituteSeperatorParam( $this->resubsituteSeperatorValue($val) );
				$flashPlayerUrl .= $key . '=' . $val . '&';
			}
		}
		return $flashPlayerUrl;
	}

	/**
	 * This method is used to subsitute the usage of the value seperator itself in the param.
	 *
	 * @param string
	 * @return string
	 */
	protected function subsituteSeperatorValue($string) {
		$result =  str_replace(self::SEPARATOR_VALUE, self::ESACPED_SEPERATOR_VALUE, $string);
		return $result;
	}

	/**
	 * This method is used to replace the substituted value string.
	 *
	 * @param string
	 * @return string
	 */
	protected function resubsituteSeperatorValue($string) {
		$result = str_replace(self::ESACPED_SEPERATOR_VALUE, self::SEPARATOR_VALUE, $string);
		return $result;
	}


	/**
	 * This method is used to subsitute the usage of the param seperator itself in the param.
	 *
	 * @param string
	 * @return string
	 */
	protected function subsituteSeperatorParam($string) {
		$result = str_replace(self::SEPARATOR_PARAM, self::ESACPED_SEPERATOR_PARAM, $string);
		return $result;
	}

	/**
	 * This method is used to resubstitute the replace value seperator.
	 *
	 * @param string
	 * @return string
	 */
	protected function resubsituteSeperatorParam($string) {
		$result = str_replace(self::ESACPED_SEPERATOR_PARAM, self::SEPARATOR_PARAM, $string);
		return $result;
	}
}

?>