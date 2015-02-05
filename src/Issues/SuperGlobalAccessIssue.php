<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class SuperGlobalAccessIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Super global access";
	}

	public function getID()
	{
        return '$'.$this->node->var->name;
	}
}