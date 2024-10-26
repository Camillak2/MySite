<?php
session_start();
require 'database.php';

// Проверка, если администратор уже авторизован
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');  // Перенаправление на страницу админа
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Поиск пользователя в базе данных
    $stmt = $pdo->prepare("SELECT * FROM user WHERE Login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка существования пользователя и пароля
    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['admin_logged_in'] = true;  // Сохранение статуса авторизации администратора в сессии
        $_SESSION['user'] = $user['Login'];   // Сохранение логина администратора в сессии
        header('Location: admin.php');  // Перенаправление на страницу админа
        exit;
    } else {
        $error = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
    <h2>Авторизация</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="login">Логин:</label>
        <input type="text" name="login" id="login" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>
