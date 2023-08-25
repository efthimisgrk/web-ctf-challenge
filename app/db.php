<?php

$db_host = 'localhost';
$db_user = 'app';
$db_password = 'l5FP^6g3an9s';
$db_name = 'db';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>