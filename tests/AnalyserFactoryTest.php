<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserFactory;

class AnalyserFactoryTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AnalyserFactory::create
	 */
	public function testCreate ()
	{
		$factory = new AnalyserFactory();
		$this->assertInstanceOf ('edsonmedina\php_testability\Analyser', $factory->create());
	}	
}