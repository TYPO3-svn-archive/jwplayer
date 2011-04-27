<?php

    /***************************************************************
    *  Copyright notice
    *
    *
    *  This script is part of the Typo3 project. The Typo3 project is
    *  free software; you can redistribute it and/or modify
    *  it under the terms of the GNU General Public License as published by
    *  the Free Software Foundation; either version 2 of the License, or
    *  (at your option) any later version.
    *
    *  The GNU General Public License can be found at
    *  www.gnu.org/copyleft/gpl.html.
    *
    *  This script is distributed in the hope that it will be useful,
    *  but WITHOUT ANY WARRANTY; without even the implied warranty of
    *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *  GNU General Public License for more details.
    *
    *  This copyright notice MUST APPEAR in all copies of the script!
    ***************************************************************/

    /**
     * Class that adds the wizard icon.
     * The plugin is defined as a CType
     *
     */
    class tx_jwplayer_wizicon {
        var $extKey='jwplayer';
        var $feExtId='jwplayer_pi1';


        function proc(&$wizardItems) {
            global $BE_USER, $LANG;


            $extKeyPlugin=$this->extKey.'_pi1';
            $iconFile='tt_content_jwplayer.gif';

            //print_r($BE_USER->groupData);        

            //Nur Wenn das Plugin für aktuellen Nutzer erlaubt ist:
            if ($BE_USER->checkAuthMode('tt_content','',$extKeyPlugin,'explicitDeny')!==FALSE) {
                    $LL = $this->includeLocalLang();

                    $wizardItems['plugins_'.$this->feExtId] = array(
                    'icon' => t3lib_extMgm::extRelPath($this->extKey).$iconFile,
                    'title' => $LANG->getLLL('jwplayer.title', $LL),
                    'description' => $LANG->getLLL('jwplayer.description', $LL),
                    'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]='.$extKeyPlugin);
            }

            return $wizardItems;
        }

    /**
     * Gets the extension language array
     *
     * @return    array        the extension language array
     */
	function includeLocalLang() {
            include(t3lib_extMgm::extPath($this->extKey).'locallang_db.php');
            return $LOCAL_LANG;
        }
    }


   	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jwplayer/Classes/class.tx_jwplayer_wizicon.php']) {     
   		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jwplayer/pi1/class.tx_jwplayer_wizicon.php']);
  	}


?>