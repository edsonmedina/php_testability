<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\FileIterator;
use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\HTMLReport;

class Testability
{
	private $path;
	private $excludeDirs;
	private $reportDir;
	private $cloverXML;

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

	public function runReport ()
	{
		$start_ts  = microtime (TRUE);

		// run
		$data     = new ReportData ();
		$analyser = new Analyser ($data);
		$iterator = new FileIterator ($this->path, $analyser);

		if (!empty($this->excludeDirs)) {
			$iterator->setExcludedDirs (explode(',', $this->excludeDirs));
		}

		echo "\nPHP_Testability by Edson Medina\n";
		echo "Analysing code on \"".$this->path."\"...\n";
		$iterator->run ();

		if ($this->cloverXML) {
			echo "\n\nImporting clover report...\n";
			$clover = file_get_contents ($this->cloverXML);
			echo "NOT IMPLEMENTED YET. SORRY.\n";
		}

		$report = new HTMLReport ($this->path, $this->reportDir, $data); 
		$report->generate ();

		$total_time = number_format (microtime (TRUE) - $start_ts, 2);

		echo "Done ({$total_time}s).\n\n";
		echo $iterator->getProcessedFilesCount()." processed files.\n";
		echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes of memory used\n\n";
	}
}