<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class FinalClassIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Final class declaration";
	}
}
