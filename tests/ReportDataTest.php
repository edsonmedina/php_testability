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

	public function testGetIssuesCountForDirectory ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (8, 'some issue');

		$r->setCurrentFilename ('dir/file1.php');
		$r->addIssue (66, 'some issue');
		$r->addIssue (93, 'some issue');

		$r->setCurrentFilename ('dir/subdir/file1.php');
		$r->addIssue (40, 'some issue');

		$r->setCurrentFilename ('dir/subdir/file2.php');
		$r->addIssue (13, 'some issue');
		$r->addIssue (54, 'some issue');
		$r->addIssue (78, 'some issue');

		$r->setCurrentFilename ('dir/subdir/file3.php');
		$r->addIssue (8, 'some issue');
		$r->addIssue (9, 'some issue');

		$r->setCurrentFilename ('dir/subdir/subsubdir/file1.php');
		$r->addIssue (48, 'some issue');
		$r->addIssue (97, 'some issue');

		$this->assertEquals (2,  $r->getIssuesCountForDirectory('dir/subdir/subsubdir/'));
		$this->assertEquals (8,  $r->getIssuesCountForDirectory('dir/subdir/'));
		$this->assertEquals (10, $r->getIssuesCountForDirectory('dir/'));
	}

	public function testGetGlobalIssuesCountWithCodeOnGlobalSpaceAndNoScope ()
	{
		$r = new ReportData;

		// file with no scopes, so code_on_global_space are
		// irrelevant, file is untestable anyway
		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (8,  'code_on_global_space');
		$r->addIssue (16, 'code_on_global_space');
		$r->addIssue (65, 'other');

		$this->assertEquals (1, $r->getGlobalIssuesCount ('whatever.php'));
	}

	public function testGetGlobalIssuesCountWithCodeOnGlobalSpaceAndScopes ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (8,  'code_on_global_space');
		$r->addIssue (16, 'code_on_global_space');
		$r->addIssue (65, 'other');
		$r->addIssue (30, 'some issue', 'Whatever::doThings', '$var');

		$this->assertEquals (3, $r->getGlobalIssuesCount ('whatever.php'));
	}

	public function testGetDirList ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('/whatever.php');
		$r->setCurrentFilename ('/dir/subdir/file1.php');
		$r->setCurrentFilename ('/dir/subdir/file2.php');
		$r->setCurrentFilename ('/dir/subdir/subdir2/subdir3/file.php');

		$expected = array ('/', '/dir', '/dir/subdir', '/dir/subdir/subdir2', '/dir/subdir/subdir2/subdir3');

		$this->assertEquals ($expected, $r->getFullDirList());
	}

	public function testIsFileUntestable ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('/whatever.php');

		$r->addIssue (8,  'code_on_global_space');
		$r->addIssue (16, 'code_on_global_space');
		$r->addIssue (65, 'other');

		$this->assertTrue ($r->isFileUntestable ('/whatever.php'));

		$r->saveScopePosition ('Whatever::doThings', 150);

		$this->assertFalse ($r->isFileUntestable ('/whatever.php'));
	}

	public function testListDirectory ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('/whatever.php');
		$r->setCurrentFilename ('/dir/subdir/file1.php');
		$r->setCurrentFilename ('/dir/subdir/file2.php');
		$r->setCurrentFilename ('/dir/subdir/subdir2/subdir2/file.php');
		$r->setCurrentFilename ('/dir/subdir/subdir2/subdir3/file3.php');

		$expected = array ('/dir/subdir/file1.php', '/dir/subdir/file2.php', '/dir/subdir/subdir2');

		$this->assertEquals ($expected, $r->listDirectory('/dir/subdir/'));
	}

	public function testListFilesWithNoIssues ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('/whatever.php');
		$r->addIssue (65, 'other');

		$r->setCurrentFilename ('/whatever2.php');

		$r->setCurrentFilename ('/whatever3.php');

		$r->setCurrentFilename ('/whatever4.php');
		$r->addIssue (4,  'code_on_global_space');
		$r->addIssue (9,  'code_on_global_space');

		$expected = array ('/whatever2.php','/whatever3.php');

		$this->assertEquals ($expected, $r->listFilesWithNoIssues ());
	}
}