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
	 * Adds a new child
	 * @param ContextInterface $child
	 * @return void
	 */
	public function addChild (ContextInterface $child)
	{
		$this->children[] = $child;
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
	 * Return all issues, recursively
	 * @return array
	 */
	public function getIssuesRecursively (ContextInterface $root = null)
	{
		if (is_null($root)) {
			$root = $this;
		}

		$issues = $this->issues;

		$children = $this->getChildren();

		if (count($children))
		{
			foreach ($children as $child)
			{
				$childIssues = $child->getIssues();

				if (count($childIssues)) 
				{
					$issues = $issues + $childIssues;
				}

				if ($child->hasChildren())
				{
					$list = $this->getIssuesRecursively ($child);
					
					if (count($list))
					{
						$issues = $issues + $this->getIssuesRecursively ($child);
					}
				}
			}
		}

		return $issues
	}

	/**
	 * Returns the name
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
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
}