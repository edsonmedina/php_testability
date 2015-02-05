<?php
/**
 * AbstractIssue 
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use PhpParser;

abstract class AbstractIssue implements IssueInterface 
{
	protected $node;

	public function __construct (PhpParser\Node $node)
	{
		$this->node = $node;
	}

	abstract public function getTitle();

	abstract public function getID();

	public function getLine()
	{
		return $this->node->getLine();
	}
}
