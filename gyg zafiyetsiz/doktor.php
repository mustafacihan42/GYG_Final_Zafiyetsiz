<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('database.php');

// Yalnızca doktor rolüne sahip kullanıcıların erişimine izin ver
authorizeDoctor();

if (isset($_GET['search'])) {
    // Girişleri güvenli hale getir
    $search = htmlspecialchars($_GET['search']);
    // Sadece ad parametresi alınacak, surname'a gerek yok
    $query = "SELECT * FROM patients WHERE name LIKE :search";
    // Parametreli sorguyu hazırla
    $stmt = $db->prepare($query);
    // Parametreyi bağla
    $stmt->bindValue(':search', '%' . $search . '%', SQLITE3_TEXT);
    // Sorguyu çalıştır
    $patients = $stmt->execute();
    if (!$patients) {
        die("Sorgu hatası: " . $db->lastErrorMsg());
    }
} else {
    // Varsayılan olarak tüm hastaları getir
    $query = "SELECT * FROM patients WHERE department = 'Doktor'";
    $patients = $db->query($query);
    if (!$patients) {
        die("Sorgu hatası: " . $db->lastErrorMsg());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doktor</title>
</head>
<body>
    <h1>Doktor</h1>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Arama...">
        <input type="submit" value="Ara">
    </form>
    <h2>Hastalar</h2>
    <ul id="patient-list">
        <?php while ($patient = $patients->fetchArray()): ?>
            <li onclick="selectPatient(<?php echo $patient['id']; ?>)"><?php echo $patient['name'] . ' ' . $patient['surname'] . ' - ' . $patient['diagnosis']; ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Mesajlaşma</h2>
    <div id="message-area"></div>
    <form id="message-form">
        <input type="hidden" id="receiver_id" name="receiver_id">
        <textarea id="message" name="message" placeholder="Mesajınızı yazın..."></textarea>
        <button type="button" onclick="sendMessage()">Gönder</button>
    </form>

    <script>
        let selectedPatientId = null;

        function selectPatient(patientId) {
            selectedPatientId = patientId;
            document.getElementById('receiver_id').value = patientId;
            fetchMessages();
        }

        function sendMessage() {
            const message = document.getElementById('message').value;
            const receiver_id = document.getElementById('receiver_id').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        fetchMessages();
                        document.getElementById('message').value = '';
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send("receiver_id=" + receiver_id + "&message=" + encodeURIComponent(message));
        }

        function fetchMessages() {
            if (!selectedPatientId) return;

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_messages.php?other_user_id=" + selectedPatientId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        const messageArea = document.getElementById('message-area');
                        messageArea.innerHTML = '';
                        response.messages.forEach(msg => {
                            const messageDiv = document.createElement('div');
                            messageDiv.textContent = msg.message;
                            messageArea.appendChild(messageDiv);
                        });
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send();
        }

        setInterval(fetchMessages, 5000); // Her 5 saniyede bir mesajları güncelle
    </script>
</body>
</html>
