<?php

use PhpParser\Node\Stmt\Class_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\FinalClassIssue;

class FinalClassIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\FinalClassIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Class_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new FinalClassIssue ($node);

		$this->assertEquals('Final class declaration', $issue->getTitle());
	}
}