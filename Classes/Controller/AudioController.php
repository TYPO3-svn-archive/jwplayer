<?php

/**
 * Video controller
 *
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_AudioController extends Tx_Jwplayer_Controller_AbstractPlayerController {

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
		$this->playlist = $this->getSetting('audiosection');

		if((boolean) $this->getSetting('add_metatags') === TRUE) {
			// create metaTags
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:description" content="' . htmlspecialchars($this->getSetting('metatag_description')) . '"/>' );
			$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="audio"/>' );
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
		if ( count( $this->playlist ) > 1 ) { // check count of audios. generate playliste when more than one audio was set
			foreach( $this->playlist as $item ) {
				$audio = $this->getAudioList( $item );
				$playlist[] = array(
					'title' => $item['audioitem']['title'],
					'description' => $item['audioitem']['description'],
					'duration' => ($item['audioitem']['duration']) ? $item['audioitem']['duration'] : 0,
					'file' => ( $audio['file_mp3'] ) ? $audio['file_mp3'] : $audio['url'],
					'image' => $this->getUploadPath( $item['audioitem']['image'] )
				);
			}
			$this->view->assign( 'playlistItems', $playlist );
		} else {
			if(is_array($this->playlist)){
				$itemArray = array_shift( $this->playlist );
				if( $audioList = $this->getAudioList( $itemArray ) ) {
					$previewImagePath = $this->getUploadPath( $itemArray['audioitem']['image'] );
					$this->view->assign ( 'file_mp3', ($audioList['file_mp3'] ? $audioList['file_mp3'] : $audioList['url']) );
					$this->view->assign ( 'image', $previewImagePath );
				}
			}
		}
	}

	/**
	 *	Generate a list with given audio formats
	 *
	 *	@param array $itemArray
	 * @return array
	 */
	protected function getAudioList( $itemArray ) {
		$audioArray = array(
			'file_mp3' => '',
			'url' => ''
		);

		// mp3
		if( $itemArray['audioitem']['file_mp3'] ) {
			$mp3 = $this->solveFilePath( $itemArray['audioitem']['file_mp3'] );
			$audioArray['file_mp3'] = $mp3;
		}

		// url
		$audioArray['url'] = $this->solveFilePath( $itemArray['audioitem']['url'], 'url' );

		if( empty( $audioArray['file_mp3'] ) && empty( $audioArray['url'] ) ) {
			return false;
		}

		return $audioArray;
	}
}

?>