<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor;

class ClassConstantFetchVisitorTest extends PHPUnit_Framework_TestCase
{
	private $prophet;
	private $stack;
	private $context;

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                ->disableOriginalConstructor()
		                ->getMock();

		$visitor = new ClassConstantFetchVisitor ($stack, $context);
		$visitor->leaveNode ($node);

	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		              ->disableOriginalConstructor()
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ClassConstFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                ->disableOriginalConstructor()
		                ->getMock();

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor')
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$visitor->leaveNode ($node);
	}
}