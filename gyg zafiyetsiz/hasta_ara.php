<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Ara</title>
</head>
<body>
    <h1>Hasta Ara</h1>
    <form action="hasta_ara.php" method="GET">
        <label for="hasta_adı">Hasta Adı:</label>
        <input type="text" id="hasta_adı" name="hasta_adı" placeholder="Hasta adını girin">
        <input type="submit" value="Ara">
    </form>

    <?php
    // Veritabanı bağlantısı
    try {
        $db = new SQLite3('hastane.db');
    } catch (Exception $e) {
        die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
    }

    // Hasta adını alma ve sorgu oluşturma
    if (isset($_GET['hasta_adı'])) {
        $hasta_adı = $_GET['hasta_adı'];
        // SQL Injection açığı içeren kod - Sadece hasta adı parametresi alınacak
        $query = "SELECT * FROM patients WHERE name LIKE '%$hasta_adı%'";
        $patients = $db->query($query);
        if (!$patients) {
            die("Sorgu hatası: " . $db->lastErrorMsg());
        }

        // Sonuçları ekrana yazdırma
        while ($patient = $patients->fetchArray()) {
            echo "Hasta Adı: " . $patient['name'] . ", Soyadı: " . $patient['surname'] . ", Tanı: " . $patient['diagnosis'] . "<br>";
        }
    } else {
        echo "Hasta adı belirtilmedi.";
    }
    ?>
</body>
</html>
