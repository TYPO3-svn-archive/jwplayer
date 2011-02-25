<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2009 AOE media GmbH <dev@aoemedia.de>
 * All rights reserved
 *
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
require_once dirname ( __FILE__ ) . '/../Classes/FlashConfigGenerator.php';
/**
 * test case for Tx_Jwplayer_FlashConfigGenerator
 * @package jwplayer
 */
class Tx_Jwplayer_FlashConfigGeneratorTest extends tx_phpunit_testcase {
	/**
	 * @var Tx_Jwplayer_FlashConfigGenerator
	 */
	private $flashConfigGenerator;
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->flashConfigGenerator = new Tx_Jwplayer_FlashConfigGenerator ();
	}
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		unset ( $this->flashConfigGenerator );
	}
	/**
	 * test method encode
	 * @test
	 */
	public function encode() {
		$settings = array();
		$settings['autostart'] = 0;
		$settings['bufferlength'] = 0;
		$settings['controlbar'] = 0;
		$settings['movie'] = 0;
		$settings['mute'] = 0;
		$settings['volume'] = 0;
		$imagePath = '';
		$code = $this->flashConfigGenerator->encode($settings,$imagePath);
		$this->assertType('string',$code);
	}
	/**
	 * test method decode
	 * @test
	 */
	public function decode() {
		$settings = array();
		$settings['autostart'] = TRUE;
		$settings['bufferlength'] = 1;
		$settings['controlbar'] = TRUE;
		$settings['movie'] = 'ddd';
		$settings['mute'] = FALSE;
		$settings['volume'] = 100;
		$imagePath = '';
		$code = $this->flashConfigGenerator->encode($settings,$imagePath);
		$url = $this->flashConfigGenerator->decode($code);
		$this->assertType('string',$url);
		$this->assertContains('autostart',$url);
		$this->assertContains('bufferlength',$url);
		$this->assertContains('controlbar',$url);
		$this->assertContains('file',$url);
		$this->assertContains('mute',$url);
		$this->assertContains('volume',$url);
	}
}