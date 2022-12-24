<?php
$con = mysqli_connect("localhost","root","CustomCad12","mdt");

$servername = "localhost";
$username = "root";
$password = "CustomCad12";
$dbname = "mdt";

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
$con = MySQLi_connect(
 
   "localhost", //Server host name.
 
   "root", //Database username.
 
   "CustomCad12", //Database password.
 
   "mdt" //Database name or anything you would like to call it.
 
);
?>