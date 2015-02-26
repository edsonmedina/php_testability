<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\IncludeVisitor;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;

class IncludeVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeWithDifferentType ()
	{
		$context = new RootContext ('/');

		$stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		              ->setConstructorArgs([$context])
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');
		              
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new IncludeVisitor ($stack, $context);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeInGlobalSpace ()
	{
		$context = new RootContext ('/');

		$stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		              ->setConstructorArgs([$context])
		              ->setMethods(['addIssue'])
		              ->getMock();

		$stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Include_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\IncludeVisitor')
		                ->setConstructorArgs([$stack, $context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);
		$visitor->leaveNode ($node);
	}	
}