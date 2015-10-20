/**
 * Created by Jens on 07.10.2015.
 */

// Timer needs a global variable for starting and stopping
var pitchTimer = false;

function randomField()
{
    for (var i=0; i<fieldHeight; i++)
    {
        for (var j=0; j<fieldWide; j++)
        {
            var cellId = i + '-' + j;
            if (Math.round(Math.random()))
            {
                document.getElementById(cellId).style.backgroundColor = "#000000";
                fieldArray[i][j] = "x";
            }
            else
            {
                document.getElementById(cellId).style.backgroundColor = "#ffffff";
                fieldArray[i][j] = "-";
            }
        }
    }
}

function clearField()
{
    for (var i=0; i<fieldHeight; i++)
    {
        for (var j=0; j<fieldWide; j++)
        {
            var cellId = i + '-' + j;
            document.getElementById(cellId).style.backgroundColor = "#ffffff";
            fieldArray[i][j] = "-";
        }
    }
}

function startGameOfLife()
{
    switch (gameKind)
    {
        case 'string': startInterval();
                       break;
        case 'gif':    var lifeCycles = prompt("Anzahl Lebenszyklen","10");
        //             displayOnWholePage(lifeCycles);  // using new page for result
                       displayImage(lifeCycles);        // using ajax
                       break;
        default:       alert('plugin not found:' + gameKind);
                       break;
    }
}

function startInterval()
{
    if (pitchTimer==false)
    {
        pitchTimer = setInterval(getNewFieldFromPHPserver, 300);
    }
}

function stopInterval()
{
    window.clearInterval(pitchTimer);
    pitchTimer = false;
}

function getNewFieldFromPHPserver()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if (xhttp.readyState == 4 && xhttp.status == 200)
        {
            var position = 0;
            for (var i=0; i<fieldHeight; i++)
            {
                for (var j = 0; j < fieldWide; j++)
                {
                    if (xhttp.responseText.charAt(position)=="x")
                    {
                        document.getElementById(i + "-" + j).style.backgroundColor = "black";
                        fieldArray [i] [j] = "x";
                    }
                    else
                    {
                        document.getElementById(i + "-" + j).style.backgroundColor = "white";
                        fieldArray [i] [j] = "-";
                    }
                    position++;
                }
            }
        }
    };
    xhttp.open("POST", "gameoflife.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("fieldHeight=" + fieldHeight + "&fieldWidth=" + fieldWide + "&string=" + stringFromArray());
}

function displayImage(lifeCycles)
{
    document.getElementById("pitchControls").style.visibility="hidden";

    var xhttp = new XMLHttpRequest();

    xhttp.open("POST", "gameoflife.php", true);
    xhttp.responseType = 'blob';

    xhttp.onload = function() {
        if (this.status == 200) {
            var blob = this.response;

            // delete content of div
            var myElem = document.getElementById('mainField');
            while (myElem.firstChild)
            {
                myElem.removeChild(myElem.firstChild);
            }

            // fill div with new <img>=gif
            var img = document.createElement('img');
            img.onload = function()
            {
                window.URL.revokeObjectURL(img.src); // Clean up after yourself.
            };
            img.src = window.URL.createObjectURL(blob);
            myElem.appendChild(img);
            document.getElementById("back").style.visibility="visible";
        }
    };
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("fieldHeight=" + fieldHeight + "&fieldWidth=" + fieldWide + "&string=" + stringFromArray() + "&gif="+ lifeCycles);

    /******************** OLD version (not working)   *********************************************************
    document.getElementById("pitchControls").style.visibility="hidden";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if (xhttp.readyState == 4 && xhttp.status == 200)
        {
            xhttp.overrideMimeType('text/plain; charset=x-user-defined');

            var responseText = xhttp.responseText;
            var responseTextLen = responseText.length;
            var binary = "";
            for (var i = 0; i < responseTextLen; ++i)
            {
                binary += String.fromCharCode(responseText.charCodeAt(i) & 255);
            }
            // delete content of div
            var myElem = document.getElementById('mainField');
            while (myElem.firstChild)
            {
                myElem.removeChild(myElem.firstChild);
            }
            // fill div with new content
            var myImage = document.createElement('img');

            myImage.setAttribute('src', 'data:image/gif;base64,'+window.btoa(binary))
            myElem.appendChild(myImage);
        }
    };
    //xhttp.responseType='blob';
    xhttp.open("POST", "gameoflife.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("fieldHeight=" + fieldHeight + "&fieldWidth=" + fieldWide + "&string=" + stringFromArray() + "&gif="+ lifeCycles);
    /*****************************************************************************************************************/
}

/**  call PHP via POST
 * this function does not use AJAX, instead it shows result on complete new page
 * @param lifeCycles
 */
function displayOnWholePage(lifeCycles)
{
    var dataName = [];
    var dataContent = [];

    dataName[0]     = "fieldHeight";
    dataContent [0] = fieldHeight;
    dataName[1]     = "fieldWidth";
    dataContent [1] = fieldWide;
    dataName[2]     = "string";
    dataContent [2] = stringFromArray();
    dataName[3]     = "gif";
    dataContent [3] = parseInt(lifeCycles);
    openFileWithPost("gameoflife.php", dataName, dataContent);
}

function openFileWithPost(url, name, content)
{
    var form = document.createElement("form");
    form.action = url;
    form.method = "POST";
    form.target = "_self";
    if (name)
    {
        for (var i= 0; i<name.length; i++)
        {
            var input = document.createElement("textarea");
            input.name = name[i];
            input.value = typeof content[i] === "object" ? JSON.stringify(content[i]) : content[i];
            form.appendChild(input);
        }
    }
    form.style.display = 'none';
    document.body.appendChild(form);
    form.submit();
}