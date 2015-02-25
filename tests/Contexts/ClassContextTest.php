<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\ClassContext;

class ClassContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\ClassContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new ClassContext ('foo', 5, 10);

		$this->assertEquals ('foo', $context->getName());
		$this->assertEquals (5, $context->startLine);
		$this->assertEquals (10, $context->endLine);
	}
}
