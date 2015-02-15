<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;

interface ContextSpecificationInterface
{
	public function isSatisfiedBy (ContextInterface $subject);
}
