<?php
namespace edsonmedina\php_testability\Issues;
use edsonmedina\php_testability\AbstractIssue;

class EmptyCatchIssue extends AbstractIssue
{
	public function getTitle()
	{
		return "Empty catch block";
	}

	public function getID()
	{
		return '';	
	}
}
