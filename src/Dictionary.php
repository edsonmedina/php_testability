<?php
namespace edsonmedina\php_testability;

class Dictionary
{
	private $phpInternalFunctions = array ();

	public function __construct ()
	{
		$list = get_defined_functions();
		$this->phpInternalFunctions = array_fill_keys($list['internal'], true);
	}

	public function isInternalFunction ($functionName)
	{
		return isset ($this->phpInternalFunctions[$functionName]);
	}
}