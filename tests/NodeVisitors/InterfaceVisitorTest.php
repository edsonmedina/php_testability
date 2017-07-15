<?php

use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\InterfaceVisitor;

require_once __DIR__.'/../../vendor/autoload.php';

class InterfaceVisitorTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\InterfaceVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder(FileContext::class)
		                ->disableOriginalConstructor()
		                ->getMock();

		$node = $this->getMockBuilder (Trait_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new InterfaceVisitor ($stack, $context);
		$this->assertEquals ('', $visitor->enterNode ($node));
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\InterfaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$stack = $this->getMockBuilder(ContextStack::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder(FileContext::class)
		                ->disableOriginalConstructor()
		                ->getMock();

		$node = $this->getMockBuilder (Interface_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new InterfaceVisitor ($stack, $context);
		$this->assertEquals (PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN, $visitor->enterNode ($node));
	}
}