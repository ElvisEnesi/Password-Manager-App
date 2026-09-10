<?php
    // include file
    include_once ("../configuration/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Create account</title>
    <link rel="stylesheet" href="<?= site_url ?>./css/style.css">
</head>
<body>
    <div class="form_container">
        <?php if (isset($_SESSION['signup'])) : ?>
            <div class="notice"><?php echo $_SESSION['signup'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signup']) ?>
        <form action="<?= site_url ?>./authentication/signup_logic.php" method="post" enctype="multipart/form-data">
            <h3>Create account</h3>
            <input type="text" name="first_name" placeholder="First name">
            <input type="text" name="last_name" placeholder="Last name">
            <input type="email" name="email" placeholder="Email address">
            <input type="file" name="profile" placeholder="Profile picture">
            <input type="password" name="cr_p" placeholder="Create password">
            <input type="password" name="co_p" placeholder="Confirm password">
            <button type="submit" name="submit">Create account</button>
        </form>
        <p style="margin-top: 10px;">Already have an account? <a href="<?= site_url ?>authentication/signin.php">Sign in</a></p>
    </div>
</body>
</html>
