<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\FileIterator;
use edsonmedina\php_testability\AnalyserFactory;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\HTMLReport;

class Testability
{
	private $path;
	private $excludeDirs;
	private $reportDir;
	//private $cloverXML;
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

	public function setCloverReport ($file)
	{
		$this->cloverXML = $file;
	}

	public function setCSV ($value)
	{
		$this->shouldOutputCSV = $value;
	}

	public function runReport ()
	{
		$start_ts  = microtime (TRUE);

		// run
		$data     = new ReportData ();
		$analyser = (new AnalyserFactory)->create ($data);
		$files = new FileIterator ($analyser);

		if (!empty($this->excludeDirs)) {
			$files->setExcludedDirs (explode(',', $this->excludeDirs));
		}

		echo "\nPHP_Testability by Edson Medina\n";
		echo "Analysing code on \"".$this->path."\"...\n";
		$files->iterate ($this->path);

		$scan_ts   = microtime (TRUE);
		$scan_time = number_format ($scan_ts - $start_ts, 2);
		echo " OK ({$scan_time}s).\n\n";


		// code coverage 
		/*
		if ($this->cloverXML) {
			echo "\n\nImporting clover report...\n";
			$clover = file_get_contents ($this->cloverXML);
			echo "NOT IMPLEMENTED YET. SORRY.\n";
		}
		*/


		// generate HTML report
		echo "Generating report to {$this->reportDir} ... ";
		$report = new HTMLReport ($this->path, $this->reportDir, $data, $this->shouldOutputCSV); 
		$report->generate ();

		$report_ts   = microtime (TRUE);
		$report_time = number_format ($report_ts - $scan_ts, 2);
		echo "OK ({$report_time}s).\n\n";

		$total_time = number_format (microtime (TRUE) - $start_ts, 2);

		echo "Done (Total: {$total_time}s).\n\n";
		echo $files->getProcessedFilesCount()." processed files.\n";
		echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes of memory used\n\n";
	}
}
