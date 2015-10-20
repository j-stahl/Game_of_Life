<?php
/**
 *
 * @file
 * @version 1.0
 * @copyright 2015 CN-Consult GmbH
 * @author Jens Stahl <jens.stahl@cn-consult.eu>
 */

namespace gameField;

use cell\Cell;

require_once "cell.php";

/**
 * Builds new game of life cycle depending on start values.
 *
 * Class GameField
 */
class GameField
{
    private $fieldCells = [];
    private $width = 0;
    private $height = 0;

    /**
     * Creates new empty game field.
     *
     * @param int $_x Width.
     * @param int $_y Height.
     */
    public function __construct($_x, $_y)
    {
        $this->width = $_x;
        $this->height = $_y;
        for ($i = 0; $i < $this->height; $i++)
        {
            for ($j = 0; $j < $this->width; $j++)
            {
                $this->fieldCells[$i][$j]= new Cell;
            }
        }
    }

    /**
     * Builds the first evolution of live cycles.
     *
     * @param string $_transmittedString Values for complete field"---x--x-x-x-x-x-".
     */
    public function buildStartField($_transmittedString)
    {
        $position = 0;
        for ($i = 0; $i < $this->height; $i++)
        {
            for ($j = 0; $j < $this->width; $j++)
            {
                if ($_transmittedString[$position] == "x")
                {
                    $cell =$this->fieldCells[$i][$j];
                    $cell->setAlive();
                }
                $position++;
            }
        }
        $this->executeNextCycle();
    }

    /**
     * Executes the next life cycle depending on settings made here.
     */
    public function executeNextCycle()
    {
        for ($i = 0; $i < $this->height; $i++)
        {
            for ($j = 0; $j < $this->width; $j++)
            {
                $cell = $this->fieldCells[$i][$j];
                $cell->cycle();
            }
        }
    }

    /**
     * Searches living neighbors.
     *
     * @param int $_y y-coordinate of cell (height).
     * @param int $_x x-coordinate of cell (width).
     * @return int amount of living neighbors.
     */
    public function numAliveNeighbors($_y, $_x)
    {
        $numAliveNeighbors = 0;
        if ($this->isAlive(($_y - 1), ($_x - 1))) $numAliveNeighbors++;
        if ($this->isAlive(($_y - 1), ($_x)))     $numAliveNeighbors++;
        if ($this->isAlive(($_y - 1), ($_x + 1))) $numAliveNeighbors++;
        if ($this->isAlive(($_y), ($_x - 1)))     $numAliveNeighbors++;
        if ($this->isAlive(($_y), ($_x + 1)))     $numAliveNeighbors++;
        if ($this->isAlive(($_y + 1), ($_x - 1))) $numAliveNeighbors++;
        if ($this->isAlive(($_y + 1), ($_x)))     $numAliveNeighbors++;
        if ($this->isAlive(($_y + 1), ($_x + 1))) $numAliveNeighbors++;
        return $numAliveNeighbors;
    }


    /**
     * checks if the neighbor defined through x and y coordinates is alive.
     *
     * @param int $_y y-value.
     * @param int $_x x-value.
     * @return bool returns true if it is alive.
     */
    public function isAlive($_y, $_x)
    {
        if (($_x < 0) ||
            ($_x >= $this->width) ||
            ($_y >= $this->height) ||
            ($_y < 0)
        )
            return false;
        else
        {/** @var Cell $cell */
            $cell = $this->fieldCells[$_y][$_x];
            return $cell->isAlive();
        }
    }

    /**
     * @return int Width of game field.
     */
    public function width()
    {
        return $this->width;
    }

    /**
     * @return int Height of game field.
     */
    public function height()
    {
        return $this->height;
    }

    /**
     * sets specified cell to alive in next life cycle.
     *
     * @param int $_y y-coordinate of cell (height).
     * @param int $_x x-coordinate of cell (width).
     */
    public function setAlive($_y, $_x)
    {/** @var Cell $cell */
        $cell = $this->fieldCells[$_y][$_x];
        $cell->setAlive();
    }
}
