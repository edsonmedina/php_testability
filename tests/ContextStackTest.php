<?php

require_once __DIR__.'/../vendor/autoload.php';

use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ClassContext;
use edsonmedina\php_testability\Contexts\FileSpecification;

class ContextStackTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\ContextStack::__construct
	 * @covers \edsonmedina\php_testability\ContextStack::current
	 */
	public function testConstructor ()
	{
		$stack = new ContextStack (new FileContext('foo.php'));

		$current = $stack->current();

		$this->assertEquals ('foo.php', $current->getName());
	}

	/**
	 * @covers \edsonmedina\php_testability\ContextStack::__construct
	 * @covers \edsonmedina\php_testability\ContextStack::start
	 * @covers \edsonmedina\php_testability\ContextStack::current
	 */
	public function testStart ()
	{
		$stack = new ContextStack (new FileContext('foo.php'));
		$stack->start (new FileContext('bar.php'));

		$current = $stack->current();

		$this->assertEquals ('bar.php', $current->getName());
	}

	/**
	 * @covers \edsonmedina\php_testability\ContextStack::__construct
	 * @covers \edsonmedina\php_testability\ContextStack::start
	 * @covers \edsonmedina\php_testability\ContextStack::end
	 * @covers \edsonmedina\php_testability\ContextStack::current
	 * @uses \edsonmedina\php_testability\ContextStack::addChild
	 * @uses \edsonmedina\php_testability\AbstractContext::getChildren
	 */
	public function testEnd ()
	{
		$stack = new ContextStack (new FileContext('foo.php'));

		$stack->start (new FileContext('bar.php'));
		$stack->end();
		
		$stack->start (new FileContext('bar2.php'));
		$stack->end();

		$current = $stack->current();

		$this->assertEquals ('foo.php', $current->getName());
		$this->assertEquals (2, count($current->getChildren()));
	}

	/**
	 * @covers \edsonmedina\php_testability\ContextStack::__construct
	 * @covers \edsonmedina\php_testability\ContextStack::findContextOfType
	 */
	public function testFindContextOfType ()
	{
		$stack = new ContextStack (new DirectoryContext('foo/'));
		$stack->start (new FileContext('bar.php'));
		$stack->start (new ClassContext('foo_bar', 1, 100));

		$result = $stack->findContextOfType(new FileSpecification);
		$this->assertEquals ('bar.php', $result->getName());
	}

	/**
	 * @covers \edsonmedina\php_testability\ContextStack::__construct
	 * @covers \edsonmedina\php_testability\ContextStack::findContextOfType
	 */
	public function testFindContextOfTypeNotFound ()
	{
		$stack = new ContextStack (new DirectoryContext('foo/'));
		$stack->start (new ClassContext('foo_bar', 1, 100));

		$this->assertFalse ($stack->findContextOfType(new FileSpecification));
	}
}