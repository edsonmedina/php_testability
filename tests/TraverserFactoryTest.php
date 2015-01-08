<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\TraverserFactory;

class TraverserFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\TraverserFactory::getDictionary
	 */
	public function testGetDictionary ()
	{
		$factory = new TraverserFactory();
		$this->assertInstanceOf ('edsonmedina\php_testability\Dictionary', $factory->getDictionary());
	}	

	/**
	 * @covers edsonmedina\php_testability\TraverserFactory::createTraverser
	 */
	public function testCreateTraverser ()
	{
		$factory = new TraverserFactory();

		$data_stub  = $this->getMock('edsonmedina\php_testability\ReportDataInterface');
		$scope_stub = $this->getMock('edsonmedina\php_testability\AnalyserScope');

		$traverser = $factory->createTraverser ($data_stub, $scope_stub);

		$this->assertInstanceOf ('PhpParser\NodeTraverser', $traverser);
	}	

	/**
	 * @covers edsonmedina\php_testability\TraverserFactory::getNodeWrapper
	 */
	public function testGetNodeWrapper ()
	{
		$factory = new TraverserFactory();

		$node_stub  = $this->getMock('PhpParser\Node');

		$nodewrapper = $factory->getNodeWrapper ($node_stub);

		$this->assertInstanceOf ('edsonmedina\php_testability\NodeWrapper', $nodewrapper);
	}	
}