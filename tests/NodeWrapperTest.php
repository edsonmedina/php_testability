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

	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameWithVariableName ()
	{
        $name = new PhpParser\Node\Name('foo\\bar');

		$node = new PhpParser\Node\Expr\StaticCall ($name, 'dance');

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('foo\\bar::dance', $obj->getName());
	}

	// /**
	//  * @covers edsonmedina\php_testability\NodeWrapper::getName
	//  */
	// public function testGetNameNewAnonymousCall ()
	// {
	// 	// = new $test();
	// 	$var  = new PhpParser\Node\Expr\Variable ('test');
	// 	$node = new PhpParser\Node\Expr\New_ ($var);

	// 	$obj = new NodeWrapper ($node);

	// 	$this->assertEquals ('test', $obj->getName());
	// }

	// /**
	//  * @covers edsonmedina\php_testability\NodeWrapper::getName
	//  */
	// public function testGetNameArrayDim ()
	// {
	// 	// = new $foo["bar"]();
	// 	$arr  = new PhpParser\Node\Expr\Variable ('foo');
	// 	$dim  = new PhpParser\Node\Scalar\String ('bar');

	// 	$subnode  = new PhpParser\Node\Expr\ArrayDimFetch ($arr, $dim);
	// 	$node = new PhpParser\Node\Expr\New_ ($subnode);

	// 	$obj = new NodeWrapper ($node);

	// 	$this->assertEquals ('$foo["bar"]', $obj->getName());
	// }
}