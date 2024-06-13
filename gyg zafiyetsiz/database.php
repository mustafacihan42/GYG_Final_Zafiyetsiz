<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantısı
try {
    $db = new SQLite3('hastane.db');
} catch (Exception $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

/*
// Kullanıcıların eklenmesi (sadece bir kez çalıştırılmalı, sonra yorum satırına alınabilir)
$db->exec("INSERT INTO users (username, password, role) VALUES ('doktor', '" . password_hash('doktor123', PASSWORD_DEFAULT) . "', 'Doktor')");
$db->exec("INSERT INTO users (username, password, role) VALUES ('acil', '" . password_hash('acil123', PASSWORD_DEFAULT) . "', 'Acil')");
$db->exec("INSERT INTO users (username, password, role) VALUES ('sekreter', '" . password_hash('sekreter123', PASSWORD_DEFAULT) . "', 'Sekreter')");
*/

// Hasta ekleme
function addPatient($name, $surname, $diagnosis, $department) {
    global $db;
    $stmt = $db->prepare("INSERT INTO patients (name, surname, diagnosis, department) VALUES (:name, :surname, :diagnosis, :department)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':surname', $surname, SQLITE3_TEXT);
    $stmt->bindValue(':diagnosis', $diagnosis, SQLITE3_TEXT);
    $stmt->bindValue(':department', $department, SQLITE3_TEXT);
    $stmt->execute();
}

// Kullanıcı doğrulama
function authenticateUser($username, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['username'] = $username; // Oturum değişkeni ayarla
        $_SESSION['role'] = $result['role']; // Oturum değişkeni ayarla
        return $result['role'];
    }
    return false;
}

// Hasta doğrulama
function authenticatePatient($name, $surname) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM patients WHERE name = :name AND surname = :surname");
    if (!$stmt) {
        die("Sorgu hazırlanamadı: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':surname', $surname, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    if ($result) {
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['role'] = 'Hasta';
        return true;
    }
    return false;
}

// Kullanıcı oturumunu doğrula
function checkUserSession() {
    if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
        return $_SESSION['role'];
    }
    return false;
}

// Yetkilendirme: Sadece giriş yapmış kullanıcılar erişebilir
function authorizeUser() {
    if (!checkUserSession()) {
        header("Location: unauthorized.php"); // Kullanıcıyı yetkisiz erişim sayfasına yönlendir
        exit();
    }
}

// Yetkilendirme: Sadece doktor rolüne sahip kullanıcılar erişebilir
function authorizeDoctor() {
    if (checkUserSession() !== 'Doktor') {
        header("Location: unauthorized.php"); // Yetkisiz erişim sayfasına yönlendir
        exit();
    }
}

// Yetkilendirme: Sadece acil tıp rolüne sahip kullanıcılar erişebilir
function authorizeEmergency() {
    if (checkUserSession() !== 'Acil') {
        header("Location: unauthorized.php"); // Yetkisiz erişim sayfasına yönlendir
        exit();
    }
}

// Yetkilendirme: Sadece sekreter rolüne sahip kullanıcılar erişebilir
function authorizeSecretary() {
    if (checkUserSession() !== 'Sekreter') {
        header("Location: unauthorized.php"); // Yetkisiz erişim sayfasına yönlendir
        exit();
    }
}

// Yetkilendirme: Sadece admin rolüne sahip kullanıcılar erişebilir
function authorizeAdmin() {
    if (checkUserSession() !== 'Admin') {
        header("Location: unauthorized.php"); // Yetkisiz erişim sayfasına yönlendir
        exit();
    }
}

// Kullanıcının rolünü doktor olarak güncelleyen işlev
function updateDoctorRights($username) {
    global $db;
    $stmt = $db->prepare('UPDATE users SET role = "Doktor" WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->execute();
}
?>
