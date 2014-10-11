<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

class ReportData implements ReportDataInterface
{
	private $currentFilename;
	private $issues     = array ();
	private $fileIssues = array ();

	/**
	 * Add new issue for the current filename
	 * @param int $line line number for the issue
	 * @param string $type 
	 * @param string $scope
	 * @param string $identifier of the current issue
	 */
	public function addIssue ($line, $type, $scope, $identifier)
	{
		@$this->issues[$this->currentFilename][$scope][$type][] = array ($identifier, $line);
	}

	/**
	 * Sets current filename (should be set before calling addIssue)
	 * @param string $filename
	 */
	public function setCurrentFilename ($filename)
	{
		$this->currentFilename = $filename;
	}

	/**
	 * Getter for current filename
	 */
	public function getCurrentFilename ()
	{
		return $this->currentFilename;
	}

	/**
	 * For debugging purposes.
	 */
	public function dumpAllIssues ()
	{
		return array($this->issues, $this->fileIssues);
	}

	/**
	 * Returns the recursive sum of issues for path
	 * @param  string $path 
	 * @return integer 
	 */
	public function getIssuesCountForPath ($path)
	{

	}

	/**
	 * Flags issues on file
	 * @param int $line
	 * @param string type
	 */
	public function addFileIssue ($line, $type)
	{
		@$this->fileIssues[$this->currentFilename][$type][$line] = true;
	}
}