<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\IssueInterface;

abstract class AbstractContext implements ContextInterface
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $children = array();

	/**
	 * @var array
	 */
	protected $issues = array();

	/**
	 * Returns the name
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}

	/**
	 * Adds a new child
	 * @param ContextInterface $child
	 * @return void
	 */
	public function addChild (ContextInterface $child)
	{
		$this->children[] = $child;
	}

	/**
	 * Are there any children?
	 * @return bool
	 */
	public function hasChildren ()
	{
		return (count($this->children) > 0);
	}

	/**
	 * Return all children
	 * @return array
	 */
	public function getChildren ()
	{
		return $this->children;
	}

	/**
	 * Adds a new issue
	 * @param IssueInterface $issue
	 * @return void
	 */
	public function addIssue (IssueInterface $issue)
	{
		$this->issues[] = $issue;
	}

	/**
	 * Are there any issues?
	 * @param bool $recursive 
	 * @return bool
	 */
	public function hasIssues ($recursive = false, ContextInterface $node = null)
	{
		$node = ($node === null) ? $this : $node;

		if ($recursive === true)
		{
			foreach ($node->getChildren() as $child)
			{
				if ($this->hasIssues(true, $child))
				{
					return true;
				}
			}
		}

		return (count($node->issues) > 0);
	}

	/**
	 * Return all issues
	 * @param bool $recursive 
	 * @return array
	 */
	public function getIssues ($recursive = false, ContextInterface $node = null)
	{
		$node = ($node === null) ? $this : $node;

		$list = $node->issues;

		if ($recursive === true && $node->hasChildren())
		{
			foreach ($node->getChildren() as $child)
			{
				if ($child->hasIssues(false))
				{
					$list = $list + $this->getIssues(true, $child);
				}
			}
		}

		return $list;
	}

	/**
	 * Counts all issues recursively
	 * @param bool $recursive 
	 * @return array
	 */
	public function getIssuesCount (ContextInterface $node = null)
	{
		$node = ($node === null) ? $this : $node;

		$count = count($node->issues);

		foreach ($node->getChildren() as $child)
		{
			$count += $this->getIssuesCount($child);
		}

		return $count;
	}
}