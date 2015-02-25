<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\TraitContext;

class TraitContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\TraitContext::__construct
	 */
	public function testConstructor ()
	{
		$context = new TraitContext ('foo', 5, 10);

		$this->assertEquals ('foo', $context->getName());
		$this->assertEquals (5, $context->startLine);
		$this->assertEquals (10, $context->endLine);
	}
}
