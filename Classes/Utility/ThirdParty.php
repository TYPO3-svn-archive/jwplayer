<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2011 AOE media GmbH <dev@aoemedia.de>
 * All rights reserved
 *
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ThirdParty utility
 *
 * @subpackage System
 */
class tx_Jwplayer_Utility_ThirdParty {

	/**
	 * Output player
	 *
	 * @static
	 * @param array $data
	 * @param array $file
	 * @param string $plugin Pi1 = Video, Pi2 = Audio
	 * @return html
	 */
	public static function getPlayer($settings, $data, $plugin = 'Pi1') {
		$pluginSettings = array();

		switch ($plugin) {
			case 'Pi1':
				$plugin = 'Pi1';
				$pluginSettings = array(
					'playlistsize' => 1,
					'disableSolveMoviePath' => TRUE,
					'disableSolveUploadPath' => TRUE,
					'moviesection' => array(
						array(
							'movieitem' => array(
								'file_flashhtml5' 	=> $data['flashhtml5'],
								'file_flash' 		=> $data['flash'],
								'file_ogv' 			=> $data['ogv'],
								'file_webm' 		=> $data['webm'],
								'url' 				=> $data['url'],
								'image' 			=> $data['image']
							)
						)
					)
				);

				break;
			case 'Pi2':
				$plugin = 'Pi2';

				$pluginSettings = array(
					'playlistsize' => 1,
					'disableSolveAudioPath' => TRUE,
					'disableSolveUploadPath' => TRUE,
					'audiosection' => array(
						array(
							'audioitem' => array(
								'file_mp3' 		=> $data['file_mp3'],
								'url' 			=> $data['url'],
								'image' 		=> $data['image']
							)
						)
					)
				);

				break;
			default:
				die('You must add a $plugin name (Pi1 = Video, Pi2 = Audio)');
		}


		$config = array(
			'userFunc'		=> 'tx_extbase_core_bootstrap->run',
			'pluginName'    => $plugin,
			'extensionName' => 'Jwplayer',
			'settings'		=> $pluginSettings
		);

		$config['settings'] = t3lib_div::array_merge_recursive_overrule($config['settings'], $settings);

		if ($data['width']) $config['settings']['width'] = (int)$data['width'];
		if ($data['height']) $config['settings']['height'] = (int)$data['height'];


		require_once t3lib_extMgm::extPath('extbase') . 'Classes/Core/Bootstrap.php';
		$cObj					= t3lib_div::makeInstance('tslib_cObj');
		$bootstrap				= t3lib_div::makeInstance('Tx_Extbase_Core_Bootstrap');
		$bootstrap->cObj		= $cObj;

		$content				= '';
		return $bootstrap->run($content, $config);
	}

}

?>