<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class StaticVariableVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$this->stack->expects($this->once())->method('addIssue');

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Static_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}	
}