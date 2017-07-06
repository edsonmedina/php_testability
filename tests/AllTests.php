<?php

require_once __DIR__.'/../vendor/autoload.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit\Framework\TestSuite('PHP_Testability unit tests');

        self::addDir(__DIR__, $suite);

        return $suite;
    }

	private static function addDir ($path, $suite)
    {
        $dir = new RecursiveIteratorIterator (new RecursiveDirectoryIterator(realpath($path)));
        foreach ($dir as $file)
        {
            // skip dirs
            if ($file->isDir()) {
                continue;
            }

            // skip non-tests
            if (substr($file, -8) == 'Test.php')
            {
                // remove extension
                $className = substr($file->getFilename(), 0, -4);

                require_once $file;
                $suite->addTestSuite($className);
            }
        }
    }
}