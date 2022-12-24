<?php include('settings.php'); 
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="link.js"></script>
        <style> 
            BT {
                font-family: 'Roboto', sans-serif;
                color: black;
                font-size: 5em;
                text-align: center;
            }
            l {
                font-family: 'Roboto', sans-serif;
                font-size: 2em;
                color: black;
                text-align: center;
                position: relative;
            }
            #codeInput {
                font-family: 'Roboto', sans-serif;
                font-size: 100px;
                text-align: center;
                color: black;
                width: 500px;
            }
            #Success {
                position: initial;
                float: top;
                top: 0px;
            }
            #image {
                height: 250px;
                width: 250px;
                margin: 75px;
                border: 5px solid black;
            }
            p {
                margin-top: 50px
            }
        </style> 
    </head>
    <body>
        <div id="Success" hidden>
            <center>
                <BT>Welcome, </BT>
                <BT id="name"></BT>
                <br>
                <img id="image" src="">
                <br>
                <p><l>Your account has been successfully linked!</l></p>
                <p><l>You may now continue to FiveM</l></p>
            </center>
        </div>
        
        
<div id="code" >
    <Center>
        <BT>Please follow the instructions below</BT>
        <br>
        <p style="margin-top: 100px"><l>1. Enter "/link" into the FiveM Chat </l></p>
        <p><l>2. Enter the code on the screen into the box below</l></p>
    <form>
        <input id="codeInput" required type="text" maxlength="4">
    </form>
    </center>
</div>
    </body>
</html>
