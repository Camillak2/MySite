<?php
// Устанавливаем cookie на 30 дней
setcookie('admin_access', '1', time() + (30 * 24 * 60 * 60), '/'); // Устанавливаем cookie
echo "Cookie установлен!"; // Сообщение об успешной установке
?>