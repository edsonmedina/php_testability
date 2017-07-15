<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\CollectionSpecification;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ClassContext;
use edsonmedina\php_testability\Contexts\TraitContext;
use edsonmedina\php_testability\Contexts\MethodContext;
use edsonmedina\php_testability\Contexts\FunctionContext;
use edsonmedina\php_testability\Contexts\AnonymousFunctionContext;

class CollectionSpecificationTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Contexts\CollectionSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedByInvalid ()
	{
		$spec = new CollectionSpecification ();

		$this->assertFalse($spec->isSatisfiedBy(new DirectoryContext('zzz')));
		$this->assertFalse($spec->isSatisfiedBy(new FileContext('zzz.php')));
		$this->assertFalse($spec->isSatisfiedBy(new MethodContext('zzz', 1, 2)));
		$this->assertFalse($spec->isSatisfiedBy(new FunctionContext('zzz', 10, 20)));
		$this->assertFalse($spec->isSatisfiedBy(new AnonymousFunctionContext(10, 20)));
	}

	/**
	 * @covers \edsonmedina\php_testability\Contexts\CollectionSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedBy ()
	{
		$spec = new CollectionSpecification ();

		$this->assertTrue($spec->isSatisfiedBy(new ClassContext('zzz', 1, 20)));
		$this->assertTrue($spec->isSatisfiedBy(new TraitContext('zzz', 1, 20)));
	}
}