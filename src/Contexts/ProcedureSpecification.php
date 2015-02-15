<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextSpecificationInterface;
use edsonmedina\php_testability\Contexts\MethodContext;
use edsonmedina\php_testability\Contexts\FunctionContext;
use edsonmedina\php_testability\Contexts\AnonymousFunctionContext;

class ProcedureSpecification implements ContextSpecificationInterface
{
	public function isSatisfiedBy (ContextInterface $subject)
	{
		return (
			$subject instanceof MethodContext 
			|| $subject instanceof FunctionContext
			|| $subject instanceof AnonymousFunctionContext
		);
	}
}
