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
        .container {
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
        }
        .admin-form {
          display: flex;
          flex-direction: column;
          width: 300px;
          padding: 20px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }
        .admin-form input {
          padding: 10px;
          margin-bottom: 10px;
          border: 1px solid #ccc;
          border-radius: 3px;
        }
        .admin-form button {
          padding: 10px;
          background-color: #4CAF50;
          color: white;
          border: none;
          border-radius: 3px;
          cursor: pointer;
        }
      </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links
  $("a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault;

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование квартир</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <style>
        .main-photo {
            opacity: 0;
            transition: opacity 0.3s ease-in;
        }
        .main-photo.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    

    <!-- Header -->
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
            <li><a href="#contacts" scroll-behavoir: smooth>Контакты</a></li>
            <li><a href="#map">Карты</a></li>
            <li><a href="#about">О нас</a></li>  
          </ul>
          <div class="search-container">
            <input type="text" placeholder="Поиск квартиры" id="search" onkeyup="searchApartments()"/>
            </div>
        </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <img src="main_photo1.jpg" alt="Main Photo" class="main-photo">
        <div class="apartment-cards">
            <!-- Apartment Card Example -->
            <div class="apartment-card">
                <div class="carousel" data-current-slide="0">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv1photo3.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo2.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo1.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo4.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo5.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo6.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo7.jpg" alt="Двухкомнатная квартира">
                        <img src="kv1photo8.jpg" alt="Двухкомнатная квартира">
                    </div>
                    <div class="carousel-controls">
                        <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                        <button class="next" onclick="nextSlide(this)">&#10095;</button>
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Двухкомнатная квартира</h2>
                    <p>• 64 м2 </p>
                    <p>• ⁠До центра 5 минут на машине, 20 минут пешком.</p>
                    <p>• ⁠Метро "Кремлёвская"</p>
                    <p>• ⁠Комфортное проживание до 10 человек</p>
                    <p>• ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                    <p>• ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p> </p>
                    <p>Адрес: Казань, ул. Широкая, д. 2</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>

            <!-- Repeat for more apartments -->
            <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv2photo1.jpg" alt="Студия">
                        <img src="kv2photo2.jpg" alt="Студия">
                        <img src="kv2photo3.jpg" alt="Студия">
                        <img src="kv2photo4.jpg" alt="Студия">
                        <img src="kv2photo5.jpg" alt="Студия">
                        <img src="kv2photo6.jpg" alt="Студия">
                        <img src="kv2photo7.jpg" alt="Студия">
                        <img src="kv2photo8.jpg" alt="Студия">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Студия в элитном доме</h2>
                    <p>* 64 </p>
                        <p>  * ⁠Исторический центр города. </p>
                            <p>  * ⁠Метро "Площадь Тука"</p>
                                <p> * ⁠Комфортное проживание до 4 человек </p>
                                    <p> * ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                                        <p> * ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Щербаковский переулок, д. 7</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv3photo1.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo2.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo3.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo4.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo5.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo6.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo7.jpg" alt="Однокомнатная квартира">
                        <img src="kv3photo8.jpg" alt="Однокомнатная квартира">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Однокомнатная квартира</h2>
                    <p>* 30 м2</p>
                        <p>* ⁠До центра 20 минут на машине.</p>
                        <p>* ⁠Чистый воздух, реки, озёра, скверы, парки, водопады.</p>
                        <p>* ⁠Комфортное проживание до 5 человек </p>
                        <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Ильгама Шакирова, д. 5</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>

             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv4photo1.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo2.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo3.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo4.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo5.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo6.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo7.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo8.jpg" alt="Двухкомнатная квартира">
                        <img src="kv4photo9.jpg" alt="Двухкомнатная квартира">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Двухкомнатная квартира</h2>
                    <p>* 48 м2 в элитном доме. </p>
                       <p>* ⁠Центр города. 
                        <p>* ⁠Метро "Козья слобода"</p>
                        <p>* ⁠Шикарный вид на город из окна 22 этажа. </p>
                        <p>* ⁠Комфортное проживание до 5 - 6 человек </p>
                        <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Шоссейная, д. 57</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>


             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv5photo1.jpg" alt="Студия">
                        <img src="kv5photo2.jpg" alt="Студия">
                        <img src="kv5photo3.jpg" alt="Студия">
                        <img src="kv5photo4.jpg" alt="Студия">
                        <img src="kv5photo5.jpg" alt="Студия">
                        <img src="kv5photo6.jpg" alt="Студия">
                        <img src="kv5photo7.jpg" alt="Студия">
                        <img src="kv5photo8.jpg" alt="Студия">
                        <img src="kv5photo9.jpg" alt="Студия">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle" onclick="showSlide(0)"></span>
                        <span class="circle" onclick="showSlide(1)"></span>
                        <span class="circle" onclick="showSlide(2)"></span>
                        <span class="circle" onclick="showSlide(3)"></span>
                        <span class="circle" onclick="showSlide(4)"></span>
                        <span class="circle" onclick="showSlide(5)"></span>
                        <span class="circle" onclick="showSlide(6)"></span>
                        <span class="circle" onclick="showSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Студия</h2>
                    <p>* 15 м2</p>
                        <p> * ⁠До центра 15 минут на машине. </p>
                            <p>* ⁠Метро "Северный вокзал"</p>
                                <p>* ⁠Комфортное проживание до 2 человек</p> 
                                    <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Октябрьская, д. 38</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv6photo1.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo2.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo3.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo4.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo5.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo6.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo7.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo8.jpg" alt="Двухкомнатная квартира">
                        <img src="kv6photo9.jpg" alt="Двухкомнатная квартира">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Двухкомнатная квартира</h2>
                    <p>* 65 м2 в элитном доме. </p>
                        <p>* ⁠Исторический центр города.</p> 
                        <p>* ⁠Метро "Площадь Тукая"</p>
                        <p>* ⁠Комфортное проживание до 7 человек </p>
                        <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Щербаковский переулок, д. 7</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv7photo1.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo2.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo3.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo4.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo5.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo6.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo7.jpg" alt="Однокомнатная квартира">
                        <img src="kv7photo8.jpg" alt="Однокомнатная квартира">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Однокомнатная квартира</h2>
                    <p>* 30 м2</p>
                        <p>   * ⁠Центр города. </p>
                            <p> * ⁠Метро "Козья слобода"</p>
                                <p>* ⁠Комфортное проживание до 5 человек </p>
                                    <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Декабристов, д. 89в</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv8photo1.jpg" alt="Студия">
                        <img src="kv8photo2.jpg" alt="Студия">
                        <img src="kv8photo3.jpg" alt="Студия">
                        <img src="kv8photo4.jpg" alt="Студия">
                        <img src="kv8photo5.jpg" alt="Студия">
                        <img src="kv8photo6.jpg" alt="Студия">
                        <img src="kv8photo7.jpg" alt="Студия">
                        <img src="kv8photo8.jpg" alt="Студия">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Студия</h2>
                    <p>* 32 м2</p>
                        <p>* ⁠До центра 10 минут на машине. </p>
                            <p>* ⁠Метро "Северный вокзал",  "Яшьлек"</p>
                                <p>* ⁠Комфортное проживание до 4 человек </p>
                                    <p>* ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                                        <p>* ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Восстания, д. 49</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
             <!-- Repeat for more apartments -->
             <div class="apartment-card">
                <div class="carousel">
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="kv9photo1.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo2.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo3.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo4.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo5.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo6.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo7.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo8.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo9.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo10.jpg" alt="Однокомнатная квартира">
                        <img src="kv9photo11.jpg" alt="Однокомнатная квартира">
                    </div>
                    <div class="carousel-circles">
                        <span class="circle active" onclick="setSlide(0)"></span>
                        <span class="circle" onclick="setSlide(1)"></span>
                        <span class="circle" onclick="setSlide(2)"></span>
                        <span class="circle" onclick="setSlide(3)"></span>
                        <span class="circle" onclick="setSlide(4)"></span>
                        <span class="circle" onclick="setSlide(5)"></span>
                        <span class="circle" onclick="setSlide(6)"></span>
                        <span class="circle" onclick="setSlide(7)"></span>
                    </div>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
                <div class="description">
                    <h2>Однокомнатная квартира</h2>
                    <p>* 40 м2 в элитном доме. </p>
                    <p> * ⁠Исторический центр города. </p>
                    <p>  * ⁠Метро "Площадь Тукая"</p>
                    <p>  * ⁠Комфортное проживание до 3 - 4 человек. </p>
                    <p>  * ⁠Чистое постельное бельё и полотенца для каждого гостя</p>
                    <p>  * ⁠WiFi, TV, фен, утюг, микроволновая печь, холодильник и др.</p>
                    <p>Адрес: Казань, ул. Щербаковский переулок, д. 7</p>
                    <button class="book-now" onclick="showBookingForm()">Забронировать</button>
                </div>
            </div>
            <!-- Add more apartment cards as needed -->
        </div>

        <div id="maps" class="maps-section">
            <!-- Map Embed Example -->
            <iframe 
            src="https://www.google.com/maps/d/embed?mid=YOUR_MAP_ID" 
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </main>
    <script>
        window.onload = function() {
            document.querySelector('.main-photo').classList.add('show');
        };
    </script>
 <button id="admin-mode-toggle" onclick="toggleAdminMode()">Включить режим администратора</button>
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
                <a href="https://www.facebook.com"><img src="avito.png" alt="Facebook"></a>
                <a href="https://www.twitter.com"><img src="whatsap.png" alt="Twitter"></a>
                <a href="https://www.instagram.com"><img src="instagram-icon.png" alt="Instagram"></a>
            </div>
            <div class="contact-info">
                <p>Телефон: <a href="tel:+71234567890">+7 123 456 7890</a></p>
                <p>Email: <a href="mailto:info@apartmentbooking.com">info@apartmentbooking.com</a></p>
                <p><a href="login.php">Вход для администраторов</a></p>
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
                <input type="tel" id="phone" name="phone" required>
                <label for="date">Дата:</label>
                <input type="date" id="date" name="date" required>
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>

