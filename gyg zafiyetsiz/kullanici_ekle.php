<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

authorizeDoctor();
authorizeEmergency();
authorizeSecretary();

// Kullanıcı veya hasta ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['personel_submit'])) {
                // Personel ekleme işlemi
                $username = $_POST['username'];
                $password = $_POST['password']; 
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Şifreyi hashle
                $role = $_POST['role'];
        
                $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
                $stmt->bindValue(':username', $username);
                $stmt->bindValue(':password', $hashed_password); // Hashlenmiş şifreyi kullan
                $stmt->bindValue(':role', $role);
                $stmt->execute();

        $message = "Yeni personel başarıyla eklendi.";
    } elseif (isset($_POST['hasta_submit'])) {
        // Hasta ekleme işlemi
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $diagnosis = $_POST['diagnosis'];
        $department = $_POST['department'];

        $stmt = $db->prepare("INSERT INTO patients (name, surname, diagnosis, department) VALUES (:name, :surname, :diagnosis, :department)");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':surname', $surname);
        $stmt->bindValue(':diagnosis', $diagnosis);
        $stmt->bindValue(':department', $department);
        $stmt->execute();

        $message = "Yeni hasta başarıyla eklendi.";
    } elseif (isset($_POST['delete_user_submit'])) {
        // Kullanıcı silme işlemi
        $username_to_delete = $_POST['username_to_delete'];
        
        // Kullanıcıyı veritabanından silme
        $stmt = $db->prepare("DELETE FROM users WHERE username = :username_to_delete");
        $stmt->bindValue(':username_to_delete', $username_to_delete);
        $stmt->execute();

        $message = "Kullanıcı başarıyla silindi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı ve Hasta Ekleme</title>
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
        .form-container input[type="password"],
        .form-container select {
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
        <h1>Kullanıcı ve Hasta Ekleme</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="form-container">
            <h2>Yeni Personel Ekle</h2>
            <form action="" method="post">
                <input type="text" name="username" placeholder="Kullanıcı Adı" required>
                <input type="password" name="password" placeholder="Şifre" required>
                <select name="role" required>
                    <option value="" disabled selected>Rol Seçin</option>
                    <option value="Doktor">Doktor</option>
                    <option value="Acil">Acil Tıp</option>
                    <option value="Sekreter">Sekreter</option>
                </select>
                <input type="submit" name="personel_submit" value="Personel Ekle">
            </form>
        </div>
        <div class="form-container">
            <h2>Yeni Hasta Ekle</h2>
            <form action="" method="post">
                <input type="text" name="name" placeholder="Ad" required>
                <input type="text" name="surname" placeholder="Soyad" required>
                <input type="text" name="diagnosis" placeholder="Hastalık Teşhisi" required>
                <select name="department" required>
                    <option value="" disabled selected>Yönlendirilecek Birim Seçin</option>
                    <option value="Doktor">Doktor</option>
                    <option value="Acil">Acil Tıp</option>
                    <option value="Sekreter">Sekreter</option>
                </select>
                <input type="submit" name="hasta_submit" value="Hasta Ekle">
            </form>
        </div>
    </div>
</body>
</html>

