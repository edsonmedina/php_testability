<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor;

class GlobalVarVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->node = $this->getMockBuilder ('PhpParser\Node\Stmt\Global_')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->node2 = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                      ->getMock();

		$this->nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                          ->disableOriginalConstructor()
		                          ->getMock();

		$this->nodewrapper2 = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                           ->disableOriginalConstructor()
		                           ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$visitor = new GlobalVarVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node2);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalVarVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$visitor = new GlobalVarVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node);
	}
}
