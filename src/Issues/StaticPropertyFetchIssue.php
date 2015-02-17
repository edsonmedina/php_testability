<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class StaticPropertyFetchIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Static property fetch";
	}
}
