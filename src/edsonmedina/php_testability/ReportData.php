<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

class ReportData implements ReportDataInterface
{
	private $currentFilename;
	private $issues     = array ();
	private $fileIssues = array ();

	/**
	 * Add a new issue. Requires using setCurrentFilename first.
	 * @param int $line line number for the issue
	 * @param string $type 
	 * @param string $scope
	 * @param string $identifier of the current issue
	 */
	public function addIssue ($line, $type, $scope = null, $identifier = null)
	{
		if (is_null($scope)) 
		{
			@$this->issues[$this->currentFilename]['global'][$type][$line] = true;
		} 
		else
		{
			@$this->issues[$this->currentFilename]['scoped'][$scope][$type][] = array ($identifier, $line);
		}
	}

	/**
	 * Sets current filename (should be set before calling addIssue)
	 * @param string $filename
	 */
	public function setCurrentFilename ($filename)
	{
		$this->currentFilename = $filename;
		$this->issues[$filename] = array();
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
		return $this->issues;
	}

	/**
	 * Returns the recursive sum of issues for path
	 * @param  string $path 
	 * @return integer 
	 */
	public function getIssuesCountForFile ($filename)
	{
		$issues = $this->issues[$filename];

		$count = 0;

		// count scope issues
		foreach ($issues['scoped'] as $scope => $report)
		{
			foreach ($report as $type => $list)
			{
				$count += count($list);	
			}
		}

		// count global issues
		foreach ($issues['global'] as $type => $list)
		{
			$count += count($list);	
		}

		return $count;
	}

	/**
	 * Returns list of files reported
	 * @return array
	 */
	public function getFileList ()
	{
		return array_keys ($this->issues);
	}

	/**
	 * Returns issues for file
	 * @param  string $filename
	 * @return array
	 */
	public function getIssuesForFile ($filename)
	{
		return @$this->issues[$filename];
	}

	/**
	 * Returns list of directories reported
	 * @return array
	 */
	public function getDirList ()
	{
		// TODO also return directories with no issues
		$dirnames = array_map ('dirname', array_keys ($this->issues));
		return array_unique ($dirnames); 
	}
}