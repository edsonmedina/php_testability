<?php
namespace edsonmedina\php_testability;
use edsonmedina\php_testability\ReportDataInterface;

class FileReport
{
	protected $data;

	public function __construct (ReportDataInterface $data)
	{
		$this->data = $data;
	}

	public function getCountOfSCopes ($filename)
	{
		return count($this->data->getScopesForFile($filename));
	}

	public function getCountOfScopesWithIssues ($filename)
	{
		$count = 0;

		if ($this->getCountOfSCopes($filename) == 0)
		{
			return 0;
		}

		foreach ($this->data->getScopesForFile($filename) as $scope)
		{
			if ($this->data->getIssuesCountForScope($filename, $scope) > 0) 
			{
				$count++;
			}
		}

		return $count;
	}
}
