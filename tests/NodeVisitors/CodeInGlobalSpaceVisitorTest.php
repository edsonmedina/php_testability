<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class CodeInGlobalSpaceVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');
		
		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs(array($this->context))
		                    ->setMethods(array('addIssue'))
		                    ->getMock();

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testLeaveNodeNotInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('inGlobalScope','isAllowedOnGlobalSpace'))
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(false);
		$visitor->expects($this->never())->method('isAllowedOnGlobalSpace');

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNodeWithAllowedObjInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('inGlobalScope','isAllowedOnGlobalSpace'))
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
		$visitor->expects($this->once())->method('isAllowedOnGlobalSpace')->willReturn(true);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('inGlobalScope','isAllowedOnGlobalSpace'))
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
		$visitor->expects($this->once())->method('isAllowedOnGlobalSpace')->willReturn(false);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::isAllowedOnGlobalSpace
	 */
	public function testIsAllowedOnGlobalSpace ()
	{
		$visitor = new CodeInGlobalSpaceVisitor ($this->stack, $this->context);

		// not allowed
		$this->assertFalse ($visitor->isAllowedOnGlobalSpace ($this->node));

		$functionNode = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		                     ->disableOriginalConstructor()
		                     ->getMock();
		// allowed
		$this->assertTrue ($visitor->isAllowedOnGlobalSpace ($functionNode));
	}
}