<?php

use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Exit_;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\IncludeVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__.'/../../vendor/autoload.php';

class IncludeVisitorTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeWithDifferentType ()
	{
		$context = new RootContext ('/');

		$stack = $this->getMockBuilder (ContextStack::class)
		              ->setConstructorArgs([$context])
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		              
		$node = $this->getMockBuilder (Exit_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new IncludeVisitor ($stack, $context);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeInGlobalSpace ()
	{
		$context = new RootContext ('/');

		$stack = $this->getMockBuilder (ContextStack::class)
		              ->setConstructorArgs([$context])
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (Include_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder(IncludeVisitor::class)
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);
		$visitor->leaveNode ($node);
	}	
}