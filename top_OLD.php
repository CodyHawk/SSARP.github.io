<?php 
include($_SERVER['DOCUMENT_ROOT'].'/mdt/settings.php');
include($_SERVER['DOCUMENT_ROOT'].'/mdt/dbcon.php');
include($_SERVER['DOCUMENT_ROOT'].'/mdt/session.php');

$result=mysqli_query($con, "select * from users where user_id='$session_id'")or die('Error In Session');
$row=mysqli_fetch_array($result);
/*
 * 
 * 
 * 
if(
 *
 * 
 * 
 *  empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
str
 * tolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) != "xmlhttprequest"){
   

 * 
 * 
 * 
 * 
 *  die("ERROR.... Your not supposed to be here.");
}
 */
?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="aop.js"></script>
</head>
<style>
    #top {
        display: table;
        background-color: #343434;
        position: fixed;
        width: 100%;
        height: 7%;
        top:0;
        left:0;
    }

    t {
        font-family: 'Roboto', sans-serif;
        color: white;
        font-size: 1.5em;
    }

    #top select {
        font-family: 'Roboto', sans-serif;
        color: white;
        font-size:  1.5em;
        background: none;
        background-color: #343434;
        border: none;
        position: absolute;
        margin-left: 5px;
    }

    #top select:focus {
        outline: none !important;
        border: none !important;
    }

    .server {
        display: table-cell;
        vertical-align: middle;
        padding-left: 30px;
        position: relative;
    }

    #clockDiv {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 25%;
        transform: translate(-50%, -50%);
    }

    #userNameDiv {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:  1.5em;
        font-weight: 200;
    }

    #aopDiv {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 75%;
        transform: translate(-50%, -50%);
        display: inline-block;
    }

    #aop {
        display: inline-block;
        text-align: right;
        float: right;
    }
    
    #logoutDiv {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 98%;
        transform: translate(-50%, -50%);
    }

</style>

<div class="server">
            <t><?php echo $topTitle?> - Server <?php echo $serverNumber ?></t>
            </div>
            <div id="clockDiv">
                <t id="clock"></t>
            </div>
            <div id="userNameDiv">
                <t id="userName"><?php echo $row['name'];?></t>
            </div>
            <div id="aopDiv">
                <t style="float: left; clear: left; width: 100%;">AOP:</t>
                <select id="aop">
                </select>
            </div>
            <div id="logoutDiv">
                <a href="../../logout.php" ><img src="../../img/logout.png" alt="Smiley face" style="position: relative" height="30" width="30"></a>
            </div>

<div id="check" style="display: none;"></div>
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
        loadXMLDoc();
        setInterval(function(){loadXMLDoc();}, 3000);
function loadXMLDoc() {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      myFunction(this);
    }
  };
  xmlhttp.open("GET", "aop.xml" , true);
  xmlhttp.send();
}

function myFunction(xml) {
  var x, i, xmlDoc, table;
  xmlDoc = xml.responseXML;
  //table = "<tr><th>Artist</th><th>Title</th></tr>";
  x = xmlDoc.getElementsByTagName("AREA")
  for (i = 0; i < x.length; i++) {
      aopCheck(x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue, document.getElementById("server").options[document.getElementById("server").selectedIndex].value);
      //console.log(document.getElementById("aop").options[document.getElementById("aop").selectedIndex].value);
      var e = document.getElementById("aop").options;
      if (typeof(e) != 'undefined' && e != null)
{
  // exists.
          if (document.getElementById("aop").options[document.getElementById("aop").selectedIndex].value === document.getElementById("check").value) {
    } else {
    table += "<option value='" + 
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "' name='" + 
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "'>" +
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "</option>";
  }
  } else {
          table += "<option value='" + 
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "' name='" + 
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "'>" +
    x[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue +
    "</option>";
  }
  }
  document.getElementById("aop").innerHTML = table;
}
</script>

<script>
    console.log(document.getElementById("server").options[document.getElementById("server").selectedIndex].value);
    
    
             $(document).on('change','#server',function(){
               //$('#serverForm').submit();
          });
          
          function change() {
              $('#serverForm').submit();
          };
    </script>