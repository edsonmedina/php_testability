<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class GlobalFunctionCallVisitorTest extends PHPUnit\Framework\TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');
		
		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();

		$this->node = $this->getMockBuilder ('PhpParser\Node\Stmt\Global_')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new GlobalFunctionCallVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalScope ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor')
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$visitor->leaveNode ($node);
	}
}
