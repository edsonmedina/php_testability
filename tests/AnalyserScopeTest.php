<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserScope;

class AnalyserScopeTest extends PHPUnit_Framework_TestCase
{
	public function testInsideClass ()
	{
		$s = new AnalyserScope;
		$this->assertFalse ($s->insideClass());

		$s->startClass ('whatever');
		$this->assertTrue ($s->insideClass());
	}

	public function testStartClassInsideClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');

		$this->setExpectedException('\Exception');
		$s->startClass ('whatever2');
	}

	public function testEndClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$s->endClass();

		$this->assertFalse ($s->insideClass());
	}

	public function testEndClassWithoutClass ()
	{
		$s = new AnalyserScope;

		$this->setExpectedException('\Exception');
		$s->endClass();
	}

	public function testInGlobalSpace ()
	{
		$s = new AnalyserScope;
		$this->assertTrue($s->inGlobalSpace());	
	}

	public function testInGlobalSpaceInsideClass ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$this->assertFalse($s->inGlobalSpace());	
	}

	public function testInGlobalSpaceInsideFunction ()
	{
		$s = new AnalyserScope;
		$s->startFunction ('whatever');
		$this->assertFalse($s->inGlobalSpace());	
	}

	public function testReset()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$s->reset();
		$this->assertFalse($s->insideClass());	
	}

	public function testGetClassName ()
	{
		$s = new AnalyserScope;
		$s->startClass ('whatever');
		$this->assertEquals('whatever', $s->getClassName());	
	}

	public function testGetClassNameWithoutClass ()
	{
		$s = new AnalyserScope;
		$this->setExpectedException('\Exception');
		$s->getClassName();
	}
}