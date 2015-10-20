<?php
/**
 *
 * @file
 * @version 1.0
 * @copyright 2015 CN-Consult GmbH
 * @author Jens Stahl <jens.stahl@cn-consult.eu>
 */

namespace cell;

/**
 * Cells of game field.
 *
 * Class Cell
 */
class Cell
{
    private $state=false;
    private $newState=false;

    /**
     * Checks if cell is alive.
     *
     * @return bool actual life state of cell.
     */
    public function isAlive()
    {
        return $this->state;
    }

    /**
     * Sets cell state in next life cycle alive.
     */
    public function setAlive()
    {
        $this->newState=true;
    }

    /**
     * Executes next cell's life cycle.
     */
    public function cycle()
    {
        if ($this->newState)
        {
            $this->newState = false;
            $this->state = true;
        }
        else
        {
            $this->state = false;
        }
    }
}