<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class StaticVariableDeclarationIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Static variable declaration";
	}
}
