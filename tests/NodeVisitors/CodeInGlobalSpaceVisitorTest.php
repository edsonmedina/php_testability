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
		                    ->setMethods(array('inGlobalSpace'))
		                    ->getMock();	

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\AnalyserAbstractFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testLeaveNodeNotInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (false);

		// do not report!
		$this->data->expects ($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods(array('isAllowedOnGlobalSpace'))
		                ->getMock();	

		$visitor->expects ($this->never())->method ('isAllowedOnGlobalSpace');

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNodeWithAllowedObjInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		// do not report!
		$this->data->expects ($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods(array('isAllowedOnGlobalSpace'))
		                ->getMock();	

		$visitor->method ('isAllowedOnGlobalSpace')->willReturn (true);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$this->data->expects($this->once())->method('addIssue')
		     ->with(
		         $this->anything()
		     );

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor')
		                ->setConstructorArgs(array($this->data, $this->scope, $this->factory))
		                ->setMethods(array('isAllowedOnGlobalSpace'))
		                ->getMock();	

		$visitor->method ('isAllowedOnGlobalSpace')->willReturn (false);

		$visitor->enterNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\CodeInGlobalSpaceVisitor::isAllowedOnGlobalSpace
	 */
	public function testIsAllowedOnGlobalSpace ()
	{
		$visitor = new CodeInGlobalSpaceVisitor ($this->data, $this->scope, $this->factory);

		$this->data->expects ($this->never())->method('addIssue');

		// not allowed
		$this->assertFalse ($visitor->isAllowedOnGlobalSpace ($this->node));

		$functionNode = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		                     ->disableOriginalConstructor()
		                     ->getMock();
		// allowed
		$this->assertTrue ($visitor->isAllowedOnGlobalSpace ($functionNode));
	}
}