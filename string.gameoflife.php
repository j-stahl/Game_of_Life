<?php
// String via AJAX displayed in table
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 06.10.2015
 * Time: 16:16
 */

include_once 'gamefield.php';
include_once 'gameoflife.interface.php';

class StringGameOfLife implements GameOfLifeInterface
{
    private $width = 0;
    private $height = 0;
    /** @var \gameField\GameField */
    private $gameField;

    /**
     * Creates new StringGameOfLife object.
     *
     * @param string $_string GameFieldValues "---x-x---x-x--x-x".
     * @param int $_fieldWidth GameFieldWidth in amount of squares.
     * @param int $_fieldHeight GameFieldHeight in amount of squares.
     */
    public function __construct($_string, $_fieldWidth, $_fieldHeight)
    {
        $this->width = $_fieldWidth;
        $this->height = $_fieldHeight;
        $this->gameField = new \gameField\GameField($_fieldWidth, $_fieldHeight);
        $this->gameField->buildStartField($_string);
    }

    /**
     * Calculates the next life cycle of cells and executes it.
     *
     */
    public function calculateNextLifeCycle()
    {
        for ($i=0; $i<$this->gameField->height(); $i++)
        {
            for ($j=0; $j<$this->gameField->width(); $j++)
            {
                $numAliveNeighbors = $this->gameField->numAliveNeighbors($i, $j);
                if ($numAliveNeighbors == 3 ||
                    $numAliveNeighbors == 2 &&
                    $this->gameField->isAlive($i, $j))
                    $this->gameField->setAlive($i, $j);
            }
        }
        $this->gameField->executeNextCycle();
    }

    /**
     * Displays the finally calculated string of next life cycle.
     *
     */
    public function displayResult()
    {
        $arrayToString="";
        for ($i=0; $i<$this->height; $i++)
        {
            for ($j=0; $j<$this->width; $j++)
            {
                if ($this->gameField->isAlive($i, $j)) $arrayToString.="x";
                else                                   $arrayToString.="-";
            }
        }
        echo $arrayToString;
    }
}
