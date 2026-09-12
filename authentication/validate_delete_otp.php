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
    if (isset($_POST['validate_delete'])) {
        // declare variables
        $otp = (int) $_POST['otp'];
        // validate inputs
        if (!$otp) {
            $_SESSION['delete_record'] = "Fill all fields!!";
        } elseif (!is_numeric($otp)) {
            $_SESSION['delete_record'] = "Invalid OTP format!!";
        } else {
            // retrive OTP
            $check_otp = "SELECT user_uuid, otp FROM otp WHERE user_uuid = ? LIMIT 1";
            // send prepared statement
            $check_stmt = mysqli_prepare($connection, $check_otp);
            // bind parameters
            mysqli_stmt_bind_param($check_stmt, "s", $_SESSION['uuid']);
            // execute statement
            mysqli_stmt_execute($check_stmt);
            // get results
            $check_result = mysqli_stmt_get_result($check_stmt);
            // check if there's available record
            if (mysqli_num_rows($check_result) > 0) {
                // convert record into associate array
                $row = mysqli_fetch_assoc($check_result);
                // declare decrypted otp
                $decrypted_otp = decrypt($row['otp']);
                // check if otp is valid
                if ($decrypted_otp == $otp) {
                    // delete record
                    $delete = mysqli_prepare($connection, "DELETE FROM password WHERE password_uuid = ? AND user_uuid = ? LIMIT 1");
                    // bind parameters
                    mysqli_stmt_bind_param($delete, "ss", $gotten_uuid, $_SESSION['uuid']);
                    // execute statement
                    mysqli_stmt_execute($delete);
                    // check if data was deleted
                    if (mysqli_stmt_affected_rows($delete) > 0) {
                        // set session delete message
                        $_SESSION['delete_record'] = "Record deleted successfully!!";
                        if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                            header("location: " . site_url . "admin/manage_record.php");
                            exit();
                        } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                            header("location: " . site_url . "user/manage_record.php");
                            exit();
                        } else {
                            // handle error if user role is not set
                            die("User role not set.");
                        }
                    } else {
                        // set session delete message
                        $_SESSION['delete_record'] = "Couldn't delete record!!";
                        if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                            header("location: " . site_url . "admin/manage_record.php");
                            exit();
                        } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                            header("location: " . site_url . "user/manage_record.php");
                            exit();
                        } else {
                            // handle error if user role is not set
                            die("User role not set.");
                        }
                    }
                } else {
                    // message for invalid otp
                    $_SESSION['delete_record'] = "Invalid OTP!!";
                }
            } else {
                // handle error if otp not found
                die("OTP not found.");
            }
            // free results
            mysqli_stmt_free_result($check_stmt);
            // close stmt
            mysqli_stmt_close($check_stmt);
        }
        // redirect if there's any error
        if (isset($_SESSION['delete_record'])) {
            header("location: " . site_url . "authentication/edit_password_otp.php");
            exit();
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "authentication/edit_password_otp.php");
        exit();
    }

    