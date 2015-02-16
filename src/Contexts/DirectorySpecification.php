<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextSpecificationInterface;
use edsonmedina\php_testability\Contexts\DirectoryContext;

class DirectorySpecification implements ContextSpecificationInterface
{
	public function isSatisfiedBy (ContextInterface $subject)
	{
		return ($subject instanceof DirectoryContext);
	}
}
