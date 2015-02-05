<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\Issues\ExitIssue;

class ReportDataTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::getCurrentFilename
	 */
	public function testSetGetCurrentFilename ()
	{
		$r = new ReportData;
		$this->assertNull ($r->getCurrentFilename()); // default is null

		$r->setCurrentFilename ('whatever.php');
		$this->assertEquals ('whatever.php', $r->getCurrentFilename());
	}	

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getIssuesCountForFile
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetIssuesCountForFile ()
	{
		$r = new ReportData;

		$stub = $this->getMockBuilder('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->setMethods(array('getLine'))
		             ->getMock();

		$stub->method('getLine')->will($this->onConsecutiveCalls(1,2,3,4,5));

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub) , 'Whatever::doThings');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$this->assertEquals (0, $r->getIssuesCountForFile ('invalidfile.php'));
		$this->assertEquals (3, $r->getIssuesCountForFile ('whatever.php'));
		$this->assertEquals (2, $r->getIssuesCountForFile ('file2.php'));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getFileList
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetFileList ()
	{
		$r = new ReportData;

		$stub = $this->getMock('PhpParser\Node\Expr\Exit_');

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (new ExitIssue($stub));

		$expected = array ('whatever.php','file2.php');

		$this->assertEquals ($expected, $r->getFileList());
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::saveScopePosition
	 * @covers edsonmedina\php_testability\ReportData::getScopePosition
	 */
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

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getIssuesCountForScope
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetIssuesCountForScope ()
	{
		$r = new ReportData;

		$stub = $this->getMock('PhpParser\Node\Expr\Exit_');

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');
		$r->addIssue (new ExitIssue($stub), 'Whatever::foo');
		$r->addIssue (new ExitIssue($stub));

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$this->assertEquals (2, $r->getIssuesCountForScope ('whatever.php', 'Whatever::doThings'));
		$this->assertEquals (1, $r->getIssuesCountForScope ('whatever.php', 'Whatever::foo'));
		
		$this->assertEquals (0, $r->getIssuesCountForScope ('whatever.php', 'Whatever::blablabla')); // doesn't exist
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getGlobalIssuesCount
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetGlobalIssuesCount ()
	{
		$r = new ReportData;

		$stub = $this->getMockBuilder('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->setMethods(array('getLine'))
		             ->getMock();

		$stub->method('getLine')->will($this->onConsecutiveCalls(1,2,3,4,5,6));

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');
		$r->addIssue (new ExitIssue($stub), 'Whatever::foo');
		$r->addIssue (new ExitIssue($stub));

		$r->setCurrentFilename ('file2.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$this->assertEquals (1, $r->getGlobalIssuesCount ('whatever.php'));
		$this->assertEquals (2, $r->getGlobalIssuesCount ('file2.php'));
		
		$this->assertEquals (0, $r->getGlobalIssuesCount ('invalid.php'));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getIssuesCountForDirectory
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetIssuesCountForDirectory ()
	{
		$r = new ReportData;

		$stub = $this->getMock('PhpParser\Node\Expr\Exit_');

		$r->setCurrentFilename ($this->fixPath('whatever.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever::foo');

		$r->setCurrentFilename ($this->fixPath('dir/file1.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever2::foo');
		$r->addIssue (new ExitIssue($stub), 'Whatever2::foo2');

		$r->setCurrentFilename ($this->fixPath('dir/subdir/file1.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever3::foo');

		// some code on global space
		$r->setCurrentFilename ($this->fixPath('dir/subdir/file2.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever4::foo');
		$r->addIssue (new ExitIssue($stub), 'Whatever5::foo');
		$r->addIssue (new ExitIssue($stub));

		// similar dir name
		$r->setCurrentFilename ($this->fixPath('dir/subdir_z/file9.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever101::foo');
		$r->addIssue (new ExitIssue($stub));

		// no scopes - no count
		$r->setCurrentFilename ($this->fixPath('dir/subdir/file3.php'));
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$r->setCurrentFilename ($this->fixPath('dir/subdir/subsubdir/file1.php'));
		$r->addIssue (new ExitIssue($stub), 'Whatever6::foo');
		$r->addIssue (new ExitIssue($stub), 'Whatever6::foo');

		$this->assertEquals (2,  $r->getIssuesCountForDirectory($this->fixPath('dir/subdir/subsubdir/')));
		$this->assertEquals (6,  $r->getIssuesCountForDirectory($this->fixPath('dir/subdir/')));
		$this->assertEquals (10, $r->getIssuesCountForDirectory($this->fixPath('dir/')));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getGlobalIssuesCount
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetGlobalIssuesCountWithCodeOnGlobalSpaceAndNoScope ()
	{
		$r = new ReportData;

		$stub = $this->getMock('PhpParser\Node\Expr\Exit_');

		// file with no scopes, so code_on_global_space are
		// irrelevant, file is untestable anyway
		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$this->assertEquals (1, $r->getGlobalIssuesCount ('whatever.php'));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::getGlobalIssuesCount
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testGetGlobalIssuesCountWithCodeOnGlobalSpaceAndScopes ()
	{
		$r = new ReportData;

		$stub = $this->getMockBuilder('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->setMethods(array('getLine'))
		             ->getMock();

		$stub->method('getLine')->will($this->onConsecutiveCalls(1,2,3,4));

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub), 'Whatever::doThings');

		$this->assertEquals (3, $r->getGlobalIssuesCount ('whatever.php'));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::getFullDirList
	 */
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

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::addIssue
	 * @covers edsonmedina\php_testability\ReportData::isFileUntestable
	 * @uses edsonmedina\php_testability\Issues\ExitIssue
	 */
	public function testIsFileUntestable ()
	{
		$r = new ReportData;

		$stub = $this->getMock('PhpParser\Node\Expr\Exit_');

		$r->setCurrentFilename ('whatever.php');
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));
		$r->addIssue (new ExitIssue($stub));

		$this->assertTrue ($r->isFileUntestable ('whatever.php'));

		$r->addIssue (new ExitIssue($stub), 'Class1::method2');
		$r->saveScopePosition ('Whatever::doThings', 150);

		$this->assertFalse ($r->isFileUntestable ('whatever.php'));
	}

	/**
	 * @covers edsonmedina\php_testability\ReportData::setCurrentFilename
	 * @covers edsonmedina\php_testability\ReportData::listDirectory
	 */
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