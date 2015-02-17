<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class FinalMethodIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Final method declaration";
	}
}
