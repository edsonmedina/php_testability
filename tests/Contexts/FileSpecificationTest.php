<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\FileSpecification;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ClassContext;

class FileSpecificationTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\FileSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedByInvalid ()
	{
		$spec = new FileSpecification ();

		$this->assertFalse($spec->isSatisfiedBy(new DirectoryContext('zzz')));
		$this->assertFalse($spec->isSatisfiedBy(new ClassContext('zzz', 1, 2)));
	}

	/**
	 * @covers edsonmedina\php_testability\Contexts\FileSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedBy ()
	{
		$spec = new FileSpecification ();

		$this->assertTrue($spec->isSatisfiedBy(new FileContext('zzz.php')));
	}
}