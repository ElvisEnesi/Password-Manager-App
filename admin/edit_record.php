<?php
    // include file
    include_once ("../configuration/database.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = (string) $_GET['uuid'];
        // select password details needed using url
        $select_password = mysqli_prepare($connection, "SELECT password_uuid, app, username FROM password WHERE password_uuid = ? LIMIT 1");
        // bind parameters
        mysqli_stmt_bind_param($select_password, "s", $gotten_uuid);
        // execute statement
        mysqli_stmt_execute($select_password);
        // get results
        $password_result = mysqli_stmt_get_result($select_password);
        // check if available
        if (mysqli_num_rows($password_result) > 0) {
            // convert record to associate array for usage
            $password = mysqli_fetch_assoc($password_result);
        } else {
            // set session error message
            $_SESSION['edit_record'] = "Record not found";
            // redirect if there's no password for gotten uuid
            header("location: " . site_url . "admin/manage_record.phh");
            exit();
        }
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
    <title>PassLock | Edit password</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="form_container">
        <!--edit message-->
        <?php if (isset($_SESSION['edit_record'])) : ?>
            <div class="notice"><?php echo htmlspecialchars($_SESSION['edit_record'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['edit_record']) ?>
        <form action="<?= site_url ?>admin/edit_record_logic.php?uuid=<?= htmlspecialchars($gotten_uuid, ENT_QUOTES, "UTF-8") ?>" method="post">
            <h3>Edit password</h3>
            <input type="text" name="social" placeholder="App or website" value="<?= htmlspecialchars($password['app'], ENT_QUOTES, "UTF-8") ?>">
            <input type="text" name="username" placeholder="Username or Email" value="<?= htmlspecialchars($password['username'], ENT_QUOTES, "UTF-8") ?>">
            <input type="password" name="cr_p" placeholder="Create password">
            <input type="password" name="co_p" placeholder="Confirm password">
            <button type="submit" name="edit_record">Edit password</button>
        </form>
    </div>
</body>
</html>
