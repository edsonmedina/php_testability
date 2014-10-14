<?php

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

interface ReportInterface  
{
	public function __construct ($baseDir, $reportDir, ReportDataInterface $data);
	public function generate ();
}