<?php
session_start();
session_unset();
session_destroy();
session_start(); // Yeni turum başlat
session_regenerate_id(true); // Yeni oturum kimliği 

header("Location: login_page.php");
exit();
?>
