<?php
session_start();

// Oturumu sonlandır
session_unset();
session_destroy();

// Kullanıcıyı giriş sayfasına yönlendir
header("Location: login.php");
exit();
?>
<?php
session_start();
session_unset(); // Tüm oturum değişkenlerini temizle
session_destroy(); // Oturumu sonlandır
header("Location: patient_login.php"); // Giriş sayfasına yönlendir
exit();
?>