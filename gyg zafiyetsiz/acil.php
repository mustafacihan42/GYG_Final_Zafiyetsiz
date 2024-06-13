<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('database.php');

// Yalnızca acil rolüne sahip kullanıcıların erişimine izin ver
authorizeEmergency();

// Arama işlemi
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $patients = $db->query("SELECT * FROM patients WHERE department = 'Acil' AND (name LIKE '%$search%' OR surname LIKE '%$search%' OR diagnosis LIKE '%$search%')");
} else {
    $patients = $db->query("SELECT * FROM patients WHERE department = 'Acil'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acil Tıp </title>
</head>
<body>
    <h1>Acil Tıp </h1>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Arama...">
        <input type="submit" value="Ara">
    </form>
    <h2>Hastalar</h2>
    <ul>
        <?php while ($patient = $patients->fetchArray()): ?>
            <li><?php echo $patient['name'] . ' ' . $patient['surname'] . ' - ' . $patient['diagnosis']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
