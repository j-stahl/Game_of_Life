<?php
// GIF-Image
/**
 *
 * @file
 * @version 1.0
 * @copyright 2015 CN-Consult GmbH
 * @author Jens Stahl <jens.stahl@cn-consult.eu>
 */

include_once 'gamefield.php';
include_once 'gameoflife.interface.php';
include_once 'gif/GIFEncoder.class.php';

class GifGameOfLife implements GameOfLifeInterface
{
    private $width = 0;
    private $height = 0;
    /** @var \gameField\GameField */
    private $gameField;

    /**
     * Creates new GifGameOfLife object.
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
     * Displays the finally calculated gif with all life cycles.
     *
     * @param int $_numGifFrames Amount of life cycles the gif returned should consists (standard=100).
     */
    public function displayResult($_numGifFrames=100)
    {
        $frames = [];
        $delayTime = [];

        for ($x=0; $x<$_numGifFrames; $x++)
        {
            $im = @ImageCreateTrueColor ($this->width * 10, $this->height *10) or die ("Kann keinen neuen GD-Bild-Stream erzeugen");
            $delayTime[$x] = 5;
            $background_color = ImageColorAllocate ($im, 255, 255, 255);
            imagefill($im, 0, 0, $background_color);
            $black = ImageColorAllocate ($im, 0, 0, 0);

            for ($i=0; $i<$this->height; $i++)
            {
                for ($j=0; $j<$this->width; $j++)
                {
                    if ($this->gameField->isAlive($i, $j))
                    {
                        imagefilledrectangle($im, ($j*10), ($i*10), ($j*10+10), ($i*10+10), $black);
                    }
                }
            }
            ob_start();
            imagegif($im);
            $frames[$x] = ob_get_contents();
            ob_end_clean();
            imagedestroy($im);
            $this->calculateNextLifeCycle();
        }

    /*  usage external GIF-Encoder:
        image_stream = new GIFEncoder	(
                                URL or Binary data	'Sources'
                                int					'Delay times'
                                int					'Animation loops'
                                int					'Disposal'
                                int					'Transparent red, green, blue colors'
                                int					'Source type');*/
        $gif = new GIFEncoder($frames, $delayTime, 0, 2, 0, 0, 0, "bin");

    /*  Possibles outputs:
        ==================
        Output as GIF for browsers :                   -> Header ( 'Content-type:image/gif' );
        Output as GIF for browsers with filename:      -> Header ( 'Content-disposition:Attachment;filename=myanimation.gif');
        Output as file to store into a specified file: -> FWrite ( FOpen ( "myanimation.gif", "wb" ), $gif->GetAnimation ( ) );*/
        Header ( 'Content-type:image/gif' );
        echo	$gif->GetAnimation ( );
    }
}
