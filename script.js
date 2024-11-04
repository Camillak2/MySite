// JavaScript для управления каруселью изображений
// Функция для отображения выбранного слайда
function showSlide(carousel, index) {
    const slides = carousel.querySelectorAll('.carousel-images img');
    const circles = carousel.querySelectorAll('.circle');

    // Установка текущего слайда
    let currentSlide = index; // Присваиваем индекс переданного слайда

    // Защита от выхода за пределы массива слайдов
    if (currentSlide >= slides.length) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = slides.length - 1;
    }

    carousel.dataset.currentSlide = currentSlide;

    // Показ нужного изображения
    slides.forEach((slide, i) => {
        slide.style.transform = `translateX(-${currentSlide * 100}%)`;
    });

    // Обновляем кружочки
    circles.forEach((circle, i) => {
        circle.classList.remove('active');
        if (i === currentSlide) {
            circle.classList.add('active'); // Устанавливаем активный кружок
        }
    });
}

// Обработчик клика по кружочкам
function setSlide(circle, index) {
    const carousel = circle.closest('.carousel');
    showSlide(carousel, index); // Перейти к выбранному слайду
}

function nextSlide(button) {
    const carousel = button.closest('.carousel');
    const currentSlide = parseInt(carousel.dataset.currentSlide) || 0;
    showSlide(carousel, currentSlide + 1);
}

function prevSlide(button) {
    const carousel = button.closest('.carousel');
    const currentSlide = parseInt(carousel.dataset.currentSlide) || 0;
    showSlide(carousel, currentSlide - 1);
}



let currentImageIndex = 0;
let images = [];

// Открытие модального окна с первым изображением
function openImageModal(imageSrc, imageArray) {
    images = imageArray;
    currentImageIndex = images.indexOf(imageSrc); // Устанавливаем индекс текущего изображения
    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");

    modal.style.display = "block";
    modalImage.src = images[currentImageIndex];
}

// Закрытие модального окна
function closeImageModal() {
    const modal = document.getElementById("imageModal");
    modal.style.display = "none";
}

// Функция для показа следующего изображения
function nextImage() {
    if (currentImageIndex < images.length - 1) {
        currentImageIndex++;
    } else {
        currentImageIndex = 0; // Возвращаемся к первому изображению
    }
    document.getElementById("modalImage").src = images[currentImageIndex];
}

// Функция для показа предыдущего изображения
function prevImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
    } else {
        currentImageIndex = images.length - 1; // Переходим к последнему изображению
    }
    document.getElementById("modalImage").src = images[currentImageIndex];
}

// Добавляем событие для навигации с помощью клавиш стрелок
document.addEventListener("keydown", function (event) {
    const modal = document.getElementById("imageModal");
    if (modal.style.display === "block") { // Проверяем, открыто ли модальное окно
        if (event.key === "ArrowRight") {
            nextImage();
        } else if (event.key === "ArrowLeft") {
            prevImage();
        } else if (event.key === "Escape") {
            closeImageModal();
        }
    }
});

// Привязываем функцию к каждому изображению в карусели для открытия модального окна
document.querySelectorAll('.carousel-images img').forEach(img => {
    img.addEventListener('click', function () {
        const carouselImages = Array.from(this.closest('.carousel-images').querySelectorAll('img')).map(img => img.src);
        openImageModal(this.src, carouselImages);
    });
});



// Функция поиска квартир
function searchApartments() {
    const input = document.getElementById('search').value.toLowerCase();
    const apartments = document.querySelectorAll('.apartment');
    apartments.forEach(apartment => {
        const title = apartment.querySelector('.apartment-title').textContent.toLowerCase();
        apartment.style.display = title.includes(input) ? '' : 'none';
    });
}

function showBookingFormFromButton(button) {
    //document.getElementById('booking-form').style.display = 'flex'; // Открываем модальное окно
    // Получаем flatID из data-атрибута кнопки
    const flatID = button.getAttribute('data-id');
    console.log('Flat ID for booking:', flatID);

    const bookingForm = document.getElementById('booking-form');
    bookingForm.style.display = 'block';
    document.getElementById('flatID').value = flatID;
    console.log('Flat ID set in form:', document.getElementById('flatID').value);
}

function closeBookingForm() {
    document.getElementById('booking-form').style.display = 'none'; // Закрываем модальное окно
}

// Вызываем функцию при нажатии кнопки "Забронировать"
document.querySelectorAll('.book-now').forEach(button => {
    button.addEventListener('click', showBookingForm);
});

// Закрытие модального окна при нажатии на крестик
document.querySelector('.close').addEventListener('click', closeBookingForm);


let isAdminMode = false; // Track if admin mode is on
const occupiedDates = new Set(); // Store occupied dates

function toggleAdminMode() {
    isAdminMode = !isAdminMode;
    document.getElementById('admin-calendar').style.display = isAdminMode ? 'block' : 'none';
    if (isAdminMode) {
        createCalendar();
    }
}

function createCalendar() {
    const calendarDiv = document.getElementById('calendar');
    calendarDiv.innerHTML = ''; // Clear existing calendar
    const today = new Date();
    const month = today.getMonth();
    const year = today.getFullYear();

    // Calculate the first day of the month
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDay = firstDay.getDay();

    // Create empty slots for days before the first day
    for (let i = 0; i < startingDay; i++) {
        const emptyDiv = document.createElement('div');
        calendarDiv.appendChild(emptyDiv);
    }

    // Create day elements
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.classList.add('calendar-day');
        dayDiv.textContent = day;

        // Add click event to select the day
        dayDiv.onclick = function () {
            toggleDaySelection(dayDiv);
        };

        // Mark as occupied if already occupied
        if (occupiedDates.has(day)) {
            dayDiv.classList.add('occupied');
        }

        calendarDiv.appendChild(dayDiv);
    }
}

function toggleDaySelection(dayDiv) {
    const day = parseInt(dayDiv.textContent);
    if (dayDiv.classList.contains('occupied')) {
        return; // Can't select occupied days
    }
    dayDiv.classList.toggle('selected');
}

function markDatesAsOccupied() {
    const selectedDays = document.querySelectorAll('.calendar-day.selected');
    selectedDays.forEach(dayDiv => {
        const day = parseInt(dayDiv.textContent);
        occupiedDates.add(day);
        dayDiv.classList.add('occupied');
    });
    alert("Даты успешно отмечены как занятые!");
}

function formatPhoneNumber(input) {
    let phoneNumber = input.value.replace(/\D/g, ''); // Удаляем все нецифровые символы

    if (phoneNumber.length === 0) {
        input.value = '';
        return;
    }

    // Определяем, с какого символа начинать заполнение
    let startIndex = phoneNumber.startsWith('7') || phoneNumber.startsWith('8') ? 1 : 0;

    // Добавляем маску
    input.value = `+7(${phoneNumber.substring(startIndex, startIndex + 3)}) ${phoneNumber.substring(startIndex + 3, startIndex + 6)}-${phoneNumber.substring(startIndex + 6, startIndex + 8)}-${phoneNumber.substring(startIndex + 8, startIndex + 10)}`;
}


document.addEventListener('DOMContentLoaded', function () {
    const lines = document.querySelectorAll('.line');
    const circles = document.querySelectorAll('.circle');
    const texts = document.querySelectorAll('.circle-item p'); // Получаем тексты этапов
    let delay = 0;

    // Сначала активируем линии, кружки и текст поочередно
    for (let i = 0; i < circles.length; i++) {
        // Активируем линию
        setTimeout(() => {
            if (lines[i]) {
                lines[i].classList.add('active');
            }
        }, delay);

        // Увеличиваем задержку перед следующим элементом
        delay += 300; // 1 секунда задержки для линии

        // Активируем круг
        setTimeout(() => {
            if (circles[i]) {
                circles[i].classList.add('active');
            }
        }, delay);

        // Увеличиваем задержку перед следующим элементом
        delay += 300; // 1 секунда задержки для круга

        // Активируем текст этапа
        setTimeout(() => {
            if (texts[i]) {
                texts[i].classList.add('active'); // Можно добавить класс для анимации текста, если требуется
            }
        }, delay);

        // Увеличиваем задержку перед следующим элементом
        delay += 300; // 1 секунда задержки для текста
    }
});


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

$(document).ready(function () {
    // Add smooth scrolling to all links
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

document.addEventListener("DOMContentLoaded", () => {
    const circleItems = document.querySelectorAll(".circle-item");
    const lines = document.querySelectorAll(".line");

    const showElements = () => {
        circleItems.forEach((item, index) => {
            const circle = item.querySelector(".circle");
            const line = lines[index];
            const rect = item.getBoundingClientRect();

            // Проверка, находится ли элемент в поле видимости
            if (rect.top < window.innerHeight && rect.bottom >= 0) {
                circle.classList.add("active");
                if (line) line.classList.add("active");
            }
        });
    };

    // Добавляем обработчик событий для прокрутки и запускаем проверку при загрузке
    window.addEventListener("scroll", showElements);
    showElements();
});

function submitBookingForm(event) {
    event.preventDefault(); // Prevent the form from submitting the default way

    const formData = new FormData(document.getElementById('bookingForm'));

    // Send the data to the server using Fetch API
    fetch('reserve.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Бронирование успешно!');
                closeBookingForm(); // Close the booking form after successful submission
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.log(error);
            console.error('Error:', error);
            alert('Произошла ошибка. Попробуйте еще раз.');
        });
}

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

function editCalendar(reservationId) {
    // Open the modal
    $("#dateSelectionModal").dialog({
        modal: true,
        width: 400
    });

    // Save dates when button clicked
    $("#saveDates").off('click').on('click', function() {
        const checkinDate = $("#checkin").val();
        const checkoutDate = $("#checkout").val();

        // Log data being sent
        console.log({
            id: reservationId,
            checkin: checkinDate,
            checkout: checkoutDate
        });

        // Send the selected dates to the server
        $.post('update_dates.php', {
            id: reservationId,
            checkin: checkinDate,
            checkout: checkoutDate
        }, function(response) {
            console.log(response); // Log the response from the server
            if (response.success) {
                location.reload(); // Reload to see the changes
            } else {
                alert('Ошибка при сохранении дат: ' + response.error);
            }
        }, 'json');
    });
}