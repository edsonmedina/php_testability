<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\Contexts\ProcedureSpecification;
use edsonmedina\php_testability\Contexts\DirectorySpecification;

class CSVReport
{
	private $baseDir   = '';
	private $reportDir = '';
	private $report;

	/**
	 * @param ContextInterface $report
	 * @param string $reportDir Where to generate the report 
	 */
	public function __construct (ContextInterface $report, $reportDir)
	{
		$this->baseDir   = $report->getName();
		$this->reportDir = $reportDir;
		$this->report    = $report;
	}

	/**
	 * Generate CSV report
	 */
	public function generate ()
	{
		$startTime = microtime (true);

		if (!is_dir($this->reportDir)) 
		{
			echo "Creating new directory {$this->reportDir}... ";
			mkdir ($this->reportDir);	
			echo "OK\n";
		}

		echo "Generating CSV report to {$this->reportDir} ... ";

		$this->generateCsvForDirectory ($this->report);
		foreach ($this->report->getChildrenRecursively(new DirectorySpecification) as $item) 
		{
			$this->generateCsvForDirectory ($item);
		}

		$totalTime = number_format (microtime(true) - $startTime, 2);
		echo "OK ({$totalTime}s).\n";
	}

	/**
	 * Generate index file
	 * @param ContextInterface $path (DirectoryInterface or RootInterface)
	 */
	public function generateCsvForDirectory (ContextInterface $path)
	{
		// list directory
		$numbers = $this->getTotalTestableProcedures($path);

		// render
		$relPath = $this->convertPathToRelative ($path->getName());
		$this->saveCSV ($relPath, $numbers);		
	}

	/**
	 * Return a count of total/testable procedures
	 * @param ContextInterface $root
	 * @return array ('total' => 12, 'testable' => 4)
	 */
	public function getTotalTestableProcedures (ContextInterface $root)
	{
		$total    = 0;
		$testable = 0;

		foreach ($root->getChildrenRecursively(new ProcedureSpecification) as $proc)
		{
			$total++;

			if (!$proc->hasIssues())
			{
				$testable++;
			}
		}

		return array ('total' => $total, 'testable' => $testable);
	}

	/**
	 * Saves file to filesystem
	 * @param string $dir RELATIVE directory
	 * @param array $result
	 */
	public function saveCSV ($dir, array $result)
	{
		// make sure the directory exists
		$dirname = $this->reportDir.DIRECTORY_SEPARATOR.$dir;

		if ($dirname && !is_dir($dirname)) {
			mkdir ($dirname, 0777, true);
		}

		$content  = '"total","testable"'."\n";
		$content .= '"'.$result['total'].'","'.$result['testable'].'"'."\n";
		
		// save
		file_put_contents ($dirname.DIRECTORY_SEPARATOR.'totals.csv', $content);
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
