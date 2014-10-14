<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportInterface;
use edsonmedina\php_testability\ReportDataInterface;

class HTMLReport implements ReportInterface
{
	private $baseDir   = '';
	private $reportDir = '';
	private $data;

	/**
	 * @param string              $baseDir   Where the code resides
	 * @param string              $reportDir Where to generate the report 
	 * @param ReportDataInterface $data      Report data
	 */
	public function __construct ($baseDir, $reportDir, ReportDataInterface $data)
	{
		$this->baseDir   = $baseDir;
		$this->reportDir = $reportDir;
		$this->data      = $data;
	}

	/**
	 * Generate HTML report
	 */
	public function generate ()
	{
		echo "\n\nGenerating report... ";

		if (!is_dir($this->reportDir)) {
			mkdir ($this->reportDir);	
		}

		foreach ($this->data->getFileList() as $file) {
			$this->generateFile ($file);
		}

		// $this->generateIndexFiles ();

		// DEBUG
		file_put_contents ('debug.log', json_encode ($this->data->dumpAllIssues(), JSON_PRETTY_PRINT));

		echo "OK.\n\n";
	}

	/**
	 * Generate file
	 * @param string $filename
	 */
	public function generateFile ($filename)
	{
		// Load code and line numbers into array 
		$content = file ($filename);
		$code    = array ();

		for ($i = 1, $len = count ($content); $i <= $len; $i++) 
		{
			@$code[$i]['line'] = $i;
			@$code[$i]['code'] = $content[$i-1];
		}
		$content = null;


		// get issues per scope / line
		$issues = $this->data->getIssuesForFile ($filename);

		$scopes = array ();
		
		if (isset($issues['scoped'])) 
		{
			foreach ($issues['scoped'] as $scope => $report)
			{
				// count issues inside scope
				$numIssues = 0;
				foreach ($report as $type => $list) {
					$numIssues += (count($list));

					// list issues per line
					foreach ($list as $issue) 
					{
						list ($name, $lineNum) = $issue;
						@$code[$lineNum]['issues'][] = array ('type' => $type, 'name' => $name);
					}
				}

				$scopes[] = array (
					'name'   => $scope, 
					'issues' => $numIssues
				);
			}
		}
		$issues = null;

		// render
		$m = new \Mustache_Engine (array(
			'loader' => new \Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		));

		$relFilename = $this->convertPathToRelative ($filename);

		$output = $m->render ('file', array (
			'currentPath' => $relFilename,
			'scopes'      => $scopes,
			'lines'       => $code,
			// 'untestable'  => $issues['global']
		));

		$this->saveFile ($relFilename.'.html', $output);
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
		return substr ($path, strlen($this->baseDir)+1);
	}
}