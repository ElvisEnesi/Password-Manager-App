<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // check if submit button was clicked
    if (isset($_POST['add_password'])) {
        // declare variables
        $social = (string) $_POST['social'];
        $user_name = (string) $_POST['username'];
        $cr_p = (string) $_POST['cr_p'];
        $co_p = (string) $_POST['co_p'];
        // validate inputs
        if (!$social || !$user_name || !$cr_p || !$co_p) {
            $_SESSION['add_record'] = "Fill all fields!!";
        } elseif ($cr_p !== $co_p) {
            $_SESSION['add_record'] = "Passwords do not match!!";
        }
        // redirect if there's any error
        if (isset($_SESSION['add_record'])) {
            header("location: ". site_url . "admin/add_record.php");
            exit();
        } else {
            // generate uuid
            function generate_uuidv4() {
                $data = random_bytes(16);
                // Set version to 0100 (Version 4)
                $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
                // Set bits 6-7 to 10 (Variant 1)
                $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

                return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            }
            $uuid = generate_uuidv4(); 
            // encrypt password
            $encrypted_password = encrypt($co_p);
            // insert into database 
            $insert = mysqli_prepare($connection, "INSERT INTO password (password_uuid, user_uuid, app, username, password) VALUES (?,?,?,?,?)");
            // bind parameters
            mysqli_stmt_bind_param($insert, "sssss", $uuid, $_SESSION['uuid'], $social, $user_name, $encrypted_password);
            // execute statement
            mysqli_stmt_execute($insert);
            // check if data was inserted
            if (mysqli_stmt_affected_rows($insert) > 0) {
                // redirect to password page with success message
                $_SESSION['add_record'] = "Password added successful!!";
                header("location: " . site_url . "admin/index.php");
                exit();
            } else {
                // redirect to password page with success message
                $_SESSION['add_record'] = "Failed to add password!!";
                header("location: " . site_url . "admin/index.php");
                exit();
            }
            // free results
            mysqli_stmt_free_result($insert);
            // close stmt
            mysqli_stmt_close($insert);
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "admin/add_record.php");
        exit();
    }
    