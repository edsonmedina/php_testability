<?php

use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Expr\StaticCall;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class CodeInGlobalSpaceVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
    private $stack;
    private $node;

    public function setup ()
	{
		$this->context = new RootContext ('/');
		
		$this->stack = $this->getMockBuilder (ContextStack::class)
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();

		$this->node = $this->getMockBuilder (StaticCall::class)
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testLeaveNodeNotInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder(CodeInGlobalSpaceVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope','isAllowedOnGlobalSpace'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(false);
		$visitor->expects($this->never())->method('isAllowedOnGlobalSpace');

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNodeWithAllowedObjInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder(CodeInGlobalSpaceVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope','isAllowedOnGlobalSpace'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
		$visitor->expects($this->once())->method('isAllowedOnGlobalSpace')->willReturn(true);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder(CodeInGlobalSpaceVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope','isAllowedOnGlobalSpace'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
		$visitor->expects($this->once())->method('isAllowedOnGlobalSpace')->willReturn(false);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::isAllowedOnGlobalSpace
	 */
	public function testIsAllowedOnGlobalSpace ()
	{
		$visitor = new CodeInGlobalSpaceVisitor ($this->stack, $this->context);

		// not allowed
		$this->assertFalse ($visitor->isAllowedOnGlobalSpace ($this->node));

		$functionNode = $this->getMockBuilder (Function_::class)
		                     ->disableOriginalConstructor()
		                     ->getMock();
		// allowed
		$this->assertTrue ($visitor->isAllowedOnGlobalSpace ($functionNode));
	}
}