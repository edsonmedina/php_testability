<?php

use PhpParser\NodeTraverser;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use edsonmedina\php_testability\Contexts\DirectoryContext;

require_once __DIR__.'/../vendor/autoload.php';

class AnalyserAbstractFactoryTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\AnalyserAbstractFactory::createTraverser
	 * @uses \edsonmedina\php_testability\Contexts\DirectoryContext::__construct
	 */
	public function testCreateTraverser ()
	{
		$factory = new AnalyserAbstractFactory();

		$traverser = $factory->createTraverser (new DirectoryContext('/'));

		$this->assertInstanceOf (NodeTraverser::class, $traverser);
	}	
}
