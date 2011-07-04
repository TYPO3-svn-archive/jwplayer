<?php

########################################################################
# Extension Manager/Repository config file for ext "jwplayer".
#
# Auto generated 01-07-2011 14:41
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'JW Player',
	'description' => 'JW Player Integration',
	'category' => 'plugin',
	'author' => 'Axel Jung',
	'author_email' => 'axel.jung@aoemedia.de',
	'author_company' => 'AOE Media GmbH',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.1.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.5.0-0.0.0',
			'php' => '5.2.0-0.0.0',
			'extbase' => '1.3.0',
			'fluid' => '1.3.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:27:{s:21:"ext_conf_template.txt";s:4:"8026";s:12:"ext_icon.gif";s:4:"337d";s:17:"ext_localconf.php";s:4:"c51b";s:14:"ext_tables.php";s:4:"fcdf";s:13:"locallang.xml";s:4:"8e5e";s:23:"tt_content_jwplayer.gif";s:4:"a450";s:18:"Classes/Config.php";s:4:"8fd7";s:32:"Classes/FlashConfigGenerator.php";s:4:"00e3";s:37:"Classes/class.tx_jwplayer_wizicon.php";s:4:"c712";s:42:"Classes/Configuration/ExtensionManager.php";s:4:"8a80";s:39:"Classes/Controller/PlayerController.php";s:4:"9d83";s:40:"Classes/ViewHelpers/ScriptViewHelper.php";s:4:"0a79";s:34:"Configuration/FlexForms/Player.xml";s:4:"e102";s:34:"Configuration/TypoScript/setup.txt";s:4:"6ccb";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"bfeb";s:45:"Resources/Private/Templates/Player/Index.html";s:4:"954b";s:35:"Resources/Public/Js/tx_jw_player.js";s:4:"741d";s:68:"Resources/Public/Player/JW Player Embedding and JavaScript Guide.pdf";s:4:"5956";s:55:"Resources/Public/Player/JW Player Quick Start Guide.pdf";s:4:"61e3";s:35:"Resources/Public/Player/jwplayer.js";s:4:"f417";s:35:"Resources/Public/Player/license.txt";s:4:"8a66";s:34:"Resources/Public/Player/player.swf";s:4:"f1e6";s:35:"Resources/Public/Player/preview.jpg";s:4:"31d7";s:35:"Resources/Public/Player/readme.html";s:4:"5268";s:36:"Resources/Public/Player/swfobject.js";s:4:"6990";s:33:"Resources/Public/Player/video.mp4";s:4:"7ae2";s:34:"Tests/FlashConfigGeneratorTest.php";s:4:"7d40";}',
	'suggests' => array(
	),
);

?>