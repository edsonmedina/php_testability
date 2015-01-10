<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserFactory;

class AnalyserFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AnalyserFactory::create
	 */
	public function testCreate ()
	{
		$factory = new AnalyserFactory();

		$data_stub  = $this->getMock('edsonmedina\php_testability\ReportDataInterface');

		$this->assertInstanceOf ('edsonmedina\php_testability\Analyser', $factory->create($data_stub));
	}	
}