<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\FileIteratorFactory;

class FileIteratorFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\FileIteratorFactory::create
	 */
	public function testGetDictionary ()
	{
		$data  = $this->getMock('edsonmedina\php_testability\ReportDataInterface');

		$factory = new FileIteratorFactory();
		$this->assertInstanceOf ('edsonmedina\php_testability\FileIterator', $factory->create($data));
	}	
}