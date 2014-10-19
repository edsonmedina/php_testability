<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserScope;

class AnalyserScopeTest extends PHPUnit_Framework_TestCase
{
	public function testinsideClass ()
	{
		$s = new AnalyserScope;
		$this->assertFalse ($s->insideClass());

		$s->startClass ('whatever');
		$this->assertTrue ($s->insideClass());
	}
}