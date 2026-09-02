<?php
    // error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // include file
    include_once ("../configuration/database.php");
    include_once ("../encryption/encryption.php");
    // check if submit button was clicked
    if (isset($_POST['submit'])) {
        // declare variables
        $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cr_p = filter_var($_POST['cr_p'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $co_p = filter_var($_POST['co_p'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $profile = $_FILES['profile'];
        // check if user id exists
        $check = mysqli_prepare($connection, "SELECT email FROM user WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $checked_result = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        if (mysqli_stmt_affected_rows($check) > 0) {
            // redirect back with a session message
            $_SESSION['signup'] = "Email already taken!!";
            header("location: " . site_url ."authentication/signup.php");
            exit();
        }
        mysqli_stmt_free_result($check);
        mysqli_stmt_close($check);
        // validate inputs if email doesn't exist
        if (!$first_name || !$last_name || !$email || !$cr_p || !$co_p || !$profile['name']) {
            $_SESSION['signup'] = "Fill all fields!!";
        } elseif ($cr_p !== $co_p) {
            $_SESSION['signup'] = "Passwords do not match!!";
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

            // encrypt email
            $encrypted_email = encrypt($email);
            $hashed_email = hash("sha256", $email);
            // hash password for database storage
            $hashed_password = password_hash($cr_p, PASSWORD_DEFAULT);
            // validate image
            $image_name = $profile['name']. "_" . time(); // make image unique by appending timestamp
            $image_tmp_name = $profile['tmp_name'];
            // where image will be stored
            $image_destination_path = "../images/users/" . $image_name;
            // validate extension
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $image_tmp_name);
            // allowed file formats
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            // check if file is in accepted array
            if (in_array($mime_type, $allowed_types)) {
                // check file size
                if ($profile['size'] > 3_000_000) {
                    $_SESSION['signup'] = "File size exceeds limit!!";
                }
            } else {
                $_SESSION['signup'] = "File type not allowed!!";
            }
        }
        // redirect if there's any error
        if (isset($_SESSION['signup'])) {
            header("location: " . site_url ."authentication/signup.php");
            exit();
        } else {
            // insert into database
            $insert = mysqli_prepare($connection, "INSERT INTO user (uuid, first_name, last_name, email, hashed_email, password, picture) VALUES (?, ?,?,?,?,?,?)");
            mysqli_stmt_bind_param($insert, "sssssss", $uuid, $first_name, $last_name, $encrypted_email, $hashed_email, $hashed_password, $image_name);
            mysqli_stmt_execute($insert);
            if (mysqli_stmt_affected_rows($insert) > 0) {
                // redirect to signin page with success message
                $_SESSION['signup'] = "Registration successful, login!!";
                header("location: " . site_url ."authentication/signin.php");
                exit();
            } else {
                // redirect to signin page with success message
                $_SESSION['signup'] = "Registration failed, try again!!";
                header("location: " . site_url ."authentication/signup.php");
                exit();
            }
            mysqli_stmt_close($insert);
        }
    } else {
        // redirect back if it wasn't clicked
        header("location: " . site_url ."authentication/signup.php");
        exit();
    }
    