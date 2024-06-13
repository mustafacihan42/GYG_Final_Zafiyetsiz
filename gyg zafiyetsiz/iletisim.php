<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

authorizeUser(); // Giriş yapılmış kullanıcı yetkilendirmesi

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim </title>
    <style>
        .container {
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>İletişim </h1>
        <div class="search-container">
            <input type="text" placeholder="Arama yap..." style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
        </div>
        <p>Merhaba</p>
    </div>
</body>
</html>
