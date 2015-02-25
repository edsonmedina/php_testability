<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\NodeVisitors\InterfaceVisitor;

class InterfaceVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\InterfaceVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		              ->disableOriginalConstructor()
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                ->disableOriginalConstructor()
		                ->getMock();

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new InterfaceVisitor ($stack, $context);
		$this->assertEquals ('', $visitor->enterNode ($node));
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\InterfaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		              ->disableOriginalConstructor()
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		
		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                ->disableOriginalConstructor()
		                ->getMock();

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Interface_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new InterfaceVisitor ($stack, $context);
		$this->assertEquals (PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN, $visitor->enterNode ($node));
	}
}