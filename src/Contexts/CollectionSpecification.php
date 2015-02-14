<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextSpecificationInterface;
use edsonmedina\php_testability\Contexts\ClassContext;
use edsonmedina\php_testability\Contexts\TraitContext;

class CollectionSpecification implements ContextSpecificationInterface
{
	public function isSatisfiedBy (ContextInterface $subject)
	{
		return (
			$subject instanceof ClassContext 
			|| $subject instanceof TraitContext
		);
	}
}