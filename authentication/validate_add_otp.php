<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // decrypt the OTP for sending in email
    if (isset($_POST['submit'])) {
        // declare variables
        $otp = filter_var($_POST['otp'], FILTER_SANITIZE_NUMBER_INT);
        // validate inputs
        if (!$otp) {
            $_SESSION['signin'] = "Fill all fields!!";
        }elseif (!is_numeric($otp)) {
            $_SESSION['signin'] = "Invalid OTP format!!";
        } else {
            // get user details
            $user_uuid = $_SESSION['uuid'];
            // create a random encrypted OTP
            $check_otp = "SELECT user_uuid, otp FROM otp WHERE user_uuid = ?";
            $check_stmt = mysqli_prepare($connection, $check_otp);
            mysqli_stmt_bind_param($check_stmt, "s", $user_uuid);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            if (mysqli_num_rows($check_result) > 0) {
                $row = mysqli_fetch_assoc($check_result);
                $decrypted_otp = decrypt($row['otp']);
                if ($decrypted_otp == $otp) {
                    if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === true) {
                        header("location: " . site_url . "admin/index.php");
                        exit();
                    } else if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] === false) {
                        header("location: " . site_url . "user/index.php");
                        exit();
                    } else {
                        // handle error if user role is not set
                        die("User role not set.");
                    }
                } else {
                    $_SESSION['signin'] = "Invalid OTP!!";
                }
            } else {
                // handle error if otp not found
                die("OTP not found.");
            }
            mysqli_stmt_free_result($check_stmt);
            mysqli_stmt_close($check_stmt);
        }
        // redirect if there's any error
        if (isset($_SESSION['signin'])) {
            header("location: " . site_url . "authentication/add_password_otp.php");
            exit();
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "authentication/add_password_otp.php");
        exit();
    }

    