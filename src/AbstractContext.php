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
	 * @param bool $recursive 
	 * @return array
	 */
	public function getChildren ($recursive = false)
	{
		$list = $this->children;

		if ($recursive === true)
		{
			foreach ($list as $child)
			{
				if ($child->hasChildren())
				{
					$list = array_merge ($list, $child->getChildren (true));
				}
			}
		}

		return $list;
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
	public function hasIssues ($recursive = false)
	{
		if ($recursive === true)
		{
			foreach ($this->getChildren(true) as $child)
			{
				if ($child->hasIssues())
				{
					return true;
				}
			}
		}

		return (count($this->issues) > 0);
	}

	/**
	 * Return all issues
	 * @param bool $recursive 
	 * @return array
	 */
	public function getIssues ($recursive = false)
	{
		$list = $this->issues;

		if ($recursive === true)
		{
			foreach ($this->getChildren(true) as $child)
			{
				foreach ($child->getIssues() as $issue)
				{
					$list[] = $issue;
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
	public function getIssuesCount ()
	{
		$count = count($this->issues);

		foreach ($this->getChildren() as $child)
		{
			$count += $child->getIssuesCount();
		}

		return $count;
	}
}