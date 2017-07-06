<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\MethodContext;

class MethodContextTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\MethodContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new MethodContext ('foo', 5, 10);

		$this->assertEquals ('foo', $context->getName());
		$this->assertEquals (5, $context->startLine);
		$this->assertEquals (10, $context->endLine);
	}
}
