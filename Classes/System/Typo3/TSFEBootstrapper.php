<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2011 AOE media GmbH <dev@aoemedia.de>
 * All rights reserved
 *
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Helper class for the eid scripts to do the bootstrapping of the TSFE.
 *
 * @package AoeSolr
 * @subpackage System
 */
class tx_Jwplayer_System_Typo3_TSFEBootstrapper {
        /**
         * @var array
         */
        private $configuration;

        public function __construct() {
                $this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jwplayer']);
        }

        /**
         * This method is used to perform the TSFE bootstrapping in the eid scripts.
         *
         * @param tslib_fe $TSFE
         * @param string $controllerKey
         */
        public function run(tslib_fe &$TSFE, $controllerKey) {

                $TSFE->initFEuser();
                $TSFE->initUserGroups();
                $TSFE->checkAlternativeIdMethods();
                $TSFE->determineId();
                $TSFE->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
                $TSFE->initTemplate();
                $TSFE->getConfigArray();
                if (isset($this->configuration['eid_fullTSFEinit']) && $this->configuration['eid_fullTSFEinit'] == 1) {
                        TSpagegen::pagegenInit();
                        $TSFE->settingLanguage();
                        $TSFE->settingLocale();
                }
                $TSFE->newCObj();

                if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/Classes/System/Typo3/TSFEBootstrapper.php']['afterEidTSFEBootstrap'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/Classes/System/Typo3/TSFEBootstrapper.php']['afterEidTSFEBootstrap'] as $_funcRef)  {
                                $_params = array('controllerKey' => $controllerKey);
                                t3lib_div::callUserFunction($_funcRef,$TSFE, $_params);
                        }
                }
        }
        
        /**
         * Returns the extension name, used to bootstrap eid calls.
         *
         * @return string
         */
        public function getEidExtensionName() {
                $result = 'Jwplayer';

                if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/Classes/System/Typo3/TSFEBootstrapper.php']['getEidExtensionName'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/ClassesSystem/Typo3/TSFEBootstrapper.php']['getEidExtensionName'] as $_funcRef) {
                                $result = t3lib_div::callUserFunction($_funcRef, $result);
                        }
                }

                return $result;
        }


        /**
         * Returns namespace of the plugin.
         *
         * @return string
         */
        public function getPluginNamespace() {
                $result = 'tx_jwplayer';

                if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/Classes/System/Typo3/TSFEBootstrapper.php']['getPluginNamespace'])) {
                        foreach($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['jwplayer/Classes/System/Typo3/TSFEBootstrapper.php']['getPluginNamespace'] as $_funcRef) {
                                $result = t3lib_div::callUserFunction($_funcRef, $result);
                        }
                }

                return $result;
        }
}