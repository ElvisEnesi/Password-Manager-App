<?php
    // include file
    include_once ("../configuration/database.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = (string) $_GET['uuid'];
    } else {
        header("location: " . site_url . "admin/manage_record.phh");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | OTP</title>
    <link rel="stylesheet" href="<?= site_url ?>/css/style.css">
</head>
<body>
    <div class="form_container">
        <form action="<?= site_url ?>authentication/validate_edit_otp.php?uuid=<?= htmlspecialchars($gotten_uuid, ENT_QUOTES, "UTF-8") ?>" method="post">
            <h3>Insert OTP</h3>
            <input type="number" name="otp" placeholder="OTP">
            <button type="submit" name="validate_edit">Proceed</button>
        </form>
    </div>
</body>
</html>
