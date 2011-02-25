<?php
/**
 * Generate the Flash config 
 * @package jwplayer
 */
class Tx_Jwplayer_FlashConfigGenerator {
	const SEPARATOR_PARAM = '|';
	const SEPARATOR_VALUE = ':';
	/**
	 * get string which contains all flash-player-data, which comes from plugin-settings and is needed by action 'showVideo'
	 * @param array $settings
	 * @param array $imagePath
	 * @return string
	 */
	public function encode(array $settings, $imagePath) {
		$flashPlayerData = array ();
		$flashPlayerData ['autostart'] = ($settings ['autostart'] == '1') ? 'true' : 'false';
		$flashPlayerData ['bufferlength'] = $settings ['bufferlength'];
		$flashPlayerData ['controlbar.position'] = $settings ['controlbar'];
		$flashPlayerData ['file'] = $settings ['movie'];
		if ('' !== $imagePath) {
			$flashPlayerData ['image'] = $imagePath;
		}
		$flashPlayerData ['mute'] = ($settings ['mute'] == '1') ? 'true' : 'false';
		$flashPlayerData ['volume'] = $settings ['volume'];
		$flashPlayerDataString = '';
		foreach ( $flashPlayerData as $key => $val ) {
			$flashPlayerDataString .= $key . self::SEPARATOR_VALUE . $val . self::SEPARATOR_PARAM;
		}
		$flashPlayerDataString = substr ( $flashPlayerDataString, 0, - 1 );
		return base64_encode ( $flashPlayerDataString );
	}
	/**
	 * add flashPlayerConfig to URL, which depends on the video
	 * @param string $flash_player_config
	 * @return string
	 */
	public function decode($flash_player_config) {
		$flashPlayerConfig = base64_decode ( $flash_player_config );
		$flashPlayerConfig = explode ( self::SEPARATOR_PARAM, $flashPlayerConfig );
		foreach ( $flashPlayerConfig as $config ) {
			list ( $key, $val ) = explode ( self::SEPARATOR_VALUE, $config );
			if (! empty ( $key ) && ! empty ( $val )) {
				$flashPlayerUrl .= $key . '=' . $val . '&';
			}
		}
		return $flashPlayerUrl;
	}
}