<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportInterface;
use edsonmedina\php_testability\ReportDataInterface;

class HTMLReport implements ReportInterface
{
	private $reportDir = '';
	private $data;

	public function __construct ($reportDir, ReportDataInterface $data)
	{
		if (!is_dir($reportDir)) {
			mkdir ($reportDir);	
		}

		$this->reportDir = $reportDir;
		$this->data = $data;
	}

	/**
	 * Generate HTML report
	 */
	public function generate ()
	{
		$baseDir = $this->findBaseDirectory();

		// TODO
		// 
		
		file_put_contents ('debug.log', json_encode ($this->data->dumpAllIssues(), JSON_PRETTY_PRINT));

		// print_r ($data->dumpAllIssues());
		
	}

	/**
	 * Find highest common path for files
	 * @return string
	 */
	public function findBaseDirectory ()
	{
		$list = $this->data->getFileList ();
		
		$baseDir = null;

		// FIXME for empty reports
		$dirParts = explode (DIRECTORY_SEPARATOR, dirname($list[0]));

		foreach ($list as $file)
		{
			$matching = '';
			$path = '';

			foreach ($dirParts as $dir)
			{
				$path .= $dir . DIRECTORY_SEPARATOR; 
				if (substr($file, 0, strlen($path)) === $path) {
					$matching = $path;
				}
				else
				{
					break;
				}
			}

			if (is_null($baseDir)) {
				$baseDir = $path;	
			} else {
				$baseDir = (strlen($baseDir) > strlen($matching)) ? $matching : $baseDir;
			}
		}

		return $baseDir;
	}
}