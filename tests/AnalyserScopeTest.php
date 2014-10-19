<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\AnalyserScope;

class AnalyserScopeTest extends PHPUnit_Framework_TestCase
{
	public function testInsideClassOrTrait ()
	{
		$s = new AnalyserScope;
		$this->assertFalse ($s->insideClassOrTrait());

		$s->startClass ('whatever');
		$this->assertTrue ($s->insideClassOrTrait());

		$s->endClass();
		$s->startTrait ('whatever2');
		$this->assertTrue ($s->insideClassOrTrait());
	}
}