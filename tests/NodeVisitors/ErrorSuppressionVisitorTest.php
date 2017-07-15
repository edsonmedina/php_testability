<?php

use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\StaticCall;
use edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

require_once __DIR__.'/../../vendor/autoload.php';

class ErrorSuppressionVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
    private $stack;
    private $wrongNode;

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
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new ErrorSuppressionVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder(ErrorSuppressionVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$node = $this->getMockBuilder (ErrorSuppress::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder(ErrorSuppressionVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = $this->getMockBuilder (ErrorSuppress::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}
}
