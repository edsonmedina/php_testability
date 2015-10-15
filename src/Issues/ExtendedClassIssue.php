<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class ExtendedClassIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Extended class";
	}
}
