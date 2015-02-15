<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class AnonymousFunctionContext extends AbstractContext
{
	public $startLine;
	public $endLine;

	public function __construct ($startLine, $endLine)
	{
		$this->name      = '<anonymous function>';
		$this->startLine = $startLine;
		$this->endLine   = $endLine;
	}
}
