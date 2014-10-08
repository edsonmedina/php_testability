<?php
/**
 * php_testability
 * @author Edson Medina <edsonmedina@gmail.com>
 */
$autoloader_locations = array (
	__DIR__.'/../../autoload.php', 
	__DIR__.'/vendor/autoload.php', 
	__DIR__.'/../../../vendor/autoload.php'
);
foreach ($autoloader_locations as $file) {
    if (file_exists($file)) {
    	require_once $file;
    }
}

error_reporting (E_ALL); // that's how we roll

use edsonmedina\php_testability\FileIterator;
use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\HTMLReport;
use Commando\Command;

// run
$data     = new ReportData ();
$analyser = new Analyser ($data);
$iterator = new FileIterator (PATH, $analyser);
$iterator->run ();

$report = new HTMLReport (); 
$report->generate ($data);