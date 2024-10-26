<?php
// database.php
$host = 'localhost';  // Хост базы данных
$dbname = 'mysite';  // Имя базы данных
$user = 'root';  // Имя пользователя базы данных
$password = 'mysql';  // Пароль пользователя базы данных

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
