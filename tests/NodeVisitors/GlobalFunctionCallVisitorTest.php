<?php

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Global_;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class GlobalFunctionCallVisitorTest extends PHPUnit\Framework\TestCase
{
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

		$this->wrongNode = $this->getMockBuilder (StaticCall::class)
		                        ->disableOriginalConstructor()
		                        ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new GlobalFunctionCallVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalScope ()
	{
		$node = $this->getMockBuilder (FuncCall::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder(GlobalFunctionCallVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$visitor->leaveNode ($node);
	}
}
