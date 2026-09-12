<?php
    // include file
    include_once ("../configuration/database.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = (string) $_GET['uuid'];
    } else {
        header("location: " . site_url . "user/manage_record.phh");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Delete password</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="form_container">
        <!--delete message-->
        <?php if (isset($_SESSION['delete_record'])) : ?>
            <div class="notice"><?php echo htmlspecialchars($_SESSION['delete_record'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['delete_record']) ?>
        <form action="<?= site_url ?>user/delete_record_logic.php?uuid=<?= htmlspecialchars($gotten_uuid, ENT_QUOTES, "UTF-8") ?>" method="post">
            <h3>Delete password</h3>
            <button type="submit" name="delete_record">Delete</button>
        </form>
    </div>
</body>
</html>
