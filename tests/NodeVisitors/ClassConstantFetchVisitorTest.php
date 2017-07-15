<?php

use PhpParser\Node\Expr\ClassConstFetch;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor;

require_once __DIR__.'/../../vendor/autoload.php';


class ClassConstantFetchVisitorTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$stack = $this->getMockBuilder(ContextStack::class)
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder(FileContext::class)
		                ->disableOriginalConstructor()
		                ->getMock();

		$visitor = new ClassConstantFetchVisitor ($stack, $context);
		$visitor->leaveNode ($node);

	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (ClassConstFetch::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$context = $this->getMockBuilder(FileContext::class)
		                ->disableOriginalConstructor()
		                ->getMock();

		$visitor = $this->getMockBuilder(ClassConstantFetchVisitor::class)
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$visitor->leaveNode ($node);
	}
}