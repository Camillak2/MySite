<?php

session_start();

// Проверяем, авторизован ли администратор
$isAdmin = isset($_SESSION['user']);

// Определяем ссылки
$userLink = "index.php"; // Ссылка для обычных пользователей
$adminLink = "admin.php"; // Ссылка для администраторов
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование квартир</title>
    <script>
        // Проверяем, есть ли уже идентификатор устройства
        if (!localStorage.getItem('deviceID')) {
            // Создаем уникальный идентификатор, например, используя текущую дату и случайное число
            const deviceID = 'admin-' + new Date().getTime() + '-' + Math.floor(Math.random() * 10000);
            localStorage.setItem('deviceID', deviceID);
        }
    </script>
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

    <?php
    // Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=mysite', 'root', password: 'mysql');

    // Запрос для получения информации о квартирах
    $sql = "SELECT * FROM flat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <main>
        <img src="main_photo1.jpg" alt="Main Photo" class="main-photo">
        <link
            href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
            rel="stylesheet">
        <div class="apartment-cards">
            <?php foreach ($apartments as $apartment): ?>
                <div id="map" class="apartment-card">
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
                                    onclick="setSlide(this, <?php echo $i; ?>)"></span>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="description">
                        <h2><?php echo htmlspecialchars($apartment['Name']); ?></h2>
                        <p>• <?php echo htmlspecialchars($apartment['Square']); ?> м²</p>
                        <p><?php echo htmlspecialchars($apartment['Location']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Metro']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Description']); ?></p>
                        <p>• ⁠Комфортное проживание до <?php echo htmlspecialchars($apartment['NumberOfPeople']); ?>
                            человек.</p>
                        <p><?php echo htmlspecialchars($apartment['Underwear']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Device']); ?></p>
                        <p>• <?php echo htmlspecialchars($apartment['Cost']); ?> руб.</p>
                        <p>Адрес: <?php echo htmlspecialchars($apartment['Address']); ?></p>
                        <p><a href="<?php echo htmlspecialchars($apartment['SutochnoLink']); ?>" target="_blank">Перейти на
                                Суточно.Ру</a></p>
                        <p></p>
                        <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- <div id="maps" class="maps-section">
        <iframe src=" " width="600" height="450" style="border:0;"
            allowfullscreen="" loading="lazy"></iframe>
    </div> -->
    </main>

    <script>
        window.onload = function () {
            document.querySelector('.main-photo').classList.add('show');
        };
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <section class="info-section">
        <h2 id="about" class="section-title">Как проходит заселение?</h2>
        <div class="circle-strip">
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 1</p>
                <p>Просмотр квартир, сдающихся посуточно в разных районах Казани. Вы можете найти нас также на других
                    платформах Интернета. </p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 2</p>
                <p>Выбор подходящего для вас жилья. Ознакомьтесь с описанием, расположением и свободными датами
                    бронирования, подходящей для вас квартиры. </p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 3</p>
                <p>Заполнение заявки на желаемую бронь. Не забудьте указать необходимые данные о себе в форме заполнения
                    на бронь квартиры.</p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 4</p>
                <p>Ожидайте! В ближайшее время с вами свяжется наш администратор для обсуждения всех интересующих
                    вопросов и нюансов при заселении.</p>
            </div>
            <div class="line"></div>
        </div>
    </section>

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
                    <p><a href="login.php">ВХОД ДЛЯ АДМИНИСТРАТОРА</a></p>
                </div>
            <?php endif; ?>
        </div>
    </footer>

    <div id="booking-form" class="booking-form">
        <div class="form-content">
            <span class="close" onclick="closeBookingForm()">&times;</span>
            <h2>Забронировать</h2>
            <!-- Calendar Section for Admin -->
            <div id="admin-calendar" style="display: none;">
                <h3>Выберите даты для занятости:</h3>
                <div id="calendar"></div>
                <button onclick="markDatesAsOccupied()">Редактировать</button>
            </div>
            <form id="bookingForm" onsubmit="submitBookingForm(event)">
                <label for="name">ФИО:</label>
                <input type="text" id="name" name="name" required>
                <label for="phone">Номер телефона:</label>
                <input type="tel" id="phone" name="phone" required oninput="formatPhoneNumber(this)">
                <label for="date">Дата:</label>
                <input type="date" id="date" name="date" required>
                <label for="count">Количество человек:</label>
                <input type="count" id="count" name="count" required oninput="formatCount(this)">
                <button type="submit">Отправить</button>
            </form>
            <script>
                function formatCount(input) {
                    // Удалить любые нецифровые символы
                    input.value = input.value.replace(/\D/g, '');

                    // Проверка на пустое поле
                    if (input.value === '') {
                        input.setCustomValidity('Пожалуйста, введите количество человек.');
                    } else {
                        input.setCustomValidity('');
                    }
                }
            </script>

        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>