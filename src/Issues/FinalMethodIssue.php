<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;
use edsonmedina\php_testability\NodeWrapper;

class FinalMethodIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Final method declaration";
	}
}
