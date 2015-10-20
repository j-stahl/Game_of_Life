<?php
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 14.10.2015
 * Time: 09:32
 */

interface GameOfLifeInterface
{
    //public function prepareReceivedData(); --> now in constructor
    public function calculateNextLifeCycle();
    public function displayResult();
}