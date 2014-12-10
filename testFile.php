<?php

/**
 * This is a test file, with several issues that 
 * should be reported by testability.
 */
class Whatever
{
    public function methodMan ($x, $y, $z)
    {
        // globals, over multiple lines
        global $boom, 
        	   $bass;

        // new instances
       	$x = new StdClass();

        // Static property of another class
       	$a = OtherClass::thing;

        // Static method call, another class
        Whatever::methodMan();

        // Static method call, dynamic class
        $xx::doBadThings();

        // Static method call, same class
        self::methodMan();

        // Parent class method call
        parent::methodMan();

        // Static constant from same class
       	$b = Whatever::notThisOne;

        // includes are dangerous
        include $dir.'dangerousFile.php';
        include_once 'dangerousFile2.php';

        // Callables (should be supported in the future)
        array_map ('Blah::something', array(1,2,3));
       	
        // Static dynamic method call, another class
        $y = Utils::$name;

        // fluent interface method call (new instance, super global)
        $thing = (new \Some\ClassThing())->doSomething($_GET['blah'])->run();

        // global function call
        dothis();

        // exit
        die('fff');

        // no return
    }

    function __set ($name, $val)
    {
        $this->values[$name] = $val;

        // no return (not reported on __set methods)
    }

    private function privateParts ()
    {
        // this method is untestable
    }

    protected function privateParts2 ()
    {
        // this one too
    } 
}

// this contains several of the same issues
// to test the parsing on global functions
function dothis()
{
    global $diddy;
    
    $y = new Whatever ();
    $y->methodMan();

    // super global references
    // globals, different form
    $w = $GLOBALS['whatever']['subnode'][$index];
    $p1 = $_GET['p1'];

    $normalArray['whatever']['happens'] = 'in vegas';

    $ss = Zzz::numberOfThings;

    // require is also dangerous
    require 'iReallyShouldnt.php';

    $varClass::method1();

    try 
    {
        callThisFunc();
    }
    catch (Exception $e) {}

    Stuff::dependency();
}

# code on global space
thisScrewsTheFile();

BadThings::happen();

global $thingy;

$y = $_GET['y'];

$BLAH = 'ugly';

function __autoload ($xxx)
{
    // require should not be reported on __autoload
    require_once 'src/'.$xxx;

    // no return, should not be reported on __autoload
}
