<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');
	t3lib_div::loadTCA('tt_content');

	// Video plugin
	Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'Pi1', 'JW Player - Video');
	t3lib_extMgm::addPlugin(array('LLL:EXT:jwplayer/locallang.xml:video.title', 'jwplayer_pi1', 'EXT:jwplayer/ext_icon.gif'), 'CType');
	$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;;1-1-1, header;;;;2-2-2,pi_flexform;;;;1-1-1';
	$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_pi1'] = 'FILE:EXT:jwplayer/Configuration/FlexForms/Video.xml';

	// Audio plugin
	Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'Pi2', 'JW Player - Audio');
	t3lib_extMgm::addPlugin(array('LLL:EXT:jwplayer/locallang.xml:audio.title', 'jwplayer_pi2', 'EXT:jwplayer/ext_icon.gif'), 'CType');
	$TCA['tt_content']['types'][$_EXTKEY.'_pi2']['showitem']='CType;;4;;1-1-1, header;;;;2-2-2,pi_flexform;;;;1-1-1';
	$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_pi2'] = 'FILE:EXT:jwplayer/Configuration/FlexForms/Audio.xml';

	// Add static typoscript
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JW Player');

	// Add jwplayer wizicon
	if (TYPO3_MODE=='BE') {
		// TODO , this wizicon is only for pi1 (video)
		$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_jwplayer_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'Classes/class.tx_jwplayer_wizicon.php';
	}
?>