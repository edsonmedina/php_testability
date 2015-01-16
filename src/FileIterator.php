<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

class FileIterator 
{
	private $analyser;
	private $excludedDirs = array ();
	private $processedFilesCount;

	/**
	 * Runs the static analyser
	 * @param  Analyser $analyser Static code analysis class
	 */
	public function __construct (AnalyserInterface $analyser)
	{
		$this->analyser = $analyser;
		$this->processedFilesCount = 0;
	}

	/** 
	 * Scans (recursively) the path and runs the analyser for each file
	 * @param string $path File or directory
	 */
	public function iterate ($path)
	{
		if (is_dir ($path) && !$this->isDirExcluded($path)) 
		{
			// recurse into directory
			foreach (new \DirectoryIterator ($path) as $fileInfo)
			{
				if ($fileInfo->isDot()) {
					continue;
				}

				$this->iterate ($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename());
			}
		}
		elseif (is_file ($path) && $this->hasPhpExtension($path))
		{
			// process file
			$this->analyser->scan ($path);
			$this->processedFilesCount++;
			echo ".";
		}
	}

	/**
	 * Is this path excluded from the scan?
	 * @param string $path
	 * @return bool
	 */
	public function isDirExcluded ($path)
	{
		foreach ($this->excludedDirs as $needle) 
		{
			if (empty($needle)) {
				continue;
			}

			$needleLen = strlen($needle);
			$pathChunk = substr($path, -$needleLen, $needleLen);

			// check if matches end of $path
			if ($pathChunk == $needle) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check for .php extension in filename
	 * @param string $filename
	 * @return bool
	 */
	protected function hasPhpExtension ($filename)
	{
		return (substr($filename, -4, 4) == '.php');
	}

	/**
	 * getter for processedFiles count
	 * @return int
	 */
	public function getProcessedFilesCount ()
	{
		return $this->processedFilesCount;
	}

	/**
	 * Set list of excluded directories
	 * @param array $dirs
	 */
	public function setExcludedDirs (array $dirs)
	{
		$this->excludedDirs = $dirs;
	}
}
