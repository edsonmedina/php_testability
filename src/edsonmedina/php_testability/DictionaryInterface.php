<?php
namespace edsonmedina\php_testability;

interface DictionaryInterface 
{
	public function __construct ();
	public function isInternalFunction ($functionName);
}