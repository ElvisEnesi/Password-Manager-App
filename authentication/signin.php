<?php
    // include file
    include_once ("../configuration/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Sign in</title>
    <link rel="stylesheet" href="<?= site_url ?>/css/style.css">
</head>
<body>
    <div class="form_container">
        <?php if (isset($_SESSION['signup'])) : //?>
            <div class="notice"><?php echo $_SESSION['signup'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signup']) ?>
        <?php if (isset($_SESSION['signin'])) : ?>
            <div class="notice"><?php echo $_SESSION['signin'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signin']) ?>
        <form action="<?= site_url ?>authentication/signin_logic.php" method="post">
            <h3>Welcome back</h3>
            <input type="email" name="email" id="" placeholder="Email address">
            <input type="password" name="key" id="" placeholder="Password">
            <button type="submit" name="submit">Sign in</button>
        </form>
    </div>
</body>
</html>
