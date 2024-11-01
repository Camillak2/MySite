<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apartmentId = $_POST['apartmentId'];
    $newPrice = $_POST['newPrice'];

    // Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', 'mysql');

    // Обновление цены квартиры
    $sql = "UPDATE flat SET Cost = :newPrice WHERE ID = :apartmentId";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['newPrice' => $newPrice, 'apartmentId' => $apartmentId]);

    echo json_encode(['success' => $result]);
}
?>