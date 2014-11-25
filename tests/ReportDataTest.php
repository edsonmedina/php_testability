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

		$r->setCurrentFilename ($this->fixPath('whatever.php'));
		$r->addIssue (8, 'some issue', 'Whatever::foo');

		$r->setCurrentFilename ($this->fixPath('dir/file1.php'));
		$r->addIssue (66, 'some issue', 'Whatever2::foo');
		$r->addIssue (93, 'some issue', 'Whatever2::foo2');

		$r->setCurrentFilename ($this->fixPath('dir/subdir/file1.php'));
		$r->addIssue (40, 'some issue', 'Whatever3::foo');

		// some code on global space
		$r->setCurrentFilename ($this->fixPath('dir/subdir/file2.php'));
		$r->addIssue (13, 'some issue', 'Whatever4::foo');
		$r->addIssue (54, 'some issue', 'Whatever5::foo');
		$r->addIssue (78, 'code_on_global_space');

		// similar dir name
		$r->setCurrentFilename ($this->fixPath('dir/subdir_z/file9.php'));
		$r->addIssue (8, 'some issue', 'Whatever101::foo');
		$r->addIssue (9, 'code_on_global_space');

		// no scopes - no count
		$r->setCurrentFilename ($this->fixPath('dir/subdir/file3.php'));
		$r->addIssue (8, 'code_on_global_space');
		$r->addIssue (9, 'code_on_global_space');

		$r->setCurrentFilename ($this->fixPath('dir/subdir/subsubdir/file1.php'));
		$r->addIssue (48, 'some issue', 'Whatever6::foo');
		$r->addIssue (97, 'some issue', 'Whatever6::foo');

		$this->assertEquals (2,  $r->getIssuesCountForDirectory($this->fixPath('dir/subdir/subsubdir/')));
		$this->assertEquals (6,  $r->getIssuesCountForDirectory($this->fixPath('dir/subdir/')));
		$this->assertEquals (10, $r->getIssuesCountForDirectory($this->fixPath('dir/')));
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

		$r->setCurrentFilename ($this->fixPath('/whatever.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/file1.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/file2.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/subdir2/subdir3/file.php'));

		$expected = array (
			$this->fixPath('/'),
			$this->fixPath('/dir'),
			$this->fixPath('/dir/subdir'),
			$this->fixPath('/dir/subdir/subdir2'),
			$this->fixPath('/dir/subdir/subdir2/subdir3')
		);

		$this->assertEquals ($expected, $r->getFullDirList());
	}

	public function testIsFileUntestable ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (8,  'code_on_global_space');
		$r->addIssue (16, 'code_on_global_space');
		$r->addIssue (65, 'other');

		$this->assertTrue ($r->isFileUntestable ('whatever.php'));

		$r->addIssue (8, 'some_scoped_issue', 'Class1::method2');
		$r->saveScopePosition ('Whatever::doThings', 150);

		$this->assertFalse ($r->isFileUntestable ('whatever.php'));
	}

	public function testListDirectory ()
	{
		$r = new ReportData;

		$r->setCurrentFilename ($this->fixPath('/whatever.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/file1.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/file2.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/subdir2/subdir2/file.php'));
		$r->setCurrentFilename ($this->fixPath('/dir/subdir/subdir2/subdir3/file3.php'));

		$expected = array (
			$this->fixPath('/dir/subdir/file1.php'),
			$this->fixPath('/dir/subdir/file2.php'),
			$this->fixPath('/dir/subdir/subdir2')
		);

		$this->assertEquals ($expected, $r->listDirectory($this->fixPath('/dir/subdir/')));
	}

	/**
	 * This helps making tests work on windows
	 */
	private function fixPath ($path)
	{
		if (DIRECTORY_SEPARATOR == '/')
		{
			return $path;
		}
		else
		{
			return str_replace ('/', DIRECTORY_SEPARATOR, $path);
		}
	}
}