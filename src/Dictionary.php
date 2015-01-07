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

	/**
	 * Is this a php internal function?
	 * @param string $functionName
	 * @return bool
	 */
	public function isInternalFunction ($functionName)
	{
		return isset ($this->phpInternalFunctions[$functionName]);
	}
}