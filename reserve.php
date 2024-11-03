<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'database.php';

$mail = new PHPMailer(true);

// Проверка, что данные были отправлены
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $countPeople = intval($_POST['count']);
    $flatID = intval($_POST['flatID']);

    // Запись данных для отладки
    file_put_contents('log.txt', print_r($_POST, true)); // Логируем входящие данные

    // Подготовка SQL-запроса для вставки данных в таблицу reservation
    $sql = "INSERT INTO reservation (Name, PhoneNumber, CountPeople, ID_flat) VALUES (:name, :phone, :countPeople, :flatID)";
    $stmt = $pdo->prepare($sql);

    // Привязка параметров
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':countPeople', $countPeople);
    $stmt->bindParam(':flatID', $flatID);

    // Выполнение запроса и проверка результата
    $stmt->execute();

    try {

        $sql = "SELECT * FROM flat WHERE ID = $flatID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(); 
        $flat = $stmt->fetch(PDO::FETCH_OBJ);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bronirovanie.kvartir.kazan@gmail.com';
        $mail->Password = 'qlze juxo mbdx utap';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kamilla.sagdieva@gmail.com', 'Эмиль');
        $mail->addAddress('kamilla.sagdieva@gmail.com');

        $mail->Subject = 'Новое бронирование!';
        $mail->Body = "Квартира: $flat->Name \nАдрес: $flat->Address\nКлиент: $name \nНомер телефона: $phone \nКол-во людей: $countPeople";

        $mail->send();

        echo json_encode(['success' => true, 'message' => 'Успешно забронировано!']);
    } catch (Exception $e) {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Не удалось забронировать: ' . $errorInfo[2]]);
    }
}
?>