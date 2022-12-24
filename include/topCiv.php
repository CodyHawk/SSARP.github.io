<?php session_start();
?>
<link rel="stylesheet" type="text/css" href="<?php echo $defaultURL;?>/include/top.css">
<link rel="stylesheet" type="text/css" href="<?php echo $defaultURL;?>/modal.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="<?php echo $defaultURL;?>/utilities/console.js"></script>

<div id="top">
    
    <div class="server"><?php echo $topTitle;?> - Server <?php echo $_SESSION['user_server'];?></div>
    
    <div id="clock"></div>
    
    <div id="userName"><?php echo $_SESSION['user_name']; ?></div>
    
    <div id="aop"></div>
    
    <div id="logout">
        <a href="../dashboard.php" ><img src="../img/home.png" style="position: relative" height="30" width="30"></a>
        <a href="../utilities/logout.php" ><img src="../img/logout.png" style="position: relative" height="30" width="30"></a>
    </div>
</div>

<div id="aopModal" class="modal">
    <center>
        <div class="modal-content">
        <span class="close">&times;</span>
        <h1>Please Select an Area of Patrol</h1>
        <select id='aopSelect'>
                        <?php
$query = "SELECT name FROM mdt_aop_names";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['name'] . "'>" . $row["name"] . "</option>";
    }
} else {
    echo "<option>No Results</option>";
}   
            ?>
        </select>
        <br>
        <button onclick='submitAOP();'>Submit</button>
        <p>Takes up to 5 seconds to update</p>

        </div>
    </center>
</div>


            <script>
    function currentTime() {
  var date = new Date(); /* creating object of Date class */
  var hour = date.getHours();
  var min = date.getMinutes();
  var sec = date.getSeconds();
  hour = updateTime(hour);
  min = updateTime(min);
  sec = updateTime(sec);
  document.getElementById("clock").innerText = hour + " : " + min + " : " + sec; /* adding time to the div */
    var t = setTimeout(function(){ currentTime() }, 1000); /* setting timer */
}

function updateTime(k) {
  if (k < 10) {
    return "0" + k;
  }
  else {
    return k;
  }
}
currentTime(); /* calling currentTime() function to initiate the process */
    </script>
    <script>
        getAOP();
function getAOP() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getAOP'
    },
    function(response){
        if (response === 'logout') {
            window.location.replace("<?php echo $defaultURL ?>");
        }
      //console.log(response);
      document.getElementById("aop").innerHTML = response;
    });
    setTimeout(getAOP, 5000);
        }
    </script>
    <script>
function submitAOP() {
    var selected = document.getElementById("aopSelect").options[aopSelect.selectedIndex].value;
    
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'submitAOP',
      aop: selected
    },
    function(response){
        aop.style.display = "none";
        if (response === "not today") {
            window.location.replace("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
        }
    });
}
    </script>
    <script>
// Get the modal
var aop = document.getElementById('aopModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

function aopOpen() {
    aop.style.display = 'block';
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
aop.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
if (event.target == aop) {
aop.style.display = "none";
}
}
    </script>