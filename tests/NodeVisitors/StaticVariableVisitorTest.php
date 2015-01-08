<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor;

class StaticVariableVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('static_var'),
		           $this->equalTo('someScopeName'),
		           $this->equalTo('foo')
		       );

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('getScopeName')->willReturn ('someScopeName');

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
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Static_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new StaticVariableVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	
}