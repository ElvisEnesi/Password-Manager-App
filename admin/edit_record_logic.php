<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = (string) $_GET['uuid'];
    } else {
        header("location: " . site_url . "admin/manage_record.phh");
        exit();
    }
    // check if submit button was clicked
    if (isset($_POST['edit_record'])) {
        // declare variables
        $social = (string) $_POST['social'];
        $user_name = (string) $_POST['username'];
        $cr_p = (string) $_POST['cr_p'];
        $co_p = (string) $_POST['co_p'];
        // validate inputs
        if (!$social || !$user_name || !$cr_p || !$co_p) {
            $_SESSION['edit_record'] = "Fill all fields!!";
        } elseif ($cr_p !== $co_p) {
            $_SESSION['edit_record'] = "Passwords do not match!!";
        }
        // redirect if there's any error
        if (isset($_SESSION['edit_record'])) {
            header("location: ". site_url . "admin/edit_record.php");
            exit();
        } else {
            // encrypt password
            $encrypted_password = encrypt($co_p);
            // declare user's data to encode
            $user = [
                "app" => $social,
                "username" => $user_name,
                "encrypted_password" => $encrypted_password
            ];
            // encode data for transfer
            $json_data = json_encode($user);
            // insert json data into database
            $insert = mysqli_prepare($connection, "INSERT INTO json_Password (password_uuid, json_data) VALUES (?,?)");
            // bind parameters
            mysqli_stmt_bind_param($insert, "ss", $gotten_uuid, $json_data);
            // execute statement
            mysqli_stmt_execute($insert);
            // send email notification
            include_once ("../smtp/mail.php");
            // check if data was inserted
            if (mysqli_stmt_affected_rows($insert) > 0) {
                // redirect to password otp page
                header("location: " . site_url . "authentication/edit_password_otp.php?uuid=" . $gotten_uuid);
                exit();
            } else {
                // redirect to password page
                header("location: " . site_url . "admin/manage_record.php");
                exit();
            }
            // free results
            mysqli_stmt_free_result($insert);
            // close stmt
            mysqli_stmt_close($insert);
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "admin/edit_record.php");
        exit();
    }
    