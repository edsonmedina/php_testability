<?php

use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\StaticCall;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\ExitVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class ExitVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;

    /** @var ContextStack|PHPUnit_Framework_MockObject_MockObject */
    private $stack;

    private $wrongNode;

    private $node;

    public function setup ()
	{
		$this->context = new RootContext ('/');
		
		$this->stack = $this->getMockBuilder (ContextStack::class)
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();

		$this->wrongNode = $this->getMockBuilder (StaticCall::class)
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->node = $this->getMockBuilder (Exit_::class)
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}
	
	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new ExitVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder(ExitVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		/** @var ExitVisitor $visitor */
		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder(ExitVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

        /** @var ExitVisitor $visitor */
		$visitor->leaveNode ($this->node);
	}	
}