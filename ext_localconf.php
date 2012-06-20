<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');

	// Video plugin
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Pi1',
		array(
			'Player' => 'index,showVideo',
		),
		array(
			'Player' => 'showVideo',
		),
		'CType'
	);

	// Video plugin via eID
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Pi2',
		array(
			'AjaxPlayer' => 'index,showVideo',
		),
		array(
			'AjaxPlayer' => 'showVideo',
		)
	);

	t3lib_extMgm::addTypoScript(
		$_EXTKEY,
		'setup',
		'plugin.tx_jwplayer.settings.ajaxPlayer {
			table = tt_news
			field = tx_jwplayerttnews_movie
		}'
	);

	$TYPO3_CONF_VARS['FE']['eID_include']['tx_jwplayer_ajax'] = 'EXT:jwplayer/Resources/Private/Eid/ajax.php';
?>
