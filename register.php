<?php
// Подключение к базе данных
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверяем, существует ли пользователь с таким логином
    $stmt = $pdo->prepare("SELECT * FROM user WHERE Login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $error = "Пользователь с таким логином уже существует!";
    } else {
        // Хешируем пароль перед сохранением в базу данных
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Сохраняем логин и хешированный пароль в базе данных
        $stmt = $pdo->prepare("INSERT INTO user (Login, Password) VALUES (:login, :password)");
        $stmt->execute(['login' => $login, 'password' => $hashed_password]);

        echo "Пользователь успешно зарегистрирован!";
        // Перенаправляем пользователя на страницу логина
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h2>Регистрация</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label for="login">Логин:</label>
        <input type="text" name="login" id="login" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <p>Уже зарегистрированы? <a href="login.php">Войдите</a></p>
</body>
</html>
