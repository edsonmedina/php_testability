<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\ReportData;

class ReportDataTest extends PHPUnit_Framework_TestCase
{
	public function testSetGetCurrentFilename ()
	{
		$r = new ReportData;
		$this->assertNull ($r->getCurrentFilename()); // default is null

		$r->setCurrentFilename ('whatever.php');
		$this->assertEquals ('whatever.php', $r->getCurrentFilename());
	}	

	public function testGetIssuesCountForFile ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (13, 'some issue', 'Whatever::doThings', '$var');
		$r->addIssue (62, 'some issue');
		$r->addIssue (86, 'some issue');

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (4, 'some issue');
		$r->addIssue (7, 'some issue');

		$this->assertEquals (0, $r->getIssuesCountForFile ('invalidfile.php'));
		$this->assertEquals (3, $r->getIssuesCountForFile ('whatever.php'));
		$this->assertEquals (2, $r->getIssuesCountForFile ('file2.php'));
	}

	public function testGetFileList ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (13, 'some issue', 'Whatever::doThings', '$var');

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (7, 'some issue');

		$expected = array ('whatever.php','file2.php');

		$this->assertEquals ($expected, $r->getFileList());
	}

	public function saveGetScopePosition ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->saveScopePosition ('Whatever::Do',  55);
		$r->saveScopePosition ('Whatever::Run', 37);

		$r->setCurrentFilename ('file2.php');
		$r->saveScopePosition ('Thing2::Do', 49);

		$this->assertEquals (37, $r->getScopePosition ('whatever.php', 'Whatever::Run'));
		$this->assertEquals (49, $r->getScopePosition ('file2.php', 'Thing2::Do'));

		$this->setExpectedException ('Exception');
		$r->getScopePosition ('whatever.php', 'Thing2::Do'); // wrong file

		$this->setExpectedException ('Exception');
		$r->getScopePosition ('invalid.php', 'ZZZ::doesntExist'); 
	}

	public function testGetIssuesCountForScope ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (13, 'some issue', 'Whatever::doThings', '$var');
		$r->addIssue (62, 'some issue', 'Whatever::doThings', '$bla');
		$r->addIssue (86, 'some issue', 'Whatever::foo', '$var');
		$r->addIssue (40, 'some issue');

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (4, 'some issue');
		$r->addIssue (7, 'some issue');

		$this->assertEquals (2, $r->getIssuesCountForScope ('whatever.php', 'Whatever::doThings'));
		$this->assertEquals (1, $r->getIssuesCountForScope ('whatever.php', 'Whatever::foo'));
		
		$this->assertEquals (0, $r->getIssuesCountForScope ('whatever.php', 'Whatever::blablabla')); // doesn't exist
	}

	public function testGetGlobalIssuesCount ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (13, 'some issue', 'Whatever::doThings', '$var');
		$r->addIssue (62, 'some issue', 'Whatever::doThings', '$bla');
		$r->addIssue (86, 'some issue', 'Whatever::foo', '$var');
		$r->addIssue (40, 'some issue');

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (4, 'some issue');
		$r->addIssue (7, 'some issue');

		$this->assertEquals (1, $r->getGlobalIssuesCount ('whatever.php'));
		$this->assertEquals (2, $r->getGlobalIssuesCount ('file2.php'));
		
		$this->assertEquals (0, $r->getGlobalIssuesCount ('invalid.php'));
	}

	public function testAnyFilesInsideDir ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->setCurrentFilename ('dir1/subdir1/file1.php');
		$r->setCurrentFilename ('dir1/subdir1/file2.php');
		$r->setCurrentFilename ('dir2/subdir2/file221.php');

		$this->assertFalse ($r->anyFilesInDirectory('subdir1/'));
		$this->assertFalse ($r->anyFilesInDirectory('dir2/subdir1/'));

		$this->assertTrue ($r->anyFilesInDirectory('dir1/subdir1/'));
		$this->assertTrue ($r->anyFilesInDirectory('dir2/subdir2/'));
	}
}