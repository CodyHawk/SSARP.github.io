<?php include('settings.php'); 
session_start();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="modal.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="<?php echo $defaultURL;?>/utilities/console.js"></script>
<title>Login</title>
</head>
<body>
    <div id="linkModal" class="modal">
        <center>
      <div class="modal-content">

        <?php include('link.php'); ?>
  </div>
        </center>
    </div>
<div class="form-wrapper">
  <form id="form" method="post" action="utilities/login.php">
    <h3>Login</h3>
	
    <div class="form-item">
		<input type="text" name="email" required="required" placeholder="Email" autofocus required></input>
    </div>
    
    <div class="form-item">
		<input type="password" name="pass" required="required" placeholder="Password" required></input>
    </div>
    
    <div class="form-item">
        <select id="server" name="server">
            <option hidden>Server</option>
            <?php
   $query = "SELECT id,ip,port FROM mdt_server";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $content = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/info.json"), true);
            if ($content) {echo "<option value='" . $row['id'] . "'>" . $row["id"] . "</option>";}
        }
        }
$conn->close();
            ?>
        </select>
    </div>
    
    <div class="button-panel">
        <input type="submit" class="button" id="sub" title="Log In" name="login" value="Login"></input>
    </div>
    <center><alert><?php  if(isset($_SESSION['loginError'])) {echo $_SESSION['loginError']; unset($_SESSION['loginError']);}?></alert></center>
    <center><a href='create.php'><h2>Create Account</h2></a></center>
  </form>
    <script>
        $(document).ready(function () {
            window.addEventListener('message', function (event) {
                var item = event.data;
                if (item.jo) {
                    alert('jo');
                    console.log('mama');
                }
                });
        });
    </script>
    <script>
    // Get the modal
var link = document.getElementById('linkModal');

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
link.style.display = 'block';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == link) {
    link.style.display = "none";
  }
}
    </script>
</div>
</body>
</html>