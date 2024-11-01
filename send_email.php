<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Подключение автозагрузки Composer

session_start();

$subject = 'Ваш новый код доступа';
$mail = new PHPMailer(true);

// Генерация нового кода и отправка его на почту
if (!isset($_SESSION['code'])) {
    $code = uniqid(); // Генерация уникального кода
    $_SESSION['code'] = $code; // Сохраняем код в сессии

    try {
        // Настройки SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP-сервер Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'kamilla.sagdieva@gmail.com'; // Ваша почта
        $mail->Password = 'PISac416@!'; // Пароль приложения
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Получатели
        $mail->setFrom('kamilla.sagdieva@gmail.com', 'Ваше Имя');
        $mail->addAddress('kamilla.sagdieva@gmail.com'); // Замените на реальный адрес

        // Контент письма
        $message = "Ваш код: " . $code;
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo "Код отправлен на почту! Пожалуйста, проверьте вашу электронную почту.";
    } catch (Exception $e) {
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['code']) && $_POST['code'] == $_SESSION['code']) {
        setcookie('admin_access', '1', time() + (30 * 24 * 60 * 60), '/');
        echo "Cookie установлен!";
        unset($_SESSION['code']);
    } else {
        echo "Неверный код! Пожалуйста, попробуйте снова.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Установка cookie</title>
</head>

<body>
    <form method="post" action="">
        <label for="code">Введите код:</label>
        <input type="text" id="code" name="code" required>
        <input type="submit" value="Отправить">
    </form>
</body>

</html>