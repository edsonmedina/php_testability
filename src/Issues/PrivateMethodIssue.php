<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class PrivateMethodIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Private method declaration";
	}
}
