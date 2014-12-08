<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\Dictionary;

class DictionaryTest extends PHPUnit_Framework_TestCase
{
	public function testIsInternalFunction ()
	{
		$d = new Dictionary;

		// internal function
		$this->assertTrue ($d->isInternalFunction ('substr'));

		// user function
		$this->assertFalse ($d->isInternalFunction ('__autoload'));

		// non-existent function
		$this->assertFalse ($d->isInternalFunction ('blablabla123'));
	}
}