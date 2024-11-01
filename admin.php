<?php
session_start();

// Проверка, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Перенаправление на страницу входа
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("a").on('click', function (event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function () {
                        window.location.hash = hash;
                    });
                }
            });
        });

        function editCalendar(apartmentId) {
            $('#calendarModal').data('apartment-id', apartmentId).show();
        }

        function editPrice(apartmentId, currentPrice) {
            $('#priceModal').data('apartment-id', apartmentId).show();
            $('#newPrice').val(currentPrice);
        }

        function closeModal(modalId) {
            $('#' + modalId).hide();
        }

        function savePrice() {
            var apartmentId = $('#priceModal').data('apartment-id');
            var newPrice = $('#newPrice').val();
            if (!isNaN(newPrice) && newPrice > 0) {
                $.post('update_price.php', { apartmentId: apartmentId, newPrice: newPrice }, function (response) {
                    if (response.success) {
                        $('#price-' + apartmentId).text(newPrice);
                        closeModal('priceModal');
                    } else {
                        alert('Ошибка при сохранении цены.');
                    }
                }, 'json');
            } else {
                alert('Пожалуйста, введите корректное числовое значение.');
            }
        }

        function saveDates() {
            var apartmentId = $('#calendarModal').data('apartment-id');
            var checkInDate = $('#checkInDate').val();
            var checkOutDate = $('#checkOutDate').val();
            if (new Date(checkInDate) < new Date(checkOutDate)) {
                $.post('update_dates.php', { apartmentId: apartmentId, checkInDate: checkInDate, checkOutDate: checkOutDate }, function (response) {
                    if (response.success) {
                        // Update the calendar view
                        closeModal('calendarModal');
                    } else {
                        alert('Ошибка при сохранении дат.');
                    }
                }, 'json');
            } else {
                alert('Пожалуйста, введите корректные даты.');
            }
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование квартир</title>
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
                    <h1>Бронирование</h1>
                    <h1>квартир</h1>
                </div>
            </div>
            <nav>
                <div class="nav-container">
                    <ul class="nav-links">
                        <li><a href="#contacts" onclick="scrollToSection('contacts')">Контакты</a></li>
                        <li><a href="#map" onclick="scrollToSection('map')">Квартиры</a></li>
                        <li><a href="#about" onclick="scrollToSection('about')">О брони</a></li>
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
    </script>

    <?php
    // Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', 'mysql');

    // Запрос для получения информации о квартирах
    $sql = "SELECT * FROM flat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="apartment-cards">
        <?php foreach ($apartments as $apartment): ?>
            <div class="apartment-card">
                <div class="carousel" data-current-slide="0">
                    <div class="carousel-images">
                        <?php
                        // Разделяем пути к фотографиям по запятой и выводим каждое фото
                        $photos = explode(', ', $apartment['Photo_path']);
                        foreach ($photos as $photo):
                            ?>
                            <img src="<?php echo 'flats/' . trim($photo); ?>"
                                alt="<?php echo htmlspecialchars($apartment['Name']); ?>">
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-controls">
                        <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                        <button class="next" onclick="nextSlide(this)">&#10095;</button>
                    </div>
                    <div class="carousel-circles">
                        <?php for ($i = 0; $i < count($photos); $i++): ?>
                            <span class="circle <?php echo $i === 0 ? 'active' : ''; ?>"
                                onclick="setSlide(<?php echo $i; ?>)"></span>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="description">
                    <h2><?= $apartment['Location'] ?></h2>
                    <p>Тип: <?= $apartment['Name'] ?></p>
                    <p>Цена: <span id="price-<?= $apartment['ID'] ?>"><?= $apartment['Cost'] ?></span> ₽</p>
                    <p>Описание: <?= $apartment['Description'] ?></p>
                    <div class="button-container">
                        <button class="book-now" onclick="editCalendar(<?= $apartment['ID'] ?>)">Редактировать даты</button>
                        <button class="edit-button"
                            onclick="editPrice(<?= $apartment['ID'] ?>, <?= $apartment['Cost'] ?>)">Редактировать
                            цену</button>
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

    <!-- Modal для редактирования цены -->
    <div id="priceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('priceModal')">&times;</span>
            <h2>Редактировать цену</h2>
            <input type="number" id="newPrice" step="0.01" placeholder="Введите новую цену">
            <button onclick="savePrice()">Сохранить</button>
        </div>
    </div>

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

    <footer>
        <h2 id="contacts" class="footer-title">Контактная информация</h2>
        <div class="footer-content">
            <div class="contact-info">
                <p><a href="tel:+79097660628">+7 909 766-06-28</a></p>
                <p>Email: <a href="mailto:5347612@mail.ru">5347612@mail.ru</a></p>
                <p>ИП Забелкина Л.И.</p>
                <p>ИНН 165606525483</p>
                <p>ОГРНИП 321169000046647</p>
            </div>
            <div class="social-icons">
                <a href="https://t.me/posutkakazanarenda"><img src="/Networks/Telegram.png" alt="Telegram"></a>
                <a href="https://api.whatsapp.com/send?phone=79097760628"><img src="/Networks/WhatsApp.png"
                        alt="WhatsApp"></a>
                <a href="https://vk.com/id728306440"><img src="/Networks/VK.png" alt="VKontakte"></a>
                <a href="mailto:5347612@mail.ru"><img src="/Networks/Email.png" alt="Email"></a>
            </div>
            <?php if (isset($_COOKIE['admin_access'])): ?>
                <div id="admin-login">
                    <p><a href="logout.php">ВЫХОД ДЛЯ АДМИНИСТРАТОРА</a></p>
                </div>
            <?php endif; ?>
        </div>
    </footer>

</body>

</html>