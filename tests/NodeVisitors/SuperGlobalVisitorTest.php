<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor;

class SuperGlobalVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->data->expects($this->never())->method('addIssue');

		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->data->expects($this->never())->method('addIssue');

		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ArrayDimFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}
}
