<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\RootContext;

class RootContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\RootContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new RootContext ('foo');

		$this->assertEquals ('foo', $context->getName());
	}
}
