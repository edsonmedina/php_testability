<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class IncludeIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Include";
	}

	public function getID()
	{
        return empty($this->node->expr->value) ? '<expression>' : $this->node->expr->value;
	}
}