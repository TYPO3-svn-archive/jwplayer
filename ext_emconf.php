<?php

########################################################################
# Extension Manager/Repository config file for ext "jwplayer".
#
# Auto generated 14-03-2011 08:17
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
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.14',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.3.0-0.0.0',
			'php' => '5.2.0-0.0.0',
			'extbase' => '1.3.0',
			'fluid' => '1.2.1',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:21:{s:21:"ext_conf_template.txt";s:4:"e30c";s:12:"ext_icon.gif";s:4:"337d";s:17:"ext_localconf.php";s:4:"78d7";s:14:"ext_tables.php";s:4:"3e39";s:32:"Classes/FlashConfigGenerator.php";s:4:"00e3";s:39:"Classes/Controller/PlayerController.php";s:4:"6b44";s:34:"Configuration/FlexForms/Player.xml";s:4:"1c80";s:34:"Configuration/TypoScript/setup.txt";s:4:"6ccb";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"2fcb";s:45:"Resources/Private/Templates/Player/Index.html";s:4:"75cd";s:35:"Resources/Public/Js/tx_jw_player.js";s:4:"ca7c";s:68:"Resources/Public/Player/JW Player Embedding and JavaScript Guide.pdf";s:4:"5956";s:35:"Resources/Public/Player/jwplayer.js";s:4:"6ff2";s:35:"Resources/Public/Player/license.txt";s:4:"8a66";s:34:"Resources/Public/Player/player.swf";s:4:"f97a";s:35:"Resources/Public/Player/preview.jpg";s:4:"31d7";s:35:"Resources/Public/Player/readme.html";s:4:"e057";s:36:"Resources/Public/Player/swfobject.js";s:4:"6990";s:33:"Resources/Public/Player/video.mp4";s:4:"7ae2";s:30:"Resources/Public/Player/yt.swf";s:4:"eb45";s:34:"Tests/FlashConfigGeneratorTest.php";s:4:"7d40";}',
	'suggests' => array(
	),
);

?>