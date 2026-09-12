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
    if (isset($_POST['validate_edit'])) {
        // declare variables
        $otp = (int) $_POST['otp'];
        // validate inputs
        if (!$otp) {
            $_SESSION['edit_record'] = "Fill all fields!!";
        } elseif (!is_numeric($otp)) {
            $_SESSION['edit_record'] = "Invalid OTP format!!";
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
                if ($decrypted_otp == $otp) {
                    // retrive json data
                    $select_json = mysqli_prepare($connection, "SELECT password_uuid, json_data FROM json_Password 
                    WHERE password_uuid = ? ORDER BY id DESC LIMIT 1");
                    // bind parameters
                    mysqli_stmt_bind_param($select_json, "s", $gotten_uuid);
                    // execute statement
                    mysqli_stmt_execute($select_json);
                    // get results
                    $json_results = mysqli_stmt_get_result($select_json);
                    // check if there's any data available
                    if (mysqli_num_rows($json_results) > 0) {
                        // retrive data
                        $json = mysqli_fetch_assoc($json_results);
                        $json_data = $json['json_data'];
                        // decode data
                        $user = json_decode($json_data, true);
                        // update password
                        $update = mysqli_prepare($connection, "UPDATE password SET app = ?, username = ?, password = ? WHERE password_uuid = ? AND user_uuid = ? LIMIT 1");
                        // bind parameters
                        mysqli_stmt_bind_param($update, "sssss", $user['app'], $user['username'], $user['encrypted_password'], $gotten_uuid, $_SESSION['uuid']);
                        // execute statement
                        mysqli_stmt_execute($update);
                        // check if it was updated
                        if (mysqli_stmt_affected_rows($update) > 0) {
                            // set session message
                            $_SESSION['edit_record'] = "Details updated successfully!!";
                            // check logged in user's role
                            if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                                // redirect to record page for admin
                                header("location: " . site_url . "admin/manage_record.php");
                                exit();
                            } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                                // redirect to record page for user
                                header("location: " . site_url . "user/manage_record.php");
                                exit();
                            } else {
                                // handle error if user role is not set
                                die("User role not set.");
                            }
                        } else {
                            // set session message
                            $_SESSION['edit_record'] = "Couldn't update record!!";
                            // check logged in user's role
                            if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                                // redirect to record page for admin
                                header("location: " . site_url . "admin/manage_record.php");
                                exit();
                            } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                                // redirect to record page for user
                                header("location: " . site_url . "user/manage_record.php");
                                exit();
                            } else {
                                // handle error if user role is not set
                                die("User role not set.");
                            }
                        }
                    } else {
                        // check logged in user's role
                        if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                            // redirect to record page for admin
                            header("location: " . site_url . "admin/manage_record.php");
                            exit();
                        } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                            // redirect to record page for user
                            header("location: " . site_url . "user/manage_record.php");
                            exit();
                        } else {
                            // handle error if user role is not set
                            die("User role not set.");
                        }
                    }
                    // free result
                    mysqli_stmt_free_result($select_json);
                    // close stmt
                    mysqli_stmt_close($select_json);
                } else {
                    // message for invalid otp
                    $_SESSION['edit_record'] = "Invalid OTP!!";
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
        if (isset($_SESSION['edit_record'])) {
            header("location: " . site_url . "authentication/edit_password_otp.php");
            exit();
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "authentication/edit_password_otp.php");
        exit();
    }

    