<?php
    // include file
    include_once ("../configuration/database.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = (string) $_GET['uuid'];
    } else {
        header("location: " . site_url . "user/manage_record.phh");
        exit();
    }
    // check if submit button was clicked
    if (isset($_POST['delete_record'])) {
        // send email notification
        include_once ("../smtp/mail.php");
        // redirect to delete otp page
        header("location: " . site_url . "authentication/delete_password_otp.php?uuid=" . $gotten_uuid);
        exit();
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "user/delete_record.php");
        exit();
    }
    