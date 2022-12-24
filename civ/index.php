<?php
include('../settings.php');
include "../include/topCiv.php";

if (empty($_SESSION['user_loggedIn'])) {
    header("Location:$defaultURL");
    die("You must login first");
}
?>
<html>
<body bgcolor="#fff">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Civilian</title>

<div id="characterSelection">
    <img id="characterPicture" src="<?php echo $defaultURL; ?>/img/profile.png">
    
    <form method="post" id='civForm' action="<?php echo $defaultURL; ?>/utilities/api.php">
        
        <div id="row1">
        <select id="nameSelect" name="nameSelect">
            <option value="add">Add Character</option>
            <?php
            $user_id = $_SESSION['user_id'];
            $query = "SELECT id, first, last FROM mdt_characters WHERE ownerID='$user_id'";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $first = $row['first'];
                    $last = $row['last'];
                    $id = $row['id'];
                    echo "<option value='$id'>$first $last</option>";
                }
            }
            ?>
        </select>
        
        <input style="margin-top:25px;" type="text" id="first" name="first"  placeholder="First Name">
        
        <input style="margin-top:25px;" type="text" id="last" name="last" placeholder="Last Name">
        </div>
        <div id="row2">
            
            <input type="date" id="dob" name="dob">
            
            <select id="gender" name="gender" style="margin-top:25px;">
                <option disabled selected>Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
            
            <input style="margin-top:25px;" type="text" id="address" name="address" placeholder="Address">
        </div>
        
        <div id="row3">
            <select id="lic" name="lic">
                <option disabled selected>Driver License</option>
                <option>None</option>
                <option>Valid</option>
                <option>Expired</option>
                <option>Suspended</option>
                <option>Revoked</option>
            </select>
            
            <select id="weapon" name="weapon" style="margin-top:25px;">
                <option disabled selected>Weapon License</option>
                <option>None</option>
                <option>Valid</option>
                <option>Revoked</option>
            </select>
            
            <input style="margin-top:25px;" type="submit" id="submit" name="submitCiv">
            <input style="margin-top:25px;" value='Delete' type="submit" id="delete" name="deleteCiv">
        </div>
    </form>
</div>


<div id="vehicleSelection">
    <img id="vehiclePicture" src="<?php echo $defaultURL; ?>/img/vehicle.png">
    
    <form method="post" action="<?php echo $defaultURL; ?>/utilities/api.php">
        
        <div id="row1">
        <select id="vehicleSelect" name="vehicleSelect">
            <option value="add">Add vehicle</option>
            <?php
            $user_id = $_SESSION['user_id'];
            $query = "SELECT model, id FROM mdt_vehicles WHERE ownerID='$user_id' ";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $model = $row['model'];
                    $id = $row['id'];
                    echo "<option value='$id'>$model</option>";
                }
            }
            ?>
        </select>
            
            <select style="margin-top:25px;" id="owner" name="owner">
                <?php
                $user_id = $_SESSION['user_id'];
                $query = "SELECT id, first, last FROM mdt_characters WHERE ownerID='$user_id'";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        $first = $row['first'];
                        $last = $row['last'];
                        $id = $row['id'];
                        echo "<option value='$id'>$first $last</option>";
                    }
                }
                ?>
            </select>
        
        <input style="margin-top:25px;" type="text" id="model" name="model"  placeholder="Vehicle Name">
        
        </div>
        <div id="row2">
            
            <input type="text" id="plate" name="plate" placeholder="Vehicle Plate">
            
            <input style="margin-top:25px;" type="text" id="description" name="description" placeholder="Vehicle Description">
            
            <select id="reg" name="reg" style="margin-top:25px;">
                <option disabled selected>Registration</option>
                <option>Valid</option>
                <option>Expired</option>
            </select>
            
        </div>
        
        <div id="row3">
            
            <select id="insurance" name="insurance">
                <option disabled selected>Insurance</option>
                <option>None</option>
                <option>Valid</option>
                <option>Expired</option>
            </select>
            
            <select id="flags" name="flags" style="margin-top:25px;">
                <option disabled selected>Flags</option>
                <option>None</option>
                <option>Stolen</option>
            </select>
            
            <input style="margin-top:25px;" type="submit" id="submit" name="submitVeh">
            <input style="margin-top:25px;" value='Delete' type="submit" id="delete" name="deleteVeh">
        </div>
    </form>
</div>

<script>
    $("#vehicleSelect").val(<?php  if(isset($_SESSION['currentVeh'])) {echo $_SESSION['currentVeh'];}else{echo "'add'";}?>);
    
    getVeh();
    
    $('#vehicleSelect').on('change', function() {getVeh();});
    
    function getVeh() {
    var selected = document.getElementById("vehicleSelect").options[vehicleSelect.selectedIndex].value;
    if (selected !== "add") {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getVeh',
      vehID: selected
    },
    function(response){
        console.log(response);
    response = JSON.parse(response);
      document.getElementById("model").value = response[0];
      document.getElementById("plate").value = response[1];
      document.getElementById("description").value = response[2];
      $("#reg").val(response[3]);
      $("#insurance").val(response[4]);
      $("#flags").val(response[5]);
      $("#owner").val(response[6]);
      console.log(response[6]);
    });
    }
    };
</script>

<script>
    $("#nameSelect").val(<?php  if(isset($_SESSION['currentCiv'])) {echo $_SESSION['currentCiv'];}else{echo "'add'";}?>);
    
    getCiv();
    
    $('#nameSelect').on('change', function() {getCiv();});
    
    function getCiv() {
    var selected = document.getElementById("nameSelect").options[document.getElementById("nameSelect").selectedIndex].value;
     
    if (selected !== "add") {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getCiv',
      civID: selected
    },
    function(response){
      response = JSON.parse(response);
      document.getElementById("first").value = response[0];
      document.getElementById("last").value = response[1];
      document.getElementById("dob").value = response[2];
      $("#gender").val(response[3]);
      document.getElementById("address").value = response[4];
      $("#lic").val(response[5]);
      $("#weapon").val(response[6]);
    });
    }
    };
</script>
<script>
    /*
$( "input" ).keydown(function() {
  this.value=this.value.replace(/,/g,'');
});
$( "input" ).keyup(function() {
  this.value=this.value.replace(/,/g,'');
});
*/
</script>