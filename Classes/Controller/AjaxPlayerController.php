<?php
/**
 * Ajax Player
 *
 * TODO
 * Use AbstractPlayerController to avoid duplicate code
 *
 * @package jwplayer
 */
class Tx_Jwplayer_Controller_AjaxPlayerController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * define separators for params and their values
	 */
	const UPLOAD_PATH = '/uploads/tx_jwplayer/';

	/**
	 * @var array
	 */
	var $allowedSkinExtension = array(
		'zip',
		'swf',
		'xml'
	);

	/**
	 * @var array
	 */
	private $conf;

	/**
	 * @var Tx_Jwplayer_FlashConfigGenerator
	 */
	private $flashConfigGenerator;

	/**
	 * View to render inline JS
	 */
	private $jsView;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$GLOBALS['TSFE']->includeTCA();
		$this->flashConfigGenerator = t3lib_div::makeInstance ( 'Tx_Jwplayer_FlashConfigGenerator' );

		$this->conf = t3lib_div::makeInstance ( 'Tx_Jwplayer_Configuration_ExtensionManager' );
	}

	/**
	 * @return string
	 */
	public function indexAction() {

		$recordUid = intval( t3lib_div::_GP('uid') );
		$recordTable = $this->getAjaxSetting('table');
		$recordField = $this->getAjaxSetting('field');

		if( $recordUid && $recordTable && $recordField )  {

			if ($GLOBALS['TCA'][$recordTable]['columns'][$recordField]['config']['allowed'] == 'tx_dam') {
				// support dam fields
				$data[$recordField] = $this->getDamUploadPath($recordField, $recordUid, $recordTable);
			} else {
				// "normal" fields containing the path
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery( $recordField, $recordTable, 'uid ='. $recordUid );
				$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
			}

			if( $data[$recordField] ) {

				$this->view->assign ( 'file_flash', '/' . $GLOBALS['TCA'][$recordTable]['columns'][$recordField]['config']['uploadfolder'] . $data[$recordField] );
			}

			$this->view->assign ( 'flashplayer', $this->conf->getPlayerPath());
			$this->view->assign ( 'autostart', $this->getSetting( 'autostart' ) );
			$this->view->assign ( 'skin', $this->getSkin() );
		}
	}

	/**
	 * @param string $type DAM name for image type ( name in tx_dam_mm_ref, field ident )
	 * @param string $uid element uid
	 * @param string $table table name where element comes from
	 * @return string
	 */
	private function getDamUploadPath( $type, $uid, $table ) {
		$path = '';
		$db = $GLOBALS['TYPO3_DB'];
		$res = $db->sql_query('SELECT td.file_path, td.file_name FROM tx_dam td JOIN tx_dam_mm_ref tdmr ON td.uid = tdmr.uid_local WHERE tdmr.tablenames=' . $db->fullQuoteStr($table, 'tx_dam_mm_ref') . ' AND tdmr.ident=' . $db->fulLQuoteStr($type, 'tx_dam_mm_ref') . ' AND tdmr.uid_foreign=' . $db->fullQuoteStr($uid, 'tx_dam_mm_ref'));
		if ($res && $db->sql_num_rows($res)) {
			$data = $db->sql_fetch_assoc($res);
			$path = $data['file_path'] . $data['file_name'];
		}
		return $path;
	}

	/**
	 * Return ajaxPlayer setting, selected by name
	 * @param string $name
	 * @return string
	 */
	protected function getAjaxSetting( $name ) {
		return $this->settings['ajaxPlayer'][ $name ];
	}

	/**
	 *	Return setting, selected by name
	 *
	 *	@param string $name
	 *	@return string
	 */
	protected function getSetting( $name ) {

		$value = ( $this->settings['overrideGlobal'][ $name ] ) ? $this->settings['overrideGlobal'][ $name ] : $this->settings[ $name ];

		return $value;
	}

	/**
	 *	Return path to skin file when is set
	 *
	 *	@return string
	 */
	protected function getSkin() {

		$skinFile = '';

		if( file_exists( PATH_site . $this->conf->getSkin() ) && $this->checkSkinFileExtension( PATH_site . $this->conf->getSkin() ) ) {
			$skinFile = $this->conf->getSkin();
		} elseif( file_exists( PATH_site . $this->getSetting( 'skin' ) ) && $this->checkSkinFileExtension( PATH_site . $this->getSetting( 'skin' ) ) ) {
			$skinFile = $this->getSetting( 'skin' );
		} else {
			$skinFile = $this->getUploadPath( $this->getSetting( 'skin' ) );
		}

		return $skinFile;
	}

	/**
	 *	Check if file extension is allow
	 *
	 *	@param string $file
	 *	@return bool
	 */
	protected function checkSkinFileExtension( $file) {
		return in_array( pathinfo( PATH_site . $file, PATHINFO_EXTENSION ) ,$this->allowedSkinExtension );
	}

	/**
	 *	Create file path, by URL checks the syntax
	 *
	 *	@param string $filename
	 *	@param string $type
	 *	@return string
	 */
	protected function solveMoviePath( $filename, $type='file' ) {
		$filePath = '';

		if( !empty( $filename ) ) {
			switch( $type ) {
				case 'file':
					$filePath = self::UPLOAD_PATH . $filename;
					break;
				case 'url':
					$filePath = ( $this->checkUrl( $filename ) ) ? $filename : '';
					break;
			}
		}

		return $filePath;
	}

	/**
	 *	Check Url
	 *
	 *	@param string $url
	 *	@return bool
	 */
	private function checkUrl( $url ) {
		$flag = false;

		if( preg_match("/[h][t]{2}[p][\:][\/]{2}[w.0-9]{0,4}[a-zA-Z0-9.-]{2,40}[.][a-zA-Z]{2,7}/", $url ) ) {
			$flag = true;
		}

		return $flag;
	}

	/**
	 * create URL to action 'showVideo'
	 *
	 * @return string
	 */
	private function createVideoUrl() {
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showVideo';
		$arguments['tx_jwplayer_pi1']['controller'] = 'Player';
		$settings = $this->settings;
		$settings['autostart']= TRUE;
		$settings['movie']= self::UPLOAD_PATH.$settings['movie'];
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $this->flashConfigGenerator->encode($settings, $this->getUploadPath() );
		$url = $this->uriBuilder->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		return $url;
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	private function getUploadPath( $filename ) {
		$path = '';
		if( $filename ){
			$path = self::UPLOAD_PATH . $filename;
		}
		return $path;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	private function removeLastChar($string) {
		return substr($string,0,-1);
	}
}

?>