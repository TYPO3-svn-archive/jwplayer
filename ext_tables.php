<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');
	t3lib_div::loadTCA('tt_content');

	$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
	$videoPluginSignature = strtolower($extensionName) . '_pivideo';
	$audioPluginSignature = strtolower($extensionName) . '_piaudio';

		// Video plugin
	Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'PiVideo', 'JW Player - Video');
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$videoPluginSignature] = 'layout,select_key,pages,recursive';
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$videoPluginSignature] = 'pi_flexform';
	t3lib_extMgm::addPiFlexFormValue($videoPluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Video.xml');

		// Audio plugin
	Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'PiAudio', 'JW Player - Audio');
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$audioPluginSignature] = 'layout,select_key,pages,recursive';
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$audioPluginSignature] = 'pi_flexform';
	t3lib_extMgm::addPiFlexFormValue($audioPluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Audio.xml');

	// Add jwplayer wizicon
	if (TYPO3_MODE=='BE') {
		// TODO , this wizicon is only for pi1 (video)
		$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_jwplayer_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'Classes/class.tx_jwplayer_wizicon.php';
	}

?>
