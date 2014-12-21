<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

class FileIterator 
{
	private $baseDir;
	private $analyser;

	private $excludedDirs = array ();

	private $processedFilesCount;

	/**
	 * Runs the static analyser
	 * @param  string $baseDir Codebase directory
	 * @param  Analyser $analyser Static code analysis class
	 */
	public function __construct ($baseDir, AnalyserInterface $analyser)
	{
		$this->baseDir  = $baseDir;
		$this->analyser = $analyser;
	}

	/**
	 * Iterate files
	 */
	public function run ()
	{
		$this->processedFilesCount = 0;
		$this->iterate ($this->baseDir);
	}

	/** 
	 * Scans (recursively) the path and runs the analyser for each file
	 * @param  string $path File or directory
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
		elseif (is_file ($path) && !$this->hasPhpExtension($path))
		{
			// process file
			$this->analyser->scan ($path);
			$this->processedFilesCount++;
			echo ".";
		}
	}

	/**
	 * Exclude filter for directories
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
	 * Check for .php
	 */
	protected function hasPhpExtension ($path)
	{
		return (substr($path, -4, 4) != '.php');
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