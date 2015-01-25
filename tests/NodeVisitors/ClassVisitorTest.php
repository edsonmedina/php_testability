<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ClassVisitor;

class ClassVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$data    = $this->getMock('edsonmedina\php_testability\ReportData');
		$scope   = $this->getMock('edsonmedina\php_testability\AnalyserScope');
		$factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassVisitor ($data, $scope, $factory);
		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data    = $this->getMock('edsonmedina\php_testability\ReportData');
		$scope   = $this->getMock('edsonmedina\php_testability\AnalyserScope');
		$factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$data    = $this->getMock('edsonmedina\php_testability\ReportData');
		$factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');
		
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
                      ->disableOriginalConstructor()
                      ->setMethods(array ('endClass'))
                      ->getMock();

		$scope->expects($this->once())->method('endClass');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$data    = $this->getMock('edsonmedina\php_testability\ReportData');
		
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
                      ->disableOriginalConstructor()
                      ->setMethods(array ('startClass'))
                      ->getMock();

		$scope->expects($this->once())
		      ->method('startClass')
		      ->with($this->equalTo('foo'));

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		             ->disableOriginalConstructor()
		             ->getMock();

		$nodewrapper->method ('getName')->willReturn ('foo');

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		$visitor = new ClassVisitor ($data, $scope, $factory);
		$visitor->enterNode ($node);
	}
}