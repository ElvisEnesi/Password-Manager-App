<?php
    // error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // include files
    require_once __DIR__ . '/../configuration/database.php';
    require_once __DIR__ . '/../encryption/encryption.php';
    require_once __DIR__ . '/../security/ip.php';
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.venv');
    $dotenv->load();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // require 'PHPMailer/src/PHPMailer.php';
    // require 'PHPMailer/src/SMTP.php';
    // require 'PHPMailer/src/Exception.php';
    // get user details
    $user_uuid = $_SESSION['uuid'];
    // create a random encrypted OTP
    $otp = encrypt(random_int(100000, 999999));
    // check if otp exists in the database
    $check_otp = "SELECT user_uuid, otp FROM otp WHERE user_uuid = ?";
    $check_stmt = mysqli_prepare($connection, $check_otp);
    mysqli_stmt_bind_param($check_stmt, "s", $user_uuid);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    if (mysqli_num_rows($check_result) > 0) {
        // update existing OTP
        $delete_otp = "DELETE FROM otp WHERE user_uuid = ?";
        $delete_stmt = mysqli_prepare($connection, $delete_otp);
        mysqli_stmt_bind_param($delete_stmt, "s", $user_uuid);
        mysqli_stmt_execute($delete_stmt);
    } 
    // insert new OTP
    $insert_otp = "INSERT INTO otp (user_uuid, otp, otp_ip_address ) VALUES (?, ?, ?)";
    $insert_otp_stmt = mysqli_prepare($connection, $insert_otp);
    mysqli_stmt_bind_param($insert_otp_stmt, "sss", $user_uuid, $otp, $user_ip);
    mysqli_stmt_execute($insert_otp_stmt);
    // get user email
    $get_email = "SELECT email FROM user WHERE uuid = ?";
    $get_email_stmt = mysqli_prepare($connection, $get_email);
    mysqli_stmt_bind_param($get_email_stmt, "s", $user_uuid);
    mysqli_stmt_execute($get_email_stmt);
    $email_result = mysqli_stmt_get_result($get_email_stmt);
    if (mysqli_num_rows($email_result) > 0) {
        $row = mysqli_fetch_assoc($email_result);
        $user_email = decrypt($row['email']);
    } else {
        // handle error if user not found
        die("User not found.");
    }
    // decrypt the OTP for sending in email
    $decrypted_otp = decrypt($otp);

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
        $mail->Port = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = 'Your login OTP';
        $mail->Body = 'Your OTP is '. $decrypted_otp . '. Do not share this with anyone.';

        $mail->send();
        // echo "Email sent";

    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
// var_dump($row['email']);
// var_dump($user_email);