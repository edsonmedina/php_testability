<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\FileContext;

class FileContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\FileContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new FileContext ('foo');

		$this->assertEquals ('foo', $context->getName());
	}
}
