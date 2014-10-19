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
	 * @param string $baseDir   Where the code resides
	 * @param string $reportDir Where to generate the report 
	 * @param ReportDataInterface $data  Report data
	 */
	public function __construct ($baseDir, $reportDir, ReportDataInterface $data)
	{
		$this->baseDir   = rtrim($baseDir, DIRECTORY_SEPARATOR);
		$this->reportDir = $reportDir;
		$this->data      = $data;
	}

	/**
	 * Generate HTML report
	 */
	public function generate ()
	{
		echo "\n\nGenerating report to {$this->reportDir} ... ";

		if (!is_dir($this->reportDir)) {
			mkdir ($this->reportDir);	
		}

		foreach ($this->data->getFileList() as $file) {
			$this->generateFile ($file);
		}

		foreach ($this->data->getFullDirList() as $path) {
			$this->generateIndexFile ($path);
		}

		if (DEBUG) {
			echo "Generating debug.log...\n";
			file_put_contents ('debug.log', json_encode ($this->data->_dumpAllIssues(), JSON_PRETTY_PRINT));
		}

		echo "OK.\n\n";
	}

	/**
	 * Generate file
	 * @param string $filename
	 */
	public function generateFile ($filename)
	{
		if (DEBUG) {
			echo "Generating file {$filename}...\n";
		}

		// Load code and line numbers into array 
		$content = file ($filename);
		$code    = array (array ());

		for ($i = 1, $len = count ($content); $i <= $len; $i++) 
		{
			@$code[$i]['line'] = $i;
			@$code[$i]['code'] = rtrim($content[$i-1]);
		}
		$content = null;


		// get scopes
		$fileScopes = $this->data->getScopesForFile ($filename);
		$scopes     = array ();

		foreach ($fileScopes as $scope) 
		{
			$scopes[] = array (
				'name'     => $scope . '()',
				'position' => $this->data->getScopePosition ($filename, $scope),
				'issues'   => $this->data->getIssuesCountForScope ($filename, $scope)
			);
		}


		// get issues per scope / line
		$issues = $this->data->getIssuesForFile ($filename);
		
		if (isset($issues['scoped'])) 
		{
			// TODO move $issues[scoped] into a getter method
			foreach ($issues['scoped'] as $scope => $report)
			{
				foreach ($report as $type => $list) 
				{
					// list issues per line
					foreach ($list as $issue) 
					{
						list ($name, $lineNum) = $issue;
						@$code[$lineNum]['issues'][] = array ('type' => $type, 'name' => $name);
					}
				}
			}
		}

		if (isset($issues['global'])) 
		{
			// add global issues
			foreach ($issues['global'] as $type => $list) 
			{
				foreach (array_keys($list) as $lineNum) 
				{
					@$code[$lineNum]['issues'][] = array ('type' => $type);
				}
			}

			$scopes[] = array (
				'name'     => '<global>',
				'position' => '',
				'issues'   => $this->data->getGlobalIssuesCount ($filename)
			);
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
			'date'        => date('r'),
			// 'untestable'  => $issues['global']
		));

		$this->saveFile ($relFilename.'.html', $output);
	}

	/**
	 * Generate index file
	 * @param string $path
	 */
	public function generateIndexFile ($path)
	{
		if (DEBUG) {
			echo "Generating index file for {$path}...\n";
		}

		// list directory
		$files = array ();
		$dirs  = array ();
		
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
    			$files[] = array (
    				'file'   => basename($filename),
    				'issues' => $this->data->getIssuesCountForFile ($filename),
    				'untestable' => $this->data->isFileUntestable ($filename) 
    			);
    		}
		}

		// render
		$m = new \Mustache_Engine (array(
			'loader' => new \Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		));

		$relPath = $this->convertPathToRelative ($path);

		$output = $m->render ('dir', array (
			'currentPath' => $relPath,
			'files'       => $files,
			'dirs'        => $dirs,
			'date'        => date('r'),
			'isBaseDir' => ($this->baseDir === $path)
		));

		$this->saveFile ($relPath.'/index.html', $output);		
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