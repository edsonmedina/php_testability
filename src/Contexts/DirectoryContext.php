<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class DirectoryContext extends AbstractContext
{
	public function __construct ($path)
	{
		$this->name = $path;
	}
}
