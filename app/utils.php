<?php

//Get IP address
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else
            return $_SERVER['REMOTE_ADDR'];
}

//TODO Check IP
function checkIP($ip){
    return true;
}

//TODO Log IP
function logIPAttempt($ip){
    return true;
}

//Email validator
function validate($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) < 40){
        return true;
    } else {
        return false;
    }
}

?>