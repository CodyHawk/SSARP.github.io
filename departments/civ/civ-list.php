<?php 
include($_SERVER['DOCUMENT_ROOT'].'/mdt/settings.php');
include($_SERVER['DOCUMENT_ROOT'].'/mdt/dbcon.php');
include($_SERVER['DOCUMENT_ROOT'].'/mdt/session.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Civilian Selection</title>
        <link rel="stylesheet" type="text/css" href="modal.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script type="text/javascript" src="aop.js"></script>
        
                <link rel="stylesheet/less" type="text/css" href="styles.less" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script>
        <script>
$(document).ready(function() {
            $("#top").load("top.php");
                });
            </script>
    </head>
    <body>
        <!-- Top panel -->
        <div id="top">
        </div>
        
        <!-- List -->
        <div id="list">
                <table class="fixed_headers">
            <thead>
        <tr>
    <th>List of Characters</th>
        </tr>
        <tr>
    <th><a onclick="add.style.display = 'block';"  style="cursor: pointer; cursor: hand;">Add New Character</a></th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT first, last, id FROM characters WHERE ownerID='$session_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><a href='civ#" . $row["id"] . ".php'>" . $row["first"]. " " . $row["last"] . "</a></tr></td>";
    }
} else {
    echo "<tr><td style='color:black;'>No Results</tr></td>";
}
$conn->close();
?>
            </tbody>
  </table>
    </div>
        
            <div id="addModal" class="modal">
        <center>
      <div class="modal-content">
    <span class="close">&times;</span>
            <style>
                    #addDiv label {
                font-family: 'Roboto', sans-serif;
                font-size: 2em;
                color: black;
                text-align: left;
                position: relative;
                float: left;
            }
            
            input {
                margin-bottom: 10px;
            }
            #addDiv input[type=text], #addDiv select, #addDiv input[type=date] {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            
           #addDiv input[type=submit] {
                font-family: 'Roboto', sans-serif;
                width: 100%;
                background-color: #333;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
    </style>

        <div id='addDiv'>
        <form id='addForm' action="#" method="post">
            <label for="first" >First Name</label>
            <input type="text" name="first" required>

            <label for="last" >Last Name</label>
            <input type="text" name="last" required>

            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" required>

            <label for="gender">Gender</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="address">Address</label>
            <input type="text" name="address">

            <label for="lic">Driver's License</label>
            <select name="lic" required>
                <option value="Licensed">Licensed</option>
                <option value="">None</option>
                <option value="Expired">Expired</option>
                <option value="Suspended">Suspended</option>
                <option value="Revoked">Revoked</option>
            </select>
            
            <input type="submit" name="Submit" value="Submit">
        </form>
        </div>
        
        <?php

        if (isset($_POST['Submit'])) {
    // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $first = $_POST['first'];
        $last = $_POST['last'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $lic = $_POST['lic'];
        
        $sql = "INSERT INTO characters (ownerID, first, last, dob, address, gender, lic)
                VALUES ('$session_id', '$first', '$last', '$dob', '$address', '$gender', '$lic')";

        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        }
        ?>

        
  </div>
        </center>
    </div>
        
<script>
    // Get the modal
var add = document.getElementById('addModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

//get url

var url_string = window.location.href;
var url = new URL(url_string);
var linkUrl = url.searchParams.get("link");
var id = url.searchParams.get("id");

//When #link open modal
if (linkUrl === "1") {
add.style.display = 'block';
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  add.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == add) {
    add.style.display = "none";
  }
}
    </script>
    </body>
</html>
