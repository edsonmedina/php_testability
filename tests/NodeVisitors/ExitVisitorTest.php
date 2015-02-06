<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ExitVisitor;

class ExitVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$factory = $this->getMock('edsonmedina\php_testability\AnalyserAbstractFactory');

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
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (true);

		$factory = $this->getMock ('edsonmedina\php_testability\AnalyserAbstractFactory');

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

		$data->expects($this->once())->method('addIssue');

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->setMethods(array('inGlobalSpace'))
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);

		// factory
		$factory = $this->getMock ('edsonmedina\php_testability\AnalyserAbstractFactory');

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ExitVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	
}