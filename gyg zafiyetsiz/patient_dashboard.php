<?php
session_start();
include('database.php');

// Oturum süresini 30 dakika olarak ayarlayalım (1800 saniye)
$inactive = 1800; // 30 dakika

if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: patient_login.php");
        exit();
    }
} else {
    $session_life = 0;
}

$_SESSION['timeout'] = time();

// Kullanıcı oturumunu doğrula
if (!isset($_SESSION['patient_id']) || !isset($_SESSION['patient_name'])) {
    header("Location: patient_login.php"); // Hasta giriş yapmamışsa, giriş sayfasına yönlendir
    exit();
}

// Hasta bilgilerini al
$patient_id = $_SESSION['patient_id'];

// Veritabanından hastanın bilgilerini al
$stmt = $db->prepare("SELECT * FROM patients WHERE id = :patient_id");
$stmt->bindValue(':patient_id', $patient_id);
$result = $stmt->execute();
$patient = $result->fetchArray(SQLITE3_ASSOC);

// Veritabanından hasta bilgilerini aldıktan sonra kapat
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Paneli</title>
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
        .patient-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .countdown {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // Sayaç geriye doğru çalışacak şekilde JavaScript kullanarak oturum süresini gösterebiliriz
        function countdown() {
            var seconds = <?php echo $inactive - $session_life; ?>;
            var countdownElement = document.getElementById("countdown");
            var interval = setInterval(function() {
                seconds--;
                if (seconds >= 0) {
                    countdownElement.innerHTML = "Oturum Süresi: " + seconds + " saniye";
                }
                if (seconds <= 0) {
                    clearInterval(interval);
                    window.location.href = "logout.php"; // Oturum süresi dolunca oturumu sonlandır
                }
            }, 1000);
        }
        window.onload = countdown; // Sayaç fonksiyonunu çağır
    </script>
</head>
<body>
    <div class="countdown" id="countdown">Oturum Süresi: <?php echo $inactive - $session_life; ?> saniye</div>
    <div class="container">
        <h1>Hasta Paneli</h1>
        <div class="patient-info">
            <h2>Hasta Bilgileri</h2>
            <p><strong>ID:</strong> <?php echo $patient['id']; ?></p>
            <p><strong>Adı:</strong> <?php echo $patient['name']; ?></p>
            <p><strong>Soyadı:</strong> <?php echo $patient['surname']; ?></p>
            <p><strong>Tanı:</strong> <?php echo $patient['diagnosis']; ?></p>
            <p><strong>Bölüm:</strong> <?php echo $patient['department']; ?></p>
        </div>
        <a href="logout.php">Çıkış Yap</a>
        <a href="sikayet_yaz.php">Düşünceleriniz</a>
    </div>
</body>
</html>
