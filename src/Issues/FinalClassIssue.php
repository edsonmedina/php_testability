<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;
use edsonmedina\php_testability\NodeWrapper;

class FinalClassIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Final class declaration";
	}

	public function getID()
	{
        $obj = new NodeWrapper ($this->node);
        return $obj->getName();
	}
}
