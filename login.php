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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9ecec;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff4f4;
            border: 1px solid #ffd6d6;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #d58a94;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ffd6d6;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #f5a1a7;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #d58a94;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Авторизация</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="login">Логин:</label>
            <input type="text" name="login" id="login" required>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>

</html>