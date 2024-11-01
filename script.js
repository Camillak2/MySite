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

// Модальное окно для увеличения изображений
const modal = document.createElement('div');
modal.classList.add('modal');
modal.innerHTML = `
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modal-img">
    <a class="prev" onclick="changeModalSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeModalSlide(1)">&#10095;</a>
`;
document.body.appendChild(modal);

let currentModalSlideIndex = 0;
let currentModalCarousel = null;

function openModal(event) {
    const carousel = event.target.closest('.carousel');
    const slides = carousel.querySelectorAll('.carousel-images img');
    const modalImg = document.getElementById('modal-img');
    currentModalSlideIndex = Array.from(slides).indexOf(event.target);
    currentModalCarousel = carousel;
    modal.style.display = 'block';
    modalImg.src = event.target.src;
}

function closeModal() {
    modal.style.display = 'none';
}

function changeModalSlide(direction) {
    const slides = currentModalCarousel.querySelectorAll('.carousel-images img');
    currentModalSlideIndex += direction;

    if (currentModalSlideIndex >= slides.length) {
        currentModalSlideIndex = 0;
    } else if (currentModalSlideIndex < 0) {
        currentModalSlideIndex = slides.length - 1;
    }

    const modalImg = document.getElementById('modal-img');
    modalImg.src = slides[currentModalSlideIndex].src;
}

// Функция поиска квартир
function searchApartments() {
    const input = document.getElementById('search').value.toLowerCase();
    const apartments = document.querySelectorAll('.apartment');
    apartments.forEach(apartment => {
        const title = apartment.querySelector('.apartment-title').textContent.toLowerCase();
        apartment.style.display = title.includes(input) ? '' : 'none';
    });
}

// JavaScript для управления формой бронирования
function showBookingForm() {
    document.getElementById('booking-form').style.display = 'flex'; // Открываем модальное окно
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

function closeBookingForm() {
    document.getElementById('booking-form').style.display = 'none';
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

function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
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
