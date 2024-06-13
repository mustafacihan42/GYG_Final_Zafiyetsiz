<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = authenticateUser($username, $password);
    if ($role) {
        // Giriş başarılı, kullanıcıya uygun sayfayı yönlendirme yapabiliriz
        header("Location: dashboard.php");
        exit();
    } else {
        $errorMessage = "Giriş başarısız. Kullanıcı adı veya şifre yanlış.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Yönetim Sistemi - Giriş</title>
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
        .login-container input[type="text"],
        .login-container input[type="password"] {
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
        .patient-login-link {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Hastane Personel - Giriş</h1>
        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" id="username" name="username" placeholder="Kullanıcı Adı" required><br><br>
            <input type="password" id="password" name="password" placeholder="Şifre" required><br><br>
            <input type="submit" value="Giriş Yap">
        </form>
        <a class="patient-login-link" href="patient_login.php">Hasta girişi için tıklayınız</a>
    </div>
</body>
</html>
