<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\NodeWrapper;

class NodeWrapperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::__construct
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameWithSimpleVariable ()
	{
		$node = new PhpParser\Node\Expr\Variable ('test');

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('test', $obj->getName());
	}

	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::__construct
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameWithVariableName ()
	{
        $name = new PhpParser\Node\Name('foo\\bar');

		$node = new PhpParser\Node\Expr\StaticCall ($name, 'dance');

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('foo\\bar::dance', $obj->getName());
	}

	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::__construct
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameWithVariableClass ()
	{
		// $test::bar()
		$name = new PhpParser\Node\Expr\Variable ('test');

		$node = new PhpParser\Node\Expr\StaticCall ($name, 'bar');

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('<variable>::bar', $obj->getName());
	}

	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::__construct
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameStaticCallVariable ()
	{
		// foo::$bar();
		$class  = new PhpParser\Node\Name ('foo');
		$method = new PhpParser\Node\Expr\Variable ('bar');
		$node   = new PhpParser\Node\Expr\StaticCall ($class, $method);

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('foo::<variable>', $obj->getName());
	}

	/**
	 * @covers edsonmedina\php_testability\NodeWrapper::__construct
	 * @covers edsonmedina\php_testability\NodeWrapper::getName
	 */
	public function testGetNameArrayDim ()
	{
		// Test::$foo["bar"]();
		$arr  = new PhpParser\Node\Expr\Variable ('foo');
		$dim  = new PhpParser\Node\Scalar\String ('bar');

		$subnode  = new PhpParser\Node\Expr\ArrayDimFetch ($arr, $dim);

		$class  = new PhpParser\Node\Name ('Test');

		$node   = new PhpParser\Node\Expr\StaticCall ($class, $subnode);

		$obj = new NodeWrapper ($node);

		$this->assertEquals ('Test::<variable>', $obj->getName());
	}
}