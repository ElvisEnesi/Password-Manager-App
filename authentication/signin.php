<?php
    // include file
    include_once ("../configuration/database.php");
    include_once ("../security/ip.php");
    // select failed from login_log table
    $failed_log = mysqli_prepare($connection, "SELECT COUNT(*) AS failed FROM login_log WHERE ip_address = ? 
    AND status = ? AND time_logged >= NOW() - INTERVAL 30 MINUTE");
    // status
    $status = "failed";
    // bind parameters using ip address from ip.php
    mysqli_stmt_bind_param($failed_log, "ss", $user_ip, $status);
    // execute statement
    mysqli_stmt_execute($failed_log);
    // get result
    $failed_result = mysqli_stmt_get_result($failed_log);
    // convert data into associate array for use
    $failed_row = mysqli_fetch_assoc($failed_result);
    $failed = $failed_row['failed'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Sign in</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="form_container">
        <!--signup message-->
        <?php if (isset($_SESSION['signup'])) : //?>
            <div class="notice"><?php echo $_SESSION['signup'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signup']) ?>
        <!--signin message-->
        <?php if (isset($_SESSION['signin'])) : ?>
            <div class="notice"><?php echo $_SESSION['signin'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['signin']) ?>
        <!--check login attempt first-->
        <?php if ($failed > 5) : ?>
            <?php
                // insert into activity log
                $insert = mysqli_prepare($connection, "INSERT INTO activity_log (ip_address, type) VALUES (?,?)");
                // declare type
                $type = "Brute force";
                // bind parameters using ip address from ip.php
                mysqli_stmt_bind_param($insert, "ss", $user_ip, $type);
                // execute statement
                mysqli_stmt_execute($insert);
                echo '<div class="notice">' . "Too many failed attempts, try again in 30 minutes!!" . '</div>';
            ?>
        <?php else : ?>
            <form action="<?= site_url ?>authentication/signin_logic.php" method="post">
                <h3>Welcome back</h3>
                <input type="email" name="email" id="" placeholder="Email address">
                <input type="password" name="key" id="" placeholder="Password">
                <button type="submit" name="submit">Sign in</button>
            </form>
            <p style="margin-top: 10px;">Don't have an account? <a href="<?= site_url ?>authentication/signup.php">Sign up</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
