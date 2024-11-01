<?php
// Создаем куки, которые будут жить до закрытия браузера
setcookie('admin_access', '1', 0, '/');
header('Location: index.php'); // Перенаправление на главную
exit;
?>