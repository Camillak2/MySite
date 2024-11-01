<?php
session_start();
if (!isset($_SESSION['user'])) {
    exit("Доступ запрещен.");
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$field = $data['field'];
$value = $data['value'];

// Подключаемся к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', 'mysql');

// Обновляем нужное поле в таблице flat
$sql = "UPDATE flat SET $field = :value WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':value', $value);
$stmt->bindParam(':id', $id);
$stmt->execute();

echo "Успешно обновлено";
