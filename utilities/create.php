<?php
include('../settings.php');
if(!empty($_POST)) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['pass']);
    $password2 = htmlspecialchars($_POST['pass2']);
    $code = htmlspecialchars($_POST['code']);
    
    if ($password != $password2) {
        session_start();
        $_SESSION['loginError'] = "Passwords Don't Match $password - $password2";
        header("Location:$defaultURL/create.php");
        exit();
    }
    
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    if ($enableCode == true) {
        $query = "SELECT * FROM mdt_users WHERE code='$code'";

        $result = $conn->query($query);

        if (!$result->num_rows > 0) {
            session_start();
            $_SESSION['loginError'] =  "Account Code Does Not Exist";
            header("Location:$defaultURL/create.php");
            exit();
        } else {    
            $query = "UPDATE mdt_users SET name='$name', email='$email', password='$password', code='(NULL)' WHERE code='$code'";

            mysqli_query($conn, $query);
            header("Location:$defaultURL/index.php");
            exit();
        }
    }

$query = "INSERT INTO mdt_users (name, email, password, code) VALUES ('$name', '$email', '$password', '(NULL)')";

mysqli_query($conn, $query);
header("Location:$defaultURL/index.php");
exit();
}