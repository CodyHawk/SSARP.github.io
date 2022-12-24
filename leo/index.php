<?php
include('../settings.php');
include "../include/top.php";

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
<title>Law Enforcement</title>
<link rel="stylesheet" type="text/css" href="<?php echo $defaultURL;?>/modal.css">
<link rel="stylesheet" type="text/css" href="style.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">

<div class="department"><b>Current Department:</b> <?php echo $_SESSION['departmentName'] . " - " . $_SESSION['divisonName'];?></div>

<div id="sidebar">
<button onclick="openScreen('#dashboard')">Dashboard</button>
<button style="margin-top: 75px;" onclick="openScreen('#nameSearch')">Name Search</button>
<button style="margin-top: 150px;" onclick="openScreen('#plateSearch')">Plate Search</button>
<button style="margin-top: 225px;" onclick="openScreen('#citation')">Citation</button>
<button style="margin-top: 300px;" onclick="openScreen('#warning')">Written Warning</button>
<button style="margin-top: 375px;" onclick="openScreen('#arrest')">Arrest Report</button>
<button style="margin-top: 450px;" onclick="penalOpen()">Penal Code</button>
<button id="signal" style="margin-top: 525px; cursor: default !important;" disabled>Signal 100</button>
</div>

<div id="statusbar">
    <button id="10-6">10-6</button>
    <button id="10-7">10-7</button>
    <button id="10-8">10-8</button>
    <button id="10-15">10-15</button>
    <button id="10-23">10-23</button>
    <button id="10-97">10-97</button>
    <button id="10-42">10-42</button>
</div>

<div id="dashboard" style="display: block !important;" class="screen">
    <h1 id="noCallAlert">No Call Assigned</h1>
    <div id="call">
        <h1 id="call_name"></h1>
        <br>
        <h2 id="call_info"></h2>
        <br>
        <div id="call_details"></div>
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

<div id="citation" class="screen">
    <h1>Citation</h1>
    <alert></alert>
    <!--Name Search-->
    <div id="citationInput">
        <div id="inputDiv">
            <label>First Name</label>
            <input type="text" id="first" placeholder="First">
        </div>
        <div id="inputDiv">
            <label>Last Name</label>
            <input type="text" id="last" placeholder="Last">
        </div>
        <br>
        <button id="searchName">Search</button>
        <br>
    </div>
    <br>
    <br>

    
    <!--Plate Search-->
        <div id="citationInput">
        <!--Plate Search-->
        <div id="inputDiv">
            <label>Plate</label>
            <input type="text" id="plate" plateId="0" placeholder="Plate">
        </div>
        <br>
        <button id="searchPlate">Search</button>
        <br>
    </div>
    <br>
    <br>
    
    <!--Vehicle Description-->
    <div id="citationInput">
        <div id="inputDiv">
            <label>Vehicle Description</label>
            <input type="text" id="description" placeholder="Description" style="width: 275%;">
        </div>
        <br>
    </div>

    
    <!--List-->
    <div id="citationList">
        <table id="citationListTable">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th colspan="2">Punishment</th>
            </tr>
        </table>
        <button style="margin-top: 5px;" onclick="citationOpen()">Add</button>
    </div>

    <div id="citationInput" style="margin-top: 20px;">
        <!--Location-->
        <div id="inputDiv">
            <label>Location</label>
            <input type="text" id="location" placeholder="Location" style="width: 275%;">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>

    <!--Fine-->
    <div id="citationInput">
        <div id="inputDiv">
            <label>Total Fine</label>
            <input type="number" id="fine" placeholder="Fine" value="0" min="0">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>
    
    <div id="citationInput" style="margin-bottom: 50px;">
    <button id="submit">Submit</button>
    </DIV>
</div>

<div id="arrest" class="screen">
    <h1>Arrest Report</h1>
    <alert></alert>
    <!--Name Search-->
    <div id="arrestInput">
        <div id="inputDiv">
            <label>First Name</label>
            <input type="text" id="first" placeholder="First">
        </div>
        <div id="inputDiv">
            <label>Last Name</label>
            <input type="text" id="last" placeholder="Last">
        </div>
        <br>
        <button id="searchName">Search</button>
        <br>
    </div>
    <br>
    <br>


    <!--Plate Search-->
    <div id="arrestInput">
        <!--Plate Search-->
        <div id="inputDiv">
            <label>Plate</label>
            <input type="text" id="plate" plateId="0" placeholder="Plate">
        </div>
        <br>
        <button id="searchPlate">Search</button>
        <br>
    </div>
    <br>
    <br>

    <!--Vehicle Description-->
    <div id="arrestInput">
        <div id="inputDiv">
            <label>Vehicle Description</label>
            <input type="text" id="description" placeholder="Description" style="width: 275%;">
        </div>
        <br>
    </div>


    <!--List-->
    <div id="arrestList">
        <table id="arrestListTable">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th colspan="2">Punishment</th>
            </tr>
        </table>
        <button style="margin-top: 5px;" onclick="arrestOpen()">Add</button>
    </div>

    <div id="arrestInput" style="margin-top: 20px;">
        <!--Location-->
        <div id="inputDiv">
            <label>Location</label>
            <input type="text" id="location" placeholder="Location" style="width: 275%;">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>

    <!--Fine-->
    <div id="arrestInput">
        <div id="inputDiv">
            <label>Total Fine</label>
            <input type="number" id="fine" placeholder="Fine" value="0" min="0">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>
    
    <!--Jail Time-->
    <div id="arrestInput">
        <div id="inputDiv">
            <label>Jail Time</label>
            <input type="number" id="jail" placeholder="Time" value="0" min="0">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>

    <div id="arrestInput" style="margin-bottom: 50px;">
        <button id="submit">Submit</button>
    </DIV>
</div>

<div id="warning" class="screen">
    <h1>Warning</h1>
    <alert></alert>
    <!--Name Search-->
    <div id="warningInput">
        <div id="inputDiv">
            <label>First Name</label>
            <input type="text" id="first" placeholder="First">
        </div>
        <div id="inputDiv">
            <label>Last Name</label>
            <input type="text" id="last" placeholder="Last">
        </div>
        <br>
        <button id="searchName">Search</button>
        <br>
    </div>
    <br>
    <br>


    <!--Plate Search-->
    <div id="warningInput">
        <!--Plate Search-->
        <div id="inputDiv">
            <label>Plate</label>
            <input type="text" id="plate" plateId="0" placeholder="Plate">
        </div>
        <br>
        <button id="searchPlate">Search</button>
        <br>
    </div>
    <br>
    <br>

    <!--Vehicle Description-->
    <div id="warningInput">
        <div id="inputDiv">
            <label>Vehicle Description</label>
            <input type="text" id="description" placeholder="Description" style="width: 275%;">
        </div>
        <br>
    </div>


    <!--List-->
    <div id="warningList">
        <table id="warningListTable">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th colspan="2">Punishment</th>
            </tr>
        </table>
        <button style="margin-top: 5px;" onclick="warningOpen()">Add</button>
    </div>

    <div id="warningInput" style="margin-top: 20px;">
        <!--Location-->
        <div id="inputDiv">
            <label>Location</label>
            <input type="text" id="location" placeholder="Location" style="width: 275%;">
        </div>
        <br>
        <br>
    </div>
    <br>
    <br>

    <div id="warningInput" style="margin-bottom: 50px;">
        <button id="submit">Submit</button>
    </DIV>
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
                }}
                    ?>
            </table>

            
        </div>
    </center>
</div>


<div id="citationModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>

            
            <table id="citationTable" style="">
                <?php 
                    $query = "SELECT id, name FROM mdt_penal_category";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='header expand' id='" . $row['id'] . "'><th colspan='3'>" . $row['name'] . "</th></tr>";
                }}
                    ?>
            </table>

            
        </div>
    </center>
</div>

<div id="warningModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>

            
            <table id="warningTable" style="">
                <?php 
                    $query = "SELECT id, name FROM mdt_penal_category";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='header expand' id='" . $row['id'] . "'><th colspan='3'>" . $row['name'] . "</th></tr>";
                }}
                    ?>
            </table>

            
        </div>
    </center>
</div>

<div id="arrestModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>


            <table id="arrestTable" style="">
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


<div id="nameModal" class="modal">
    <center>
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>


            <table id="civTable" style="">
            </table>


        </div>
    </center>
</div>

<script> 
    getSignal();
    var sound = 0;
    var signal = new Audio("<?php echo $defaultURL; ?>/audio/signal.mp3");
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
        signal.play();
    }
    setTimeout(getSignal, 2000);
    }
</script>
<script>
$("#arrest #submit").click(function(){
  
  var characterId = $("#arrest input#first").attr('characterid');
  var plateId = $("#arrest input#plate").attr('plateid');
  var first = $("#arrest input#first").val();
  var last = $("#arrest input#last").val();
  var plate = $("#arrest input#plate").val();
  var description = $("#arrest input#description").val();
  var location = $("#arrest input#location").val();
  var fine = $("#arrest input#fine").val();
  var jail = $("#arrest input#jail").val();
  
  var infraction = [];
  
$("#arrestListTable tr").each(function(){
    infraction.push($(this).find("td:first").text()); //put elements into array
});
    infraction.shift();//fixes weird extra element bug thing
    console.log(plateId);

  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'submitArrest',
      characterId: characterId,
      first: first,
      last: last,
      plateId: plateId,
      plate: plate,
      description: description,
      infraction: infraction,
      location: location,
      fine: fine,
      jail: jail
    },
    function(response){
        console.log(response);
        $("#arrest alert").html(response);
        if (response === "") {
            clearArrest();
        }
    });
    
});

$("#arrest #searchPlate").click(function(){
    var plate = $("#arrest input#plate").val();

    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'doesPlateExistArrest',
      plate: plate
    },
    function(response){
        //console.log(response);
        response = JSON.parse(response);
        if (response[0] === "single"){
        $("#arrest input#description").val(response[1]);
    } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
    }
    });
    
});

$("#arrest #searchName").click(function(){
    var first = $("#arrest input#first").val();
    var last = $("#arrest input#last").val();
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchNameArrest',
      first: first,
      last: last
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        
        if (response[0] === "single"){
            $("#arrest input#first").attr('characterid',response[1]);
        } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
        }
    });
});

function searchNameArrest(id) {
    $("#arrest input#first").attr('characterid',id);
    closeModal();
}
function searchPlateArrest(id,description) {
    $("#arrest input#plate").attr('plateid',id);
    $("#arrest input#description").val(description);
    closeModal();
}

function clearArrest() {
    $("#arrest input").val('');
    $('#arrestListTable tr').not(':first').remove();
    $("#arrest input#fine").val('0');
    $("#arrest input#jail").val('0');
}

    function arrestAdd(id) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalFromId',
      id: id
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        $(response[0]).insertAfter("#arrestListTable tr:last");
        closeModal();
        var fine = parseInt($("#arrest #fine").val()) + parseInt(response[1]);
        console.log(document.getElementById("fine").value);
        $("#arrest #fine").val(fine);
        var jail = parseInt($("#arrest #jail").val()) + parseInt(response[2]);
        $("#arrest #jail").val(jail)
    });
    }
    
$('#arrestTable .header').click(function(){
     if ($(this).nextUntil('#arrestTable tr.header').length === 0) {
     var id = $(this)[0].id;
     
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalArrests',
      id: id
    },
    function(response){
        $(response).insertAfter("#arrestTable #"+id);
    });
    }
    $('#arrestTable tr').not('#arrestTable tr.header').remove();
});
</script>

<script>
$("#warning #submit").click(function(){
  
  var characterId = $("#warning input#first").attr('characterid');
  var plateId = $("#warning input#plate").attr('plateid');
  var first = $("#warning input#first").val();
  var last = $("#warning input#last").val();
  var plate = $("#warning input#plate").val();
  var description = $("#warning input#description").val();
  var location = $("#warning input#location").val();
  
  var infraction = [];
  
$("#warningListTable tr").each(function(){
    infraction.push($(this).find("td:first").text()); //put elements into array
});
    infraction.shift();//fixes weird extra element bug thing
    console.log(infraction);

  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'submitWarning',
      characterId: characterId,
      first: first,
      last: last,
      plateId: plateId,
      plate: plate,
      description: description,
      infraction: infraction,
      location: location
    },
    function(response){
        console.log(response);
        $("#warning alert").html(response);
        if (response === "") {
            clearWarning();
        }
    });
    
});

$("#warning #searchPlate").click(function(){
    var plate = $("#warning input#plate").val();
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'doesPlateExistWarning',
      plate: plate
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        if (response[0] === "single"){
        $("#warning input#description").val(response[1]);
    } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
    }
    });
    
});

$("#warning #searchName").click(function(){
    var first = $("#warning input#first").val();
    var last = $("#warning input#last").val();
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchNameWarning',
      first: first,
      last: last
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        
        if (response[0] === "single"){
            $("#warning input#first").attr('characterid',response[1]);
        } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
        }
    });
});

function searchNameWarning(id) {
    $("#warning input#first").attr('characterid',id);
    closeModal();
}
function searchPlateWarning(id,description) {
    $("#warning input#plate").attr('plateid',id);
    $("#warning input#description").val(description);
    closeModal();
}

function clearWarning() {
    $("#warning input").val('');
    $('#warningListTable tr').not(':first').remove();
}

    function warningAdd(id) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalFromId',
      id: id
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        $(response[0]).insertAfter("#warningListTable tr:last");
        closeModal();
    });
    }
    
$('#warningTable .header').click(function(){
     if ($(this).nextUntil('#warningTable tr.header').length === 0) {
     var id = $(this)[0].id;
     
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalWarnings',
      id: id
    },
    function(response){
        $(response).insertAfter("#warningTable #"+id);
    });
    }
    $('#warningTable tr').not('#warningTable tr.header').remove();
});
</script>

<script>
$("#citation #submit").click(function(){
  
  var characterId = $("#citation input#first").attr('characterid');
  var plateId = $("#citation input#plate").attr('plateid');
  var first = $("#citation input#first").val();
  var last = $("#citation input#last").val();
  var plate = $("#citation input#plate").val();
  var description = $("#citation input#description").val();
  var location = $("#citation input#location").val();
  var fine = $("#citation input#fine").val();
  
  var infraction = [];
  
$("#citationListTable tr").each(function(){
    infraction.push($(this).find("td:first").text()); //put elements into array
});
    infraction.shift();//fixes weird extra element bug thing
    console.log(infraction);

  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'submitCitation',
      characterId: characterId,
      first: first,
      last: last,
      plateId: plateId,
      plate: plate,
      description: description,
      infraction: infraction,
      location: location,
      fine: fine
    },
    function(response){
        console.log(response);
        $("#citation alert").html(response);
        if (response === "") {
            clearTicket();
        }
    });
    
});

$("#citation #searchPlate").click(function(){
    var plate = $("#citation input#plate").val();

    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'doesPlateExist',
      plate: plate
    },
    function(response){
        //console.log(response);
        response = JSON.parse(response);
        if (response[0] === "single"){
        $("#citation input#description").val(response[1]);
    } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
    }
    });
    
});

$("#citation #searchName").click(function(){
    var first = $("#citation input#first").val();
    var last = $("#citation input#last").val();
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchNameCitation',
      first: first,
      last: last
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        
        if (response[0] === "single"){
            $("#citation input#first").attr('characterid',response[1]);
        } else {
            $("#civTable").html("<tr><th>Name</th><th>DOB</th><th>Civ</th></tr>" + response[1]);
            nameOpen();
        }
    });
});

function searchNameCitation(id) {
    $("#citation input#first").attr('characterid',id);
    closeModal();
}
function searchPlateCitation(id,description) {
    $("#citation input#plate").attr('plateid',id);
    $("#citation input#description").val(description);
    closeModal();
}

function clearTicket() {
    $("#citation input").val('');
    $('#citationListTable tr').not(':first').remove();
    $("#citation input#fine").val('0');
}

    function citationAdd(id) {
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalFromId',
      id: id
    },
    function(response){
        console.log(response);
        response = JSON.parse(response);
        $(response[0]).insertAfter("#citationListTable tr:last");
        closeModal();
        var fine = parseInt($("#citation #fine").val()) + parseInt(response[1]);
        console.log(document.getElementById("fine").value);
        $("#citation #fine").val(fine)
    });
    }
    
$('#citationTable .header').click(function(){
     if ($(this).nextUntil('#citationTable tr.header').length === 0) {
     var id = $(this)[0].id;
     
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'getPenalCitations',
      id: id
    },
    function(response){
        $(response).insertAfter("#citationTable #"+id);
    });
    }
    $('#citationTable tr').not('#citationTable tr.header').remove();
});
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
    /*
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
*/
</script>

<script>
    function openScreen(open) {
        $(".screen").hide();
        $(open).show();
    }
</script>
<script>
$("#statusbar button").click(function(){
  $("#statusbar button").attr('class', '');
  $(this).attr('class', 'selected');
  var status = $(this).html();
  console.log(status);
    
    if (status === "10-8") {
        $("#call").hide();
    }
  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'submitStatus',
      status: status
    },
    function(response){
        console.log(response);
    });
    
});
function getStatus() {
$.post("<?php echo $defaultURL; ?>/utilities/api.php",
{
  function: 'getStatus'
},
function(response){
    $("#statusbar button").attr('class', '');
    $("#statusbar button#" + response).attr('class', 'selected');
});
    }
</script>
<script>
    getCall();
  var newCall = 0;
function getCall() {
var newCallSound = new Audio("<?php echo $defaultURL; ?>/audio/newCall.mp3");
$.post("<?php echo $defaultURL; ?>/utilities/api.php",
{
  function: 'getCall'
},
function(response){
  //console.log(response);
  
  if (response !== 'none') {
      $("#call").show();
      $("#noCallAlert").hide();
      newCall = newCall + 1;
      
    response = JSON.parse(response);
    document.getElementById("call_name").innerHTML = "Call Details - " + response[1];
    document.getElementById("call_info").innerHTML = "CALL " + response[0] + " - " + response[2];
    document.getElementById("call_details").innerHTML = response[3];
      
      
  } else {
      $("#call").hide();
      $("#noCallAlert").show();
      newCall = 0;
  }
  
  if (newCall === 1) {
      console.log('New Call');
    newCallSound.play();
  } 
  
});
getStatus();
setTimeout(getCall, 5000);
    }
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
     console.log(id);
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
              $("#plateSearch input#plate").attr('plateid',response[8]);
              plateReport();
    });
} 
</script>
<script>
$("#nameSearch #search").click(function(){
  $(this).attr('class', 'selected');
  var first = $("input#first").val();
  var last = $("input#last").val();
  
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'searchName',
      first: first,
      last: last
    },
    function(response){
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
              $("#nameSearch #searchResults #name").html("Name: " + response[1] + " " +response[2]);
              $("#nameSearch #searchResults #dob").html("DOB: " + response[3]);
              $("#nameSearch #searchResults #gender").html("Gender: " + response[4]);
              $("#nameSearch #searchResults #address").html("Address: " + response[5]);
              $("#nameSearch #searchResults #lic").html(response[6]);
              $("#nameSearch #searchResults #weapon").html(response[7]);
              getSearchedNameVehicles(response[8]);
              $("#nameSearch input#first").attr('nameid',response[8]);
              nameReport();
              }
        }
    });
    
});

function nameReport() {
    var name = $("#nameSearch input#first").attr('nameid');
    var type = $("#nameSearch #nameReportSelect option:selected" ).val();
    console.log(type);
    //var plate = $("#plateSearch input#plate").val();
    console.log(plate);
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'nameReport',
      name: name,
      type: type
    },
    function(response){
        console.log(response);
        $("#nameSearch #searchResults #nameReportTable").html(response);
    });
}

function nameReportDetailed(id) {
    var type = $("#nameSearch #nameReportSelect option:selected" ).val();
     if ($('#' + id).nextUntil('#nameReportTable tr.header').length === 0) {
     console.log('yo');
    $.post("<?php echo $defaultURL; ?>/utilities/api.php",
    {
      function: 'nameReportDetailed',
      type: type,
      id: id
    },
    function(response){
        console.log(response);
        $(response).insertAfter("#nameReportTable #"+id);
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
    function(response){
        console.log(response);
        response = JSON.parse(response);
              $("#nameSearch alert").hide();
              //$("#nameInput").hide();
              $("#nameSearch #searchResults").show();
              $("#nameSearch #searchResults #name").html("Name: " + response[1] + " " +response[2]);
              $("#nameSearch #searchResults #dob").html("DOB: " + response[3]);
              $("#nameSearch #searchResults #gender").html("Gender: " + response[4]);
              $("#nameSearch #searchResults #address").html("Address: " + response[5]);
              $("#nameSearch #searchResults #lic").html(response[6]);
              $("#nameSearch #searchResults #weapon").html(response[7]);
              getSearchedNameVehicles(response[8]);
              $("#nameSearch input#first").attr('nameid',response[8]);
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
    function(response){
        $("#nameSearch #searchResults #vehicles").html("<tr><th>Plate</th><th>Model</th><th>Description</th></tr>" + response);
    });
    }
</script>
    <script>
function nameOpen() {
    document.getElementById('nameModal').style.display = 'block';
}


function penalOpen() {
    document.getElementById('penalModal').style.display = 'block';
}

function citationOpen() {
    document.getElementById('citationModal').style.display = 'block';
}

function warningOpen() {
    document.getElementById('warningModal').style.display = 'block';
}

function arrestOpen() {
    document.getElementById('arrestModal').style.display = 'block';
}

function closeModal() {
  $(".modal").hide();
}

window.onclick = function(event) {
  if ($(event.target).hasClass("modal")) {
    $(".modal").hide();
  }};
</script>