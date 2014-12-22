<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\NodeWrapper;

class NodeWrapperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameWithSimpleVariable ()
	{
		$node = new PhpParser\Node\Expr\Variable ('test');

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('test', $obj->getName());
	}
}