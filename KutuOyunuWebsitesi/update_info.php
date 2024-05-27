<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// Formdan gelen verileri al
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$gsm = $_POST['gsm'];
$birthdate = $_POST['birthdate'];

// Tarih formatını dönüştür
$birthdate = DateTime::createFromFormat('d.m.Y', $birthdate)->format('Y-m-d');

// Veritabanında kullanıcı bilgilerini güncellemek için prepared statement kullanın
$stmt = $mysqli->prepare("UPDATE users SET first_name=?, email=?, gsm_no=?, birth_date=? WHERE username=?");
$stmt->bind_param("sssss", $fullname, $email, $gsm, $birthdate, $_SESSION['username']);

if ($stmt->execute()) {
    // Yönlendirme yaparak profil sayfasına geri dön
    // Session değerlerini güncelle
    $_SESSION['fullname'] = $fullname;
    $_SESSION['email'] = $email;
    $_SESSION['gsm'] = $gsm;
    $_SESSION['birthdate'] = $birthdate;

    header("Location: profile.php");
    exit();
} else {
    echo "Bilgileri güncellemede bir hata oluştu: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
