<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\VisitorAbstract;

class VisitorAbstractTest extends PHPUnit_Framework_TestCase
{
	/**
	 * This test verifies if the properties are being set (for the child classes to use)
	 * @covers edsonmedina\php_testability\VisitorAbstract::__construct
	 */
	public function testConstruct ()
	{
		$args = array (
			$this->getMock('edsonmedina\php_testability\ReportData'),
			$this->getMock('edsonmedina\php_testability\AnalyserScope'),
			$this->getMock('edsonmedina\php_testability\AnalyserAbstractFactory')
		);

		$stub = $this->getMockForAbstractClass ('edsonmedina\php_testability\VisitorAbstract', $args);

		$this->assertInstanceOf ('edsonmedina\php_testability\ReportData', PHPUnit_Framework_Assert::readAttribute($stub, 'data'));
		$this->assertInstanceOf ('edsonmedina\php_testability\AnalyserScope', PHPUnit_Framework_Assert::readAttribute($stub, 'scope'));
		$this->assertInstanceOf ('edsonmedina\php_testability\AnalyserAbstractFactory', PHPUnit_Framework_Assert::readAttribute($stub, 'factory'));
	}	
}