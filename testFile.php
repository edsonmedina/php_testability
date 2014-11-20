<?php

/**
 * Comments bla bla bla
 */

class Whatever
{
    public function methodMan ($x, $y, $z)
    {
        global $boom, 
        	   $bass;

       	$x = new StdClass();
       	$a = OtherClass::thing;

        Whatever::methodMan();
        self::methodMan();
        parent::methodMan();

       	$b = Whatever::notThisOne;

        $xx::doBadThings();

        include 'dangerousFile.php';
        include_once 'dangerousFile2.php';

        array_map ('Blah::something', array(1,2,3));
       	// $y = Utils::$name;

        $thing = (new \Some\ClassThing())->doSomething($_GET['blah'])->run();

        dothis();
		    die('fff');
    }

    public function __set ($name, $val)
    {
        $this->values[$name] = $val;

        // no return
    }
}

// another one here

function dothis()
{
    global $diddy;
    
    $y = new Whatever ();
    $y->methodMan();

    $w = $GLOBALS['whatever']['subnode'][$index];
    $p1 = $_GET['p1'];

    $normalArray['whatever']['happens'] = 'in vegas';

    $ss = Zzz::numberOfThings;

    require 'iReallyShouldnt.php';
    $varClass::method1();

    try 
    {
        callThisFunc();
    }
    catch (Exception $e) {}

    Stuff::dependency();
}

# some more

thisScrewsTheFile();

BadThings::happen();

global $thingy;

$y = $_GET['y'];

$BLAH = 'ugly';

function __autoload ($xxx)
{
    $x = 1;
    // no return
}