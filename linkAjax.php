<?php
$api_key = "5983858A0DEB07846F46D2D2386E89BA";

//Including Database configuration file.
 
include('settings.php');
session_start();
 
//Getting value of "search" variable from "script.js".
 
if (isset($_POST['code'])) {
 
//Search box value assigning to $Name variable.
 
   $Name = $_POST['code'];
   $id = $_SESSION['user_id'];
 
//Search query.
 
   $Query = "SELECT * FROM mdt_players WHERE code='$Name'";
 
//Query execution
 
   $ExecQuery = MySQLi_query($conn, $Query);
 
//Creating unordered list to display result.
   echo '';
   //Fetching result from database.
 
   while ($Result = MySQLi_fetch_array($ExecQuery)) {

echo steamInfo($Result["steam"],$id);
 
}}

function steamInfo($SteamID, $UserID) {
        $SteamAPI_KEY = "5983858A0DEB07846F46D2D2386E89BA";
        //$SteamID = "76561198164179083";
	
	$Name = "Player Name";
	$Avatar  = 'img/noavatar.jpg';
	/*
	if (isset($_GET['steamid'])) 
	{
            */
		$SteamAPI = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $SteamAPI_KEY . '&steamids=' . $SteamID;
		
		$SteamAPI_Returned = file_get_contents($SteamAPI);
		
		$steamAPI = json_decode($SteamAPI_Returned, true);
		
		if (isset($steamAPI['response']['players'][0]['personaname']))
		{
			$Name = $steamAPI['response']['players'][0]['personaname'];
		}
		
		if (isset($steamAPI['response']['players'][0]['avatarfull']))
		{
			$Avatar = $steamAPI['response']['players'][0]['avatarfull'];
		}

                //add steam id to database
include('settings.php'); 
                // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "UPDATE mdt_users SET steam='$SteamID' WHERE user_id='$UserID'";

if ($conn->query($sql) === TRUE) {
    //echo "Record updated successfully $sql";
} else {
    //echo "$SteamID Error updating record: " . $conn->error;
}

$conn->close();

                return 'Success,' .$Name . ',' . $Avatar;
}
?>