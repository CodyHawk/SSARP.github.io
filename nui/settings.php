<?php
//MySQL Database
$servername = "localhost"; //URL to MySQL server. If running locally use localhost
$username = "root"; //Username used to login to server
$password = "CustomCad12"; //Password used to login to server
$dbname = "mdt"; //Name of the database with the provided SQL files

$conn = mysqli_connect($servername, $username, $password, $dbname); //DON'T TOUCH

//Website Url
$defaultURL = "//localhost/mdt"; //Url to where the MDT pages can be found. MUST INCLUDE "//" BEFORE URL ex: //test.com/mdt

//Top bar title
$topTitle = "CAD";

?>