<?php
include('../settings.php');
include "../include/topCiv.php";

if (empty($_SESSION['user_loggedIn'])) {
    header("Location:$defaultURL");
    die("You must login first");
}

if ($_SESSION['user_admin'] == 0) {
    header("Location:https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit();
}
?>
<html>
<body bgcolor="#fff">
<title>Admin Panel</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $defaultURL;?>/modal.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">

<div id="sidebar">
    <button onclick="openScreen('#server')">Server Controls</button>
    <button style="margin-top: 75px;" onclick="openScreen('#user')">User Controls</button>
</div>

<div id="server" class="screen">
    <div class="serverStatus">
        <img id="loading" src="<?php echo $defaultURL; ?>/img/loading.gif">
    </div>
</div>

<div id="viewServer" class="screen">
    <h1 id="serverHeader">Server - </h1>
    <button id="refresh" class="smallButton" style="position:relative;" onclick="">Refresh</button>
    <table id="playerTable"></table>
    <div id="options">
        <h2 style="margin-left: 25px;" id="uptime">Uptime: 00h 10m</h2>
        <h2 style="margin-left: 25px;" id="players">Players: 22/32</h2>
    </div>
    <div id="options2">
        <h2>Announce to Server</h2>
        <textarea id="announcement"></textarea>
        <button class="smallButton" style="margin-left:0;" id="aSend" onclick="announcementSend()">Send</button>
    </div>
    <div id="options3">
        <h2>Restart Server</h2>
        <button class="smallButton" style="margin-left:0;" onclick="restartConfirm()">Restart</button>
    </div>
</div>

<div id="user" class="screen">
    <h1 style="margin-left: 25px;">MDT User Controls</h1>
    <table id="userTable"></table>
    <div id="Code">
    <h2 style="margin-left: 25px;">Generate and Remove Login Code</h2>
    <button onclick="genLogin()" class="mediumButton">Generate</button>
    <button onclick="removeLogin()" class="mediumButton">Remove All</button>
    <br>
    <textarea id="loginCode" disabled></textarea>
    </div>
</div>

<div id="banModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Please enter a ban reason</h1>
            <textarea id="banReason"></textarea>
            <br>
            <button id="yes">Ban</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<div id="kickModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Please enter a kick reason</h1>
            <textarea id="kickReason"></textarea>
            <br>
            <button id="yes">Kick</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<div id="restartModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Are you sure you want to restart server </h1>
            <button id="yes">Restart</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<div id="suspendModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Are you sure you want to suspend this user?</h1>
            <button id="yes">Yes</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<script>
    function openScreen(open) {
        $(".screen").hide();
        $(open).show();
    }
    openScreen('#server')
</script>
<script>
loadServers();
function loadServers() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getServers'
    },
    function(response){
        $("#server .serverStatus").html(response);
    });
}

function selectServer(id) {
openScreen('#viewServer');
$("#viewServer #serverHeader").html("Server " + id + " Control Pannel");
$("#viewServer #refresh").attr('onclick',"selectServer(" + id + ")");
$("#viewServer #aSend").attr('onclick',"announcementSend(" + id + ")");
$("#restartModal h1").text("Are you sure you want to restart server " + id + "?");
$("#restartModal #yes").attr('onclick',"rcon(" + id + ",'restart_server'); closeModal();");
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getServerPlayerTable',
      id: id
    },
    function(response){
        $("#playerTable").html(response);
    });

$.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getServerInfo',
      id: id
    },
    function(response){
        response = JSON.parse(response);
        $("#uptime").text("Uptime: " + response[0]);
        $("#players").text("Players: " + response[1]);
    });
}

function ban(server,id) {
    var reason = $("#banReason").val();
    rcon(server, "ban " + id + " " + reason);
}

function kick(server,id) {
    var reason = $("#kickReason").val();
    rcon(server, "kick " + id + " " + reason);
}

function rcon(server, command) {
    closeModal();
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'rcon',
      server: server,
      command: command
    },
    function(response){
            selectServer(server);
			console.log(response)
    });
}

function announcementSend(server) {
    var announcement = $("#announcement").val();
    rcon(server, "announce " + announcement)
    $("#announcement").val("");
}
</script>
<script>
function genLogin() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'genLogin'
    },
    function(response){
        $("#loginCode").val(response);
    });
}

function removeLogin() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'removeLogin'
    },
    function(response){
        $("#loginCode").val(response);
    });
}

loadUserTable();
function loadUserTable() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getUsersTable'
    },
    function(response){
        $("#userTable").html(response);
        console.log(response);
    });
    }
    
function userSuspend(id, suspend) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'userSuspend',
      id: id,
      suspend: suspend
    },
    function(response){
        closeModal();
        loadUserTable();
    });
}
</script>
<script>
function banConfirm(server, id) {
    document.getElementById('banModal').style.display = 'block';
    $("#banModal button#yes").attr('onclick',"ban(" + server + "," + id + ")");
}

function kickConfirm(server, id) {
    document.getElementById('kickModal').style.display = 'block';
    $("#kickModal button#yes").attr('onclick',"kick(" + server + "," + id + ")");
}

function restartConfirm(server, id) {
    document.getElementById('restartModal').style.display = 'block';
    $("#kickModal button#yes").attr('onclick',"kick(" + server + "," + id + ")");
}

function userSuspendConfirm(id) {
    document.getElementById('suspendModal').style.display = 'block';
    $("#suspendModal button#yes").attr('onclick',"userSuspend(" + id + ",1)");
}

function closeModal() {
$(".modal").hide();
}

window.onclick = function(event) {
if ($(event.target).hasClass("modal")) {
$(".modal").hide();
}};
</script>
</body>
</html>