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
	static function runReport ()
	{
		$start_ts  = microtime (TRUE);

		// run
		$data     = new ReportData ();
		$analyser = new Analyser ($data);
		$iterator = new FileIterator (PATH, $analyser);

		if (EXCLUDE_DIRS != '') {
			$iterator->setExcludedDirs (explode(',', EXCLUDE_DIRS));
		}

		echo "\nPHP_Testability by Edson Medina\n";
		echo "Analysing code on ".PATH."...\n";
		$iterator->run ();

		$report = new HTMLReport (PATH, REPORT_DIR, $data); 
		$report->generate ();

		$total_time = number_format (microtime (TRUE) - $start_ts, 2);

		echo "Done ({$total_time}s).\n\n";
		echo $iterator->getProcessedFilesCount()." processed files.\n";
		echo number_format (memory_get_peak_usage()/1024/1024, 2)." Mbytes of memory used\n\n";
	}
}