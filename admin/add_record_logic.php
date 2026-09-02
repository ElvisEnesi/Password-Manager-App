<?php
    // include file
    include_once ("database.php");
    // check if submit button was clicked
    if (isset($_POST['submit'])) {
        // declare variables
        $social = filter_var($_POST['social'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cr_p = filter_var($_POST['cr_p'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $co_p = filter_var($_POST['co_p'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validate inputs
        if (!$social || !$cr_p || !$co_p) {
            $_SESSION['add_record'] = "Fill all fields!!";
        } elseif ($cr_p !== $co_p) {
            $_SESSION['add_record'] = "Passwords do not match!!";
        }
        // redirect if there's any error
        if (isset($_SESSION['add_record'])) {
            header("location: add_record.php");
            exit();
        } else {
            // insert into database
            $insert = mysqli_prepare($connection, "INSERT INTO password (user_id, app, password) VALUES (?,?,?)");
            mysqli_stmt_bind_param($insert, "iss", $_SESSION['user_id'], $social, $co_p);
            mysqli_stmt_execute($insert);
            if (mysqli_stmt_affected_rows($insert) > 0) {
                // redirect to password page with success message
                $_SESSION['add_record'] = "Password added successful!!";
                header("location: manage_record.php");
                exit();
            } else {
                // redirect to password page with success message
                $_SESSION['add_record'] = "Failed to add password!!";
                header("location: add_record.php");
                exit();
            }
            mysqli_stmt_close($insert);
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: add_record.php");
        exit();
    }
    