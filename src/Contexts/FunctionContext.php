<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class FunctionContext extends AbstractContext
{
	protected $startLine;
	protected $endLine;

	public function __construct ($name, $starLine, $endLine)
	{
		$this->name      = $name;
		$this->startLine = $startLine;
		$this->endLine   = $endLine;
	}
}