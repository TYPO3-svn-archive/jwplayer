<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Tolleiv Nietch
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
 * ************************************************************* */

/**
 * View helper for rendering countries
 *
 * = Examples =
 */
class Tx_Jwplayer_ViewHelpers_ScriptViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    /**
     * @param string $content
     * @param bool $inline
     * @param bool $compress
     * @param bool $forceOnTop
     * @return void
     */
	public function render($inline=TRUE, $compress=FALSE, $forceOnTop=FALSE, $pageRenderer=TRUE) {
        	$content = $this->renderChildren();
        	/** @var $pagerender t3lib_pagerenderer */

		if ( $pageRenderer == true ) {
        		$pagerender = $GLOBALS['TSFE']->getPageRenderer();
        		$pagerender->addJsFooterInlineCode(md5($content), $content, $compress, $forceOnTop);
		} else {
			return $content;
		}
	}
}
?>
