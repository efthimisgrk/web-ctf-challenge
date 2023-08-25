<?php

require_once('db.php');
require_once('utils.php');

session_start();

//Find user by email
function findUser($conn, $email){
    $sql = "SELECT id FROM users WHERE email = '" . urldecode($email) . "';";
    $result = mysqli_query($conn, $sql);
      
    // Make sure the query returns exactly one result
    if ($result && mysqli_num_rows($result) == 1){   
        $obj = $result->fetch_object();         
        $id = $obj->id;
    }else{
        $id = null;
    }

    return $id;
}

// Check password 
function checkPassword($conn, $email, $password){
    // Query the password of the user 
    $query = "SELECT password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbPassword);
    mysqli_stmt_fetch($stmt);

    // Verify password
    if ($password === $dbPassword) {        
        return true;
    } else {
        return false;
    }
}

function findRoleById($conn, $id){
    // Get the user role 
    $query = "SELECT role FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $role);
    mysqli_stmt_fetch($stmt);

    return $role;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //TODO Implement IP blacklist
    
    // Login implementation
    if (!validate($email)) {
        $error = "invalid email format";
    }else{
        // Check if the user exists in the DB
        $id = findUser($conn, $email);
        if (!isset($id)){           
            // User not found
            $error = "user not found";
        } else {
            if (checkPassword($conn, $email, $password)){
                // Login successful
                $_SESSION['loggedin'] = true;
                $_SESSION['role'] = findRoleById($conn, $id);
                header("Location: flag.php");
            } else {
                // Login failed
                $error = "invalid password";
            }
        }
    }
    
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="static/css/style.css">
</head>

<body>

<div class="error-message">
    <?php
    if (isset($error)) {
        echo '<p>' . $error . '</p>';
    }
    ?>
</div>

<form method="POST">
    <label for="email">email</label><br>
    <input type="text" name="email"><br>
    <label for="password">password</label><br>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<div class=register>
    <p>or register <a href="/register.php">here</a>.</p>
</div>

</body>
</html>