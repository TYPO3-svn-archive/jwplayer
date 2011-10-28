<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Pi1',
		array(
			'Player' => 'index,showVideo',
		),
		array(
			'Player' => 'showVideo',
		)
	);
	
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


	$TYPO3_CONF_VARS['FE']['eID_include']['tx_jwplayer_ajax'] = 'EXT:jwplayer/Resources/Private/Eid/ajax.php';
?>
