<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\DirectorySpecification;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ClassContext;

class DirectorySpecificationTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\DirectorySpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedByInvalid ()
	{
		$spec = new DirectorySpecification ();

		$this->assertFalse($spec->isSatisfiedBy(new FileContext('zzz')));
		$this->assertFalse($spec->isSatisfiedBy(new ClassContext('zzz', 1, 2)));
	}

	/**
	 * @covers edsonmedina\php_testability\Contexts\DirectorySpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedBy ()
	{
		$spec = new DirectorySpecification ();

		$this->assertTrue($spec->isSatisfiedBy(new DirectoryContext('zzz')));
	}
}