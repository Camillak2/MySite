<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Подключение автозагрузки Composer

session_start();

$subject = 'Ваш новый код доступа';
$mail = new PHPMailer(true);
$message_sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    // Проверка введенного кода
    $entered_code = $_POST['code'];

    if (isset($_SESSION['code']) && $_SESSION['code'] === $entered_code) {
        // Если код верен, устанавливаем куки и перенаправляем
        setcookie('admin_access', '1', 0, '/');

        // Очищаем код из сессии для безопасности
        unset($_SESSION['code']);

        // Перенаправляем на страницу авторизации
        header('Location: login.php');
        exit;
    } else {
        $error = "Неверный код. Попробуйте еще раз.";
    }
} else {
    // Генерация нового кода и отправка его на почту
    $code = uniqid(); // Генерация уникального кода
    $_SESSION['code'] = $code; // Сохраняем код в сессии

    try {
        // Настройки SMTP
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.gmail.com'; // SMTP-сервер Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'bronirovanie.kvartir.kazan@gmail.com'; // Ваша почта
        $mail->Password = 'qlze juxo mbdx utap'; // Пароль приложения
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Получатели
        $mail->setFrom('kamilla.sagdieva@gmail.com', 'Бронирование квартир');
        $mail->addAddress('kamilla.sagdieva@gmail.com'); // Замените на реальный адрес

        // Контент письма
        $message = "Ваш код: " . $code;
        $mail->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $mail->Body = $message;

        $mail->send();
        $message_sent = true;
    } catch (Exception $e) {
        $error = "Ошибка отправки: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Установка cookie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #D8C7AD;
            color: #301811;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #EBDCC8;
            border: 1px solid #ffd6d6;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 320px;
            text-align: center;
        }

        h2 {
            color: #4b280a;
            font-size: 1.6em;
            margin-top: 0;
        }

        .message {
            color: #1a501c;
            font-size: 1em;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #4b280a;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
            background-color: #f8efe1;
        }

        button {
            background-color: #4b280a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 1em;
        }

        button:hover {
            background-color: #301811;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Аутентификация</h2>
        <?php if ($message_sent): ?>
            <p class="message">Код отправлен на почту! Пожалуйста, проверьте вашу электронную почту.</p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="code">Введите код:</label>
            <input type="text" name="code" id="code" required>
            <button type="submit">Отправить</button>
        </form>
    </div>
</body>

</html>