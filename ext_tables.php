<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'JW Player'
);

$TCA['tt_content']['types']['list']['subtypes_excludelist']['jwplayer_pi1'] = 'layout,recursive,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist']['jwplayer_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( 'jwplayer_pi1', 'FILE:EXT:jwplayer/Configuration/FlexForms/Player.xml');
?>