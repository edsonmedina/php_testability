<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class TraitContext extends AbstractContext
{
	public $startLine;
	public $endLine;

	public function __construct ($name, $startLine, $endLine)
	{
		$this->name      = $name;
		$this->startLine = $startLine;
		$this->endLine   = $endLine;
	}
}