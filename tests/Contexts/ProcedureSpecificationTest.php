<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Contexts\ProcedureSpecification;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ClassContext;
use edsonmedina\php_testability\Contexts\MethodContext;
use edsonmedina\php_testability\Contexts\FunctionContext;
use edsonmedina\php_testability\Contexts\AnonymousFunctionContext;

class ProcedureSpecificationTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Contexts\ProcedureSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedByInvalid ()
	{
		$spec = new ProcedureSpecification ();

		$this->assertFalse($spec->isSatisfiedBy(new DirectoryContext('zzz')));
		$this->assertFalse($spec->isSatisfiedBy(new FileContext('zzz.php')));
		$this->assertFalse($spec->isSatisfiedBy(new ClassContext('zzz', 1, 2)));
	}

	/**
	 * @covers edsonmedina\php_testability\Contexts\ProcedureSpecification::isSatisfiedBy
	 */
	public function testIsSatisfiedBy ()
	{
		$spec = new ProcedureSpecification ();

		$this->assertTrue($spec->isSatisfiedBy(new MethodContext('zzz', 10, 20)));
		$this->assertTrue($spec->isSatisfiedBy(new FunctionContext('zzz', 10, 20)));
		$this->assertTrue($spec->isSatisfiedBy(new AnonymousFunctionContext(10, 20)));
	}
}