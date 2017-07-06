<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\FileIterator;

class FileIteratorTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\FileIterator::setExcludedDirs
	 * @covers edsonmedina\php_testability\FileIterator::isDirExcluded
	 */
	public function testIsDirExcluded ()
	{
		$analyser = $this->getMockBuilder('edsonmedina\php_testability\Analyser')->disableOriginalConstructor()->getMock();

		$fileIterator = new FileIterator ($analyser);
		$fileIterator->setExcludedDirs (['whatever/thirdparty', '', 'other']);

		$this->assertTrue ($fileIterator->isDirExcluded('bla/whatever/thirdparty'));
		$this->assertTrue ($fileIterator->isDirExcluded('bla/whatever/other'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/somedir'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tests'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/.git'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/vendor'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tmp'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/temp'));
	}	

	/**
	 * @covers edsonmedina\php_testability\FileIterator::isDirExcluded
	 */
	public function testIsDirExcludedDefaultValues ()
	{
		$analyser = $this->getMockBuilder('edsonmedina\php_testability\Analyser')->disableOriginalConstructor()->getMock();

		$fileIterator = new FileIterator ($analyser);

		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/somedir'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tests'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/.git'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/vendor'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/tmp'));
		$this->assertFalse ($fileIterator->isDirExcluded('bla/whatever/temp'));
	}

	/**
	 * @covers edsonmedina\php_testability\FileIterator::__construct
	 * @covers edsonmedina\php_testability\FileIterator::getProcessedFilesCount 
	 */
	public function testGetProcessedFilesCountAtStart ()
	{
		$analyser = $this->getMockBuilder('edsonmedina\php_testability\Analyser')->disableOriginalConstructor()->getMock();

		$fileIterator = new FileIterator ($analyser);

		$this->assertTrue (0 === $fileIterator->getProcessedFilesCount());
	}	
}