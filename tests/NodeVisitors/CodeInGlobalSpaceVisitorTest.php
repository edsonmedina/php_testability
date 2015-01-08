<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor;

class CodeInGlobalSpaceVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                      ->getMock();

		$this->nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                          ->disableOriginalConstructor()
		                          ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testLeaveNodeNotInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')
		            ->willReturn (false);

		$visitor = new CodeInGlobalSpaceVisitor ($this->data, $this->scope, $this->factory);
		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNodeWithAllowedObjInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$this->nodewrapper->method ('isAllowedOnGlobalSpace')->willReturn (true);

		$this->factory->method ('getNodeWrapper')->willReturn ($this->nodewrapper);

		$visitor = new CodeInGlobalSpaceVisitor ($this->data, $this->scope, $this->factory);
		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$this->nodewrapper->method ('isAllowedOnGlobalSpace')->willReturn (false);

		$this->factory->method ('getNodeWrapper')->willReturn ($this->nodewrapper);

		$this->node->method ('getLine')->willReturn (7);

		$this->data->expects($this->once())->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('code_on_global_space')
		       );

		$visitor = new CodeInGlobalSpaceVisitor ($this->data, $this->scope, $this->factory);
		$visitor->enterNode ($this->node);
	}
}