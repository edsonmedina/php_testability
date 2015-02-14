<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\IssueInterface;
use edsonmedina\php_testability\ContextSpecificationInterface;

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
	 * Filter and list children
	 * @param  ContextSpecificationInterface $filter (optional)
	 * @return array
	 */
	public function getChildren (ContextSpecificationInterface $filter = null)
	{
		if (is_null($filter))
		{
			return $this->children;
		}
		else
		{
			return array_filter ($this->children, array($filter, 'isSatisfiedBy'));
		}
	}

	/**
	 * Are there any children?
	 * @param  ContextSpecificationInterface $filter
	 * @return bool
	 */
	public function hasChildren (ContextSpecificationInterface $filter = null)
	{
		return (count($this->getChildren($filter)) > 0);
	}

	/**
	 * Return all children recursively
	 * @param  ContextSpecificationInterface $filter
	 * @return array
	 */
	public function getChildrenRecursively (ContextSpecificationInterface $filter = null)
	{
		$list = $this->getChildren($filter);

		foreach ($list as $child)
		{
			if ($child->hasChildren($filter))
			{
				$list = array_merge ($list, $child->getChildrenRecursively($filter));
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
			foreach ($this->getChildrenRecursively() as $child)
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
			foreach ($this->getChildrenRecursively() as $child)
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