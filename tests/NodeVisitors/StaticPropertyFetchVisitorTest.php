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

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$visitor = new StaticPropertyFetchVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeFetchingFromSelf()
	{
		// visitor
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods (array('isFetchingFromSelf'))
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn ('true');

		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::isFetchingFromSelf
	 */
	public function testIsFetchingFromSelfOutsideOfClass()
	{
		$this->scope->method ('insideClass')->willReturn (false);

		$visitor = new StaticPropertyFetchVisitor ($this->data, $this->scope, $this->factory);
		$this->assertFalse ($visitor->isFetchingFromSelf ($this->node));
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	/*
	public function testLeaveNodeInsideClass ()
	{
		// data
		$this->data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('static_var'),
		           $this->equalTo('someScopeName'),
		           $this->equalTo('$foo')
		       );

		// scope
		$this->scope->method ('getScopeName')->willReturn ('someScopeName');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->method ('getVarList')->willReturn (array (
			(object) array ('name' => 'foo')
		));

		// factory
		$this->factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Static_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new StaticPropertyFetchVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}
	*/
}