<?php
    // include files
    include_once ("../configuration/database.php");
    include_once ("../security/ip.php");
    // check for logged in user
    if (!isset($_SESSION['uuid'])) {
        // log in user's ip address to activity log
        $activity = mysqli_prepare($connection, "INSERT INTO activity_log (ip_address, type) VALUES(?,?)");
        // declare login status
        $type = "User not logged in!!";
        // bind parameters
        mysqli_stmt_bind_param($activity, "ss", $user_ip, $type);
        // execute statement
        mysqli_stmt_execute($activity);
        // free results
        mysqli_stmt_free_result($activity);
        // close stmt
        mysqli_stmt_close($activity);
        // redirect user to login page with session message
        $_SESSION['signin'] = "Login to access data!!";
        header("location: " . site_url . "authentication/signin.php");
        exit();
    }
    