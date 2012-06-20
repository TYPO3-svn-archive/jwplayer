<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');

	Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY,
		'Pi1',
		'JW Player'
	);


	t3lib_div::loadTCA('tt_content');

	$TCA['tt_content']['types']['list']['subtypes_excludelist']['jwplayer_pi1'] = 'layout,recursive,select_key,pages';
	$TCA['tt_content']['types']['list']['subtypes_addlist']['jwplayer_pi1'] = 'pi_flexform';
	t3lib_extMgm::addPlugin(array('LLL:EXT:jwplayer/locallang.xml:jwplayer.title', $_EXTKEY .'_pi1' ), 'list_type');
	t3lib_extMgm::addPiFlexFormValue( 'jwplayer_pi1', 'FILE:EXT:jwplayer/Configuration/FlexForms/Player.xml');

	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JW Player JS Files');

	t3lib_extMgm::addPlugin(array('LLL:EXT:jwplayer/locallang.xml:jwplayer.title', 'jwplayer_pi1', 'EXT:jwplayer/ext_icon.gif'), 'CType');
	$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;;1-1-1, header;;;;2-2-2,pi_flexform;;;;1-1-1';
	$TCA['tt_content']['columns']['pi_flexform']['config']['ds'][','.$_EXTKEY.'_pi1'] = 'FILE:EXT:jwplayer/Configuration/FlexForms/Player.xml';

	if (TYPO3_MODE=='BE')    {
		$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_jwplayer_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'Classes/class.tx_jwplayer_wizicon.php';
	}
?>