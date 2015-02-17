<?php
namespace edsonmedina\php_testability\Issues;

use edsonmedina\php_testability\AbstractIssue;

class CodeOnGlobalSpaceIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Code on global space";
	}

	public function getID()
	{
		return '';
	}
}
