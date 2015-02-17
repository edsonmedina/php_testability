<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class GlobalFunctionCallIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Global function call";
	}
}
