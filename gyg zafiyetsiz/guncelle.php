<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

// Kullanıcı güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $old_username = $_POST['old_username'];
    $new_username = $_POST['new_username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Yetkilendirme kontrolü yapılmalı
    if ($_SESSION['username'] !== $old_username) {
        echo "Yetkisiz erişim!";
        exit(); // Yetkisiz erişim olduğunda işlemi sonlandır
    }

    try {
        // Yeni kullanıcı adını ve şifreyi al ve veritabanındaki kaydı güncelle
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Yeni şifreyi hashle
        $stmt = $db->prepare("UPDATE users SET username = :new_username, password = :new_password WHERE username = :old_username");
        $stmt->bindValue(':new_username', $new_username, SQLITE3_TEXT);
        $stmt->bindValue(':new_password', $hashed_password, SQLITE3_TEXT);
        $stmt->bindValue(':old_username', $old_username, SQLITE3_TEXT);
        
        $result = $stmt->execute();

        if ($result) {
            // Oturumu güncelle sadece şifre güncelleyen kullanıcılar için
            $_SESSION['username'] = $new_username;

            echo "Kullanıcı başarıyla güncellendi.";
            header("refresh:3;url=dashboard.php"); // 3 saniye sonra dashboard'a yönlendir
            exit();
        } else {
            echo "Kullanıcı güncellenemedi. Lütfen tekrar deneyin.";
            header("refresh:3;url=dashboard.php"); // 3 saniye sonra dashboard'a yönlendir
        }
    } catch (Exception $e) {
        echo "Hata: " . $e->getMessage();
        header("refresh:3;url=dashboard.php"); // 3 saniye sonra dashboard'a yönlendir
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Güncelleme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container h2 {
            margin-top: 0;
        }
        .form-container input[type="text"], 
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kullanıcı Güncelleme</h1>
        <div class="form-container">
            <form action="" method="post">
                <input type="text" name="old_username" placeholder="Eski Kullanıcı Adı" required>
                <input type="text" name="new_username" placeholder="Yeni Kullanıcı Adı" required>
                <input type="password" name="old_password" placeholder="Eski Şifre" required>
                <input type="password" name="new_password" placeholder="Yeni Şifre" required>
                <input type="submit" name="update_submit" value="Kullanıcı Güncelle">
            </form>
        </div>
    </div>
</body>
</html>
