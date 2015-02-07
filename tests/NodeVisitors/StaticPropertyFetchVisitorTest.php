<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor;

class StaticPropertyFetchVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		                   ->disableOriginalConstructor()
		                   ->setMethods(array('addIssue'))
		                   ->getMock();	

		$this->scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		                    ->disableOriginalConstructor()
		                    ->getMock();	

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticPropertyFetch')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\AnalyserAbstractFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->data->expects($this->never())->method('addIssue');
		
		$visitor = new StaticPropertyFetchVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeFetchingFromSelf()
	{
		$this->data->expects($this->never())->method('addIssue');

		// visitor
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods (array('isFetchingFromSelf'))
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (true);

		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::isFetchingFromSelf
	 */
	public function testIsFetchingFromSelfOutsideOfClass()
	{
		$this->data->expects($this->never())->method('addIssue');

		$this->scope->method ('insideClass')->willReturn (false);

		$visitor = new StaticPropertyFetchVisitor ($this->data, $this->scope, $this->factory);
		$this->assertFalse ($visitor->isFetchingFromSelf ($this->node));
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNode()
	{
		$this->data->expects($this->once())->method('addIssue');
		
		// visitor
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods (array('isFetchingFromSelf'))
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (false);

		$visitor->leaveNode ($this->node);
	}
}