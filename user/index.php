<?php
    // include file
    include_once ("../configuration/database.php");
    // select user's passwords
    // $select = mysqli_prepare($connection, "SELECT * FROM password WHERE user_id = ? ORDER BY date DESC LIMIT 2");
    // mysqli_stmt_bind_param($select, "i", $_SESSION['user_id']);
    // mysqli_stmt_execute($select);
    // $results = mysqli_stmt_get_result($select);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassLock | Overview</title>
    <link rel="stylesheet" href="<?= site_url ?>css/style.css">
</head>
<body>
    <div class="container">
        <aside>
            <div class="logo">PassLock</div>
            <a href="<?= site_url ?>user/index.php">Overview</a>
            <a href="<?= site_url ?>user/add_record.php">Add password</a>
            <a href="<?= site_url ?>authentication/signout.php">Log out</a>
        </aside>
        <main>
            <div class="head">Welcome Uche</div>
            <h2>Your passwords</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Password</th>
                    <th>Date</th>
                </tr>
                <?php //while ($result = mysqli_fetch_assoc($results)) : ?>
                <!-- <tr>
                    <td><?php //echo htmlspecialchars($result['app']) ?></td>
                    <td><?php //echo htmlspecialchars($result['password']) ?></td>
                    <td><?php //echo htmlspecialchars($result['date']) ?></td>
                </tr> -->
                <?php //endwhile; ?>
            </table>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
