<?php
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 14.10.2015
 * Time: 10:06
 */

$plugins = glob("*.gameoflife.php");
foreach($plugins as $plugin)
{
    include_once $plugin;
}


if (isset($_POST['fieldHeight']) &&
    isset($_POST['fieldWidth'])  &&
    isset($_POST['string'])         )
{
    if  (isset($_POST['gif']))
    {
        $gameOfLife = new GifGameOfLife($_POST['string'], $_POST['fieldWidth'], $_POST['fieldHeight']);
        $numLifeCycles = $_POST['gif'];
        $numLifeCycles*=1;
        if ($numLifeCycles>1) $gameOfLife->displayResult($numLifeCycles);
        else                  $gameOfLife->displayResult();
    }
    else
    {
        $gameOfLife = new StringGameOfLife($_POST['string'], $_POST['fieldWidth'], $_POST['fieldHeight']);
        $gameOfLife->calculateNextLifeCycle();
        $gameOfLife->displayResult();
    }
}