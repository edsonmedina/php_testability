<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;

class FileIterator 
{
	private $analyser;
	private $excludedDirs = [];
	private $processedFilesCount;
	private $verbose;

	/**
	 * Runs the static analyser
	 * @param Analyser $analyser Static code analysis class
	 * @param bool $verbose
	 */
	public function __construct (Analyser $analyser, $verbose)
	{
		$this->analyser = $analyser;
		$this->verbose  = $verbose;
		$this->processedFilesCount = 0;
	}

	/** 
	 * Scans (recursively) the path and runs the analyser for each file
	 * @param ContextInterface $parent 
	 */
	public function iterate (ContextInterface $parent)
	{
		$path = $parent->getName();

		foreach (new \DirectoryIterator ($path) as $fileInfo)
		{
			if ($fileInfo->isDot()) {
				continue;
			}

			$fullPath = $path.DIRECTORY_SEPARATOR.$fileInfo->getFilename();

			if ($fileInfo->isDir() && !$this->isDirExcluded($fileInfo->getFilename())) 
			{
				$dir = new DirectoryContext ($fullPath);
				$this->iterate ($dir);

				// no need to keep directories
				// with no mathing files
				if ($dir->hasChildren())
				{
					$parent->addChild ($dir);
				}
			}
			elseif ($this->hasPhpExtension($fileInfo->getFilename()))
			{
				$file = new FileContext ($fullPath);

				if ($this->verbose) {
					echo $path.DIRECTORY_SEPARATOR.$fileInfo->getFilename()."... ";
				}
				
				$this->analyser->scan ($file);

				if ($this->verbose)	{
					echo "OK\n";
				} else {
					echo ".";
				}		

				$parent->addChild ($file);
				$this->processedFilesCount++;
			}
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
