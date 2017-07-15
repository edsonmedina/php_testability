<?php

use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class StaticPropertyFetchVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
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

		$this->node = $this->getMockBuilder (StaticPropertyFetch::class)
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		
		$visitor = new StaticPropertyFetchVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeFetchingFromSelf()
	{
		$this->stack->expects($this->never())->method('addIssue');

		// visitor
		$visitor = $this->getMockBuilder (StaticPropertyFetchVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods (['isFetchingFromSelf'])
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (true);

		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNode()
	{
		$this->stack->expects($this->once())->method('addIssue');
		
		// visitor
		$visitor = $this->getMockBuilder (StaticPropertyFetchVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods (['isFetchingFromSelf'])
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (false);

		$visitor->leaveNode ($this->node);
	}
}