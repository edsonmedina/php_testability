<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;
use edsonmedina\php_testability\Contexts\MethodContext;
use edsonmedina\php_testability\Contexts\FunctionContext;

class FileContext extends AbstractContext
{
	public function __construct ($path)
	{
		$this->name = $path;
	}

	/**
	 * Returns a count of issues per scope (class/trait/method/function) 
	 * present in this file, indexed by their (start) line number
	 * @return array ie [['name' => 'Whatever::foo', 'startLine' => 15, 'issues' => 2]...]
	 */
	public function getContextsNumberOfIssues()
	{
		$list = array ();

		foreach ($this->getChildren() as $child)
		{
			if ($child instanceof FunctionContext)
			{
				$list[] = array (
					'name'      => $child->getName(),
					'startLine' => $child->startLine,
					'issues'    => $child->getIssuesCount()
				);
			}
			else
			{
				// this might be a class, look for methods
				foreach ($child->getChildren() as $method)
				{
					if ($method instanceof MethodContext)
					{
						$list[] = array (
							'name'      => $child->getName().'::'.$method->getName(),
							'startLine' => $method->startLine,
							'issues'    => $method->getIssuesCount()
						);
					}
				}
			}
		}

		return $list;
	}
}