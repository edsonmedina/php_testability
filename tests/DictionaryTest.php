<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\Dictionary;

class DictionaryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Dictionary::__construct
	 * @covers edsonmedina\php_testability\Dictionary::isInternalFunction
	 */
	public function testIsInternalFunction ()
	{
		$d = new Dictionary;

		// internal function
		$this->assertTrue ($d->isInternalFunction ('substr'));

		// user function
		$this->assertFalse ($d->isInternalFunction ('__autoload'));

		// user function
		$this->assertFalse ($d->isInternalFunction ('blablabla123'));
	}

	/**
	 * @covers edsonmedina\php_testability\Dictionary::__construct
	 * @covers edsonmedina\php_testability\Dictionary::isClassSafeForInstantiation
	 */
	public function testIsClassSafeForInstantiation ()
	{
		$d = new Dictionary;

		// safe classes
		$this->assertTrue ($d->isClassSafeForInstantiation ('DateTime'));
		$this->assertTrue ($d->isClassSafeForInstantiation ('RecursiveArrayIterator'));
		$this->assertTrue ($d->isClassSafeForInstantiation ('SplHeap'));		

		// unsafe classes
		$this->assertFalse ($d->isClassSafeForInstantiation ('PDO'));
		$this->assertFalse ($d->isClassSafeForInstantiation ('SoapClient'));

		// user class
		$this->assertFalse ($d->isClassSafeForInstantiation ('edsonmedina\php_testability\Dictionary'));
		$this->assertFalse ($d->isClassSafeForInstantiation ('SomeClass'));
	}	
}