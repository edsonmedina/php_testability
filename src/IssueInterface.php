<?php
namespace edsonmedina\php_testability;

use PhpParser;

interface IssueInterface 
{
	public function __construct (PhpParser\Node $node);

	public function getTitle();

	public function getID();

	public function getLine();
}