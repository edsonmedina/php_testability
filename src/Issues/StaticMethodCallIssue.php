<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class StaticMethodCallIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Static method call";
	}
}
