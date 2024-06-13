<?php
session_start();
include('database.php');

// Kullanıcı oturumunu doğrula
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: unauthorized.php"); // Yetkisiz erişim sayfasına yönlendir
    exit();
}

$role = $_SESSION['role'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Yönetim Sistemi </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
            text-align: center;
            margin-bottom: -60px;
        }
        .category-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .category {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 150px;
            text-align: center;
            text-decoration: none;
            color: #333;
        }
        .category:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hastane Yönetim Sistemi </h1>
        <p>Giriş başarılı. Rol: <?php echo $role; ?></p>
        </div>
    <!-- Kullanıcı bilgisi alt kısmında gösterilecek -->
    <div class="container">
        <p> <?php echo $_SESSION['username']; ?></p>
    </div>
        <div class="category-container">
            <?php if ($role === 'Doktor'): ?>
                <a href="doktor.php" class="category">Doktor </a>
                <a href="hasta_sil.php" class="category">Hasta Sil</a> <!-- Hasta Sil kategorisi eklendi -->
                <a href="kullanici_ekle.php" class="category">Kullanıcı/Hasta Ekle</a>
            <?php elseif ($role === 'Acil'): ?>
                <a href="acil.php" class="category">Acil Tıp </a>
                <a href="kullanici_ekle.php" class="category">Kullanıcı/Hasta Ekle</a>
            <?php elseif ($role === 'Sekreter'): ?>
                <a href="sekreter.php" class="category">Sekreter </a>
                <a href="hasta_sil.php" class="category">Hasta Sil</a> <!-- Hasta Sil kategorisi eklendi -->
                <a href="kullanici_ekle.php" class="category">Kullanıcı/Hasta Ekle</a>
            <?php endif; ?>
            <a href="iletisim.php" class="category">İletişim </a>
            <a href="guncelle.php" class="category">Güncelle </a>
            
            
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .logout-container {
            position: fixed;
            top: 20px;
            right: 20px;
        }
        .category-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 60px; /* Çıkış butonu yerine kaydırma */
        }
        .category {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 150px;
            text-align: center;
            text-decoration: none;
            color: #333;
        }
        .category:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <form action="logout.php" method="post">
            <input type="submit" value="Çıkış Yap">
        </form>
    </div>
    <div class="container">  
        <div class="category-container">
            <!-- Kategori bağlantıları buraya gelecek -->
        </div>
    </div>
</body>
</html>


