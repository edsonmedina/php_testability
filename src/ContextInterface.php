<?php
namespace edsonmedina\php_testability;

interface ContextInterface
{
	public function addChild (ContextInterface $child);
	public function getName ();
}