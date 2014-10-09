<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportInterface;
use edsonmedina\php_testability\ReportDataInterface;

class HTMLReport implements ReportInterface
{
	public function generate (ReportDataInterface $data)
	{
		print_r ($data->dumpAllIssues());
	}
}