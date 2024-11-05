<?php
// database.php
$host = 'broniruyu-kazan.ru';  // Хост базы данных
$dbname = 'cn29435_broni';  // Имя базы данных
$user = 'cn29435_broni';  // Имя пользователя базы данных
$password = 'PH7rVxG_dq';  // Пароль пользователя базы данных

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
