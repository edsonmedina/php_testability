<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class AnonymousFunctionContext extends AbstractContext
{
	protected $startLine;
	protected $endLine;

	public function __construct ($starLine, $endLine)
	{
		$this->name      = '<anonymous>';
		$this->startLine = $startLine;
		$this->endLine   = $endLine;
	}
}