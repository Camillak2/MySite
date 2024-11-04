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
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal для редактирования календаря -->
        <div id="calendarModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('calendarModal')">&times;</span>
                <h2>Редактировать даты</h2>
                <input type="hidden" id="apartmentId">
                <div class="calendar">
                    <label for="checkInDate">Дата заезда:</label>
                    <input type="date" id="checkInDate">
                    <label for="checkOutDate">Дата выезда:</label>
                    <input type="date" id="checkOutDate">
                    <button onclick="saveDates()">Сохранить</button>
                </div>
                <div id="calendarDisplay"></div>
            </div>
        </div>

    </main>

    <script>
        function openCalendarModal(apartmentId) {
            document.getElementById('apartmentId').value = apartmentId;
            loadBookedDates(apartmentId);
            document.getElementById('calendarModal').style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function saveDates() {
            var apartmentId = document.getElementById('apartmentId').value;
            var checkInDate = document.getElementById('checkInDate').value;
            var checkOutDate = document.getElementById('checkOutDate').value;

            $.ajax({
                url: 'booking.php',
                type: 'POST',
                data: {
                    apartmentId: apartmentId,
                    checkInDate: checkInDate,
                    checkOutDate: checkOutDate
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert('Даты успешно сохранены!');
                        closeModal('calendarModal');
                        loadBookedDates(apartmentId); // Обновить календарь
                    } else {
                        alert('Ошибка: ' + (result.message || 'Неизвестная ошибка'));
                    }
                }
            });
        }

        function loadBookedDates(apartmentId) {
            $.ajax({
                url: 'get_booked_dates.php', // Создайте и реализуйте этот скрипт
                type: 'GET',
                data: {
                    apartmentId: apartmentId
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        displayBookedDates(result.dates);
                    } else {
                        alert('Ошибка при загрузке забронированных дат');
                    }
                }
            });
        }

        function displayBookedDates(dates) {
            var calendarDisplay = document.getElementById('calendarDisplay');
            calendarDisplay.innerHTML = '';

            dates.forEach(function (date) {
                var dateElement = document.createElement('div');
                dateElement.classList.add('booked-date');
                dateElement.innerText = date;
                calendarDisplay.appendChild(dateElement);
            });
        }        
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <footer>
    </footer>
</body>

</html>