<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextSpecificationInterface;

class ContextStack
{
	/**
	 * @var array
	 */
	protected $stack;

	public function __construct (ContextInterface $context)
	{
		$this->stack = [$context];
	}

	/**
	 * Return current context
	 * @return ContextInterface 
	 */
	public function current()
	{
		return end ($this->stack);
	}

	/**
	 * Start new context
	 * @param ContextInterface $context
	 */
	public function start (ContextInterface $context)
	{
		$this->stack[] = $context;
	}

	/**
	 * Close current context and add it as a
	 * child to the parent context
	 * @return void
	 */
	public function end ()
	{
		$lastContext = array_pop ($this->stack);
		$this->addChild ($lastContext);
	}

	/**
	 * Add a child to current context
	 * @param ContextInterface $child
	 * @return void
	 */
	protected function addChild (ContextInterface $child)
	{
		$length = count($this->stack);
		$this->stack[$length-1]->addChild ($child);
	}

	/**
	 * Add an issue to current context
	 * @param IssueInterface $issue
	 * @return void
	 */
	public function addIssue (IssueInterface $issue)
	{
		$length = count($this->stack);
		$this->stack[$length-1]->addIssue ($issue);
	}

	/**
	 * Find context in stack matching specification
	 * @param ContextSpecificationInterface $filter
	 * @return ContextInterface $node
	 */
	public function findContextOfType (ContextSpecificationInterface $filter)
	{
		foreach ($this->stack as $context)
		{
			if ($filter->isSatisfiedBy($context))
			{
				return $context;
			}
		}

		return false;
	}
}
