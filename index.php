<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game of Life</title>
    <script src="gamefield.js"></script>
    <script src="controls.js"></script>
    <link rel="stylesheet" type="text/css" href="gamefield.css">
</head>
<body>
<h1>Game of Life</h1>
<div id="mainField">
    <form name="initializeForm">
        <table>
            <tr>
                <td>Breite:</td>
                <td><input type="text" name="width"/></td>
            </tr>
            <tr>
                <td>H&ouml;he:</td>
                <td><input type="text" name="elevation" /></td>
            </tr>
        </table>
        <fieldset>
            <legend>Darstellung:</legend>
            <?php
            //------------------------------------
            // find and display plugins
            // return plugins
            $plugins = glob("*.gameoflife.php");
            $numPlugins = 0;
            foreach($plugins as $plugin)
            {
                if (++$numPlugins>1) echo "\n<br />";
                // get 2nd comment line out of php File
                $pluginFile = fopen($plugin, "r") or die("Unable to open file!");
                if (!feof($pluginFile)) $comment = fgets($pluginFile);
                if (!feof($pluginFile)) $comment = fgets($pluginFile);
                $comment = str_replace("// ", "", $comment);
                $plugin = str_replace(".gameoflife.php", "", $plugin);

                echo '<input type="radio" name="gameKind" value="'.$plugin.'"';
                if ($numPlugins==1) echo " checked";
                echo ">".$comment;
            }
            ?>
        </fieldset>
        <br />

        <input type="button" value="OK" onclick="displayEmptyField()"/>
    </form>
</div>
<br />
<div id="pitchControls" style="visibility: hidden;">
    <input type="button" value="Generiere Zufallsfeld" onclick="randomField()"/>
    <input type="button" value="leeren"                onclick="clearField()"/>
    <br /> <br />
    <!--   Max Intervall: <input type="text" value="0" /></br>-->
    <input type="button" value="Starte Game of Life"   onclick="startGameOfLife()"/>
    <input type="button" value="Stop!"                 onclick="stopInterval()"/>
</div>
<div id="back" style="visibility: hidden;">
    <input type="button" value="zur&uuml;ck"           onclick="displayLastField()"/>
    <input type="button" value="Neustart"              onclick="window.open('index.php','_self')"/>
</div>
</body>
</html>