<?php
namespace edsonmedina\php_testability;

class Dictionary implements DictionaryInterface
{
	private $phpInternalFunctions = array ();

	public function __construct ()
	{
		$this->phpInternalFunctions = array_fill_keys(get_defined_functions()['internal'], true);
	}

	public function isInternalFunction ($functionName)
	{
		return isset ($this->phpInternalFunctions[$functionName]);
	}
}