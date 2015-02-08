<?php
/**
 * AbstractIssue 
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\NodeWrapper;
use PhpParser;

abstract class AbstractIssue implements IssueInterface 
{
	protected $node;

	final public function __construct (PhpParser\Node $node)
	{
		$this->node = $node;
	}

	abstract public function getTitle();

	public function getID()
	{
        $obj = new NodeWrapper ($this->node);
        return $obj->getName();
	}

	final public function getLine()
	{
		return $this->node->getLine();
	}
}
