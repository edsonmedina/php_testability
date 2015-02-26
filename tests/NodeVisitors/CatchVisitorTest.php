<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\NodeVisitors\CatchVisitor;
use PhpParser\Node\Name;

class CatchVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		              ->disableOriginalConstructor()
		              ->getMock();

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new CatchVisitor ($stack, $context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CatchVisitor')
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Catch_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNodeWithChildren ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CatchVisitor')
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = new PhpParser\Node\Stmt\Catch_ (new Name('Whatever'), 'x', [true]);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CatchVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		             ->disableOriginalConstructor()
		             ->setMethods(['addIssue'])
		             ->getMock();

		$stack->expects($this->once())->method('addIssue');

		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                ->disableOriginalConstructor()
		                ->getMock();
		              
		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CatchVisitor')
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = new PhpParser\Node\Stmt\Catch_ (new Name('Whatever'), 'x');

		$visitor->leaveNode ($node);
	}
}