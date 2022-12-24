<?php
include('../settings.php');
include "../include/top.php";

$_SESSION['departmentName'] = 'Dispatch';
$_SESSION['divisonName'] = '';

//session_start();
if (empty($_SESSION['user_loggedIn'])) {
    header("Location:$defaultURL");
    die("You must login first");
}
include('../settings.php');
?>
<html>
<body bgcolor="#fff">
<div style="display: none;"><?php include "../utilities/api.php"; ?></div>
<title>Dispatch</title>
<link rel="stylesheet" type="text/css" href="<?php echo $defaultURL;?>/modal.css">
<link rel="stylesheet" type="text/css" href="style.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">

<div id="sidebar">
<button onclick="openScreen('#dashboard')">Dashboard</button>
<button style="margin-top: 75px;" onclick="openScreen('#nameSearch')">Name Search</button>
<button style="margin-top: 150px;" onclick="openScreen('#plateSearch')">Plate Search</button>
<button style="margin-top: 225px;" onclick="penalOpen()">Penal Code</button>
<button id="signal" style="margin-top: 300px;" onclick="signal()">Signal 100</button>
</div>

<div id="call">
        <h1>Create Call</h1>
        <p><alert></alert></p>
        <div id="callInput">
        <div id="inputDiv">
        <label>Call Type</label>
        <select id="type">
            <option disabled hidden selected value='Call Type'>Call Type</option>
            <?php 
            $query = "SELECT id, name FROM mdt_call_type";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<option>"  . $row['name'] . "</option>";
                }
            }
            ?>
        </select>
        </div>
        <br>
        <div id="inputDiv">
            <label>Location</label>
            <input type="text" id="location" placeholder="Location">
        </div>
        <br>
        <div id="inputDiv">
            <label>Postal Code</label>
            <input type="text" id="postal" placeholder="Postal">
        </div>
        <br>
        <div id="inputDiv">
            <label>Description</label>
            <textarea id='description' style='resize:vertical'></textarea>
        </div>
        <br>
        <div id="inputDiv">
            <button id="create">Create</button>
        </div>
</div>
</div>

<div id="dashboard" style="display: block !important;" class="screen">
    <div id="openCall">
        <h3>Open Calls</h3>
        <table id="openCallTable">
            <tr>
                <th>Call #</th>
                <th>Type</th>
                <th colspan="2">Location</th>
            </tr>
        </table>
    </div>
    
    
    <div id="units">
        <h3>Units</h3>
        <table id="unitTable">
        </table>
    </div>
</div>

<div id="nameSearch" class="screen">
    <h1>Name Search</h1>
    <div id="nameInput">
        <div id="inputDiv">
            <label>First Name</label>
            <input type="text" id="first" placeholder="First">
        </div>
        <div id="inputDiv">
            <label>Last Name</label>
            <input type="text" id="last" placeholder="Last">
        </div>
        <br>
        <button id="search">Search</button>
    </div>
    <br>
    <alert>No Results</alert>

    <div id="searchResults">
        <h2 id="name"></h2>
        <h2 id="dob"></h2>
        <h2 id="gender"></h2>
        <h2 id="address"></h2>
        <h1>Licenses</h1>
        <table style="margin-left: 40px; width: 30%;">
            <tr>
                <th>Type</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Driver</td>
                <td id="lic"></td>
            </tr>
            <tr>
                <td>Weapon</td>
                <td id="weapon"></td>
            </tr>
        </table>

        <h1>Vehicles</h1>
        <table id="vehicles" style="margin-left: 40px; width: 30%;"></table>

        <h1>Records</h1>
        <select id="nameReportSelect" onchange="nameReport(this.value)">
            <option value="tickets">Tickets</option>
            <option value="warnings">Warnings</option>
            <option value="arrests">Arrests</option>
        </select>
        <table id="nameReportTable">
        </table>
    </div>

</div>

<div id="plateSearch" class="screen">
    <h1>Plate Search</h1>
    <div id="plateInput">
        <div id="inputDiv">
            <label>Plate</label>
            <input type="text" id="plate" placeholder="Plate">
        </div>
        <br>
        <button id="search">Search</button>
    </div>
    <br>
    <alert>No Results</alert>

    <div id="searchResults">
        <h2 id="plate"></h2>
        <h2 id="model"></h2>
        <h2 id="owner"></h2>
        <h2 id="description"></h2>
        <h2 id="reg"></h2>
        <h2 id="insurance"></h2>
        <h2 id="flags"></h2>

        <h1>Records</h1>
        <select id="plateReportSelect" onchange="plateReport(this.value)">
            <option value="tickets">Tickets</option>
            <option value="warnings">Warnings</option>
            <option value="arrests">Arrests</option>
        </select>
        <table id="plateReportTable">
        </table>

    </div>     
</div>

<div id="nameModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>


            <table id="civTable" style="">
            </table>


        </div>
    </center>
</div>

<div id="penalModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>


            <table id="penalTable" style="">
                <?php
                $query = "SELECT id, name FROM mdt_penal_category";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='header expand' id='" . $row['id'] . "'><th colspan='3'>" . $row['name'] . "</th></tr>";
                    }
                }
                ?>
            </table>


        </div>
    </center>
</div>

<div id="clearModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Are you sure you want to clear this call and mark all units 10-8?</h1>
            <button id="yes">Yes</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<div id="logoutModal" class="modal">
    <center>
        <div class="modal-content">
            <h1>Are you sure you want to logout </h1><br><h1 style='text-decoration: underline;' id="name"></h1>
            <button id="yes">Yes</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </center>
</div>

<div id="editModal" class="modal">
        <div class="modal-content">
            <div id="callInput">
                <div id="inputDiv">
                    <label>Call Type</label>
                    <select id="type">
                        <option disabled hidden selected value='Call Type'>Call Type</option>
                        <?php
                        $query = "SELECT id, name FROM mdt_call_type";

                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <br>
                <div id="inputDiv">
                    <label>Location</label>
                    <input type="text" id="location" placeholder="Location">
                </div>
                <br>
                <div id="inputDiv">
                    <label>Description</label>
                    <textarea id='description' style='resize:vertical'></textarea>
                </div>
                <br>
                <div id="inputDiv">
                    <button id="edit">Edit</button>
                    <button onclick="closeModal()">Cancel</button>
                </div>
            </div>
        </div>
</div>

<script>
    function openScreen(open) {
        $(".screen").hide();
        $(open).show();
    }
</script>
<script>
    getSignal();
    var sound = 0;
    function getSignal() {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getSignal'
    },
    function(response){
        if (response === '1') {
            $('#sidebar #signal').attr('class','active');
            sound = 1 + sound;
        } else {
            $('#sidebar #signal').attr('class','');
            sound = 0;
        }
    });
    
    if (sound === 1) {
        var signal = new Audio("<?php echo $defaultURL; ?>/audio/signal.mp3");
        signal.play();
    }
    setTimeout(getSignal, 2000);
    }
</script>
<script>
        getUnits();
function getUnits() { 
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getUnits'
    },
    function(response){
        $("#units #unitTable").html(response);
    });
setTimeout(getUnits, 5000);   
}

function changeStatus(id, status) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'changeStatus',
      id: id,
      status: status
    },
    function(response){
        getUnits();
    });
}

function changeCall(id, call) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'changeCall',
      id: id,
      call: call
    },
    function(response){
        getUnits();
    });
}

function clearCallConfirm(id) {
    $("#clearModal button#yes").attr('onclick','clearCall(' + id + ')');
    clearOpen();
}

function logoutUserConfirm(id,name) {
    $("#logoutModal button#yes").attr('onclick','logoutUser(' + id + ')');
    $("#logoutModal #name").text(name);
    logoutOpen();
}

function logoutUser(id) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'logoutUser',
      id: id
    },
    function(response){
        closeModal();
        getUnits();
    });
}
</script>
<script>
    getCallDispatch();
function getCallDispatch() { 
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getCallDispatch'
    },
    function(response){
        $("#openCall #openCallTable").html(response);
    });
setTimeout(getCallDispatch, 5000);   
}

function clearCallConfirm(id) {
    $("#clearModal button#yes").attr('onclick','clearCall(' + id + ')');
    clearOpen();
}

function clearCall(id) { 
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'clearCall',
      id: id
    },
    function(response){
        closeModal();
        getCallDispatch();
    });
}

function editCall(id) {
    
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'editCallGet',
      id: id
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        $("#editModal #location").val(response[0]);
        $("#editModal #description").val(response[1]);
        $("#editModal #type").val(response[2]);
        $("#editModal #edit").val(id);
    });
    
    editOpen();
}

$("#editModal #edit").click(function(){
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'editCall',
      type: $("#editModal #type").val(),
      location: $("#editModal #location").val(),
      description: $("#editModal #description").val(),
      id:  $("#editModal #edit").val()
    },
    function(response){
        getCallDispatch();
    });
    
    
    closeModal();
});
</script>
<script>
    $("#call #create").click(function(){
  var type = $( "#call #type option:selected" ).text();
  var location = $("#call input#location").val();
  var postal = $("#call input#postal").val();
  var description = $("#call textarea#description").val();
  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'createCall',
      type: type,
      location: location,
      postal: postal,
      description: description
    },
    function(response){
        console.log(response);
        $("#call alert").html(response);
        if (response === "") {
            $("#call #type").val("Call Type");
            $("#call #location").val("");
            $("#call #postal").val("");
            $("#call #description").val("");
            getCallDispatch();
        }
    });
});
</script>
<script>
    var plateSearchId;
$("#plateSearch #search").click(function(){
  $(this).attr('class', 'selected');
  var plate = $("input#plate").val();
  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchPlate',
      plate: plate
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        
        if (response[0] === "No Results") {
              $("#plateSearch alert").show();
              $("#plateSearch #searchResults").hide();
        } else {
              if (response[0] === "multiple") {
              $("#civTable").html("<tr><th>Plate</th><th>Description</th><th>Civ</th></tr>" + response[1]);
              nameOpen();
          } else {
              $("#plateSearch alert").hide();
              //$("#nameInput").hide();
              $("#plateSearch #searchResults").show();
              $("#plateSearch #searchResults #plate").html("Plate: " + response[1]);
              $("#plateSearch #searchResults #model").html("Model: " + response[2]);
              $("#plateSearch #searchResults #owner").html("Owner: " + response[3]);
              $("#plateSearch #searchResults #description").html("Description: " + response[4]);
              $("#plateSearch #searchResults #reg").html("Registration: " + response[5]);
              $("#plateSearch #searchResults #insurance").html("Insurance: " + response[6]);
              $("#plateSearch #searchResults #flags").html("Flags: " + response[7]);
              $("#plateSearch input#plate").attr('plateid',response[8]);
              plateReport();
              }
        }
    });
    
});

function plateReport() {
    var plate = $("#plateSearch #plate").attr('plateid');
    var type = $("#plateSearch #plateReportSelect option:selected" ).val();
    console.log(type);
    //var plate = $("#plateSearch input#plate").val();
    console.log(plate);
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'plateReport',
      plate: plate,
      type: type
    },
    function(response){
        console.log(response);
        $("#plateSearch #searchResults #plateReportTable").html(response);
    });
}

function plateReportDetailed(id) {
    var type = $("#plateSearch #plateReportSelect option:selected" ).val();
     if ($('#' + id).nextUntil('#plateReportTable tr.header').length === 0) {
     console.log('yo');
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'plateReportDetailed',
      type: type,
      id: id
    },
    function(response){
        console.log(response);
        $(response).insertAfter("#plateReportTable #"+id);
    });
    }
    $('#plateReportTable tr').not('#plateReportTable tr.header').remove();
}


</script>
<script>
function searchPlateMultiple(id) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchPlateMultiple',
      id: id
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
              $("#plateSearch alert").hide();
              $("#plateSearch #searchResults").show();
              $("#plateSearch #searchResults #plate").html("Plate: " + response[1]);
              $("#plateSearch #searchResults #model").html("Model: " + response[2]);
              $("#plateSearch #searchResults #owner").html("Owner: " + response[3]);
                    $("#plateSearch #searchResults #description").html("Description: " + response[4]);
                    $("#plateSearch #searchResults #reg").html("Registration: " + response[5]);
                    $("#plateSearch #searchResults #insurance").html("Insurance: " + response[6]);
                    document.getElementById('nameModal').style.display = 'none';
                    $("#plateSearch #searchResults #flags").html("Flags: " + response[7]);
                    $("#plateSearch input#plate").attr('plateid', response[8]);
                    console.log(response[8]);
                    plateReport();
                });
    }
</script>
<script>
    $("#nameSearch #search").click(function () {
        $(this).attr('class', 'selected');
        var first = $("input#first").val();
        var last = $("input#last").val();

        $.post("<?php echo $defaultURL; ?>/utilities/api.php",
                {
                    function: 'searchName',
                    first: first,
                    last: last
                },
                function (response) {
                    console.log(response);
                    response = JSON.parse(response);

                    if (response[0] === "No Results") {
                        $("#nameSearch alert").show();
                        $("#nameSearch #searchResults").hide();
                    } else {
                        if (response[0] === "multiple") {
                            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
                            nameOpen();
                        } else {
                            $("#nameSearch alert").hide();
                            //$("#nameInput").hide();
                            $("#nameSearch #searchResults").show();
                            $("#nameSearch #searchResults #name").html("Name: " + response[1] + " " + response[2]);
                            $("#nameSearch #searchResults #dob").html("DOB: " + response[3]);
                            $("#nameSearch #searchResults #gender").html("Gender: " + response[4]);
                            $("#nameSearch #searchResults #address").html("Address: " + response[5]);
                            $("#nameSearch #searchResults #lic").html(response[6]);
                            $("#nameSearch #searchResults #weapon").html(response[7]);
                            getSearchedNameVehicles(response[8]);
                            $("#nameSearch input#first").attr('nameid', response[8]);
                            nameReport();
                        }
                    }
                });

    });

    function nameReport() {
        var name = $("#nameSearch input#first").attr('nameid');
        var type = $("#nameSearch #nameReportSelect option:selected").val();
        console.log(type);
        //var plate = $("#plateSearch input#plate").val();
        console.log(plate);
        $.post("<?php echo $defaultURL; ?>/utilities/api.php",
                {
                    function: 'nameReport',
                    name: name,
                    type: type
                },
                function (response) {
                    console.log(response);
                    $("#nameSearch #searchResults #nameReportTable").html(response);
                });
    }

    function nameReportDetailed(id) {
        var type = $("#nameSearch #nameReportSelect option:selected").val();
        if ($('#' + id).nextUntil('#nameReportTable tr.header').length === 0) {
            console.log('yo');
            $.post("<?php echo $defaultURL; ?>/utilities/api.php",
                    {
                        function: 'nameReportDetailed',
                        type: type,
                        id: id
                    },
                    function (response) {
                        console.log(response);
                        $(response).insertAfter("#nameReportTable #" + id);
                    });
        }
        $('#nameReportTable tr').not('#nameReportTable tr.header').remove();
    }
</script>
<script>
    function searchNameMultiple(id) {
        $.post("<?php echo $defaultURL; ?>/utilities/api.php",
                {
                    function: 'searchNameMultiple',
                    id: id
                },
                function (response) {
                    console.log(response);
                    response = JSON.parse(response);
                    $("#nameSearch alert").hide();
                    //$("#nameInput").hide();
                    $("#nameSearch #searchResults").show();
                    $("#nameSearch #searchResults #name").html("Name: " + response[1] + " " + response[2]);
                    $("#nameSearch #searchResults #dob").html("DOB: " + response[3]);
                    $("#nameSearch #searchResults #gender").html("Gender: " + response[4]);
                    $("#nameSearch #searchResults #address").html("Address: " + response[5]);
                    $("#nameSearch #searchResults #lic").html(response[6]);
                    $("#nameSearch #searchResults #weapon").html(response[7]);
                    getSearchedNameVehicles(response[8]);
                    $("#nameSearch input#first").attr('nameid', response[8]);
                    nameReport();
                    document.getElementById('nameModal').style.display = 'none';
                });
    }
</script>
<script>
    function getSearchedNameVehicles(id) {
        $.post("<?php echo $defaultURL; ?>/utilities/api.php",
                {
                    function: 'getSearchedNameVehicles',
                    id: id
                },
                function (response) {
                    $("#nameSearch #searchResults #vehicles").html("<tr><th>Plate</th><th>Model</th><th>Description</th></tr>" + response);
                });
    }
</script>
<script>
$('#penalModal .header').click(function(){
     if ($(this).nextUntil('#penalModal tr.header').length === 0) {
     var id = $(this)[0].id;
     
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenal',
      id: id
    },
    function(response){
        $(response).insertAfter("#penalModal #"+id);
    });
    }
    $('#penalModal tr').not('#penalModal tr.header').remove();
});
</script>
<script>
function nameOpen() {
document.getElementById('nameModal').style.display = 'block';
}

function penalOpen() {
document.getElementById('penalModal').style.display = 'block';
}

function clearOpen() {
document.getElementById('clearModal').style.display = 'block';
}

function logoutOpen() {
document.getElementById('logoutModal').style.display = 'block';
}

function editOpen() {
document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
$(".modal").hide();
}

window.onclick = function(event) {
if ($(event.target).hasClass("modal")) {
$(".modal").hide();
}};
</script>

<script>
    function signal() {   
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'signal'
    },
    function(){
        getSignal();
    });
    }
</script>