<?php
session_start();
    include 'db_pass.php';

    if(empty($_SESSION['username']))
    {
        header("Location:index.php");
        exit();
    }

    if(!empty($_SESSION['username']))
    {
        $username = $_SESSION['username'];
        $stmt = $db->prepare("SELECT * FROM Users WHERE username = ? and admin = 1");
        $stmt->execute([$username]);
        $userAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($userAdmin))
        {
            header("Location:index.php");
            exit();
        }
    }

    $stmt = $db->query("SELECT * FROM Users");
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->query("
        SELECT lang_id, COUNT(user_id) AS num_users
        FROM UserProgrammingLanguages
        GROUP BY lang_id
    ");
    $statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/admin.css">
    <title>admin panel</title>
</head>
<body>

<h1>Данные пользователей</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Дата рождения</th>
                <th>Пол</th>
                <th>Биография</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['fullname']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['dob']) ?></td>
                    <td><?= htmlspecialchars($user['gender']) ?></td>
                    <td><?= htmlspecialchars($user['bio']) ?></td>
                    <td><a href="user_profile.php?user_id=<?= htmlspecialchars($user['user_id']) ?>">Редактировать</a></td>
                    <td><a href="delete_user.php?user_id=<?= htmlspecialchars($user['user_id']) ?>">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>Язык программирования</th>
                <th>Количество пользователей</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statistics as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['lang_id']) ?></td>
                    <td><?= htmlspecialchars($row['num_users']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php">Вернуться на страницу входа</a>
</body>
</html>