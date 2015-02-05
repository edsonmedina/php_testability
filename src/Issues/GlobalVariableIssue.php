<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;
use edsonmedina\php_testability\NodeWrapper;

class GlobalVariableIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Global variable";
	}

	public function getID()
	{
        $obj = new NodeWrapper ($this->node);

        $names = array ();
        foreach ($obj->getVarList() as $var) {
            $names[] = '$'.$var->name;
        }

        return join (',', $names);
	}
}
