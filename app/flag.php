<?php

session_start();

// Unauthenticated users 
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// Authenticated users
$flag = "Only admins can see the flag!";

// Check if user is admin
if ($_SESSION['role'] === 'admin'){
    $flag = "CTF{f4k3_fl4g_4_t3st1ng}";
}

echo $flag;

?>
