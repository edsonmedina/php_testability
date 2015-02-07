<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;
use edsonmedina\php_testability\NodeWrapper;

class GlobalFunctionCallIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Global function call";
	}
}
