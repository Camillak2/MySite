<?php
session_start();
session_unset(); // Удаляем все сессионные переменные
session_destroy(); // Уничтожаем сессию
header('Location: index.php'); // Перенаправление на главную страницу
exit;
?>