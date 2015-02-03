<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserAbstractFactory;

class AnalyserAbstractFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AnalyserAbstractFactory::getDictionary
	 */
	public function testGetDictionary ()
	{
		$factory = new AnalyserAbstractFactory();
		$this->assertInstanceOf ('edsonmedina\php_testability\Dictionary', $factory->getDictionary());
	}	

	/**
	 * @covers edsonmedina\php_testability\AnalyserAbstractFactory::createTraverser
	 */
	public function testCreateTraverser ()
	{
		$factory = new AnalyserAbstractFactory();

		$data_stub  = $this->getMock('edsonmedina\php_testability\ReportDataInterface');
		$scope_stub = $this->getMock('edsonmedina\php_testability\AnalyserScope');

		$traverser = $factory->createTraverser ($data_stub, $scope_stub);

		$this->assertInstanceOf ('PhpParser\NodeTraverser', $traverser);
	}	

	/**
	 * @covers edsonmedina\php_testability\AnalyserAbstractFactory::getNodeWrapper
	 */
	public function testGetNodeWrapper ()
	{
		$factory = new AnalyserAbstractFactory();

		$node_stub  = $this->getMock('PhpParser\Node');

		$nodewrapper = $factory->getNodeWrapper ($node_stub);

		$this->assertInstanceOf ('edsonmedina\php_testability\NodeWrapper', $nodewrapper);
	}	
}