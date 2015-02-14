<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\FileIteratorFactory;
use edsonmedina\php_testability\HTMLReport;
use edsonmedina\php_testability\Contexts\RootContext;

class Testability
{
	private $path;
	private $excludeDirs;
	private $reportDir;
	private $shouldOutputCSV;

	public function __construct ($path, $reportDir)
	{
		$this->path = $path;
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
		$start_ts  = microtime (TRUE);

		// run
		$report = new RootContext ($this->path);
		$files  = (new FileIteratorFactory)->create($report);

		if (!empty($this->excludeDirs)) {
			$files->setExcludedDirs (explode(',', $this->excludeDirs));
		}

		echo "\nPHP_Testability by Edson Medina\n";
		echo "Analysing code on \"".$this->path."\"...\n";
		$files->iterate ($report);

		$scan_ts   = microtime (TRUE);
		$scan_time = number_format ($scan_ts - $start_ts, 2);
		echo " OK ({$scan_time}s).\n\n";


		// generate HTML report
		echo "Generating report to {$this->reportDir} ... ";
		$baseDir = is_dir($this->path) ? $this->path : dirname ($this->path); 
		$htmlReport = new HTMLReport ($report, $this->reportDir, $this->shouldOutputCSV); 
		$htmlReport->generate ();

		$report_ts   = microtime (TRUE);
		$report_time = number_format ($report_ts - $scan_ts, 2);
		echo "OK ({$report_time}s).\n\n";

		$total_time = number_format (microtime (TRUE) - $start_ts, 2);

		echo "Done (Total: {$total_time}s).\n\n";
		echo $files->getProcessedFilesCount()." processed files.\n";
		echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes of memory used\n\n";
	}
}
