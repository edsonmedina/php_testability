<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;

class ContextSpecificationInterface
{
	public function isSatisfiedBy (ContextInterface $subject);
}