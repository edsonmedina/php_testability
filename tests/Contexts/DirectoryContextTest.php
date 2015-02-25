<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\DirectoryContext;

class DirectoryContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\DirectoryContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new DirectoryContext ('foo');

		$this->assertEquals ('foo', $context->getName());
	}
}
