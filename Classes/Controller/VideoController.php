<?php

/**
 * Video controller
 *
 * @package TYPO3
 * @subpackage tx_jwplayer
 */
class Tx_Jwplayer_Controller_VideoController extends Tx_Jwplayer_Controller_AbstractPlayerController {

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
		$this->playlist = $this->getSetting('moviesection');
	}

	/**
	 * @return string
	 */
	public function indexAction() {
		parent::indexAction();

		if((boolean) $this->getSetting('add_metatags') === TRUE) {
			// create metaTags
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:description" content="' . htmlspecialchars($this->getSetting('metatag_description')) . '"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="video"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta name="medium" content="video"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video" content="' . $this->createUrlToSinglePlayer() . '"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:type" content="application/x-shockwave-flash"/>' );
		}
	}

	/**
	 *	Check count of files and create a playlist
	 *
	 * @return void
	 */
	protected function setPlayList() {

		if ( count( $this->playlist ) > 1 ) { // check count of movies. generate playliste when more than one movie was set
			foreach( $this->playlist as $item ) {
				$movie = $this->getMovieList( $item );
				$playlist[] = array(
					'title' => $item['movieitem']['title'],
					'description' => $item['movieitem']['description'],
					'duration' => ($item['movieitem']['duration']) ? $item['movieitem']['duration'] : 0,
					'file' => ( $movie['flash'] ) ? $movie['flash'] : $movie['url'],
					'image' => $this->getUploadPath( $item['movieitem']['image'] )
				);
			}
			$this->view->assign( 'playlistItems', $playlist );

		} else {
			if(is_array($this->playlist)){
				$itemArray = array_shift( $this->playlist );

				if( $movieList = $this->getMovieList( $itemArray ) ) {
					$previewImagePath = $this->getUploadPath( $itemArray['movieitem']['image'] );

					$this->view->assign ( 'file_flash', ( !empty( $movieList['flash'] ) ) ? $movieList['flash'] : $movieList['url'] );
					$this->view->assign ( 'files_html5', $movieList['html5'] );
					$this->view->assign ( 'image', $previewImagePath );

					// add movie specific meta tags for facebook
					if((boolean) $this->settings['add_metatags'] === TRUE) {

						$title = empty($this->settings['metatag_title']) ? $itemArray['movieitem']['file'] : $this->settings['metatag_title'];
						$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:title" content="'.htmlspecialchars($title).'"/>' );

						# TODO: Render image to 50x50 PX
						$imgPath = $this->removeLastChar(t3lib_div::getIndpEnv('TYPO3_SITE_URL')) . $previewImagePath;
						$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:image" content="'.$imgPath.'">' );
					}
				}
			}
		}
	}

	/**
	 *	Generate a list with given movie formats
	 *
	 *	@param array $itemArray
	 * @return array
	 */
	protected function getMovieList( $itemArray ) {
		$movieArray = array(
			'flash' => '',
			'html5' => array(),
			'url' => ''
		);

		// flashhtml5
		if( $itemArray['movieitem']['file_flashhtml5'] ) {
			$flashhtml5 = $this->solveFilePath( $itemArray['movieitem']['file_flashhtml5'] );
			$movieArray['flash'] = $flashhtml5;
			$movieArray['html5'][ pathinfo( $flashhtml5, PATHINFO_EXTENSION ) ] = $flashhtml5;
		}

		// flash
		if( $itemArray['movieitem']['file_flash'] ) {
			$movieArray['flash'] = $this->solveFilePath( $itemArray['movieitem']['file_flash'] );
		}

		// html5
		if( $itemArray['movieitem']['file_ogv'] ) {
			$ogv = $this->solveFilePath( $itemArray['movieitem']['file_ogv'] );
			$movieArray['html5'][ pathinfo( $ogv, PATHINFO_EXTENSION ) ] = $ogv;
		}

		if( $itemArray['movieitem']['file_webm'] ) {
			$webm = $this->solveFilePath( $itemArray['movieitem']['file_webm'] );
			$movieArray['html5'][ pathinfo( $webm, PATHINFO_EXTENSION ) ] = $webm;
		}

		// url
		$movieArray['url'] = $this->solveFilePath( $itemArray['movieitem']['url'], 'url' );

		if( empty( $movieArray['flash'] ) && empty( $movieArray['url'] ) ) {
			return false;
		}

		return $movieArray;
	}

}

?>
