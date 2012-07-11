<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');

	// Video plugin
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Pi1',
		array(
			'Video' => 'index,showSingleFile',
		),
		array(
			'Video' => 'showSingleFile',
		),
		'CType'
	);

	// Audio plugin
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Pi2',
		array(
			'Audio' => 'index,showSingleFile',
		),
		array(
			'Audio' => 'showSingleFile',
		),
		'CType'
	);

	// Ajax video plugin via eID
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Ajax',
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
