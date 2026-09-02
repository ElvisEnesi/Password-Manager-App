<?php
    // include file
    include_once ("../configuration/database.php");
    // select user's passwords
    $select = mysqli_prepare($connection, "SELECT * FROM password WHERE user_id = ?");
    mysqli_stmt_bind_param($select, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($select);
    $results = mysqli_stmt_get_result($select);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Manage passwords</title>
    <link rel="stylesheet" href="<?= site_url ?>./css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['add_record'])) : ?>
        <div class="notice"><?php echo $_SESSION['add_record'] ?></div>
    <?php endif; ?>
    <?php unset($_SESSION['add_record']) ?>
    <div class="container">
        <aside>
            <div class="logo">PassLock</div>
            <a href="index.php">Overview</a>
            <a href="add_record.php">Add password</a>
            <a href="manage_record.php">Manage password</a>
            <a href="signout.php">Log out</a>
        </aside>
        <main>
            <div class="head">Secure credential vault</div>
            <h2>Your passwords</h2>
            <table>
                <tr>
                    <th>s/n</th>
                    <th>Name</th>
                    <th>Password</th>
                    <th>Date</th>
                </tr>
                <?php while ($result = mysqli_fetch_assoc($results)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['id']) ?></td>
                    <td><?php echo htmlspecialchars($result['app']) ?></td>
                    <td><?php echo htmlspecialchars($result['password']) ?></td>
                    <td><?php echo htmlspecialchars($result['date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
