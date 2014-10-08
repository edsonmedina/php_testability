<?php
/**
 * PHP_Testability 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */
namespace edsonmedina\php_testability;

class FileIterator 
{
	private $baseDir;
	private $analyser;

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
		$this->iterate ($this->baseDir);
	}

	/** 
	 * Scans (recursively) the path and runs the analyser for each file
	 * @param  string $path File or directory
	 */
	public function iterate ($path)
	{
		if (is_dir ($path)) 
		{
			// recurse into directory
			foreach (new \DirectoryIterator ($path) as $fileInfo)
			{
				if ($fileInfo->isDot()) 
				{
					continue;
				}

				$this->iterate ($fileInfo->getFilename());
			}
		}
		elseif (is_file ($path))
		{
			// analyse file
			$this->analyser->scan ($path);
		}
	}
}