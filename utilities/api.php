<?php
require 'q3query.class.php';
include('../settings.php');
session_start();
date_default_timezone_set('America/Los_Angeles');
$date = date("m-d-Y H:i:s");

if ($_POST['function'] == 'setDepartment') {
    session_start();
    $_SESSION['departmentID'] = $_POST['departmentID'];
    $departmentID = $_POST['departmentID'];
    $_SESSION['divisonID'] = $_POST['divisonID'];
    $divisonID = $_POST['divisonID'];
    
    $result = mysqli_query($conn, "SELECT * FROM mdt_departments WHERE id='$departmentID' LIMIT 1");
    while($row = mysqli_fetch_assoc($result)) {
    $_SESSION['departmentName'] = $row['abbreviation'];
    }
    
    $result = mysqli_query($conn, "SELECT * FROM mdt_divisions WHERE id='$divisonID' LIMIT 1");
    while($row = mysqli_fetch_assoc($result)) {
    $_SESSION['divisonName'] = $row['abbreviation'];
    }

    header("Location:$defaultURL/dashboard.php");
}

if ($_POST['function'] == 'getAOP') {
    if (empty($_SESSION['user_loggedIn'])) {
        echo "logout";
    } else {
        
        $serverID = $_SESSION['user_server'];
    $query = "SELECT aop FROM mdt_server WHERE id='$serverID'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            if($_SESSION['user_admin']) {
                echo "<a href='javascript:aopOpen();'>AOP: " . $row['aop'] ."</a>";}else {echo 'AOP: ' . $row['aop'];}
        }
    }
}
}

if ($_POST['function'] == 'submitAOP') {
    if($_SESSION['user_admin']) {
    $serverID = $_SESSION['user_server'];
    $aop = $_POST['aop'];
    
    $query = "UPDATE mdt_server SET aop='$aop' WHERE id='$serverID'";

    mysqli_query($conn, $query);
} else {
    echo "not today";
}}

if ($_POST['function'] == 'getCiv') {
    $civID = $_POST['civID'];
    unset($_SESSION['currentCiv']);
    $_SESSION['currentCiv'] = $civID;
    $query = "SELECT first, last, dob, gender, address, lic, weapon FROM mdt_characters WHERE id='$civID'";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
        echo json_encode(array($row['first'] , $row['last'] , $row['dob'] , $row['gender'] , $row['address'] , $row['lic'] , $row['weapon']));
    }
}
}

if ($_POST['function'] == 'getVeh') {
    $vehID = $_POST['vehID'];
    unset($_SESSION['currentVeh']);
    $_SESSION['currentVeh'] = $vehID;
    $query = "SELECT model, plate, description, reg, insurance, flags, characterID FROM mdt_vehicles WHERE id='$vehID'";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            
        //echo $row['model'];
        echo json_encode(array($row['model'],$row['plate'],$row['description'],$row['reg'],$row['insurance'],$row['flags'],$row['characterID']));
    }
}
}

if (isset($_POST['submitCiv'])) {
    $ownerID = $_SESSION['user_id'];
    $id = $_POST['nameSelect'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $lic = $_POST['lic'];
    $weapon = $_POST['weapon'];
    
    if ($_POST['nameSelect'] == 'add') {
    $query = "INSERT INTO mdt_characters (ownerID, first, last, dob, gender, address, lic, weapon) VALUES ('$ownerID', '$first', '$last', '$dob', '$gender', '$address', '$lic', '$weapon')";
    
    mysqli_query($conn, $query);
    header("Location:$defaultURL/civ");  
} else {
    
    $query = "UPDATE mdt_characters SET first='$first', last='$last', dob='$dob', gender='$gender', address='$address', lic='$lic', weapon='$weapon' WHERE id='$id'";

        mysqli_query($conn, $query);
        header("Location:$defaultURL/civ");
    }
}

if (isset($_POST['deleteCiv'])) {
    $id = $_POST['nameSelect'];
    $query = "DELETE FROM mdt_characters WHERE id='$id'";
    mysqli_query($conn, $query);
    unset($_SESSION['currentCiv']);
    header("Location:$defaultURL/civ");
}

if (isset($_POST['submitVeh'])) {
    $ownerID = $_SESSION['user_id'];
    $id = $_POST['vehicleSelect'];
    $model = $_POST['model'];
    $plate = $_POST['plate'];
    $description = $_POST['description'];
    $reg = $_POST['reg'];
    $insurance = $_POST['insurance'];
    $flags = $_POST['flags'];
    $characterID = $_POST['owner'];
    
    if ($_POST['vehicleSelect'] == 'add') {
    $query = "INSERT INTO mdt_vehicles (ownerID, characterID, model, plate, description, reg, insurance, flags) VALUES ('$ownerID', '$characterID', '$model', '$plate', '$description', '$reg', '$insurance', '$flags')";
    
    mysqli_query($conn, $query);
    header("Location:$defaultURL/civ");
    
} else{
    
    $query = "UPDATE mdt_vehicles SET characterID='$characterID', model='$model', plate='$plate', description='$description', reg='$reg', insurance='$insurance', flags='$flags' WHERE id='$id'";

        mysqli_query($conn, $query);
        header("Location:$defaultURL/civ");
    }
}

if (isset($_POST['deleteVeh'])) {
    $id = $_POST['vehicleSelect'];
    $query = "DELETE FROM mdt_vehicles WHERE id='$id'";
    mysqli_query($conn, $query);
    unset($_SESSION['currentVeh']);
    header("Location:$defaultURL/civ");
}

if (isset($_POST['submitIdentifier'])) {
    $department = $_SESSION['departmentName'];
    $divison = $_SESSION['divisonName'];
    $user_id = $_SESSION['user_id'];
    $identifier = $_POST['identifier'];
    $serverID = $_SESSION['user_server'];
    $result = mysqli_query($conn, "SELECT * FROM active_users WHERE user_id='$user_id' LIMIT 1");
    if (mysqli_fetch_row($result)) {
        $query = "UPDATE mdt_active_users SET identifier='$identifier', server='$serverID' WHERE user_id='$user_id'";
    } else {
        $query = "INSERT INTO mdt_active_users (user_id, identifier, server, department, divison) VALUES ('$user_id', '$identifier', '$serverID', '$department', '$divison')";
    }
    
    $_SESSION['user_identifier'] = $identifier;
    mysqli_query($conn, $query);
    if ($_SESSION['departmentName'] === "Dispatch") {
        header("Location:$defaultURL/dispatch");
    } else {
        header("Location:$defaultURL/leo");
    }
}

if ($_POST['function'] == 'submitStatus') {     
    $user_id = $_SESSION['user_id'];
    $status = $_POST['status'];
    $query = "UPDATE mdt_active_users SET status='$status' WHERE user_id='$user_id'";
    $_SESSION['status'] = $status;
    mysqli_query($conn, $query);
    echo $status;
    if ($status == "10-8") {
        $query = "UPDATE mdt_active_users SET callID='0' WHERE user_id='$user_id'";
        mysqli_query($conn, $query);
    }
}

if ($_POST['function'] == 'getStatus') {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM mdt_active_users WHERE user_id='$user_id'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo $row['status'];
            $_SESSION['status'] = $row['status'];
            if ($row['status'] == "10-8") {
                $query = "UPDATE mdt_active_users SET callID='0' WHERE user_id='$user_id'";
                mysqli_query($conn, $query);
            }
        }
    }
}

    if ($_POST['function'] == 'getCall') {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM mdt_active_users WHERE user_id='$user_id'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $callID = $row['callID'];
            if ($row['logout'] == 1) {
                header("Location:$defaultURL/utilities/logout.php");
                exit();
            }
        }
    }

    $query = "SELECT id, type, location, details FROM mdt_calls WHERE id='$callID'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo json_encode(array($row['id'],$row['type'],$row['location'],$row['details']));
        }
    } else {
        echo "none";
    }
}

//tbh i dont even know how i made this
//get search name


if ($_POST['function'] == 'searchName') {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $query = "SELECT ownerID, id, first, last, dob, gender, address, lic, weapon FROM mdt_characters WHERE first='$first' AND last='$last'";
    
    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
            if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
        echo json_encode(array("single",$row['first'],$row['last'],$row['dob'],$row['gender'],$row['address'],$row['lic'],$row['weapon'],$row['id']));
        $_SESSION['searchedNameID'] = $row['id'];
        }
    } else {
        while ($row = $result->fetch_assoc()) {
        $response .= "<tr><td><a href='javascript:searchNameMultiple(" . $row['id'] . ")'>" . $row['first'] . ' ' . $row['last'] . '</a></td><td>' . $row['dob']  . '</td><td>' . ownerName($row['ownerID']) . "</td></tr>";
        }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }

} else {
    echo json_encode(array("No Results"));
}}

function ownerName($id) {
    include('../settings.php');
    $query = "SELECT * FROM mdt_users WHERE user_id='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            return $row['name'];
        }
    } else {
        return "No name for $id";
    }
}

if ($_POST['function'] == 'searchNameMultiple') {
    $id = $_POST['id'];
    $query = "SELECT id, first, last, dob, gender, address, lic, weapon FROM mdt_characters WHERE id='$id'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single",$row['first'],$row['last'],$row['dob'],$row['gender'],$row['address'],$row['lic'],$row['weapon'],$row['id']));
            }
        }
    }
    
if ($_POST['function'] == 'getSearchedNameVehicles') {
    $id = $_POST['id'];
    $query = "SELECT plate, model, description FROM mdt_vehicles WHERE characterID='$id'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr><td>' . $row['plate'] . '</td><td>' . $row['model'] . '</td><td>' . $row['description'] . '</td></tr>';
            }
        }
    }
    
    //Get searched vehicles
    
    if ($_POST['function'] == 'searchPlate') {
    $plate = $_POST['plate'];
    $query = "SELECT ownerID, id, characterID, model, plate, description, reg, insurance, flags FROM mdt_vehicles WHERE plate='$plate'";

    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single",$row['plate'],$row['model'],characterName($row['characterID']),$row['description'],$row['reg'],$row['insurance'],$row['flags'],$row['id']));
                $_SESSION['searchedNameID'] = $row['id'];
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $response .= "<tr><td><a href='javascript:searchPlateMultiple(" . $row['id'] . ")'>" . $row['plate'] . '</a></td><td>' . $row['description'] . '</td><td>' . characterName($row['characterID']) . "</td></tr>";
            }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
    } else {
        echo json_encode(array("No Results"));
    }
}

if ($_POST['function'] == 'searchPlateMultiple') {
    $id = $_POST['id'];
    $query = "SELECT ownerID, id, characterID, model, plate, description, reg, insurance, flags FROM mdt_vehicles WHERE id='$id'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo json_encode(array("single",$row['plate'],$row['model'],characterName($row['characterID']),$row['description'],$row['reg'],$row['insurance'],$row['flags'],$row['id']));
        }
    }
}

function characterName($id) {
    include('../settings.php');
    $query = "SELECT * FROM mdt_characters WHERE id='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            return $row['first'] . ' ' . $row['last'];
        }
    } else {
        return "?";
    }
}

if ($_POST['function'] == 'getPenal') {
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_penal_charges WHERE cat='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td>" . $row['name'] . "</td><td>" . $row['type'] . "</td><td>" . $row['punishment'] . "</td></tr>";
        }
    }
}

if ($_POST['function'] == 'getPenalCitations') {
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_penal_charges WHERE cat='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td><a href='javascript:citationAdd(". $row['id'] . ");'>" . $row['name'] . "</a></td><td>" . $row['type'] . "</td><td>" . $row['punishment'] . "</td></tr>";
        }
    }
}

if ($_POST['function'] == 'getPenalFromId') {
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_penal_charges WHERE id='$id' LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo json_encode(array("<tr><td onclick=''>" . $row['name'] . "</td><td>" . $row['type'] . "</td><td>" . $row['punishment'] . "</td><td><span class='close' onclick='$(this).parent().parent().remove();'>&times;</span></td></tr>",$row['fine'],$row['jail']));
        }
    }
}

if ($_POST['function'] == 'submitCitation') {
    
    if (!isset($_POST['infraction'])) {
        echo "Please add a infraction!";
        exit();
    }
    
    if (!isset($_POST['characterId'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (!doesCivExist($_POST['first'], $_POST['last'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (isset($_POST['location'])) {
    if ($_POST['location'] == "") {
        echo "Please add a location!";
        exit();
    }}

    if (isset($_POST['fine'])) {
    if ($_POST['fine'] == "") {
        echo "Please add a fine!";
        exit();
    }}
    
    $characterId = $_POST['characterId'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $plateId = $_POST['plateId'];
    $plate = $_POST['plate'];
    $description = $_POST['description'];
    $infraction = json_encode($_POST['infraction']);
    $location = $_POST['location'];
    $fine = $_POST['fine'];

    $query = "INSERT INTO mdt_tickets (charaterId, first, last, plateId, plate, description, infraction, location, fine, date, officer) VALUES ('$characterId', '$first', '$last', '$plateId', '$plate', '$description', '$infraction', '$location', '$fine', '$date', '" . $_SESSION['user_identifier'] . " [" . $_SESSION['departmentName'] . "]')";
    mysqli_query($conn, $query);
}

function doesCivExist($first, $last) {
    include('../settings.php');
    $query = "SELECT * FROM mdt_characters WHERE first='$first' AND last='$last'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
            return true;
    } else {
            return false;
        }
    }
    
if ($_POST['function'] == 'doesPlateExist') {
   $plate = $_POST['plate'];
   
    $query = "SELECT * FROM mdt_vehicles WHERE plate='$plate'";
    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single",$row['model'] . ' - ' . $row['description']));
        }
    } else {
        while ($row = $result->fetch_assoc()) {
            $response .= "<tr><td><a href="  . '"' . "javascript:searchPlateCitation(" . $row['id'] . ",'" . $row['model'] . ' - ' . $row['description'] . "')" . '"' . ">" . $row['plate'] . '</a></td><td>' . $row['description'] . '</td><td>' . characterName($row['characterID']) . "</td></tr>";
        }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
}
}

if ($_POST['function'] == 'searchNameCitation') {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $query = "SELECT ownerID, id, first, last, dob FROM mdt_characters WHERE first='$first' AND last='$last'";
    
    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
            if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
        echo json_encode(array("single",$row['id']));
        }
    } else {
        while ($row = $result->fetch_assoc()) {
        $response .= "<tr><td><a href='javascript:searchNameCitation(" . $row['id'] . ")'>" . $row['first'] . ' ' . $row['last'] . '</a></td><td>' . $row['dob']  . '</td><td>' . ownerName($row['ownerID']) . "</td></tr>";
        }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
} else {
    echo json_encode(array("No Results"));
}}








if ($_POST['function'] == 'submitWarning') {
    
    if (!isset($_POST['infraction'])) {
        echo "Please add a infraction!";
        exit();
    }
    
    if (!isset($_POST['characterId'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (!doesCivExist($_POST['first'], $_POST['last'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (isset($_POST['location'])) {
    if ($_POST['location'] == "") {
        echo "Please add a location!";
        exit();
    }}

    $characterId = $_POST['characterId'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $plateId = $_POST['plateId'];
    $plate = $_POST['plate'];
    $description = $_POST['description'];
    $infraction = json_encode($_POST['infraction']);
    $location = $_POST['location'];

    $query = "INSERT INTO mdt_warnings (charaterId, first, last, plateId, plate, description, infraction, location, date, officer) VALUES ('$characterId', '$first', '$last', '$plateId', '$plate', '$description', '$infraction', '$location', '$date', '" . $_SESSION['user_identifier'] . " [" . $_SESSION['departmentName'] . "]')";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'doesPlateExistWarning') {
    $plate = $_POST['plate'];

    $query = "SELECT * FROM mdt_vehicles WHERE plate='$plate'";
    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single", $row['model'] . ' - ' . $row['description']));
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $response .= "<tr><td><a href=" . '"' . "javascript:searchPlateWarning(" . $row['id'] . ",'" . $row['model'] . ' - ' . $row['description'] . "')" . '"' . ">" . $row['plate'] . '</a></td><td>' . $row['description'] . '</td><td>' . characterName($row['characterID']) . "</td></tr>";
            }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
    }
}

if ($_POST['function'] == 'searchNameWarning') {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $query = "SELECT ownerID, id, first, last, dob FROM mdt_characters WHERE first='$first' AND last='$last'";

    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single", $row['id']));
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $response .= "<tr><td><a href='javascript:searchNameWarning(" . $row['id'] . ")'>" . $row['first'] . ' ' . $row['last'] . '</a></td><td>' . $row['dob'] . '</td><td>' . ownerName($row['ownerID']) . "</td></tr>";
            }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
    } else {
        echo json_encode(array("No Results"));
    }
}

if ($_POST['function'] == 'getPenalWarnings') {
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_penal_charges WHERE cat='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td><a href='javascript:warningAdd(". $row['id'] . ");'>" . $row['name'] . "</a></td><td>" . $row['type'] . "</td><td>" . $row['punishment'] . "</td></tr>";
        }
    }
}





if ($_POST['function'] == 'submitArrest') {

    if (!isset($_POST['infraction'])) {
        echo "Please add a charge!";
        exit();
    }
    
    
    if (!isset($_POST['characterId'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (!doesCivExist($_POST['first'], $_POST['last'])) {
        echo "Civilian does not exist!";
        exit();
    }

    if (isset($_POST['location'])) {
    if ($_POST['location'] == "") {
        echo "Please add a location!";
        exit();
    }}

    if (isset($_POST['fine'])) {
    if ($_POST['fine'] == "") {
        echo "Please add a fine!";
        exit();
    }}
    
    if (isset($_POST['jail'])) {
        if ($_POST['jail'] == "") {
            echo "Please add a jail!";
            exit();
        }
    }

    $characterId = $_POST['characterId'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $plateId = $_POST['plateId'];
    $plate = $_POST['plate'];
    $description = $_POST['description'];
    $infraction = json_encode($_POST['infraction']);
    $location = $_POST['location'];
    $fine = $_POST['fine'];
    $jail = $_POST['jail'];

    $query = "INSERT INTO mdt_arrests (charaterId, first, last, plateId, plate, description, infraction, location, fine, jail, date, officer) VALUES ('$characterId', '$first', '$last', '$plateId', '$plate', '$description', '$infraction', '$location', '$fine', '$jail', '$date', '" . $_SESSION['user_identifier'] . " [" . $_SESSION['departmentName'] . "]')";
    mysqli_query($conn, $query);
}
    
if ($_POST['function'] == 'doesPlateExistArrest') {
    $plate = $_POST['plate'];

    $query = "SELECT * FROM mdt_vehicles WHERE plate='$plate'";
    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single", $row['model'] . ' - ' . $row['description']));
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $response .= "<tr><td><a href=" . '"' . "javascript:searchPlateArrest(" . $row['id'] . ",'" . $row['model'] . ' - ' . $row['description'] . "')" . '"' . ">" . $row['plate'] . '</a></td><td>' . $row['description'] . '</td><td>' . characterName($row['characterID']) . "</td></tr>";
            }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
    }
}

if ($_POST['function'] == 'searchNameArrest') {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $query = "SELECT ownerID, id, first, last, dob FROM mdt_characters WHERE first='$first' AND last='$last'";

    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        if ($result->num_rows === 1) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode(array("single", $row['id']));
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $response .= "<tr><td><a href='javascript:searchNameArrest(" . $row['id'] . ")'>" . $row['first'] . ' ' . $row['last'] . '</a></td><td>' . $row['dob'] . '</td><td>' . ownerName($row['ownerID']) . "</td></tr>";
            }
            $arr = array('multiple', $response);
            echo json_encode($arr);
        }
    } else {
        echo json_encode(array("No Results"));
    }
}

if ($_POST['function'] == 'getPenalArrests') {
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_penal_charges WHERE cat='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td><a href='javascript:arrestAdd(" . $row['id'] . ");'>" . $row['name'] . "</a></td><td>" . $row['type'] . "</td><td>" . $row['punishment'] . "</td></tr>";
        }
    }
}

if ($_POST['function'] == 'plateReport') {
    $type = $_POST['type'];
    $id = $_POST['plate'];
    $query = "SELECT * FROM mdt_$type WHERE plateId='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr id='" . $row['id'] . "' onclick='plateReportDetailed(" . $row['id'] . ")' class='header expand'><th>#" . $row['id'] . "</a></th><th>" . $row['date'] . "</th></tr>";
        }
    }
}

if ($_POST['function'] == 'plateReportDetailed') {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_$type WHERE id='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

        echo "<tr><td colspan='2'>Driver: " . $row['first'] . " " . $row['last'] . "</td></tr><tr><td colspan='2'>Location: " . $row['location'] . "</td></tr><tr colspan='2'><td colspan='2'>Infraction(s): " . substr(str_replace('","', ", ", $row['infraction']), 2, -2) . "</td></tr><tr><td colspan='2'>Fine: $" . $row['fine'] . "</td></tr>";
        if ($type === "Arrests") {echo "<tr><td colspan='2'>Jail Time: " . $row['jail'] . " Seconds </td></tr>";}
        }
    }
}


if ($_POST['function'] == 'nameReport') {
    $type = $_POST['type'];
    $id = $_POST['name'];
    $query = "SELECT * FROM mdt_$type WHERE charaterId='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr id='" . $row['id'] . "' onclick='nameReportDetailed(" . $row['id'] . ")' class='header expand'><th>#" . $row['id'] . "</a></th><th>" . $row['date'] . "</th></tr>";
        }
    }
}

if ($_POST['function'] == 'nameReportDetailed') {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $query = "SELECT * FROM mdt_$type WHERE id='$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

        echo "<tr><td colspan='2'>Person: " . $row['first'] . " " . $row['last'] . "</td></tr><tr><td colspan='2'>Location: " . $row['location'] . "</td></tr><tr colspan='2'><td colspan='2'>Infraction(s): " . substr(str_replace('","', ", ", $row['infraction']), 2, -2) . "</td></tr><tr><td colspan='2'>Fine: $" . $row['fine'] . "</td></tr>";
        if ($type === "Arrests") {echo "<tr><td colspan='2'>Jail Time: " . $row['jail'] . " Seconds </td></tr>";}
        }
    }
}

if ($_POST['function'] == 'createCall') {

    if ($_POST['type'] == "Call Type") {
        echo "Please set call type!";
        exit();
    }
    
    if ($_POST['location'] == "") {
        echo "Please set a location!";
        exit();
    }
    
    if ($_POST['postal'] == "") {
        echo "Please set a postal!";
        exit();
    }
    
    if (!isset($_POST['description'])) {
        echo "Please set description!";
        exit();
    }
    
    $description = $_POST['description'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $postal = $_POST['postal'];

    $query = "INSERT INTO mdt_calls (type, location, details) VALUES ('$type', '$location / Nearest Postal $postal', '$description')";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'getCallDispatch') {
    echo "
            <tr>
                <th>Call #</th>
                <th>Type</th>
                <th colspan='2'>Location</th>
            </tr>
        ";
    
    $query = "SELECT * FROM mdt_calls";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
    echo "
            <tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['type'] . "</td>
                <td>" . $row['location'] . "
                <img src='../img/clear.jpg' onclick='clearCallConfirm(" . $row['id'] . ")'><img src='../img/edit.png' onclick='editCall(" . $row['id'] . ")'></td>
            </tr>
        ";
        }
    }
}

if ($_POST['function'] == 'clearCall') {
    $id = $_POST['id'];
    $query = "DELETE FROM mdt_calls WHERE id='$id'";
    mysqli_query($conn, $query);
    
    $query = "UPDATE mdt_active_users SET callID='0', status='10-8' WHERE callID='$id'";
    mysqli_query($conn, $query);
}


if ($_POST['function'] == 'editCallGet') {
$id = $_POST['id'];
$query = "SELECT * FROM mdt_calls WHERE id='$id'";

$result = $conn->query($query);
if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo json_encode(array($row['location'], $row['details'], $row['type']));
        }
    }    
}

if ($_POST['function'] == 'editCall') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    
    $query = "UPDATE mdt_calls SET type='$type', location='$location', details='$description' WHERE id='$id'";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'getUnits') {
    echo "
            <tr>
                <th>Unit #</th>
                <th>Type</th>
                <th>Status</th>
                <th>Call</th>
            </tr>
        ";

    $query = "SELECT * FROM mdt_active_users WHERE NOT department='Dispatch' AND NOT logout='1'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>" . $row['identifier'] . "<br>" . ownerName($row['user_id']) . "</td>
                <td>" . $row['department'] . " - " . $row['divison'] . "</t>
                <td>
                <select id='" . $row['user_id'] . "Status' onchange='changeStatus(" . $row['user_id'] . ", this.value)'>
                    <option>10-6</option>
                    <option>10-7</option>
                    <option>10-8</option>
                    <option>10-15</option>
                    <option>10-23</option>
                    <option>10-97</option>
                    <option>10-42</option>
                </selcect>
                <script>$('#" . $row['user_id'] . "Status').val('" . $row['status'] . "');</script>
                </td>
                <td>
                <select id='" . $row['user_id'] . "Call' onchange='changeCall(" . $row['user_id'] . ", this.value)'>
                    <option value='0'>None</option>
                    " . getCallNames() . "
                </select>
                <script>$('#" . $row['user_id'] . "Call').val('" . $row['callID'] . "');</script>
                <img src='../img/clear.jpg' onclick=" . '"' . "logoutUserConfirm(" . $row['user_id'] . ",'" . $row['identifier'] . " " . ownerName($row['user_id']) . "')" . '"' . ">
                </td>
            </tr>
        ";
        }
    }
}

function getCallNames() {
    include('../settings.php');
    $query = "SELECT * FROM mdt_calls";

    $result = $conn->query($query);
    global $response;
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $response .= "<option value='" . $row['id'] . "'>#" . $row['id'] . " - " . $row['type'] . "</option>";
        }
    }
    return $response;
}

if ($_POST['function'] == 'changeStatus') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    $query = "UPDATE mdt_active_users SET status='$status' WHERE user_id='$id'";
    mysqli_query($conn, $query);
    
    if ($status == "10-8") {
        $query = "UPDATE mdt_active_users SET callID='0' WHERE user_id='$id'";
        mysqli_query($conn, $query);
        echo 'yo mama';
    }
}

if ($_POST['function'] == 'changeCall') {
    $id = $_POST['id'];
    $call = $_POST['call'];

    $query = "UPDATE mdt_active_users SET callID='$call', status='10-6' WHERE user_id='$id'";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'logoutUser') {
    $id = $_POST['id'];

    $query = "UPDATE mdt_active_users SET logout='1' WHERE user_id='$id'";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'signal') {
    $server = $_SESSION['user_server'];
    if (sig100() == 0) {

    $query = "UPDATE mdt_server SET emergency='1' WHERE id='$server'";
    mysqli_query($conn, $query);
    } else {
        
        $query = "UPDATE mdt_server SET emergency='0' WHERE id='$server'";
        mysqli_query($conn, $query);
    }
}

function sig100() {
    include('../settings.php');
    $query = "SELECT * FROM mdt_server";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            return $row['emergency'];
        }
    }
}

if ($_POST['function'] == 'getSignal') {
    echo sig100();
}

if ($_POST['function'] == 'doesSteamExist') {
    $steam = $_POST['steam'];
    echo 'yo mama';
    
    $query = "SELECT * FROM mdt_users WHERE steam='$steam'";

    $result = $conn->query($query);
    if ($result->num_rows > 1) {
        while ($row = $result->fetch_assoc()) {
        echo $row['user_id'];
        }
    }
}

if ($_POST['function'] == 'getServers') {
    $query = "SELECT id,ip,port FROM mdt_server";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<div style='display:none;'>";
            $content = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/info.json"), true);
            echo "</div>";
            echo "<div class='serverStatusBox'>";
            echo "<div class='serverStatusTitle'>Server " . $row['id'] . "</div>";
            echo "<div class='serverStatusStatus' style='color: "; if ($content) {echo "green";} else {echo "red";} echo ";'>"; if (!$content) {echo "Offline</div></div>"; } elseif (!$content['vars']['Uptime']) {echo "Starting...</div></div>";} else {echo "Online</div>";
            $gta5_players = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/players.json"), true);
            echo "<div class='serverStatusInfo'>" . count($gta5_players) . "/" . $content['vars']['sv_maxClients'] . " Players</div>";
            echo "<div class='serverStatusInfo'>Uptime " . $content['vars']['Uptime'] . "</div>";
            echo "<button onclick='selectServer(" . $row['id'] . ");'>View</button>";
            echo "</div>";}
    }
}
}

function getIPFromID($id,$conn) {
    
    $query = "SELECT ip,port FROM mdt_server WHERE id=$id";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $ip = $row['ip'];
            $port = $row['port'];
            return array("ip"=>"$ip","port"=>"$port");
        }
    }
}

if ($_POST['function'] == 'getServerPlayerTable') {
    $row = getIPFromID($_POST['id'],$conn);
    $sid = $_POST['id'];
    $json = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/players.json"), true);
    $n = -1;
    echo "<tr><th>Id</th><th>Name</th><th>Options</th>";
    foreach($json as $a) {
        $n = $n+1;
        echo "<tr><td>" . $json[$n]['id'] . "</td><td>" . $json[$n]['name'] . "</td><td><a onclick='kickConfirm($sid, " . $json[$n]['id'] . ")'>Kick</a><a onclick='banConfirm($sid, " . $json[$n]['id'] . ")'>Ban</a></td></tr>";
    }
}

if ($_POST['function'] == 'getServerInfo') {
    $row = getIPFromID($_POST['id'],$conn);
    $content = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/info.json"), true);
    $gta5_players = json_decode(file_get_contents("http://" . $row['ip'] . ":" . $row['port'] . "/players.json"), true);
    echo json_encode(array($content['vars']['Uptime'],count($gta5_players) . "/" . $content['vars']['sv_maxClients']));
}

if ($_POST['function'] == 'rcon') {
    $fConn = getIPFromID($_POST['server'],$conn);
    $rcon = new q3query($fConn['ip'], $fConn['port'], $success);
    $rcon->setRconpassword($rconPW);
    $rcon->rcon($_POST['command']); 
    unset($rcon);
}

if ($_POST['function'] == 'genLogin') {
    $str = rand();
    $result = md5($str);
    echo $result;  
    
    $query = "INSERT INTO mdt_users (code) VALUES ('$result')";
    mysqli_query($conn, $query);
}

if ($_POST['function'] == 'removeLogin') {
    $query = "DELETE FROM mdt_users where code<>'(NULL)'";
    mysqli_query($conn, $query);
    echo mysqli_affected_rows($conn) . " login codes deleted.";
}

if ($_POST['function'] == 'getUsersTable') {
    $query = "SELECT name,email,suspend,user_id,admin FROM mdt_users";

    $result = $conn->query($query);
    echo "<tr><th>Name</th><th>Options</th>";
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td ";
            if ($row['admin'] > 0) {
                echo "style='color:red;'";
            }
            echo ">" . $row['name'] ."</td><td>";
            if ($row['suspend'] == 1) {
                echo "<a onclick='userSuspend(" . $row['user_id'] . ",0)'>Unsuspend</a>";
            } else {
                echo "<a onclick='userSuspendConfirm(" . $row['user_id'] . ")'>Suspend</a>";
            }
            echo "<a href='mailto: " . $row['email'] . "' target='_blank'>Email</a></td>";
        }
    }
}

if ($_POST['function'] == 'userSuspend') {
    
    $query = "UPDATE mdt_users SET suspend='" . $_POST['suspend'] ."' WHERE user_id='" . $_POST['id'] ."'";

    mysqli_query($conn, $query);
}