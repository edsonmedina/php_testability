<?php

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

interface ReportInterface  
{
	public function __construct ($reportDir, ReportDataInterface $data);
	public function generate ();
}