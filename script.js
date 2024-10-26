// JavaScript для управления каруселью изображений
function showSlide(carousel, index) {
    const slides = carousel.querySelectorAll('.carousel-images img');
    const circles = carousel.querySelectorAll('.circle');
    let currentSlide = parseInt(carousel.dataset.currentSlide) || 0;

    if (index >= slides.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = index;
    }

    carousel.dataset.currentSlide = currentSlide;

    slides.forEach((slide, i) => {
        slide.style.transform = `translateX(-${currentSlide * 100}%)`;
    });

    circles.forEach((circle, i) => {
        circle.classList.remove('active');
        if (i == currentSlide) {
            circle.classList.add('active');
        }
    });
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

function setSlide(circle, index) {
    const carousel = circle.closest('.carousel');
    showSlide(carousel, index);
}

// Инициализация каруселей при загрузке страницы
document.addEventListener('DOMContentLoaded', function () {
    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach(carousel => {
        showSlide(carousel, 0);

        // Добавим обработчики событий для увеличения изображений
        const images = carousel.querySelectorAll('.carousel-images img');
        images.forEach(image => {
            image.addEventListener('click', openModal);
        });

        const circles = carousel.querySelectorAll('.circle');
        circles.forEach((circle, index) => {
            circle.addEventListener('click', () => setSlide(circle, index));
        });
    });
});


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
    document.getElementById('booking-form').style.display = 'flex';
}

function closeBookingForm() {
    document.getElementById('booking-form').style.display }


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
        dayDiv.onclick = function() {
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

  