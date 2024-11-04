<?php
session_start();

// Проверка, установлен ли файл куки
if (!isset($_COOKIE['admin_access'])) {
    header('Location: send_email.php');
}

// Проверка, авторизирован ли админ
if (isset($_COOKIE['admin_access'])) {

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php'); // Перенаправление на страницу входа
        exit;
    }
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

        function editPrice(apartmentId, newPriceWeekday, newPriceWeekend, newPriceWeekdayAdditional) {
            console.log('Editing price for apartment:', apartmentId);
            console.log('New Prices:', newPriceWeekday, newPriceWeekend, newPriceWeekdayAdditional);
            $('#priceModal').data('apartment-id', apartmentId).show();
            $('#newPriceWeekday').val(newPriceWeekday);
            $('#newPriceWeekend').val(newPriceWeekend);
            $('#newPriceWeekdayAdditional').val(newPriceWeekdayAdditional);
        }

        function closeModal(modalId) {
            $('#' + modalId).hide();
        }

        function savePrice() {
            var apartmentId = $('#priceModal').data('apartment-id');
            var newPriceWeekday = $('#newPriceWeekday').val();
            var newPriceWeekend = $('#newPriceWeekend').val();
            var newPriceWeekdayAdditional = $('#newPriceWeekdayAdditional').val();

            // Проверка всех значений перед отправкой
            if (!isNaN(newPriceWeekday) && newPriceWeekday > 0 &&
                !isNaN(newPriceWeekend) && newPriceWeekend > 0 &&
                !isNaN(newPriceWeekdayAdditional) && newPriceWeekdayAdditional > 0) {

                // Один запрос на сервер для всех трех значений
                $.post('update_price.php', {
                    apartmentId: apartmentId,
                    newPriceWeekday: newPriceWeekday,
                    newPriceWeekend: newPriceWeekend,
                    newPriceWeekdayAdditional: newPriceWeekdayAdditional
                }, function (response) {
                    if (response.success) {
                        // Обновление цен в карточке
                        $('#price-' + apartmentId + ' .weekday-price').text(newPriceWeekday);
                        $('#price-' + apartmentId + ' .weekend-price').text(newPriceWeekend);
                        $('#price-' + apartmentId + ' .additional-price').text(newPriceWeekdayAdditional);
                        closeModal('priceModal');
                    } else {
                        alert('Ошибка при сохранении цены.');
                    }
                }, 'json');

            } else {
                alert('Пожалуйста, введите корректные числовые значения для всех полей.');
            }
        }

    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администраторская панель</title>
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
                    <h1>Бронирование квартир</h1>
                </div>
            </div>
            <nav>
                <div class="nav-container">
                    <ul class="nav-links">
                        <li><?php if (isset($_COOKIE['admin_access'])): ?><a href="index.php">Назад</a><?php endif; ?>
                        </li>
                        <li><a href="bookings.php">Бронирования</a></li>
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

        function filterApartments() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const apartmentCards = document.querySelectorAll('.apartment-card');
            let count = 0;
            let firstMatch = null; // Переменная для хранения первого совпадения

            apartmentCards.forEach(card => {
                const name = card.querySelector('h2').innerText.toLowerCase();
                const description = card.querySelector('.description').innerText.toLowerCase();
                const cost = card.querySelector('.description').children[7].innerText.toLowerCase(); // предполагаем, что стоимость находится в 8-м элементе

                const matches = name.includes(filter) || description.includes(filter) || cost.includes(filter);

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

    <?php
    // Подключение к базе данных
    require 'database.php';

    // Запрос для получения информации о квартирах
    $sql = "SELECT * FROM flat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <div id="map" class="apartment-cards">
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
                    <h2><?php echo htmlspecialchars($apartment['Name']); ?></h2>
                    <p>• <?php echo htmlspecialchars($apartment['Square']); ?> м²</p>
                    <p><?php echo htmlspecialchars($apartment['Location']); ?></p>
                    <p><?php echo htmlspecialchars($apartment['Metro']); ?></p>
                    <p><?php echo htmlspecialchars($apartment['Description']); ?></p>
                    <p>• ⁠Комфортное проживание до <?php echo htmlspecialchars($apartment['NumberOfPeople']); ?>
                        человек.</p>
                    <p><?php echo htmlspecialchars($apartment['Underwear']); ?></p>
                    <p><?php echo htmlspecialchars($apartment['Device']); ?></p>
                    <p>• <?php echo htmlspecialchars($apartment['Cost']); ?> рублей за сутки в будни.</p>
                    <p>• <?php echo htmlspecialchars($apartment['CostWeekend']); ?> рублей за сутки в выходные.</p>
                    <p>Доплата за каждого гостя <?php echo htmlspecialchars($apartment['Surcharge']); ?> рублей, если их
                        количество превышает <?php echo htmlspecialchars($apartment['PeoplePay']); ?>.</p>
                    <p>Адрес: <?php echo htmlspecialchars($apartment['Address']); ?></p>
                    <p><a href="<?php echo htmlspecialchars($apartment['SutochnoLink']); ?>" target="_blank">Перейти на
                            Суточно.Ру</a></p>
                    <p></p>
                    <div class="button-container">
                        <button class="book-now" onclick="editCalendar(<?= $apartment['ID'] ?>)">Редактировать даты</button>
                        <button class="edit-button"
                            onclick="editPrice(<?= $apartment['ID'] ?>, <?= $apartment['Cost'] ?>, <?= $apartment['CostWeekend'] ?>, <?= $apartment['Surcharge'] ?>)">Редактировать
                            цены</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Modal для редактирования цены -->
    <div id="priceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('priceModal')">&times;</span>
            <h2>Редактировать цены</h2>
            <p>
                <label for="newPriceWeekday">Новая цена в будни:</label>
                <input type="number" id="newPriceWeekday" step="100" placeholder="Введите новую цену в будни">
            </p>
            <p>
                <label for="newPriceWeekend">Новая цена в выходные:</label>
                <input type="number" id="newPriceWeekend" step="100" placeholder="Введите новую цену в выходные">
            </p>
            <p>
                <label for="newPriceWeekdayAdditional">Новая цена для доплаты:</label>
                <input type="number" id="newPriceWeekdayAdditional" step="50"
                    placeholder="Введите новую цену для доплаты">
            </p>

            <button onclick="savePrice()">Сохранить</button>
        </div>
    </div>
</body>

</html>