<?php
    // include file
    include_once ("../configuration/database.php");
    // unset & destroy session
    session_unset();
    session_destroy();
    header("location: " . site_url . "authentication/signin.php");
    exit();
?>