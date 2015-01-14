<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\CatchVisitor;

class CatchVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new CatchVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$scope->method('inGlobalSpace')->willReturn(true);
		              
		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Catch_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new CatchVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithChildren ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$scope->method('inGlobalSpace')->willReturn(false);
		              
        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->expects($this->once())->method ('hasChildren')->willReturn (true);

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Catch_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new CatchVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(23),
		           $this->equalTo('empty_catch'),
		           $this->equalTo('scopeName'),
		           $this->equalTo('')
		       );

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$scope->method('inGlobalSpace')->willReturn(false);
		$scope->method('getScopeName')->willReturn('scopeName');
		              
        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->expects($this->once())->method ('hasChildren')->willReturn (false);

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Catch_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (23);

		$visitor = new CatchVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}
}