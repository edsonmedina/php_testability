<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\AnonymousFunctionContext;

class AnonymousFunctionContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\AnonymousFunctionContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new AnonymousFunctionContext (5, 10);

		$this->assertEquals ('<anonymous function>', $context->getName());
		$this->assertEquals (5, $context->startLine);
		$this->assertEquals (10, $context->endLine);
	}
}