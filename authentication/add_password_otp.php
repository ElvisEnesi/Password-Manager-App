<?php
    // include file
    include_once ("../configuration/database.php");
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
        <?php if (isset($_SESSION['signin'])) : ?>
            <div class="notice"><?php echo htmlspecialchars($_SESSION['signin'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signin']) ?>
        <form action="<?= site_url ?>authentication/validate_add_otp.php" method="post">
            <h3>Insert OTP</h3>
            <input type="number" name="otp" placeholder="OTP">
            <button type="submit" name="submit">Sign in</button>
        </form>
    </div>
</body>
</html>
