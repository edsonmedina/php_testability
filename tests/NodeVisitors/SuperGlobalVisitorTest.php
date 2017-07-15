<?php

use PhpParser\Node\Expr\StaticCall;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class SuperGlobalVisitorTest extends PHPUnit\Framework\TestCase
{
    private $context;
    private $stack;
    private $wrongNode;
    private $visitor;

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

		$this->visitor = $this->getMockBuilder (SuperGlobalVisitor::class)
		                      ->setConstructorArgs([$this->stack, $this->context])
		                      ->setMethods(['inGlobalScope'])
		                      ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = new SuperGlobalVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}
}
