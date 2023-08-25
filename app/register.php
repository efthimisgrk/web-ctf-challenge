<?php

require_once('db.php');
require_once('utils.php');

// Check email
function chechEmail($conn, $email){
    // Check if the email is used by another user
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        return false;
    } else {
        return true;
    }
}

// Register
function registerEmail($conn, $email, $password){
    // Insert the new user into the database
    $query = "INSERT INTO users (email, password, role) VALUES (?, ?, 'user')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the password is empty
    if (!isset($password) || $password === ''){
        $error = "password cannot be empty";
    } else {
        // Register implementation
        if (!validate($email)) {
            $error = "invalid email format";
        }else{
            if (!chechEmail($conn, $email)){
                $error = "email is used";
            } else {
                // TODO Hash the password
                //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                if (registerEmail($conn, $email, $password)){
                    $error = "user registered";
                    header("Location: login.php");
                } else {
                    $error = "try again";
                }
            }
        }
    }  
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="static/css/style.css">
</head>
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
    <input type="submit" value="Register">
</form>

<div class=register>
    <p>or login <a href="/login.php">here</a>.</p>
</div>

</body>
</html>