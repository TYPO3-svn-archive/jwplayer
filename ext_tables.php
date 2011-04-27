<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'JW Player'
);

t3lib_div::loadTCA('tt_content');

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = array(
    0 => 'LLL:EXT:jwplayer/locallang.xml:jwplayer.title',
    1 => 'jwplayer',
    2 => 'i/tt_content_image.gif',
);

$TCA['tt_content']['types']['list']['subtypes_excludelist']['jwplayer_pi1'] = 'layout,recursive,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist']['jwplayer_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( 'jwplayer_pi1', 'FILE:EXT:jwplayer/Configuration/FlexForms/Player.xml');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JW Player JS Files');

if (TYPO3_MODE=='BE')    {
    $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_jwplayer_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'classes/class.tx_jwplayer_wizicon.php';
}
?>