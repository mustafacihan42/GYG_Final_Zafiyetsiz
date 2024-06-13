<?php
session_start();
include('database.php');

// Kullanıcı oturumunu doğrula
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: unauthorized.php");
    exit();
}

$role = $_SESSION['role'];

// Hasta silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_patient'])) {
    $patient_id = $_POST['patient_id'];
    $stmt = $db->prepare("DELETE FROM patients WHERE id = :id");
    $stmt->bindValue(':id', $patient_id, SQLITE3_INTEGER);
    $stmt->execute();
    header("Location: hasta_sil.php");
    exit();
}

// Kullanıcı silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
    $stmt->execute();
    header("Location: hasta_sil.php");
    exit();
}

// Tüm hastaları ve kullanıcıları al
function getAllPatients() {
    global $db;
    $stmt = $db->query("SELECT * FROM patients");
    $patients = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $patients[] = $row;
    }
    return $patients;
}

function getAllUsers() {
    global $db;
    $stmt = $db->query("SELECT * FROM users");
    $users = [];
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $users[] = $row;
    }
    return $users;
}

$patients = getAllPatients();
$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Yönetim Sistemi - Hasta Sil</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin: 0;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hastane Yönetim Sistemi - Hasta Sil</h1>
        <h2>Hastalar</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Tanı</th>
                <th>Bölüm</th>
                <th>İşlem</th>
            </tr>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?php echo $patient['id']; ?></td>
                    <td><?php echo $patient['name']; ?></td>
                    <td><?php echo $patient['surname']; ?></td>
                    <td><?php echo $patient['diagnosis']; ?></td>
                    <td><?php echo $patient['department']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                            <input type="submit" name="delete_patient" value="Sil">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Kullanıcılar</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>Rol</th>
                <th>İşlem</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="submit" name="delete_user" value="Sil">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
