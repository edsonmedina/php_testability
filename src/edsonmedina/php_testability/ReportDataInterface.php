<?php
namespace edsonmedina\php_testability;

interface ReportDataInterface  
{
	public function setCurrentFilename ($filename);
	public function getIssuesCountForFile ($filename);
	public function addIssue ($line, $type, $scope = null, $identifier = null);
	public function getFileList ();
	public function getDirList ();
}