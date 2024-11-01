<?php
session_start();

// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', 'mysql');

// Получение данных из POST запроса
$apartmentId = $_POST['apartmentId'];
$checkInDate = $_POST['checkInDate'];
$checkOutDate = $_POST['checkOutDate'];
$name = $_SESSION['user_name'] ?? 'Имя не указано'; // Получите имя из сессии, если доступно
$phoneNumber = $_SESSION['user_phone'] ?? 'Телефон не указан'; // Получите номер телефона из сессии
$countPeople = $_POST['countPeople'] ?? 1; // Количество людей, по умолчанию 1

$response = ['success' => false];

try {
    // Вставка данных в таблицу reservations
    $sql = "INSERT INTO reservation (flat_id, Name, PhoneNumber, Checkintime, Checkouttime, CountPeople)
            VALUES (:flat_id, :name, :phoneNumber, :checkinTime, :checkoutTime, :countPeople)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':flat_id' => $apartmentId,
        ':name' => $name,
        ':phoneNumber' => $phoneNumber,
        ':checkinTime' => $checkInDate,
        ':checkoutTime' => $checkOutDate,
        ':countPeople' => $countPeople
    ]);

    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage(); // Возврат ошибки
}

header('Content-Type: application/json'); // Установите заголовок
echo json_encode($response);
?>
