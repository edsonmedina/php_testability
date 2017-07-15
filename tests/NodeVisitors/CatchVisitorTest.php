<?php

use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Name;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\CatchVisitor;

require_once __DIR__.'/../../vendor/autoload.php';

class CatchVisitorTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder(FileContext::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$node = $this->getMockBuilder (Trait_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new CatchVisitor ($stack, $context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder(FileContext::class)
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$visitor = $this->getMockBuilder(CatchVisitor::class)
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$node = $this->getMockBuilder (Catch_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithChildren ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder(FileContext::class)
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$visitor = $this->getMockBuilder(CatchVisitor::class)
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = new PhpParser\Node\Stmt\Catch_ ([new Name('Whatever')], 'x', [true]);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->once())->method('addIssue');

		$context = $this->getMockBuilder(FileContext::class)
		                ->disableOriginalConstructor()
		                ->getMock();
		              
		$visitor = $this->getMockBuilder(CatchVisitor::class)
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = new PhpParser\Node\Stmt\Catch_ ([new Name('Whatever')], 'x');

		$visitor->leaveNode ($node);
	}
}