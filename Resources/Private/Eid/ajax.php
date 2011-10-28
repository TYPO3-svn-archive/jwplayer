<?php

        tslib_eidtools::connectDB();
        tslib_eidtools::initTCA();

        $TSFE = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], t3lib_div::_GP('id'), t3lib_div::_GP('type'), true);
        $TSFEBootstrapper = t3lib_div::makeInstance('tx_Jwplayer_System_Typo3_TSFEBootstrapper');
        $TSFEBootstrapper->run($TSFE,'ajax');

        $pluginConfig   = $TSFEBootstrapper->getTypoScriptConfigurationPath();
        $extensionName  = $TSFEBootstrapper->getEidExtensionName();

        $config = array(
                'userFunc'		=> 'tx_extbase_core_bootstrap->run',
                'pluginName'    => 'Pi2',
                'extensionName' => $extensionName,
                'settings'		=> $pluginConfig
        );


        require_once t3lib_extMgm::extPath('extbase') . 'Classes/Core/Bootstrap.php';

        $cObj					= t3lib_div::makeInstance('tslib_cObj');
        $bootstrap				= t3lib_div::makeInstance('Tx_Extbase_Core_Bootstrap');
        $bootstrap->cObj		= $cObj;

        $content				= '';
        $response				= $bootstrap->run($content, $config);

        $responseContentType	= 'text/plain';

        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-Type: '.$responseContentType.'; charset=' . $TSFE->renderCharset);
        header('Content-Transfer-Encoding: 8bit');

        echo $response;

?>