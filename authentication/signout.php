<?php
    // include file
    include_once ("../configuration/database.php");
    // unset & destroy session
    session_unset();
    session_destroy();
    // redirect to sign in page
    header("location: " . site_url . "authentication/signin.php");
    exit();
?>