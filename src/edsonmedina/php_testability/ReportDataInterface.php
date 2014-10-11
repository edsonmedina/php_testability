<?php
namespace edsonmedina\php_testability;

interface ReportDataInterface  
{
	public function addIssue ($line, $type, $scope, $identifier);
	public function setCurrentFilename ($filename);
	public function getIssuesCountForPath ($path);
	public function addFileIssue ($line, $type);
}