<?php

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Global_;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class GlobalVarVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
    private $stack;
    private $node;
    private $node2;

    public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder (ContextStack::class)
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();

		$this->node = $this->getMockBuilder (Global_::class)
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->node2 = $this->getMockBuilder (StaticCall::class)
		                    ->disableOriginalConstructor()
		                    ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = new GlobalVarVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->node2);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		
		$visitor = $this->getMockBuilder (GlobalVarVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->method ('inGlobalScope')->willReturn (true);
		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');
		
		$visitor = $this->getMockBuilder (GlobalVarVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->method ('inGlobalScope')->willReturn (false);
		$visitor->leaveNode ($this->node);
	}
}
