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
    <style>
        body {
            font-family: sans-serif;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
            z-index: 100;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            margin-right: 20px;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .title {
            display: flex;
            flex-direction: column;
        }

        .title h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin-right: 20px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .search-container {
            margin-left: 20px;
        }

        .search-container input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .apartment-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 100px;
            /* Ensure it clears the fixed header */
            padding: 20px;
        }

        .apartment-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px;
            width: calc(33% - 40px);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .apartment-card img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .apartment-card .description {
            display: flex;
            flex-direction: column;
        }

        .apartment-card .description h2 {
            margin: 10px 0;
        }

        .apartment-card .description p {
            margin: 5px 0;
        }

        .apartment-card .description .book-now {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .apartment-card .description .edit-button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

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
            // Logic to open the calendar editing interface
            alert('Editing calendar for apartment ID: ' + apartmentId);
            // You can replace the above alert with your actual logic
        }

        function editPrice(apartmentId, currentPrice) {
            var newPrice = prompt("Enter new price for apartment ID " + apartmentId + ":", currentPrice);
            if (newPrice !== null) {
                // Logic to save the new price
                alert('New price for apartment ID ' + apartmentId + ' is ' + newPrice);
                // You can replace the above alert with your actual logic
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
                <ul>
                    <li><a href="#contacts" onclick="scrollToSection('contacts')">Контакты</a></li>
                    <li><a href="#map" onclick="scrollToSection('map')">Карты</a></li>
                    <li><a href="#about" onclick="scrollToSection('about')">О нас</a></li>
                </ul>
                <div class="search-container">
                    <input type="text" placeholder="Поиск квартиры" id="search" onkeyup="searchApartments()" />
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

    <main>
        <img src="main_photo1.jpg" alt="Main Photo" class="main-photo">
        <div class="apartment-cards">
            <?php foreach ($apartments as $apartment): ?>
                <div class="apartment-card">
                    <div class="carousel" data-current-slide="0">
                        <div class="carousel-images">
                            <?php
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
                        <h2><?php echo htmlspecialchars($apartment['Name']); ?></h2>
                        <p>• <?php echo htmlspecialchars($apartment['Square']); ?> м²</p>
                        <p><?php echo htmlspecialchars($apartment['Location']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Metro']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Description']); ?></p>
                        <p>• ⁠Комфортное проживание до <?php echo htmlspecialchars($apartment['NumberOfPeople']); ?>
                            человек.</p>
                        <p><?php echo htmlspecialchars($apartment['Underwear']); ?></p>
                        <p><?php echo htmlspecialchars($apartment['Device']); ?></p>
                        <p>• <span
                                id="price-<?php echo $apartment['id']; ?>"><?php echo htmlspecialchars($apartment['Cost']); ?></span>
                            руб.</p>
                        <p>Адрес: <?php echo htmlspecialchars($apartment['Address']); ?></p>
                        <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                        <button class="edit-button" onclick="editCalendar(<?php echo $apartment['id']; ?>)">Редактировать
                            календарь</button>
                        <button class="edit-button"
                            onclick="editPrice(<?php echo $apartment['id']; ?>, '<?php echo htmlspecialchars($apartment['Cost']); ?>')">Редактировать
                            цену</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="maps" class="maps-section">
            <!-- Map Embed Example -->
            <iframe src="https://www.google.com/maps/d/embed?mid=YOUR_MAP_ID" width="600" height="450" style="border:0;"
                allowfullscreen="" loading="lazy"></iframe>
        </div>
    </main>
    <script>
        window.onload = function () {
            document.querySelector('.main-photo').classList.add('show');
        };
    </script>
    <section class="info-section">
        <h2 class="section-title">Как проходит заселение?</h2>
        <div class="circle-strip">
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 1</p>
                <p>Этап 1</p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 2</p>
                <p>Этап 2</p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 3</p>
                <p>Этап 3</p>
            </div>
            <div class="line"></div>
            <div class="circle-item">
                <div class="circle"></div>
                <p>Этап 4</p>
                <p>Этап 4</p>
            </div>
            <div class="line"></div>
        </div>
    </section>

    <footer>
        <h2 id="contacts" class="footer-title">Контактная информация</h2>
        <div class="footer-content">
            <div class="social-icons">
                <a href="https://www.facebook.com"><img src="/Networks/Avito.png" alt="Avito"></a>
                <a href="https://www.twitter.com"><img src="/Networks/Telegram.png" alt="Telegram"></a>
                <a href="https://www.instagram.com"><img src="/Networks/WhatsApp.png" alt="WhatsApp"></a>
                <a href="https://www.instagram.com"><img src="/Networks/Instagram.png" alt="Instagram"></a>
                <a href="mailto:info@apartmentbooking.com"><img src="/Networks/Email.png" alt="Email"></a>
            </div>
            <div class="contact-info">
                <p>Телефон: <a href="tel:+71234567890">+7(123)456-78-90</a></p>
                <p>Email: <a href="mailto:info@apartmentbooking.com">info@apartmentbooking.com</a></p>
                <p><a href="index.php">ВЫХОД ДЛЯ АДМИНИСТРАТОРА</a></p>
            </div>
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
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>