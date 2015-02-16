<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\FileIteratorFactory;
use edsonmedina\php_testability\HTMLReport;
use edsonmedina\php_testability\CSVReport;
use edsonmedina\php_testability\Contexts\RootContext;

class Testability
{
	private $path;
	private $excludeDirs;
	private $reportDir;
	private $shouldOutputCSV;

	public function __construct ($path, $reportDir)
	{
		$this->path      = $path;
		$this->reportDir = $reportDir;
	}

	public function setExcludeDirs ($exclude)
	{
		$this->excludeDirs = $exclude;
	}

	public function setCSV ($value)
	{
		$this->shouldOutputCSV = $value;
	}

	public function runReport ()
	{
		echo "\nPHP_Testability by Edson Medina\n";
		
		$start_ts  = microtime (TRUE);

		// run
		$report = new RootContext ($this->path);
		$files  = (new FileIteratorFactory)->create();

		if (!empty($this->excludeDirs)) {
			$files->setExcludedDirs (explode(',', $this->excludeDirs));
		}

		echo "Analysing code on \"".$this->path."\"...\n";
		$files->iterate ($report);

		$scan_time = number_format (microtime(TRUE) - $start_ts, 2);
		echo " OK ({$scan_time}s).\n\n";

		// generate HTML report
		$htmlReport = new HTMLReport ($report, $this->reportDir); 
		$htmlReport->generate ();

		// generate CSV report
		if ($this->shouldOutputCSV) {
			$csvReport = new CSVReport ($report, $this->reportDir); 
			$csvReport->generate ();
		}
		
		// output info
		$total_time = number_format (microtime (TRUE) - $start_ts, 2);
		echo "Done (Total: {$total_time}s).\n\n";

		echo $files->getProcessedFilesCount()." processed files.\n";
		echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes of memory used\n\n";
	}
}
