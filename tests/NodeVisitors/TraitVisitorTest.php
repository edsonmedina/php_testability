<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\TraitVisitor;

class TraitVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new TraitVisitor ($data, $scope, $factory);
		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new TraitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->setMethods(array('endClass'))
		              ->getMock();

		$scope->expects($this->once())->method('endClass');

		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new TraitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\TraitVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->setMethods(array('startClass'))
		              ->getMock();

		$scope->expects($this->once())->method('startClass')->with('foo');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		             ->disableOriginalConstructor()
		             ->getMock();

		$nodewrapper->method ('getName')->willReturn ('foo');

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new TraitVisitor ($data, $scope, $factory);
		$visitor->enterNode ($node);
	}	
}