<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class GlobalVarVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs(array($this->context))
		                    ->setMethods(array('addIssue'))
		                    ->getMock();

		$this->node = $this->getMockBuilder ('PhpParser\Node\Stmt\Global_')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->node2 = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                    ->disableOriginalConstructor()
		                    ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = new GlobalVarVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->node2);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('inGlobalScope'))
		                ->getMock();

		$visitor->method ('inGlobalScope')->willReturn (true);
		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');
		
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('inGlobalScope'))
		                ->getMock();

		$visitor->method ('inGlobalScope')->willReturn (false);
		$visitor->leaveNode ($this->node);
	}
}
