<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\Contexts\DirectoryContext;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\Contexts\ProcedureSpecification;
use Mustache_Loader_FilesystemLoader;
use Mustache_Engine;

class HTMLReport
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
	 * Generate HTML report
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

		echo "Generating HTML report to {$this->reportDir} ... ";
		$this->iterate ($this->report);

		$totalTime = number_format (microtime(true) - $startTime, 2);
		echo "OK ({$totalTime}s).\n";
	}

	/**
	 * Iterate the report
	 * @param ContextInterface $root
	 * @return void
	 */
	protected function iterate (ContextInterface $root) 
	{
		$this->generateIndexFile ($root);

		foreach ($root->getChildren() as $item) 
		{
			if ($item instanceof FileContext)
			{
				$this->generateFile ($item);
			}
			elseif ($item instanceof DirectoryContext)
			{
				$this->iterate ($item);
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
			$code[$issue->getLine()-1]['issues'][] = [
				'type' => $issue->getTitle(),
				'name' => $issue->getID()
			];
		}

		// render
		$view = new Mustache_Engine ([
			'loader' => new Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		]);

		$relFilename = $this->convertPathToRelative ($file->getName());

		$output = $view->render ('file', [
			'currentPath' => $relFilename,
			'scopes'      => $scopes,
			'lines'       => $code,
			'date'        => date('r'),
		]);

		$this->saveFile ($relFilename.'.html', $output);
	}

	/**
	 * Returns file contents as array of tuples (['line' => 12, 'text' => '...'])
	 * @param string $filename
	 * @return array
	 */
	public function getContentInTuples ($filename)
	{
		$result     = [];
		$lineNumber = 1;

		// load file and create array of tuples (line, code)
		foreach (file ($filename) as $line)
		{
			$result[] = [
				'line' => $lineNumber++,
				'text' => rtrim($line)
			];
		}

		return $result;
	}

	/**
	 * Generate index file
	 * @param ContextInterface $path (DirectoryInterface or RootInterface)
	 */
	public function generateIndexFile (ContextInterface $path)
	{
		// list directory
		$files = [];
		$dirs  = [];

		foreach ($path->getChildren() as $child)
		{
			$filename = $child->getName();

			$numbers = $this->getTotalTestableProcedures($child);

			$percent = $numbers['total'] > 0 ? ($numbers['testable'] / $numbers['total']) : 1;

			$node = [
				'name'     => basename($filename),
				'total'    => $numbers['total'],
				'testable' => $numbers['testable'],
				'percent'  => number_format ($percent*100, 2),
                'label'    => $numbers['total'] ? $this->getCssClass($percent) : ''
			];

			if ($child instanceof DirectoryContext)
			{
    			$dirs[] = $node;
			}
			elseif ($child instanceof FileContext)
			{
    			$files[] = $node;
			}
		}

		// render
		$view = new Mustache_Engine ([
			'loader' => new Mustache_Loader_FilesystemLoader (__DIR__.'/views'),
		]);

		$relPath = $this->convertPathToRelative ($path->getName());


		$output = $view->render ('dir', [
			'currentPath' => $relPath,
			'files'       => $files,
			'dirs'        => $dirs,
			'date'        => date('r'),
			'isBaseDir'   => ($this->baseDir === $path->getName())
		]);

		$this->saveFile ($relPath.'/index.html', $output);		
	}

	/**
	 * Gets a css class name to use, according to the percentage
	 * @param int $percentage (0 to 1)
	 * @return string class name
	 */
	public function getCssClass ($percentage)
	{
		if ($percentage >= 0.8) 
		{
			return 'success';
		}
		elseif ($percentage >= 0.5)
		{
			return 'warning';
		} 
		else 
		{
			return 'danger';
		}
	}

	/**
	 * Return a count of total/testable procedures
	 * @param ContextInterface $root
	 * @return ['total' => 12, 'testable' => 4]
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

		return ['total' => $total, 'testable' => $testable];
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
