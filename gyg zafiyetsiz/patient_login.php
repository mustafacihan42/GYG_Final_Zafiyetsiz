<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen ad ve soyadı al
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    // Hasta giriş işlemi
    $stmt = $db->prepare("SELECT * FROM patients WHERE name = :name AND surname = :surname");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':surname', $surname);
    $result = $stmt->execute();

    // Hasta var mı kontrol et
    if ($patient = $result->fetchArray(SQLITE3_ASSOC)) {
        // Başarılı giriş, oturumu başlat ve ana sayfaya yönlendir
        $_SESSION['patient_id'] = $patient['id'];
        $_SESSION['patient_name'] = $patient['name'];
        header("Location: patient_dashboard.php");
        exit();
    } else {
        // Başarısız giriş, hata mesajı göster
        $errorMessage = "Hasta girişi başarısız. Lütfen doğru ad ve soyadı girin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Giriş Ekranı</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Hasta Giriş Ekranı</h1>
        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form action="patient_login.php" method="post">
            <input type="text" id="name" name="name" placeholder="Ad" required><br><br>
            <input type="text" id="surname" name="surname" placeholder="Soyad" required><br><br>
            <input type="submit" value="Giriş Yap">
        </form>
    </div>
</body>
</html>
