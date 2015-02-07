<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor;

class ErrorSuppressionVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		                   ->disableOriginalConstructor()
		                   ->setMethods(array('addIssue'))
		                   ->getMock();	

		$this->scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		                    ->disableOriginalConstructor()
		                    ->getMock();	

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\ErrorSuppress')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->node2 = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\AnalyserAbstractFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->data->expects($this->never())->method('addIssue');

		$visitor = new ErrorSuppressionVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node2);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->data->expects($this->never())->method('addIssue');
		
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$visitor = new ErrorSuppressionVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->data->expects($this->once())->method('addIssue');
		
		$this->scope->method ('inGlobalSpace')->willReturn (false);

		$visitor = new ErrorSuppressionVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node);
	}
}
