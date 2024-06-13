<?php
session_start();
include('database.php'); // Veritabanı bağlantısı ve fonksiyonları içeren dosyayı dahil et

// Kullanıcı girişlerini güvenli hale getirme fonksiyonu
function secureInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Şikayet yazma formu gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complaint"])) {
    // Şikayet verisini güvenli hale getirerek XSS saldırılarına karşı koruma
    $complaint = secureInput($_POST["complaint"]);

    // Şikayeti işleme ve güvenli bir şekilde görüntüleme
    echo "<p>Şikayetiniz başarıyla alındı:</p>";
    echo "<p>" . $complaint . "</p>";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şikayet Yaz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Şikayet Yaz</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="complaint" placeholder="Şikayetinizi buraya yazın..." required>
            <br>
            <input type="submit" value="Gönder">
        </form>
    </div>
</body>
</html>
