<?php

//Including Database configuration file.
 
include "dbcon.php";
 
//Getting value of "search" variable from "script.js".
 
if (isset($_POST['name'])) {
 
//Search box value assigning to $Name variable.
 
   $Name = $_POST['name'];
   $serverId = $_POST['serverId'];
 
//Search query.
 
   $Query = "SELECT * FROM server WHERE aop='$Name' AND id='$serverId'";
 
//Query execution
 
   $ExecQuery = MySQLi_query($con, $Query);
 
//Creating unordered list to display result.
   echo '';
   //Fetching result from database.
 
   while ($Result = MySQLi_fetch_array($ExecQuery)) {
$aop = $Result['aop'];
       if ($Result['aop'] === $Name) {
echo "selected, $aop";
       }
}}
