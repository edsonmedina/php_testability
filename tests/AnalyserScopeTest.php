<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserScope;

class AnalyserScopeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::insideClass
	 */
	public function testInsideClass ()
	{
		$s = new AnalyserScope;
		$this->assertFalse ($s->insideClass());

		$s->startClass ('whatever');
		$this->assertTrue ($s->insideClass());
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::startClass
	 */
	public function testStartClassInsideClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');

		$this->setExpectedException('\Exception');
		$s->startClass ('whatever2');
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::endClass
	 */
	public function testEndClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$s->endClass();

		$this->assertFalse ($s->insideClass());
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::endClass
	 */
	public function testEndClassWithoutClass ()
	{
		$s = new AnalyserScope;

		$this->setExpectedException('\Exception');
		$s->endClass();
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::inGlobalSpace
	 */
	public function testInGlobalSpace ()
	{
		$s = new AnalyserScope;
		$this->assertTrue($s->inGlobalSpace());	
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::inGlobalSpace
	 */
	public function testInGlobalSpaceInsideClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$this->assertFalse($s->inGlobalSpace());	
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::inGlobalSpace
	 */
	public function testInGlobalSpaceInsideFunction ()
	{
		$s = new AnalyserScope;
		$s->startFunction ('whatever');
		$this->assertFalse($s->inGlobalSpace());	
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::reset
	 */
	public function testReset()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$s->reset();
		$this->assertFalse($s->insideClass());	
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::getClassName
	 */
	public function testGetClassName ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$this->assertEquals('whatever', $s->getClassName());	
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::getClassName
	 */
	public function testGetClassNameWithoutClass ()
	{
		$s = new AnalyserScope;
		$this->setExpectedException('\Exception');
		$s->getClassName();
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::getScopeName
	 */
	public function testGetScopeNameWithNoScope ()
	{
		$s = new AnalyserScope;
		$this->setExpectedException('\Exception');
		$s->getScopeName();
	}

	/**
	 * @covers edsonmedina\php_testability\AnalyserScope::getScopeName
	 */
	public function testGetScopeNameInFunction ()
	{
		$s = new AnalyserScope;
		$s->startFunction('test1');
		$this->assertEquals ('test1', $s->getScopeName());
	}
}