<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\FileIterator;

class FileIteratorTest extends PHPUnit_Framework_TestCase
{
	public function testIsDirExcluded ()
	{
		$analyser = $this->getMockBuilder('edsonmedina\php_testability\Analyser')->disableOriginalConstructor()->getMock();

		$fileIterator = new FileIterator ('', $analyser);
		$fileIterator->setExcludedDirs (array('whatever/thirdparty', '', 'other'));

		$this->assertTrue ($fileIterator->isDirExcluded('bla/whatever/thirdparty'));
		$this->assertTrue ($fileIterator->isDirExcluded('bla/whatever/other'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/somedir'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tests'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/.git'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/vendor'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tmp'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/temp'));
	}	

	public function testIsDirExcludedDefaultValues ()
	{
		$analyser = $this->getMockBuilder('edsonmedina\php_testability\Analyser')->disableOriginalConstructor()->getMock();

		$fileIterator = new FileIterator ('', $analyser);

		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/somedir'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tests'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/.git'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/vendor'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tmp'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/temp'));
	}
}