<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticPropertyFetchIssue;

class StaticPropertyFetchIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\StaticPropertyFetchIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\StaticPropertyFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticPropertyFetchIssue ($node);

		$this->assertEquals('Static property fetch', $issue->getTitle());
	}
}