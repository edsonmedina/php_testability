<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserAbstractFactory;
use edsonmedina\php_testability\Contexts\DirectoryContext;

class AnalyserAbstractFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AnalyserAbstractFactory::createTraverser
	 * @uses edsonmedina\php_testability\Contexts\DirectoryContext::__construct
	 */
	public function testCreateTraverser ()
	{
		$factory = new AnalyserAbstractFactory();

		$data_stub  = $this->createMock('edsonmedina\php_testability\ReportDataInterface');
		$scope_stub = $this->createMock('edsonmedina\php_testability\AnalyserScope');

		$traverser = $factory->createTraverser (new DirectoryContext('/'));

		$this->assertInstanceOf ('PhpParser\NodeTraverser', $traverser);
	}	
}
