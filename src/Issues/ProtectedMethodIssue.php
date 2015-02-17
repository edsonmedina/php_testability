<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class ProtectedMethodIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Protected method declaration";
	}
}
