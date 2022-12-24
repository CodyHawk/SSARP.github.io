<?php 
include('dbcon.php');
include('session.php'); 

$result=mysqli_query($con, "select * from users where user_id='$session_id'")or die('Error In Session');
$row=mysqli_fetch_array($result);

 ?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
* {
    box-sizing: border-box;
}

A {
    text-decoration: none;
}

.departmenmts {
postion:relative;
float:left;
margin: 25px;
margin-top: 0px;
}

/* Clearfix (clear floats) */
.row {
    content: "";
    position: relative;
}

up{
  font-size: 25px;
  font-weight: 640;
  color: #e7e7e7;
  font-family:'sans-serif','helvetica';
  margin: 10px;
  margin-top: 0px;
}
</style>
<body>
<div class="welcome-wrapper"> 
    <center><h3>Welcome: <?php echo $row['name']; ?> </h3></center>
        <center><up>Please select a department</up></center>
	 <div class="reminder">
    <p><a href="logout.php">Log out</a></p>
  </div>
        <center><div style='postion:relative; display: inline-block;'>
            <div class='departmenmts'>
      <a href="departments/civ/civ-list.php">
          <p><img src="img/civIcon.png" height="150" width="150" alt="Dispatch"></p>
          <p><up>Civilian</up></p>
      </a>
            </div>
            <div class='departmenmts'>
      <a href="department/leo.php">
          <p><img src="img/temp.png" height="150" width="150" alt="Dispatch"></p>
          <p><up>Law Enforcement</up></p>
      </a>
            </div>
            <div class='departmenmts'>
      <a href="department/Dispatch.php">
          <p><img src="img/dispatchIcon.png" height="150" width="150" alt="Dispatch"></p>
          <p><up>Dispatch</up></p>
      </a>
            </div>
        </div></center>
  </div>        

  

</body>
</html>