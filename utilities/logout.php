<?php
session_start();
include('../settings.php');
$user_id = $_SESSION['user_id'];
mysqli_query($conn, "DELETE FROM mdt_active_users WHERE user_id='$user_id'");
session_unset();
session_destroy();
header('location:../index.php');
?>