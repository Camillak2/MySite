<?php
// Подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', 'mysql');

// Получение ID квартиры
$apartmentId = $_GET['apartmentId'];
$response = ['success' => false];

try {
    // Запрос для получения забронированных дат
    $sql = "SELECT Checkintime, Checkouttime FROM reservation WHERE flat_id = :flat_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':flat_id' => $apartmentId]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $bookedDates = [];
    foreach ($reservations as $reservation) {
        $checkin = new DateTime($reservation['Checkintime']);
        $checkout = new DateTime($reservation['Checkouttime']);
        while ($checkin < $checkout) {
            $bookedDates[] = $checkin->format('Y-m-d'); // Собираем все дни между заездом и выездом
            $checkin->modify('+1 day');
        }
    }

    $response['success'] = true;
    $response['dates'] = $bookedDates;
} catch (Exception $e) {
    $response['message'] = $e->getMessage(); // Возврат ошибки
}

echo json_encode($response);
?>
