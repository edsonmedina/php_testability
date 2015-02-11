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
}