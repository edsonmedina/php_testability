<?php

use PhpParser\Node\Expr\StaticPropertyFetch;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticPropertyFetchIssue;

class StaticPropertyFetchIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\StaticPropertyFetchIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(StaticPropertyFetch::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticPropertyFetchIssue ($node);

		$this->assertEquals('Static property fetch', $issue->getTitle());
	}
}