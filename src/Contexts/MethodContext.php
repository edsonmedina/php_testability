<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class MethodContext extends AbstractContext
{
	protected $startLine;
	protected $endLine;

	public function __construct ($name, $startLine, $endLine)
	{
		$this->name      = $name;
		$this->startLine = $startLine;
		$this->endLine   = $endLine;
	}
}