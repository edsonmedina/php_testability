<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class ExternalClassConstantFetchIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "External class constant fetch";
	}
}
