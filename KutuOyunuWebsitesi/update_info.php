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

// Veritabanında kullanıcı bilgilerini güncelle
$query = "UPDATE users SET first_name='$fullname', email='$email', gsm_no='$gsm', birth_date='$birthdate' WHERE username='" . $_SESSION['username'] . "'";
if ($mysqli->query($query) === TRUE) {
    // Yönlendirme yaparak profil sayfasına geri dön
    // Session değerlerini güncelle
    $_SESSION['fullname'] = $fullname;
    $_SESSION['email'] = $email;
    $_SESSION['gsm'] = $gsm;
    $_SESSION['birthdate'] = $birthdate;

    header("Location: profile.php");
    exit();
} else {
    echo "Bilgileri güncellemede bir hata oluştu: " . $mysqli->error;
}

$mysqli->close();
?>
