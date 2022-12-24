<?php
include('../settings.php');
if(!empty($_POST)) {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['pass']);
        $serverNumber = htmlspecialchars($_POST['server']);

    $query = "SELECT user_id, name, password, admin, steam FROM mdt_users WHERE email = ?";
        
        try {
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            $result = mysqli_stmt_execute($stmt);

            if ($result == FALSE) {
                die(mysqli_error($conn));
            }
        }
        catch (Exception $e)
        {
            die("Failed to run query: " . $e->getMessage());
        }
        
        mysqli_stmt_bind_result($stmt, $user_id, $name, $fetchedPassword, $admin, $steam);
        mysqli_stmt_fetch($stmt);
        
        $login_ok = false;
        
    if (password_verify($password, $fetchedPassword)) {
        $login_ok = true;
    } else {
        session_start();
        $_SESSION['loginError'] = 'Invalid credentials';
        header("Location:$defaultURL/index.php");
        exit();
    }
    
    if ($serverNumber == Server) {
        session_start();
        $_SESSION['loginError'] = 'Please select a server';
        header("Location:$defaultURL/index.php");
        exit();
    }
    
        session_start();
        $_SESSION['user_loggedIn'] = 'logged in';
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_admin'] = $admin;
        $_SESSION['user_server'] = $serverNumber;
        if ($steam == 0) {
            header("Location:$defaultURL?link=1");
            exit();
        }
        header("Location:$defaultURL/dashboard.php");
}

?>