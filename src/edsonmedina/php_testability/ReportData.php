<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

class ReportData implements ReportDataInterface
{
	private $currentFilename = null;
	private $issues     = array ();
	private $fileIssues = array ();
	private $info       = array ();

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

		if (!isset ($this->issues[$filename])) {
			$this->issues[$filename] = array ();
		}
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
	public function _dumpAllIssues ()
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
		if (!isset($this->issues[$filename])) {
			return 0;
		}

		$issues = $this->issues[$filename];
		$count  = 0;

		// count scope issues
		if (isset($issues['scoped'])) 
		{
			foreach ($issues['scoped'] as $scope => $report)
			{
				foreach ($report as $type => $list)
				{
					$count += count($list);	
				}
			}
		}

		// count global issues
		$count += $this->getGlobalIssuesCount ($filename);

		return $count;
	}

	/**
	 * get number of issues for directory (recursive)
	 * @param string $path
	 */
	public function getIssuesCountForDirectory ($path)
	{
		$count = 0;
		foreach (array_keys($this->issues) as $filename) {
			if ($path == '' || strpos($filename, $path) === 0) {
				$count += $this->getIssuesCountForFile ($filename);
			}
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
		// TODO split this into specific methods (to avoid coupling)
		return isset($this->issues[$filename]) ? $this->issues[$filename] : array ();
	}

	/**
	 * Returns list of all directories reported
	 * @return array
	 */
	public function getFullDirList ()
	{
		// get dir names from reported files
		$dirnames = array_map ('dirname', array_keys ($this->issues));
		$dirnames = array_fill_keys ($dirnames, true);

		// fill gaps
		foreach (array_keys($dirnames) as $dir) 
		{
			$parts = explode (DIRECTORY_SEPARATOR, $dir);
			
			for ($n = 2, $len = count($parts); $n <= $len; $n++)
			{
				$path = join (DIRECTORY_SEPARATOR, array_slice($parts, 0, $n));

				if (!isset($dirnames[$path])) {
					$dirnames[$path] = true;
				}
			}
		}

		ksort ($dirnames);

		return array_keys ($dirnames);
	}

	/**
	 * Save scope start line
	 * @param string $scope name
	 * @param int $lineNum line number
	 */
	public function saveScopePosition ($scope, $lineNum)
	{
		if (!isset($this->info[$this->currentFilename])) {
			$this->info[$this->currentFilename] = array ();
		}

		$this->info[$this->currentFilename][$scope] = $lineNum;
	}

	/**
	 * Get scope start line
	 * @param string $filename
	 * @param string $scope
	 */
	public function getScopePosition ($filename, $scope)
	{
		if (isset($this->info[$filename][$scope])) {
			return $this->info[$filename][$scope];
		}

		throw new \Exception ('Unknown scope '.$scope.' in '.$filename);
	}

	/**
	 * Return list of scopes in file. Needs prior saveScopePosition() usage.
	 * @param  string $filename 
	 * @return array
	 */
	public function getScopesForFile ($filename)
	{
		return isset($this->info[$filename]) ? array_keys($this->info[$filename]) : array ();
	}

	/**
	 * Get issue count for scope
	 * @param string $filename
	 * @param string $scope
	 */
	public function getIssuesCountForScope ($filename, $scope)
	{
		$count = 0;
		foreach ($this->getIssuesForScope ($filename, $scope) as $type => $list)
		{
			$count += count($list);
		}

		return $count;
	}

	/**
	 * Get issue count for global space
	 * @param string $filename
	 * @return int
	 */
	public function getGlobalIssuesCount ($filename)
	{
		$noScopes = empty($this->issues[$filename]['scoped']);

		$count = 0;
		foreach ($this->getGlobalIssuesForFile ($filename) as $type => $list) 
		{
			// If code_on_global_space on a file without scopes, don't count
			// (all the file will have the same issue)
			if (!($noScopes && $type == 'code_on_global_space'))
			{
				$count += count($list);
			}
		}

		return $count;
	}

	/**
	 * Are there any files in directory?
	 * @param string $dir
	 * @return bool
	 */
	public function anyFilesInDirectory ($directory)
	{
		foreach (array_keys($this->issues) as $filename) {
			if (strpos($filename, $directory) === 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get global isses for file
	 * @param string $filename
	 * @return array
	 */
	public function getGlobalIssuesForFile ($filename)
	{
		if (!isset($this->issues[$filename]['global'])) {
			return array ();
		}

		return $this->issues[$filename]['global'];
	}

	/**
	 * Get scoped isses for file
	 * @param string $filename
	 * @return array
	 */
	public function getIssuesForScope ($filename, $scope)
	{
		if (!isset($this->issues[$filename]['scoped'][$scope])) {
			return array ();
		}

		return $this->issues[$filename]['scoped'][$scope];
	}

	/**
	 * Is file unstestable (no testable scopes)? Needs prior saveScopePosition() usage.
	 * @param string $filename
	 * @return bool
	 */
	public function isFileUntestable ($filename)
	{
		return (count($this->getScopesForFile($filename)) === 0);
	}
}