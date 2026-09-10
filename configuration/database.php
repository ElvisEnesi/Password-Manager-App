<?php
    // database file
    // start session if session isn't set
    if (session_status() === PHP_SESSION_NONE) {
        // start session
        session_start();
    }
    // show errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // declare constant url for site
    define("site_url", "http://localhost:3000/");
    // set default timezone
    date_default_timezone_set('Africa/Lagos');
    // declare db variables
    $server = "localhost";
    $username = "elvis";
    $db_password = "ElvisSecure2026!";
    $db_name = "password_manager";
    // connect database
    $connection = new mysqli($server, $username, $db_password, $db_name);
    // check for successful connection
    if (mysqli_errno($connection)) {
        die("connection failed: " . mysqli_error($connection));
    } else {
        // echo "Connected successfully!!";
    }
    