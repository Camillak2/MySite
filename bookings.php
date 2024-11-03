<?php
require_once 'database.php';

session_start();

$isAdmin = isset($_SESSION['user']);

if (!$isAdmin) {
    header('Location: /');
    exit();
}

$stmt = $pdo->prepare("
            SELECT reservation.Name AS user_name, reservation.PhoneNumber AS user_phone, reservation.CountPeople AS user_count, flat.* 
            FROM reservation 
            JOIN flat ON flat.ID = reservation.ID_flat
        ");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($reservations);
// die();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирования</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <div class="logo">
                    <img src="logo.jpg" alt="Логотип" />
                </div>
                <div class="title">
                    <h1>Бронирования</h1>
                </div>
            </div>
            <nav>
                <div class="nav-container">
                    <ul class="nav-links">
                        <li><a href="admin.php">Вернуться назад</a></li>
                    </ul>
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Поиск по имени, цене или описанию"
                            oninput="filterApartments()">
                        <p id="resultCount"></p>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <script>
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
        }

        function filterApartments() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const apartmentCards = document.querySelectorAll('.apartment-card');
            let count = 0;
            let firstMatch = null; // Переменная для хранения первого совпадения

            apartmentCards.forEach(card => {
                const user_name = card.querySelector('h2').innerText.toLowerCase();
                const user_phone = card.querySelector('.description').children[1].innerText.toLowerCase();
                const user_count = card.querySelector('.description').children[2].innerText.toLowerCase();
                const name = card.querySelector('.description').children[3].innerText.toLowerCase();
                const address = card.querySelector('.description').children[4].innerText.toLowerCase();

                const matches = user_name.includes(filter) || user_phone.includes(filter) || user_count.includes(filter) || name.includes(filter) || address.includes(filter);

                if (matches) {
                    card.style.display = '';
                    count++;
                    card.classList.add('highlight'); // Подсветка совпадения
                    setTimeout(() => {
                        card.classList.remove('highlight'); // Удаляем подсветку через некоторое время
                    }, 3000);

                    // Запоминаем первое найденное совпадение
                    if (!firstMatch) {
                        firstMatch = card;
                    }
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('resultCount').innerText = `${count} из ${apartmentCards.length}`;

            // Прокрутка к первому найденному элементу
            if (firstMatch) {
                firstMatch.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>

    <main>
        <link
            href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
            rel="stylesheet">
        <div class="apartment-cards">
            <?php foreach ($reservations as $reservation): ?>
                <div class="apartment-card">
                    <div class="carousel" data-current-slide="0">
                        <div class="carousel-images">
                            <?php
                            // Разделяем пути к фотографиям по запятой и выводим каждое фото
                            $photos = explode(', ', $reservation['Photo_path']);
                            foreach ($photos as $photo):
                                ?>
                                <img src="<?php echo 'flats/' . trim($photo); ?>"
                                    alt="<?php echo htmlspecialchars($reservation['Name']); ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="description">
                        <h2><?= $reservation['user_name'] ?></h2>
                        <p><?= $reservation['user_phone'] ?></p>
                        <p>Количество человек: <?= $reservation['user_count'] ?></p>
                        <p>Квартира: <?= $reservation['Name'] ?></p>
                        <p>Адрес: <?= $reservation['Address'] ?></p>
                        <p></p>
                        <div class="button-container">
                            <button class="book-now" onclick="editCalendar(<?= $reservation['ID'] ?>)">Редактировать
                                даты</button>
                            <button class="edit-button"
                                onclick="editPrice(<?= $reservation['ID'] ?>, <?= $reservation['Cost'] ?>)">Редактировать
                                цену</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        window.onload = function () {
            document.querySelector('.main-photo').classList.add('show');
        };
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <footer>
    </footer>
</body>

</html>