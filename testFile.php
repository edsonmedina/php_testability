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

        dothis();
		    die('fff');
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

    callThisFunc();

    Stuff::dependency();
}

# some more

thisScrewsTheFile();

BadThings::happen();

global $thingy;

$y = $_GET['y'];

$BLAH = 'ugly';