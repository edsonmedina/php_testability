<?php

use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Expr\Eval_;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class StaticVariableVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
    private $stack;

    public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder (ContextStack::class)
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder(Eval_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$this->stack->expects($this->once())->method('addIssue');

		// node
		$node = $this->getMockBuilder (Static_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}	
}