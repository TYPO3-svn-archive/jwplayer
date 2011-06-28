<?php

/**
 * This class is a demo view helper for the Fluid templating engine.
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class Tx_Jwplayer_ViewHelpers_JsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    /**
     * Renders javascript into the basis template
     *
     * @return string javascript
     * @author Martin Tepper <martin.tepper@aoemedia.de>
     */
    public function render($lenght) {
        $dummyContent = 'Lorem ipsum dolor sit amet.';
        return substr($dummyContent, 0, $lenght);
    }
}

?>