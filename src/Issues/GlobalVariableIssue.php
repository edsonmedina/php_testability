<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class GlobalVariableIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Global variable";
	}
}
