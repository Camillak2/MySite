<?php
// logout.php
session_start();
session_destroy();  // Уничтожение сессии
header('Location: login.php');  // Перенаправление на страницу логина
exit;
?>