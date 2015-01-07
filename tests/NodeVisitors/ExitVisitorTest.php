<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ExitVisitor;
use edsonmedina\php_testability\TraverserFactory;

class ExitVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
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

		$visitor = new ExitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->getMock();

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (true);

		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ExitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
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
		           $this->equalTo('exit'),
		           $this->equalTo('someScopeName'),
		           $this->equalTo('')
		       );

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->setMethods(array('inGlobalSpace','getScopeName'))
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);
		$scope->method ('getScopeName')->willReturn ('someScopeName');

		// factory
		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->setMethods(array('getLine'))
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new ExitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	
}