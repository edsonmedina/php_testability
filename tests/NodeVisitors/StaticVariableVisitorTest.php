<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor;

class StaticVariableVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->factory = $this->getMock('edsonmedina\php_testability\AnalyserAbstractFactory');
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->data->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$this->data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->anything(),
		           $this->equalTo('someScopeName')
		       );

		// scope
		$this->scope->method ('getScopeName')->willReturn ('someScopeName');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Static_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}	
}