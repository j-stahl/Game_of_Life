/**
 * Created by Jens on 07.10.2015.
 */

// Field-creation and cell-control
var fieldWide;
var fieldHeight;
var fieldArray = [];
var gameKind='';

function displayEmptyField()
{
    fieldWide = document.initializeForm.width.value;
    fieldHeight = document.initializeForm.elevation.value;
    var newField = "<table class=\"pitch\">\n";
    for (var i=0; i<fieldHeight; i++)
    {
        fieldArray[i] = [];
        newField += "<tr>\n";
        for (var j=0; j<fieldWide; j++)
        {
            fieldArray [i] [j] = "-";
            newField += "<td id=\"" + i + '-' + j + "\" onclick='changeCellState(this)'></td>";
        }
        newField += "</tr>\n";
    }
    newField += "</table>";

    // gameKind = document.initializeForm.gameKind.value;  not compatible with IE
    var radioButtons = document.getElementsByName('gameKind');
    for (var i = 0; i < radioButtons.length; i++)
    {
        if (radioButtons[i].checked) gameKind = radioButtons[i].value;
    }

    document.getElementById("mainField").innerHTML=newField;
    document.getElementById("pitchControls").style.visibility="visible";
}

function displayLastField()
{
    var newField = "<table class=\"pitch\">\n";
    for (var i=0; i<fieldHeight; i++)
    {
        newField += "<tr>\n";
        for (var j=0; j<fieldWide; j++)
        {
            if (fieldArray [i] [j] == "x")
            newField += "<td id=\"" + i + '-' + j + "\" onclick='changeCellState(this)' " +
                "style='background-color: black;'></td>";
            else newField += "<td id=\"" + i + '-' + j + "\" onclick='changeCellState(this)' " +
                "style='background-color: white;'></td>";
        }
        newField += "</tr>\n";
    }
    newField += "</table>";

    document.getElementById("mainField").innerHTML=newField;
    document.getElementById("pitchControls").style.visibility="visible";
    document.getElementById("back").style.visibility="hidden";
}

function changeCellState(_actualCell)
{
    var coordinate = _actualCell.getAttribute("id");
    coordinate = coordinate.split("-");

    if (fieldArray[coordinate[0]][coordinate[1]] == "-")
    {
        _actualCell.style.backgroundColor = "#000000";
        fieldArray[coordinate[0]][coordinate[1]] = "x";
    }
    else
    {
        _actualCell.style.backgroundColor = "#ffffff";
        fieldArray[coordinate[0]][coordinate[1]] = "-";
    }
}

function stringFromArray()
{
    arrayString = "";
    for (var i=0; i<fieldHeight; i++)
    {
        for (var j=0; j<fieldWide; j++)
        {
            arrayString += fieldArray [i] [j];
        }
    }
    return arrayString;
}