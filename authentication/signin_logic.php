<?php
    // handle errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // include file
    include_once ("../configuration/database.php");
    // check if submit button was clicked  
    if (isset($_POST['submit'])) {
        // declare variables
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $key = filter_var($_POST['key'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validate inputs
        if (!$key || !$email) {
            $_SESSION['signin'] = "Fill all fields!!";
        } else {
            // check if staff id exists
            $check = mysqli_prepare($connection, "SELECT uuid, first_name, last_name, hashed_email, password, is_admin 
            FROM user WHERE hashed_email = ? LIMIT 1");
            // decrypt email before checking
            $email = hash("sha256", $email);
            mysqli_stmt_bind_param($check, "s", $email);
            mysqli_stmt_execute($check);
            // get data as an associate array
            $checked_result = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
            // check if role exists in our database
            if (mysqli_stmt_affected_rows($check) == 1) {
                $data_key = $checked_result['password'];
                // verify password
                if (password_verify($key, $data_key)) {
                    // set session to control login access
                    $_SESSION['uuid'] = $checked_result['uuid'];
                    $_SESSION['full_name'] = $checked_result['first_name'] . " " . $checked_result['last_name'];
                    if ($checked_result['is_admin'] == 1) {
                        $_SESSION['user_is_admin'] = true;
                    } else {
                        $_SESSION['user_is_admin'] = false;
                    }
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
                    $_SESSION['signin'] = "Incorrect password!!";
                }
            } else {
                $_SESSION['signin'] = "User not found!!";
            }
            mysqli_stmt_free_result($check);
            mysqli_stmt_close($check);
        }
        // redirect if there's any error
        if (isset($_SESSION['signin'])) {
            header("location: " . site_url . "authentication/signin.php");
            exit();
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url . "authentication/signin.php");
        exit();
    }
    