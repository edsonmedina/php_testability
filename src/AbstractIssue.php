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
	protected $line;
	protected $id;

	final public function __construct (PhpParser\Node $node)
	{
		//$this->node = $node;
		$this->line = $node->getLine();

        $obj = new NodeWrapper ($node);
        $this->id = $obj->getName();
	}

	abstract public function getTitle();

	public function getID()
	{
		return $this->id;
	}

	final public function getLine()
	{
		return $this->line;
	}
}
