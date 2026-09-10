<?php
    // include file
    include_once ("../configuration/database.php");
    // get uuid from url
    if (isset($_GET['uuid'])) {
        $gotten_uuid = filter_var($_GET['uuid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    } else {
        # code...
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Add password</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="form_container">
        <?php if (isset($_SESSION['add_record'])) : ?>
            <div class="notice"><?php echo htmlspecialchars($_SESSION['add_record'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['add_record']) ?>
        <form action="add_record_logic.php" method="post">
            <h3>Add password</h3>
            <input type="text" name="social" placeholder="App or website">
            <input type="password" name="cr_p" placeholder="Create password">
            <input type="password" name="co_p" placeholder="Confirm password">
            <button type="submit" name="submit">Save password</button>
        </form>
    </div>
</body>
</html>
