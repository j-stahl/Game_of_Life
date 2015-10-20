<html>
<body bgcolor='#CCCC88'>
<form method='get' action='<?=$_SERVER['PHP_SELF'] ?>'>
	Please, write your name: <input type='text' name='nome'><br />
	<input type='checkbox' name='transparent' value='1'> Transparent background (NOT RECOMMENDED)!<br />
	<input type='submit' value='Generate new animation'>
</form>
<hr size='1' />
<?
require_once "dGifAnimator.inc.php";

$nome = (isset($_GET['nome']) && ($tmp=trim($_GET['nome'])))?
	$tmp:
	"Alexandre Tedeschi (d)";

/** 1. Build all the frames that will be part of the animation (30 frames) **/
if(!is_dir("tempFolder"))
	mkdir("tempFolder");

$genStartTime = microtime(true);
for($x = 0; $x <= 30; $x++){
	$im   = imageCreateTrueColor (400, 200);
	$imbg = imageCreateFromPNG ('tile.png'); // Alpha-transparent PNG
	
	$black = imagecolorallocate($im,   0,   0,   0);
	$white = imagecolorallocate($im, 255, 255, 255);
	$red   = imagecolorallocate($im, 255,   0,   0);
	$green = imagecolorallocate($im,   0, 128,   0);
	
	imagestring ($im, 3, 60+$x*4, 40, $nome, $green);
	imagestring ($im, 3, 59+$x*4, 39, $nome, $white);
	
	imageline  ($im, 0, 20+$x*8, 400, 40+$x*-2,      $green);
	imagestring($im, 3, 30,       50, microtime(),   $white);
	imagestring($im, 3, 35,       60, "X: $x",       $white);
	imagestring($im, 3, 105,      60, date("H:i:s"), $red);
   
	imagesettile ($im, $imbg);
	imagefilledrectangle ($im, 0, 0, 400, 200, IMG_COLOR_TILED);
	
	$randFilename = "tempFolder/".uniqid("$x-").".gif";
	$generated[] = $randFilename;
	imagegif($im, $randFilename);
}
$genEndTime = microtime(true);

$startTime = microtime(true);
/** Instantiate the class to join all the frames into one single GIF **/
$gif = new dGifAnimator();
$gif->setLoop(0);                         # Loop forever
$gif->setDefaultConfig('delay_ms', '10'); # Delay: 10ms
if(isset($_GET['transparent']))
	$gif->setDefaultConfig('transparent_color', 0);

/** Adds all the frames to the animation **/
for($x = 0; $x < sizeof($generated); $x++){
	if($x == 25)
		$gif->setFrameConfig('delay_ms', '100'); // Make frame 25 stays visible for a little longer
	$gif->addFile($generated[$x]);
}
$gif->build("sample.gif");
$endTime = microtime(true);

/** Exclude all frames used to build the final animation **/
for($x = 0; $x < sizeof($generated); $x++)
	unlink($generated[$x]);

echo "<img src='sample.gif?".uniqid()."'><br />";
echo "<b>Time taken to GENERATE the frames:</b> ".number_format($genEndTime-$genStartTime, 4)." seconds.<br />";
echo "<b>Time taken to JOIN the animation:</b> ".number_format($endTime-$startTime, 5) . " seconds.<br />";
echo "Just for comparisson, a single query to the database wouldn't take longer than 0.003 seconds.";

