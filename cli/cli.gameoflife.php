<?php
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 06.10.2015
 * Time: 16:16
 */

include_once 'gamefield.php';

class CliGameOfLife implements GameOfLifeInterface
{
    private $dataString = "";
    private $lineLength = 0;
    private $numLines = 0;
    public  $fileName = '';
    public function prepareReceivedData()
    {
        $gameFile = fopen($this->fileName, "r") or die("Unable to open file!");
        while (!feof($gameFile))
        {
            $this->numLines++;
            $fileLine = fgets($gameFile);
            $fileLine = str_replace("\n", "", $fileLine);
            $fileLine = str_replace("\r", "", $fileLine);
            if ($this->lineLength==0 || strlen($fileLine) == $this->lineLength)
            {
                $this->lineLength = strlen($fileLine);
            }
            else
            {
                fclose($gameFile);
                die("File-content inconsistent ". $this->lineLength ." ". strlen($fileLine));
            }

            $this->dataString .= $fileLine;
            echo $fileLine."\n";
        }
        fclose($gameFile);
    }

    public function calculateNextLifeCycle()
    {
        $field1 = new \gameField\GameField($this->lineLength, $this->numLines);
        $field1->buildStartField($this->dataString);
        // calculateNextCycle
        for ($i=0; $i<$field1->height(); $i++)
        {
            for ($j=0; $j<$field1->width(); $j++)
            {
                $numAliveNeighbors = $field1->numAliveNeighbors($i, $j);
                if ($numAliveNeighbors == 3 ||
                    $numAliveNeighbors == 2 &&
                    $field1->isAlive($i, $j))
                    $field1->setAlive($i, $j);
            }
        }
        $field1->executeNextCycle();
        // end calculateNextCycle

        $this->dataString=$field1->resultString();
    }

    public function displayResult()
    {
        sleep(1);
        for ($i=0; $i<20; $i++)
        {
            echo "\n";
        }

        for ($i=0; $i<$this->height; $i++)
        {
            $arrayToString="";
            for ($j=0; $j<$this->width; $j++)
            {
                if ($this->gameField->isAlive($i, $j)) $arrayToString.="x";
                else                                   $arrayToString.="-";
            }
            echo $arrayToString."\n";
        }

        for ($i=0; $i<16; $i++)
        {
            echo "\n";
        }
    }
}
