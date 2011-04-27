<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Tolleiv Nietsch (info@tolleiv.de), Martin Tepper (martin.tepper@aoemedia.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 *
 * @author	Martin Tepper <martin.tepper@aoemedia.de>
 */

define('TYPO3_MOD_PATH','../typo3conf/ext/jwplayer/');
$BACK_PATH = '../../../typo3/';
require($BACK_PATH.'init.php');
require($BACK_PATH.'template.php');

require_once(t3lib_extMgm::extPath('jwplayer').'classes/controller/PlayerController.php');

$GLOBALS['LANG']->includeLLFile('EXT:lang/locallang_wizards.xml');
$GLOBALS['LANG']->includeLLFile('EXT:jwplayer/locallang.xml');

$SOBE = t3lib_div::makeInstance('Tx_Jwplayer_Controller_PlayerController');
$SOBE->triggerAction();

?>