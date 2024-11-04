<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apartmentId = $_POST['apartmentId'];
    $newPriceWeekday = $_POST['newPriceWeekday'];
    $newPriceWeekend = $_POST['newPriceWeekend'];
    $newPriceWeekdayAdditional = $_POST['newPriceWeekdayAdditional'];

    // Подключение к базе данных
    require_once 'database.php';

    // Обновление цены квартиры
    $sql = "UPDATE flat SET Cost = :newPriceWeekday, CostWeekend = :newPriceWeekend, Surcharge = :newPriceWeekdayAdditional
            WHERE ID = :apartmentId";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'newPriceWeekday' => $newPriceWeekday,
        'newPriceWeekend' => $newPriceWeekend,
        'newPriceWeekdayAdditional' => $newPriceWeekdayAdditional,
        'apartmentId' => $apartmentId
    ]);

    echo json_encode(['success' => $result]);
}
?>
