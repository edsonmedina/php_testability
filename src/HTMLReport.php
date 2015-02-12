<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\FileReport;
use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;

class HTMLReport 
{
	private $baseDir   = '';
	private $reportDir = '';
	private $report;
	private $outputCSV;

	/**
	 * @param ContextInterface $report
	 * @param string $reportDir Where to generate the report 
	 * @param bool $outputCSV 	Output CSV files per directory
	 */
	public function __construct (ContextInterface $report, $reportDir, $outputCSV = false)
	{
		$this->baseDir   = $report->getName();
		$this->reportDir = $reportDir;
		$this->report    = $report;
		$this->outputCSV = $outputCSV;
	}

	/**
	 * Generate HTML report
	 */
	public function generate ()
	{
		if (!is_dir($this->reportDir)) {
			mkdir ($this->reportDir);	
		}

		$this->iterate ($this->report);
	}

	/**
	 * Iterate the report
	 * @param ContextInterface $root
	 * @return void
	 */
	protected function iterate (ContextInterface $root) 
	{
		foreach ($root->getChildren() as $item) 
		{
			if ($item instanceof FileContext)
			{
				$this->generateFile ($item);
			}
			elseif ($item instanceof DirectoryContext)
			{
				$this->iterate ($item);
				// $this->generateIndexFile ($item);

				//if ($this->outputCSV) {
				//	$this->generateCSV ($item);
				//}
			}
		}
	}

	/**
	 * Generate file
	 * @param FileContext $file
	 */
	public function generateFile (FileContext $file)
	{
		// Load code and line numbers into array 
		$code = $this->getContentInTuples ($file->getName());

		// load scopes names, lines and issues
		$scopes = $file->getContextsNumberOfIssues();

		// get list of issues per line
		$issues = $file->getIssues (true);

		foreach ($issues as $issue) {
			$code[$issue->getLine()-1]['issues'][] = array (
				'type' => $issue->getTitle(),
				'name' => $issue->getID()
			);
		}

		// render
		$view = new \Mustache_Engine (array(
			'loader' => new \Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		));

		$relFilename = $this->convertPathToRelative ($file->getName());

		$output = $view->render ('file', array (
			'currentPath' => $relFilename,
			'scopes'      => $scopes,
			'lines'       => $code,
			'date'        => date('r'),
		));

		$this->saveFile ($relFilename.'.html', $output);
	}

	/**
	 * Returns file contents as array of tuples (array('line' => 12, 'text' => '...'))
	 * @param string $filename
	 * @return array
	 */
	public function getContentInTuples ($filename)
	{
		$result = array ();
		$lineNumber = 1;

		// create array of tuples (line, code)
		foreach (file ($filename) as $line)
		{
			$result[] = array (
				'line' => $lineNumber++,
				'text' => rtrim($line)
			);
		}

		return $result;
	}


	/**
	 * Generate index file
	 * @param string $path
	 */
	public function generateIndexFile ($path)
	{
		// list directory
		$files = array ();
		$dirs  = array ();

		$fileReport = new FileReport ($this->data);
		
		foreach ($this->data->listDirectory($path) as $filename) 
		{
			// directory
    		if (is_dir($filename)) 
    		{
    			$dirs[] = array (
    				'name'   => basename($filename),
    				'issues' => $this->data->getIssuesCountForDirectory ($filename),
    			);
    		} 
    		// file
    		elseif (substr ($filename, -4, 4) == '.php')
    		{
				$totalScopes      = $fileReport->getCountOfScopes($filename);
				$untestableScopes = $fileReport->getCountOfScopesWithIssues($filename);
				$testableScopes   = $totalScopes - $untestableScopes;
    			$percent          = $totalScopes > 0 ? number_format (($testableScopes / $totalScopes) * 100, 2) : 0;

    			$files[] = array (
    				'file'     => basename($filename),
    				'total'    => $totalScopes,
    				'testable' => $testableScopes,
    				'percent'  => $percent,
                    'label'    => $percent == 100 ? 'success' : ($percent > 70 ? 'warning' : 'danger')
    			);
    		}
		}

		// render
		$view = new \Mustache_Engine (array(
			'loader' => new \Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		));

		$relPath = $this->convertPathToRelative ($path);

		$output = $view->render ('dir', array (
			'currentPath' => $relPath,
			'files'       => $files,
			'dirs'        => $dirs,
			'date'        => date('r'),
			'isBaseDir'   => ($this->baseDir === $path)
		));

		$this->saveFile ($relPath.'/index.html', $output);		
	}

	/**
	 * Generate CSV files
	 * TODO: this method shouldn't be here, it needs a class of its own
	 * @param string $path
	 */
	public function generateCSV ($path)
	{
		$total = $this->data->getIssuesCountForDirectory ($path);

		$relPath = $this->convertPathToRelative ($path);
		$this->saveFile ($relPath.'/total.csv', '"Total Issues"'.PHP_EOL.$total);
	}

	/**
	 * Saves file to filesystem
	 * @param string $filename RELATIVE filename
	 * @param string $contents
	 */
	public function saveFile ($filename, $contents)
	{
		// make sure the directory exists
		$dirname = $this->reportDir.'/'.dirname ($filename);

		if ($dirname && !is_dir($dirname)) {
			mkdir ($dirname, 0777, true);
		}

		// save
		file_put_contents ($this->reportDir.'/'.$filename, $contents);
	}

	/**
	 * Convert absolute path into relative
	 * @param string $path
	 * @return string $path
	 */
	public function convertPathToRelative ($path)
	{
		$newPath = substr ($path, strlen($this->baseDir)+1);
		return $newPath;
	}
}
